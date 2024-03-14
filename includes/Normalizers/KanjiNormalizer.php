<?php

namespace MediaWiki\Extension\TextTransform\Normalizers;

use MediaWiki\Extension\TextTransform\Normalizers\Data\GanaConversion;
use MediaWiki\Extension\TextTransform\Normalizers\Data\KanjiConversion;


class KanjiNormalizer implements Normalizer
{
	private static $consecutiveKanjiRegex = '/([\x{3041}-\x{3093}]?)([^\x{3000}-\x{3002}\x{FF01}-\x{FF1F}\x{2010}-\x{2033}\x{3041}-\x{30F6}\x{30FC}]{2,})/u';

	private static $singleKanjiRegex = '/([\x{30A1}-\x{30F6}])([^\x{3000}-\x{3002}\x{FF01}-\x{FF1F}\x{2010}-\x{2033}\x{3041}-\x{30F6}\x{30FC}])/u';

	/**
	 * {@inheritDoc}
	 */
	public static function normalize(string $input): string
	{
		$text = self::hiragana($input);
		$text = GanaNormalizer::normalize($text);
		return $text;
	}

	/**
	 * Converts kanji to hiragana
	 * 
	 * @param string $input
	 * @return string
	 */
	public static function hiragana(string $input): string
	{
		if (strlen($input) == mb_strlen($input, 'UTF-8')) {
			// skip pure ascii
			return $input;
		}

		$text = preg_replace('/(.)々/u', '$1$1', $input);
		$text = strtr($text, KanjiConversion::$kanji2Hira);
		$text = preg_replace_callback(self::$consecutiveKanjiRegex, [self::class, 'onyomi'], $text);
		$text = preg_replace_callback(self::$singleKanjiRegex, [self::class, 'onyomi'], $text);
		$text = strtr($text, KanjiConversion::$kanji2Kun);

		return $text;
	}

	/**
	 * Converts kanji to onyomi hiragana
	 * 
	 * @param string[] $input
	 * @return string
	 */
	private static function onyomi($text): string
	{
		$precedingGana = $text[1];
		$lastChar = strtr($text[1], GanaConversion::$kata2Hira);
		$chars = mb_str_split($text[2]);

		for ($i = 0, $l = count($chars); $i < $l; ++$i) {
			$char = &$chars[$i];
			$onyomi = KanjiConversion::$kanji2On[$char] ?? null;
			if ($onyomi === null) {
				continue;
			}

			$char = $onyomi;
			if ($i > 0) {
				$lastChar = mb_substr($chars[$i - 1], -1, 1, 'UTF-8');
			}
			$firstChar = mb_substr($char, 0, 1, 'UTF-8');

			if (isset(GanaConversion::$haRow[$firstChar])) {
				if ($lastChar == 'つ' || $lastChar == 'っ' || $lastChar == 'ち' || $lastChar == 'ん') {
					$char = GanaConversion::$haRow[$firstChar] . mb_substr($char, 1, null, 'UTF-8');
				} else if ($lastChar) {
					$char = GanaConversion::$haRow2[$firstChar] . mb_substr($char, 1, null, 'UTF-8');
				}
				if ($i > 0) {
					$chars[$i - 1] = preg_replace('/(?<=.)[\x{3064}\x{3061}]$/u', 'っ', $chars[$i - 1]);
				}
			} else if (isset(GanaConversion::$kaRow[$firstChar])) {
				if ($lastChar == 'つ' || $lastChar == 'っ' || $lastChar == 'ち' || $lastChar == 'く') {
					$char = GanaConversion::$kaRow[$firstChar] . mb_substr($char, 1, null, 'UTF-8');
				}
				if ($i > 0) {
					$chars[$i - 1] = preg_replace('/(?<=.)[\x{3064}\x{304F}\x{3061}]$/u', 'っ', $chars[$i - 1], -1);
				}
			} else if (isset(GanaConversion::$saRow[$firstChar])) {
				if ($lastChar == 'つ' || $lastChar == 'っ' || $lastChar == 'ち') {
					$char = GanaConversion::$saRow[$firstChar] . mb_substr($char, 1, null, 'UTF-8');
				}
				if ($i > 0) {
					$chars[$i - 1] = preg_replace('/(?<=.)[\x{3064}\x{3061}]$/u', 'っ', $chars[$i - 1], -1);
				}
			} else if (isset(GanaConversion::$taRow[$firstChar])) {
				if ($lastChar == 'つ' || $lastChar == 'っ' || $lastChar == 'ち') {
					$char = GanaConversion::$taRow[$firstChar] . mb_substr($char, 1, null, 'UTF-8');
				}
				if ($i > 0) {
					$chars[$i - 1] = preg_replace('/(?<=.)[\x{3064}\x{3061}]$/u', 'っ', $chars[$i - 1], -1);
				}
			}
		}

		return $precedingGana . implode('', $chars);
	}
}
