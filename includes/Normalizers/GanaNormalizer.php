<?php

namespace MediaWiki\Extension\TextTransform\Normalizers;

use MediaWiki\Extension\TextTransform\Normalizers\Data\GanaConversion;

class GanaNormalizer implements Normalizer
{
	private static $littleRegex = null;

	private static $beforeRegex = '/([aiueo])ー/u';

	private static $afterRegex = '/(?:ッ|っ)([bcdfghjkmnprstvwyz])/u';

	/**
	 * {@inheritDoc}
	 */
	public static function normalize(string $input): string
	{
		return self::romaji($input);
	}

	/**
	 * Converts hiragana to romaji.
	 * 
	 * @param string $input
	 * @return string
	 */
	public static function romaji(string $input): string
	{
		if (self::$littleRegex === null) {
			self::$littleRegex = '/(?<=[knhftmyrwgbpdvjz])[aiueo](' . implode('|', array_keys(GanaConversion::$little2Romaji)) . ')/u';
		}

		$text = strtr($input, GanaConversion::$gana2Romaji);
		$text = preg_replace_callback(
			self::$littleRegex,
			function ($res) {
				return GanaConversion::$little2Romaji[$res[1]];
			},
			$text
		);
		$text = preg_replace(self::$beforeRegex, '$1$1', $text);
		$text = preg_replace(self::$afterRegex, '$1$1', $text);
		$text = strtr($text, GanaConversion::$little2Romaji + GanaConversion::$short2Empty);

		return $text;
	}
}
