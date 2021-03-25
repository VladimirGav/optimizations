<?
namespace modules\optimizations\services;

class sCacheHtml
{

    protected static $instance;
    protected $dir = __DIR__.'/../../../../www/uploads/cache/template/';
    // Очистить кэш /optimizations/cache/clear

    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Создать кэш
     * @param $cacheName
     * @param $CacheHtml
     */
    public function saveCacheHtml($cacheName, $CacheHtml){
        if(!isset($_REQUEST['clear_cache'])){
            if (!file_exists($this->dir)) {
                mkdir($this->dir, 0777, true);
            }
            $fp = fopen($this->dir.$cacheName, "w");
            fwrite($fp,$CacheHtml);
            fclose($fp);
        }
    }

    /**
     * Получить содержимое кеша если найдено
     * @param $cacheName
     * @return false|string
     */
    public function getCacheHtml($cacheName){
        $contentCacheHtml='';
        // Если кеш найден, то выдаем кэш
        if(file_exists($this->dir.$cacheName)){
            $contentCacheHtml = file_get_contents($this->dir.$cacheName);
        }
        return $contentCacheHtml;
    }

    /**
     * Очистить кэш по имени
     * @param $cacheName
     */
    public function clearCacheHtml($cacheName){
        // Если нужно удалить кэш
        if(file_exists($this->dir.$cacheName)){
            unlink($this->dir.$cacheName);
        }
    }

    /**
     * Очистить весь кэш
     * @return bool
     */
    public function clearAllCacheHtml(){
        if (file_exists($this->dir)) {
            foreach (glob($this->dir.'*') as $file) {
                unlink($file);
            }
        }
        return true;
    }

    /**
     * Показать кэш если найден и завершить скрипт
     * @param $cacheName
     */
    public function showCacheHtml($cacheName){
        // Если есть, то показываем и завершаем
        $CacheHtml = sCacheHtml::instance()->getCacheHtml($cacheName);
        if(!empty($CacheHtml)){
            echo $CacheHtml;
            exit;
        }
    }
}