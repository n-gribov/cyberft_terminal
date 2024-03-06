<?php
namespace common\components\xmlsec\xmlseclibs;

use common\base\DOMCyberXml;
use common\components\xmlsec\xmlseclibs\XMLSeclibsHelper;
use common\components\xmlsec\xmlseclibs\XMLSecurityKey;
use common\helpers\Address;
use common\helpers\Uuid;
use common\modules\certManager\components\ssl\X509FileModel;
use common\modules\certManager\models\Cert;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Yii;
use yii\base\InvalidValueException;

/**
 * xmlseclibs.php
 *
 * Copyright (c) 2007-2010, Robert Richards <rrichards@cdatazone.org>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Robert Richards nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author     Robert Richards <rrichards@cdatazone.org>
 * @copyright  2007-2011 Robert Richards <rrichards@cdatazone.org>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    1.3.0
 */

class XMLSecurityDSig
{
	const XMLDSIGNS = 'http://www.w3.org/2000/09/xmldsig#';
	const SHA1 = 'http://www.w3.org/2000/09/xmldsig#sha1';
	const SHA256 = 'http://www.w3.org/2001/04/xmlenc#sha256';
	const SHA512 = 'http://www.w3.org/2001/04/xmlenc#sha512';
	const RIPEMD160 = 'http://www.w3.org/2001/04/xmlenc#ripemd160';
	const C14N = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
	const C14N_COMMENTS = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315#WithComments';
	const EXC_C14N = 'http://www.w3.org/2001/10/xml-exc-c14n#';
	const EXC_C14N_COMMENTS = 'http://www.w3.org/2001/10/xml-exc-c14n#WithComments';
	const XMLDSIG_FILTER2 = 'http://www.w3.org/2002/06/xmldsig-filter2';

	const XPATH_TRANSFORM_ALGO = 'http://www.w3.org/TR/1999/REC-xpath-19991116';

	const SIGNATURE_TEMPLATE = <<< XML
	<Signature xmlns="http://www.w3.org/2000/09/xmldsig#">
		<SignedInfo>
			<SignatureMethod />
		</SignedInfo>
	</Signature>
XML;

	const SECDSIG_PREFIX = 'secdsig';

	public $sigNode = null;
	public $idKeys = [];
	public $idNS = [];
	private $signedInfo = null;
	private $xPathCtx = null;
	protected $canonicalMethod = null;

    public $terminalId;

	/* This variable contains an associative array of validated nodes. */
	private $validatedNodes = null;

	public function __construct()
	{
		$sigdoc = new DOMDocument();
		$sigdoc->loadXML(static::SIGNATURE_TEMPLATE, LIBXML_PARSEHUGE);
		$this->sigNode = $sigdoc->documentElement;
	}

	private function resetXPathObj()
	{
		$this->xPathCtx = null;
	}

	public function getXPathObj()
	{
		if (empty($this->xPathCtx) && !empty($this->sigNode)) {
			$xpath = new DOMXPath($this->sigNode->ownerDocument);
			$xpath->registerNamespace(static::SECDSIG_PREFIX, static::XMLDSIGNS);
			$this->xPathCtx = $xpath;
		}

		return $this->xPathCtx;
	}

	public function locateSignature($objDoc)
	{
		if ($objDoc instanceof DOMDocument) {
			$doc = $objDoc;
		} else {
			$doc = $objDoc->ownerDocument;
		}

		if ($doc) {
			$xpath = new DOMXPath($doc);
			$xpath->registerNamespace(static::SECDSIG_PREFIX, static::XMLDSIGNS);
			$query = ".//" . static::SECDSIG_PREFIX . ":Signature";
			$nodeset = $xpath->query($query, $objDoc);
			$this->sigNode = $nodeset->item(0);

			return $this->sigNode;
		}

		return null;
	}

    public function createNewSignNode($name, $value = null)
	{
		$doc = $this->sigNode->ownerDocument;

		return $doc->createElementNS(
                    static::XMLDSIGNS,
                    (!empty($this->prefix) ? $this->prefix . ':' . $name : $name),
                    $value
                );
	}

	public function setCanonicalMethod($method)
	{
		switch ($method) {
			case static::C14N:
			case static::C14N_COMMENTS:
			case static::EXC_C14N:
			case static::EXC_C14N_COMMENTS:
				$this->canonicalMethod = $method;
				break;
			default:
				throw new Exception('Invalid Canonical Method');
		}

		if (($xpath = $this->getXPathObj())) {
			$query = './' . static::SECDSIG_PREFIX . ':SignedInfo';
			$nodeset = $xpath->query($query, $this->sigNode);
			if (($sinfo = $nodeset->item(0))) {
				$query = './' . static::SECDSIG_PREFIX . ':CanonicalizationMethod';
				$nodeset = $xpath->query($query, $sinfo);
				if (!($canonNode = $nodeset->item(0))) {
					$canonNode = $this->createNewSignNode('CanonicalizationMethod');
					$sinfo->insertBefore($canonNode, $sinfo->firstChild);
				}
				$canonNode->setAttribute('Algorithm', $this->canonicalMethod);
			}
		}
	}

	public static function canonicalizeData(DOMNode $node, $canonicalmethod, $arXPath = null, $prefixList = null, $xpathForSubtract = null)
	{
//        \Yii::info($node->ownerDocument->saveXML($node));
		$exclusive = false;
		$withComments = false;
		switch ($canonicalmethod) {
			case static::C14N:
				$exclusive = false;
				$withComments = false;
				break;
			case static::C14N_COMMENTS:
				$withComments = true;
				break;
			case static::EXC_C14N:
				$exclusive = true;
				break;
			case static::EXC_C14N_COMMENTS:
				$exclusive = true;
				$withComments = true;
				break;
		}

        if ($xpathForSubtract) {
            $nodeCopy = new \DOMDocument();
            $xml = ($node instanceof \DOMDocument) ? $node->saveXML() : $node->ownerDocument->saveXML($node);
            $nodeCopy->loadXML($xml);
            $node = $nodeCopy;
            $xpath = new \DOMXPath($node);
            $nodesToSubtract = $xpath->query($xpathForSubtract);
            foreach ($nodesToSubtract as $nodeToSubtract) {
                $nodeToSubtract->parentNode->removeChild($nodeToSubtract);
            }
        }

        return $node->C14N($exclusive, $withComments, $arXPath, $prefixList);
	}

	public function canonicalizeSignedInfo()
	{
		$doc = $this->sigNode->ownerDocument;
		$canonicalmethod = null;
		if ($doc) {
			$xpath = $this->getXPathObj();
			$query = './' . static::SECDSIG_PREFIX . ':SignedInfo';
			$nodeset = $xpath->query($query, $this->sigNode);
			if (($signInfoNode = $nodeset->item(0))) {
				$query = './' . static::SECDSIG_PREFIX . ':CanonicalizationMethod';
				$nodeset = $xpath->query($query, $signInfoNode);
				if (($canonNode = $nodeset->item(0))) {
					$canonicalmethod = $canonNode->getAttribute('Algorithm');
				}
				$this->signedInfo = static::canonicalizeData($signInfoNode, $canonicalmethod);

				return $this->signedInfo;
			}
		}

		return null;
	}

	public static function calculateDigest($digestAlgorithm, $data)
	{
		switch ($digestAlgorithm) {
			case static::SHA1:
				$alg = 'sha1';
				break;
			case static::SHA256:
				$alg = 'sha256';
				break;
			case static::SHA512:
				$alg = 'sha512';
				break;
			case static::RIPEMD160:
				$alg = 'ripemd160';
				break;
			default:
				throw new Exception("Cannot validate digest: Unsupported Algorithm <$digestAlgorithm>");
		}
		if (function_exists('hash')) {
			return base64_encode(hash($alg, $data, true));
		} else if (function_exists('mhash')) {
			$alg = 'MHASH_' . strtoupper($alg);
			return base64_encode(mhash(constant($alg), $data));
		} else if ($alg === 'sha1') {
			return base64_encode(sha1($data, true));
		} else {
			throw new Exception('xmlseclibs is unable to calculate a digest. Maybe you need the mhash library?');
		}
	}

	public function validateDigest($refNode, $data)
	{
		$xpath = new DOMXPath($refNode->ownerDocument);
		$xpath->registerNamespace(static::SECDSIG_PREFIX, static::XMLDSIGNS);
		$query = 'string(./' . static::SECDSIG_PREFIX . ':DigestMethod/@Algorithm)';
		$digestAlgorithm = $xpath->evaluate($query, $refNode);
		$digValue = $this->calculateDigest($digestAlgorithm, $data);
		$query = 'string(./' . static::SECDSIG_PREFIX . ':DigestValue)';
		$digestValue = $xpath->evaluate($query, $refNode);

		return ($digValue == $digestValue);
	}

	public function processTransforms($refNode, $objData, $includeCommentNodes = true)
	{
		$data = $objData;
		$xpath = new DOMXPath($refNode->ownerDocument);
		$xpath->registerNamespace(static::SECDSIG_PREFIX, static::XMLDSIGNS);
		$query = './' . static::SECDSIG_PREFIX . ':Transforms/' . static::SECDSIG_PREFIX . ':Transform';
		$nodelist = $xpath->query($query, $refNode);
		$canonicalMethod = static::C14N;
		$arXPath = null;
		$prefixList = null;
        $xpathForSubtract = null;

        foreach ($nodelist as $transform) {
			$algorithm = $transform->getAttribute("Algorithm");
			switch ($algorithm) {
				case static::EXC_C14N:
				case static::EXC_C14N_COMMENTS:

					if (!$includeCommentNodes) {
						/* We remove comment nodes by forcing it to use a canonicalization
						 * without comments.
						 */
						$canonicalMethod = static::EXC_C14N;
					} else {
						$canonicalMethod = $algorithm;
					}

					$node = $transform->firstChild;
					while ($node) {
						if ($node->localName == 'InclusiveNamespaces') {
							if (($pfx = $node->getAttribute('PrefixList'))) {
								$arpfx = [];
								$pfxlist = explode(" ", $pfx);
								foreach ($pfxlist as $pfx) {
									$val = trim($pfx);
									if (!empty($val)) {
										$arpfx[] = $val;
									}
								}
								if (count($arpfx) > 0) {
									$prefixList = $arpfx;
								}
							}
							break;
						}
						$node = $node->nextSibling;
					}
					break;
				case static::C14N:
				case static::C14N_COMMENTS:
					if (!$includeCommentNodes) {
						/* We remove comment nodes by forcing it to use a canonicalization
						 * without comments.
						 */
						$canonicalMethod = static::C14N;
					} else {
						$canonicalMethod = $algorithm;
					}

					break;
				case static::XPATH_TRANSFORM_ALGO:
					$node = $transform->firstChild;
					while ($node) {
						if ($node->localName == 'XPath') {
							$arXPath = [];
							$arXPath['query'] = '(.//. | .//@* | .//namespace::*)[' . $node->nodeValue . ']';
							$arXPath['namespaces'] = [];
							$nslist = $xpath->query('./namespace::*', $node);
							foreach ($nslist as $nsnode) {
								if ($nsnode->localName != 'xml') {
									$arXPath['namespaces'][$nsnode->localName] = $nsnode->nodeValue;
								}
							}
							break;
						}
						$node = $node->nextSibling;
					}
                    break;
                case static::XMLDSIG_FILTER2:
                    /** @var \DOMNode $childNode */
                    foreach ($transform->childNodes as $childNode) {
                        if ($childNode->localName === 'XPath') {
                            $filter = $childNode->attributes->getNamedItem('Filter');
                            if ($filter) {
                                if ($filter->nodeValue === 'subtract') {
                                    $xpathForSubtract = $childNode->nodeValue;
                                } else {
                                    throw new \Exception("Unsupported filter type: {$filter->nodeValue}");
                                }
                            }
                        }
                    }
                    break;
			}
		}
		if ($data instanceof DOMNode) {
			$data = static::canonicalizeData($objData, $canonicalMethod, $arXPath, $prefixList, $xpathForSubtract);
		}

		return $data;
	}

	public function processRefNode($refNode)
	{
		$dataObject = null;

		/*
		 * Depending on the URI, we may not want to include comments in the result
		 * See: http://www.w3.org/TR/xmldsig-core/#sec-ReferenceProcessingModel
		 */
		$includeCommentNodes = true;

		if (($uri = $refNode->getAttribute('URI'))) {

            if (strstr($uri, '#id_') !== false) {
                return true;
            }

			$arUrl = parse_url($uri);
			if (empty($arUrl['path'])) {
				if (($identifier = $arUrl['fragment'])) {

					/* This reference identifies a node with the given id by using
					 * a URI on the form "#identifier". This should not include comments.
					 */
					$includeCommentNodes = false;

					$xPath = new DOMXPath($refNode->ownerDocument);
					if ($this->idNS && is_array($this->idNS)) {
						foreach ($this->idNS as $nspf => $ns) {
							$xPath->registerNamespace($nspf, $ns);
						}
					}
					$iDlist = '@Id="' . $identifier . '"';
					if (is_array($this->idKeys)) {
						foreach ($this->idKeys as $idKey) {
							$iDlist .= " or @$idKey='$identifier'";
						}
					}
					$query = '//*[' . $iDlist . ']';
					$dataObject = $xPath->query($query)->item(0);
				} else {
					$dataObject = $refNode->ownerDocument;
				}
			} else {
				$dataObject = file_get_contents($arUrl);
			}
		} else {
			/* This reference identifies the root node with an empty URI. This should
			 * not include comments.
			 */
			$includeCommentNodes = false;

			$dataObject = $refNode->ownerDocument;
		}

		$data = $this->processTransforms($refNode, $dataObject, $includeCommentNodes);
		if (!$this->validateDigest($refNode, $data)) {
			return false;
		}

		if ($dataObject instanceof DOMNode) {
			/* Add this node to the list of validated nodes. */
			if (!empty($identifier)) {
				$this->validatedNodes[$identifier] = $dataObject;
			} else {
				$this->validatedNodes[] = $dataObject;
			}
		}

		return true;
	}

	public function getRefNodeID($refNode)
	{
		if (($uri = $refNode->getAttribute('URI'))) {
			$arUrl = parse_url($uri);
			if (empty($arUrl['path'])) {
				if (($identifier = $arUrl['fragment'])) {
					return $identifier;
				}
			}
		}
		return null;
	}

	public function getRefIDs()
	{
		$refids = [];
		$doc = $this->sigNode->ownerDocument;

		$xpath = $this->getXPathObj();
		$query = './' . static::SECDSIG_PREFIX . ':SignedInfo/' . static::SECDSIG_PREFIX . ':Reference';
		$nodeset = $xpath->query($query, $this->sigNode);
		if ($nodeset->length == 0) {
			throw new Exception('Reference nodes not found');
		}
		foreach ($nodeset as $refNode) {
			$refids[] = $this->getRefNodeID($refNode);
		}

		return $refids;
	}

	public function validateReference()
	{
		$doc = $this->sigNode->ownerDocument;
		if (!$doc->isSameNode($this->sigNode)) {
			$this->sigNode->parentNode->removeChild($this->sigNode);
		}
		$xpath = $this->getXPathObj();
		$query = './' . static::SECDSIG_PREFIX . ':SignedInfo/' . static::SECDSIG_PREFIX . ':Reference';
		$nodeset = $xpath->query($query, $this->sigNode);
		if ($nodeset->length == 0) {
			throw new Exception('Reference nodes not found');
		}

		/* Initialize/reset the list of validated nodes. */
		$this->validatedNodes = [];

		foreach ($nodeset as $refNode) {
			if (!$this->processRefNode($refNode)) {
				/* Clear the list of validated nodes. */
				$this->validatedNodes = null;
				throw new \Exception('Reference validation failed');
			}
		}

		return true;
	}

	private function addRefInternal($sinfoNode, $node, $algorithm, $arTransforms = null, $options = null)
	{
		$prefix = null;
		$prefix_ns = null;
		$id_name = 'Id';
		$overwrite_id = true;
		$force_uri = false;

		if (is_array($options)) {
			$prefix = empty($options['prefix']) ? null : $options['prefix'];
			$prefix_ns = empty($options['prefix_ns']) ? null : $options['prefix_ns'];
			$id_name = empty($options['id_name']) ? 'Id' : $options['id_name'];
			$overwrite_id = !isset($options['overwrite']) ? true : (bool) $options['overwrite'];
			$force_uri = !isset($options['force_uri']) ? false : (bool) $options['force_uri'];
		}

		$attname = $id_name;
		if (!empty($prefix)) {
			$attname = $prefix . ':' . $attname;
		}

		$refNode = $this->createNewSignNode('Reference');
		$sinfoNode->appendChild($refNode);

		if (!$node instanceof DOMDocument) {
			$uri = null;
			if (!$overwrite_id) {
				$uri = $node->getAttributeNS($prefix_ns, $attname);
			}
			if (empty($uri)) {
				$uri = XMLSeclibsHelper::generateGUID();
				$node->setAttributeNS($prefix_ns, $attname, $uri);
			}
			$refNode->setAttribute('URI', '#' . $uri);
		} else if ($force_uri) {
			$refNode->setAttribute('URI', '');
		}

		$transNodes = $this->createNewSignNode('Transforms');
		$refNode->appendChild($transNodes);

		if (is_array($arTransforms)) {
            foreach ($arTransforms as $transform) {
                if (is_array($transform) &&
                        (!empty($transform[static::XPATH_TRANSFORM_ALGO])) &&
                        (!empty($transform[static::XPATH_TRANSFORM_ALGO]['query']))
                ) {
                    $transNode = $this->createNewSignNode('Transform');
                    $transNodes->appendChild($transNode);
                    $transNode->setAttribute('Algorithm', static::XPATH_TRANSFORM_ALGO);
                    $XPathNode = $this->createNewSignNode('XPath',
                            $transform[static::XPATH_TRANSFORM_ALGO]['query']);
                    $transNode->appendChild($XPathNode);
                    if (!empty($transform[static::XPATH_TRANSFORM_ALGO]['namespaces'])) {
                        foreach ($transform[static::XPATH_TRANSFORM_ALGO]['namespaces'] as $prefix => $namespace) {
                            $XPathNode->setAttributeNS("http://www.w3.org/2000/xmlns/", "xmlns:$prefix", $namespace);
                        }
                    }
                }
            }
        } else if (!empty($this->canonicalMethod)) {
            $transNode = $this->createNewSignNode('Transform');
            $transNodes->appendChild($transNode);
            $transNode->setAttribute('Algorithm', $this->canonicalMethod);
        }

        $canonicalData = $this->processTransforms($refNode, $node);
		$digValue = $this->calculateDigest($algorithm, $canonicalData);
		$digestMethod = $this->createNewSignNode('DigestMethod');
		$refNode->appendChild($digestMethod);
		$digestMethod->setAttribute('Algorithm', $algorithm);

		$digestValue = $this->createNewSignNode('DigestValue', $digValue);
		$refNode->appendChild($digestValue);

// XADES ISOLATED
//        $reference = $this->createNewSignNode('Reference');
//        $sinfoNode->appendChild($reference);
//
//        $reference->setAttribute('Type', 'http://www.w3.org/2000/09/xmldsig#Object');
//        $reference->setAttribute('URI', '#id_' . Uuid::generate());
//
//        $transforms = $this->createNewSignNode('Transforms');
//        $reference->appendChild($transforms);
//
//        $transform = $this->createNewSignNode('Transform');
//        $transform->setAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');
//        $transforms->appendChild($transform);
//
//        $digestMethod = $this->createNewSignNode('DigestMethod');
//        $reference->appendChild($digestMethod);
//
//        $digestMethod->setAttribute('Algorithm', $algorithm);
//        $digestValue = $this->createNewSignNode('DigestValue');
//
//        $reference->appendChild($digestValue);
	}

	public function addReference($node, $algorithm, $arTransforms = null, $options = null)
	{
		if (($xpath = $this->getXPathObj())) {
			$query = './' . static::SECDSIG_PREFIX . ':SignedInfo';
			$nodeset = $xpath->query($query, $this->sigNode);
			if (($sInfo = $nodeset->item(0))) {
				$this->addRefInternal($sInfo, $node, $algorithm, $arTransforms, $options);
			}
		}
	}

	public function addReferenceList($arNodes, $algorithm, $arTransforms = null, $options = null)
	{
		if (($xpath = $this->getXPathObj())) {
			$query = './' . static::SECDSIG_PREFIX . ':SignedInfo';
			$nodeset = $xpath->query($query, $this->sigNode);
			if (($sInfo = $nodeset->item(0))) {
				foreach ($arNodes as $node) {
					$this->addRefInternal($sInfo, $node, $algorithm, $arTransforms, $options);
				}
			}
		}
	}

	public function addObject($data, $mimetype = null, $encoding = null)
	{
		$objNode = $this->createNewSignNode('Object');
		$this->sigNode->appendChild($objNode);
		if (!empty($mimetype)) {
			$objNode->setAtribute('MimeType', $mimetype);
		}
		if (!empty($encoding)) {
			$objNode->setAttribute('Encoding', $encoding);
		}

		if ($data instanceof DOMElement) {
			$newData = $this->sigNode->ownerDocument->importNode($data, true);
		} else {
			$newData = $this->sigNode->ownerDocument->createTextNode($data);
		}
		$objNode->appendChild($newData);

		return $objNode;
	}

	public function locateKey($node = null)
	{
		if (empty($node)) {
			$node = $this->sigNode;
		}
		if (!$node instanceof DOMNode) {
			return null;
		}
		if (($doc = $node->ownerDocument)) {
			$xpath = new DOMXPath($doc);
			$xpath->registerNamespace(static::SECDSIG_PREFIX, static::XMLDSIGNS);
			$query = 'string(./' . static::SECDSIG_PREFIX . ':SignedInfo/' . static::SECDSIG_PREFIX . ':SignatureMethod/@Algorithm)';
			$algorithm = $xpath->evaluate($query, $node);
			if ($algorithm) {
				try {
					$objKey = new XMLSecurityKey($algorithm, ['type' => 'public']);
				} catch (Exception $e) {
					return null;
				}

				return $objKey;
			}
		}

		return null;
	}

	/**
	 * Функция позиционирует внутренний указатель сигнатур на указанный узел,
	 * что позволяет иметь и проверять более одной сигнатуры в одном XML-файле.
	 * Сигнатура должна принадлежать проверяемому документу.
	 */
	public function toSignatureNode($sigNode)
	{
		$this->sigNode = $sigNode;
	}

	public function verify($objKey)
	{
		$doc = $this->sigNode->ownerDocument;
		$xpath = new DOMXPath($doc);
		$xpath->registerNamespace(static::SECDSIG_PREFIX, static::XMLDSIGNS);
		$query = 'string(./' . static::SECDSIG_PREFIX . ':SignatureValue)';
		$sigValue = $xpath->evaluate($query, $this->sigNode);

		if (empty($sigValue)) {
			throw new Exception('Unable to locate SignatureValue');
		}

		return $objKey->verifySignature($this->signedInfo, base64_decode($sigValue));
	}

	public function signData($objKey, $data)
	{
		return $objKey->signData($data);
	}

	public function sign($objKey, $appendToNode = null)
	{
		// If we have a parent node append it now so C14N properly works
		if ($appendToNode != null) {
			$this->resetXPathObj();
			$this->appendSignature($appendToNode);
			$this->sigNode = $appendToNode->lastChild;
		}

		if (($xpath = $this->getXPathObj())) {
			$query = './' . static::SECDSIG_PREFIX . ':SignedInfo';
			$nodeset = $xpath->query($query, $this->sigNode);
			if (($sInfo = $nodeset->item(0))) {
				$query = './' . static::SECDSIG_PREFIX . ':SignatureMethod';
				$nodeset = $xpath->query($query, $sInfo);
				$sMethod = $nodeset->item(0);
				$sMethod->setAttribute('Algorithm', $objKey->type);
				$data = static::canonicalizeData($sInfo, $this->canonicalMethod);
				$sigValue = base64_encode($this->signData($objKey, $data));

				$sigValueNode = $this->createNewSignNode('SignatureValue', $sigValue);
				if (($infoSibling = $sInfo->nextSibling)) {
					$infoSibling->parentNode->insertBefore($sigValueNode, $infoSibling);
				} else {
					$this->sigNode->appendChild($sigValueNode);
				}
			}
		}
	}

	public function appendKey($objKey, $parent = null)
	{
		$objKey->serializeKey($parent);
	}

	/**
	 * This function inserts the signature element.
	 *
	 * The signature element will be appended to the element, unless $beforeNode is specified. If $beforeNode
	 * is specified, the signature element will be inserted as the last element before $beforeNode.
	 *
	 * @param $node  The node the signature element should be inserted into.
	 * @param $beforeNode  The node the signature element should be located before.
	 *
	 * @return DOMNode The signature element node
	 */
	public function insertSignature($node, $beforeNode = null)
	{
		$document = $node->ownerDocument;
		$signatureElement = $document->importNode($this->sigNode, true);

        $signatureXml = $signatureElement->ownerDocument->saveXML($signatureElement);

        return DOMCyberXml::insertBefore($node, $signatureXml, $beforeNode);
	}

	public function appendSignature($parentNode, $insertBefore = false)
	{
		$beforeNode = $insertBefore ? $parentNode->firstChild : null;

		return $this->insertSignature($parentNode, $beforeNode);
	}

	static function get509XCert($cert, $isPEMFormat = true)
	{
		$certs = static::staticGet509XCerts($cert, $isPEMFormat);
		if (!empty($certs)) {
			return $certs[0];
		}

		return '';
	}

	static function staticGet509XCerts($certs, $isPEMFormat = true)
	{
		if ($isPEMFormat) {
			$data = '';
			$certlist = [];
			$arCert = explode("\n", $certs);
			$inData = false;
			foreach ($arCert as $curData) {
				if (!$inData) {
					if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) == 0) {
						$inData = true;
					}
				} else {
					if (strncmp($curData, '-----END CERTIFICATE', 20) == 0) {
						$inData = false;
						$certlist[] = $data;
						$data = '';
						continue;
					}
					$data .= trim($curData);
				}
			}

			return $certlist;
		} else {
			return array($certs);
		}
	}

	static function staticAdd509Cert($parentRef, $cert, $isPEMFormat = true, $isURL = False, $xpath = null)
	{
		if ($isURL) {
			$cert = file_get_contents($cert);
		}
		if (!$parentRef instanceof DOMElement) {
			throw new Exception('Invalid parent Node parameter');
		}
		$baseDoc = $parentRef->ownerDocument;

		if (empty($xpath)) {
			$xpath = new DOMXPath($parentRef->ownerDocument);
			$xpath->registerNamespace(static::SECDSIG_PREFIX, static::XMLDSIGNS);
		}

		$query = './' . static::SECDSIG_PREFIX . ':KeyInfo';
		$nodeset = $xpath->query($query, $parentRef);
		$keyInfo = $nodeset->item(0);
		if (!$keyInfo) {
			$inserted = false;
			$keyInfo = $baseDoc->createElementNS(static::XMLDSIGNS, 'KeyInfo');

			$query = './' . static::SECDSIG_PREFIX . ':Object';
			$nodeset = $xpath->query($query, $parentRef);
			if (($sObject = $nodeset->item(0))) {
				$sObject->parentNode->insertBefore($keyInfo, $sObject);
				$inserted = true;
			}

			if (!$inserted) {
				$parentRef->appendChild($keyInfo);
			}
		}

		// Add all certs if there are more than one
		$certs = static::staticGet509XCerts($cert, $isPEMFormat);

		// Atach X509 data node
		$x509DataNode = $baseDoc->createElementNS(static::XMLDSIGNS, 'X509Data');
		$keyInfo->appendChild($x509DataNode);

		// Atach all certificate nodes
		foreach ($certs as $X509Cert) {
			$x509CertNode = $baseDoc->createElementNS(static::XMLDSIGNS, 'X509Certificate', $X509Cert);
			$x509DataNode->appendChild($x509CertNode);
		}
	}

	public function add509Cert($cert, $isPEMFormat = true, $isURL = false)
	{
		if (($xpath = $this->getXPathObj())) {
			self::staticAdd509Cert($this->sigNode, $cert, $isPEMFormat, $isURL, $xpath);
		}
	}

	/* This function retrieves an associative array of the validated nodes.
	 *
	 * The array will contain the id of the referenced node as the key and the node itself
	 * as the value.
	 *
	 * Returns:
	 *  An associative array of validated nodes or null if no nodes have been validated.
	 */

	public function getValidatedNodes()
	{
		return $this->validatedNodes;
	}

	/**
	 * Функция добавляет информацию о ключе, включающую фингерпринт сертификата
	 * и SubjectName
	 * @param string $fingerprint Отпечаток сертификата
	 * @throws Exception
	 */
	public function addKeyInfo($fingerprint)
	{
		if (($xpath = $this->getXPathObj())) {
			self::staticAddKeyInfo($this->sigNode, $fingerprint, $xpath);
		}
	}

    public function addSigningInfo($x509Info)
    {
        if (($xpath = $this->getXPathObj())) {
            $this->buildSigningInfo($this->sigNode, $x509Info, $xpath);
        }
    }

	/**
	 * Функция добавляет информацию о ключе, включающую фингерпринт сертификата
	 * и SubjectName
	 * @param DOMElement $parentRef
	 * @param string $fingerprint Отпечаток сертификата
	 * @param DOMXPath $xpath Объект для выполнения запросов для поиска узлов в Signature
	 * @throws Exception
	 */
	static function staticAddKeyInfo($parentRef, $fingerprint, $xpath = null)
	{
		if (!$parentRef instanceof DOMElement) {
			throw new Exception('Invalid parent Node parameter');
		}
		$baseDoc = $parentRef->ownerDocument;

		/**
		 * @todo Субъект сертификата сейчас пустой. Для именной подписи его будет
		 * необходимо заполнять.
		 */
		$subject = '';

		if (empty($xpath)) {
			$xpath = new DOMXPath($parentRef->ownerDocument);
			$xpath->registerNamespace(static::SECDSIG_PREFIX, static::XMLDSIGNS);
		}

		// Ищем тэг для информации о ключе, и создаем его, если он еще не существует
		$query = './secdsig:KeyInfo';
		$nodeset = $xpath->query($query, $parentRef);
		$keyInfo = $nodeset->item(0);
		if (!$keyInfo) {
			$inserted = false;
			$keyInfo = $baseDoc->createElementNS(static::XMLDSIGNS, 'KeyInfo');

			$query = './secdsig:Object';
			$nodeset = $xpath->query($query, $parentRef);
			if (($sObject = $nodeset->item(0))) {
				$sObject->parentNode->insertBefore($keyInfo, $sObject);
				$inserted = true;
			}

			if (!$inserted) {
				$parentRef->appendChild($keyInfo);
			}
		}

		// Ищем тэг для информации о фингерпринте сертификата, и создаем его,
		// если он еще не существует
		$query = './secdsig:KeyName';
		$nodeset = $xpath->query($query, $keyInfo);
		$keyName = $nodeset->item(0);
		if (!$keyName) {
			$inserted = false;
			$keyName = $baseDoc->createElementNS(static::XMLDSIGNS, 'KeyName', $fingerprint);

			$query = './secdsig:X509Data';
			$nodeset = $xpath->query($query, $keyInfo);
			if (($sObject = $nodeset->item(0))) {
				$sObject->parentNode->insertBefore($keyName, $sObject);
				$inserted = true;
			}

			if (!$inserted) {
				$keyInfo->appendChild($keyName);
			}
		}

		// Attach X509 data node
		$x509DataNode = $baseDoc->createElement('X509Data');
		$keyInfo->appendChild($x509DataNode);
		// Атрибут SubjectName
		$x509SubjectName = $baseDoc->createElement('X509SubjectName', $subject);
		$x509DataNode->appendChild($x509SubjectName);
	}

    /**
     * Функция добавляет информацию о подписании
     * @param DOMElement $parentRef
     * @param X509FileModel $x509Info
     * @param null $xpath
     * @throws \Exception
     */
    protected function buildSigningInfo($parentRef, $x509Info, $xpath = null)
    {
        if (!$parentRef instanceof DOMElement) {
            throw new \Exception('Invalid parent node parameter');
        }
        $baseDoc = $parentRef->ownerDocument;

        if (empty($xpath)) {
            $xpath = new DOMXPath($parentRef->ownerDocument);
            $xpath->registerNamespace(static::SECDSIG_PREFIX, static::XMLDSIGNS);
        }

        $signatureId = Uuid::generate();
        $parentRef->setAttribute('Id', 'id_' . $signatureId);

        $object = $baseDoc->createElementNS(static::XMLDSIGNS, 'Object');
        $parentRef->appendChild($object);

        $qualifyingProperties = $baseDoc->createElement('QualifyingProperties');
        $object->appendChild($qualifyingProperties);

        $signedProperties = $baseDoc->createElement('SignedProperties');
        $qualifyingProperties->appendChild($signedProperties);

        $signedSignatureProperties = $baseDoc->createElement('SignedSignatureProperties');
        $signedProperties->appendChild($signedSignatureProperties);

        // Время подписания
        $signingTime = $baseDoc->createElement('SigningTime', date('c'));
        $signedSignatureProperties->appendChild($signingTime);

        // Информация о подписанте
        $signingCertificate = $baseDoc->createElement('SigningCertificate');
        $signedSignatureProperties->appendChild($signingCertificate);

        //
        $signaturePolicyIdentifier = $baseDoc->createElement('SignaturePolicyIdentifier');
        $signedSignatureProperties->appendChild($signaturePolicyIdentifier);

        $signaturePolicyImplied = $baseDoc->createElement('SignaturePolicyImplied');
        $signaturePolicyIdentifier->appendChild($signaturePolicyImplied);
        //

        $cert = $baseDoc->createElement('Cert');
        $signingCertificate->appendChild($cert);

        $certDigest = $baseDoc->createElement('CertDigest');
        $cert->appendChild($certDigest);

        $digestMethod = $baseDoc->createElementNS(static::XMLDSIGNS, 'DigestMethod');
        $digestMethod->setAttribute('Algorithm', static::SHA1);
        $certDigest->appendChild($digestMethod);

        // Получение digestValue

        if (!$this->terminalId) {
            $this->terminalId = Yii::$app->terminals->defaultTerminal->terminalId;
        }

        // hack for backward compatibility with the old cyberft-sign service
        if (empty($x509Info)) {
            $participant = Address::truncateAddress($this->terminalId);

            $query = Cert::findByParticipant($participant);
            $certObj = $query->select([])->one();

            // Если сертификат контролера не найден, выбрасываем исключение
            if (empty($certObj)) {
               throw new InvalidValueException("Can't find active controller certificate ({$this->terminalId})");
            }

            $x509Info = X509FileModel::loadData($certObj->body);
        }

        $value = static::calculateDigest(static::SHA1, $x509Info->body);

        $digestValue = $baseDoc->createElementNS(static::XMLDSIGNS, 'DigestValue', $value);
        $certDigest->appendChild($digestValue);

        $issuerSerial = $baseDoc->createElement('IssuerSerial');
        $cert->appendChild($issuerSerial);

        $issuerName = $baseDoc->createElementNS(static::XMLDSIGNS, 'X509IssuerName');
        $issuerName->appendChild($baseDoc->createTextNode($x509Info->issuerName));
        $issuerSerial->appendChild($issuerName);

        $issuerSerialNumber = $baseDoc->createElementNS(static::XMLDSIGNS, 'X509SerialNumber', $x509Info->serialNumber);
        $issuerSerial->appendChild($issuerSerialNumber);

    }

	/**
	 * Функция экстрактирует из документа данные, над которыми выполняется подписание.
	 * Требуется для выполнения подписания данных на тонком клиенте.
	 * @param DOMElement $data
	 * @param array $config Конфигурация, устанавливающая параметры экстракции
	 * @return string Строка данных для подписания
	 */
	public function extractData($data, $config)
	{
		// Устанавливаем метод канонизации по умолчанию
		$this->setCanonicalMethod($config['canonicalMethod']);
		// Референс на подписываемые данные, сгенерирует URI='', т.е. подписываем
		// все XML-данные внутри файла, с применимыми трансформациями
		$this->addReference($data, $config['algorithm'], [
				$config['signatureClass'], [
					$config['transformationName'] => $config['transformation'],
				],
			], [
				'overwrite' => false, 'force_uri' => true
			]
		);

		// Экстрактируем данные, подлежащие подписанию
		if (($xpath = $this->getXPathObj())) {
			$query = './secdsig:SignedInfo';
			$nodeset = $xpath->query($query, $this->sigNode);
			if (($sInfo = $nodeset->item(0))) {
				$query = './secdsig:SignatureMethod';
				$nodeset = $xpath->query($query, $sInfo);
				$sMethod = $nodeset->item(0);
				$sMethod->setAttribute('Algorithm', $config['keyClass']);

                $data = static::canonicalizeData($sInfo, $this->canonicalMethod);

                return $data;
			}
		}

		return '';
	}

	/**
	 * Функция добавляет подпись в XML-документ, выполненную по технологии XML
	 * Signature. Вычислителем подписи является внешний криптомодуль из тонкого
	 * клиента CyberFT.
	 * @param DOMDocument $data Документ для подписания
	 * @param string $signature Подпись
     * @param string $certBody Тело сертификата
	 * @param array $config Конфигурация, устанавливающая параметры подписи
	 * @return boolean Возвращает true в случае успешного завершения и false
	 * в противном случае.
	 */
	public function injectSignature($data, $signature, $certBody, $config)
	{
        // Hack for backward compatibility with cyberft-sign service
        $x509Info = X509FileModel::loadData($certBody);
        $fingerprint = $x509Info->fingerprint;
//        if (X509FileModel::isCertificate($certBody)) {
//            $x509Info = X509FileModel::loadData($certBody);
//            $fingerprint = $x509Info->fingerprint;
//        } else {
//            $x509Info = null;
//            $fingerprint = $certBody;
//        }

		// Создаем объект для поиска узла, в котором будет храниться подпись
		$xpath = new DOMXPath($data);
		$xpath->registerNamespace($config['defaultNamespacePrefix'], $config['defaultNamespace']);
		// Получаем заголовок xml-документа, в который будет добавлена подпись
		$headerNode = $xpath->query($config['headerPath']);
		if ($headerNode->length) {
			// Получаем узел заголовка, куда будет впоследствии добавлена подпись
			$headerNode = $headerNode->item(0);
		} else {
			return false;
		}

		// Устанавливаем метод каноникализации
		$this->setCanonicalMethod($config['canonicalMethod']);
		// Референс на подписываемые данные
		$this->addReference($data, $config['algorithm'], [
				$config['signatureClass'], [
					$config['transformationName'] => $config['transformation'],
				],
			], [
				'overwrite' => false, 'force_uri' => true
			]
		);

		// Помещаем подпись в указанную ветку
		if (($xpath = $this->getXPathObj())) {
			$query = './secdsig:SignedInfo';
			$nodeset = $xpath->query($query, $this->sigNode);
			if (($sInfo = $nodeset->item(0))) {
				$query = './secdsig:SignatureMethod';
				$nodeset = $xpath->query($query, $sInfo);
				$sMethod = $nodeset->item(0);
				$sMethod->setAttribute('Algorithm', $config['keyClass']);

				$sigValueNode = $this->createNewSignNode('SignatureValue', $signature);
				if (($infoSibling = $sInfo->nextSibling)) {
					$infoSibling->parentNode->insertBefore($sigValueNode, $infoSibling);
				} else {
					$this->sigNode->appendChild($sigValueNode);
				}
			}
		}
		// Добавляем сведения о ключе в соответствующий раздел подписи
		$this->addKeyInfo($fingerprint);

        // Добавляем информацию о подписании и подписателе
        if (!$this->terminalId) {
            $this->terminalId = Yii::$app->terminals->defaultTerminal->terminalId;
        }

        $this->addSigningInfo($x509Info);

		// Добавляем подпись в структуру документа, создавая тэг-контейнер подписи
		$mySignatureNode = $data->createElementNS($config['defaultNamespace'], $config['signatureContainerTag']);
		$headerNode->appendChild($mySignatureNode);
		$this->appendSignature($mySignatureNode);

		return true;
	}

	/**
	 * Функция выполняет подписание XML-документа по технологии XML Signature,
	 * используя формат файлов XML-CyberFT
	 * @param DOMDocument $domDocument Документ для подписания
	 * @param string $privateKey Приватный ключ для подписания
	 * @param string $passphrase Пароль приватного ключа
	 * @param string $fingerprint Отпечаток сертификата
     * @param string $certificate Тело сертификата
     * @param array $config Конфигурация, устанавливающая параметры подписи
	 * @return boolean Возвращает true в случае успешного завершения и false
	 * в противном случае.
	 */
	public function signDocument($domDocument, $privateKey, $passphrase, $fingerprint, $certificate, $config)
	{
        // Создаем новую ветку для работы с подписью
        // Используется в случае кэширования модуля подписания
        $this->xPathCtx = null;
        $sigdoc = new DOMDocument();
        $sigdoc->loadXML(static::SIGNATURE_TEMPLATE, LIBXML_PARSEHUGE);
        $this->sigNode = $sigdoc->documentElement;

		// Создаем объект для поиска узла, в котором будет храниться подпись
		$xpath = new DOMXPath($domDocument);
		$xpath->registerNamespace($config['defaultNamespacePrefix'], $config['defaultNamespace']);

		// Получаем заголовок xml-документа, в который будет добавлена подпись
		$headerNode = $xpath->query($config['headerPath']);
		if ($headerNode->length) {
			// Получаем узел заголовка, куда будет впоследствии добавлена подпись
			$headerNode = $headerNode->item(0);
		} else {

			return false;
		}

		// Создаем объект-приватный ключ
		$objKey = new XMLSecurityKey(
				$config['keyClass'], [
					'type' => 'private',
				]
		);
		// Обязательный пароль для приватного ключа
		$objKey->passphrase = $passphrase;

		// Ключ должен грузиться из файла
		$objKey->loadKey($privateKey, is_file($privateKey));

		// Устанавливаем метод каноникализации
		$this->setCanonicalMethod($config['canonicalMethod']);
		// Референс на подписываемые данные

		$this->addReference(
            $domDocument,
            $config['algorithm'],
            [
                $config['signatureClass'],
                [
                    $config['transformationName'] => $config['transformation'],
                ],
            ],
            [
                'overwrite' => false,
                'force_uri' => true
            ]
		);

		// Добавляем сведения о ключе в соответствующий раздел подписи
		$this->addKeyInfo($fingerprint);

        try {

            $x509Info = X509FileModel::loadData($certificate);

            $this->addSigningInfo($x509Info);
            // Выполняем подписание, помещая подпись в указанную ветку
            $this->sign($objKey);

            // Добавляем подпись в структуру документа, создавая тэг-контейнер подписи
            $mySignatureNode = $domDocument->createElementNS($config['defaultNamespace'], $config['signatureContainerTag']);
            $headerNode->appendChild($mySignatureNode);
            $this->appendSignature($mySignatureNode);

        } catch (InvalidValueException $ex) {

            // Обработка исключения, в случае
            // отсутствия сертификата контроллера
            Yii::error($ex->getMessage(), 'system');

            // Создание нового исключения для джоба подписания
            throw new InvalidValueException('failed to sign - ' . $ex->getMessage());

        } catch (Exception $ex) {
            Yii::error($ex->getMessage(), 'system');

            return false;
        }

		return true;
	}

	/**
	 * Функция верифицирует сигнатуру с указанными параметрами
	 * @param DOMNode $signature Подпись, подлежащая проверке
	 * @param string $certBody Сертификат для проверки подписи
	 * @return boolean
	 */
	public function verifySignature($signature, $certBody, $containerPos = 1, $signaturePos = 1)
	{
		$keyClass = $this->determineKeyClass($signature);

        if (false === $keyClass) {
			throw new Exception('keyClass not found');
		}

		$verificationKey = new XMLSecurityKey($keyClass, ['type' => 'public']);

		// Выполняем загрузку указанного сертификата из строки
		/**
		 * Load key from string
		 */
		$verificationKey->loadKeyVerify($certBody);

		// Позиционируемся на указанную сигнатуру
		$this->toSignatureNode($signature);
		// Выполняем каноническое преобразование данных
		$this->canonicalizeSignedInfo();
		// Проверяем подпись

		if (1 !== $this->verify($verificationKey)) {
			return false;
		}

		// Проверяем ссылки (references)
		try {
			$this->validateReference();
		} catch (Exception $ex) {
            Yii::error($ex->getBody);

            return false;
		}

		return true;
	}

	private function determineKeyClass($signature)
	{
		if (!($signature instanceof DOMElement)) {
			throw new Exception('Signature is not a DOMElement');
		}

		$searchNodes = $signature->getElementsByTagName('SignatureMethod');

		foreach ($searchNodes as $searchNode) {
            $algorithm = $searchNode->getAttribute('Algorithm');
			if (in_array($algorithm, XMLSecurityKey::getAllKeyClass())) {
				return $algorithm;
			}
		}

		return false;
	}

}
