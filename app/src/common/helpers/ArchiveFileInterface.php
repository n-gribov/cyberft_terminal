<?php
namespace common\helpers;

/**
 * Description of ArchiveFileInterface
 *
 * @author nikolaenko
 */
interface ArchiveFileInterface
{
    function extract($file, $destination);
}