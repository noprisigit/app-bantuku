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