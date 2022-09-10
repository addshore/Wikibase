<?php

namespace Wikibase\Repo\OneString;

use Wikibase\Repo\Hooks\Formatters\EntityLinkFormatter;

class OneStringEntityLinkFormatter implements EntityLinkFormatter {

	function getHtml(\Wikibase\DataModel\Entity\EntityId $entityId, array $labelData = null) {
		return "Formatted MW Link ID: " . $entityId->getSerialization();;
	}

	function getTitleAttribute(\Wikibase\DataModel\Entity\EntityId $entityId, array $labelData = null, array $descriptionData = null) {
		return "some-title-attrib";
	}

	function getFragment(\Wikibase\DataModel\Entity\EntityId $entityId, $fragment) {
		return $fragment;
	}
}
