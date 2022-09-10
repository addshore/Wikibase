<?php

namespace Wikibase\Repo\OneString;

use \Wikibase\Repo\Content\EntityHolder;
use \Wikibase\Repo\Content\EntityContent;

class OneStringContent extends EntityContent {

	private $holder;

	public function __construct(
		EntityHolder $holder
	) {
		parent::__construct( OneStringConstants::CONTENT_ID);
		$this->holder = $holder;
	}

	public function getEntity() {
		return $this->holder->getEntity();
	}

	public function getEntityHolder() {
		return $this->holder;
	}

	public function getTextForSearchIndex() {
		return $this->holder->getEntity()->getContent();
	}

	public function isEmpty() {
		return ( !$this->holder || $this->getEntity()->isEmpty() );
	}

	public function getIgnoreKeysForFilters() {
		return ['id', 'type'];
	}
}
