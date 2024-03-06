<?php

namespace common\helpers;

/**
 * Description of ArchiveFile
 *
 * @author nikolaenko
 */
class ArchiveFile
{
    private $_handler;

    function __construct($handler)
    {
        $this->_handler = $handler;
    }

    public static function getHandler($file)
    {
        $pathinfo = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if ($pathinfo == 'zip') {
            return new ArchiveFile(new ArchiveFileZip());
        } else if ($pathinfo == '7z') {
            return new ArchiveFile(new ArchiveFileSevenZip());
        }

        return null;
    }

    function extract($file, $destination = null)
    {
        return $this->_handler ? $this->_handler->extract($file, $destination) : null;
    }
}