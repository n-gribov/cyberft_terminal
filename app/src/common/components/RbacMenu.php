<?php
namespace common\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class RbacMenu extends Component implements \ArrayAccess
{
    /**
     * Весь набор меню
     * @var array
     */
    protected $_items;

    /**
     * Набор меню с признаками сортировки
     * @var array
     */
    protected $_sortedItems;
    /**
     * Набор разрешенных элементов
     * @var array
     */
    protected $_permitted;

    /**
     * @param $items
     */
    public function setItems($items)
    {
        $this->_items = $items;
    }

    /**
     * Получение всех разрешенных значений
     * @return array
     */
    public function getItems()
    {
        if (isset($this->_permitted)) {
            return $this->_permitted;
        }

        $this->sortItems();

        $auth = \Yii::$app->authManager;
        return $this->_permitted = $this->buildPermittedItems($this->_items, $auth);
    }

    /**
     * @param array $items
     * @param \yii\rbac\PhpManager $auth
     * @return array
     */
    protected function buildPermittedItems($items, $auth)
    {
        foreach ($items as $k => &$v) {
            if (!empty($v['permission'])) {
                $permissionParams = $v['permissionParams'] ?? [];
                if (!Yii::$app->user->can($v['permission'], $permissionParams)) {
                    unset($v, $items[$k]);
                    continue;
                }
            }

            if (isset($v['serviceID']) && !Yii::$app->user->can('accessService', ['serviceId' => $v['serviceID']])) {
                unset($v, $items[$k]);
                continue;
            }

            if (isset($v['items'])) {
                $v['items'] = $this->buildPermittedItems($v['items'], $auth);
                if (empty($v['items'])) {
                    unset($v, $items[$k]);
                }
            }
        }
        return $items;
    }

    /**
     * @inherited
     */
    public function offsetSet($offset, $value)
    {
        $this->_permitted = null;
        $this->_items[$offset] = $value;
    }

    /**
     * @inherited
     */
    public function offsetExists($var)
    {
        return isset($this->items[$var]);
    }

    /**
     * @inherited
     */
    public function offsetUnset($var)
    {
        unset($this->_items[$var]);
    }

    /**
     * @inherited
     */
    public function offsetGet($var)
    {
        return $this->items[$var];
    }

    public function addItem($serviceId, $entry)
    {
        $this->_items[$serviceId] = $entry;
    }

    protected function sortItems()
    {
        foreach ($this->_items as $key=>$value) {
            if (array_key_exists('after', $value)) {
                $this->insertItemAfter($key, $value, $value['after']);
                $this->sortItems();
                break;
            } else if (array_key_exists('before', $value)) {
                $this->insertItemBefore($key, $value, $value['before']);
                $this->sortItems();
                break;
            }
        }
    }

    protected function insertItemAfter($serviceId, $data, $id)
    {
        $insertPosition = 0;
        $servicePosition = array_search($serviceId, array_keys($this->_items), true);

        unset($this->_items[$serviceId]);
        unset($data['after']);

        foreach ($this->_items as $value) {
            $insertPosition++;
            if (isset($value['id'])) {
                if ($value['id'] === $id) {
                    break;
                }
            }
        }

        $this->sortItem($serviceId, $data, $id, $insertPosition, $servicePosition);
    }

    protected function insertItemBefore($serviceId, $data, $id)
    {
        $insertPosition = 0;
        $servicePosition = (int) array_search($serviceId, array_keys($this->_items), true);

        unset($this->_items[$serviceId]);
        unset($data['before']);

        foreach ($this->_items as $value) {
            if (isset($value['id'])) {
                if ($value['id'] === $id) {
                    break;
                }
            }

            $insertPosition++;
        }

        $this->sortItem($serviceId, $data, $id, $insertPosition, $insertPosition);
    }

    protected function sortItem($serviceId, $data, $id, $insertPosition, $servicePosition)
    {
        $items[$serviceId] = $data;
        $items[$serviceId]['pos'] = $insertPosition;

        $items = ArrayHelper::merge($items, $this->procSortedItems($serviceId));

        $items = $this->getArraySort($items, 'pos');

        $this->_sortedItems[strtolower($id)][] = $serviceId;

        $this->_items = ArrayHelper::merge(
            array_slice($this->_items, 0, $insertPosition, true),
            $items,
            array_slice($this->_items, $insertPosition, count($this->_items) - 1, true)
        );
    }

    protected function procSortedItems($id)
    {
        $items = [];

        if (isset($this->_sortedItems[$id])) {
            $itemsKeys = array_keys($this->_items);

            foreach ($this->_sortedItems[$id] as $key => $value) {
                $items[$value] = $this->_items[$value];
                $items[$value]['pos'] = (int) array_search($value, $itemsKeys, true);
                unset($this->_items[$value]);

                if (isset($this->_sortedItems[$value])) {
                    $items += $this->procSortedItems($value);
                }
            }
        }

        return $items;
    }

    protected function getArraySort($array, $on, $order=SORT_ASC)
    {
        $new_array = [];
        $sortable_array = [];

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
}
