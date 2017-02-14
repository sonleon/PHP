<?php
/*
 * DAOPDO类，通过PDO来操作数据库
 * 包含的方法：
 * fetchAll：查询所有数据
 * fetchOne：查询一个字段的值
 * fetchRow：查询一条数据
 * exec:执行数据库的增删改操作
 * lastInsertId:
 */
require_once 'I_DAO.interface.php';//引入接口文件
final class DAOPDO implements I_DAO{
	private $host;
	private $user;
	private $pwd;
	private $port;
	private $dbname;
	private $charset;
	private $pdo;//用于接收实例化对象	
	private static $instance;//定义静态成员属性
	
	//单例模式
	
	//私有的构造函数,阻止程序员通过new实例化对象
	private function __construct(array $option){
		$this->host=isset($option['host'])?$option['host']:'';
		$this->user=isset($option['user'])?$option['user']:'';
		$this->pwd=isset($option['pwd'])?$option['pwd']:'';
		$this->port=isset($option['port'])?$option['port']:'';
		$this->dbname=isset($option['dbname'])?$option['dbname']:'';
		$this->charset=isset($option['charset'])?$option['charset']:'';
		if($this->host===''||$this->user===''||$this->pwd===''||$this->port===''||$this->dbname===''||$this->charset===''){
			die('参数错误!');
		}
		$dsn="mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=$this->charset";
		$this->pdo=new PDO($dsn,$this->user,$this->pwd);
		//var_dump($this->pdo);				
	}
	
	
	//私有的克隆，防止程序员克隆
	private function __clone(){
		
	}
	
	public static function getSingleton(array $option){
		if(!self::$instance instanceof self){
			self::$instance=new self($option);
		}
		return self::$instance;//一个函数一定要有返回值！！！
	}
	
	
	
	
	//查询所有数据的方法
	public function fetchAll($sql){
		$res=$this->pdo->query($sql);
		if($res){
			$rows=$res->fetchAll(PDO::FETCH_ASSOC);
		}else{
			//die('执行失败！错误信息是：'.$res->errorInfo());
			die('执行失败！错误信息是：'.$this->pdo->errorInfo()[2]);
		}
		//关闭指针
		$res->closeCursor();
		return $rows;//一个函数一定要有返回值！！！
		
	}
	//查询一条数据的方法
	public function fetchRow($sql){
		$res=$this->pdo->query($sql);
		if($res){
			$row=$res->fetch();
		}else{
			die('执行失败！错误信息是：'.$this->pdo->errorInfo()[2]);
		}
		return $row;//一个函数一定要有返回值！！！
		
	}
	//查询一个字段的值的方法
	public function fetchOne($sql,$col_num){
		$res=$this->pdo->query($sql);//此处的$res就是$pdo_statement对象
		if($res){
			$row=$res->fetchColumn($col_num);
		}else{
			die('执行失败！错误信息是：'.$this->pdo->errorInfo()[2]);
		}
		return $row;//一个函数一定要有返回值！！！
		
	}
	//执行增删改的方法
	public function exec($sql){
		$res=$this->pdo->exec($sql);
		if(!$res){
			die('执行失败！错误信息是：'.$this->pdo->errorInfo()[2]);
		}	
		return $res;//一个函数一定要有返回值！！！此时的返回值就是受影响的记录数
	}
	//获得刚刚插入的数据的主键值
	public function lastInsertId(){
		$res=$this->pdo->lastInsertId();
		if(!$res){
			die('执行失败！错误信息是：'.$this->pdo->errorInfo()[2]);
		}
		return $res;//一个函数一定要有返回值！！！
		
	}

	
}