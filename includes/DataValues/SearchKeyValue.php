<?php

namespace MediaWiki\Extension\TextTransform\DataValues;

use MediaWiki\MediaWikiServices;
use MediaWiki\Extension\TextTransform\Normalizers\TextNormalizer;
use SMWDIBlob as DIBlob;
use SMW\DataValues\StringValue;

class SearchKeyValue extends StringValue
{
	/**
	 * DV text identifier
	 */
	const TYPE_ID = '_shk';

	/**
	 * DV identifier
	 */
	const TYPE_LEGACY_ID = '_shk';

	/**
	 * @see DataValue::parseUserValue
	 *
	 * {@inheritDoc}
	 */
	protected function parseUserValue($value)
	{
		$value = preg_replace('/\\s+/', ' ', trim($value));

		if ($value === '') {
			$this->addErrorMsg('smw_emptystring');
		}

		/** @var TextNormalizer */
		$textNormalizer = MediaWikiServices::getInstance()->get('TextTransform.TextNormalizer');

		$key = preg_replace('/\\s*\\*\\s*/', '*', $textNormalizer->normalize($value, ' '));
		if ($key === '') {
			$key = $value;
		}

		$this->m_dataitem = new DIBlob($key);
	}
}
