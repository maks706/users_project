<?php
namespace App;
class Image{
    public static function upload_image($image){
        $file=$image['name'];
        var_dump($image);
        $extension=pathinfo($file)['extension'];
        $new_filename=uniqid() . "." . $extension;
        move_uploaded_file($image['tmp_name'],'img/demo/avatars/' . $new_filename);
        return $new_filename;
    }
}

?>