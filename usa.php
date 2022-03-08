<?php
 //[{"date":"2023-11-06","business_day":true},{"date":"2023-11-13","business_day":true},{"date":"2023-11-20","business_day":true},{"date":"2023-11-27","business_day":true}]

//Hesabınızın kullanıcı adı (email de olabilir)
define('USERNAME', 'selmantunc@gmail.com');
 
//Hesabınızın giriş şifresi
define('PASSWORD', 'mypasrXiQ&vE?j8A233Lsword');
 
//Bir user agent oluşturalım ki tarayıcı kızmasın.
define('USER_AGENT', 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.2309.372 Safari/537.36');
 
//Çerez bilgimizin nerede depolanacağı (kimlik doğrulama için gerekli).
define('COOKIE_FILE', 'cookie.txt');
 
//Login formu hangi url'den çağırıyor?
define('LOGIN_FORM_URL', 'https://ais.usvisa-info.com/tr-tr/niv/users/sign_in');
 
//Login formu hangi url'yi çağırıyor? Bu bazen aynı url'dir. /tr-tr/niv/users/sign_in
define('LOGIN_ACTION_URL', 'https://ais.usvisa-info.com/tr-tr/niv/account');
 


function verifyReCaptcha($recaptchaCode){
    $postdata = http_build_query(["secret"=>"XXXXXX","response"=>$recaptchaCode]);
    $opts = ['http' =>
        [
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        ]
    ];
    $context  = stream_context_create($opts);
    $result = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
    $check = json_decode($result);
    return $check->success;
}


// Gerekli form alanlarını temsil eden ilişkisel bir dizi.
// Formun adıyla eşleşmesi için key/ array adlarını değiştirmeniz gerekebilir.
 
$postValues = array(
    'username' => USERNAME,
    'password' => PASSWORD,
    'policy_confirmed' => 1
);
 
//CURL'yi başlat.
$curl = curl_init();
 
curl_setopt($curl, CURLOPT_URL, LOGIN_ACTION_URL);
 
curl_setopt($curl, CURLOPT_POST, true);
 
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postValues));
 
// Herhangi bir HTTPS hatası almak istemiyoruz.
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
 
// Çerez ayrıntılarımızın nerede saklandığı bilgisi. Bu genellikle gerekli
// kimlik doğrulaması için, oturum kimliği genellikle çerez dosyasına kaydedilir.
curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIE_FILE);
 
curl_setopt($curl, CURLOPT_USERAGENT, USER_AGENT);
 
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
 
curl_setopt($curl, CURLOPT_REFERER, LOGIN_FORM_URL);
 
// Herhangi bir yönlendirmeyi takip etmek ister miyiz?
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
 
//Giriş istediğini çalıştıralım.
echo curl_exec($curl);
 die;

//Hata kontrolü
if(curl_errno($curl)){
    throw new Exception(curl_error($curl));
}
 
//Şu an giriş yaptık. Şifre korumalı bir sayfaya erişmeye çalışalım
curl_setopt($curl, CURLOPT_URL, 'http://example.com/protected-page.php');
 
//Aynı çerez dosyasını kullanalım.
curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIE_FILE);
 
curl_setopt($curl, CURLOPT_USERAGENT, USER_AGENT);
 
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
 
//Sonucu ekrana yazdıralım. 
echo curl_exec($curl);