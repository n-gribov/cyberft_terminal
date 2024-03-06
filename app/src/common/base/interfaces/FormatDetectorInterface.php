<?php

namespace common\base\interfaces;

/**
 * Interface for format detectors
 */
interface FormatDetectorInterface
{
    /**
     * detect format
     * @param string $filePath
     * @param array $options
     * @return model|boolean
     */
    public static function detect($filePath, $options = []);

}