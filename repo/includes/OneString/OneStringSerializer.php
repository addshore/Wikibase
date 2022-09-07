<?php

namespace Wikibase\Repo\OneString;

class OneStringSerializer implements \Serializers\DispatchableSerializer {

	public function isSerializerFor( $object ) {
		return $object instanceof OneString;

	}

	public function serialize( $object ) {
		$serialization = [
			'id' => $object->getId()->getSerialization(),
			'type' => OneStringConstants::ENTITY_TYPE,
			'content' => $object->getContent(),
		];

		$termListSerializer = \Wikibase\Repo\WikibaseRepo::getBaseDataModelSerializerFactory()->newTermListSerializer();
		$aliasGroupListSerializer = \Wikibase\Repo\WikibaseRepo::getBaseDataModelSerializerFactory()->newAliasGroupListSerializer();

		// This is not currently reusable, but stolen directly from ItemSerializer...
		// I could have put this all under a `fingerprint` key, but decided to be consistent with other entities for now...
		$fingerprint = $object->getFingerprint();
		$serialization['labels'] = $termListSerializer->serialize( $fingerprint->getLabels() );
		$serialization['descriptions'] =
			$termListSerializer->serialize( $fingerprint->getDescriptions() );
		$serialization['aliases'] =
			$aliasGroupListSerializer->serialize( $fingerprint->getAliasGroups() );

		return $serialization;
	}

}
