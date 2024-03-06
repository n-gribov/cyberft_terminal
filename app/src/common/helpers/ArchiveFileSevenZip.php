<?php

namespace common\helpers;

/**
 * Description of ArchiveFileSevenZip
 *
 * @author nikolaenko
 */
class ArchiveFileSevenZip implements ArchiveFileInterface
{
    public function extract($file, $destination)
    {
        $output = [];
        $result = 0;
        exec('7z e -o' . $destination . ' ' . $file, $output, $result);

        return $result == 0;
    }
}