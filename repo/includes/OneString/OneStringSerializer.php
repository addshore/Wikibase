<?php

namespace Wikibase\Repo\OneString;

class OneStringSerializer implements \Serializers\DispatchableSerializer {

	public function isSerializerFor( $object ) {
		return $object instanceof OneString;

	}

	public function serialize( $object ) {
		return [
			'id' => $object->getId()->getSerialization(),
			'type' => OneStringConstants::ENTITY_TYPE,
			'content' => $object->getContent(),
		];
	}

}
