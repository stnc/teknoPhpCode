<?php
//dolar cek
$apiKey = "4RfMup6BllrkoMTz4rwZu2:0blygMZDxJtI3lyqhDblpC";
if (isset($_GET['action']) && $_GET['action'] == "exchange")
  {

    $piyasalar = array();

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.collectapi.com/economy/allCurrency",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization: apikey " . $apiKey . "",
            "content-type: application/json"
        ) ,
    ));

    $responseExchange = curl_exec($curl);

    $exchange = json_decode($responseExchange, true);
    // print_r( $exchange);
    

    $err = curl_error($curl);

    curl_close($curl);

    if ($err)
      {
        echo "cURL Error #:" . $err;
      }
    else
      {
        echo $responseExchange;
      }

    //--------------------------------
    

    $curl2 = curl_init();
    curl_setopt_array($curl2, array(
        CURLOPT_URL => "https://api.collectapi.com/economy/goldPrice",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization: apikey " . $apiKey . "",
            "content-type: application/json"
        ) ,
    ));

    $response_gold = curl_exec($curl2);

    $gold_price = json_decode($response_gold, true);

    // print_r( $gold_price);
    $err = curl_error($curl2);

    curl_close($curl2);

    if ($err)
      {
        echo "cURL Error #:" . $err;
      }
    else
      {
        echo $response_gold;
      }

    // option7 := entity.Options{OptionName: "hayvan_dusuk_agirligi", OptionValue: "0-200"}
    // db.Debug().Create(&option7)
    

    // row := r.db.Debug().Table(optionTableName).Select("option_id").Where("option_name = ?", name).Row()
    // row.Scan(&returnValue)
    

    $piyasalar['stncWpKiosk_text_field_dolar'] = $exchange["result"][0]["selling"];
    $piyasalar['stncWpKiosk_text_field_euro'] = $exchange["result"][1]["selling"];

    $piyasalar['stncWpKiosk_text_field_altin'] = $gold_price["result"][0]["selling"];
    $piyasalar['stncWpKiosk_text_field_ceyrek_altin'] = $gold_price["result"][1]["selling"];

    // print_r($piyasalar);
    $serialize_php = serialize($piyasalar);
    // print_r($serialize_php);

    // veriTabaniIslemleri("stncWpKiosk_Exchange_Settings",$serialize_php);

    
  }
/*
a:4:{s:28:"stncWpKiosk_text_field_dolar";s:5:"15.00";s:27:"stncWpKiosk_text_field_euro";s:5:"16.00";s:28:"stncWpKiosk_text_field_altin";s:3:"458";s:35:"stncWpKiosk_text_field_ceyrek_altin";s:3:"800";}
*/

if (isset($_GET['action']) && $_GET['action'] == "hava"){

  $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.collectapi.com/weather/getWeather?data.lang=tr&data.city=kayseri",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "authorization: apikey ".$apiKey."",
      "content-type: application/json"
    ),
  ));
  
  $response = curl_exec($curl);
  $err = curl_error($curl);
  
  curl_close($curl);
  
  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $response;
  }



  
  veriTabaniIslemleri("stncWpKiosk_Weather_Today",$response);

  }




  function veriTabaniIslemleri($optionName,$optionValue){

    //////
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = 'qggmkvwm';
    $dbname = 'summit';

    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    $mysqli->set_charset("utf8");
    if ($mysqli->connect_errno)
      {
        printf("Connect failed: %s<br />", $mysqli→connect_error);
        exit();
      }
    // printf('Connected successfully.<br />');

    $sql = "SELECT option_id FROM wp_options WHERE option_name = '".$optionName."'";

    $result = $mysqli->query($sql);
    // print_r($result);
    if ($result)
      {
        if ($result->num_rows > 0)
          {
            // Associative array
            $row = $result->fetch_assoc();
            $row["option_id"];
          //  echo $sql2 = "UPDATE wp_options set option_value = '".$serialize_php."' where option_id = ".$row["option_id"]."";
            $sql2 = "UPDATE wp_options SET option_value = '".$optionValue."' WHERE option_id = ".$row["option_id"]."";

          if ($mysqli->query($sql2)) {
              // printf("Table updated successfully.<br />");
            }


          }
        else
          {
            echo "No record found";
          }
      }
    else
      {
        echo "Error in ";
      }

  }