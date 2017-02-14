<?php
/*
 * 生成验证码
 * time:2017-2-7
 * 作者：sjs
 * 可调用的方法有：
 * 1.makeImage():生成验证码图像
 * 2.checkCode():验证用户输入的验证码是否正确
 */
class Captchar{
	private $_width=100;//定义画布的宽度
	private $_height=30;//定义画布的高度
	private $_number=4;//定义产生的验证码长度
	private $code_size=16;//定义验证码的字体大小
	private $fontfile='STXINWEI.TTF';//定义验证码的字体(需要先将同名的字体文件拷贝到当前文件夹下面)
	private $line_number=5;//定义干扰线条条数
	private $pix_number=280;//定义干扰像素点个数
	//创建验证码
	public function makeImage(){
		//创建画布
		$image=imagecreatetruecolor($this->_width, $this->_height);
		//设置验证码背景颜色,mt_rand(),用于产生更具有随机性的随机数
		$color=imagecolorallocate($image, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
		imagefill($image, 0, 0, $color);
		
		//绘制字母
		$code=$this->makeCode();//获取makcode函数生成的验证码
		
		//开始绘制
		for($i=0;$i<$this->_number;$i++){
		//配置绘制验证码字体的画笔颜色
		$color=imagecolorallocate($image, mt_rand(0,100), mt_rand(0,100), mt_rand(0,100));
		imagettftext($image, $this->code_size, mt_rand(-50, 50), 22*$i+10, 20, $color, $this->fontfile, $code[$i]);
		}
		//绘制干扰线条
		//2.循环绘制n条线条
		for($i=0;$i<$this->line_number;$i++){
			//1.配置线条颜色
			$color=imagecolorallocate($image, mt_rand(0,100), mt_rand(0,100), mt_rand(0,100));
			imageline($image, mt_rand(0,$this->_width), mt_rand(0, $this->_height), mt_rand(0,$this->_width), mt_rand(0,$this->_height), $color);
		}
		
		//绘制干扰像素点
		//2.开始绘制像素点
		for($i=0;$i<$this->pix_number;$i++){
			//1.配置干扰像素点颜色(将配置颜色放入循环体中让生成的每个像素点的颜色都不相同)
			$color=imagecolorallocate($image, mt_rand(100, 255), mt_rand(100, 255), mt_rand(100, 255));	
			imagesetpixel($image, mt_rand(0,$this->_width), mt_rand(0,$this->_height), $color);
			
		}
		
		//将产生的验证码存储起来，只能用session存储(在验证码生成的时候就将生成的验证码存储在session中)
		//开启session
		session_start();
		$_SESSION['captchar']=$code;//此处的captchar是自己定义的
// 		var_dump($_SESSION);
		
		
		
		//告诉浏览器如何显示
		header("Content-Type:image/png");
		//显示图像
		imagepng($image);
		//及时的销毁图像资源
		imagedestroy($image);
		
		
	}
	//随机产生验证码
	private function makeCode(){
		//获得字母的范围,rang()返回的是一个数组
		$lower=range('a','z');//24个字母
		$upper=range('A','Z');//24个字母
		$number=range(2,9);//8个数字
		//将上面的2个数组合并成一个数组
		$code=array_merge($lower,$upper,$number);
		//打乱数组的顺序
		shuffle($code);
		//从上面的数组中随机抽取n个字符组成字符串,需要通过数组的下标来实现
		//循环取出合并后的数组前n个字符(所以需要将数组的顺序打乱)
		$str='';
		for($i=0;$i<$this->_number;$i++){
			$str.=$code[$i];	
		}
		//echo $str;
		return $str;//返回生成的验证码		
	}
	//检测用户输入的验证码是否正确
	public function checkCode($user_code){
		//$user_code是接收的用户提交的验证码
		//首先开启session
		session_start();
		//为了不区分大小写,将用户提交的字符和验证码统一转换成小写然后进行比较
		//验证的时候的session是上面保存过的下标captchar,
		if(strtolower($user_code)==strtolower($_SESSION['captchar'])){
			//echo '验证码正确!';
			return true;
		}else{
			//echo '验证码错误!';
			return false;
			
		}
	}
	
	
	
}


