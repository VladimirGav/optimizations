<?php

namespace modules\optimizations\services;


use core\image\ImageResize;

class sFilesHelper
{

    static function instance(){
        return new sFilesHelper;
    }

    public function saveImgsArr($uploaddir = '/uploads/files/', $input_name='file'){
        $ImgsArr=[];
        if(!empty($_FILES[$input_name]['name'])){
            foreach ($_FILES[$input_name]['name'] as $keyFile => $File){
                $file_path = sFilesHelper::instance()->saveImg($uploaddir, $input_name, $keyFile);
                if(!empty($file_path)){
                    $ImgsArr[]=$file_path;
                }
            }
        }
        return $ImgsArr;
    }

    /**
     * @param string $uploaddir - директория
     * @param string $input_name - имя инпута type=file
     * @return bool|string - путь к картинке
     */
    public function saveImg($uploaddir = '/uploads/files/', $input_name='file', $keyFile = false){
        /*
         * Если нужна одна фотка из массива
         */
        if($keyFile!==false){
            if(empty($_FILES[$input_name]['name'][$keyFile])){
                return false;
            }
            /*
             * Создадим папку если нет
             */
            $dirFolder = $_SERVER['DOCUMENT_ROOT'].$uploaddir;
            if (!file_exists($dirFolder)) {
                mkdir($dirFolder, 0755, true);
            }
            if(isset($_FILES[$input_name]['name'][$keyFile])){

                $img_name = basename($_FILES[$input_name]['name'][$keyFile]);
                $rash = substr($img_name, strrpos($img_name, '.') + 1);
                $img_name = time().rand(10000,99999).'.'.$rash;
                $file_path = $uploaddir . $img_name;
                $uploadfile = $_SERVER['DOCUMENT_ROOT'].$file_path;

                if (move_uploaded_file($_FILES[$input_name]["tmp_name"][$keyFile], $uploadfile)) {
                    //echo "Файл корректен и был успешно загружен.\n";
                } else {
                    return false;
                }

                return $file_path;
            }
        } else {
            if(empty($_FILES[$input_name]['name'])){
                return false;
            }
            /*
             * Создадим папку если нет
             */
            $dirFolder = $_SERVER['DOCUMENT_ROOT'].$uploaddir;
            if (!file_exists($dirFolder)) {
                mkdir($dirFolder, 0755, true);
            }
            if(isset($_FILES[$input_name]['name'])){

                $img_name = basename($_FILES[$input_name]['name']);
                $rash = substr($img_name, strrpos($img_name, '.') + 1);
                $img_name = time().rand(10000,99999).'.'.$rash;
                $file_path = $uploaddir . $img_name;
                $uploadfile = $_SERVER['DOCUMENT_ROOT'].$file_path;

                if (move_uploaded_file($_FILES[$input_name]["tmp_name"], $uploadfile)) {
                    //echo "Файл корректен и был успешно загружен.\n";
                } else {
                    return false;
                }

                return $file_path;
            }
        }
    }

    /**
     * @param string $uploaddir - директория
     * @param string $name_file - имя
     * @return bool|string - путь к картинке
     */
    public function saveImgSrc($link, $uploaddir = '/uploads/files/', $name_file='file.jpg'){
        // Создадим папку если нет
        $dirFolder = $_SERVER['DOCUMENT_ROOT'].$uploaddir;
        if (!file_exists($dirFolder)) {
            mkdir($dirFolder, 0755, true);
        }

        $dirFolder = $_SERVER['DOCUMENT_ROOT'].$uploaddir;
        $file = file_get_contents($link);
        if(empty($file)){
            return false;
        }
        if(file_put_contents($dirFolder.$name_file, $file)){
            return $uploaddir.$name_file;
        }
        return false;
    }


    /**
     * Получить расширение по имени файла
     * @param $name_file
     * @return mixed
     */
    public function getExpansion($name_file){
        preg_match('/.+\.(\w+)$/xis', $name_file, $pocket);
        return mb_strtolower($pocket[1]);
    }


    /**
     * Получить тип файла, картинка или видео
     * @param $name_file
     * @return string
     */
    public function getTypeFile($name_file){
        $imgArr=['jpg','jpeg','png','gif','bmp','dib','tif','tiff'];
        $videoArr=['mp4','mp4','m4v','f4v','f4a','m4b','m4r','f4b','mov','3gp','3gp2','3g2','3gpp','3gpp2','ogg','oga','ogv','ogx','wmv','wma','asf*','webm','flv','avi','hdv','OP1a','OP-Atom','ts','wav','lxf','gxf','vob'];
        $Expansion = sFilesHelper::instance()->getExpansion($name_file);
        if(in_array($Expansion,$imgArr)){
            return 'image';
        }
        if(in_array($Expansion,$videoArr)){
            return 'video';
        }
    }
}