<?php

namespace Wikibase\Repo\OneString;

use \Wikibase\Repo\Content\EntityHolder;

class OneStringContentHandler extends \Wikibase\Repo\Content\EntityHandler {

	public function getSpecialPageForCreation() {
		return 'OneStringSpecialCreate';
	}

	public function getEntityType() {
		return OneStringConstants::ENTITY_TYPE;
	}

	public function makeEmptyEntity() {
		return new OneString();
	}

	protected function newEntityContent( EntityHolder $entityHolder = null ) {
		return new OneStringContent( $entityHolder );
	}

	public function makeEntityId( $id ) {
		return new OneStringId( $id );
	}
}
