<?php
//获取一句话
$YJH = getYJH();
//开始绘图
$im = imagecreatefrompng("background.png");
$color = imagecolorallocate($im,255,255,255);
//设置透明
//imagecolortransparent($im,$color);
imagefill($im,0,0,$color);
//设置颜色
$pink  = ImageColorAllocate($im, 0 , 0 ,0);
$red   = ImageColorAllocate($im, 255 , 0 ,0);
$zise  = ImageColorAllocate($im, 0 , 255 ,0);
$fontfile = dirname(__FILE__) . "/msyh.ttf";//雅黑字库
//拆分换行
$fontsize  = 20;
$angle     = 0;
$width     = 300;
$YJH       = autowrap($fontsize, $angle, $fontfile, $YJH, $width);
//打印文章内容
@ImageTTFText($im,$fontsize,$angle,50,130,$pink,$fontfile,$YJH);	
@ImageTTFText($im, 10,0,35,295,$pink,$fontfile,'感谢 http://hitokoto.us 提供API，Made by Puteulanus');
Header("Content-type: image/png");
Imagepng($im);
ImageDestroy($im);
exit();

//获取一句话
function getYJH(){
	$str = file_get_contents('http://api.hitokoto.us/rand');
	$pattern = '/'.preg_quote('"hitokoto":"','/').'(.*?)'.preg_quote('",','/').'/i';
	preg_match ( $pattern,$str, $result );
	return $result[1];
}

//自动换行
function autowrap($fontsize, $angle, $fontfile, $string, $width) {
// 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度
	$content = "";

	// 将字符串拆分成一个个单字 保存到数组 letter 中
	for ($i=0;$i<mb_strlen($string);$i++) {
		$letter[] = mb_substr($string, $i, 1);
	}

	foreach ($letter as $l) {
		$teststr = $content." ".$l;
		$testbox = @imagettfbbox($fontsize, $angle, $fontfile, $teststr);
		// 判断拼接后的字符串是否超过预设的宽度
		if (($testbox[2] > $width) && ($content !== "")) {
			$content .= "\n";
		}
		$content .= $l;
	}
	return $content;
}
