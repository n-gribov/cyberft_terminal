<?php

namespace common\commands;

use common\base\Model;
use common\commands\BaseCommandInterface;
use yii\helpers\ArrayHelper;

/**
 * Base command class
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package core
 * @subpackage command
 *
 * @property array $result Command result
 */
class BaseCommand extends Model implements BaseCommandInterface
{
    /**
     * @var string $_code Command code
     */
    protected $_code;

    /**
     * @var string $_entity Command entity
     */
    protected $_entity;

    /**
     * @var string $_entityId Entity ID
     */
    protected $_entityId;

    /**
     * @var integer $_acceptsCount Count of accepts for command
     */
    protected $_acceptsCount;

    /**
     * @var integer $_userId User ID
     */
    protected $_userId;

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(),
                [
                'code', 'entity', 'entityId', 'acceptsCount', 'userId'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'entity', 'entityId', 'acceptsCount'], 'required'],
        ];
    }

    /**
     * Get command code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * Get entity
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->_entity;
    }

    /**
     * Get entity ID
     *
     * @return integer
     */
    public function getEntityId()
    {
        return $this->_entityId;
    }

    /**
     * Get user ID
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->_userId;
    }

    /**
     * Get accepts count
     *
     * @return integer
     */
    public function getAcceptsCount()
    {
        return 0;
    }

    /**
     * Set command code
     *
     * @param string $_code Command code
     */
    public function setCode($code)
    {
        $this->_code = $code;
    }

    /**
     * Set command entity
     *
     * @param type $entity Command entity
     */
    public function setEntity($entity)
    {
        $this->_entity = $entity;
    }

    /**
     * Set command entity ID
     *
     * @param integer $entityId Command entity ID
     */
    public function setEntityId($entityId)
    {
        $this->_entityId = $entityId;
    }

    /**
     * Set user ID
     *
     * @param integer $userId User ID
     */
    public function setUserId($userId)
    {
        $this->_userId = $userId;
    }

    /**
     * Set accepts count
     *
     * @param intger $acceptsCount Count of accepts for command
     */
    public function setAcceptsCount($acceptsCount)
    {
        $this->_acceptsCount = $acceptsCount;
    }

    /**
     * Serialize arguments
     *
     * @see http://php.net/manual/en/function.json-encode.php
     * @return string Return JSON string or FALSE
     */
    public function serializeArgs()
    {
        return json_encode($this->attributes);
    }
}