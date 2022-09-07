<?php

namespace Wikibase\Repo\OneString;

use Deserializers\TypedObjectDeserializer;
use Wikibase\DataModel\Term\Fingerprint;

class OneStringDeserializer extends TypedObjectDeserializer {

	public function __construct() {
		// This demonstrates why the type is generally stored in serialization (because of all of the dispatching)
		parent::__construct(OneStringConstants::ENTITY_TYPE,'type');
	}

	public function deserialize( $serialization ) {
		// This is not currently reusable, but stolen directly from ItemDeserializer...
		$termListDeserializer = \Wikibase\Repo\WikibaseRepo::getBaseDataModelDeserializerFactory()->newTermListDeserializer();
		$aliasGroupListDeserializer = \Wikibase\Repo\WikibaseRepo::getBaseDataModelDeserializerFactory()->newAliasGroupListDeserializer();

		$labels = $termListDeserializer->deserialize( $serialization['labels'] );
		$descriptions = $termListDeserializer->deserialize( $serialization['descriptions'] );
		$aliases = $aliasGroupListDeserializer->deserialize( $serialization['aliases'] );

		return new OneString(
			// This demonstrates how storing the ID in the entity serialization can be a shortcut
			new OneStringId($serialization['id']),
			$serialization['content'],
			new Fingerprint($labels,$descriptions,$aliases)
		);
	}

}
