<?php

declare( strict_types = 1 );

namespace Wikibase\View;

use Wikibase\DataModel\Term\AliasGroupList;
use Wikibase\DataModel\Term\TermList;
use Wikibase\Lib\LanguageNameLookup;
use Wikibase\View\Template\TemplateFactory;

/**
 * Generates HTML to display terms of an entity in a list.
 *
 * @license GPL-2.0-or-later
 */
class TermsListView {

	private TemplateFactory $templateFactory;

	private LanguageDirectionalityLookup $languageDirectionalityLookup;

	private LanguageNameLookup $languageNameLookup;

	private LocalizedTextProvider $textProvider;

	public function __construct(
		TemplateFactory $templateFactory,
		LanguageNameLookup $languageNameLookup,
		LocalizedTextProvider $textProvider,
		LanguageDirectionalityLookup $languageDirectionalityLookup
	) {
		$this->templateFactory = $templateFactory;
		$this->languageNameLookup = $languageNameLookup;
		$this->textProvider = $textProvider;
		$this->languageDirectionalityLookup = $languageDirectionalityLookup;
	}

	/**
	 * @param TermList $labels
	 * @param TermList $descriptions
	 * @param AliasGroupList|null $aliasGroups
	 * @param string[] $languageCodes The languages the user requested to be shown
	 *
	 * @return string HTML
	 */
	public function getHtml(
		TermList $labels,
		TermList $descriptions,
		?AliasGroupList $aliasGroups,
		array $languageCodes
	): string {
		$entityTermsForLanguageViewsHtml = '';

		foreach ( $languageCodes as $languageCode ) {
			$entityTermsForLanguageViewsHtml .= $this->getListItemHtml(
				$labels,
				$descriptions,
				$aliasGroups,
				$languageCode
			);
		}

		return $this->getListViewHtml( $entityTermsForLanguageViewsHtml );
	}

	/**
	 * @return string HTML
	 */
	public function getListViewHtml( string $contentHtml ): string {
		return $this->templateFactory->render(
			'wikibase-entitytermsforlanguagelistview',
			$this->textProvider->getEscaped( 'wikibase-entitytermsforlanguagelistview-language' ),
			$this->textProvider->getEscaped( 'wikibase-entitytermsforlanguagelistview-label' ),
			$this->textProvider->getEscaped( 'wikibase-entitytermsforlanguagelistview-description' ),
			$this->textProvider->getEscaped( 'wikibase-entitytermsforlanguagelistview-aliases' ),
			$contentHtml
		);
	}

	/**
	 * @return string HTML
	 */
	public function getListItemHtml(
		TermList $labels,
		TermList $descriptions,
		?AliasGroupList $aliasGroups,
		string $languageCode
	): string {
		$languageName = $this->languageNameLookup->getName( $languageCode );

		return $this->templateFactory->render(
			'wikibase-entitytermsforlanguageview',
			'tr',
			'td',
			$languageCode,
			htmlspecialchars( $languageName ),
			$this->getLabelView(
				$labels,
				$languageCode
			),
			$this->getDescriptionView(
				$descriptions,
				$languageCode
			),
			$aliasGroups ? $this->getAliasesView( $aliasGroups, $languageCode ) : '',
			'',
			'th'
		);
	}

	private function getLabelView( TermList $listOfLabelTerms, string $languageCode ): string {
		$hasLabelInLanguage = $listOfLabelTerms->hasTermForLanguage( $languageCode );
		$effectiveLanguage = $hasLabelInLanguage ? $languageCode : $this->textProvider->getLanguageOf( 'wikibase-label-empty' );
		return $this->templateFactory->render(
			'wikibase-labelview',
			$hasLabelInLanguage ? '' : 'wb-empty',
			htmlspecialchars( $hasLabelInLanguage
				? $listOfLabelTerms->getByLanguage( $languageCode )->getText()
				: $this->textProvider->get( 'wikibase-label-empty' )
			),
			'',
			$this->languageDirectionalityLookup->getDirectionality( $effectiveLanguage ) ?: 'auto',
			$effectiveLanguage
		);
	}

	private function getDescriptionView( TermList $listOfDescriptionTerms, string $languageCode ): string {
		$hasDescriptionInLanguage = $listOfDescriptionTerms->hasTermForLanguage( $languageCode );
		$effectiveLanguage = $hasDescriptionInLanguage ? $languageCode : $this->textProvider->getLanguageOf( 'wikibase-description-empty' );
		return $this->templateFactory->render(
			'wikibase-descriptionview',
			$hasDescriptionInLanguage ? '' : 'wb-empty',
			htmlspecialchars( $hasDescriptionInLanguage
				? $listOfDescriptionTerms->getByLanguage( $languageCode )->getText()
				: $this->textProvider->get( 'wikibase-description-empty' )
			),
			'',
			$this->languageDirectionalityLookup->getDirectionality( $effectiveLanguage ) ?: 'auto',
			$effectiveLanguage
		);
	}

	/**
	 * @return string HTML
	 */
	private function getAliasesView( AliasGroupList $aliasGroups, string $languageCode ): string {
		if ( !$aliasGroups->hasGroupForLanguage( $languageCode ) ) {
			return $this->templateFactory->render(
				'wikibase-aliasesview',
				'wb-empty',
				'',
				'',
				// FIXME: Ideally we would not emit dir and lang attributes at all here
				'', // Empty dir attribute is considered invalid and thus the element inherits dir
				$languageCode // Empty lang attribute would mean "explicitly unknown language"
			);
		} else {
			$aliasesHtml = '';
			$aliases = $aliasGroups->getByLanguage( $languageCode )->getAliases();
			foreach ( $aliases as $alias ) {
				$aliasesHtml .= $this->templateFactory->render(
					'wikibase-aliasesview-list-item',
					htmlspecialchars( $alias )
				);
			}

			return $this->templateFactory->render(
				'wikibase-aliasesview',
				'',
				$aliasesHtml,
				'',
				$this->languageDirectionalityLookup->getDirectionality( $languageCode ) ?: 'auto',
				$languageCode
			);
		}
	}

}
