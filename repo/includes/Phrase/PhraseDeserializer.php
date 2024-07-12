<?php

namespace Wikibase\Repo\Phrase;

use Deserializers\TypedObjectDeserializer;

class PhraseDeserializer extends TypedObjectDeserializer {

	public function __construct() {
		parent::__construct(PhraseDocument::TYPE,'type');
	}

	public function deserialize( $serialization ) {
		return new PhraseDocument(
			new PhraseId($serialization['id']),
			$serialization['language'],
			$serialization['phrase']
		);
	}

}