<?php

namespace common\models\listitem;

use yii\base\Model;

abstract class NestedListItem extends Model
{
    public static function createListFromJson($json)
    {
        $itemsAttributes = json_decode($json);

        if (empty($itemsAttributes)) {
            return [];
        }

        $itemClass = static::class;
        return array_map(
            function ($itemAttributes) use ($itemClass) {
                return new $itemClass($itemAttributes);
            },
            $itemsAttributes
        );
    }

    /**
     * @param static[] $list
     * @return string
     */
    public static function listToJson($list)
    {
        return json_encode(
            array_map(
                function (NestedListItem $item) {
                    return $item->attributes;
                },
                $list
            )
        );
    }

}
