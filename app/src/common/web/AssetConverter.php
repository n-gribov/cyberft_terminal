<?php

namespace common\web;

use yii\base\Exception;

/**
 * @inheritdoc
 */
class AssetConverter extends \yii\web\AssetConverter {

    /**
     * @inheritdoc
     * 3rd element is prefix for file in full path
     * you can change directory lvl up or set full path to target directory in your rule
     */
    public $commands = [
        'less' => ['css', 'lessc {from} {to} --no-color --source-map', '../css'],
        'scss' => ['css', 'sass {from} {to} --sourcemap', '../css'],
        'sass' => ['css', 'sass {from} {to} --sourcemap', '../css'],
        'styl' => ['css', 'stylus < {from} > {to}', '../css'],
        'coffee' => ['js', 'coffee -p {from} > {to}', '../js'],
        'ts' => ['js', 'tsc --out {to} {from}', '../js'],
    ];

    /**
     * @inheritdoc
     */
    public function convert($asset, $basePath)
    {
        $pos = strrpos($asset, '.');
        if ($pos !== false) {
            $ext = substr($asset, $pos + 1);
            if (isset($this->commands[$ext])) {
                list ($ext, $command, $toPathPrefix) = $this->commands[$ext];
                $result = substr($asset, 0, $pos + 1) . $ext;
                list ($realPath, $result) = $this->getTargetPath($basePath, $result, $toPathPrefix);
                if (@filemtime($realPath) < filemtime("$basePath/$asset")) {
                    $this->runCommand($command, $basePath, $asset, $result);
                }

                return $result;
            }
        }

        return $asset;
    }

    protected function getTargetPath($basePath, $result, $toPathPrefix)
    {
        if (substr($toPathPrefix, 0, 1) === '/') {
            $realPath = \Yii::getAlias('@webroot') . "/$result";
        } else {
            if (!($realPath = realpath(dirname($basePath . '/' . $result) . '/' . $toPathPrefix))) {
                throw new Exception('Save path ' . dirname($basePath . '/' . $result) . '/' . $toPathPrefix . ' not found for file ' . $result);
            }
            $realPath .= '/' . basename($result);
            $result = str_replace(realpath($basePath) . '/', '', $realPath);
        }
        return [$realPath, $result];
    }
}
