<?php

namespace Library;

/**
 * 日志类
 */
class Log
{
	/**
	 * 初始化
	 * @return bool
	 */
	public static function init()
	{
		set_error_handler(array('\Applications\Library\Log','errHandle'), E_RECOVERABLE_ERROR | E_USER_ERROR);
		return self::checkWriteable();
	}

	/**
	 * 检查log目录是否可写
	 * @return bool
	 */
	public static function checkWriteable()
	{
		$ok = true; $log_dir = dirname(__DIR__). '/Logs/';
		if(!is_dir($log_dir)){
			// 检查log目录是否可读
			umask(0);
			if(@mkdir($log_dir, 0777) === false){
				$ok = false;
			}
			@chmod($log_dir, 0777);
		}
		
		if(!is_readable($log_dir) || !is_writeable($log_dir)){
			$ok = false;
		}
	}

	/**
	 * 添加日志
	 * @param string $msg
	 * @return void
	 */
	public static function add($msg)
	{
		$log_dir = dirname(__DIR__) . '/Logs/' . date('Y-m-d');
		umask(0);
		// 没有log目录创建log目录
		if(!is_dir($log_dir)){
			if(@mkdir($log_dir, 0777, true) === false){
				echo 'Log dir not exists ' . $log_dir .PHP_EOL;
				return false;
			}
		}
		if(!is_readable($log_dir)){
			echo 'Log dir is unreadable ' . $log_dir .PHP_EOL;
			return false;
		}
	
		$log_file = $log_dir . "/server.log";
		file_put_contents($log_file, date('Y-m-d H:i:s') . " " . $msg . "\n", FILE_APPEND | LOCK_EX);
	}

	/**
	 * 错误日志捕捉函数
	 * @param int $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param int $errline
	 * @return false
	 */
	public static function errHandle($errno, $errstr, $errfile, $errline)
	{
		$err_type_map = array(E_RECOVERABLE_ERROR=>'E_RECOVERABLE_ERROR',E_USER_ERROR=>'E_USER_ERROR');
		
		switch($errno){
			case E_RECOVERABLE_ERROR:
			case E_USER_ERROR:
				$msg = "{$err_type_map[$errno]} $errstr";
				self::add($msg);
				// trigger_error($errstr);
				throw new \Exception($msg, self::CATCHABLE_ERROR);
				break;
			default:
				return false;
		}
	}
}
