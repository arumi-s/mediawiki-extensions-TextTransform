<?php

namespace MediaWiki\Extension\TextTransform\Normalizers;

interface Normalizer
{
	/**
	 * Normalizes the input text
	 * 
	 * @param string $input
	 * @return string
	 */
	public static function normalize(string $input): string;
}
