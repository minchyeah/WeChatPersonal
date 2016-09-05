<?php 

/**
 * MySQL数据库配置
 * @author minch<yeah@minch.me>
 */
class DbConfig
{
	/**
	 * 数据库的一个实例配置，则使用时像下面这样使用
	 * $user_array = Db::instance('one_demo')->select('name,age')->from('user')->where('age>12')->query();
	 * 等价于
	 * $user_array = Db::instance('one_demo')->query('SELECT `name`,`age` FROM `one_demo` WHERE `age`>12');
	 * @var array
	 */
	public static $default = array(
		'host'		=> '192.168.0.206',
		'port'		=> '3306',
		'user'		=> 'zhs',
		'password'	=> '111111',
		'dbname'	=> 'mall_0114',
		'charset'	=> 'utf8',
	);
}