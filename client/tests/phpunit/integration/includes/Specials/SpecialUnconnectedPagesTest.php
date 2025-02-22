<?php

namespace Wikibase\Client\Tests\Integration\Specials;

use Iterator;
use SpecialPageTestBase;
use Title;
use Wikibase\Client\NamespaceChecker;
use Wikibase\Client\Specials\SpecialUnconnectedPages;
use Wikibase\Client\WikibaseClient;
use Wikibase\Lib\SettingsArray;
use Wikimedia\Rdbms\IDatabase;

/**
 * @covers \Wikibase\Client\Specials\SpecialUnconnectedPages
 *
 * @group WikibaseClient
 * @group SpecialPage
 * @group WikibaseSpecialPage
 * @group Wikibase
 * @group Database
 *
 * @license GPL-2.0-or-later
 * @author John Erling Blad < jeblad@gmail.com >
 * @author Thiemo Kreuz
 */
class SpecialUnconnectedPagesTest extends SpecialPageTestBase {

	protected function setUp(): void {
		$this->setService(
			'WikibaseClient.NamespaceChecker',
			new NamespaceChecker(
				[],
				[ $this->getDefaultWikitextNS(), $this->getDefaultWikitextNS() + 1 ]
			)
		);

		parent::setUp();
	}

	public function addDBDataOnce(): void {
		$namespace = $this->getDefaultWikitextNS();

		// Remove old stray pages.
		$this->db->delete( 'page', IDatabase::ALL_ROWS, __METHOD__ );

		$expectedUnconnectedTitle = Title::newFromTextThrow(
			"SpecialUnconnectedPagesTest-expectedUnconnected",
			$namespace
		);
		$unconnectedTitle = Title::newFromTextThrow(
			"SpecialUnconnectedPagesTest-unconnected",
			$namespace
		);
		$connectedTitle = Title::newFromTextThrow(
			"SpecialUnconnectedPagesTest-connected",
			$namespace
		);
		$furtherUnconnectedTitle = Title::newFromTextThrow(
			"SpecialUnconnectedPagesTest-unconnected2",
			$namespace
		);

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $expectedUnconnectedTitle );
		$page->insertOn( $this->db, 100 );

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $unconnectedTitle );
		$page->insertOn( $this->db, 200 );

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $connectedTitle );
		$page->insertOn( $this->db, 300 );

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $furtherUnconnectedTitle );
		$page->insertOn( $this->db, 400 );
	}

	private function insertPageProp(
		int $pageId,
		string $propName,
		string $value = '',
		float $sortKey = 0.0
	): void {
		$this->db->insert(
			'page_props',
			[
				[
					'pp_page' => $pageId,
					'pp_propname' => $propName,
					'pp_value' => $value,
					'pp_sortkey' => $sortKey,
				],
			],
			__METHOD__
		);
	}

	private function insertExpectedUnconnectedPagePageProp(): void {
		$this->insertPageProp( 100, 'expectedUnconnectedPage' );
	}

	private function insertUnexpectedUnconnectedPagePageProp(): void {
		$namespace = $this->getDefaultWikitextNS();
		$this->insertPageProp( 200, 'unexpectedUnconnectedPage', $namespace, $namespace );
		$this->insertPageProp( 400, 'unexpectedUnconnectedPage', $namespace, $namespace );
	}

	private function insertWikibaseItemPageProp(): void {
		$this->insertPageProp( 300, 'wikibase_item', 'Q12' );
	}

	protected function newSpecialPage(
		NamespaceChecker $namespaceChecker = null,
		int $migrationStage = MIGRATION_WRITE_BOTH
	): SpecialUnconnectedPages {
		$services = $this->getServiceContainer();
		return new SpecialUnconnectedPages(
			$services->getNamespaceInfo(),
			$services->getTitleFactory(),
			WikibaseClient::getClientDomainDbFactory( $services ),
			$namespaceChecker ?: WikibaseClient::getNamespaceChecker( $services ),
			new SettingsArray( [ 'tmpUnconnectedPagePagePropMigrationStage' => $migrationStage ] )
		);
	}

	public function migrationStageProvider(): array {
		return [
			'MIGRATION_OLD' => [
				'migrationStage' => MIGRATION_OLD,
				'descending' => true,
			],
			'MIGRATION_WRITE_BOTH' => [
				'migrationStage' => MIGRATION_WRITE_BOTH,
				'descending' => true,
			],
			'MIGRATION_NEW' => [
				'migrationStage' => MIGRATION_NEW,
				'descending' => false,
			],
		];
	}

	/**
	 * @dataProvider migrationStageProvider
	 */
	public function testReallyDoQuery( int $migrationStage, bool $descending ) {
		// Remove old stray page props
		$this->db->delete( 'page_props', IDatabase::ALL_ROWS, __METHOD__ );

		// Insert page props
		$this->insertWikibaseItemPageProp();
		$this->insertExpectedUnconnectedPagePageProp();
		if ( $migrationStage >= MIGRATION_WRITE_BOTH ) {
			$this->insertUnexpectedUnconnectedPagePageProp();
		}

		$namespace = $this->getDefaultWikitextNS();
		$specialPage = $this->newSpecialPage( null, $migrationStage );

		$expectedRows = [
			[
				'value' => '200',
				'namespace' => strval( $namespace ),
				'title' => 'SpecialUnconnectedPagesTest-unconnected'
			],
			[
				'value' => '400',
				'namespace' => strval( $namespace ),
				'title' => 'SpecialUnconnectedPagesTest-unconnected2'
			],
		];
		if ( $descending ) {
			$expectedRows = array_reverse( $expectedRows );
		}

		// First entry
		$res = $specialPage->reallyDoQuery( 1 );
		$this->assertSame( 1, $res->numRows() );
		$this->assertSame( $expectedRows[ 0 ], (array)$res->fetchObject() );

		// Continue with offset
		$res = $specialPage->reallyDoQuery( 10, 1 );
		$this->assertSame( 1, $res->numRows() );
		$this->assertSame( $expectedRows[ 1 ], (array)$res->fetchObject() );

		// Get all entries at once
		$res = $specialPage->reallyDoQuery( 5 );
		$this->assertSame( 2, $res->numRows() );
		$this->assertSame( $expectedRows, [ (array)$res->fetchObject(), (array)$res->fetchObject() ] );
	}

	/**
	 * @dataProvider migrationStageProvider
	 */
	public function testReallyDoQuery_noResults( int $migrationStage ) {
		// Remove old stray page props
		$this->db->delete( 'page_props', IDatabase::ALL_ROWS, __METHOD__ );

		// Insert page props (this is => MIGRATION_WRITE_BOTH behaviour)
		$this->insertWikibaseItemPageProp();
		$this->insertExpectedUnconnectedPagePageProp();
		$this->insertUnexpectedUnconnectedPagePageProp();

		$specialPage = $this->newSpecialPage( null, $migrationStage );
		// Query another namespace
		$specialPage->getRequest()->setVal( 'namespace', $this->getDefaultWikitextNS() + 1 );

		$this->assertSame( 0, $specialPage->reallyDoQuery( 10 )->numRows() );
	}

	/**
	 * Integration test that ensures that the "unexpectedUnconnectedPage" page
	 * prop is used.
	 */
	public function testReallyDoQuery_unexpectedUnconnectedPage() {
		// Make sure only the "unexpectedUnconnectedPage" page prop exists
		$this->db->delete( 'page_props', IDatabase::ALL_ROWS, __METHOD__ );
		$this->insertUnexpectedUnconnectedPagePageProp();

		$namespace = $this->getDefaultWikitextNS();
		$specialPage = $this->newSpecialPage( null, MIGRATION_NEW );
		$specialPage->getRequest()->setVal( 'namespace', $namespace );
		$res = $specialPage->reallyDoQuery( 1, 1 );
		$this->assertSame( 1, $res->numRows() );
		$this->assertSame( [
				'value' => '400',
				'namespace' => strval( $namespace ),
				'title' => 'SpecialUnconnectedPagesTest-unconnected2'
			],
			(array)$res->fetchObject()
		);
	}

	public function testExecuteDoesNotCauseFatalError() {
		$this->executeSpecialPage( '' );
		$this->assertTrue( true, 'Calling execute without any subpage value' );
	}

	/**
	 * @dataProvider provideBuildNamespaceConditionals
	 */
	public function testBuildNamespaceConditionals( ?int $ns, array $expected, int $migrationStage ) {
		$checker = new NamespaceChecker( [ 2 ], [ 0, 4 ] );
		$page = $this->newSpecialPage( $checker, $migrationStage );
		$page->getRequest()->setVal( 'namespace', $ns );
		$this->assertSame( $expected, $page->buildNamespaceConditionals() );
	}

	public function provideBuildNamespaceConditionals(): Iterator {
		yield 'no namespace' => [
			null,
			[ 'page_namespace' => [ 0, 4 ] ],
			MIGRATION_OLD,
		];
		yield 'included namespace' => [
			0,
			[ 'page_namespace' => 0 ],
			MIGRATION_WRITE_BOTH,
		];
		yield 'excluded namespace' => [
			2,
			[ 'page_namespace' => [ 0, 4 ] ],
			MIGRATION_WRITE_NEW,
		];
		yield 'no namespace (MIGRATION_NEW)' => [
			null,
			[ 'pp_sortkey' => [ 0, 4 ] ],
			MIGRATION_NEW,
		];
		yield 'included namespace (MIGRATION_NEW)' => [
			0,
			[ 'pp_sortkey' => 0 ],
			MIGRATION_NEW,
		];
		yield 'excluded namespace (MIGRATION_NEW)' => [
			2,
			[ 'pp_sortkey' => [ 0, 4 ] ],
			MIGRATION_NEW,
		];
	}

	public function getQueryInfoProvider(): array {
		return [
			[ [ 'expectedUnconnectedPage', 'wikibase_item' ], MIGRATION_OLD ],
			[ [ 'expectedUnconnectedPage', 'wikibase_item' ], MIGRATION_WRITE_BOTH ],
			[ [ 'unexpectedUnconnectedPage' ], MIGRATION_NEW ],
		];
	}

	/**
	 * @dataProvider getQueryInfoProvider
	 */
	public function testGetQueryInfo( $pageProps, $migrationStage ) {
		$page = $this->newSpecialPage( null, $migrationStage );
		$queryInfo = $page->getQueryInfo();
		$this->assertIsArray( $queryInfo );
		$this->assertNotEmpty( $queryInfo );

		foreach ( $pageProps as $pageProp ) {
			$this->assertStringContainsString(
				json_encode( $pageProp ),
				json_encode( $queryInfo['join_conds']['page_props'] )
			);
		}

		$this->assertArrayHasKey( 'conds', $queryInfo );
	}

	public function testReallyDoQueryReturnsEmptyResultWhenExceedingLimit() {
		$page = $this->newSpecialPage();
		$result = $page->reallyDoQuery( 1, 10001 );
		$this->assertSame( 0, $result->numRows() );
	}

	public function testFetchFromCacheReturnsEmptyResultWhenExceedingLimit() {
		$page = $this->newSpecialPage();
		$result = $page->fetchFromCache( 1, 10001 );
		$this->assertSame( 0, $result->numRows() );
	}

	public function testFormatResult() {
		$skin = $this->createMock( \Skin::class );
		$result = new \stdClass();
		$result->value = 1;

		$namespaceChecker = new NamespaceChecker( [] );

		$titleFactoryMock = $this->createMock( \TitleFactory::class );

		$titleFactoryMock->method( 'newFromID' )
			->willReturn( null );

		$services = $this->getServiceContainer();
		$specialPage = new SpecialUnconnectedPages(
			$services->getNamespaceInfo(),
			$titleFactoryMock,
			WikibaseClient::getClientDomainDbFactory( $services ),
			$namespaceChecker ?: WikibaseClient::getNamespaceChecker( $services ),
			new SettingsArray( [ 'tmpUnconnectedPagePagePropMigrationStage' => MIGRATION_WRITE_BOTH ] )
		);

		$this->assertFalse( $specialPage->formatResult( $skin, $result ) );
	}

}
