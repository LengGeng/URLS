<?php
include_once 'DB.php';

class URL
{
    private $db;
    private $check_repeat_url = true;
    private $size = 5;
    private $url_encode_fun = 'random_md5_encode';

    /**
     * URL constructor.
     */
    function __construct()
    {
        global $db;
        $this->db = $db;
    }

    /**
     * 对URL进行缩短
     * @param string $url 要进行缩短的URL
     * @return array 短URL编码以及执行信息
     */
    function set_url(string $url): array
    {
        if ($this->check_repeat_url) {
            $result = $this->get_by_url($url);
            if ($result) {
                return [
                    'success' => true,
                    'code' => 2,
                    'url_code' => $result['code'],
                ];
            }
        }
        $url_code = $this->{$this->url_encode_fun}($url);
        return [
            'success' => true,
            'code' => 1,
            'url_code' => $url_code,
        ];
    }

    /**
     * 通过短域名code查找url,用于跳转
     * @param string $code 短域名code
     * @return bool|mixed url 没用返回false
     */
    function get_url(string $code)
    {
        $result = $this->get_by_code($code);
        if ($result) {
            return $result['url'];
        } else {
            return false;
        }
    }

    /**
     * 通过短域名进行跳转,count++
     * @param string $url_code 短域名
     * @return bool|mixed 跳转url
     */
    function jump(string $url_code)
    {
        $result = $this->get_by_code($url_code);
        if ($result) {
            $this->count_next($result['id']);
            return $result['url'];
        }
        return false;

    }

    /**
     * url跳转数(count)+1
     * @param int $id 数据id
     * @return bool 执行状态
     */
    function count_next(int $id): bool
    {
        return $this->db->execute("update my_urls set `count`=`count`+1 where `id`=?", [$id]);
    }

    /**
     * 通过url的md5随机生成指定位数的URL_ID编码,会检查是否重复
     * @param string $url
     * @return string
     */
    function random_md5_encode(string $url)
    {
        // 生成url的md5
        $md5 = md5($url);
        // 随机抽取 MD5 中的字符作为 ID
        $url_code = '';
        for ($i = 0; $i < $this->size; $i++) {
            $rand_id = rand(0, strlen($md5) - 1);
            $url_code .= $md5[$rand_id];
        }
        // 检查 url_code 是否存在
        if ($this->has_code($url_code)) {
            return $this->random_md5_encode($url);
        } else {
            $this->db->execute("insert into my_urls (`url`,`code`,`create`) VALUES (?,?,NOW())", [$url, $url_code]);
            return $url_code;
        }
    }

    /**
     * 通过将MYSQL自增ID进行编码生成URL_ID编码,高并发可能存在问题(可能)
     * @param string $url
     * @return string
     */
    function base_encode(string $url)
    {
        $er = $this->db->execute("insert into my_urls (`url`,`code`,`create`) VALUES (?,?,NOW())", [$url, substr(md5($url), 8, 16)]);
        var_dump($er);
        $id = $this->db->lastInsertId();
        $url_code = $this->custom_encode($id);
        $this->db->execute("update my_urls set `code`=? where `id`=?", [$url_code, $id]);
        return $url_code;
    }

    /**
     * 对数字进行自定义编码
     * @param int $number 要进行编码的数字
     * @return string  编码后的字符串
     */
    function custom_encode(int $number): string
    {
        $out = "";
        $codes = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
//        $number_array = range(0, 9); // 生成一个0到9范围的数组
//        $abc = range('a', 'z');     // 生成一个a到z范围的数组
//        $big_abc = range('A', 'Z'); // 生成一A到Z范围的数组
//        $codes = ((array_merge($number_array, $abc, $big_abc)));
        $len = strlen($codes);  // 62
        while ($number >= $len) {
            $key = $number % $len;
            $number = floor($number / $len) - 1;
            $out = $codes{$key} . $out;
        }
        return ($codes{(int)$number} . $out);
    }

    /**
     * 将自定义编码后的字符解码成数字
     * @param string $code 要解码的字符串
     * @return int 解码后的数字
     */
    function custom_decode(string $code): int
    {
        // kk
        $codes = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codes_len = strlen($codes);
        $number = 0;
        $len = strlen($code);
        $index = 0;
        while ($index < $len) {
            $pos = strpos($codes, $code{$index}) + 1;
            $index++;
            $number += $pos * ($codes_len ** ($len - $index));
        }
        return --$number;

    }

    /**
     * 获取所有URL数据
     * @return array URL数据
     */
    function getAll(): array
    {
        $result = $this->db->query('select * from my_urls');
        return $result->fetchAll();
    }

    /**
     * 通过 url 查找数据
     * @param string $url url
     * @return array 第一条URL数据
     */
    function get_by_url(string $url)
    {
        $result = $this->db->query("select * from my_urls where url='$url'");
        return $result->fetch();
    }

    /**
     * 通过短域名 code 查找数据
     * @param string $code 短域名code
     * @return array 第一条URL数据
     */
    function get_by_code(string $code)
    {
        $result = $this->db->query("select * from my_urls where code='$code'");
        return $result->fetch();
    }

    /**
     * 检查短域名code是否存在
     * @param string $code 短域名code
     * @return boolean
     */
    function has_code(string $code)
    {
        $result = $this->db->query("select * from my_urls where code='$code'");
        return $result->fetch() != false;
    }

    function delete(int $id)
    {
        $this->db->execute("DELETE FROM `my_urls` WHERE id =?", [$id]);
    }

    function deletes(array $ids)
    {
        $this->db->executes("DELETE FROM `my_urls` WHERE id =?", $ids);
    }
}