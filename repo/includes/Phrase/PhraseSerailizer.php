<?php

namespace Wikibase\Repo\Phrase;

class PhraseSerailizer implements \Serializers\DispatchableSerializer {

	public function isSerializerFor( $object ) {
		return $object instanceof PhraseDocument;

	}

	public function serialize( $object ) {
		return [
			'id' => $object->getId()->getSerialization(),
			'type' => $object->getType(),
			'language' => $object->getLanguage(),
			'phrase' => $object->getPhrase(),
		];
	}

}