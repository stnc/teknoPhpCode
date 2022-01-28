<?php


//https://ahmetimamoglu.com.tr/postgresql-turkce-karakter-hatasinin-cozumleri
require_once 'vendor/autoload.php';
define('DB_TYPE', 'pq');
define('DB_HOST', 'localhost');
define('DB_NAME', 'krbn');
define('DB_USER', 'postgres');
define('DB_PASS', 'changeme');
define('DB_PORT', '5432');



define('DB_TYPE2', 'mysql');
define('DB_HOST2', 'localhost');
define('DB_NAME2', 'uama2');
define('DB_USER2', 'root');
define('DB_PASS2', 'qggmkvwm');
/* //use 1
use Stnc\Db\MysqlAdapter ;
$db = new MysqlAdapter();
*/

/* //use 2
use Stnc\Db\MysqlAdapter as dbs;
$db = new dbs();
*/

//use 3


function replace_tr($text) {
    $text = trim($text);
    $search = array('Ç','ç','Ğ','ğ','ı','İ','Ö','ö','Ş','ş','Ü','ü',' ');
    $replace = array('c','c','g','g','i','i','o','o','s','s','u','u','-');
    $new_text = str_replace($search,$replace,$text);
    return $new_text;
    } 

    
function generateRandomString($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


$dbPq = new Stnc\Db\PostgresqlAdapter();
$tableName = 'adres_contact_datas_fil';
echo $q = "SELECT * FROM " . $tableName . "  WHERE  kaynak='qurbani'  order by no desc  ";


$host = "localhost";
$username = "root";
$password = "qggmkvwm";
$database = "uama2";
$connection = mysqli_connect($host, $username, $password, $database); 

if (mysqli_connect_errno()) { 
    echo "Database connection failed."; 
}


$result = mysqli_query($connection, $q);

// echo $q = "SELECT * FROM " . $tableName . "  WHERE  kaynak='qurbani' and no NOT IN  (6450,6417,6122,6120,6119,6118) order by no desc limit 10 ";

$kisilerRoot = mysqli_fetch_all($result,MYSQLI_ASSOC);
// echo "<pre>";
// print_r($kisilerRoot );
// $kisilerRoot = $db->fetchAll($q);
foreach ($kisilerRoot as $kisilerRootvalue) {
    echo '<br>';
    echo '-----ust kısım----';
    echo '<br>';
echo $kisilerRootvalue["adi_soyadi"];
echo '<br>';
echo $kisilerRootvalue["no"];
echo '<br>';




$q = "SELECT count(no) as total FROM donation_donated_qurbani_fil WHERE contact_id=".$kisilerRootvalue["no"]." ";
$result = mysqli_query($connection, $q);

// echo $q = "SELECT * FROM " . $tableName . "  WHERE  kaynak='qurbani' and no NOT IN  (6450,6417,6122,6120,6119,6118) order by no desc limit 10 ";

$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
print_r($row );
echo $row ['total'];


if ($row ['total']>0){

echo '<hr>';
echo '<br>';
echo '-----alt kısım----';

return mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content, 'UTF-8, ISO-8859-9', true));

$kisiOlustur = array(
   // 'id' => $kisilerRootvalue["no"],
    'user_id' => $kisilerRootvalue["ilk_kayit_yapan"],
    'ad_soyad' =>   mb_convert_encoding($kisilerRootvalue["adi_soyadi"],'UTF-8','auto'),
    'telefon' =>$kisilerRootvalue["telefon"],
    'email' => mb_convert_encoding($kisilerRootvalue["e_posta"],'UTF-8','auto'),
    'aciklama' =>"first data",
    'adres' =>"",
    'durum' =>1,
    'doviz_cinsi' =>2,
    'referans_kisi1' =>0,
    'created_at' =>  $kisilerRootvalue["ilk_kayit_tarihi"],
    'updated_at' => $kisilerRootvalue["son_duzenleme_tarihi"],


);
$dbPq->insert("sacrifice_kisiler", $kisiOlustur);





// $q = "select max(id) as data from sacrifice_kisiler";
// $row5= $dbPq->fetch ( $q );
// echo  $kisiID= $row5 ['data'];

$kisiID = $dbPq->lastID('sacrifice_kisiler_id_seq');


$q = "SELECT * FROM donation_donated_qurbani_fil WHERE contact_id=".$kisilerRootvalue["no"]." ";
// $kestikleri = $db->fetchAll($q);
$result = mysqli_query($connection, $q);
$kestikleri = mysqli_fetch_all($result,MYSQLI_ASSOC);


foreach ($kestikleri as $kestiklerivalue) {
    echo '<br>';
    echo $kestiklerivalue["kim_adina"];
    echo '<br>';

    $kurbanOlustur = array(
        'user_id' => $kestiklerivalue["ilk_kayit_yapan"],
        'grup_id' => 6,
        'kisi_id' => $kisiID,
        'vekalet_durumu' => 2,
        'agirlik' => 0,
        'slug' => generateRandomString(),
        'kurban_turu' => 6,
        'durum' => 1,
        'aciklama' => "",
        'borc_durum' => 6,
        'grup_lideri' => 0,
        'kurban_fiyati' =>$kestiklerivalue["donation_price"],
        'borc' =>0,
        'alacak' =>0,
        'bakiye' =>$kestiklerivalue["donation_price"],
        'hayvan_cinsi' =>$kestiklerivalue["donation_price"],
        'tarih' =>$kestiklerivalue["ilk_kayit_tarihi"],
        'kim_adina' =>  mb_convert_encoding($kestiklerivalue["kim_adina"],'UTF-8','auto'),
        'sertifika' =>$kestiklerivalue["cert"],
        'created_at' => $kestiklerivalue["ilk_kayit_tarihi"],
        'updated_at' => $kestiklerivalue["son_duzenleme_tarihi"],
 
    );
    $dbPq->insert("sacrifice_kurbanlar", $kurbanOlustur);

    // $q = "select max(id) as data from sacrifice_kurbanlar";
    // $row6= $dbPq->fetch ( $q );
    //  $kurbanID= $row6 ['data'];
     $kurbanID = $dbPq->lastID('sacrifice_kurbanlar_id_seq');

    $odemeOlustur1 = array(
        'user_id' => $kestiklerivalue["ilk_kayit_yapan"],
        'kurban_id' => $kurbanID,
        'aciklama' => "ilk Eklenen Fiyat",

        'makbuz' => "",
        'durum' => 0,
        'borc_durum' => 1,
        'kurban_fiyati' =>$kestiklerivalue["donation_price"],
        'verilen_ucret' =>0,
        'borc' =>0,
        'alacak' =>0,
        'bakiye' =>$kestiklerivalue["donation_price"],
        'verildigi_tarih' => $kestiklerivalue["ilk_kayit_tarihi"],
        'created_at' => $kestiklerivalue["ilk_kayit_tarihi"],
        'updated_at' => $kestiklerivalue["son_duzenleme_tarihi"],
     
    );
    $dbPq->insert("sacrifice_odemeler", $odemeOlustur1);


    // $db->bosalt() ;
}


}

$odemeOlustur2 = array(
    'user_id' => $kestiklerivalue["ilk_kayit_yapan"],
    'kurban_id' => $kurbanID,
    'aciklama' => "Odeme Eklendi: ".$kestiklerivalue["donation_price"]." $ / Hesap Kapandi",
    'makbuz' => "",
    'durum' => 1,
    'borc_durum' => 6,
    'kurban_fiyati' =>$kestiklerivalue["donation_price"],
    'verilen_ucret' =>$kestiklerivalue["donation_price"],
    'borc' =>0,
    'alacak' =>0,
    'bakiye' =>$kestiklerivalue["donation_price"],
    'verildigi_tarih' => $kestiklerivalue["ilk_kayit_tarihi"],
    'created_at' => $kestiklerivalue["ilk_kayit_tarihi"],
    'updated_at' => $kestiklerivalue["son_duzenleme_tarihi"],
  
);
$dbPq->insert("sacrifice_odemeler", $odemeOlustur2);

echo '<div style="border:1px solid red"></div>';


// foreach ($pieces as $piece) {
//     echo ($piece);
//     echo '<br>';
//     if ($piece!=""){
//         $data = array(
//             'save_user_id' => 5,
//             'branch_id' => $value["id"],
//             'user_id' =>$piece,
    
//             'created_at' => "2022-01-26 18:00:00",
//             'updated_at' => "2022-01-26 18:00:00",
//         );
    
//         $db->insert("categories_branchs", $data);
//     }


// }


}

