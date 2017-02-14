<?php
//使用interface关键字定义接口，接口可以用来规范类应该有哪些方法，方法名称是什么
interface I_DAO{
	//查询所有数据的方法
	public function fetchAll($sql);
	//查询一条数据的方法
	public function fetchRow($sql);
	//查询一个字段的值的方法
	public function fetchOne($sql,$col_num);
	//执行增删改的方法
	public function exec($sql);
	//获得刚刚插入的数据的主键值
	public function lastInsertId();
}