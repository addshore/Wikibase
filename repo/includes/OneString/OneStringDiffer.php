<?php

namespace Wikibase\Repo\OneString;

use Diff\DiffOp\Diff\Diff;
use Diff\DiffOp\DiffOpAdd;
use Diff\DiffOp\DiffOpChange;
use Diff\DiffOp\DiffOpRemove;
use Wikibase\DataModel\Entity\EntityDocument;

class OneStringDiffer implements \Wikibase\DataModel\Services\Diff\EntityDifferStrategy {

	public function canDiffEntityType( $entityType ) {
		return $entityType === OneStringConstants::ENTITY_TYPE;
	}

	public function diffEntities( EntityDocument $from, EntityDocument $to ) {
		$dops = [];

		if ($from->isEmpty() && !$to->isEmpty()) {
			$dops['content'] = new DiffOpAdd( $to->getContent() );
		} elseif (!$from->isEmpty() && $to->isEmpty()) {
			$dops['content'] = new DiffOpRemove( $from->getContent() );
		} elseif (!$from->isEmpty() && !$to->isEmpty()) {
			$dops['content'] = new DiffOpChange( $from->getContent(), $to->getContent() );
		}

		return new OneStringDiff($dops);
	}

	public function getConstructionDiff( EntityDocument $entity ) {
		return new OneStringDiff(['content' => new DiffOpAdd( $entity->getContent() ) ] );
	}

	public function getDestructionDiff( EntityDocument $entity ) {
		return new OneStringDiff(['content' => new DiffOpRemov( $entity->getContent() ) ] );
	}

}
