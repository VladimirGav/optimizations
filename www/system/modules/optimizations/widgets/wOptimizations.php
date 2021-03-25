<?php
namespace modules\optimizations\widgets;

use modules\optimizations\services\sImages;

class wOptimizations {

    protected static $instance;

    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Оптимизируем контент
     * @param $html
     * @return string
     */
    public function optimizationsImgs($html){
        return sImages::instance()->optimizationsImgContent($html);
    }

    /**
     * Заменить все SRC на data-optimizations="true" data-src=
     * @param $html
     * @return string|string[]
     */
    public function imgReplace($html){
        return sImages::instance()->getImgReplace($html);
    }

}