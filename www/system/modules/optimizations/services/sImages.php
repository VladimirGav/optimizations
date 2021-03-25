<?php
namespace modules\optimizations\services;


class sImages
{
    protected static $instance;

    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Оптимизировать картинки и заменить src
     * @param $HTML
     * @return string|string[]
     */
    public function optimizationsImgContent($HTML){
        $newHTML = $HTML;

        // Получим все картинки из HTML
        $images = [];
        preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $HTML, $media);
        $data = preg_replace('/(img|src)("|\'|="|=\')(.*)/i', "$3", $media[0]);

        foreach ($data as $url) {
            $info = pathinfo($url);
            if (isset($info['extension'])) {
                if (($info['extension'] == 'jpg') ||
                    ($info['extension'] == 'jpeg') ||
                    ($info['extension'] == 'gif') ||
                    ($info['extension'] == 'png'))
                    $images[] = $url;
            }
        }

        // Оптимизируем их и заменяем на оптимизированный кэш
        foreach($images as $image){
            $fileImg = $_SERVER['DOCUMENT_ROOT'].$image;
            if (file_exists($_SERVER['DOCUMENT_ROOT'].$image)) {
                $sizeArr = getimagesize($fileImg);
                if(!empty($sizeArr[0])){

                $imageNew = sToolsImage::instance()->getCacheResizeImage($image, false, false, false, true);
                    /*$w3 = $sizeArr[0];

                    $imageNew320 = $imageNew;
                    $w1 = $sizeArr[0];

                    $imageNew480 = $imageNew;
                    $w2 = $sizeArr[0];


                        if($sizeArr[0]>320){
                            $w1 = 320;
                            $imageNew320 = sToolsImage::instance()->getCacheResizeImage($image, 320, false, false, true);
                        }
                        if($sizeArr[0]>480){
                            $w2 = 480;
                            $imageNew480 = sToolsImage::instance()->getCacheResizeImage($image, 480, false, false, true);
                        }

                        $newHTML = str_replace(' src="'.$image, '
                        sizes="(max-width: 320px) '.$w1.'px, (max-width: 480px) '.$w2.'px, '.$w3.'px"
                        srcset="'.$imageNew320.' '.$w1.'w, '.$imageNew480.' '.$w2.'w,'.$imageNew.' '.$w3.'w"
                        src="'.$imageNew.'"
                        data-orig-src="'.$image, $newHTML);*/
                    $newHTML = str_replace($image, $imageNew, $newHTML);
                }
            }
        }
        /*echo '<pre>';
        print_r($images);
        echo '</pre>';*/

        //sToolsImage::instance()->getCacheResizeImage($AdsRow['img'], 256, 256, false, true);
        //\core\ToolsImage::instance()->getCacheResizeImage($AdsRow['img'], 256, 256, false, true)
        return $newHTML;
    }

    public function getImgReplace($HTML){
        $newHTML = $HTML;

        // Получим все картинки из HTML
        $images = [];
        preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $HTML, $media);
        $data = preg_replace('/(img|src)("|\'|="|=\')(.*)/i', "$3", $media[0]);

        foreach ($data as $url) {
            $info = pathinfo($url);
            if (isset($info['extension'])) {
                if (($info['extension'] == 'jpg') ||
                    ($info['extension'] == 'jpeg') ||
                    ($info['extension'] == 'gif') ||
                    ($info['extension'] == 'png'))
                    $images[] = $url;
            }
        }

        // Оптимизируем их и заменяем на оптимизированный кэш
        foreach($images as $image){
            $newHTML = str_replace('src="'.$image, 'data-optimizations="true" data-src="'.$image, $newHTML);
        }

        return $newHTML;
    }

    /**
     * Оптимизируем массив картинок, создаем кеш и возвращаем
     * Прием Array([0] => Array(
    [id] => 0
    [img] => /logo.png
    [max_width] => 1
    )
     * @param $imgArr
     * @return array
     */
    public function getImgOptArr($imgArr){
        $imgOptArr=[];

        foreach ($imgArr as $imgRow){
            $imageNew='';
            $status=0;
            if(!empty($imgRow['img'])){
                $width = false;
                if(!empty($imgRow['max_width'])){
                    $width = $imgRow['max_width'];
                }
                $imageNew = sToolsImage::instance()->getCacheResizeImage($imgRow['img'], $width, false, false, true);
                if(!empty($imageNew)){
                    $status=1;
                } else {
                    $imageNew = $imgRow['img'];
                }
            }
            $imgOptArr[]=[
                'id' => $imgRow['id'],
                'img' => $imageNew,
                'status' => $status,
            ];
        }

        return $imgOptArr;
    }

}