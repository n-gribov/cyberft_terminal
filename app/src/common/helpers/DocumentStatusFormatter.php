<?php

namespace common\helpers;

use common\document\Document;
use yii\i18n\Formatter;

class DocumentStatusFormatter extends Formatter
{
	public static $documentStatusGlyphs = [
		Document::STATUS_REGISTERED => "glyphicon-tag",
		Document::STATUS_PENDING => "glyphicon-cog",
		Document::STATUS_REJECTED => "glyphicon-ban-circle",
		Document::STATUS_DELIVERED => "glyphicon-ok-circle",
		Document::STATUS_EXPORTED => "glyphicon-saved",
		Document::STATUS_NOT_EXPORTED => "glyphicon-minus",
		Document::STATUS_ROUTED => "glyphicon-transfer",
	];
	
	public static $documentDirectionGlyphs = [
		Document::DIRECTION_IN => "glyphicon-log-in",
		Document::DIRECTION_OUT => "glyphicon-log-out",
	];
	
	public static $originClasses = [
		Document::ORIGIN_FILE => 'fa-file-text-o',
		Document::ORIGIN_XMLFILE => 'fa-code',
		Document::ORIGIN_MQ => 'fa-cloud-download',
		Document::ORIGIN_WEB => 'fa-desktop',
		Document::ORIGIN_WEB_FILE => 'fa-folder-open-o',
		Document::ORIGIN_SERVICE => 'fa-wrench',
		Document::ORIGIN_NOTAPPLICABLE => 'fa-minus',
	];

	/**
	 * Функция форматирует целое число как статус документа.
	 * @param integer $value
	 * @return string Html-строка с названием статуса и сопутствующей иконкой
	 */	
    public function asDocumentStatus($value)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }
		$class = (
			array_key_exists($value, self::$documentStatusGlyphs)
				? self::$documentStatusGlyphs[$value]
				: ''
		);
		return "<i class=\"glyphicon {$class}\"></i>&nbsp;{$this->getStatusLabel($value)}";
    }
	
	/**
	 * Функция возвращает строку-название статуса по его целочисленному коду
	 * @param integer $status
	 * @return string Название статуса
	 */
	protected function getStatusLabel($status)
	{
		return !is_null($status) && array_key_exists($status, Document::getStatusLabels()) 
				? Document::getStatusLabels()[$status]
				: '';
	}
	
	/**
	 * Функция форматирует целое число как статус исходного документа.
	 * @param integer $value
	 * @return string Html-строка с названием статуса и сопутствующей иконкой
	 */	
    public function asSourceStatus($value)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }
		$class = (
			array_key_exists($value, self::$sourceStatusGlyphs)
				? self::$sourceStatusGlyphs[$value]
				: ''
		);
		return "<i class=\"glyphicon {$class}\"></i>&nbsp;{$this->getStatusSourceLabel($value)}";
    }
	
	/**
	 * Функция возвращает строку-название статуса исходного документа по его 
	 * целочисленному коду
	 * @param integer $status
	 * @return string Название статуса
	 */
	protected function getStatusSourceLabel($status)
	{
		return !is_null($status) && array_key_exists($status, Document::getStatusSourceLabels()) 
				? Document::getStatusSourceLabels()[$status]
				: '';
	}
	
	/**
	 * Функция форматирует целое число как статус контейнера документа.
	 * @param integer $value
	 * @return string Html-строка с названием статуса и сопутствующей иконкой
	 */	
    public function asContainerStatus($value)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }
		$class = (
			array_key_exists($value, self::$containerStatusGlyphs)
				? self::$containerStatusGlyphs[$value]
				: ''
		);
		return "<i class=\"glyphicon {$class}\"></i>&nbsp;{$this->getStatusContainerLabel($value)}";
    }
	
	/**
	 * Функция возвращает строку-название статуса контейнера документа по его 
	 * целочисленному коду
	 * @param integer $status
	 * @return string Название статуса
	 */
	protected function getStatusContainerLabel($status)
	{
		return !is_null($status) && array_key_exists($status, Document::getStatusContainerLabels()) 
				? Document::getStatusContainerLabels()[$status]
				: '';
	}
	
	/**
	 * Функция форматирует целое число как статус контейнера документа.
	 * @param integer $value
	 * @return string Html-строка с названием статуса и сопутствующей иконкой
	 */	
    public function asDocumentDirection($value)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }
		$class = (
			array_key_exists($value, self::$documentDirectionGlyphs)
				? self::$documentDirectionGlyphs[$value]
				: ''
		);
		return "<i class=\"glyphicon {$class}\"></i>&nbsp;{$value}";
    }
	
	/**
	 * Функция форматирует целое число как статус контейнера документа.
	 * @param integer $value
	 * @return string Html-строка с названием статуса и сопутствующей иконкой
	 */	
    public function asDocumentOrigin($value)
    {
        if ($value === null) {
            return $this->nullDisplay;
        }
		$class = (
			array_key_exists($value, self::$originClasses)
				? self::$originClasses[$value]
				: ''
		);
		return "<code class=\"btn-default\" title=\"Origin: {$value}\"><i class=\"fa {$class}\"></i>&nbsp;{$this->getOriginLabel($value)}</code>";
    }	
	
	/**
	 * Функция возвращает наименование источника появления документа по его коду.
	 * @param integer $origin
	 * @return string Строка с названием источника
	 */	
	protected function getOriginLabel($origin)
	{
		return !is_null($origin) && array_key_exists($origin, Document::getOriginLabels()) 
				? Document::getOriginLabels()[$origin]
				: $this->nullDisplay;
	}
}
