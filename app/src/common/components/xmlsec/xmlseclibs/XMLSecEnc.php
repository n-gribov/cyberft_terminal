<?php

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

namespace common\components\xmlsec\xmlseclibs;

use common\helpers\Uuid;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Exception;

class XMLSecEnc
{
	const ENC_TEMPLATE = <<<XML
<xenc:EncryptedData xmlns:xenc='http://www.w3.org/2001/04/xmlenc#'>
   <xenc:CipherData>
      <xenc:CipherValue></xenc:CipherValue>
   </xenc:CipherData>
</xenc:EncryptedData>
XML;
	const XMLENCNS = 'http://www.w3.org/2001/04/xmlenc#';
	const ELEMENT = 'http://www.w3.org/2001/04/xmlenc#Element';
	const CONTENT = 'http://www.w3.org/2001/04/xmlenc#Content';
	const ENCRYPTED_KEY = 'http://www.w3.org/2001/04/xmlenc#EncryptedKey';

	private $encdoc = null;
	private $rawNode = null;
	public $type = null;
	public $encKey = null;
	private $references = [];

	public function __construct()
	{
		$this->resetTemplate();
	}

	private function resetTemplate()
	{
		$this->encdoc = new DOMDocument();
		$this->encdoc->loadXML(XMLSecEnc::ENC_TEMPLATE);
	}

	public function addReference($name, $node, $type)
	{
		if (!$node instanceOf DOMNode) {
			throw new Exception("$node is not of type DOMNode");
		}

        $curencdoc = $this->encdoc;
		$this->resetTemplate();
		$encdoc = $this->encdoc;
		$this->encdoc = $curencdoc;
		$refuri = XMLSeclibsHelper::generateGUID();
		$element = $encdoc->documentElement;
		$element->setAttribute('Id', $refuri);
		$this->references[$name] = ['node' => $node, 'type' => $type, 'encnode' => $encdoc, 'refuri' => $refuri];
	}

	public function setNode($node)
	{
		$this->rawNode = $node;
	}

	public function encryptNode($objKey, $replace = true)
	{
		$data = '';

		if (empty($this->rawNode)) {
			throw new Exception('Node to encrypt has not been set');
		}

		if (!$objKey instanceof XMLSecurityKey) {
			throw new Exception('Invalid Key');
		}

		$doc = $this->rawNode->ownerDocument;
		$xPath = new DOMXPath($this->encdoc);
		$objList = $xPath->query('/xenc:EncryptedData/xenc:CipherData/xenc:CipherValue');
		$cipherValue = $objList->item(0);

		if ($cipherValue == null) {
			throw new Exception('Error locating CipherValue element within template');
		}

		switch ($this->type) {
			case (XMLSecEnc::ELEMENT):
				$data = $doc->saveXML($this->rawNode);
				$this->encdoc->documentElement->setAttribute('Type', XMLSecEnc::ELEMENT);

				break;
			case (XMLSecEnc::CONTENT):
				$children = $this->rawNode->childNodes;
				foreach ($children AS $child) {
					$data .= $doc->saveXML($child);
				}
				$this->encdoc->documentElement->setAttribute('Type', XMLSecEnc::CONTENT);

				break;
			default:
				throw new Exception('Type is currently not supported');
		}

		$encMethod = $this->encdoc->documentElement->appendChild($this->encdoc->createElementNS(XMLSecEnc::XMLENCNS, 'xenc:EncryptionMethod'));
		$encMethod->setAttribute('Algorithm', $objKey->getAlgorith());
		$cipherValue->parentNode->parentNode->insertBefore($encMethod, $cipherValue->parentNode->parentNode->firstChild);

		$strEncrypt = base64_encode($objKey->encryptData($data));
		$value = $this->encdoc->createTextNode($strEncrypt);
		$cipherValue->appendChild($value);

		if ($replace) {
			switch ($this->type) {
				case (XMLSecEnc::ELEMENT):
					if ($this->rawNode->nodeType == XML_DOCUMENT_NODE) {
						return $this->encdoc;
					}
					$importEnc = $this->rawNode->ownerDocument->importNode($this->encdoc->documentElement, true);
					$this->rawNode->parentNode->replaceChild($importEnc, $this->rawNode);

					return $importEnc;
				case (XMLSecEnc::CONTENT):
					$importEnc = $this->rawNode->ownerDocument->importNode($this->encdoc->documentElement, true);
					while ($this->rawNode->firstChild) {
						$this->rawNode->removeChild($this->rawNode->firstChild);
					}
					$this->rawNode->appendChild($importEnc);

					return $importEnc;
			}
		}
	}

	public function encryptReferences($objKey)
	{
		$curRawNode = $this->rawNode;
		$curType = $this->type;

		foreach ($this->references AS $name => $reference) {
			$this->encdoc = $reference['encnode'];
			$this->rawNode = $reference['node'];
			$this->type = $reference['type'];
			try {
				$encNode = $this->encryptNode($objKey);
				$this->references[$name]['encnode'] = $encNode;
			} catch (Exception $ex) {
				$this->rawNode = $curRawNode;
				$this->type = $curType;

				throw $ex;
			}
		}

		$this->rawNode = $curRawNode;
		$this->type = $curType;
	}

	/**
	 * Retrieve the CipherValue text from this encrypted node.
	 *
	 * @return string | null  The Ciphervalue text, or null if no CipherValue is found.
	 */
	public function getCipherValue()
	{
		if (empty($this->rawNode)) {
			throw new Exception('Node to decrypt has not been set');
		}

		$doc = $this->rawNode->ownerDocument;
		$xPath = new DOMXPath($doc);
		$xPath->registerNamespace('xmlencr', XMLSecEnc::XMLENCNS);

		// Only handles embedded content right now and not a reference
		$query = "./xmlencr:CipherData/xmlencr:CipherValue";

		$nodeset = $xPath->query($query, $this->rawNode);
		$node = $nodeset->item(0);

		if (!$node) {

			return null;
		}

		return base64_decode($node->nodeValue);
	}

	/**
	 * Decrypt this encrypted node.
	 *
	 * The behaviour of this function depends on the value of $replace.
	 * If $replace is false, we will return the decrypted data as a string.
	 * If $replace is true, we will insert the decrypted element(s) into the
	 * document, and return the decrypted element(s).
	 *
	 * @params XMLSecurityKey $objKey  The decryption key that should be used when decrypting the node.
	 * @params boolean $replace  Whether we should replace the encrypted node in the XML document with the decrypted data. The default is true.
	 * @return string|DOMElement  The decrypted data.
	 */
	public function decryptNode($objKey, $replace = true)
	{
		if (!$objKey instanceof XMLSecurityKey) {
			throw new Exception('Invalid Key');
		}

		$encryptedData = $this->getCipherValue();

		if ($encryptedData) {
			$decrypted = $objKey->decryptData($encryptedData);

            /**
             * Check for ZIP header
             */
            if (substr($decrypted, 0, 4) === "PK\x3\x4") {
                $decrypted = \common\helpers\ZipHelper::unpackStringFromString($decrypted);
            }
			if ($replace) {
				if ($this->type == XMLSecEnc::ELEMENT) {
                    $newdoc = new DOMDocument();
                    $newdoc->loadXML($decrypted, LIBXML_PARSEHUGE);

                    if ($this->rawNode->nodeType == XML_DOCUMENT_NODE) {

                        return $newdoc;
                    }

                    $importEnc = $this->rawNode->ownerDocument->importNode($newdoc->documentElement, true);
                    //$this->rawNode->parentNode->replaceChild($importEnc, $this->rawNode);
                    \common\base\DOMCyberXml::replaceChild($this->rawNode, $decrypted);

                    return $importEnc;
                } else if ($this->type == XMLSecEnc::CONTENT) {
                    if ($this->rawNode->nodeType == XML_DOCUMENT_NODE) {
                        $doc = $this->rawNode;
                    } else {
                        $doc = $this->rawNode->ownerDocument;
                    }

                    //$newFrag = $doc->createDocumentFragment();
                    //$newFrag->appendXML($decrypted);
                    $parent = $this->rawNode->parentNode;
                    //$parent->replaceChild($newFrag, $this->rawNode);

                    \common\base\DOMCyberXml::replaceChild($this->rawNode, $decrypted);

                    return $parent;//this->rawNode->parentNode;
				}
			}

			return $decrypted;

		} else {
			throw new Exception('Cannot locate encrypted data');
		}
	}

	public function encryptKey($srcKey, $rawKey, $append = true)
	{
		if ((!$srcKey instanceof XMLSecurityKey) || (!$rawKey instanceof XMLSecurityKey)) {
			throw new Exception('Invalid Key');
		}

		$strEncKey = base64_encode($srcKey->encryptData($rawKey->key));

		$root = $this->encdoc->documentElement;
		$encKey = $this->encdoc->createElementNS(XMLSecEnc::XMLENCNS, 'xenc:EncryptedKey');
        $encKey->setAttribute('Id', 'id' . Uuid::generate(false));

        if ($append) {
			$keyInfo = $root->insertBefore($this->encdoc->createElementNS(XMLSecurityDSig::XMLDSIGNS, 'dsig:KeyInfo'), $root->firstChild);
			$keyInfo->appendChild($encKey);
		} else {
			$this->encKey = $encKey;
		}

		$encMethod = $encKey->appendChild($this->encdoc->createElementNS(XMLSecEnc::XMLENCNS, 'xenc:EncryptionMethod'));
		$encMethod->setAttribute('Algorithm', $srcKey->getAlgorith());

        $encKeyInfo = $encKey->appendChild($this->encdoc->createElementNS(XMLSecurityDSig::XMLDSIGNS, 'dsig:KeyInfo'));

        $encKeyInfo->appendChild(
            $this->encdoc->createElementNS(
                XMLSecurityDSig::XMLDSIGNS,
                'dsig:KeyName',
                strtoupper($srcKey->getX509Thumbprint()))
        );

        $encX509Data = $encKeyInfo->appendChild(
            $this->encdoc->createElementNS(
                XMLSecurityDSig::XMLDSIGNS,
                'dsig:X509Data')
        );

        $encX509Data->appendChild(
            $this->encdoc->createElementNS(
                XMLSecurityDSig::XMLDSIGNS,
                'dsig:X509SubjectName',
                htmlentities($srcKey->parsedCertData['name']))
        );

		if (!empty($srcKey->name)) {
			$keyInfo = $encKey->appendChild($this->encdoc->createElementNS(XMLSecurityDSig::XMLDSIGNS, 'dsig:KeyInfo'));
			$keyInfo->appendChild($this->encdoc->createElementNS(XMLSecurityDSig::XMLDSIGNS, 'dsig:KeyName', $srcKey->name));
		}

		$cipherData = $encKey->appendChild($this->encdoc->createElementNS(XMLSecEnc::XMLENCNS, 'xenc:CipherData'));
		$cipherData->appendChild($this->encdoc->createElementNS(XMLSecEnc::XMLENCNS, 'xenc:CipherValue', $strEncKey));

        if (is_array($this->references) && count($this->references) > 0) {
			$refList = $encKey->appendChild($this->encdoc->createElementNS(XMLSecEnc::XMLENCNS, 'xenc:ReferenceList'));
			foreach ($this->references as $name => $reference) {
				$refuri = $reference['refuri'];
				$dataRef = $refList->appendChild($this->encdoc->createElementNS(XMLSecEnc::XMLENCNS, 'xenc:DataReference'));
				$dataRef->setAttribute('URI', '#' . $refuri);
			}
		}

		return;
	}

	public function decryptKey($encKey)
	{
        if (!$encKey->isEncrypted) {
			throw new Exception('Key is not Encrypted');
		}

		if (empty($encKey->key)) {
			throw new Exception('Key is missing data to perform the decryption');
		}

		return $this->decryptNode($encKey, false);
	}

	public function locateEncryptedData($element)
	{
		if ($element instanceof DOMDocument) {
			$doc = $element;
		} else {
			$doc = $element->ownerDocument;
		}

		if ($doc) {
			$xpath = new DOMXPath($doc);
			$query = "//*[local-name()='EncryptedData' and namespace-uri()='" . XMLSecEnc::XMLENCNS . "']";
			$nodeset = $xpath->query($query);
			return $nodeset->item(0);
		}

		return null;
	}

	public function locateKey($node = null)
	{
		if (empty($node)) {
			$node = $this->rawNode;
		}

		if (!$node instanceof DOMNode) {

			return null;
		}

		if (($doc = $node->ownerDocument)) {
			$xpath = new DOMXPath($doc);
			$xpath->registerNamespace('xmlsecenc', XMLSecEnc::XMLENCNS);
			$query = './/xmlsecenc:EncryptionMethod';
			$nodeset = $xpath->query($query, $node);

            if (($encmeth = $nodeset->item(0))) {
				$attrAlgorithm = $encmeth->getAttribute('Algorithm');
				try {
					$objKey = new XMLSecurityKey($attrAlgorithm, ['type' => 'private']);

				} catch (Exception $ex) {

					return null;
				}

				return $objKey;
			}
		}

		return null;
	}

	public static function staticLocateKeyInfo($objBaseKey = null, $node = null)
	{
		if (empty($node) || (!$node instanceof DOMNode)) {
			return null;
		}

		$doc = $node->ownerDocument;

		if (!$doc) {
			return null;
		}

		$xpath = new DOMXPath($doc);
		$xpath->registerNamespace('xmlsecenc', XMLSecEnc::XMLENCNS);
		$xpath->registerNamespace('xmlsecdsig', XMLSecurityDSig::XMLDSIGNS);
		$query = './xmlsecdsig:KeyInfo';
		$nodeset = $xpath->query($query, $node);
		$encmeth = $nodeset->item(0);

		if (!$encmeth) {
			/* No KeyInfo in EncryptedData / EncryptedKey. */
			return $objBaseKey;
		}
		$encryptedKeys = [];
		foreach ($encmeth->childNodes AS $child) {
            switch ($child->localName) {
				case 'KeyName':
					if (!empty($objBaseKey)) {
						$objBaseKey->name = $child->nodeValue;
					}
					break;
				case 'KeyValue':
					foreach ($child->childNodes AS $keyval) {
						switch ($keyval->localName) {
							case 'DSAKeyValue':
								throw new Exception('DSAKeyValue currently not supported');

								break;
							case 'RSAKeyValue':
								$modulus = null;
								$exponent = null;
								if (($modulusNode = $keyval->getElementsByTagName('Modulus')->item(0))) {
									$modulus = base64_decode($modulusNode->nodeValue);
								}
								if (($exponentNode = $keyval->getElementsByTagName('Exponent')->item(0))) {
									$exponent = base64_decode($exponentNode->nodeValue);
								}
								if (empty($modulus) || empty($exponent)) {
									throw new Exception('Missing Modulus or Exponent');
								}
								$publicKey = XMLSecurityKey::convertRSA($modulus, $exponent);
								$objBaseKey->loadKey($publicKey);

								break;
						}
					}
					break;
				case 'RetrievalMethod':
					$type = $child->getAttribute('Type');
					if ($type !== XMLSecEnc::ENCRYPTED_KEY) {
						/* Unsupported key type. */
						break;
					}
					$uri = $child->getAttribute('URI');
					if ($uri[0] !== '#') {
						/* URI not a reference - unsupported. */
						break;
					}
					$id = substr($uri, 1);

					$query = "//xmlsecenc:EncryptedKey[@Id='$id']";
					$keyElement = $xpath->query($query)->item(0);
					if (!$keyElement) {
						throw new Exception("Unable to locate EncryptedKey with @Id='$id'.");
					}

					return XMLSecurityKey::fromEncryptedKeyElement($keyElement);
				case 'EncryptedKey':
                    $encryptedKeys[] = XMLSecurityKey::fromEncryptedKeyElement($child);
				case 'X509Data':
					if (($x509certNodes = $child->getElementsByTagName('X509Certificate'))) {
						if ($x509certNodes->length > 0) {
							$x509cert = $x509certNodes->item(0)->textContent;
							$x509cert = str_replace(["\r", "\n"], '', $x509cert);
							$x509cert = "-----BEGIN CERTIFICATE-----\n" . chunk_split($x509cert, 64, "\n") . "-----END CERTIFICATE-----\n";
							$objBaseKey->loadKey($x509cert, false, true);
						}
					}
					break;
			}
		}

		if (!empty($encryptedKeys)) {
			return $encryptedKeys;
		}

		return $objBaseKey;
	}

	public function locateKeyInfo($objBaseKey = null, $node = null)
	{
		if (empty($node)) {
			$node = $this->rawNode;
		}

		return static::staticLocateKeyInfo($objBaseKey, $node);
	}

}