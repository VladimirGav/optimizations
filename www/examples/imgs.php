<?php
/**
 * Оптимизация картинок, создание webp картинок, замена картинок не лету
 * Использует composer require spatie/image
 * Для нормальной работы нужно установить на сервер
 * yum install jpegoptim
 * yum install optipng
 * yum install pngquant
 * sudo npm install -g svgo
 * yum gifsicle
 */

use modules\optimizations\widgets\wOptimizations;

// Подключим автозагрузчик composer, defines
require_once __DIR__ .'/../system/defines.php';
require_once __DIR__ .'/../system/vendor/autoload.php';

// Пример HTML кода
$exampleHTML = '
<img src="/examples/imgs/1.jpg" width="300"><br> 
<img src="/examples/imgs/2.jpg" width="300"><br>
<img src="/examples/imgs/3.jpg" width="300"><br>
<img src="/examples/imgs/4.jpg" width="300"><br>
';

// Оптимизируем картинки и создаем webp, если еще не создавали, заменяем пути картинкам на новые
if (class_exists('modules\optimizations\widgets\wOptimizations')) {
    $exampleHTML = wOptimizations::instance()->optimizationsImgs($exampleHTML);
}

echo $exampleHTML;