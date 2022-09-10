<?php

namespace Wikibase\Repo\OneString;

use Wikibase\DataModel\Entity\EntityId;

class OneStringId implements EntityId {

	private string $id;

	public function __construct( string $id ) {
		$this->id = $id;
	}

	public function getEntityType() {
		return OneStringConstants::ENTITY_TYPE;
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

	/**
	 * TODO: This method shouldn't exist on this interface as it doesn't make sense for certain types of IDs. It should be moved to a
	 *       separate interface, or removed altogether.
	 *
	 * @return string
	 */
	public function getLocalPart() {
		return $this->getSerialization();
	}

	/**
	 * TODO: This method shouldn't exist on this interface as it doesn't make sense for certain types of IDs. It should be moved to a
	 *       separate interface, or removed altogether.
	 *
	 * @return string
	 */
	public function getRepositoryName() {
		throw new \Exception( 'Not implemented' );
	}

	public function serialize(): string {
		return $this->id;
	}

	public function unserialize($data): void {
		$this->id = $data;
	}

}
