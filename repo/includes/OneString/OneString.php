<?php

namespace Wikibase\Repo\OneString;

use Wikibase\DataModel\Entity\EntityDocument;

/**
 * A basic entity that just takes any string.
 */
class OneString implements EntityDocument {

	private $id;
	private $content;

	public function __construct(OneStringId $id = null, string $content = '') {
		$this->id = $id;
		$this->content = $content;
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
		return $this->content === '';
	}

	public function equals( $target ) {
		return $target instanceof self && $this->content === $target->content;
	}

	public function copy() {
		return new self( clone $this->id, $this->content );
	}

	public function getContent() {
		return $this->content;
	}

}
