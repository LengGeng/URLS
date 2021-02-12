<?php
// 检查配置文件,不存在运行安装程序
if (!file_exists("config.php")) {
    header("Location: install");
    exit;
}
// 引入必要的依赖
require_once("config.php");
require_once('require/require.php');
// 判断是否有跳转请求
if (isset($_GET['id'])) {
    $url = new url();
    // 尝试跳转
    if ($jump_url = $url->jump($_GET['id'])) {
        // 重定向至目标网址
        header("Location: $jump_url");
        exit;
    } else {
        echo "<script>alert('跳转失败,urlCode不存在!');</script>";
    }
}
require_once "static/html/index/index.tpl";