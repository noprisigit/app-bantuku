<?php

function dd($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    exit;
}

function random_strings($length_of_string) 
{ 
    // String of all alphanumeric character 
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
  
    // Shufle the $str_result and returns substring 
    // of specified length 
    return substr(str_shuffle($str_result),  0, $length_of_string); 
} 

function generateOrderNumber()
{
    $str_result = '0123456789876543210';
    return substr(str_shuffle($str_result), 0, 8);
}

function generateInvoiceNumber()
{
    $str_result = '0123456789876543210';
    return substr(str_shuffle($str_result), 0, 12);
}

function generate_code($length)
{
    $str_result = '0123456789';

    return substr(str_shuffle($str_result), 0, $length);
}

function generateTokenLogin()
{
    $token = openssl_random_pseudo_bytes(128);
    $token = bin2hex($token);
    return $token;
}

function resizeImage($filename, $folder_name)
{
    $th = get_instance();

    $source_path = $_SERVER['DOCUMENT_ROOT'] . '/app-bantuku/assets/dist/img/'. $folder_name .'/' . $filename;
    $target_path = $_SERVER['DOCUMENT_ROOT'] . '/app-bantuku/assets/dist/img/'.$folder_name.'/thumbnail';

    // dd($source_path . " & " . $target_path);
    $config_manip = array(
        'image_library' => 'gd2',
        'source_image' => $source_path,
        'new_image' => $target_path,
        'maintain_ratio' => TRUE,
        'create_thumb' => TRUE,
        'thumb_marker' => '_thumb',
        'width' => 150,
        'height' => 150
    );


    $th->load->library('image_lib', $config_manip);
    if (!$th->image_lib->resize()) {
        echo $th->image_lib->display_errors();
    }

    $th->image_lib->clear();
}

function sendMessage($phone, $message) {
    $userkey = "53ab867e7aa5";
    $passkey = "100e821ffc195a7cafc73cf5";
    $telp = $phone;
    $text = $message;
    $url = "https://masking.zenziva.net/api/sendsms/";

    $curlHandler = curl_init();
    curl_setopt($curlHandler, CURLOPT_URL, $url);
    curl_setopt($curlHandler, CURLOPT_HEADER, 0);
    curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlHandler, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curlHandler, CURLOPT_TIMEOUT,30);
    curl_setopt($curlHandler, CURLOPT_POST, 1);
    curl_setopt($curlHandler, CURLOPT_POSTFIELDS, array(
        'userkey' => $userkey,
        'passkey' => $passkey,
        'nohp' => $telp,
        'pesan' => $text
    ));
    $results = json_decode(curl_exec($curlHandler), true);
    curl_close($curlHandler);
}