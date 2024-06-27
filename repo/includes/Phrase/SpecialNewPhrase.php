<?php

namespace Wikibase\Repo\Phrase;

use SpecialPage;
use Status;
use HTMLForm;
use Wikibase\Repo\Specials\HTMLForm\HTMLTrimmedTextField;

class SpecialNewPhrase extends SpecialPage {

	public static function factory() {
		return new self();
	}

	public function __construct() {
		parent::__construct( 'NewPhrase' );
	}

	public function execute( $subPage ) {
		parent::execute( $subPage );

		$form = $this->createForm();
		$form->prepareForm();

		/** @var Status|false $submitStatus `false` if form was not submitted */
		$submitStatus = $form->tryAuthorizedSubmit();

		if ( $submitStatus && $submitStatus->isGood() ) {
			$this->getOutput()->redirect( (\Title::newFromText( 'Special:RecentChanges' ))->getFullURL() );
			return;
		}

		$form->displayForm( $submitStatus ?: Status::newGood() );
	}

	/**
	 * @return HTMLForm
	 */
	private function createForm() {
		return HTMLForm::factory( 'ooui',
		[
			'language' => [
				'name' => 'language',
				'class' => HTMLTrimmedTextField::class,
				'id' => 'wb-newentity-language',
				'label-message' => 'wikibase-newentity-language-label',
			],
			'phrase' => [
				'name' => 'phrase',
				'class' => HTMLTrimmedTextField::class,
				'id' => 'wb-newentity-phrase',
				'label-message' => 'wikibase-newentity-phrase-label',
			],
		],
			$this->getContext()
			)
			->setId( 'mw-newentity-form1' )
			->setSubmitID( 'wb-newentity-submit' )
			->setSubmitName( 'submit' )
			->setSubmitTextMsg( 'wikibase-newentity-submit' )
			->setSubmitCallback(
				function ( $data, HTMLForm $form ) {
					$validationStatus = $this->validateFormData( $data );
					if ( !$validationStatus->isGood() ) {
						return $validationStatus;
					}

					$entity = new PhraseDocument( new PhraseId(uniqid( 'Phrase', true )), $data['language'], $data['phrase'] );

					\Wikibase\Repo\WikibaseRepo::getEntityStore()->saveEntity(
						$entity,
						"New phrase created in language " . $entity->getLanguage() . " with content " . $entity->getPhrase(),
						$this->getContext()->getUser(),
						EDIT_NEW
					);

					return Status::newGood( $entity );
				}
			);
	}

	protected function validateFormData( array $formData ) {
		$status = Status::newGood();
		if ( $formData[ 'language' ] == '') {
			$status->fatal( 'insufficient-data' );
		}
		if ( $formData[ 'phrase' ] == '') {
			$status->fatal( 'insufficient-data' );
		}
		return $status;
	}

}