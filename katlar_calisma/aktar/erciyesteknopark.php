<?php



///////////////////////////////////////////////////////////////////////////////////
//                                                                               //
// This is using a sample local WordPress Install and is not production safe     //
// It uses the  REST and Basic Auth plugins                                      //
//                                                                               //
///////////////////////////////////////////////////////////////////////////////////

die;
// setup user name and password
function shorten_text($text, $max_length = 350, $cut_off = '', $keep_word = false)
{
    if(strlen($text) <= $max_length) {
        return $text;
    }

    if(strlen($text) > $max_length) {
        if($keep_word) {
            $text = substr($text, 0, $max_length + 1);

            if($last_space = strrpos($text, ' ')) {
                $text = substr($text, 0, $last_space);
                $text = rtrim($text);
                $text .=  $cut_off;
            }
        } else {
            $text = substr($text, 0, $max_length);
            $text = rtrim($text);
            $text .=  $cut_off;
        }
    }

    return strip_tags($text);
}

function aktar($title,$content){
	$username = 'admin';
	$password = '123456';
	
	// the standard end point for posts in an initialised Curl
	$process = curl_init('http://teknoyeni.test/wp-json/wp/v2/posts');
	
	// create an array of data to use, this is basic - see other examples for more complex inserts
	$data = array(
	'categories'=>84,
	'title' =>$title , 
	'content' => $content, 
	'status' => 'draft', 
	'excerpt' => shorten_text($content) 
	);
	$data_string = json_encode($data);
	
	// create the options starting with basic authentication
	curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
	curl_setopt($process, CURLOPT_TIMEOUT, 30);
	curl_setopt($process, CURLOPT_POST, 1);
	// make sure we are POSTing
	curl_setopt($process, CURLOPT_CUSTOMREQUEST, "POST");
	// this is the data to insert to create the post
	curl_setopt($process, CURLOPT_POSTFIELDS, $data_string);
	// allow us to use the returned data from the request
	curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
	// we are sending json
	curl_setopt($process, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($data_string))                                                                       
	);
	
	// process the request
	$return = curl_exec($process);
	
	curl_close($process);
	
	// This buit is to show you on the screen what the data looks like returned and then decoded for PHP use
	echo '<h2>Results</h2>';
	
	print_r($return);
	
	echo '<h2>Decoded</h2>';
	
	$result = json_decode($return, true);
	
	// print_r($result);
	return  $result["generated_slug"];

}


require_once 'vendor/autoload.php';
  define('DB_TYPE', 'mysql');
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'oldtekno');
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

$id=881;
$tableName = 'news_translations';
// multiple rows
 $q = "SELECT * FROM ".$tableName." where id=".$id." order by id desc";
$array_expression = $db->fetchAll ( $q );
foreach ( $array_expression as $value ) {
	echo  $value ['name'];
	echo '<br>';

	//  echo  $value ['content'];
	// echo $value ["slug"];

	 echo '<a target="_blank" href="https://www.erciyesteknopark.com/haber/'.$value ["slug"].'">AÇ</a>';

	// echo '<a target="_blank" href="'.$value ["destination_url"].'">AÇ</a>';
	echo '<hr>';

	$url=aktar($value ["name"],$value ["content"]);

	  $data = array (
		'rtype' => 301,
		'source_url' => "/haber"."/".$value ["slug"],
		'destination_url' => "http://teknoyeni.test/".$url,
		'last_access' => "2021-11-25 16:01:58",
		'time' => "2021-11-25 16:01:58",
     );

  $db->insert ( "wp_all_in_one_redirection", $data );

// update metod


   
}
die;
// single row
$q = "SELECT * FROM ".$tableName ." where id=1";
$array_expression = $db->fetch ( $q );


	echo $value ['username'];



//print_r($array_expression);



// query metod
$q = "ALTER TABLE users MODIFY COLUMN id  int(11) NOT NULL AUTO_INCREMENT FIRST";
 $db->query ( $q );

// insert data
$data = array (
		'first_name' => "john",
		'last_name' => "carter",
		'username' => "rob",
		'password' => "12345",

);
//print_r($data );
 $db->insert ( $tableName, $data );

// update metod

$data = array (
		'first_name' => "johnx",
		'last_name' => "carter",
);

$where = array (
		'id' => 1 
);

 $db-> update ( $tableName, $data, $where );

$where = array (
		'id' => 5
);
$db->delete ( $tableName, $where );


//last id 
$db->lastID();

///NEW MTEOD //Chaining methods where update //orm step 2
$db->where('id', '=', 1)->update2(['username' =>'selman sedat']);

//orm step 1
$db->tableName=$tableName;
$array_expression = $db->where_test ( 'id','=','1' );

