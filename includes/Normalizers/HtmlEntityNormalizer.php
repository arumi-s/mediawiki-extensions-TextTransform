<?php

namespace MediaWiki\Extension\TextTransform\Normalizers;

class HtmlEntityNormalizer implements Normalizer
{
	/**
	 * {@inheritDoc}
	 */
	public static function normalize(string $input): string
	{
		return html_entity_decode($input, ENT_QUOTES | ENT_XML1);
	}
}
