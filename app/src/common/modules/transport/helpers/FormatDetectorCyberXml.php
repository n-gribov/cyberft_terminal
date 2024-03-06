<?php

namespace common\modules\transport\helpers;

use common\base\interfaces\FormatDetectorInterface;
use common\models\cyberxml\CyberXmlDocument;

/**
 * Format Detector for CyberXml import files
 */
class FormatDetectorCyberXml implements FormatDetectorInterface
{
    public static function detect($filePath, $options = [])
    {
        $cyxDoc = new CyberXmlDocument();
        
        if (!$cyxDoc->loadXml(file_get_contents($filePath))) {

            return false;
        }

        return $cyxDoc;
    }

}