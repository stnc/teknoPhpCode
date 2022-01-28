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
$datass= explode( '/', $value ['sayfa'] ) ;
	echo '<br>';
	//print_r($datass);
	//  echo  $value ['id'];
	// echo $value ["slug"];

	 echo '<a target="_blank" href="'.$value ["sayfa"].'">AÇ</a>';
	 echo '<br>';
	 echo '<a target="_blank" href="ttohaber.php?id='.$value ["id"].'">Aktar</a>';

	//  http://www.erciyestto.com/duyuru/6088/tubitak-cnrst-fas-ikili-isbirligi-cagrisi-acildi.html
	// echo '<a target="_blank" href="'.$value ["destination_url"].'">AÇ</a>';
	echo '<hr>';
echo $datass[5];
$source_url= explode( '.', $datass[5] ) ;
print_r($source_url[0]);
echo '<br>';
    $data = array(
        'rtype' => 301,
        'source_url' => '/'.$datass[3].'/'.$datass[4].'/'.$datass[5],
        'destination_url' => 'https://www.erciyestto.com/'.$source_url[0], 
        'last_access' => "2022-01-04 16:01:58",
        'time' => "2022-01-04 16:01:58",
    );
	print_r(  $data);
   // $db->insert("wp_all_in_one_redirection", $data);

    $where = array(
        'id' => $value ["id"]
    );

    $data = array(
        'durum' => 1,
    );

    //$db->update($tableName, $data, $where);

}
