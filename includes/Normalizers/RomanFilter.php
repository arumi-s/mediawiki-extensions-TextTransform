<?php

namespace MediaWiki\Extension\TextTransform\Normalizers;

class RomanFilter extends LatinFilter
{
	/**
	 * {@inheritDoc}
	 */
	public static function filter(string &$input): bool
	{
		if (preg_match('/[\x{00C0}-\x{00D6}\x{00D8}-\x{00F6}\x{00F8}-\x{024F}\x{0259}\x{027C}\x{0292}\x{0386}\x{0388}-\x{03FF}\x{0400}-\x{04FF}\x{1E02}-\x{1EF3}\x{1F00}-\x{1FFF}\x{2C60}-\x{2C7F}]/u', $input)) {
			$input = mb_strtolower($input, 'UTF-8');
			return true;
		}
		return false;
	}
}
