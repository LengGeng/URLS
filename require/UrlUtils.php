<?php

class UrlUtils
{

    /**
     * 获取用户 IP
     * @return mixed|string
     */
    static public function get_ip()
    {
        $ip = '0.0.0.0';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        } else if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        } else if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (!empty($_SERVER['HTTP_FORWARDED'])) {
            $ip = $_SERVER['HTTP_FORWARDED'];
        } else if (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else if (!empty($_SERVER['HTTP_VIA'])) {
            $ip = $_SERVER['HTTP_VIA '];
        }
        return $ip;
    }

    /**
     * 获取用户 UserAgent
     * @return mixed|string
     */
    static public function get_ua()
    {
        $ua = 'N/A';
        if (!empty($_SERVER['HTTP_USER_AGENT'])) $ua = $_SERVER['HTTP_USER_AGENT'];
        return $ua;
    }

    /**
     * 返回网站域名dns
     * @return mixed
     */
    static public function get_hostname()
    {
        $data = parse_url(SITE_URL);

        return $data['host'];
    }

    /**
     * 返回网站顶级域名dns
     * @return mixed
     */
    static public function get_domain()
    {
        $hostname = get_hostname();

        preg_match("/\.([^\/]+)/", $hostname, $domain);

        return $domain[1];
    }

}
