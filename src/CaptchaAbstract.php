<?php
namespace 'RainSunshineCloud/CaptchaAbstract';

abstract class CaptchaAbstract
{
    //字体大小
    protected static $font_size = 20;
    //字体文件
    protected static $fontfile = __DIR__.'/font/VeraSansBold.ttf';
    //验证码
    protected static $choose_text = '';
    //可选择文字
    protected static $text_arr = [
        'number' => [0,1,2,3,4,5,6,7,8,9],
        'alpha' => ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'],
        'lower_alpha-number' => [2,3,4,6,7,8,9,'a','b','c','d','e','f','g','h','j','k','m','n','p','r','s','t','u','v','w','x','y'],
        'all_alpha-number' => [2,3,4,6,7,8,9,'a','b','d','e','f','g','h','j','m','n','r','t','u','y','A','B','D','E','F','G','H','J','M','N','Q','R','T','U','Y'],
    ];
    //选择文字
    protected static $text = 'all_alpha-number';
    //宽度
    protected static $width = 100;
    //高度
    protected static $height = 30;
    //字体个数
    protected static $num = 4;
    //画布
    private static $im;
    //画点个数
    protected static $pixel_num = 60;
    //划线个数
    protected static $line_num = 2;
    //旋转范围
    protected static $angle = [-30,30];
    //字符串
    protected static $error = '';

    /**
     * 创建验证码
     * @param  string 键
     * @param  string 值
     * @return string 验证码文本
     */
    protected function create(string $key,string $filepath = '')
    {
        $this->createFontImages($key);
        $this->resizeImage();
        $this->createDot(self::$pixel_num);
        $this->createLine(self::$line_num);
        $this->printOut($filepath);
        return self::$choose_text;
    } 

    /**
     * 验证图片验证码
     * @param  [string] $code [验证码]
     * @param  [string] $key  [存储使用的键]
     * @param  [bool]   $is_case_sense [大小写敏感]
     * @return [bool] 
     */
    protected function verify(string $code, string $key, bool $is_case_sense = false) {
        if (empty($code)) {
            throw new CaptchaException('请输入验证码',1);
        }

        $res = false;
        if ($is_case_sense) {
            if ($code == $this->getstoreText($key)) $res = true;
        } else {
           if (strtolower($code) == strtolower($this->getstoreText($key))) $res = true;
        }

        $this->deleteStoreText($key);
        return $res;
    }

    /*
    *生成字体图像
    */
    protected function createFontImages($key)
    {
        $font_size_width = self::$font_size * self::$num *1.4;
        $font_size_height = self::$font_size * 1.6;
        self::$im = imagecreate($font_size_width,$font_size_height);
        $int = imagecolorallocate(self::$im,255,mt_rand(50,255),mt_rand(50,255));

        $i = 0;
        $font_arr = array_flip(self::$text_arr[self::$text]);
        $width = 0;
        $distance_max = self::$font_size * 0.3;
        $distance_min = self::$font_size * 0.2;

        while ($i <  self::$num) {
            $color = imagecolorallocate(self::$im,mt_rand(0,200),0,mt_rand(0,200));
            $font = array_rand($font_arr);
            self::$choose_text .= $font;
            $width += mt_rand($distance_min,$distance_max);
            $p_array = imagettftext(self::$im,self::$font_size,mt_rand(self::$angle[0],self::$angle[1]),$width,self::$font_size * 1.2,$color,self::$fontfile,$font);
            $width += self::$font_size;
            $i++;
        }

        $this->storeText($key,self::$choose_text);
    }

    /**
     *调整图片大小至目标大小
     */
    protected function resizeImage()
    {
        $font_size_width = self::$font_size * self::$num * 1.4;
        $font_size_height = self::$font_size * 1.6;
        $im = imagecreate(self::$width,self::$height);
        if ($font_size_width <= self::$width && $font_size_height <= self::$height) {
            $start_width = floor((self::$width - $font_size_width) / 2);
            $start_height = floor((self::$height - $font_size_height) / 2);
            imagecopyresampled($im,self::$im,$start_width,$start_height,0,0,$font_size_width,$font_size_height,$font_size_width,$font_size_height);
        } else {
            $start_width = 0;
            $start_height = 0;
            imagecopyresampled($im,self::$im,0,0,0,0,self::$width,self::$height,$font_size_width,$font_size_height);
        }
        
        imagedestroy(self::$im);
        self::$im = $im;
    }

    /**
     * 花点
     * @param  int
     * @return [type]
     */
    protected function createDot(int $num)
    {
        $i = 0;
        while ($i < $num) {
            $color = imagecolorallocate(self::$im,mt_rand(0,255),mt_rand(0,255), mt_rand(0,255));
            imagesetpixel(self::$im,mt_rand(0,self::$width),mt_rand(0,self::$height),$color);
            $i++;
        }
    }

    /**
     * 划线
     * @param  int
     * @return [type]
     */
    protected function createLine(int $num)
    {
        $width_mid = self::$width/2;
        $height_mid = self::$height/2;
        $i = 0;
        while ($i < $num) {
            $w   = imagecolorallocate(self::$im, mt_rand(0,255),mt_rand(0,255), mt_rand(0,255));
            $red = imagecolorallocate(self::$im, mt_rand(0,255),mt_rand(0,255), mt_rand(0,255));
            $style = array($red, $red, $red, $red, $red, $w, $w, $w, $w, $w);
            imagesetstyle(self::$im, $style);
            imageline(self::$im,mt_rand(0,$width_mid),mt_rand(0,$height_mid),mt_rand($width_mid,self::$width), mt_rand($height_mid,self::$height), IMG_COLOR_STYLED);
            $i++;
        }
    }

    /**
     * 输出
     * @param  string
     * @return [type]
     */
    protected function printOut(string $filepath = '')
    {
        if ($filepath) {
            return imagepng(self::$im,$filepath);
        }
        header('Content-Type: image/png');
        imagepng(self::$im);
        imagedestroy(self::$im);
    }

    /**
     * 设置字体大小
     * @param int
     */
    public static function setSize(int $size)
    {
        self::$font_size = $size;
    }

    /**
     * 设置图片宽度
     * @param int
     */
    public static function setWidth(int $width)
    {
        self::$width = $width;
    }

    /**
     * 设置图片高度
     * @param int
     */
    public static function setHeight(int $height)
    {
        self::$height = $height;
    }

    /**
     * 设置可选择的code 文本
     * @param string
     * @param array
     */
    public static function setCodeArr(string $key,array $code)
    {
        self::$text_arr[$key] = $code;
    }

    /**
     * 设置要从CodeArr选择的文本
     * @param string
     */
    public static function setChooseText(string $key)
    {
        self::$text = $key;
    }

    /**
     * 设置验证码数量
     * @param int
     */
    public static function setTextnum(int $num)
    {
        self::$num = $num;
    }

    /**
     * 设置干扰点的数量
     * @param int
     */
    public static function setPixelNum(int $num)
    {
        self::$pixel_num = $num;
    }

    /**
     * 这只干扰线的数量
     * @param int
     */
    public static function setLineNum(int $num)
    {
        self::$line_num = $num;
    }

    /**
     * 设置随机角度的范围
     * @param int
     */
    public static function setAngle(array $arr)
    {
        self::$num = $arr;
    }

    public static function __callStatic($methods,$params)
    {
        if ($methods == 'create' || $methods == 'verify') {
            $obj = get_called_class();
            $obj = new $obj();
            return call_user_func_array([$obj,$methods],$params);
        }

        $method = strtolower(str_replace('get','',$methods));
        if ( is_string($method) && isset(self::${$method})) {
            return self::${$method};
        }

        throw new CaptchaException('未有该方法',2);
    }

    public function __call($methods,$params) 
    {
        if ($methods == 'create' || $methods == 'verify') {
            $obj = get_called_class();
            $obj = new $obj();
            return call_user_func_array([$obj,$methods],$params);
        }

        throw new CaptchaException('未有该方法',2);
    }

    /**
     * 存储验证码
     * @param  string $key  [键]
     * @param  string $text [验证码]
     * @return [bool]       
     */
    protected abstract  function storeText(string $key, string $text);

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
}

class CaptchaException extends \Exception 
{

}