<?php

/*Sample Written by Shafraz Rahim*/


// returning the status to request received from ISYD. 


$data = json_decode(file_get_contents('php://input'));

$data->activeStatus = 1;

$json=json_encode($data);

echo $json;


/*------------  IdeaBiz part  is below. This is to invoke the Do Box when a certain condition is met------------------*/


// Reading the stored file to get the existing refresh token. 


$myfile = fopen("newfile.txt", "r+") or die("Unable to open file!");

$refresh_token = fread($myfile, filesize("newfile.txt"));

fclose($myfile);
	

//Getting the new access token  

$ch2 = curl_init("https://ideabiz.lk/apicall/token?grant_type=refresh_token&refresh_token=".$refresh_token."&scope=PRODUCTION");
 curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
 curl_setopt($ch2, CURLOPT_POST, 1);
    curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Basic <base 64 encoded Consumer key:consumer secret>'));
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    
$res2 = curl_exec($ch2);
curl_close($ch2);


$acces_token_data = json_decode($res2);

$refresh_token = $acces_token_data->refresh_token;
$access_token  = $acces_token_data->access_token;


$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");

fwrite($myfile,$refresh_token);

fclose($myfile);



// This is to be if a user is invoking the service when "YOU DO" part is triggered. 

$ch = curl_init("https://ideabiz.lk/apicall/isayyoudo/1.0/invokeUserCondition");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Accept: text/plain',
        'Authorization: Bearer '.$access_token
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$json);

    $res = curl_exec($ch);
    curl_close($ch);



?>