<?php
require_once '../../../autoload.php';
use RainSunshineCloud\Captcha;
use RainSunshineCloud\CaptchaException;

try {   
    $res = new Captcha();
    $res->setSize(20);
    $res->setWidth(100);
    $res->setHeight(100);
    $res->setHeight(100);
    $res->setChooseText('all_alpha-number');
    $res->setTextnum(5);
    $res->setPixelNum(120);
    $res->setLineNum(2);
    $key = 'skdjfk';
    // $res ->create($key);
    $bool = $res->verify('ed6mu',$key);
} catch (\CaptchaException $e) {
    echo $e->getmessage();
}