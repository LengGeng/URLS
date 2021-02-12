<?php

// 引入必要的依赖
require_once('../config.php');
require_once('../require.php');
$url = new url();

// 默认响应信息
$opt = [
    'success' => 'false',
    'code' => 0,
];
// 获取请求参数
$request_arr = json_decode(file_get_contents('php://input'), true);
$shorten_url = $request_arr['url'];
if (!$shorten_url) {
    $shorten_url = $_POST["url"];
}
if (isset($shorten_url)) {
    // 添加 HTTP 协议前缀
    if (!strstr($shorten_url, 'http://') && !strstr($shorten_url, 'https:')) $shorten_url = 'http://' . $shorten_url;
    // 检测网址格式是否正确
    $is_link = preg_match('(http(|s)://([\w-]+\.)+[\w-]+(/)?)', $shorten_url);
    // 判断条件
    if ($shorten_url != '' && !strstr($shorten_url, $_SERVER['HTTP_HOST']) && $is_link) {
        $opt['success'] = 'true';
        $opt['code'] = 1;
        $opt['content']['url'] = $url->set_url($shorten_url)['url_code'];
    } else if (strstr($shorten_url, $_SERVER['HTTP_HOST'])) {
        $opt['code'] = 2;
        $opt['msg'] = '链接已经是短地址了!';
    } else if (!$is_link) {
        $opt['msg'] = '请输入正确格式的网址!';
    }
} else {
    $opt['code'] = -1;
    $opt['msg'] = '请求参数错误!';
}

echo json_encode($opt, JSON_UNESCAPED_UNICODE);
