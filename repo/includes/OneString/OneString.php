<?php

namespace Wikibase\Repo\OneString;

use Wikibase\DataModel\Entity\EntityDocument;
use Wikibase\DataModel\Entity\HasMutableFingerprint;
use Wikibase\DataModel\Term\FingerprintProvider;
use Wikibase\DataModel\Term\LabelsProvider;
use Wikibase\DataModel\Term\DescriptionsProvider;
use Wikibase\DataModel\Term\AliasesProvider;
use Wikibase\DataModel\Term\Fingerprint;

/**
 * A basic entity that just takes any string.
 */
class OneString implements EntityDocument, FingerprintProvider, LabelsProvider, DescriptionsProvider, AliasesProvider {

	use HasMutableFingerprint;

	private $id;
	private $content;

	public function __construct( OneStringId $id = null, string $content = '', Fingerprint $fingerprint = null ) {
		$this->id = $id;
		$this->content = $content;
		$this->fingerprint = $fingerprint ?: new Fingerprint();
	}

	public function getType() {
		return OneStringConstants::ENTITY_TYPE;
	}

	public function getId() {
		return $this->id;
	}

	public function setId( $id ) {
		if ( $id instanceof OneStringId ) {
			$this->id = $id;
		} else {
			throw new \InvalidArgumentException( 'Invalid id type' );
		}
	}

	public function isEmpty() {
		return $this->content === '' && $this->fingerprint->isEmpty();
	}

	public function equals( $target ) {
		return $target instanceof self &&
			$this->content === $target->content &&
			$this->fingerprint->equals( $target->fingerprint );
	}

	public function copy() {
		return new self( clone $this->id, $this->content, clone $this->fingerprint );
	}

	public function getContent() {
		return $this->content;
	}

}
