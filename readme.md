# 验证码生成器

## 该验证码采用font字体和width height 各自生成的策略，当字体所需空间小于width 和 height 的宽高时，保持原有大小，超过该大小时，自动缩放为固定大小

## 文件

### CaptchaAbstract 为生成验证码的抽象类，必须实现三个抽象方法

```
    /**
     * 存储验证码
     * @param  string $key [存储使用的键]
     * @param  string $text [验证码]
     * @return [bool]       
     */
    protected abstract  function storeText(string $key,string $text);

    /**
     * 获取存储的字符串
     * @param  string $key [存储使用的键]
     * @return [string]    存储的验证码
     */
    protected abstract  function getStoreText(string $key);

    /**
     * 删除存储的字符串
     * @param  string $key [存储使用的键]
     * @return [bool]
     */
    protected abstract function deleteStoreText(string $key);

```
### Captcha 为CaptchaAbstract 的一种实现，采用session 存储 验证码；
## usage
```
$res->create('sjfdh'); //生成验证码图像 ,
$res->create('./file/img.png'); //将生成的验证码存储在某个文件内，只支持保存为png；
$res->verify('sdfkj','key'); //验证码验证，不区分大小写，存储使用的键为key
$res->verify('sjdfjk','key',true); //验证码验证，区分大小写，存储使用的键为key
```

### 其他设置请自行查看抽象类方法
### create 方法和 verify 支持静态调用
