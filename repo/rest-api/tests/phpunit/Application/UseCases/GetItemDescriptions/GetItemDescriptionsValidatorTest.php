<?php declare( strict_types=1 );

namespace Wikibase\Repo\Tests\RestApi\Application\UseCases\GetItemDescriptions;

use PHPUnit\Framework\TestCase;
use Wikibase\Repo\RestApi\Application\UseCases\GetItemDescriptions\GetItemDescriptionsRequest;
use Wikibase\Repo\RestApi\Application\UseCases\GetItemDescriptions\GetItemDescriptionsValidator;
use Wikibase\Repo\RestApi\Application\UseCases\UseCaseError;
use Wikibase\Repo\RestApi\Application\Validation\ItemIdValidator;

/**
 * @covers \Wikibase\Repo\RestApi\Application\UseCases\GetItemDescriptions\GetItemDescriptionsValidator
 *
 * @group Wikibase
 *
 * @license GPL-2.0-or-later
 */
class GetItemDescriptionsValidatorTest extends TestCase {

	public function testWithInvalidId(): void {
		$invalidId = 'X123';

		try {
			$this->newValidator()->assertValidRequest( new GetItemDescriptionsRequest( $invalidId ) );
			$this->fail( 'Exception was not thrown.' );
		} catch ( UseCaseError $useCaseEx ) {
			$this->assertSame( UseCaseError::INVALID_ITEM_ID, $useCaseEx->getErrorCode() );
			$this->assertSame( "Not a valid item ID: $invalidId", $useCaseEx->getErrorMessage() );
		}
	}

	public function testWithValidId(): void {
		$this->expectNotToPerformAssertions();
		$this->newValidator()->assertValidRequest( new GetItemDescriptionsRequest( 'Q321' ) );
	}

	private function newValidator(): GetItemDescriptionsValidator {
		return ( new GetItemDescriptionsValidator( new ItemIdValidator() ) );
	}

}
