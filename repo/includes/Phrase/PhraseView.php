<?php

namespace Wikibase\Repo\Phrase;

use Wikibase\View\EntityDocumentView;
use Wikibase\View\ViewContent;

class PhraseView implements EntityDocumentView {

	function getTitleHtml(\Wikibase\DataModel\Entity\EntityDocument $entity) {
		return "Title of " . $entity->getId()->getSerialization();
	}

	function getContent(\Wikibase\DataModel\Entity\EntityDocument $entity, $revision): ViewContent {
		/* @var PhraseDocument $entity */
		return new ViewContent(
			"Language: " . $entity->getLanguage() . "<br>Phrase: " . $entity->getPhrase(),
			[]
		);
	}
}