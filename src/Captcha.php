<?php

class Captcha extends CaptchaAbstract
{
    protected function storeText(string $text) 
    {
        $res = file_put_contents('./file.log',$text);
        if (!$res) {
            throw new CaptchaException('存储验证码失败');
        }
    }

    protected function getstoreText(string $key) 
    {
        $res = file_get_contents('./file.log');
        if (!$res) {
            throw new CaptchaException('获取验证码失败');
        }
        return $res;
    }

    protected function deleteStoreText(string $key)
    {
        $res = file_put_contents('./file.log','');

        if (!$res) {
            throw new CaptchaException('删除验证码失败');
        }
    }
}