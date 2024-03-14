<?php

namespace MediaWiki\Extension\TextTransform\Normalizers;

class SpaceFilter implements Filter
{
	public const TYPE = 1;

	/**
	 * {@inheritDoc}
	 */
	public static function filter(string &$input): bool
	{
		if ($input === ' ') {
			return true;
		}
		return false;
	}
}
