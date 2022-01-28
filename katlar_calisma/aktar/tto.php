<?php
$id = htmlspecialchars($_GET["id"]);

///////////////////////////////////////////////////////////////////////////////////
//                                                                               //
// This is using a sample local WordPress Install and is not production safe     //
// It uses the  REST and Basic Auth plugins                                      //
//                                                                               //
///////////////////////////////////////////////////////////////////////////////////




function aktar($title, $content, $tarih, $mediaID)
{
    $username = 'admin';
    $password = '123456789';

    // the standard end point for posts in an initialised Curl
    $process = curl_init('http://ercietto.test/wp-json/wp/v2/posts');

    // create an array of data to use, this is basic - see other examples for more complex inserts
    $data = array(
        'categories' => 2,
        'title' => $title,
        'content' => $content,
        'status' => 'draft',
        'featured_media' => $mediaID,
        'date' => $tarih . 'T00:00:00', // YYYY-MM-DDTHH:MM:SS
        // 'excerpt' => shorten_text($content)
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
    curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
    // we are sending json
    curl_setopt($process, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string)
    ));

    // process the request
    $return = curl_exec($process);

    curl_close($process);

    // This buit is to show you on the screen what the data looks like returned and then decoded for PHP use
    echo '<h2>Results</h2>';

    print_r($return);

    echo '<h2>Decoded</h2>';

    $result = json_decode($return, true);

    // print_r($result);
    return $result["generated_slug"];

}

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
$q = "SELECT * FROM " . $tableName . " where id=" . $id . " and durum=0 order by id desc";
$array_expression = $db->fetchAll($q);
foreach ($array_expression as $value)
{
    echo $value['sayfa'];
    echo '<br>';

    //  echo  $value ['content'];
    // echo $value ["slug"];
    echo '<a target="_blank" href="' . $value["sayfa"] . '">AÇ</a>';

    // echo '<a target="_blank" href="'.$value ["destination_url"].'">AÇ</a>';
    echo '<hr>';

    $sonuc = curll($value["sayfa"]);

    $full = regexFull($sonuc);
    $content = regexContent($full[0]);
    $title = regexBaslik($full[0]);
    $regexImg = regexImg($full[0]);
    $tarih = regexTarih($full[0]);
    $tarih = tarihParcala($tarih[0]);

    download_image1('http://www.erciyestto.com/' . $regexImg[1], $id . "_gelen.jpg");
	
    $iamgID = curllMedia($id . "_gelen.jpg");

     $mediaID = $iamgID->id;

    // print_r($regexImg[1]);
    // print_r($content[0]);
    // die;
    

    $url = aktar($title[0], $content[0], $tarih, $mediaID);

    $data = array(
        'rtype' => 301,
        'source_url' => $value["sayfa"],
        'destination_url' => $url,
        'last_access' => "2021-11-30 16:01:58",
        'time' => "2021-11-30 16:01:58",
    );

    $db->insert("wp_all_in_one_redirection", $data);

    $where = array(
        'id' => $id
    );

    $data = array(
        'durum' => 1,
    );

    $db->update($tableName, $data, $where);

    // update metod
    
}

//bu sayfayı erciyestto sayfasını ceker
function curll($sayfa)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $sayfa);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $sonuc = curl_exec($ch);

    curl_close($ch);
    return $sonuc;
}

//verilen linkdeki resimi indirir
function download_image1($image_url, $image_file)
{
    $fp = fopen($image_file, 'w+'); // open file handle
    $ch = curl_init($image_url);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // enable if you want
    curl_setopt($ch, CURLOPT_FILE, $fp); // output to file
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1000); // some large value to allow curl to run for a long time
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    // curl_setopt($ch, CURLOPT_VERBOSE, true);   // Enable this line to see debug prints
    curl_exec($ch);

    curl_close($ch); // closing curl handle
    fclose($fp); // closing file handle
    
}

//wordpress api ye resim aktarır
function curllMedia($indirlenResim)
{
    $file = file_get_contents($indirlenResim);
    $url = 'http://etto.test/wp-json/wp/v2/media/';
    $ch = curl_init();
    $username = 'admin';
    $password = '123456789';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $file);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Disposition: form-data; filename="'.$indirlenResim.'"', 'Authorization: Basic ' . base64_encode($username . ':' . $password) , ]);
    $result = curl_exec($ch);
    // echo '<pre>';
    curl_close($ch);
    // print_r( json_decode( $result ));
    return (json_decode($result));
}

//ilk regex işlemi
function regexFull($sonuc)
{
    $re = '/(?<=<div class="duyuru_blok">)(.|\n)*?(?=<\/div>)/mu';
    preg_match_all($re, $sonuc, $matches, PREG_SET_ORDER, 0);
    // print_r(  $matches[0]);
    // Print the entire match result
    return $matches[0];
}

//veri ıcınde img olanları bulur
function regexImg($sonuc)
{
    $re = '/<img[^>]*?src\s*=\s*[""\']?([^\'"" >]+?)[ \'""][^>]*?>/m';
    preg_match_all($re, $sonuc, $matches, PREG_SET_ORDER, 0);
    // print_r(  $matches[0]);
    // Print the entire match result
    return $matches[0];
}

//p tag ıcındekı yazıları verır
function regexContent($sonuc)
{
    $re = '/(?<=<p>)(.|\n)*?(?=<\/p>)/mu';
    preg_match_all($re, $sonuc, $matches, PREG_SET_ORDER, 0);
    // print_r(  $matches[0]);
    // Print the entire match result
    return $matches[0];
}

//h1 baslıgını bulur
function regexBaslik($sonuc)
{

    $re = '/(?<=<h1>)(.|\n)*?(?=<\/h1>)/mu';

    preg_match_all($re, $sonuc, $matches, PREG_SET_ORDER, 0);
    // print_r(  $matches[0]);
    // Print the entire match result
    return $matches[0];

}

//ıcınde tarıhı bulur
function regexTarih($sonuc)
{

    $re = '/(?<=<small>)(.|\n)*?(?=<\/small>)/mu';

    preg_match_all($re, $sonuc, $matches, PREG_SET_ORDER, 0);
    // print_r(  $matches[0]);
    // Print the entire match result
    return $matches[0];

}

//tarih verisini mysql e gore formatlar 
function tarihParcala($tarih)
{

    $pieces = explode(".", $tarih);
    // print_r($pieces);
    return $pieces[2] . "-" . $pieces[1] . "-" . $pieces[0];

}


// setup user name and password
function shorten_text($text, $max_length = 350, $cut_off = '', $keep_word = false)
{
    if (strlen($text) <= $max_length)
    {
        return $text;
    }

    if (strlen($text) > $max_length)
    {
        if ($keep_word)
        {
            $text = substr($text, 0, $max_length + 1);

            if ($last_space = strrpos($text, ' '))
            {
                $text = substr($text, 0, $last_space);
                $text = rtrim($text);
                $text .= $cut_off;
            }
        }
        else
        {
            $text = substr($text, 0, $max_length);
            $text = rtrim($text);
            $text .= $cut_off;
        }
    }

    return strip_tags($text);
}
die;
// single row
$q = "SELECT * FROM " . $tableName . " where id=1";
$array_expression = $db->fetch($q);

echo $value['username'];

//print_r($array_expression);


// query metod
$q = "ALTER TABLE users MODIFY COLUMN id  int(11) NOT NULL AUTO_INCREMENT FIRST";
$db->query($q);

// insert data
$data = array(
    'first_name' => "john",
    'last_name' => "carter",
    'username' => "rob",
    'password' => "12345",

);
//print_r($data );
$db->insert($tableName, $data);

// update metod
$data = array(
    'first_name' => "johnx",
    'last_name' => "carter",
);

$where = array(
    'id' => 1
);

$db->update($tableName, $data, $where);

$where = array(
    'id' => 5
);
$db->delete($tableName, $where);

//last id
$db->lastID();

///NEW MTEOD //Chaining methods where update //orm step 2
$db->where('id', '=', 1)
    ->update2(['username' => 'selman sedat']);

//orm step 1
$db->tableName = $tableName;
$array_expression = $db->where_test('id', '=', '1');

