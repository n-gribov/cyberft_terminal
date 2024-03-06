<?php
namespace addons\swiftfin\config\mt0xx;

function getChoiceScheme($tag, $keys) {
	$tagScheme = [];

	if (!isset($tagScheme[$tag])) {
		$scheme = [
			$keys[0] => [
				'name'   => $keys[0],
				'type'   => 'sequence',
				'scheme' => [
					[
						#'name'  => (int)$tag.$keys[0],
						'label' => 'Input',
						'field' => 'textarea'
					]
				]
			]
		];
		return $scheme;
	}

	return array_intersect_key($tagScheme[$tag], array_fill_keys($keys, null));
}