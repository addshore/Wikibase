<?php

namespace Wikibase\Repo\Phrase;

use \Wikibase\Repo\Content\EntityHolder;
use Wikibase\DataModel\Entity\EntityId;

class PhraseContentHandler extends \Wikibase\Repo\Content\EntityHandler {

	public function getEntityType() {
		return PhraseDocument::TYPE;
	}

	public function makeEmptyEntity() {
		return new PhraseDocument();
	}

	protected function newEntityContent( EntityHolder $entityHolder = null ) {
		return new PhraseContent( $entityHolder );
	}

	public function makeEntityId( $id ) {
		return new PhraseId( $id );
	}

}