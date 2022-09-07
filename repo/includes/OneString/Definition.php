<?php

namespace Wikibase\Repo\OneString;

use Wikibase\Lib\EntityTypeDefinitions as Def;
use Wikibase\Repo\WikibaseRepo;
use Wikibase\DataModel\Serializers\SerializerFactory;
use Wikibase\Lib\Store\TitleLookupBasedEntityArticleIdLookup;
use Wikibase\Lib\Store\TitleLookupBasedEntityTitleTextLookup;
use \Language;
use Wikibase\Lib\TermLanguageFallbackChain;
use Wikibase\DataModel\Entity\EntityDocument;
use Wikibase\DataModel\Deserializers\DeserializerFactory;
use Wikibase\DataAccess\NullPrefetchingTermLookup;
use Wikibase\Lib\Store\TitleLookupBasedEntityUrlLookup;
use Wikibase\Lib\Store\TitleLookupBasedEntityExistenceChecker;

return [
	// Enables persistence of the entity type in MediaWiki.
	// Also:
	// - An interface to allow creation (SpecialPage)
	// - Integration of Namespace, SpecialPage into i18n of MediaWiki
	Def::CONTENT_MODEL_ID => OneStringConstants::CONTENT_ID,
	Def::CONTENT_HANDLER_FACTORY_CALLBACK => function() {
		// Implements EntityHandler
		$services = \MediaWiki\MediaWikiServices::getInstance();
		return new OneStringContentHandler(
			OneStringConstants::CONTENT_ID,
			null, // unused
			// These services from WikibaseRepo are all of the "dispatching" services, but we could also provide specific services here
			// but this dispatching services again mean the only thing we pass in here are the entity type and content ID..
			WikibaseRepo::getEntityContentDataCodec( $services ),
			WikibaseRepo::getEntityConstraintProvider( $services ),
			WikibaseRepo::getValidatorErrorLocalizer( $services ),
			WikibaseRepo::getEntityIdParser( $services ),
			WikibaseRepo::getFieldDefinitionsFactory( $services )
			->getFieldDefinitionsByType( OneStringConstants::ENTITY_TYPE ),
			null
		);
	},
	Def::STORAGE_SERIALIZER_FACTORY_CALLBACK => function( SerializerFactory $serializerFactory ) {
		// Implement DispatchableSerializer
		return new OneStringSerializer();
	},
	Def::ENTITY_DIFFER_STRATEGY_BUILDER => static function () {
		// Implements EntityDifferStrategy
		return new OneStringDiffer();
	},
	Def::ARTICLE_ID_LOOKUP_CALLBACK => static function () {
		// Implements EntityArticleIdLookup
		return new TitleLookupBasedEntityArticleIdLookup(
			WikibaseRepo::getEntityTitleLookup()
		);
	},
	Def::TITLE_TEXT_LOOKUP_CALLBACK => static function () {
		// Implements EntityTitleTextLookup
		return new TitleLookupBasedEntityTitleTextLookup(
			WikibaseRepo::getEntityTitleLookup()
		);
	},

	// Enables Wikibase to read back stored serialized entities into PHP objects
	Def::DESERIALIZER_FACTORY_CALLBACK => static function ( DeserializerFactory $deserializerFactory ) {
		return new OneStringDeserializer();
	},

	// Enables rendering of the entity type in MediaWiki HTML view
	Def::VIEW_FACTORY_CALLBACK => function(
		Language $language,
		TermLanguageFallbackChain $fallbackChain,
		EntityDocument $entity
	) {
		// Implements EntityDocumentView
		return new OneStringView();
	},
	// OutputPageEntityIdreader complains if there is not yet an ID parser...
	// This is again a dispatching sevrice, hence an ID pattern is required
	Def::ENTITY_ID_PATTERN => '/^[1-9a-z]+/i',
	Def::ENTITY_ID_BUILDER => static function ( $serialization ) {
		return new OneStringId( $serialization );
	},

	// Adding the view factor and ID builder seemed to break recent changes
	// We apparently need to provide these services to fix it...
	// After this point the titles in recent changes are instead rendered as the ID only
	Def::PREFETCHING_TERM_LOOKUP_CALLBACK => static function () {
		return new NullPrefetchingTermLookup();
	},
	Def::URL_LOOKUP_CALLBACK => static function () {
		return new TitleLookupBasedEntityUrlLookup( WikibaseRepo::getEntityTitleLookup() );
	},
	Def::EXISTENCE_CHECKER_CALLBACK => static function () {
		$services = \MediaWiki\MediaWikiServices::getInstance();
		return new TitleLookupBasedEntityExistenceChecker(
			WikibaseRepo::getEntityTitleLookup( $services ),
			$services->getLinkBatchFactory()
		);
	},

	// Now MediaWiki will format the links as we desire
	Def::LINK_FORMATTER_CALLBACK => static function ( Language $language ) {
		return new OneStringEntityLinkFormatter();
	},

	// Now wbsetlabel will be able to function for OneString entities
	Def::ENTITY_FACTORY_CALLBACK => static function () {
		return new OneString();
	},

	// We need a public serialization for things like wbgetentities and Special:EntityData to work
	Def::SERIALIZER_FACTORY_CALLBACK => static function ( SerializerFactory $serializerFactory ) {
		// Implement DispatchableSerializer
		// Just using the same one as the internal serializer for now... (As is done for items and properties right now)
		return new OneStringSerializer();
	},
];
