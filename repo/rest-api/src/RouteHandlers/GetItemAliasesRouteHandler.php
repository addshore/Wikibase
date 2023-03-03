<?php declare( strict_types=1 );

namespace Wikibase\Repo\RestApi\RouteHandlers;

use MediaWiki\Rest\RequestInterface;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\StringStream;
use Wikibase\Repo\RestApi\RouteHandlers\Middleware\AuthenticationMiddleware;
use Wikibase\Repo\RestApi\RouteHandlers\Middleware\MiddlewareHandler;
use Wikibase\Repo\RestApi\RouteHandlers\Middleware\UserAgentCheckMiddleware;
use Wikibase\Repo\RestApi\Serialization\AliasesSerializer;
use Wikibase\Repo\RestApi\UseCases\GetItemAliases\GetItemAliases;
use Wikibase\Repo\RestApi\UseCases\GetItemAliases\GetItemAliasesRequest;
use Wikibase\Repo\RestApi\UseCases\GetItemAliases\GetItemAliasesResponse;
use Wikibase\Repo\RestApi\WbRestApi;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @license GPL-2.0-or-later
 */
class GetItemAliasesRouteHandler extends SimpleHandler {

	private const ITEM_ID_PATH_PARAM = 'item_id';

	private GetItemAliases $useCase;
	private AliasesSerializer $aliasesSerializer;
	private MiddlewareHandler $middlewareHandler;

	public function __construct(
		GetItemAliases $useCase,
		AliasesSerializer $aliasesSerializer,
		MiddlewareHandler $middlewareHandler
	) {
		$this->useCase = $useCase;
		$this->aliasesSerializer = $aliasesSerializer;
		$this->middlewareHandler = $middlewareHandler;
	}

	public static function factory(): self {
		return new self(
			WbRestApi::getGetItemAliases(),
			new AliasesSerializer(),
			new MiddlewareHandler( [
				WbRestApi::getUnexpectedErrorHandlerMiddleware(),
				new UserAgentCheckMiddleware(),
				new AuthenticationMiddleware(),
				WbRestApi::getPreconditionMiddlewareFactory()->newPreconditionMiddleware(
					fn( RequestInterface $request ): string => $request->getPathParam( self::ITEM_ID_PATH_PARAM )
				),
			] ),
		);
	}

	public function needsWriteAccess(): bool {
		return false;
	}

	/**
	 * @param mixed ...$args
	 */
	public function run( ...$args ): Response {
		return $this->middlewareHandler->run( $this, [ $this, 'runUseCase' ], $args );
	}

	public function runUseCase( string $itemId ): Response {
		$useCaseResponse = $this->useCase->execute( new GetItemAliasesRequest( $itemId ) );
		return $this->newSuccessHttpResponse( $useCaseResponse );
	}

	public function getParamSettings(): array {
		return [
			self::ITEM_ID_PATH_PARAM => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
	}

	private function newSuccessHttpResponse( GetItemAliasesResponse $useCaseResponse ): Response {
		$httpResponse = $this->getResponseFactory()->create();
		$httpResponse->setHeader( 'Content-Type', 'application/json' );
		$httpResponse->setHeader( 'Last-Modified', wfTimestamp( TS_RFC2822, $useCaseResponse->getLastModified() ) );
		$this->setEtagFromRevId( $httpResponse, $useCaseResponse->getRevisionId() );
		$httpResponse->setBody(
			new StringStream( json_encode( $this->aliasesSerializer->serialize( $useCaseResponse->getAliases() ) ) )
		);

		return $httpResponse;
	}

	private function setEtagFromRevId( Response $response, int $revId ): void {
		$response->setHeader( 'ETag', "\"$revId\"" );
	}

	/**
	 * Preconditions are checked via {@link PreconditionMiddleware}
	 *
	 * @inheritDoc
	 */
	public function checkPreconditions(): ?ResponseInterface {
		return null;
	}

}
