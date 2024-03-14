<?php

namespace MediaWiki\Extension\TextTransform\Normalizers;

use MediaWiki\Extension\TextTransform\Normalizers\Data\WidthConversion;

class WidthNormalizer implements Normalizer
{
	/**
	 * {@inheritDoc}
	 */
	public static function normalize(string $input): string
	{
		return strtr($input, WidthConversion::$table);
	}
}
