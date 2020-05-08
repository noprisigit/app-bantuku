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

function resizeImage($filename, $folder_name)
{
    $th = get_instance();

    $source_path = $_SERVER['DOCUMENT_ROOT'] . '/bantuku/assets/dist/img/'. $folder_name .'/' . $filename;
    $target_path = $_SERVER['DOCUMENT_ROOT'] . '/bantuku/assets/dist/img/'.$folder_name.'/thumbnail';

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