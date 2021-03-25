<?
namespace modules\optimizations\controllers;

use core\controller;
use modules\optimizations\services\sImages;

/*
 *
 */

class img extends controller
{

    /**
     * Перекидываем на список
     */
    public function showIndex()
    {
        header("Location: /");
    }

    /**
     * Сохранить данные из формы
     */
    public function showOptimimgs()
    {

        $data = $_POST;
        if(empty($data['imgs_json'])){
            echo json_encode(['error'=> 1, 'data' => 'Empty imgs']);
            exit;
        }
        $imgArr = json_decode($data['imgs_json'], true);
        if(empty($imgArr)){
            echo json_encode(['error'=> 1, 'data' => 'Empty imgArr']);
            exit;
        }

        $imgOptArr = sImages::instance()->getImgOptArr($imgArr);

        echo json_encode(['error'=> 0, 'data' => $imgOptArr]);
        exit;


    }

}