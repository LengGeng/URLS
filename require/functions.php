<?php

// 获取配置信息
function get_info($info)
{
    global $config;
    return $config[$info];
}

// 获取程序所在路径
function get_uri()
{
    global $config;
    // 获取传输协议
    $url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    // 获取域名
    $url .= $_SERVER['HTTP_HOST'];
    // 获取程序所在路径
    $url .= $config['path'];
    if (substr($url, strlen($url) - 1) != '/') $url .= '/';
    $config['url'] = $url;
    return $url;
}

// 检查是否是管理员登录
function is_admin_login()
{
    if (@$_SESSION['admin'] == 1) {
        return true;
    }
    return false;
}
