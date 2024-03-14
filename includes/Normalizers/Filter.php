<?php

namespace MediaWiki\Extension\TextTransform\Normalizers;

interface Filter
{
	/**
	 * Normalizes and categorize the input text
	 * 
	 * @param string &$input
	 * @return bool Is matched
	 */
	public static function filter(string &$input): bool;
}
