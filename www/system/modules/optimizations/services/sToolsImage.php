<?
namespace modules\optimizations\services;


use Spatie\Image\Image;
use Spatie\Image\Manipulations;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class sToolsImage
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
     * Работает на git Spatie
     * composer require spatie/image
     * Нужно установить
     * yum install jpegoptim
     * yum install optipng
     * yum install pngquant
     * sudo npm install -g svgo
     * yum gifsicle
     */
    public function ImageOptimizer($pathToImage, $pathToOutput = null)
    {
        $optimizerChain = OptimizerChainFactory::create();

        $pathToImage = $_SERVER['DOCUMENT_ROOT'] . $pathToImage;
        if (!empty($pathToOutput)) {
            $pathToOutput = $_SERVER['DOCUMENT_ROOT'] . $pathToOutput;
        }

        if (file_exists($pathToImage)) {
            $optimizerChain->optimize($pathToImage, $pathToOutput);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Выдаем картинку из кеша, если нет то создаем
     */
    public function getCacheResizeImage($pathOriginal, $width = false, $height = false, $watermark=false, $create_webp=false)
    {

        $dirSave = '/uploads/cache/img/';
        $pathOriginal = $_SERVER['DOCUMENT_ROOT'] . $pathOriginal;
        $fileName = basename($pathOriginal);

        if (!file_exists($pathOriginal)) {
            return false;
        }
        if (!is_file($pathOriginal)) {
            return false;
        }

        /*
         * Генерируем путь сохранения
         */
        $pathFolder = '';
        if (!empty($width)) {
            $pathFolder .= $width;
        }
        $pathFolder .= 'x';
        if (!empty($height)) {
            $pathFolder .= $height;
        }
        $dirToOutput = $_SERVER['DOCUMENT_ROOT'] . $dirSave . $pathFolder . '/';
        $pathToOutput = $dirToOutput . $fileName;
        /*
         * Если уже есть картинка то отдаем
         */
        if (file_exists($pathToOutput)) {
            $pathFindFile = $dirSave . $pathFolder . '/' . $fileName;

            // Если поддерживает webp
            if( strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) !== false || $_SERVER['HTTP_ACCEPT']=='*/*') {
                $fileExpansion = sFilesHelper::instance()->getExpansion($pathFindFile);
                $pathFindFileWebP = str_replace($fileExpansion,'webp',$pathFindFile);

                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $pathFindFileWebP)) {
                    return $pathFindFileWebP;
                }

            }

            return $pathFindFile;
        }

        /*
         * Если нет то создадим миниатюру
         */
        if (!file_exists($pathOriginal)) {
            return false;
        }
        // Если нет папки то создадим
        if (!file_exists($dirToOutput)) {
            if (!mkdir($dirToOutput, 0755, true)) {
                return false;
            }
        }

        $Image = Image::load($pathOriginal);
        if(!empty($width) && !empty($height)){
            $Image->fit(Manipulations::FIT_FILL, $width, $height)
                ->background('FFFFFF');
        } else {
            if (!empty($width)) {
                $Image->width($width);
            }
            if (!empty($height)) {
                $Image->height($height);
            }
        }
        if (!empty($watermark)) {
            $Image->watermark(sToolsImage::instance()->createWatermark($watermark));
            $Image->watermarkOpacity(50);
            $Image->watermarkPadding(5);
            $Image->watermarkPosition(Manipulations::POSITION_TOP_LEFT);
        }

        $Image->save($pathToOutput);
        OptimizerChainFactory::create()->optimize($pathToOutput);

        if($create_webp){
            $fileExpansion = sFilesHelper::instance()->getExpansion($fileName);
            $info = pathinfo($fileName);
            $fileNameNotExpansion = $info['filename'];

            Image::load($dirToOutput . $fileName)
                ->format(Manipulations::FORMAT_WEBP)
                ->save($dirToOutput .$fileNameNotExpansion.'.webp');
            OptimizerChainFactory::create()->optimize($dirToOutput .$fileNameNotExpansion.'.webp');
        }

        return $dirSave . $pathFolder . '/' . $fileName;

    }

    function createWatermark($text='',$fontsize=5,$width  = 256,$height = 256){
        if(empty($text)){
            $text = $_SERVER['HTTP_HOST'];
        }

        $dir = $_SERVER['DOCUMENT_ROOT'].'/uploads/watermark/';
        $fileName=md5($text.$fontsize.$width.$height).'_watermark.png';
        $pathToOutput = $dir .$fileName;

        // Если нет папки то создадим
        if (!file_exists($dir)) {
            if (!mkdir($dir, 0755, true)) {
                return false;
            }
        }

        if (file_exists($pathToOutput)) {
            return $pathToOutput;
        }

        $img = imagecreate($width, $height);

        // Transparent background
        $black = imagecolorallocate($img, 0, 0, 0);
        imagecolortransparent($img, $black);

        // Red text
        $red = imagecolorallocate($img, 0, 0, 0);
        imagestring($img, $fontsize, 0, 0, $text, $red);

        //header('Content-type: image/png');
        imagepng($img, $pathToOutput);
        //imagedestroy($img);

        return $pathToOutput;
    }
}