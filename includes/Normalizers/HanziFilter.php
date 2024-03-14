<?php

namespace MediaWiki\Extension\TextTransform\Normalizers;

use MediaWiki\Extension\TextTransform\Normalizers\Data\HanziConversion;
use MediaWiki\Languages\Data\ZhConversion;

class HanziFilter implements Filter
{
	public const TYPE = 5;

	/**
	 * {@inheritDoc}
	 */
	public static function filter(string &$input): bool
	{
		if (preg_match('/[\x{4E00}-\x{62FF}\x{6300}-\x{77FF}\x{7800}-\x{8CFF}\x{8D00}-\x{9FFF}\x{3400}-\x{4DBF}\x{F900}-\x{FAFF}]/u', $input)) {
			$input = HanziConversion::$jp2Hans[$input] ?? ZhConversion::$zh2Hans[$input] ?? $input;
			return true;
		}
		return false;
	}
}
