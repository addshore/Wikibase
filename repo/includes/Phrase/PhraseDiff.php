<?php

namespace Wikibase\Repo\Phrase;

use Diff\DiffOp\Diff\Diff;
use Diff\DiffOp\DiffOp;
use Wikibase\DataModel\Services\Diff\EntityDiff;

class PhraseDiff extends EntityDiff {

	public function __construct( array $operations = [] ) {
		parent::__construct( $operations );
	}

	public function getContentDiff() {
		return $this['content'] ?? new Diff( [], true );
	}

	public function isEmpty(): bool {
		// FIXME: Needs to be fixed, otherwise conflict resolution may lead to unexpected results
		return $this->getContentDiff()->isEmpty();
	}

	public function toArray( callable $valueConverter = null ): array {
		throw new \LogicException( 'toArray() is not implemented' );
	}

}