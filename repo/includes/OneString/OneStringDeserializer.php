<?php

namespace Wikibase\Repo\OneString;

use Deserializers\TypedObjectDeserializer;

class OneStringDeserializer extends TypedObjectDeserializer {

	public function __construct() {
		// This demonstrates why the type is generally stored in serialization (because of all of the dispatching)
		parent::__construct(OneStringConstants::ENTITY_TYPE,'type');
	}

	public function deserialize( $serialization ) {
		return new OneString(
			// This demonstrates how storing the ID in the entity serialization can be a shortcut
			new OneStringId($serialization['id']),
			$serialization['content']
		);
	}

}
