<?php

namespace Wikibase\Repo\Phrase;

use \Wikibase\Repo\Content\EntityHolder;
use \Wikibase\Repo\Content\EntityContent;

class PhraseContent extends EntityContent {

    const ID = 'phrase';

	private $holder;

	public function __construct(
		EntityHolder $holder
	) {
		parent::__construct( PhraseContent::ID );
		$this->holder = $holder;
	}

	public function getEntity() {
		return $this->holder->getEntity();
	}

	public function getEntityHolder() {
		return $this->holder;
	}

	public function getTextForSearchIndex() {
		return $this->holder->getEntity()->getPhrase();
	}

	public function isEmpty() {
		return ( !$this->holder || $this->getEntity()->isEmpty() );
	}

	public function getIgnoreKeysForFilters() {
		return ['id', 'type'];
	}
}