<?php

namespace Wikibase\Repo\OneString;

use \Wikibase\Repo\Content\EntityHolder;
use Wikibase\Repo\Actions\EditEntityAction;
use Wikibase\Repo\Actions\HistoryEntityAction;
use Wikibase\Repo\Actions\SubmitEntityAction;
use Wikibase\Repo\Actions\ViewEntityAction;

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

		/**
	 * @return (\Closure|class-string)[]
	 */
	public function getActionOverrides() {
		return [
			// View action is needed to allow JS to load?
			// But we could also create a custom action for this rather than reusing ViewEntityAction
			'view' => ViewEntityAction::class,
		];
	}
}
