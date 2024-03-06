<?php

namespace common\helpers\sbbol;

use common\helpers\sbbol\SBBOLXmlSerializer\XmlDeserializationVisitor;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;
use JMS\Serializer\Context;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\Naming\CamelCaseNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\XmlSerializationVisitor;
use Yii;

class SBBOLXmlSerializer
{
    const METADATA_DIRS = [
        'common\models\sbbolxml\request'  => '@common/models/sbbolxml/request/_metadata',
        'common\models\sbbolxml\response' => '@common/models/sbbolxml/response/_metadata',
        'common\models\sbbolxml'          => '@common/models/sbbolxml/_metadata',
    ];

    /** @var Serializer */
    private $jmsSerializer;

    /** @var self */
    private static $instance;

    private function __construct()
    {
        $this->jmsSerializer = $this->createJmsSerializer();
    }

    public static function serialize($data)
    {
        return static::instance()->jmsSerializer->serialize($data, 'xml');
    }

    public static function deserialize($data, $type)
    {
        return static::instance()->jmsSerializer->deserialize($data, $type, 'xml');
    }

    private static function instance()
    {
        if (static::$instance === null) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    private function createJmsSerializer()
    {
        $jmsSerializerBuilder = SerializerBuilder::create();

        foreach (static::METADATA_DIRS as $namespace => $dirAlias) {
            $jmsSerializerBuilder->addMetadataDir(
                Yii::getAlias($dirAlias),
                $namespace
            );
        }

        $jmsSerializerBuilder->configureHandlers(function (HandlerRegistryInterface $handler) use ($jmsSerializerBuilder) {
            $jmsSerializerBuilder->addDefaultHandlers();
            $handler->registerSubscribingHandler(new BaseTypesHandler()); // XMLSchema List handling
            $handler->registerSubscribingHandler(new XmlSchemaDateHandler()); // XMLSchema date handling
        });

        // Add custom visitor to serialize boolean as 1 and 0 (required by BoolType XSD definition)
        $jmsSerializerBuilder->setSerializationVisitor('xml', static::createXmlSerializationVisitor());
        $jmsSerializerBuilder->setDeserializationVisitor(
            'xml',
            new XmlDeserializationVisitor(new SerializedNameAnnotationStrategy(new CamelCaseNamingStrategy()))
        );

        return $jmsSerializerBuilder->build();
    }

    public static function createCdataNode($namespacePrefix, $wrapperTag, $content)
    {
        $cData = static::encodeForCdata($content);

        return new \SoapVar(
            "<$namespacePrefix:$wrapperTag><![CDATA[$cData]]></$namespacePrefix:$wrapperTag>",
            XSD_ANYXML
        );
    }

    private static function encodeForCdata($string)
    {
        return preg_replace('/]]>/', ']]]]><![CDATA[>', $string);
    }

    private function createXmlSerializationVisitor()
    {
        return new class(new SerializedNameAnnotationStrategy(new CamelCaseNamingStrategy())) extends XmlSerializationVisitor
        {
            public function visitBoolean($data, array $type, Context $context)
            {
                if (null === $this->document) {
                    $this->document = $this->createDocument(null, null, true);
                    $this->getCurrentNode()->appendChild($this->document->createTextNode($data ? '1' : '0'));

                    return;
                }

                return $this->document->createTextNode($data ? '1' : '0');
            }
        };
    }

}
