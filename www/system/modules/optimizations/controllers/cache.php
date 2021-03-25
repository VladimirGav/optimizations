<?
namespace modules\optimizations\controllers;

use core\controller;
use modules\optimizations\services\sCacheHtml;
use modules\optimizations\services\sImages;

/*
 *
 */

class cache extends controller
{

    /**
     * Перекидываем на список
     */
    public function showIndex()
    {
        header("Location: /");
    }

    /**
     * Очистить CacheHtml
     */
    public function showClear($cacheName='')
    {
        if(!empty($cacheName)){
            sCacheHtml::instance()->clearCacheHtml($cacheName);
        } else {
            sCacheHtml::instance()->clearAllCacheHtml();
        }

        echo json_encode(['error'=> 0, 'data' => 'success'.$cacheName]);
        exit;


    }

}