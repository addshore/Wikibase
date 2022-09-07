<?php

namespace Wikibase\Repo\OneString;

use Wikibase\View\EntityDocumentView;
use Wikibase\View\ViewContent;

class OneStringView implements EntityDocumentView {

	function getTitleHtml(\Wikibase\DataModel\Entity\EntityDocument $entity) {
		return "Title of " . $entity->getId()->getSerialization();
	}

	function getContent(\Wikibase\DataModel\Entity\EntityDocument $entity, $revision): \Wikibase\View\ViewContent {
		/* @var OneString $entity */
		return new ViewContent(
			"String contents is: " . $entity->getContent(),
			[]
		);
	}
}
