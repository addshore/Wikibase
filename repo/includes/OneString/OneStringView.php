<?php

namespace Wikibase\Repo\OneString;

use Wikibase\View\EntityDocumentView;
use Wikibase\View\ViewContent;

class OneStringView implements EntityDocumentView {

	function getTitleHtml(\Wikibase\DataModel\Entity\EntityDocument $entity) {
		return "On page Title of " . $entity->getId()->getSerialization() . "(" . $entity->getFingerprint()->getLabels()->getByLanguage( 'en' )->getText() . ")";
	}

	function getContent(\Wikibase\DataModel\Entity\EntityDocument $entity, $revision): \Wikibase\View\ViewContent {
		/* @var OneString $entity */
		return new ViewContent(
			"String en label is: " . $entity->getFingerprint()->getLabels()->getByLanguage( 'en' )->getText() .
			PHP_EOL . "</br>" . PHP_EOL .
			"String contents is: " . $entity->getContent() .
			PHP_EOL . "</br>" . PHP_EOL .
			PHP_EOL . "</br>" . PHP_EOL .
			"Some raw serialization is: " . serialize($entity),
			[]
		);
	}
}
