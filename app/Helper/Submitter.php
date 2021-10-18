<?php


namespace App\Helper;

use function header;
use function json_encode;

class Submitter
{
    private const ACTION_ALERT = "ACTION_ALERT";
    private const REDIRECT = "REDIRECT";
    private const REFRESH = "REFRESH";
    private array $response;

    public function __construct(bool $status, string $msg)
    {
        $this->response = ["status" => $status, "msg" => $msg];
        return $this;
    }

    public static function toastRedirect($msg, $uri = "", $color = "success", $data = [], $successParam = false)
    {
        $response = new self(true, $msg);
        $response->setDataAttribute($data);
        return $response->actionAlert("toastr", $uri, $color)->send($successParam);
    }

    public static function swalRedirect($msg, $uri = "", $color = "success", $data = [])
    {
        $response = new self(true, $msg);
        $response->setDataAttribute($data);
        return $response->actionAlert("swal", $uri, $color)->send();
    }

    public static function refresh()
    {
        return (new self(true, ""))->setAction(self::REFRESH)->send();
    }

    public static function json($data)
    {
        header("Content-type application/json");
        return json_encode($data);
    }

    public static function alert($msg,$color = 'success',$mode = 'toastr',$data = [])
    {
        $response = new self(true,$msg);
        $response->setAction('ALERT');
        $response->setMode($mode);
        $response->setDataAttribute($data);
        $response->setColor($color);
        return $response->send();
    }

    public function send($successParam = false)
    {
        if ($successParam) {
            $this->response["success"] = $this->response["status"];
        }
        return json_encode($this->response);
    }

    public function actionAlert($mode, $uri, $color)
    {
        $this->setMode($mode);
        $this->setColor($color);
        $this->setUri($uri);
        $this->setAction(self::ACTION_ALERT);
        return $this;
    }

    public function setMode($mode)
    {
        $this->response["mode"] = $mode;
        return $this;
    }

    public function setColor($color)
    {
        $this->response["color"] = $color;
        return $this;
    }

    public function setUri($uri)
    {
        $this->response["url"] = $uri;
        return $this;
    }

    public function setAction($action)
    {
        $this->response["action"] = $action;
        return $this;
    }

    public function setDataAttribute($data)
    {
        $this->response["data"] = $data;
        return $this;

    }

    public static function error($msg, $responseCode = null,$successParam = false)
    {
        $response = new self(false, $msg);
        if ($responseCode)
            http_response_code($responseCode);
        return $response->send($successParam);
    }

    public static function jsonResponse(array $data, $responseCode = 200)
    {
        $response["type"] = "json";
        foreach ($data as $key => $value) {
            $response[$key] = $value;
        }
        self::responseCode($responseCode);
        return json_encode($response);
    }

    public static function responseCode($code)
    {
        http_response_code($code);
    }

    public function redirectAction($uri)
    {
        $this->setUri($uri);
        $this->setAction(self::REDIRECT);
    }

    public function refreshAction()
    {
        $this->setAction(self::REFRESH);
    }
}
