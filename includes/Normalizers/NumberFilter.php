<?php

namespace MediaWiki\Extension\TextTransform\Normalizers;

class NumberFilter implements Filter
{
	public const TYPE = 2;

	/**
	 * {@inheritDoc}
	 */
	public static function filter(string &$input): bool
	{
		if (preg_match('/[0-9]/u', $input)) {
			return true;
		}
		return false;
	}
}
