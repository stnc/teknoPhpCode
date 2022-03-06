<?php


require_once 'vendor/autoload.php';
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'ercietto');
define('DB_USER', 'root');
define('DB_PASS', '');
  

/* //use 1 
use Stnc\Db\MysqlAdapter ;
$db = new MysqlAdapter();
*/

/* //use 2
use Stnc\Db\MysqlAdapter as dbs;
$db = new dbs();
*/


//use 3
$db = new Stnc\Db\MysqlAdapter();



$tableName = 'sayfa';
// multiple rows
 $q = "SELECT * FROM ".$tableName." where durum=0 and type='duyuru' order by id asc";
$array_expression = $db->fetchAll ( $q );
// echo '<pre>';
// print_r($array_expression);
foreach ( $array_expression as $value ) {
	echo  $value ['sayfa'];
	
	echo '<br>';

	//  echo  $value ['id'];
	// echo $value ["slug"];

	 echo '<a target="_blank" href="'.$value ["sayfa"].'">AÇ</a>';
	 echo '<br>';
	 echo '<a target="_blank" href="tto.php?id='.$value ["id"].'">Aktar</a>';

	// echo '<a target="_blank" href="'.$value ["destination_url"].'">AÇ</a>';
	echo '<hr>';



}
