<?php

use \Library\DbConnection;
use \Library\Db;

/**
 * 数据导出类
 * @author Minch<yeah@minch.me>
 * @since 2016-08-11
 */
class Export
{
	/**
	 * 数据库连接
	 * @var DbConnection
	 */
	protected $db;

	/**
	 * 订单表名
	 * @var string
	 */
	private $table_order = 'shs_order';
	
	/**
	 * 活动表名
	 * @var string
	 */
	private $table_goods = 'shs_goods';
	
	/**
	 * 活动分类表名
	 * @var string
	 */
	private $table_category = 'shs_goods_category';
	
	/**
	 * 活动分类
	 * @var string
	 */
	private $cates = array();
	
	/**
	 * 活动分类
	 * @var string
	 */
	private $goods_cate = array();
	
	private $worker = null;
	
	private $global = null;
	
	/**
	 * 构造函数
	 */
	public function __construct($worker = null, $global = null)
	{
		if($worker){
			$this->worker = $worker;
		}
		$this->db = Db::instance(DbConfig::$default);
		$this->_initcates();
	}
	
	public function init()
	{
		echo '开始导数据'.date("Y-m-d H:i:s", $stime).PHP_EOL;
		$title = '"会员名","id","注册时间","购买时间","网购价","活动价","一级类目","二级类目"'. "\r\n";
		file_put_contents(dirname(dirname(__DIR__)).'/Data/exp.csv', $title);
		$params = array('minuid'=>$minuid);
		$sql = "SELECT uname,uid FROM shs_user
				WHERE uid>:minuid
				LIMIT 200;";
		$rows = $this->db->query($sql, $params);
		if(!is_array($rows) OR empty($rows)){
			break;
		}
		$csvline = '';
		foreach ($rows as $row){
			$tmp = $row;
			$tmp['uid'] = $row['uid'];
			$tmp['uname'] = $row['uname'];
			$csvline .= '"'.implode('","', $tmp).'"';
			$csvline .= "\r\n";
			$i += 1;
		}
	}

	/**
	 * 导出操作
	 */
	public function dump()
	{
		$i = 0; $j = 0; $minuid = 0;
		do{
			$params = array('minuid'=>$minuid);
			$sql = "SELECT uname,uid FROM shs_user 
					WHERE uid>:minuid 
					LIMIT 200;";
			$rows = $this->db->query($sql, $params);
			if(!is_array($rows) OR empty($rows)){
				break;
			}
			$csvline = '';
			foreach ($rows as $row){
				$tmp = $row;
				$tmp['uid'] = $row['uid'];
				$tmp['uname'] = $row['uname'];
				$csvline .= '"'.implode('","', $tmp).'"';
				$csvline .= "\r\n";
				$i += 1;
			}
			file_put_contents(dirname(dirname(__DIR__)).'/Data/exp.csv', $csvline, FILE_APPEND);
			echo '进程'.$this->worker->id.' 导出'.$i.'行'.PHP_EOL;
			unset($tmp,$csvline);
			$j += 1;
		}while ($j < 10000);
		if($this->worker->id == 0){
			echo '数据导出结束'.date("i:s", time()-$stime).PHP_EOL;
		}
	}

}