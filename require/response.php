<?php

class Response
{
    /**
     * @var array
     */
    private $response;
    /**
     * @var string
     */
    private $type;
    /**
     * @var bool
     */
    private $unicode;
    const typeArray = ['json'];

    function __construct($type = 'JSON', $unicode = false)
    {
        $this->type = in_array(strtolower($type), $this::typeArray) ? strtolower($type) : 'json';
        $this->unicode = $unicode ? null : JSON_UNESCAPED_UNICODE;
        $this->response = [
            'code' => 0
        ];
    }

    function code($code = 0)
    {
        $this->response['code'] = $code;
        return $this;
    }

    function msg($msg)
    {
        $this->response['msg'] = $msg;
        return $this;
    }

    function data($data)
    {
        $this->response['data'] = $data;
        return $this;
    }

    function get($return = true)
    {
        $action = ($return ? 'get' : '') . strtoupper($this->type);
        return $this->{$action}();
    }

    function getJSON()
    {
        header('Content-Type:text/json; charset=utf-8');
        return json_encode($this->response, $this->unicode);
    }

    function JSON()
    {
        header('Content-Type:text/json; charset=utf-8');
        exit(json_encode($this->response, $this->unicode));
    }

    function getXML()
    {
        header('Content-Type:text/xml; charset=utf-8');
        return xml_encode($this->response);
    }

    function XML()
    {
        header('Content-Type:text/xml; charset=utf-8');
        exit(xml_encode($this->response));
    }

    function clear()
    {
        unset($this->response);
        $this->response = [
            'code' => 0
        ];
        return $this;
    }
}

function xml_encode($data, $root = true)
{
    if ($root) $root = (is_string($root) && !empty($root)) ? $root : 'response';
    $xml = $root ? "<$root>" : '';
    foreach ($data as $key => $val) {
        $value = is_array($val) ? xml_encode($val, false) : $val;
        $xml .= "<$key>$value</$key>";
    }
    $xml .= $root ? "</$root>" : '';
    return $xml;
}