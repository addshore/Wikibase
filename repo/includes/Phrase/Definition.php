<?php

namespace Wikibase\Repo\Phrase;

use Wikibase\Lib\EntityTypeDefinitions as Def;
use Wikibase\Repo\WikibaseRepo;

return [
	Def::CONTENT_MODEL_ID => PhraseContent::ID,
	Def::CONTENT_HANDLER_FACTORY_CALLBACK => function() {
		$services = \MediaWiki\MediaWikiServices::getInstance();
		return new PhraseContentHandler(
			PhraseContent::ID,
			null, // unused
			WikibaseRepo::getEntityContentDataCodec( $services ),
			WikibaseRepo::getEntityConstraintProvider( $services ),
			WikibaseRepo::getValidatorErrorLocalizer( $services ),
			WikibaseRepo::getEntityIdParser( $services ),
			WikibaseRepo::getFieldDefinitionsFactory( $services )
			->getFieldDefinitionsByType( PhraseDocument::TYPE ),
			null
		);
	},
	Def::STORAGE_SERIALIZER_FACTORY_CALLBACK => function( \Wikibase\DataModel\Serializers\SerializerFactory $serializerFactory ) {
		return new PhraseSerailizer();
	},
	Def::DESERIALIZER_FACTORY_CALLBACK => static function ( \Wikibase\DataModel\Deserializers\DeserializerFactory $deserializerFactory ) {
		return new PhraseDeserializer();
	},
	Def::SERIALIZER_FACTORY_CALLBACK => static function ( \Wikibase\DataModel\Serializers\SerializerFactory $serializerFactory ) {
		return new PhraseSerailizer();
	},
	Def::ENTITY_DIFFER_STRATEGY_BUILDER => static function () {
		return new PhraseDiffer();
	},
	Def::VIEW_FACTORY_CALLBACK => function(
		\Language $language,
		\Wikibase\Lib\TermLanguageFallbackChain $fallbackChain,
		\Wikibase\DataModel\Entity\EntityDocument $entity
	) {
		return new PhraseView();
	},
	Def::ENTITY_ID_PATTERN => '/^PH[0-9A-Z]+/',
	Def::ENTITY_ID_BUILDER => static function ( $serialization ) {
		return new PhraseId( $serialization );
	},
	Def::PREFETCHING_TERM_LOOKUP_CALLBACK => static function () {
		return new \Wikibase\DataAccess\NullPrefetchingTermLookup();
	},
	Def::URL_LOOKUP_CALLBACK => static function () {
		return new \Wikibase\Lib\Store\TitleLookupBasedEntityUrlLookup( WikibaseRepo::getEntityTitleLookup() );
	},
	Def::EXISTENCE_CHECKER_CALLBACK => static function () {
		$services = \MediaWiki\MediaWikiServices::getInstance();
		return new \Wikibase\Lib\Store\TitleLookupBasedEntityExistenceChecker(
			WikibaseRepo::getEntityTitleLookup( $services ),
			$services->getLinkBatchFactory()
		);
	},
	Def::TITLE_TEXT_LOOKUP_CALLBACK => function () {
		return new \Wikibase\Lib\Store\TitleLookupBasedEntityTitleTextLookup(
			WikibaseRepo::getEntityTitleLookup()
		);
	},
];