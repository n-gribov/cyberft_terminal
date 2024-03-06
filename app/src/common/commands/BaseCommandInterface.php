<?php

namespace common\commands;

/**
 * Base command interface
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package core
 * @subpackage command
 */
interface BaseCommandInterface
{
    /**
     * Get enity ID
     */
    public function getEntityId();

    /**
     * Get entity
     */
    public function getEntity();

    /**
     * Get command code
     */
    public function getCode();

    /**
     * Get user ID
     */
    public function getUserId();

    /**
     * Get accept count
     */
    public function getAcceptsCount();

    /**
     * Set entity ID
     *
     * @param string $entityId Command entity ID
     */
    public function setEntityId($entityId);

    /**
     * Set entity
     *
     * @param string $entity Command entity
     */
    public function setEntity($entity);

    /**
     * Set command code
     *
     * @param string $code Command code
     */
    public function setCode($code);

    /**
     * Set user ID
     *
     * @param integer $userId User ID
     */
    public function setUserId($userId);

    /**
     * Get accepts count
     *
     * @param integer $acceptsCount
     */
    public function setAcceptsCount($acceptsCount);

    /**
     * Serialize data
     */
    public function serializeArgs();
}