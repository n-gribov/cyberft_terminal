<?php

namespace common\modules\wiki\models;

use common\modules\wiki\WikiModule;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Wiki page
 *
 * @package modules
 * @subpackage wiki
 *
 * @property integer    $id         Page id
 * @property integer    $pid        Parent page id
 * @property string     $title
 * @property string     $slug
 * @property string     $preview    Page preview for modals
 * @property string     $body
 * @property string     $version    App version tag which current page corresponds
 * @property datetime   $created
 * @property datetime   $updated
 */
class Page extends ActiveRecord
{
    protected $_parents;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => 'updated',
                'value' => new Expression('NOW()'),
            ],
            'slug' => [
                'class' => 'common\behaviors\SlugBehavior',
                'in_attribute' => 'title',
                'out_attribute' => 'slug',
            ]
        ];
    }

    // сочетание slug и версии должно быть уникальным
    public function validateSlugVersion($attribute, $params)
    {
        if (self::find()->where(['slug' => $this->$attribute, 'version' => $this->version])->exists()) {
            $this->addError($attribute, 'В каждой версии ПО терминала может быть только один документ');
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slug'], 'validateSlugVersion', 'on' => 'create'],
            [['title', 'body', 'preview', 'slug', 'version'], 'string'],
            [['pid',], 'integer'],
            [['pid', 'title', 'body', 'slug'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
    }

    public static function buildTree($parentId = null)
    {
        $data = [];
        $tree = [];

        foreach (self::find()->asArray()->all() as $doc) {
            $data[$doc['id']] = $doc;
        }

        foreach ($data as $id => &$node) {
            if (!$node['pid']) {
                $tree[$node['id']] = &$node;
            } else {
                $data[$node['pid']]['subtree'][$id] = &$node;
            }
        }

        return $tree;
    }

    public function getParent()
    {
        return $this->hasOne(Page::className(), ['id' => 'pid']);
    }

    /**
     * @return bool есть ли у документа дочерние документы
     */
    public function hasChildren()
    {
        return self::find()->where(['pid' => $this->id])->count() > 0;
    }

    public function hasAttachments()
    {
        return Attachment::find()->where(['page_id' => $this->id])->count() > 0;
    }

    public function getChildren()
    {
        return $this->hasMany(Page::className(), ['pid' => 'id']);
    }

    public function getParents()
    {
        if (is_null($this->_parents)) {
            $this->_parents = [];
            if (!empty($this->parent)) {

                if ($this->parent->id == $this->id) {
                    return $this->_parents;
                }

                $this->_parents[] = $this->parent;
                $this->_parents = \yii\helpers\ArrayHelper::merge($this->_parents, $this->parent->parents);
            }
        }

        return $this->_parents;
    }

    public function getAttachments()
    {
        return $this->hasMany(Attachment::className(), ['page_id' => 'id']);
    }

    public function getUrl() {
        $strSlug = '/help/wiki/';

        $slug = [];

        if (!empty($parents = $this->parents)) {
            krsort($parents);

            foreach ($parents as $parent) {
                $slug[] = $parent->slug;
            }
        }

        $slug[] = $this->slug;

        $strSlug .= implode('/',$slug);

        return $strSlug;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => WikiModule::t('models', 'Parent page ID'),
            'parent' => WikiModule::t('models', 'Parent page'),
            'title' => WikiModule::t('models', 'Title'),
            'slug' => WikiModule::t('models', 'Slug'),
            'body' => WikiModule::t('models', 'Body'),
            'preview' => WikiModule::t('models', 'Preview'),
            'version' => WikiModule::t('models', 'App version'),
            'created' => WikiModule::t('models', 'Created at'),
            'updated' => WikiModule::t('models', 'Updated at'),
        ];
    }

    protected function deleteAttachments()
    {
        foreach ($this->attachments as $attach) {
            if (!$attach->delete()) {
                return false;
            }
        }

        return true;
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if (!$this->deleteAttachments()) {
                return false;
            }

            return true;
        } else {
            
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Dump::flushCachedData();
    }

    public function afterDelete()
    {
        parent::afterDelete();
        Dump::flushCachedData();
    }
}