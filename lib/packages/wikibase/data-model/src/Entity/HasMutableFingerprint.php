<?php

namespace Wikibase\DataModel\Entity;

use Wikibase\DataModel\Term\Fingerprint;

trait HasMutableFingerprint {

	private $fingerprint;

	/**
	 * @return Fingerprint
	 */
	public function getFingerprint() {
		return $this->fingerprint;
	}

	/**
	 * @param Fingerprint $fingerprint
	 */
	public function setFingerprint( Fingerprint $fingerprint ) {
		$this->fingerprint = $fingerprint;
	}

	/**
	 * @see LabelsProvider::getLabels
	 *
	 * @return TermList
	 */
	public function getLabels() {
		return $this->fingerprint->getLabels();
	}

	/**
	 * @see DescriptionsProvider::getDescriptions
	 *
	 * @return TermList
	 */
	public function getDescriptions() {
		return $this->fingerprint->getDescriptions();
	}

	/**
	 * @see AliasesProvider::getAliasGroups
	 *
	 * @return AliasGroupList
	 */
	public function getAliasGroups() {
		return $this->fingerprint->getAliasGroups();
	}

	/**
	 * @param string $languageCode
	 * @param string $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function setLabel( $languageCode, $value ) {
		$this->fingerprint->setLabel( $languageCode, $value );
	}

	/**
	 * @param string $languageCode
	 * @param string $value
	 *
	 * @throws InvalidArgumentException
	 */
	public function setDescription( $languageCode, $value ) {
		$this->fingerprint->setDescription( $languageCode, $value );
	}

	/**
	 * @param string $languageCode
	 * @param string[] $aliases
	 *
	 * @throws InvalidArgumentException
	 */
	public function setAliases( $languageCode, array $aliases ) {
		$this->fingerprint->setAliasGroup( $languageCode, $aliases );
	}

}
