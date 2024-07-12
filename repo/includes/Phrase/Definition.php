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
];