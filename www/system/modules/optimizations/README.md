Чтобы включить кеширование и новые форматы webp
1. sToolsImage - работа с картинками, нужно установить 
composer require spatie/image

2. Добавить в difines 
define('_OPTIMIZE_IMG_CONTENT_', true);

3. В core\templateHelper function display() добавить
if(defined('_OPTIMIZE_IMG_CONTENT_') && !empty(_OPTIMIZE_IMG_CONTENT_)){
            if (class_exists('modules\optimizations\widgets\wOptimizations')) {
                $Content = wOptimizations::instance()->optimizationsImgs($Content);
            }
        }
        
 
