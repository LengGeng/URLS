<?php
// 引入配置文件
require_once(dirname(__FILE__) . '/../config.php');
// 引入域名缩址库
require_once(dirname(__FILE__) . '/functions.php');
require_once(dirname(__FILE__) . '/DB.php');
require_once(dirname(__FILE__) . '/URL.php');
// 初始化数据库
global $db;
$db = new DB($config);