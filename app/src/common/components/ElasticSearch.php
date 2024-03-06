<?php
namespace common\components;

use common\base\interfaces\ElasticSearchable;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\elasticsearch\Command;
use yii\elasticsearch\Connection;
use yii\elasticsearch\Query;

class ElasticSearch extends Component
{
    private $_elastic;
    public $index;
    public $nodes;

    public function init()
    {
        parent::init();
        $this->_elastic = new Connection(
            ['nodes' => $this->nodes]
        );
    }

    public function addWarmer($serviceId, $name, $body, $options=[])
    {
        try {
            if (is_array($body)) {
                $body = json_encode($body);
                return $this->_elastic->put([$this->index, $serviceId, '_warmer', $name], $options, $body);
            }
        } catch(Exception $ex) {
            $this->log('ElasticSearch is broken:', true);
            $this->log($ex->getMessage());
        }
    }

    public function deleteWarmer($name)
    {
        try {
            return $this->_elastic->delete([$this->index, '_warmer', $name]);
        } catch(Exception $ex) {
            $this->log('ElasticSearch is broken:', true);
            $this->log($ex->getMessage());
        }
    }

    public function deleteType($name)
    {
        try {
            return $this->_elastic->delete([$this->index, $name]);
        } catch(Exception $ex) {
            $this->log('ElasticSearch is broken:', true);
            $this->log($ex->getMessage());
        }
    }

    public function putDocument(ElasticSearchable $doc)
    {
        try {
            $cmd = new Command([
                'db' => $this->_elastic
            ]);

            $fields = $doc->getSearchFields();

            if (is_array($fields) && !empty($fields)) {
                $cmd->insert($this->index, $doc->getSearchType(), json_encode($fields), $doc->getSearchId());

                return true;
            }
        } catch(Exception $ex) {
            $this->log('ElasticSearch is broken:', true);
            $this->log($ex->getMessage());
        }

		return false;
	}

	public function search($type, $fields, $text, $idList = null, $useHighlight = false)
	{
            $queryPart = [
                'wildcard' => [
                    '_all' => [
                        'value' => '*' . mb_strtolower($text, 'UTF8') . '*'
                    ],
                ],
            ];

            if (!empty($idList) && is_array($idList)) {
                $queryPart = [
                    'filtered' => [
                        'query' => $queryPart,
                        'filter' => [
                            'and' => [
                                [
                                    'ids' => [
                                        //'type' => $type
                                        'values' => $idList
                                    ]
                                ]
                            ],
                        ]
                    ]
                ];
            }

            $query = new Query();

            if (empty($fields)) {
                    $fields = [];
            }

            $query->fields($fields)
                ->from($this->index, $type)
                ->query($queryPart);

            if ($useHighlight) {
                $highlight = [
                    'pre_tags' => ['<span style="background:yellow">'],
                    'post_tags' => ['</span>'],
                    'type' => 'plain',
                     // use new \stdClass() instead of empty {} element!
                    'fields' => [
                        '*' => ['fragment_size' => 30, 'number_of_fragments' => 1]
                    ],
                ];
                $query->highlight($highlight);
            }
        try {
            $command = $query->createCommand($this->_elastic);

            $rows = $command->search(['size' => 50]);

            return $rows;
        } catch(yii\base\Exception $ex) {
            $this->log('ElasticSearch is broken:', true);
            $this->log($ex->getMessage());
        }

        return false;
    }

    public function log($message, $error = false)
    {
        if (!$error) {
            Yii::info($message, 'elastic');
        } else {
            Yii::error($message, 'elastic');
        }
    }
}
