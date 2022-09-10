<?php

namespace Wikibase\Repo\OneString;

use \Language;
use Wikibase\View\EntityDocumentView;
use Wikibase\View\EntityView;
use Wikibase\View\ViewContent;
use Wikibase\Repo\ParserOutput\EntityTermsViewFactory;
use Wikibase\Repo\ParserOutput\TermboxFlag;
use Wikibase\Lib\TermLanguageFallbackChain;
use Wikibase\DataModel\Entity\EntityDocument;
use Wikibase\View\Template\TemplateFactory;
use Wikibase\Repo\MediaWikiLanguageDirectionalityLookup;

/**
 * Did extend EntityDocumentView
 * But Switched to EntityView as we want "all the JS" to load, for termbox...
 */
class OneStringView extends EntityView {

	private $language;
	private $fallbackChain;
	private $entity;

	private $entityTermsView;

	public function __construct(
		Language $language,
		TermLanguageFallbackChain $fallbackChain,
		EntityDocument $entity
	){
		$this->language = $language;
		$this->fallbackChain = $fallbackChain;
		$this->entity = $entity;
		parent::__construct(
			TemplateFactory::getDefaultInstance(),
			new MediaWikiLanguageDirectionalityLookup(),
			$this->language->getCode()
		);

		// TODO this should actually be injected, but I want to keep the Definitions thin and easy to follow for now..
		$this->entityTermsView = ( new EntityTermsViewFactory() )
		->newEntityTermsView(
			$this->entity,
			$this->language,
			$this->fallbackChain,
			TermboxFlag::getInstance()->shouldRenderTermbox()
		);
	}

	function getTitleHtml(\Wikibase\DataModel\Entity\EntityDocument $entity) {
		return "On page Title of " . $entity->getId()->getSerialization() . "(" . $entity->getFingerprint()->getLabels()->getByLanguage( 'en' )->getText() . ")";
	}

	function getMainHtml(\Wikibase\DataModel\Entity\EntityDocument $entity) {
		/* @var OneString $entity */

		$termsHtml = $this->entityTermsView->getHtml(
			$this->language->getCode(),
			$entity->getLabels(),
			$entity->getDescriptions(),
			$entity->getAliasGroups(),
			$entity->getId()
		);

		return $termsHtml .
		PHP_EOL . "</br>" . PHP_EOL .
		"String contents is: " . $entity->getContent() .
		PHP_EOL . "</br>" . PHP_EOL .
		PHP_EOL . "</br>" . PHP_EOL .
		"Some raw serialization is: " . serialize($entity);
	}

	function getSideHtml(\Wikibase\DataModel\Entity\EntityDocument $entity) {
		return "";
	}

	function getContent(\Wikibase\DataModel\Entity\EntityDocument $entity, $revision): \Wikibase\View\ViewContent {
		return new ViewContent(
			$this->renderEntityView( $entity ),
			$this->entityTermsView->getPlaceholders( $entity, $revision, $this->languageCode )
		);
	}
}
