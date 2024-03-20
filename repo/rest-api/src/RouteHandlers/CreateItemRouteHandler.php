<?php declare( strict_types=1 );

namespace Wikibase\Repo\RestApi\RouteHandlers;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Rest\StringStream;
use MediaWiki\Rest\Validator\BodyValidator;
use Wikibase\Repo\RestApi\Application\Serialization\ItemPartsSerializer;
use Wikibase\Repo\RestApi\Application\UseCases\CreateItem\CreateItem;
use Wikibase\Repo\RestApi\Application\UseCases\CreateItem\CreateItemRequest;
use Wikibase\Repo\RestApi\Application\UseCases\CreateItem\CreateItemResponse;
use Wikibase\Repo\RestApi\Application\UseCases\UseCaseError;
use Wikibase\Repo\RestApi\Domain\ReadModel\ItemParts;
use Wikibase\Repo\RestApi\WbRestApi;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @license GPL-2.0-or-later
 */
class CreateItemRouteHandler extends SimpleHandler {
	use AssertContentType;

	public const ITEM_BODY_PARAM = 'item';
	public const TAGS_BODY_PARAM = 'tags';
	public const BOT_BODY_PARAM = 'bot';
	public const COMMENT_BODY_PARAM = 'comment';

	private CreateItem $useCase;
	private ItemPartsSerializer $itemSerializer;
	private ResponseFactory $responseFactory;

	public function __construct(
		CreateItem $useCase,
		ItemPartsSerializer $serializer,
		ResponseFactory $responseFactory
	) {
		$this->useCase = $useCase;
		$this->itemSerializer = $serializer;
		$this->responseFactory = $responseFactory;
	}

	public static function factory(): Handler {
		return new self(
			new CreateItem(
				WbRestApi::getValidatingRequestDeserializer(),
				WbRestApi::getItemUpdater(),
				WbRestApi::getAssertUserIsAuthorized()
			),
			WbRestApi::getSerializerFactory()->newItemPartsSerializer(),
			new ResponseFactory()
		);
	}

	public function run(): Response {
		$jsonBody = $this->getValidatedBody();
		'@phan-var array $jsonBody'; // guaranteed to be an array per getBodyValidator()

		try {
			return $this->newSuccessHttpResponse(
				$this->useCase->execute(
					new CreateItemRequest(
						$jsonBody[self::ITEM_BODY_PARAM],
						$jsonBody[self::TAGS_BODY_PARAM] ?? [],
						$jsonBody[self::BOT_BODY_PARAM] ?? false,
						$jsonBody[self::COMMENT_BODY_PARAM] ?? null,
						$this->getUsername()
					)
				)
			);
		} catch ( UseCaseError $e ) {
			return $this->responseFactory->newErrorResponseFromException( $e );
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getBodyValidator( $contentType ): BodyValidator {
		$this->assertContentType( [ 'application/json' ], $contentType );

		return new TypeValidatingJsonBodyValidator( [
			self::ITEM_BODY_PARAM => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'object',
				ParamValidator::PARAM_REQUIRED => true,
			],
			self::TAGS_BODY_PARAM => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'array',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => [],
			],
			self::BOT_BODY_PARAM => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => false,
			],
			self::COMMENT_BODY_PARAM => [
				self::PARAM_SOURCE => 'body',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
			],
		] );
	}

	private function newSuccessHttpResponse( CreateItemResponse $useCaseResponse ): Response {
		$response = $this->getResponseFactory()->create();
		$response->setStatus( 201 );
		$response->setHeader( 'Content-Type', 'application/json' );
		$response->setHeader(
			'Last-Modified',
			wfTimestamp( TS_RFC2822, $useCaseResponse->getLastModified() )
		);
		$response->setHeader( 'ETag', "\"{$useCaseResponse->getRevisionId()}\"" );
		$item = $useCaseResponse->getItem();
		$response->setHeader(
			'Location',
			$this->getRouter()->getRouteUrl(
				GetItemRouteHandler::ROUTE,
				[ GetItemRouteHandler::ITEM_ID_PATH_PARAM => $item->getId() ]
			)
		);
		$response->setBody( new StringStream( json_encode(
			$this->itemSerializer->serialize( new ItemParts(
				$item->getId(),
				ItemParts::VALID_FIELDS,
				$item->getLabels(),
				$item->getDescriptions(),
				$item->getAliases(),
				$item->getStatements(),
				$item->getSitelinks()
			) )
		) ) );

		return $response;
	}

	private function getUsername(): ?string {
		$mwUser = $this->getAuthority()->getUser();
		return $mwUser->isRegistered() ? $mwUser->getName() : null;
	}

}
