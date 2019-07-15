<?php
// This file is generated by build/generateAutoload.php, do not adjust manually
// phpcs:disable Generic.Files.LineLength
global $wgAutoloadClasses;

$wgAutoloadClasses += [
	'DataValues\\DataValueFactory' => __DIR__ . '/includes/DataValueFactory.php',
	'Wikibase\\ByIdDispatchingItemTermStore' => __DIR__ . '/includes/Store/ByIdDispatchingItemTermStore.php',
	'Wikibase\\Change' => __DIR__ . '/includes/Changes/Change.php',
	'Wikibase\\ChangeRow' => __DIR__ . '/includes/Changes/ChangeRow.php',
	'Wikibase\\DiffChange' => __DIR__ . '/includes/Changes/DiffChange.php',
	'Wikibase\\EntityChange' => __DIR__ . '/includes/Changes/EntityChange.php',
	'Wikibase\\EntityFactory' => __DIR__ . '/includes/EntityFactory.php',
	'Wikibase\\ItemChange' => __DIR__ . '/includes/Changes/ItemChange.php',
	'Wikibase\\LanguageFallbackChain' => __DIR__ . '/includes/LanguageFallbackChain.php',
	'Wikibase\\LanguageFallbackChainFactory' => __DIR__ . '/includes/LanguageFallbackChainFactory.php',
	'Wikibase\\LanguageWithConversion' => __DIR__ . '/includes/LanguageWithConversion.php',
	'Wikibase\\LibHooks' => __DIR__ . '/LibHooks.php',
	'Wikibase\\Lib\\Changes\\CentralIdLookupFactory' => __DIR__ . '/includes/Changes/CentralIdLookupFactory.php',
	'Wikibase\\Lib\\Changes\\EntityChangeFactory' => __DIR__ . '/includes/Changes/EntityChangeFactory.php',
	'Wikibase\\Lib\\Changes\\EntityDiffChangedAspects' => __DIR__ . '/includes/Changes/EntityDiffChangedAspects.php',
	'Wikibase\\Lib\\Changes\\EntityDiffChangedAspectsFactory' => __DIR__ . '/includes/Changes/EntityDiffChangedAspectsFactory.php',
	'Wikibase\\Lib\\ContentLanguages' => __DIR__ . '/includes/ContentLanguages.php',
	'Wikibase\\Lib\\DataType' => __DIR__ . '/includes/DataType.php',
	'Wikibase\\Lib\\DataTypeDefinitions' => __DIR__ . '/includes/DataTypeDefinitions.php',
	'Wikibase\\Lib\\DataTypeFactory' => __DIR__ . '/includes/DataTypeFactory.php',
	'Wikibase\\Lib\\DataValue\\UnmappedEntityIdValue' => __DIR__ . '/includes/DataValue/UnmappedEntityIdValue.php',
	'Wikibase\\Lib\\DifferenceContentLanguages' => __DIR__ . '/includes/DifferenceContentLanguages.php',
	'Wikibase\\Lib\\EntityTypeDefinitions' => __DIR__ . '/includes/EntityTypeDefinitions.php',
	'Wikibase\\Lib\\FormatableSummary' => __DIR__ . '/includes/FormatableSummary.php',
	'Wikibase\\Lib\\Formatters\\AutoCommentFormatter' => __DIR__ . '/includes/Formatters/AutoCommentFormatter.php',
	'Wikibase\\Lib\\Formatters\\BinaryOptionDispatchingSnakFormatter' => __DIR__ . '/includes/Formatters/BinaryOptionDispatchingSnakFormatter.php',
	'Wikibase\\Lib\\Formatters\\CachingKartographerEmbeddingHandler' => __DIR__ . '/includes/Formatters/CachingKartographerEmbeddingHandler.php',
	'Wikibase\\Lib\\Formatters\\CommonsInlineImageFormatter' => __DIR__ . '/includes/Formatters/CommonsInlineImageFormatter.php',
	'Wikibase\\Lib\\Formatters\\CommonsLinkFormatter' => __DIR__ . '/includes/Formatters/CommonsLinkFormatter.php',
	'Wikibase\\Lib\\Formatters\\CommonsThumbnailFormatter' => __DIR__ . '/includes/Formatters/CommonsThumbnailFormatter.php',
	'Wikibase\\Lib\\Formatters\\ControlledFallbackEntityIdFormatter' => __DIR__ . '/includes/Formatters/ControlledFallbackEntityIdFormatter.php',
	'Wikibase\\Lib\\Formatters\\DispatchingEntityIdHtmlLinkFormatter' => __DIR__ . '/includes/Formatters/DispatchingEntityIdHtmlLinkFormatter.php',
	'Wikibase\\Lib\\Formatters\\DispatchingSnakFormatter' => __DIR__ . '/includes/Formatters/DispatchingSnakFormatter.php',
	'Wikibase\\Lib\\Formatters\\DispatchingValueFormatter' => __DIR__ . '/includes/Formatters/DispatchingValueFormatter.php',
	'Wikibase\\Lib\\Formatters\\EntityIdLinkFormatter' => __DIR__ . '/includes/Formatters/EntityIdLinkFormatter.php',
	'Wikibase\\Lib\\Formatters\\EntityIdPlainLinkFormatter' => __DIR__ . '/includes/Formatters/EntityIdPlainLinkFormatter.php',
	'Wikibase\\Lib\\Formatters\\EntityIdSiteLinkFormatter' => __DIR__ . '/includes/Formatters/EntityIdSiteLinkFormatter.php',
	'Wikibase\\Lib\\Formatters\\EntityIdTitleFormatter' => __DIR__ . '/includes/Formatters/EntityIdTitleFormatter.php',
	'Wikibase\\Lib\\Formatters\\EntityIdValueFormatter' => __DIR__ . '/includes/Formatters/EntityIdValueFormatter.php',
	'Wikibase\\Lib\\Formatters\\ErrorHandlingSnakFormatter' => __DIR__ . '/includes/Formatters/ErrorHandlingSnakFormatter.php',
	'Wikibase\\Lib\\Formatters\\EscapingSnakFormatter' => __DIR__ . '/includes/Formatters/EscapingSnakFormatter.php',
	'Wikibase\\Lib\\Formatters\\EscapingValueFormatter' => __DIR__ . '/includes/Formatters/EscapingValueFormatter.php',
	'Wikibase\\Lib\\Formatters\\FormatterLabelDescriptionLookupFactory' => __DIR__ . '/includes/Formatters/FormatterLabelDescriptionLookupFactory.php',
	'Wikibase\\Lib\\Formatters\\GlobeCoordinateDetailsFormatter' => __DIR__ . '/includes/Formatters/GlobeCoordinateDetailsFormatter.php',
	'Wikibase\\Lib\\Formatters\\GlobeCoordinateInlineWikitextKartographerFormatter' => __DIR__ . '/includes/Formatters/GlobeCoordinateInlineWikitextKartographerFormatter.php',
	'Wikibase\\Lib\\Formatters\\GlobeCoordinateKartographerFormatter' => __DIR__ . '/includes/Formatters/GlobeCoordinateKartographerFormatter.php',
	'Wikibase\\Lib\\Formatters\\HtmlExternalIdentifierFormatter' => __DIR__ . '/includes/Formatters/HtmlExternalIdentifierFormatter.php',
	'Wikibase\\Lib\\Formatters\\HtmlTimeFormatter' => __DIR__ . '/includes/Formatters/HtmlTimeFormatter.php',
	'Wikibase\\Lib\\Formatters\\HtmlUrlFormatter' => __DIR__ . '/includes/Formatters/HtmlUrlFormatter.php',
	'Wikibase\\Lib\\Formatters\\InterWikiLinkHtmlFormatter' => __DIR__ . '/includes/Formatters/InterWikiLinkHtmlFormatter.php',
	'Wikibase\\Lib\\Formatters\\InterWikiLinkWikitextFormatter' => __DIR__ . '/includes/Formatters/InterWikiLinkWikitextFormatter.php',
	'Wikibase\\Lib\\Formatters\\ItemPropertyIdHtmlLinkFormatter' => __DIR__ . '/includes/Formatters/ItemPropertyIdHtmlLinkFormatter.php',
	'Wikibase\\Lib\\Formatters\\LabelsProviderEntityIdHtmlLinkFormatter' => __DIR__ . '/includes/Formatters/LabelsProviderEntityIdHtmlLinkFormatter.php',
	'Wikibase\\Lib\\Formatters\\MediaWikiNumberLocalizer' => __DIR__ . '/includes/Formatters/MediaWikiNumberLocalizer.php',
	'Wikibase\\Lib\\Formatters\\MessageSnakFormatter' => __DIR__ . '/includes/Formatters/MessageSnakFormatter.php',
	'Wikibase\\Lib\\Formatters\\MonolingualHtmlFormatter' => __DIR__ . '/includes/Formatters/MonolingualHtmlFormatter.php',
	'Wikibase\\Lib\\Formatters\\MonolingualTextFormatter' => __DIR__ . '/includes/Formatters/MonolingualTextFormatter.php',
	'Wikibase\\Lib\\Formatters\\MonolingualWikitextFormatter' => __DIR__ . '/includes/Formatters/MonolingualWikitextFormatter.php',
	'Wikibase\\Lib\\Formatters\\MwTimeIsoFormatter' => __DIR__ . '/includes/Formatters/MwTimeIsoFormatter.php',
	'Wikibase\\Lib\\Formatters\\NonExistingEntityIdHtmlFormatter' => __DIR__ . '/includes/Formatters/NonExistingEntityIdHtmlFormatter.php',
	'Wikibase\\Lib\\Formatters\\OutputFormatSnakFormatterFactory' => __DIR__ . '/includes/Formatters/OutputFormatSnakFormatterFactory.php',
	'Wikibase\\Lib\\Formatters\\OutputFormatValueFormatterFactory' => __DIR__ . '/includes/Formatters/OutputFormatValueFormatterFactory.php',
	'Wikibase\\Lib\\Formatters\\PropertyValueSnakFormatter' => __DIR__ . '/includes/Formatters/PropertyValueSnakFormatter.php',
	'Wikibase\\Lib\\Formatters\\QuantityDetailsFormatter' => __DIR__ . '/includes/Formatters/QuantityDetailsFormatter.php',
	'Wikibase\\Lib\\Formatters\\SnakFormat' => __DIR__ . '/includes/Formatters/SnakFormat.php',
	'Wikibase\\Lib\\Formatters\\SnakFormatter' => __DIR__ . '/includes/Formatters/SnakFormatter.php',
	'Wikibase\\Lib\\Formatters\\TimeDetailsFormatter' => __DIR__ . '/includes/Formatters/TimeDetailsFormatter.php',
	'Wikibase\\Lib\\Formatters\\TypedValueFormatter' => __DIR__ . '/includes/Formatters/TypedValueFormatter.php',
	'Wikibase\\Lib\\Formatters\\UnDeserializableValueFormatter' => __DIR__ . '/includes/Formatters/UnDeserializableValueFormatter.php',
	'Wikibase\\Lib\\Formatters\\UnknownTypeEntityIdHtmlLinkFormatter' => __DIR__ . '/includes/Formatters/UnknownTypeEntityIdHtmlLinkFormatter.php',
	'Wikibase\\Lib\\Formatters\\UnmappedEntityIdValueFormatter' => __DIR__ . '/includes/Formatters/UnmappedEntityIdValueFormatter.php',
	'Wikibase\\Lib\\Formatters\\VocabularyUriFormatter' => __DIR__ . '/includes/Formatters/VocabularyUriFormatter.php',
	'Wikibase\\Lib\\Formatters\\WikibaseSnakFormatterBuilders' => __DIR__ . '/includes/Formatters/WikibaseSnakFormatterBuilders.php',
	'Wikibase\\Lib\\Formatters\\WikibaseValueFormatterBuilders' => __DIR__ . '/includes/Formatters/WikibaseValueFormatterBuilders.php',
	'Wikibase\\Lib\\Formatters\\WikitextExternalIdentifierFormatter' => __DIR__ . '/includes/Formatters/WikitextExternalIdentifierFormatter.php',
	'Wikibase\\Lib\\LanguageFallbackIndicator' => __DIR__ . '/includes/LanguageFallbackIndicator.php',
	'Wikibase\\Lib\\LanguageNameLookup' => __DIR__ . '/includes/LanguageNameLookup.php',
	'Wikibase\\Lib\\MediaWikiContentLanguages' => __DIR__ . '/includes/MediaWikiContentLanguages.php',
	'Wikibase\\Lib\\MessageException' => __DIR__ . '/includes/MessageException.php',
	'Wikibase\\Lib\\Modules\\DataTypesModule' => __DIR__ . '/includes/Modules/DataTypesModule.php',
	'Wikibase\\Lib\\Modules\\EntityTypesConfigValueProvider' => __DIR__ . '/includes/Modules/EntityTypesConfigValueProvider.php',
	'Wikibase\\Lib\\Modules\\MediaWikiConfigModule' => __DIR__ . '/includes/Modules/MediaWikiConfigModule.php',
	'Wikibase\\Lib\\Modules\\MediaWikiConfigValueProvider' => __DIR__ . '/includes/Modules/MediaWikiConfigValueProvider.php',
	'Wikibase\\Lib\\Modules\\PropertyValueExpertsModule' => __DIR__ . '/includes/Modules/PropertyValueExpertsModule.php',
	'Wikibase\\Lib\\Modules\\SettingsValueProvider' => __DIR__ . '/includes/Modules/SettingsValueProvider.php',
	'Wikibase\\Lib\\PropertyInfoDataTypeLookup' => __DIR__ . '/includes/PropertyInfoDataTypeLookup.php',
	'Wikibase\\Lib\\PropertyInfoSnakUrlExpander' => __DIR__ . '/includes/PropertyInfoSnakUrlExpander.php',
	'Wikibase\\Lib\\Reporting\\ExceptionHandler' => __DIR__ . '/includes/Reporting/ExceptionHandler.php',
	'Wikibase\\Lib\\Reporting\\LogWarningExceptionHandler' => __DIR__ . '/includes/Reporting/LogWarningExceptionHandler.php',
	'Wikibase\\Lib\\Reporting\\ReportingExceptionHandler' => __DIR__ . '/includes/Reporting/ReportingExceptionHandler.php',
	'Wikibase\\Lib\\Reporting\\RethrowingExceptionHandler' => __DIR__ . '/includes/Reporting/RethrowingExceptionHandler.php',
	'Wikibase\\Lib\\RepositoryDefinitions' => __DIR__ . '/includes/RepositoryDefinitions.php',
	'Wikibase\\Lib\\Serialization\\CallbackFactory' => __DIR__ . '/includes/Serialization/CallbackFactory.php',
	'Wikibase\\Lib\\Serialization\\RepositorySpecificDataValueDeserializerFactory' => __DIR__ . '/includes/Serialization/RepositorySpecificDataValueDeserializerFactory.php',
	'Wikibase\\Lib\\Serialization\\SerializationModifier' => __DIR__ . '/includes/Serialization/SerializationModifier.php',
	'Wikibase\\Lib\\SimpleCacheWithBagOStuff' => __DIR__ . '/includes/SimpleCacheWithBagOStuff.php',
	'Wikibase\\Lib\\Sites\\SiteMatrixParser' => __DIR__ . '/includes/Sites/SiteMatrixParser.php',
	'Wikibase\\Lib\\Sites\\SitesBuilder' => __DIR__ . '/includes/Sites/SitesBuilder.php',
	'Wikibase\\Lib\\SnakUrlExpander' => __DIR__ . '/includes/SnakUrlExpander.php',
	'Wikibase\\Lib\\StaticContentLanguages' => __DIR__ . '/includes/StaticContentLanguages.php',
	'Wikibase\\Lib\\StatsdMissRecordingSimpleCache' => __DIR__ . '/includes/StatsdMissRecordingSimpleCache.php',
	'Wikibase\\Lib\\Store\\AbstractTermPropertyLabelResolver' => __DIR__ . '/includes/Store/AbstractTermPropertyLabelResolver.php',
	'Wikibase\\Lib\\Store\\BadRevisionException' => __DIR__ . '/includes/Store/BadRevisionException.php',
	'Wikibase\\Lib\\Store\\CacheAwarePropertyInfoStore' => __DIR__ . '/includes/Store/CacheAwarePropertyInfoStore.php',
	'Wikibase\\Lib\\Store\\CacheRetrievingEntityRevisionLookup' => __DIR__ . '/includes/Store/CacheRetrievingEntityRevisionLookup.php',
	'Wikibase\\Lib\\Store\\CachingEntityRevisionLookup' => __DIR__ . '/includes/Store/CachingEntityRevisionLookup.php',
	'Wikibase\\Lib\\Store\\CachingFallbackLabelDescriptionLookup' => __DIR__ . '/includes/Store/CachingFallbackLabelDescriptionLookup.php',
	'Wikibase\\Lib\\Store\\CachingPropertyInfoLookup' => __DIR__ . '/includes/Store/CachingPropertyInfoLookup.php',
	'Wikibase\\Lib\\Store\\CachingPropertyOrderProvider' => __DIR__ . '/includes/Store/CachingPropertyOrderProvider.php',
	'Wikibase\\Lib\\Store\\CachingSiteLinkLookup' => __DIR__ . '/includes/Store/CachingSiteLinkLookup.php',
	'Wikibase\\Lib\\Store\\ChunkAccess' => __DIR__ . '/includes/Store/ChunkAccess.php',
	'Wikibase\\Lib\\Store\\ChunkCache' => __DIR__ . '/includes/Store/ChunkCache.php',
	'Wikibase\\Lib\\Store\\DelegatingEntityTermStoreWriter' => __DIR__ . '/includes/Store/DelegatingEntityTermStoreWriter.php',
	'Wikibase\\Lib\\Store\\DispatchingEntityInfoBuilder' => __DIR__ . '/includes/Store/DispatchingEntityInfoBuilder.php',
	'Wikibase\\Lib\\Store\\DispatchingEntityPrefetcher' => __DIR__ . '/includes/Store/DispatchingEntityPrefetcher.php',
	'Wikibase\\Lib\\Store\\DispatchingEntityRevisionLookup' => __DIR__ . '/includes/Store/DispatchingEntityRevisionLookup.php',
	'Wikibase\\Lib\\Store\\DispatchingPropertyInfoLookup' => __DIR__ . '/includes/Store/DispatchingPropertyInfoLookup.php',
	'Wikibase\\Lib\\Store\\DispatchingTermBuffer' => __DIR__ . '/includes/Store/DispatchingTermBuffer.php',
	'Wikibase\\Lib\\Store\\ElasticTermLookup' => __DIR__ . '/includes/Store/Elastic/ElasticTermLookup.php',
	'Wikibase\\Lib\\Store\\EntityByLinkedTitleLookup' => __DIR__ . '/includes/Store/EntityByLinkedTitleLookup.php',
	'Wikibase\\Lib\\Store\\EntityContentDataCodec' => __DIR__ . '/includes/Store/EntityContentDataCodec.php',
	'Wikibase\\Lib\\Store\\EntityContentTooBigException' => __DIR__ . '/includes/Store/EntityContentTooBigException.php',
	'Wikibase\\Lib\\Store\\EntityInfo' => __DIR__ . '/includes/Store/EntityInfo.php',
	'Wikibase\\Lib\\Store\\EntityInfoBuilder' => __DIR__ . '/includes/Store/EntityInfoBuilder.php',
	'Wikibase\\Lib\\Store\\EntityInfoTermLookup' => __DIR__ . '/includes/Store/EntityInfoTermLookup.php',
	'Wikibase\\Lib\\Store\\EntityNamespaceLookup' => __DIR__ . '/includes/Store/EntityNamespaceLookup.php',
	'Wikibase\\Lib\\Store\\EntityRevision' => __DIR__ . '/includes/Store/EntityRevision.php',
	'Wikibase\\Lib\\Store\\EntityRevisionCache' => __DIR__ . '/includes/Store/EntityRevisionCache.php',
	'Wikibase\\Lib\\Store\\EntityRevisionLookup' => __DIR__ . '/includes/Store/EntityRevisionLookup.php',
	'Wikibase\\Lib\\Store\\EntityStore' => __DIR__ . '/includes/Store/EntityStore.php',
	'Wikibase\\Lib\\Store\\EntityStoreWatcher' => __DIR__ . '/includes/Store/EntityStoreWatcher.php',
	'Wikibase\\Lib\\Store\\EntityTermLookup' => __DIR__ . '/includes/Store/EntityTermLookup.php',
	'Wikibase\\Lib\\Store\\EntityTermLookupBase' => __DIR__ . '/includes/Store/EntityTermLookupBase.php',
	'Wikibase\\Lib\\Store\\EntityTermStoreWriter' => __DIR__ . '/includes/Store/EntityTermStoreWriter.php',
	'Wikibase\\Lib\\Store\\EntityTitleLookup' => __DIR__ . '/includes/Store/EntityTitleLookup.php',
	'Wikibase\\Lib\\Store\\FallbackLabelDescriptionLookup' => __DIR__ . '/includes/Store/FallbackLabelDescriptionLookup.php',
	'Wikibase\\Lib\\Store\\FallbackPropertyOrderProvider' => __DIR__ . '/includes/Store/FallbackPropertyOrderProvider.php',
	'Wikibase\\Lib\\Store\\FieldPropertyInfoProvider' => __DIR__ . '/includes/Store/FieldPropertyInfoProvider.php',
	'Wikibase\\Lib\\Store\\HashSiteLinkStore' => __DIR__ . '/includes/Store/HashSiteLinkStore.php',
	'Wikibase\\Lib\\Store\\HttpUrlPropertyOrderProvider' => __DIR__ . '/includes/Store/HttpUrlPropertyOrderProvider.php',
	'Wikibase\\Lib\\Store\\LabelConflictFinder' => __DIR__ . '/includes/Store/LabelConflictFinder.php',
	'Wikibase\\Lib\\Store\\LanguageFallbackLabelDescriptionLookup' => __DIR__ . '/includes/Store/LanguageFallbackLabelDescriptionLookup.php',
	'Wikibase\\Lib\\Store\\LanguageFallbackLabelDescriptionLookupFactory' => __DIR__ . '/includes/Store/LanguageFallbackLabelDescriptionLookupFactory.php',
	'Wikibase\\Lib\\Store\\LatestRevisionIdResult' => __DIR__ . '/includes/Store/LatestRevisionIdResult.php',
	'Wikibase\\Lib\\Store\\MultiTermStoreWriter' => __DIR__ . '/includes/Store/MultiTermStoreWriter.php',
	'Wikibase\\Lib\\Store\\PrefetchingTermLookup' => __DIR__ . '/includes/Store/PrefetchingTermLookup.php',
	'Wikibase\\Lib\\Store\\PropertyInfoLookup' => __DIR__ . '/includes/Store/PropertyInfoLookup.php',
	'Wikibase\\Lib\\Store\\PropertyInfoProvider' => __DIR__ . '/includes/Store/PropertyInfoProvider.php',
	'Wikibase\\Lib\\Store\\PropertyInfoStore' => __DIR__ . '/includes/Store/PropertyInfoStore.php',
	'Wikibase\\Lib\\Store\\PropertyOrderProvider' => __DIR__ . '/includes/Store/PropertyOrderProvider.php',
	'Wikibase\\Lib\\Store\\PropertyOrderProviderException' => __DIR__ . '/includes/Store/PropertyOrderProviderException.php',
	'Wikibase\\Lib\\Store\\RedirectRevision' => __DIR__ . '/includes/Store/RedirectRevision.php',
	'Wikibase\\Lib\\Store\\RevisionBasedEntityLookup' => __DIR__ . '/includes/Store/RevisionBasedEntityLookup.php',
	'Wikibase\\Lib\\Store\\RevisionedUnresolvedRedirectException' => __DIR__ . '/includes/Store/RevisionedUnresolvedRedirectException.php',
	'Wikibase\\Lib\\Store\\SiteLinkLookup' => __DIR__ . '/includes/Store/SiteLinkLookup.php',
	'Wikibase\\Lib\\Store\\SiteLinkStore' => __DIR__ . '/includes/Store/SiteLinkStore.php',
	'Wikibase\\Lib\\Store\\StorageException' => __DIR__ . '/includes/Store/StorageException.php',
	'Wikibase\\Lib\\Store\\TermIndexPropertyLabelResolver' => __DIR__ . '/includes/Store/TermIndexPropertyLabelResolver.php',
	'Wikibase\\Lib\\Store\\TermIndexSearchCriteria' => __DIR__ . '/includes/Store/TermIndexSearchCriteria.php',
	'Wikibase\\Lib\\Store\\TermLookupSearcher' => __DIR__ . '/includes/Store/Elastic/TermLookupSearcher.php',
	'Wikibase\\Lib\\Store\\TypeDispatchingEntityRevisionLookup' => __DIR__ . '/includes/Store/TypeDispatchingEntityRevisionLookup.php',
	'Wikibase\\Lib\\Store\\TypeDispatchingEntityStore' => __DIR__ . '/includes/Store/TypeDispatchingEntityStore.php',
	'Wikibase\\Lib\\Store\\WikiPagePropertyOrderProvider' => __DIR__ . '/includes/Store/WikiPagePropertyOrderProvider.php',
	'Wikibase\\Lib\\Store\\WikiTextPropertyOrderProvider' => __DIR__ . '/includes/Store/WikiTextPropertyOrderProvider.php',
	'Wikibase\\Lib\\Tests\\Changes\\ChangeRowTest' => __DIR__ . '/tests/phpunit/Changes/ChangeRowTest.php',
	'Wikibase\\Lib\\Tests\\Changes\\EntityChangeTest' => __DIR__ . '/tests/phpunit/Changes/EntityChangeTest.php',
	'Wikibase\\Lib\\Tests\\Changes\\MockRepoClientCentralIdLookup' => __DIR__ . '/tests/phpunit/Changes/MockRepoClientCentralIdLookup.php',
	'Wikibase\\Lib\\Tests\\Changes\\TestChanges' => __DIR__ . '/tests/phpunit/Changes/TestChanges.php',
	'Wikibase\\Lib\\Tests\\EntityRevisionLookupTestCase' => __DIR__ . '/tests/phpunit/EntityRevisionLookupTestCase.php',
	'Wikibase\\Lib\\Tests\\MockPropertyLabelResolver' => __DIR__ . '/tests/phpunit/MockPropertyLabelResolver.php',
	'Wikibase\\Lib\\Tests\\MockRepository' => __DIR__ . '/tests/phpunit/MockRepository.php',
	'Wikibase\\Lib\\Tests\\Store\\EntityInfoBuilderTestCase' => __DIR__ . '/tests/phpunit/Store/EntityInfoBuilderTestCase.php',
	'Wikibase\\Lib\\Tests\\Store\\EntityTermLookupTest' => __DIR__ . '/tests/phpunit/Store/EntityTermLookupTest.php',
	'Wikibase\\Lib\\Tests\\Store\\GenericEntityInfoBuilder' => __DIR__ . '/tests/phpunit/Store/GenericEntityInfoBuilder.php',
	'Wikibase\\Lib\\Tests\\Store\\HttpUrlPropertyOrderProviderTestMockHttp' => __DIR__ . '/tests/phpunit/Store/HttpUrlPropertyOrderProviderTestMockHttp.php',
	'Wikibase\\Lib\\Tests\\Store\\MockChunkAccess' => __DIR__ . '/tests/phpunit/Store/MockChunkAccess.php',
	'Wikibase\\Lib\\Tests\\Store\\MockPropertyInfoLookup' => __DIR__ . '/tests/phpunit/Store/MockPropertyInfoLookup.php',
	'Wikibase\\Lib\\Tests\\Store\\MockTermIndex' => __DIR__ . '/tests/phpunit/Store/MockTermIndex.php',
	'Wikibase\\Lib\\Tests\\Store\\Sql\\Terms\\Util\\FakeLBFactory' => __DIR__ . '/tests/phpunit/Store/Sql/Terms/Util/FakeLBFactory.php',
	'Wikibase\\Lib\\Tests\\Store\\Sql\\Terms\\Util\\FakeLoadBalancer' => __DIR__ . '/tests/phpunit/Store/Sql/Terms/Util/FakeLoadBalancer.php',
	'Wikibase\\Lib\\Tests\\Store\\WikiTextPropertyOrderProviderTestHelper' => __DIR__ . '/tests/phpunit/Store/WikiTextPropertyOrderProviderTestHelper.php',
	'Wikibase\\Lib\\UnionContentLanguages' => __DIR__ . '/includes/UnionContentLanguages.php',
	'Wikibase\\Lib\\UserInputException' => __DIR__ . '/includes/UserInputException.php',
	'Wikibase\\Lib\\UserLanguageLookup' => __DIR__ . '/includes/UserLanguageLookup.php',
	'Wikibase\\Lib\\WikibaseContentLanguages' => __DIR__ . '/includes/WikibaseContentLanguages.php',
	'Wikibase\\MultiItemTermStore' => __DIR__ . '/includes/Store/MultiItemTermStore.php',
	'Wikibase\\MultiPropertyTermStore' => __DIR__ . '/includes/Store/MultiPropertyTermStore.php',
	'Wikibase\\PopulateSitesTable' => __DIR__ . '/maintenance/populateSitesTable.php',
	'Wikibase\\RepoAccessModule' => __DIR__ . '/includes/Modules/RepoAccessModule.php',
	'Wikibase\\Settings' => __DIR__ . '/includes/Settings.php',
	'Wikibase\\SettingsArray' => __DIR__ . '/includes/SettingsArray.php',
	'Wikibase\\SitesModule' => __DIR__ . '/includes/Modules/SitesModule.php',
	'Wikibase\\Store\\BufferingTermLookup' => __DIR__ . '/includes/Store/BufferingTermLookup.php',
	'Wikibase\\Store\\EntityIdLookup' => __DIR__ . '/includes/Store/EntityIdLookup.php',
	'Wikibase\\StringNormalizer' => __DIR__ . '/includes/StringNormalizer.php',
	'Wikibase\\Summary' => __DIR__ . '/includes/Summary.php',
	'Wikibase\\TermIndex' => __DIR__ . '/includes/Store/TermIndex.php',
	'Wikibase\\TermIndexEntry' => __DIR__ . '/includes/TermIndexEntry.php',
	'Wikibase\\TermIndexItemTermStore' => __DIR__ . '/includes/Store/TermIndexItemTermStore.php',
	'Wikibase\\TermIndexPropertyTermStore' => __DIR__ . '/includes/Store/TermIndexPropertyTermStore.php',
	'Wikibase\\WikibaseSettings' => __DIR__ . '/includes/WikibaseSettings.php',
];
