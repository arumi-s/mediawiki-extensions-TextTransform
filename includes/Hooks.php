<?php

namespace MediaWiki\Extension\TextTransform;

use MediaWiki\Extension\TextTransform\DataValues\SearchKeyValue;
use Parser;
use SMW\DataTypeRegistry;
use SMWDataItem;

class Hooks implements
	\MediaWiki\Hook\ParserFirstCallInitHook
{
	/**
	 * Registers our parser functions with a fresh parser.
	 *
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ParserFirstCallInit
	 *
	 * @param Parser $parser
	 */
	public function onParserFirstCallInit($parser)
	{
		$parser->setFunctionHook('simplify', [TextTransform::class, 'simplify']);
		$parser->setFunctionHook('romaji', [TextTransform::class, 'romaji']);
		$parser->setFunctionHook('hiragana', [TextTransform::class, 'hiragana']);
	}

	/**
	 * Registers out Semantic MediaWiki data types
	 */
	public function onSMW__DataType__initTypes()
	{
		DataTypeRegistry::getInstance()->registerDatatype(SearchKeyValue::TYPE_ID, SearchKeyValue::class, SMWDataItem::TYPE_BLOB, 'Search Key', false, true);
		DataTypeRegistry::getInstance()->registerDataTypeAlias(SearchKeyValue::TYPE_ID, '搜索');
	}
}
