<?php

namespace MediaWiki\Extension\TextTransform\Normalizers;

class TextNormalizer
{
	/**
	 * @param string $input
	 * @param ?string $separator
	 * @return string
	 */
	public function normalize(string $input, ?string $separator = null): string
	{
		$input = trim($input);
		$output = $this->internalNormalize($input);

		return $this->respace($output, $separator);
	}

	/**
	 * @param string $input
	 * @param ?string $separator
	 * @return string
	 */
	public function respace(string $input, ?string $separator = null): string
	{
		if ($separator === null || $separator === ' ') {
			return $input;
		}

		return str_replace(' ', $separator, $input);
	}

	/**
	 * @param string $input
	 * @return string
	 */
	protected function internalNormalize(string $input): string
	{
		$input = HtmlEntityNormalizer::normalize($input);
		$input = SpaceNormalizer::normalize($input);
		$input = WidthNormalizer::normalize($input);

		$chars = mb_str_split($input);
		$output = '';
		$hasGana = false;
		$type = SpaceFilter::TYPE;
		$lastType = SpaceFilter::TYPE;
		foreach ($chars as $char) {
			$type = $this->filterChar($char);
			if ($type !== SpaceFilter::TYPE) {
				if ($type === GanaFilter::TYPE) {
					$hasGana = true;
				}
				$output .= ($type === $lastType ? '' : ' ') . $char;
			}
			$lastType = $type;
		}

		if ($hasGana) {
			$output = GanaNormalizer::normalize($output);
		}

		return trim($output);
	}

	/**
	 * @param string &$char
	 * @return int Type
	 */
	protected function filterChar(&$char)
	{
		switch (true) {
			case SpaceFilter::filter($char):
				return SpaceFilter::TYPE;
			case NumberFilter::filter($char):
				return NumberFilter::TYPE;
			case LatinFilter::filter($char):
				return LatinFilter::TYPE;
			case GanaFilter::filter($char):
				return GanaFilter::TYPE;
			case HanziFilter::filter($char):
				return HanziFilter::TYPE;
			case RomanFilter::filter($char):
				return RomanFilter::TYPE;
		}
		return 0;
	}

	protected function isAscii(string $text): bool
	{
		return strlen($text) === mb_strlen($text);
	}
}
