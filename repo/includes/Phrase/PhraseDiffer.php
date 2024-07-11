<?php

namespace Wikibase\Repo\Phrase;

use Diff\DiffOp\DiffOpAdd;
use Diff\DiffOp\DiffOpChange;
use Diff\DiffOp\DiffOpRemove;
use Wikibase\DataModel\Entity\EntityDocument;

class PhraseDiffer implements \Wikibase\DataModel\Services\Diff\EntityDifferStrategy {

	public function canDiffEntityType( $entityType ) {
		return $entityType === PhraseDocument::TYPE;
	}

	public function diffEntities( EntityDocument $from, EntityDocument $to ) {
		$dops = [];

		if ($from->isEmpty() && !$to->isEmpty()) {
			$dops['language'] = new DiffOpAdd( $to->getLanguage() );
			$dops['phrase'] = new DiffOpAdd( $to->getPhrase() );
		} elseif (!$from->isEmpty() && $to->isEmpty()) {
			$dops['language'] = new DiffOpRemove( $from->getLanguage() );
			$dops['phrase'] = new DiffOpRemove( $from->getPhrase() );
		} elseif (!$from->isEmpty() && !$to->isEmpty()) {
            if ($from->getLanguage() !== $to->getLanguage()) {
                $dops['language'] = new DiffOpChange( $from->getLanguage(), $to->getLanguage() );
            }
            if ($from->getPhrase() !== $to->getPhrase()) {
                $dops['phrase'] = new DiffOpChange( $from->getPhrase(), $to->getPhrase() );
            }
		}

		return new PhraseDiff($dops);
	}

	public function getConstructionDiff( EntityDocument $entity ) {
        return new PhraseDiff([
            'language' => new DiffOpAdd( $entity->getLanguage() ),
            'phrase' => new DiffOpAdd( $entity->getPhrase() )
        ]);
	}

	public function getDestructionDiff( EntityDocument $entity ) {
        return new PhraseDiff([
            'language' => new DiffOpRemove( $entity->getLanguage() ),
            'phrase' => new DiffOpRemove( $entity->getPhrase() )
        ]);
	}

}