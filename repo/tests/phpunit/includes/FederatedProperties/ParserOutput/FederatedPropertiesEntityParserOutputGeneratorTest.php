<?php

declare( strict_types = 1 );
namespace Wikibase\Repo\Tests\FederatedProperties\ParserOutput;

use Language;
use MediaWikiIntegrationTestCase;
use Wikibase\DataModel\Entity\EntityId;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Snak\PropertyNoValueSnak;
use Wikibase\DataModel\Statement\Statement;
use Wikibase\Lib\LanguageFallbackChain;
use Wikibase\Lib\Store\EntityRevision;
use Wikibase\Lib\Store\EntityTitleLookup;
use Wikibase\Repo\FederatedProperties\ApiEntityLookup;
use Wikibase\Repo\FederatedProperties\ApiRequestExecutionException;
use Wikibase\Repo\FederatedProperties\FederatedPropertiesEntityParserOutputGenerator;
use Wikibase\Repo\FederatedProperties\FederatedPropertiesError;
use Wikibase\Repo\LinkedData\EntityDataFormatProvider;
use Wikibase\Repo\ParserOutput\DispatchingEntityMetaTagsCreatorFactory;
use Wikibase\Repo\ParserOutput\DispatchingEntityViewFactory;
use Wikibase\Repo\ParserOutput\EntityParserOutputGenerator;
use Wikibase\Repo\ParserOutput\FullEntityParserOutputGenerator;
use Wikibase\Repo\ParserOutput\ItemParserOutputUpdater;
use Wikibase\Repo\ParserOutput\ParserOutputJsConfigBuilder;
use Wikibase\View\LocalizedTextProvider;
use Wikibase\View\Template\TemplateFactory;

/**
 * @covers \Wikibase\Repo\FederatedProperties\FederatedPropertiesEntityParserOutputGenerator
 *
 * @group Wikibase
 *
 * @license GPL-2.0-or-later
 */
class FederatedPropertiesEntityParserOutputGeneratorTest extends MediaWikiIntegrationTestCase {

	public function testShouldPrefetchFederatedProperties() {
		$labelLanguage = 'en';
		$userLanguage = 'en';

		$item = new Item( new ItemId( 'Q7799929' ) );
		$item->setLabel( $labelLanguage, 'kitten item' );

		$statementWithReference = new Statement( new PropertyNoValueSnak( 1 ) );
		$statementWithReference->addNewReference( new PropertyNoValueSnak( 4 ) );

		$item->getStatements()->addStatement( $statementWithReference );
		$item->getStatements()->addStatement( new Statement( new PropertyNoValueSnak( 2 ) ) );
		$item->getStatements()->addStatement( new Statement( new PropertyNoValueSnak( 3 ) ) );
		$item->getStatements()->addStatement( new Statement( new PropertyNoValueSnak( 3 ) ) );

		$expectedIds = [
			new PropertyId( "P1" ),
			new PropertyId( "P2" ),
			new PropertyId( "P3" ),
			new PropertyId( "P4" )
		];

		$prefetchingTermLookup = $this->createMock( ApiEntityLookup::class );
		$prefetchingTermLookup->expects( $this->once() )
			->method( 'fetchEntities' )
			->willReturnCallback( $this->getPrefetchTermsCallback(
				$expectedIds
			) );

		$innerPog = $this->createMock( EntityParserOutputGenerator::class );
		$entityParserOutputGenerator = $this->newEntityParserOutputGenerator( $prefetchingTermLookup, $innerPog, $userLanguage );

		$entityParserOutputGenerator->getParserOutput( new EntityRevision( $item, 4711 ), false );
	}

	public function testShouldNotCallPrefetchIfNoProperties() {
		$labelLanguage = 'en';
		$userLanguage = 'en';

		$item = new Item( new ItemId( 'Q7799929' ) );
		$item->setLabel( $labelLanguage, 'kitten item' );

		$prefetchingTermLookup = $this->createMock( ApiEntityLookup::class );
		$prefetchingTermLookup->expects( $this->never() )
			->method( 'fetchEntities' );

		$innerPog = $this->createMock( EntityParserOutputGenerator::class );
		$entityParserOutputGenerator = $this->newEntityParserOutputGenerator( $prefetchingTermLookup, $innerPog, $userLanguage );

		$entityParserOutputGenerator->getParserOutput( new EntityRevision( $item, 4711 ), false );
	}

	protected function getPrefetchTermsCallback( $expectedIds ) {
		$prefetchTerms = function (
			array $entityIds,
			array $termTypes = null,
			array $languageCodes = null
		) use (
			$expectedIds
		) {
			$expectedIdStrings = array_map( function( EntityId $id ) {
				return $id->getSerialization();
			}, $expectedIds );
			$entityIdStrings = array_map( function( EntityId $id ) {
				return $id->getSerialization();
			}, $entityIds );

			sort( $expectedIdStrings );
			sort( $entityIdStrings );

			$this->assertEquals( $expectedIdStrings, $entityIdStrings );
		};
		return $prefetchTerms;
	}

	/**
	 * @dataProvider errorPageProvider
	 */
	public function testGetParserOutputHandlesFederatedApiException( $labelLanguage, $userLanguage ) {

		$item = new Item( new ItemId( 'Q7799929' ) );
		$item->setLabel( $labelLanguage, 'kitten item' );

		$prefetchingTermLookup = $this->createMock( ApiEntityLookup::class );
		$prefetchingTermLookup->expects( $this->never() )
			->method( 'fetchEntities' );

		$updater = $this->createMock( ItemParserOutputUpdater::class );
		$entityParserOutputGenerator = $this->newEntityParserOutputGenerator(
			$prefetchingTermLookup,
			$this->getFullGeneratorMock( [ $updater ], $userLanguage ),
			$userLanguage
		);
		$updater->method( 'updateParserOutput' )
			->willThrowException( new ApiRequestExecutionException() );

		// T254888 Exception will be handled and show an error page.
		$this->expectException( FederatedPropertiesError::class );

		$entityParserOutputGenerator->getParserOutput( new EntityRevision( $item, 4711 ), false );
	}

	private function getFullGeneratorMock( $dataUpdaters, $language ) {
		return new FullEntityParserOutputGenerator(
			$this->createMock( DispatchingEntityViewFactory::class ),
			$this->createMock( DispatchingEntityMetaTagsCreatorFactory::class ),
			$this->createMock( ParserOutputJsConfigBuilder::class ),
			$this->createMock( EntityTitleLookup::class ),
			$this->createMock( LanguageFallbackChain::class ),
			TemplateFactory::getDefaultInstance(),
			$this->createMock( LocalizedTextProvider::class ),
			new EntityDataFormatProvider(),
			$dataUpdaters,
			Language::factory( $language )
		);
	}

	private function newEntityParserOutputGenerator( $prefetchingTermLookup, $fullGenerator, $languageCode = 'en' ) {
		return new FederatedPropertiesEntityParserOutputGenerator(
			$fullGenerator,
			Language::factory( $languageCode ),
			$prefetchingTermLookup
		);
	}

	public function errorPageProvider() {
		return [
			[ 'en', 'en' ],
			[ 'de', 'en' ],
		];
	}
}
