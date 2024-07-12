<?php

namespace Wikibase\Repo\Phrase;

use Wikibase\DataModel\Entity\EntityId;

class PhraseId implements EntityId {

	private string $id;

	public function __construct( string $id ) {
		$this->id = $id;
	}

	public static function newRandom() {
		$s = uniqid( 'PH', true );
		// Remove the dot, as it is not allowed in entity IDs
		$s = str_replace( '.', '', $s );
		// Uppercase it
		$s = strtoupper( $s );
		return new self( $s );
	}

	public function getEntityType() {
		return PhraseDocument::TYPE;
	}

	public function getSerialization() {
		return $this->id;
	}

	public function __toString() {
		return $this->id;
	}

	public function equals( $target ) {
		return $target instanceof self && $this->id === $target->id;
	}

	
	public function __serialize(): array {
		return [ 'serialization' => $this->id ];
	}

	public function __unserialize( array $data ): void {
		$this->__construct( $data['serialization'] );
		if ( $this->id !== $data['serialization'] ) {
			throw new \InvalidArgumentException( '$data contained invalid serialization' );
		}
	}

}