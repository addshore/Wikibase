<?php

namespace Wikibase\Repo\OneString;

use SpecialPage;
use Status;
use HTMLForm;
use Wikibase\Repo\Specials\HTMLForm\HTMLTrimmedTextField;
use Wikibase\DataModel\Term\Fingerprint;
use Wikibase\DataModel\Term\TermList;

class OneStringSpecialCreate extends SpecialPage {

	public static function factory() {
		return new self();
	}

	public function __construct() {
		parent::__construct( 'OneStringSpecialCreate' );
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

	private function createForm() {
		return HTMLForm::factory( 'ooui',
		[
			'enlabel' => [
				'label' => 'enlabel',
				'name' => 'enlabel',
				'class' => HTMLTrimmedTextField::class,
				'id' => 'wb-newentity-enlabel',
			],
			'content' => [
				'label' => 'content',
				'name' => 'content',
				'class' => HTMLTrimmedTextField::class,
				'id' => 'wb-newentity-content',
			]
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

					$entity = new OneString(
						new OneStringId(bin2hex(random_bytes(16))),
						$data['content'],
						new Fingerprint(
							new TermList([new \Wikibase\DataModel\Term\Term('en', $data['enlabel'])])
						)
					);
					$summary = "OneString created";

					\Wikibase\Repo\WikibaseRepo::getEntityStore()->saveEntity(
						$entity,
						$summary,
						$this->getContext()->getUser(),
						EDIT_NEW
					);

					return Status::newGood( $entity );
				}
			);
	}

	protected function validateFormData( array $formData ) {
		$status = Status::newGood();
		if ( $formData[ 'content' ] == '') {
			$status->fatal( 'insufficient-data' );
		}
		return $status;
	}

}
