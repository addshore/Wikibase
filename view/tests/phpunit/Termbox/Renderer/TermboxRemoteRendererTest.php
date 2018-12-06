<?php

namespace Wikibase\View\Tests\Termbox\Renderer;

use Exception;
use MediaWiki\Http\HttpRequestFactory;
use MWHttpRequest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit4And6Compat;
use Wikibase\DataModel\Entity\ItemId;
use PHPUnit\Framework\TestCase;
use Wikibase\View\Termbox\Renderer\TermboxRemoteRenderer;
use Wikibase\View\Termbox\Renderer\TermboxRenderingException;

/**
 * @covers \Wikibase\View\Termbox\Renderer\TermboxRemoteRenderer
 *
 * @group Wikibase
 *
 * @license GPL-2.0-or-later
 */
class TermboxRemoteRendererTest extends TestCase {

	use PHPUnit4And6Compat;

	/** private */ const SSR_URL = 'https://ssr/termbox';

	public function testGetContentWithEntityIdAndLanguage_returnsRequestResponse() {
		$content = 'hello from server!';

		$request = $this->newHttpRequest();
		$request->expects( $this->once() )
			->method( 'getStatus' )
			->willReturn( TermboxRemoteRenderer::HTTP_STATUS_OK );
		$request->expects( $this->once() )
			->method( 'getContent' )
			->willReturn( $content );

		$requestFactory = $this->newHttpRequestFactory();
		$requestFactory->expects( $this->once() )
			->method( 'create' )
			->with( self::SSR_URL . '?entity=Q42&language=de', [] )
			->willReturn( $request );

		$client = new TermboxRemoteRenderer( $requestFactory, self::SSR_URL );
		$this->assertSame(
			$content,
			$client->getContent( new ItemId( 'Q42' ), 'de' )
		);
	}

	public function testGetContentWithEntityIdAndLanguage_bubblesRequestException() {
		$entityId = new ItemId( 'Q42' );
		$language = 'de';
		$upstreamException = new Exception( 'domain exception' );

		$request = $this->newHttpRequest();
		$request->expects( $this->once() )
			->method( 'execute' )
			->willThrowException( $upstreamException );

		$requestFactory = $this->newHttpRequestFactory();
		$requestFactory->expects( $this->once() )
			->method( 'create' )
			->with( self::SSR_URL . '?entity=Q42&language=de', [] )
			->willReturn( $request );

		$client = new TermboxRemoteRenderer( $requestFactory, self::SSR_URL );

		try {
			$client->getContent( $entityId, $language );
			$this->fail( 'Expected exception did not occur.' );
		} catch ( Exception $exception ) {
			$this->assertInstanceOf( TermboxRenderingException::class, $exception );
			$this->assertSame( 'Encountered request problem', $exception->getMessage() );
			$this->assertSame( $upstreamException, $exception->getPrevious() );
		}
	}

	public function testGetContentEncounteringServerErrorResponse_throwsException() {
		$entityId = new ItemId( 'Q42' );
		$language = 'de';

		$request = $this->newHttpRequest();
		$request->expects( $this->once() )
			->method( 'getStatus' )
			->willReturn( 500 );
		$request->expects( $this->never() )
			->method( 'getContent' );

		$requestFactory = $this->newHttpRequestFactory();
		$requestFactory->expects( $this->once() )
			->method( 'create' )
			->with( self::SSR_URL . '?entity=Q42&language=de', [] )
			->willReturn( $request );

		$client = new TermboxRemoteRenderer( $requestFactory, self::SSR_URL );

		try {
			$client->getContent( $entityId, $language );
			$this->fail( 'Expected exception did not occur.' );
		} catch ( Exception $exception ) {
			$this->assertInstanceOf( TermboxRenderingException::class, $exception );
			$this->assertSame( 'Encountered bad response: 500', $exception->getMessage() );
		}
	}

	public function testGetContentEncounteringNotFoundResponse_throwsException() {
		$entityId = new ItemId( 'Q4711' );
		$language = 'de';

		$request = $this->newHttpRequest();
		$request->expects( $this->once() )
			->method( 'getStatus' )
			->willReturn( 404 );
		$request->expects( $this->never() )
			->method( 'getContent' );

		$requestFactory = $this->newHttpRequestFactory();
		$requestFactory->expects( $this->once() )
			->method( 'create' )
			->with( self::SSR_URL . '?entity=Q4711&language=de', [] )
			->willReturn( $request );

		$client = new TermboxRemoteRenderer( $requestFactory, self::SSR_URL );

		try {
			$client->getContent( $entityId, $language );
			$this->fail( 'Expected exception did not occur.' );
		} catch ( Exception $exception ) {
			$this->assertInstanceOf( TermboxRenderingException::class, $exception );
			$this->assertSame( 'Encountered bad response: 404', $exception->getMessage() );
		}
	}

	/**
	 * @return MockObject|HttpRequestFactory
	 */
	private function newHttpRequestFactory() {
		return $this->createMock( HttpRequestFactory::class );
	}

	/**
	 * @return MockObject|MWHttpRequest
	 */
	private function newHttpRequest() {
		$req = $this->createMock( MWHttpRequest::class );
		$req->expects( $this->once() )->method( 'execute' );

		return $req;
	}

}
