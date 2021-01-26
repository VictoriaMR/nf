<?php

namespace App\Services\Common;

class ImageService
{

	public function verifyCode($len = 4, $width = 80, $height = 40)
	{
		//创建画布
		$image = @imagecreatetruecolor($width, $height) or die('Cannot Initialize new GD image stream');

    	//填充背景色
    	$bgcolor = imagecolorallocate($image, 255, 255, 255);
    	imagefill($image, 0, 0, $bgcolor);

    	$randstr = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';
    	// 生成随机码
    	$checkcode = $code = '';
    	for ($i = 0; $i < $len; $i++) {
    		//设置随机字体大小
		    $fontsize = rand(10, 40);
		    //设置随机字体颜色
		    $fontcolor = imagecolorallocate($image, rand(0, 120), rand(0, 120), rand(0, 120));
		    $code = $randstr[rand(0, 49)];
		    $checkcode .= $code;
		    //随机码宽度
		    $x = ($i * $width / $len) + rand(1, 5);
		    //随机码高度
		    $y = rand(1, $height - $fontsize);
		    //填充当前字符入画布
		    imagestring($image, $fontsize, $x, $y, $code, $fontcolor);
    	}
    	//加入干扰线
	    for($i = 0; $i < 4; $i++) {
	        //设置线的颜色
		    $linecolor = imagecolorallocate($image, rand(80, 220), rand(80, 220),rand(80, 220));
		    //设置线，两点一线
		    imageline($image, rand(1, $width - 1), rand(1, $height - 1), rand(1, $width - 1), rand(1, $height - 1), $linecolor);

	    }
	    //加入干扰象素
	    for ($i = 0; $i < 300; $i++) {
		    //设置点的颜色
		    $pointcolor = imagecolorallocate($image, rand(50, 200), rand(50, 200), rand(50, 200));    
		    //imagesetpixel画一个单一像素
		    imagesetpixel($image, rand(0, $width), rand(0, $height), $pointcolor);
		}
		//生成png图片
		imagepng($image);
		//销毁$image
		imagedestroy($image);
		return $checkcode;
	}
}