<?php
/*
 * DaoMysqli类
 * 时间：2017-2-8
 * 作者：sjs
 * 使用该类的方法
 * $dao=DaoMysqli::getSingleton($option);
 */
//使用final关键字阻止类被继承
final class DaoMysqli{
	private $_host;//主机名
	private $_user;//用户名
	private $_pwd;//密码
	private $_db;//数据库
	private $_port;//端口号
	private $_charset;//字符编码
	private static $_instance;//静态属性，用于实现单例模式
	private $_mySQLi;//利用这个成员属性来实例化mysqli
	
	
	//构造函数,把构造函数设置成为private是为了防止程序员直接new对象
	private function __construct(array $option=array()){
		//初始化成员变量
		$this->_initOption($option);
		//初始化mysqli对象
		$this->_initMysqli();
		
	}
	//编写_initOption()方法
	private function _initOption(array $option=array()){
		//对我们的成员变量进行初始化的工作
		$this->_host=isset($option['host'])?$option['host']:'';//如果下标为host的存在则返回host对应的值，否则返回''
		$this->_user=isset($option['user'])?$option['user']:'';
		$this->_pwd=isset($option['pwd'])?$option['pwd']:'';
		$this->_db=isset($option['db'])?$option['db']:'';
		$this->_port=isset($option['port'])?$option['port']:'';
		$this->_charset=isset($option['charset'])?$option['charset']:'';
		//判断参数是否正确
		if($this->_host===''||$this->_user===''||$this->_pwd===''||$this->_db===''||$this->_port===''||$this->_charset===''){			
			die('参数有误!');
		}
	}
	//编写_initMysqli()方法,在这个方法中完成对mysqli的连接工作
	private function _initMysqli(){
		
		//这个函数是用来初始化mysqli对象
		$this->_mySQLi=new MySQLi($this->_host,$this->_user,$this->_pwd,$this->_db,$this->_port,$this->_charset);
		
		//判断对象是否成功获取
		if($this->_mySQLi->connect_errno){
			die('获取mysqli对象失败 错误信息:'.$this->_mySQLi->connect_error);
		}
		//设置字符集
		$this->_mySQLi->set_charset($this->_charset);	
	}
	
	//阻止克隆
	private function __clone(){
		
	}
	//编写一个静态方法来完成对对象实例化的任务
	//获取对象实例的方法，我们通过数组传入
	public static function getSingleton(array $option=array()){
		//判断对象是否已经存在，如果已经存在则直接返回，如果不存在则实例化对象，至此完成单例模式
		if(!self::$_instance instanceof self){
			self::$_instance = new self($option);//由于构造函数是private的所以实例化对象不能使用new，通过操作静态方法来在函数内部实例化对象
		}
		return self::$_instance;
	}
	
	//以下开始编写数据库操作方法
	
	//编写一个成员方法用来取出数据库中的一个数据
	public function fetch_one($sql){
		//$res是一个结果集
		$res=$this->_mySQLi->query($sql);
		if($res){
			$row=$res->fetch_assoc();//取出一条数据存入$row;fetch_assoc()函数返回的是一条关联数组
			$res->free();
		}else{
			echo '执行失败!';
			die("<br>错误信息是：".$this->_mySQLi->errno);
		}
		return $row;//返回结果(是一个数组)
		
	}
	//编写一个成员方法用于取出数据库中的所有数据
	public function fetch_all($sql){
		$res=$this->_mySQLi->query($sql);
		if($res){
			//此处zendstudio中会出现一个警告，是由于代码不严谨产生的，并不影响程序的运行,改进方法如下
			while(($row=$res->fetch_assoc())==true){
				$rows[]=$row;
			}
			
		}else{
			echo '执行失败！';
			die("<br>错误信息是：".$this->_mySQLi->error);
			
		}
		return $rows;//返回结果(是一个二维数组)
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}