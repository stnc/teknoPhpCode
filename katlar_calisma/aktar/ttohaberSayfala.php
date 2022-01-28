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



    $sonuc = file_get_contents("haber.txt");

    // print_r(  $sonuc);
echo '<pre>';
    $content = regexlink( $sonuc );
    print_r(  $content);



    foreach ($content as $value)
    {


         $data = array(
            'sayfa' => 'http://www.erciyestto.com'.$value[1],
            'durum' => 0,
            'type' => "guncel-fon",
        );
    
       // $db->insert("sayfa", $data);

    }







    





//h1 baslıgını bulur
function regexlink($sonuc)
{

    $url = preg_match_all('/<a href="(.+)">/', $sonuc, $match, PREG_SET_ORDER, 0);
    // print_r(  $matches[0]);
    // Print the entire match result
    $info = parse_url($match);
    // print_r ( $info);
    return $match;

}



