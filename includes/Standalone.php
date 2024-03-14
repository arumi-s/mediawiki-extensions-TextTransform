<?php

use MediaWiki\Extension\TextTransform\Normalizers\TextNormalizer;

spl_autoload_register(function ($name) {
	$path = preg_replace('#^MediaWiki\\\\Extension\\\\([^\\\\]+)\\\\#', '$1/includes/', $name, 1, $count);

	if ($count > 0) {
		include_once dirname(dirname(__DIR__)) . '/' . str_replace('\\', '/', $path) . '.php';
		return;
	}

	$path = preg_replace_callback('#^MediaWiki\\\\(.+)\\\\#', function ($match) {
		return strtolower($match[1]) . '/';
	}, $name, 1, $count);

	if ($count > 0) {
		include_once dirname(dirname(dirname(__DIR__))) . '/includes/' . str_replace('\\', '/', $path) . '.php';
		return;
	}
});

class Standalone
{
	/** @var TextNormalizer */
	private $textNormalizer;

	public function __construct()
	{
		$this->textNormalizer = new TextNormalizer();
	}

	/**
	 * @param string|string[] $text
	 * @param ?string $sep
	 * @return string|string[]
	 */
	public function simplify($text, $sep = null)
	{
		if (is_array($text)) {
			return array_filter(array_map(function ($a) use ($sep) {
				return $this->textNormalizer->normalize($a, $sep);
			}, $text));
		}

		return $this->textNormalizer->normalize($text, $sep);
	}
}

/**
 * @param string|string[] $text
 * @param ?string $sep
 * @return string|string[]
 */
function simplify($text, $sep = null)
{
	static $instance = null;

	if ($instance === null) {
		$instance = new Standalone();
	}

	return $instance->simplify($text, $sep);
}
