<?php

namespace Wikibase\Repo\Phrase;

use Wikibase\DataModel\Entity\EntityDocument;

class PhraseDocument implements EntityDocument {

	const TYPE = 'phrase';

	private $id;
	private $language;
	private $phrase;

	public function __construct( PhraseId $id = null, string $language = 'en', string $phrase = '' ) {
		$this->id = $id;
		$this->language = $language;
		$this->phrase = $phrase;
	}

	public function getType() {
		return self::TYPE;
	}

	public function getId() {
		return $this->id;
	}

	public function setId( $id ) {
		if ( $id instanceof PhraseId ) {
			$this->id = $id;
		} else {
			throw new \InvalidArgumentException( 'Invalid id type' );
		}
	}

	public function isEmpty() {
		return $this->phrase === '';
	}

	public function equals( $target ) {
		return $target instanceof self && $this->language === $target->language && $this->phrase === $target->phrase;
	}

	public function copy() {
		return new self( clone $this->id, $this->language, $this->phrase );
	}

	public function getLanguage() {
		return $this->language;
	}

	public function getPhrase() {
		return $this->phrase;
	}

}
