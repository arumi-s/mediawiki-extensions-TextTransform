<?php

namespace MediaWiki\Extension\TextTransform\Normalizers;

class GanaFilter implements Filter
{
	public const TYPE = 4;

	/**
	 * {@inheritDoc}
	 */
	public static function filter(string &$input): bool
	{
		if (preg_match('/[\x{3041}-\x{3093}\x{30A1}-\x{30F6}\x{30FC}]/u', $input)) {
			return true;
		}
		return false;
	}
}
