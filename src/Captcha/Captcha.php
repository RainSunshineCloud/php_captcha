<?php
namespace RainSunshineCloud\Captcha;

class Captcha extends CaptchaAbstract
{
    public function __construct()
    {
        session_status() != 2 && session_start();
    }
    protected function storeText(string $key,string $text) 
    {
        
        $_SESSION[$key] = $text;
    }

    protected function getstoreText(string $key) 
    {
        if (isset($_SESSION[$key])) {
             return $_SESSION[$key];
        }
       throw new CaptchaException('获取失败');
    }

    protected function deleteStoreText(string $key)
    {
        unset($_SESSION[$key]);
    }
}