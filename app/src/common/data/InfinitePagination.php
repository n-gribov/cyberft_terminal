<?php

namespace common\data;

use yii\data\Pagination;
use yii\web\Link;

class InfinitePagination extends Pagination
{
    /** @var bool */
    public $hasMorePages = false;

    public function getLinks($absolute = false)
    {
        $currentPage = $this->getPage();
        $links = [
            Link::REL_SELF => $this->createUrl($currentPage, null, $absolute),
        ];
        if ($currentPage > 0) {
            $links[self::LINK_FIRST] = $this->createUrl(0, null, $absolute);
            $links[self::LINK_PREV] = $this->createUrl($currentPage - 1, null, $absolute);
        }
        $links[self::LINK_NEXT] = $this->createUrl($currentPage + 1, null, $absolute);

        return $links;
    }

    public function setPage($value, $validatePage = false)
    {
        $this->validatePage = false;
        parent::setPage($value, false);
    }

    public function getPageCount()
    {
        throw new \Exception('Not applicable');
    }
}
