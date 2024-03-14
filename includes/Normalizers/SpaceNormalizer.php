<?php

namespace MediaWiki\Extension\TextTransform\Normalizers;

class SpaceNormalizer implements Normalizer
{
	/**
	 * {@inheritDoc}
	 */
	public static function normalize(string $input): string
	{
		return preg_replace('/[\\s\x{00A0}\x{1680}\x{180E}\x{2002}-\x{200D}\x{202F}\x{205F}\x{2060}\x{3000}\x{FEFF}]/u', ' ', $input);
	}
}

