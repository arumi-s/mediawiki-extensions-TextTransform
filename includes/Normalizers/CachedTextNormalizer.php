<?php

namespace MediaWiki\Extension\TextTransform\Normalizers;

use Config;
use WANObjectCache;

class CachedTextNormalizer extends TextNormalizer
{
	/** @var Config */
	private $config;

	/** @var WANObjectCache */
	private $cache;

	/**
	 * @param Config $config
	 * @param WANObjectCache $cache
	 */
	public function __construct(
		Config $config,
		WANObjectCache $cache
	) {
		$this->config = $config;
		$this->cache = $cache;
	}

	/**
	 * @param string $input
	 * @param ?string $separator
	 * @param bool $refresh
	 * @return string
	 */
	public function normalize(string $input, ?string $separator = null, $refresh = false): string
	{
		$input = trim($input);

		$key = $this->cache->makeKey('normalized', base64_encode($input));

		if ($refresh) {
			$output = $this->internalNormalize($input);
			$this->cache->delete($key);
		} else {
			$output = $this->cache->getWithSetCallback(
				$key,
				$this->cache::TTL_DAY * 7,
				function ($oldValue, &$ttl, &$setOpts) use ($input) {
					return $this->internalNormalize($input);
				}
			);
		}

		return $this->respace($output, $separator);
	}

	/**
	 * @param string $input
	 * @return bool
	 */
	public function refresh(string $input): bool
	{
		$input = trim($input);

		$key = $this->cache->makeKey('normalized', base64_encode($input));

		return $this->cache->delete($key);
	}
}
