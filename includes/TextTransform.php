<?php

namespace MediaWiki\Extension\TextTransform;

use MediaWiki\MediaWikiServices;
use MediaWiki\Extension\TextTransform\Normalizers\TextNormalizer;
use MediaWiki\Extension\TextTransform\Normalizers\GanaNormalizer;
use MediaWiki\Extension\TextTransform\Normalizers\KanjiNormalizer;
use Parser;

/**
 * TextTransform parser function handlers
 */
class TextTransform
{
	/**
	 * Normalizes the input text.
	 * 
	 * @param Parser $parser
	 * @param string $text
	 * @param ?string $sep
	 * @return string
	 */
	public static function simplify($parser, $text, $sep = null)
	{
		/** @var TextNormalizer */
		$textNormalizer = MediaWikiServices::getInstance()->get('TextTransform.TextNormalizer');

		return implode("\n", array_map(function ($a) use ($sep, $textNormalizer) {
			return $textNormalizer->normalize($a, $sep);
		}, explode("\n", $text)));
	}

	/**
	 * Converts hiragana to romaji.
	 * 
	 * @param Parser $parser
	 * @param string $text
	 * @param ?string $noMatch
	 * @return string
	 */
	static function romaji($parser, $text, $noMatch = null)
	{
		$result = GanaNormalizer::romaji($text);
		if ($noMatch === null || $result !== $text) {
			return $result;
		}

		return trim($noMatch);
	}

	/**
	 * Converts kanji to hiragana.
	 * 
	 * @param Parser $parser
	 * @param string $text
	 * @param ?string $noMatch
	 * @return string
	 */
	static function hiragana($parser, $text, $noMatch = false)
	{
		$result = KanjiNormalizer::hiragana($text);
		if ($noMatch === null || $result !== $text) {
			return $result;
		}

		return trim($noMatch);
	}
}
