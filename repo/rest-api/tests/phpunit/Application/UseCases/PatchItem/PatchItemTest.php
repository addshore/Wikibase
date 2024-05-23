<?php declare( strict_types=1 );

namespace Wikibase\Repo\Tests\RestApi\Application\UseCases\PatchItem;

use PHPUnit\Framework\TestCase;
use Wikibase\DataModel\Entity\Item as ItemWriteModel;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Snak\PropertySomeValueSnak;
use Wikibase\DataModel\Term\Fingerprint;
use Wikibase\DataModel\Term\Term;
use Wikibase\DataModel\Term\TermList;
use Wikibase\Lib\Store\EntityRevisionLookup;
use Wikibase\Repo\RestApi\Application\Serialization\AliasesDeserializer;
use Wikibase\Repo\RestApi\Application\Serialization\AliasesSerializer;
use Wikibase\Repo\RestApi\Application\Serialization\DescriptionsDeserializer;
use Wikibase\Repo\RestApi\Application\Serialization\DescriptionsSerializer;
use Wikibase\Repo\RestApi\Application\Serialization\ItemDeserializer;
use Wikibase\Repo\RestApi\Application\Serialization\ItemSerializer;
use Wikibase\Repo\RestApi\Application\Serialization\LabelsDeserializer;
use Wikibase\Repo\RestApi\Application\Serialization\LabelsSerializer;
use Wikibase\Repo\RestApi\Application\Serialization\PropertyValuePairDeserializer;
use Wikibase\Repo\RestApi\Application\Serialization\ReferenceDeserializer;
use Wikibase\Repo\RestApi\Application\Serialization\SitelinkDeserializer;
use Wikibase\Repo\RestApi\Application\Serialization\SitelinkSerializer;
use Wikibase\Repo\RestApi\Application\Serialization\SitelinksSerializer;
use Wikibase\Repo\RestApi\Application\Serialization\StatementDeserializer;
use Wikibase\Repo\RestApi\Application\Serialization\StatementListSerializer;
use Wikibase\Repo\RestApi\Application\UseCases\PatchItem\PatchItem;
use Wikibase\Repo\RestApi\Application\UseCases\PatchItem\PatchItemRequest;
use Wikibase\Repo\RestApi\Application\UseCases\PatchItem\PatchItemValidator;
use Wikibase\Repo\RestApi\Application\UseCases\PatchJson;
use Wikibase\Repo\RestApi\Domain\ReadModel\Aliases;
use Wikibase\Repo\RestApi\Domain\ReadModel\Description;
use Wikibase\Repo\RestApi\Domain\ReadModel\Descriptions;
use Wikibase\Repo\RestApi\Domain\ReadModel\Item;
use Wikibase\Repo\RestApi\Domain\ReadModel\Label;
use Wikibase\Repo\RestApi\Domain\ReadModel\Labels;
use Wikibase\Repo\RestApi\Domain\ReadModel\Sitelinks;
use Wikibase\Repo\RestApi\Domain\ReadModel\StatementList;
use Wikibase\Repo\RestApi\Domain\Services\ItemRetriever;
use Wikibase\Repo\RestApi\Domain\Services\ItemUpdater;
use Wikibase\Repo\RestApi\Domain\Services\StatementReadModelConverter;
use Wikibase\Repo\RestApi\Infrastructure\DataAccess\EntityRevisionLookupItemDataRetriever;
use Wikibase\Repo\RestApi\Infrastructure\JsonDiffJsonPatcher;
use Wikibase\Repo\RestApi\Infrastructure\SitelinksReadModelConverter;
use Wikibase\Repo\Tests\RestApi\Application\UseCaseRequestValidation\TestValidatingRequestDeserializer;
use Wikibase\Repo\Tests\RestApi\Infrastructure\DataAccess\DummyItemRevisionMetaDataRetriever;
use Wikibase\Repo\Tests\RestApi\Infrastructure\DataAccess\InMemoryItemRepository;
use Wikibase\Repo\Tests\RestApi\Infrastructure\DataAccess\SameTitleSitelinkTargetResolver;

/**
 * @covers \Wikibase\Repo\RestApi\Application\UseCases\PatchItem\PatchItem
 *
 * @group Wikibase
 *
 * @license GPL-2.0-or-later
 */
class PatchItemTest extends TestCase {

	private const ALLOWED_BADGES = [ 'Q999' ];

	private InMemoryItemRepository $itemRepository;
	private PatchItemValidator $validator;
	private PatchJson $patchJson;
	private ItemRetriever $itemRetriever;
	private ItemUpdater $itemUpdater;

	protected function setUp(): void {
		parent::setUp();

		$this->itemRepository = new InMemoryItemRepository();

		$this->validator = new TestValidatingRequestDeserializer();
		$this->patchJson = new PatchJson( new JsonDiffJsonPatcher() );
		$this->itemRetriever = $this->getMockBuilder( EntityRevisionLookupItemDataRetriever::class )
			->onlyMethods( [ 'getItem' ] )
			->setConstructorArgs( [
				$this->createStub( EntityRevisionLookup::class ),
				$this->createStub( StatementReadModelConverter::class ),
				$this->createStub( SitelinksReadModelConverter::class ),
			] )
			->getMock();
		$this->itemRetriever->method( 'getItem' )->willReturnCallback(
			fn( $itemId ) => $this->itemRepository->getItem( $itemId )
		);
		$this->itemUpdater = $this->itemRepository;
	}

	public function testHappyPath(): void {
		$itemId = new ItemId( 'Q123' );
		$editTags = TestValidatingRequestDeserializer::ALLOWED_TAGS;
		$isBot = false;
		$comment = 'statement replaced by ' . __METHOD__;

		$this->itemRepository->addItem(
			new ItemWriteModel(
				$itemId,
				new Fingerprint( new TermList( [ new Term( 'en', 'potato' ), new Term( 'de', 'Kartoffel' ) ] ) )
			)
		);

		$response = $this->newUseCase()->execute(
			new PatchItemRequest(
				"$itemId",
				[
					[ 'op' => 'add', 'path' => '/descriptions/en', 'value' => 'staple food' ],
					[ 'op' => 'replace', 'path' => '/labels/en', 'value' => 'Solanum tuberosum' ],
					[ 'op' => 'remove', 'path' => '/labels/de' ],
				],
				$editTags,
				$isBot,
				$comment,
				null
			)
		);

		$this->assertSame( $this->itemRepository->getLatestRevisionId( $itemId ), $response->getRevisionId() );
		$this->assertSame(
			$this->itemRepository->getLatestRevisionTimestamp( $itemId ),
			$response->getLastModified()
		);
		$this->assertEquals(
			new Item(
				$itemId,
				new Labels( new Label( 'en', 'Solanum tuberosum' ) ),
				new Descriptions( new Description( 'en', 'staple food' ) ),
				new Aliases(),
				new Sitelinks(),
				new StatementList()
			),
			$response->getItem()
		);
	}

	private function newUseCase(): PatchItem {
		return new PatchItem(
			$this->validator,
			$this->itemRetriever,
			new ItemSerializer(
				new LabelsSerializer(),
				new DescriptionsSerializer(),
				new AliasesSerializer(),
				$this->createStub( StatementListSerializer::class ),
				new SitelinksSerializer( new SitelinkSerializer() )
			),
			$this->newItemDeserializer(),
			$this->patchJson,
			$this->itemUpdater
		);
	}

	private function newItemDeserializer(): ItemDeserializer {
		$propValPairDeserializer = $this->createStub( PropertyValuePairDeserializer::class );
		$propValPairDeserializer->method( 'deserialize' )->willReturnCallback(
			fn( array $p ) => new PropertySomeValueSnak( new ItemId( $p[ 'item' ][ 'id' ] ) )
		);

		return new ItemDeserializer(
			new LabelsDeserializer(),
			new DescriptionsDeserializer(),
			new AliasesDeserializer(),
			new StatementDeserializer( $propValPairDeserializer, $this->createStub( ReferenceDeserializer::class ) ),
			new SitelinkDeserializer(
				'/\?/',
				self::ALLOWED_BADGES,
				new SameTitleSitelinkTargetResolver(),
				new DummyItemRevisionMetaDataRetriever()
			)
		);
	}

}
