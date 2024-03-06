<?php

namespace common\models;

// не буду сейчас имплементировать интерфейс коллекций, т.к. он скорее всего будет невостребован.

class CryptoproContainerCollection
{
    private $_containers = [];
    private $_source = [];
    public $errorCode = null;
    public $returnCode = null;

    public function __construct($source, $filter = [])
    {
        if ($source) {
            $this->load($source, $filter);
        }
    }

    private static function filterContainer($filter, $container)
    {
        if (empty($filter)) {
            return true;
        }

        $entries = array_uintersect_assoc($filter, $container, 'strcasecmp');

        return count($filter) == count($entries);
    }

    /**
     * Функция парсит вывод командной строки программы certmgr
     * и собирает атрибуты каждого контейнера в отдельный массив
     * @param array|string $source Массив строк вывода программы certmgr
     * @param array $filter Фильтр [ключ => значение, ...] для выборки нужных контейнеров
     */
    public function load($source, $filter = [])
    {
        $this->_source = $source;

        $this->_containers = [];
        $this->errorCode = null;
        $this->returnCode = null;
        $container = [];

        // Построчно парсим source, разбирая строки с парами "ключ : значение"
        $key = null;
        $prevKey = null;
        foreach($source as $line) {
            $pos = strpos($line, ':');
            if ($pos === false) {
                continue;
            }

            $prevKey = $key;
            $key = trim(substr($line, 0, $pos));
            $value = trim(substr($line, $pos + 1));

            if ($key == '[ErrorCode') {
                $this->errorCode = rtrim($value, ']');

                break;
            }

            if ($key == '[ReturnCode') {
                $this->returnCode = rtrim($value, ']');

                break;
            }

            if ($key !== $prevKey && array_key_exists($key, $container)) {
                // Повторное появление ключа означает, что начался новый контейнер (за исключением ситуации, когда ключ совпадает с предыдущим)
                // Проверяем текущий контейнер на совпадение с фильтром, если он не пустой
                // В случае успеха сохраняем контейнер в выходной массив
                if (static::filterContainer($filter, $container)) {
                    $this->_containers[] = $container;
                }

                $prevKey = null;
                $container = [];
            }

            $container[$key] = $value;
        }

        if (!empty($container) && static::filterContainer($filter, $container)) {
            $this->_containers[] = $container;
        }
    }

    public function first()
    {
        return $this->get(0);
    }

    public function get($pos)
    {
        return isset($this->_containers[$pos]) ? $this->_containers[$pos] : null;
    }

    public function getContainers()
    {
        return $this->_containers;
    }

    public function count()
    {
        return count($this->_countainers);
    }

    public function errorCodeOK()
    {
        return $this->errorCode === '0x00000000' || $this->returnCode === '0';
    }

}
