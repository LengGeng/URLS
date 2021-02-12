<?php
// 获取请求参数
$request_arr = json_decode(file_get_contents('php://input'), true);

if ($request_arr && isset($request_arr["url"])) {
    $shorten_url = $request_arr['url'];
    // 默认响应信息
    $opt = [
        'success' => 'false',
        'code' => 0,
    ];

    // 检测网址是否为空
    if (strlen($shorten_url) == 0) {
        $opt['msg'] = '调用参数不能为空';
        echo json_encode($opt, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 添加 HTTP 协议前缀
    if (!strstr($shorten_url, 'http://') && !strstr($shorten_url, 'https:')) $shorten_url = 'http://' . $shorten_url;
    if (strstr($shorten_url, $_SERVER['HTTP_HOST']) && $shorten_url != 'http://' . $_SERVER['HTTP_HOST']) {
        $opt['code'] = 2;
        $opt['msg'] = '链接已经是短地址了!';
        echo json_encode($opt, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 检测网址格式
    $type = match_type($shorten_url);
    if (!$type) {
        $opt['msg'] = '请输入正确格式的网址!';
        echo json_encode($opt, JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        $opt['content']['type'] = $type;
    }

    // 引入必要的依赖
    require_once('../config.php');
    require_once('../require/require.php');
    $url = new url();
    $result = $url->set_url($shorten_url);
    if ($result) {
        $opt['success'] = 'true';
        $opt['code'] = 1;
        $opt['content']['url'] = $_SERVER['HTTP_REFERER'] . $result['url_code'];
    }
    echo json_encode($opt, JSON_UNESCAPED_UNICODE);
    exit;
} else {
    require_once('../static/html/api/api.html');
}


function match_type($url)
{
    $host_match = '/^(https?:\/\/)?([^\/]+)/i';
    $url_match = '(^([a-zA-Z0-9][a-zA-Z0-9\-]{0,62}\.)+([a-zA-Z]+)(/.+)?)';
    $ip_match = '(^((25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)(:\d+)?))$)';
    $dns_match = '(^([a-zA-Z0-9][a-zA-Z0-9\-]{0,62}\.)+([a-zA-Z]+(:\d+)?)$)';
    preg_match($host_match, $url, $extract);
    $host = $extract[2];
    if ($host) {
        if (preg_match($ip_match, $host)) {
            return 'ip';
        } else if (preg_match($dns_match, $host)) {
            return 'dns';
        }
    }
}