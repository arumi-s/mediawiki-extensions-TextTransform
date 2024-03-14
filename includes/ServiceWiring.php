<?php

namespace MediaWiki\Extension\TextTransform;

use MediaWiki\MediaWikiServices;
use MediaWiki\Extension\TextTransform\Normalizers\TextNormalizer;
use MediaWiki\Extension\TextTransform\Normalizers\CachedTextNormalizer;

/**
 * TextTransform wiring for MediaWiki services.
 */
return [
	'TextTransform.TextNormalizer' => static function (MediaWikiServices $services) {

		return new TextNormalizer();
	},
	'TextTransform.CachedTextNormalizer' => static function (MediaWikiServices $services) {

		return new CachedTextNormalizer(
			$services->getMainConfig(),
			$services->getMainWANObjectCache(),
		);
	},
];
