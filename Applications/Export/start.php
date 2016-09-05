<?php 
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

$GlobalServer = new GlobalData\Server('127.0.0.1', 6985);

$global = new GlobalData\Client('127.0.0.1:6985');

// ExportWorker
$worker = new \Workerman\Worker();
$worker->count = 5;
$worker->name = 'ExportWorker';
$worker->onWorkerStart = function ()use($worker, $global) {
	$instance = new Export($worker, $global);
	if($worker->id == 0){
		$instance->init();
	}
	$instance->dump();
};
// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START'))
{
	\Workerman\Worker::runAll();
}
