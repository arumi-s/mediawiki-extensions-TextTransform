<?php

namespace MediaWiki\Extension\TextTransform\Normalizers;

class LatinFilter implements Filter
{
	public const TYPE = 3;

	/**
	 * {@inheritDoc}
	 */
	public static function filter(string &$input): bool
	{
		if (preg_match('/[A-Za-z]/u', $input)) {
			$input = strtolower($input);
			return true;
		}
		return false;
	}
}
