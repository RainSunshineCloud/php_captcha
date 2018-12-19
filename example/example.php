<?php
include '../CaptchaAbstract.php';
include '../Captcha.php';

try {   
    $res = new Captcha();
    $res->setSize(20);
    $res->setWidth(100);
    $res->setHeight(100);
    $res->setHeight(100);
    $res->setChooseText('all_alpha-number');
    $res->setTextnum(5);
    $res->setPixelNum(1200);
    $res->setLineNum(10);

    $res -> create();
    // $res = $res->getStoreText('key','ru8y');
} catch (\CaptchaException $e) {
    echo $e->getmessage();
}