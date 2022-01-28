<?php



require_once 'vendor/autoload.php';
define('DB_TYPE', 'pq');
define('DB_HOST', 'localhost');
define('DB_NAME', 'krbn');
define('DB_USER', 'postgres');
define('DB_PASS', 'changeme');
define('DB_PORT', '5432');

/* //use 1
use Stnc\Db\MysqlAdapter ;
$db = new MysqlAdapter();
*/

/* //use 2
use Stnc\Db\MysqlAdapter as dbs;
$db = new dbs();
*/

//use 3
$db = new Stnc\Db\PostgresqlAdapter();

$tableName = 'branches';
// multiple rows
//  $q = "SELECT * FROM " . $tableName . " where id=" . $id . " and durum=0 and type='guncel-fon' order by id desc";
echo $q = "SELECT * FROM " . $tableName . " WHERE id NOT IN (2,4) order by id desc";

$array_expression = $db->fetchAll($q);
foreach ($array_expression as $value) {

echo $value["kullanicilar"];
echo '<br>';

$pieces = explode("-", $value["kullanicilar"]);
echo '<pre>';
print_r($pieces);
foreach ($pieces as $piece) {
    echo ($piece);
    echo '<br>';
    if ($piece!=""){
        $data = array(
            'save_user_id' => 5,
            'branch_id' => $value["id"],
            'user_id' =>$piece,
    
            'created_at' => "2022-01-26 18:00:00",
            'updated_at' => "2022-01-26 18:00:00",
        );
    
        $db->insert("categories_branchs", $data);
    }


}
/*



*/

}

