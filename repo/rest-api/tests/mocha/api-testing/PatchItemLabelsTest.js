'use strict';

const { assert, utils, action } = require( 'api-testing' );
const { expect } = require( '../helpers/chaiHelper' );
const entityHelper = require( '../helpers/entityHelper' );
const { newPatchItemLabelsRequestBuilder } = require( '../helpers/RequestBuilderFactory' );
const { makeEtag } = require( '../helpers/httpHelper' );
const { formatLabelsEditSummary } = require( '../helpers/formatEditSummaries' );

function assertValidErrorResponse( response, statusCode, responseBodyErrorCode, context = null ) {
	expect( response ).to.have.status( statusCode );
	assert.header( response, 'Content-Language', 'en' );
	assert.strictEqual( response.body.code, responseBodyErrorCode );
	if ( context === null ) {
		assert.notProperty( response.body, 'context' );
	} else {
		assert.deepStrictEqual( response.body.context, context );
	}
}

describe( newPatchItemLabelsRequestBuilder().getRouteDescription(), () => {
	let testItemId;
	let testEnLabel;
	let originalLastModified;
	let originalRevisionId;
	const languageWithExistingLabel = 'en';

	before( async function () {
		testEnLabel = `English Label ${utils.uniq()}`;
		testItemId = ( await entityHelper.createEntity( 'item', {
			labels: [ { language: languageWithExistingLabel, value: testEnLabel } ]
		} ) ).entity.id;

		const testItemCreationMetadata = await entityHelper.getLatestEditMetadata( testItemId );
		originalLastModified = new Date( testItemCreationMetadata.timestamp );
		originalRevisionId = testItemCreationMetadata.revid;

		// wait 1s before modifying labels to verify the last-modified timestamps are different
		await new Promise( ( resolve ) => {
			setTimeout( resolve, 1000 );
		} );
	} );

	describe( '200 OK', () => {
		it( 'can add a label', async () => {
			const label = `neues deutsches label ${utils.uniq()}`;
			const response = await newPatchItemLabelsRequestBuilder(
				testItemId,
				[
					{ op: 'add', path: '/de', value: label }
				]
			).makeRequest();

			expect( response ).to.have.status( 200 );
			assert.strictEqual( response.body.de, label );
			assert.strictEqual( response.header[ 'content-type' ], 'application/json' );
			assert.isAbove( new Date( response.header[ 'last-modified' ] ), originalLastModified );
			assert.notStrictEqual( response.header.etag, makeEtag( originalRevisionId ) );
		} );

		it( 'can patch labels with edit metadata', async () => {
			const label = `new arabic label ${utils.uniq()}`;
			const user = await action.robby(); // robby is a bot
			const tag = await action.makeTag( 'e2e test tag', 'Created during e2e test' );
			const editSummary = 'I made a patch';
			const response = await newPatchItemLabelsRequestBuilder(
				testItemId,
				[
					{
						op: 'add',
						path: '/ar',
						value: label
					}
				]
			).withJsonBodyParam( 'tags', [ tag ] )
				.withJsonBodyParam( 'bot', true )
				.withJsonBodyParam( 'comment', editSummary )
				.withUser( user )
				.assertValidRequest().makeRequest();

			expect( response ).to.have.status( 200 );
			assert.strictEqual( response.body.ar, label );
			assert.strictEqual( response.header[ 'content-type' ], 'application/json' );
			assert.isAbove( new Date( response.header[ 'last-modified' ] ), originalLastModified );
			assert.notStrictEqual( response.header.etag, makeEtag( originalRevisionId ) );

			const editMetadata = await entityHelper.getLatestEditMetadata( testItemId );
			assert.include( editMetadata.tags, tag );
			assert.property( editMetadata, 'bot' );
			assert.strictEqual(
				editMetadata.comment,
				formatLabelsEditSummary( 'update-languages-short', 'ar', editSummary )
			);
		} );
	} );

	describe( '422 error response', () => {
		const makeReplaceExistingLabelPatchOp = ( newLabel ) => ( {
			op: 'replace',
			path: `/${languageWithExistingLabel}`,
			value: newLabel
		} );

		it( 'invalid label', async () => {
			const invalidLabel = 'tab characters \t not allowed';
			const response = await newPatchItemLabelsRequestBuilder(
				testItemId,
				[ makeReplaceExistingLabelPatchOp( invalidLabel ) ]
			)
				.assertValidRequest()
				.makeRequest();

			assertValidErrorResponse(
				response,
				422,
				'patched-label-invalid',
				{
					language: languageWithExistingLabel,
					value: invalidLabel
				}
			);
			assert.include( response.body.message, invalidLabel );
			assert.include( response.body.message, `'${languageWithExistingLabel}'` );
		} );

		it( 'empty label', async () => {
			const response = await newPatchItemLabelsRequestBuilder(
				testItemId,
				[ makeReplaceExistingLabelPatchOp( '' ) ]
			)
				.assertValidRequest()
				.makeRequest();

			assertValidErrorResponse(
				response,
				422,
				'patched-label-empty',
				{
					language: languageWithExistingLabel
				}
			);
		} );

		it( 'label too long', async () => {
			// this assumes the default value of 250 from Wikibase.default.php is in place and
			// may fail if $wgWBRepoSettings['string-limits']['multilang']['length'] is overwritten
			const maxLength = 250;
			const tooLongLabel = 'x'.repeat( maxLength + 1 );
			const comment = 'Label too long';
			const response = await newPatchItemLabelsRequestBuilder(
				testItemId,
				[ makeReplaceExistingLabelPatchOp( tooLongLabel ) ]
			)
				.withJsonBodyParam( 'comment', comment )
				.assertValidRequest()
				.makeRequest();

			assertValidErrorResponse(
				response,
				422,
				'patched-label-too-long',
				{
					value: tooLongLabel,
					'character-limit': maxLength,
					language: languageWithExistingLabel
				}
			);
			assert.strictEqual(
				response.body.message,
				`Changed label for '${languageWithExistingLabel}' must not be more than ${maxLength} characters long`
			);
		} );

		it( 'invalid language code', async () => {
			const invalidLanguage = 'invalid-language-code';
			const response = await newPatchItemLabelsRequestBuilder(
				testItemId,
				[ {
					op: 'add',
					path: `/${invalidLanguage}`,
					value: 'potato'
				} ]
			)
				.assertValidRequest()
				.makeRequest();

			assertValidErrorResponse(
				response,
				422,
				'patched-labels-invalid-language-code',
				{ language: invalidLanguage }
			);
			assert.include( response.body.message, invalidLanguage );
		} );
	} );

	describe( '404 error response', () => {
		it( 'item not found', async () => {
			const itemId = 'Q999999';
			const response = await newPatchItemLabelsRequestBuilder(
				itemId,
				[
					{
						op: 'replace',
						path: '/en',
						value: utils.uniq()
					}
				]
			).assertValidRequest().makeRequest();

			expect( response ).to.have.status( 404 );
			assert.strictEqual( response.header[ 'content-language' ], 'en' );
			assert.strictEqual( response.body.code, 'item-not-found' );
			assert.include( response.body.message, itemId );
		} );
	} );

	describe( '409 error response', () => {
		it( 'item is a redirect', async () => {
			const redirectTarget = testItemId;
			const redirectSource = await entityHelper.createRedirectForItem( redirectTarget );

			const response = await newPatchItemLabelsRequestBuilder(
				redirectSource,
				[
					{
						op: 'replace',
						path: '/en',
						value: utils.uniq()
					}
				]
			).assertValidRequest().makeRequest();

			assertValidErrorResponse( response, 409, 'redirected-item' );
			assert.include( response.body.message, redirectSource );
			assert.include( response.body.message, redirectTarget );
		} );

		it( '"path" field target does not exist', async () => {
			const operation = { op: 'remove', path: '/path/does/not/exist' };

			const response = await newPatchItemLabelsRequestBuilder( testItemId, [ operation ] )
				.assertValidRequest()
				.makeRequest();

			assertValidErrorResponse(
				response,
				409,
				'patch-target-not-found',
				{
					field: 'path',
					operation: operation
				}
			);
			assert.include( response.body.message, operation.path );
		} );

		it( '"from" field target does not exist', async () => {
			const operation = { op: 'copy', from: '/path/does/not/exist', path: '/en' };

			const response = await newPatchItemLabelsRequestBuilder( testItemId, [ operation ] )
				.assertValidRequest()
				.makeRequest();

			assertValidErrorResponse(
				response,
				409,
				'patch-target-not-found',
				{
					field: 'from',
					operation: operation
				}
			);
			assert.include( response.body.message, operation.from );
		} );

		it( 'patch test condition failed', async () => {
			const operation = { op: 'test', path: '/en', value: 'potato' };
			const response = await newPatchItemLabelsRequestBuilder( testItemId, [ operation ] )
				.assertValidRequest()
				.makeRequest();

			assertValidErrorResponse(
				response,
				409,
				'patch-test-failed',
				{
					operation: operation,
					'actual-value': testEnLabel
				}
			);
			assert.include( response.body.message, operation.path );
			assert.include( response.body.message, JSON.stringify( operation.value ) );
			assert.include( response.body.message, testEnLabel );
		} );
	} );

	describe( '400 error response', () => {

		it( 'invalid item id', async () => {
			const itemId = testItemId.replace( 'Q', 'P' );
			const response = await newPatchItemLabelsRequestBuilder( itemId, [] )
				.assertInvalidRequest().makeRequest();

			assertValidErrorResponse( response, 400, 'invalid-item-id' );
			assert.include( response.body.message, itemId );
		} );

		it( 'invalid patch', async () => {
			const invalidPatch = { foo: 'this is not a valid JSON Patch' };
			const response = await newPatchItemLabelsRequestBuilder( testItemId, invalidPatch )
				.assertInvalidRequest().makeRequest();

			assertValidErrorResponse( response, 400, 'invalid-patch' );
		} );

		it( "invalid patch - missing 'op' field", async () => {
			const invalidOperation = { path: '/a/b/c', value: 'test' };
			const response = await newPatchItemLabelsRequestBuilder( testItemId, [ invalidOperation ] )
				.assertInvalidRequest().makeRequest();

			assertValidErrorResponse(
				response,
				400,
				'missing-json-patch-field',
				{ operation: invalidOperation, field: 'op' }
			);
			assert.include( response.body.message, "'op'" );
		} );

		it( "invalid patch - missing 'path' field", async () => {
			const invalidOperation = { op: 'remove' };
			const response = await newPatchItemLabelsRequestBuilder( testItemId, [ invalidOperation ] )
				.assertInvalidRequest().makeRequest();
			assertValidErrorResponse(
				response,
				400,
				'missing-json-patch-field',
				{ operation: invalidOperation, field: 'path' }
			);
			assert.include( response.body.message, "'path'" );
		} );

		it( "invalid patch - missing 'value' field", async () => {
			const invalidOperation = { op: 'add', path: '/a/b/c' };
			const response = await newPatchItemLabelsRequestBuilder( testItemId, [ invalidOperation ] )
				.makeRequest();

			assertValidErrorResponse(
				response,
				400,
				'missing-json-patch-field',
				{ operation: invalidOperation, field: 'value' }
			);
			assert.include( response.body.message, "'value'" );
		} );

		it( "invalid patch - missing 'from' field", async () => {
			const invalidOperation = { op: 'move', path: '/a/b/c' };
			const response = await newPatchItemLabelsRequestBuilder( testItemId, [ invalidOperation ] )
				.makeRequest();

			assertValidErrorResponse(
				response,
				400,
				'missing-json-patch-field',
				{ operation: invalidOperation, field: 'from' }
			);
			assert.include( response.body.message, "'from'" );
		} );

		it( "invalid patch - invalid 'op' field", async () => {
			const invalidOperation = { op: 'foobar', path: '/a/b/c', value: 'test' };
			const response = await newPatchItemLabelsRequestBuilder( testItemId, [ invalidOperation ] )
				.assertInvalidRequest().makeRequest();

			assertValidErrorResponse( response, 400, 'invalid-patch-operation', { operation: invalidOperation } );
			assert.include( response.body.message, "'foobar'" );
		} );

		it( "invalid patch - 'op' is not a string", async () => {
			const invalidOperation = { op: { foo: [ 'bar' ], baz: 42 }, path: '/a/b/c', value: 'test' };
			const response = await newPatchItemLabelsRequestBuilder( testItemId, [ invalidOperation ] )
				.assertInvalidRequest().makeRequest();

			assertValidErrorResponse(
				response,
				400,
				'invalid-patch-field-type',
				{ operation: invalidOperation, field: 'op' }
			);
			assert.include( response.body.message, "'op'" );
			assert.deepEqual( response.body.context.operation, invalidOperation );
			assert.strictEqual( response.body.context.field, 'op' );
		} );

		it( "invalid patch - 'path' is not a string", async () => {
			const invalidOperation = { op: 'add', path: { foo: [ 'bar' ], baz: 42 }, value: 'test' };
			const response = await newPatchItemLabelsRequestBuilder( testItemId, [ invalidOperation ] )
				.assertInvalidRequest().makeRequest();

			assertValidErrorResponse(
				response,
				400,
				'invalid-patch-field-type',
				{ operation: invalidOperation, field: 'path' }
			);
			assert.include( response.body.message, "'path'" );
		} );

		it( "invalid patch - 'from' is not a string", async () => {
			const invalidOperation = { op: 'move', from: { foo: [ 'bar' ], baz: 42 }, path: '/a/b/c' };
			const response = await newPatchItemLabelsRequestBuilder( testItemId, [ invalidOperation ] )
				.makeRequest();

			assertValidErrorResponse(
				response,
				400,
				'invalid-patch-field-type',
				{ operation: invalidOperation, field: 'from' }
			);
			assert.include( response.body.message, "'from'" );
		} );

		it( 'invalid edit tag', async () => {
			const invalidEditTag = 'invalid tag';
			const response = await newPatchItemLabelsRequestBuilder( testItemId, [] )
				.withJsonBodyParam( 'tags', [ invalidEditTag ] ).assertValidRequest().makeRequest();

			assertValidErrorResponse( response, 400, 'invalid-edit-tag' );
			assert.include( response.body.message, invalidEditTag );
		} );

		it( 'invalid edit tag type', async () => {
			const response = await newPatchItemLabelsRequestBuilder( testItemId, [] )
				.withJsonBodyParam( 'tags', 'not an array' ).assertInvalidRequest().makeRequest();

			expect( response ).to.have.status( 400 );
			assert.strictEqual( response.body.code, 'invalid-request-body' );
			assert.strictEqual( response.body.fieldName, 'tags' );
			assert.strictEqual( response.body.expectedType, 'array' );
		} );

		it( 'invalid bot flag type', async () => {
			const response = await newPatchItemLabelsRequestBuilder( testItemId, [] )
				.withJsonBodyParam( 'bot', 'not boolean' ).assertInvalidRequest().makeRequest();

			expect( response ).to.have.status( 400 );
			assert.strictEqual( response.body.code, 'invalid-request-body' );
			assert.strictEqual( response.body.fieldName, 'bot' );
			assert.strictEqual( response.body.expectedType, 'boolean' );
		} );

		it( 'comment too long', async () => {
			const comment = 'x'.repeat( 501 );
			const response = await newPatchItemLabelsRequestBuilder( testItemId, [] )
				.withJsonBodyParam( 'comment', comment ).assertValidRequest().makeRequest();

			assertValidErrorResponse( response, 400, 'comment-too-long' );
			assert.include( response.body.message, '500' );
		} );

		it( 'invalid comment type', async () => {
			const response = await newPatchItemLabelsRequestBuilder( testItemId, [] )
				.withJsonBodyParam( 'comment', 1234 ).assertInvalidRequest().makeRequest();

			expect( response ).to.have.status( 400 );
			assert.strictEqual( response.body.code, 'invalid-request-body' );
			assert.strictEqual( response.body.fieldName, 'comment' );
			assert.strictEqual( response.body.expectedType, 'string' );
		} );
	} );

	describe( '415 error response', () => {
		it( 'unsupported media type', async () => {
			const contentType = 'multipart/form-data';
			const response = await newPatchItemLabelsRequestBuilder(
				'Q123',
				[
					{
						op: 'replace',
						path: '/en',
						value: utils.uniq()
					}
				]
			).withHeader( 'content-type', contentType ).makeRequest();

			expect( response ).to.have.status( 415 );
			assert.strictEqual( response.body.message, `Unsupported Content-Type: '${contentType}'` );
		} );
	} );
} );
