<?php

class DB
{
    /**
     * @var PDO
     */
    private $dao;

    /**
     * DB constructor.
     * @param array $config 配置参数
     */
    function __construct(array $config)
    {
        $this->content($config);
    }

    /**
     * 数据库连接
     * @param array $config 数据库配置
     */
    function content(array $config)
    {
        $dsn = sprintf(
            "%s:host=%s;port=%s;dbname=%s;charset=%s",
            isset($config['dbms']) ? $config['dbms'] : 'mysql',
            isset($config['host']) ? $config['host'] : '127.0.0.1',
            isset($config['port']) ? $config['port'] : '3306',
            $config['dbname'],
            isset($config['charset']) ? $config['charset'] : 'utf8'
        );
        try {
            $this->dao = new PDO($dsn, $config['user'], $config['password']);
            $this->dao->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            echo '<br />';
            echo iconv('gbk', 'utf-8', $e->getMessage());
            die();
        }
    }

    /**
     * 初始化数据库结构,创建表
     */
    function init_tab()
    {
        // 网址表
        $this->dao->exec("CREATE TABLE IF NOT EXISTS `my_urls`
(
    `id`     int(10) UNSIGNED                                NOT NULL auto_increment,
    `url`    text CHARACTER SET utf8 COLLATE utf8_bin        NOT NULL,
    `code`   varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL default '',
    `alias`  varchar(40) CHARACTER SET utf8 COLLATE utf8_bin,
    `create` datetime                                        NOT NULL,
    `count`  int                                             NOT NULL default 0,
    `ip`     varchar(20) CHARACTER SET utf8 COLLATE utf8_bin,
    `ua`     varchar(256) CHARACTER SET utf8 COLLATE utf8_bin,
    PRIMARY KEY (id),
    UNIQUE KEY code (code)
)");
    }

    /**
     * query()
     * @param string $sql SQL语句
     * @return false|PDOStatement
     */
    function query(string $sql)
    {
        return $this->dao->query($sql);
    }

    /**
     * exec()
     * @param string $sql SQL语句
     * @return false|int
     */
    function exec(string $sql)
    {
        return $this->dao->exec($sql);
    }

    /**
     * lastInsertId 最后插入数据的ID
     * @return string lastInsertId
     */
    function lastInsertId()
    {
        return $this->dao->lastInsertId();
    }

    /**
     * 预处理语句
     * @param string $sql
     * @param array $vars
     * @return bool
     */
    function execute(string $sql, $vars)
    {
        $pre = $this->dao->prepare($sql);
        $vars = is_array($vars) ? $vars : [$vars];
        return $pre->execute($vars);
    }

    /**
     * 相同SQL不同数据的批处理(支持事务处理)
     * @param string $sql SQL语句
     * @param array $varsArray 数据数组
     */
    function executes(string $sql, array $varsArray)
    {
        try {
            $this->dao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dao->beginTransaction();
            $pre = $this->dao->prepare($sql);
            foreach ($varsArray as $vars) {
                $vars = is_array($vars) ? $vars : [$vars];
                $pre->execute($vars);
            }
            $this->dao->commit();
        } catch (Exception $e) {
            $this->dao->rollBack();
            echo $e->getMessage();
        }
    }

    /**
     * 开启事务
     */
    function beginTransaction()
    {
        $this->dao->beginTransaction();
    }

    /**
     * 提交事务
     */
    function commit()
    {
        $this->dao->commit();
    }

    /**
     * 事务回滚
     */
    function rollBack()
    {
        $this->dao->rollBack();
    }
}

//function __test()
//{
//    $conf = [
//        'dbname' => 'urls',
//        'user' => 'root',
//        'password' => ''
//    ];
//    $db = new DB($conf);
//    $query = $db->query('select * from my_urls');
//    print_r($query->fetchAll());
//}
//__test();
