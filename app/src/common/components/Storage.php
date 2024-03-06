<?php
/**
 * Компонент хранилища данных
 */
namespace common\components;

use common\components\storage\Resource;
use common\components\storage\StoredFile;
use Yii;
use yii\base\Component;

/**
 * Класс хранилища данных
 */
class Storage extends Component
{
    // Ид сервиса по умолчанию
    const DEFAULT_SERVICE_ID = 'storage';
    // Ид ресурса по умолчанию
    const DEFAULT_RESOURCE_ID = 'default';
    // Путь в хранилище по умолчанию
    const DEFAULT_STORAGE_PATH = '@storage';
    // Максимальное количество файлов в папке
    const MAX_FILES = 10000;

    /**
     * Метод инициализирует ресурсы хранения
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Yii::$app->registry->registerStorageResources(
            self::DEFAULT_SERVICE_ID, self::DEFAULT_STORAGE_PATH
        );
    }

    /**
     * Метод помещает данные в хранилище
     *
     * @param string $data Data
     * @param string $serviceId  Service ID
     * @param string $resourceId Resource ID
     * @param string $filename Original file name
     * @return StoredFile|null
     */
    public function putData($data, $serviceId, $resourceId = self::DEFAULT_RESOURCE_ID, $filename = '')
    {
        // Получить ресурс по ид сервиса и ресурса
        $resource = Yii::$app->registry->getStorageResource($serviceId, $resourceId);

        if (!$resource) {
            return null;
        }

        // Записать данные в ресурс
        $fileInfo = $resource->putData($data, $filename);
        // Сформировать в БД запись о сохранённом файле и вернуть его
        return $this->storeFile($fileInfo, $filename);
    }

    /**
     * Метод помещает файл в хранилище
     *
     * @param string $path File path
     * @param string $serviceId  Service ID
     * @param string $resourceId Resource ID
     * @param string $filename Original file name
     * @return StoredFile|null
     */
    public function putFile($path, $serviceId, $resourceId = self::DEFAULT_RESOURCE_ID, $filename = '')
    {
        // Получить ресурс по ид сервиса и ресурса
        $resource = Yii::$app->registry->getStorageResource($serviceId, $resourceId);

        if (!$resource) {
            return null;
        }

        // ЗАписать файл в ресурс
        $fileInfo = $resource->putFile($path, $filename);
        // Сформировать в БД запись о сохранённом файле и вернуть его
        return $this->storeFile($fileInfo, $filename);
    }

    /**
     * Метод помещает данные из потока в хранилище
     *
     * @param type $readStream Resource
     * @param string $serviceId  Service ID
     * @param string $resourceId Resource ID
     * @param string $filename Original file name
     * @return StoredFile|null
     */
    public function putStream($readStream, $serviceId, $resourceId = self::DEFAULT_RESOURCE_ID, $filename = '')
    {
        // Получить ресурс по ид сервиса и ресурса
        $resource = Yii::$app->registry->getStorageResource($serviceId, $resourceId);

        if (!$resource) {
            return null;
        }

        $fileInfo = $resource->putStream($readStream, $filename);
        // Сформировать в БД запись о сохранённом файле и вернуть его
        return $this->storeFile($fileInfo, $filename);
    }

    /**
     * Метод обновляет существующий файл в хранилище
     *
     * @param StoredFile $storedFile
     * @param string $data Data for save
     * @return array|false
     */
    public function updateData($storedFile, $data)
    {
        // Получить ресурс по ид сервиса и ресурса
        $resource = Yii::$app->registry->getStorageResource($storedFile->serviceId, $storedFile->resourceId);
        // Сохранить модель в БД
        $storedFile->save(); // for updateDate etc.

        return $resource->updateData($storedFile->path, $data);
    }

    /**
     * Метод регистрирует в хранилище уже созданный файл без каких-либо манипуляций с ним.
     * Некоторые процессы могут пользоваться ресурсами автономно и порождать в них файлы.
     * Так как файлы могут быть большого размера, копирование их может загрузить диск
     * и в результате система упрется в I/O.
     * Чтобы не пересоздавать файл в хранилище, просто передаем готовый путь.
     * в целях формальной безопасности хранилище проверяет, валиден ли ли путь для данного ресурса
     * @param type $path
     * @param type $serviceId
     * @param type $resourceId
     * @param type $filename
     */
    public function registerFile($path, $serviceId, $resourceId = self::DEFAULT_RESOURCE_ID, $filename = '',
        $entity = '', $entityId = null
    ) {
        $resource = Yii::$app->registry->getStorageResource($serviceId, $resourceId);

        if ($resource && $resource->checkPath($path)) {
            return $this->storeFile($serviceId, $resourceId, $path, $filename, $entity, $entityId);
        }

        return null;
    }

    /**
     * Метод регистрирует файл в ресурсе
     * @param type $path
     * @param Resource $resource
     * @param type $filename
     * @param type $entity
     * @param type $entityId
     * @return null
     */
    public function registerFileInResource($path, Resource $resource, $filename = '', $entity = '', $entityId = null)
    {
        // Если существует ресурс и 
        if ($resource && $resource->checkPath($path)) {
            return $this->storeFile($resource->getServiceId(), $resource->id, $path, $filename, $entity, $entityId);
        }

        return null;
    }

    /**
     * Save file info in DB
     * @param array $fileInfo
     * @param string $filename Original file name
     * @return StoredFile|null
     */
    private function storeFile($fileInfo, $filename, $entity = '', $entityId = null)
    {
        $data = [
            'originalFilename'  => $filename,
            'path'              => $fileInfo['relativePath'],
            'serviceId'         => $fileInfo['serviceId'],
            'resourceId'        => $fileInfo['resourceId'],
            'status'            => StoredFile::STATUS_READY,
            'entity'            => $entity,
            'entityId'          => $entityId
        ];

        if (isset($fileInfo['isEncrypted'])) {
            $data['isEncrypted'] = $fileInfo['isEncrypted'];
        }

        $storedFile = new StoredFile($data);

        // Если модель успешно сохранена в БД
        if ($storedFile->save()) {
            return $storedFile;
        }

        return null;
    }

    /**
     * Читает модель
     * @param type $id
     * @return StoredFile $model
     */
    public function get($id)
    {
        return StoredFile::findOne(['id' => $id]);
    }

    /**
     * Remove file row
     *
     * @param integer $id
     */
    public function remove($id)
    {
        // Найти запись о файле в БД по id
        $storedFile = StoredFile::findOne(['id' => $id]);
        $path = $storedFile->getRealPath();

        if (is_file($path)) {
            // Удалить файл
            unlink($path);
        }

        // Удалить запись о файле из БД 
        $storedFile->delete();
    }

    public function getFileSystem($serviceId, $resourceId = null)
    {
        $path = '@storage/' . $serviceId;

        if (!empty($resourceId)) {
            $path . '/' . $resourceId;
        }

        return Yii::createObject([
            'class' => 'creocoder\flysystem\LocalFilesystem',
            'path' => $path
        ]);
    }

    public function decryptStoredFile($id)
    {
        $storedFile = $this->get($id);

        if (!$storedFile) {
            throw new \Exception("Can't get stored file data");
        }

        $path = $storedFile->getRealPath();

        if (!is_file($path)) {
            throw new \Exception("Can't open file for decrypt");
        }

        $data = file_get_contents($path);

        if (!$storedFile->isEncrypted) {
            return $data;
        }

        return Yii::$app->xmlsec->decryptData($data);
    }
}
