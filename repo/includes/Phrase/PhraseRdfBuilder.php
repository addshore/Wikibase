<?php

namespace Wikibase\Repo\Phrase;

use Wikibase\Repo\Rdf\EntityRdfBuilder;
use Wikibase\Repo\Rdf\RdfVocabulary;
use Wikimedia\Purtle\RdfWriter;
use Wikibase\DataModel\Entity\EntityDocument;

class PhraseRdfBuilder implements EntityRdfBuilder {

    private $vocabulary;
    private $writer;

	public function __construct(
		RdfVocabulary $vocabulary,
		RdfWriter $writer
	) {
		$this->vocabulary = $vocabulary;
		$this->writer = $writer;
	}

	public function addEntity( EntityDocument $entity ){
		// Stolen from TermsRdfBuilder::getLabelPredicates
		$labelPredicates = [
			[ 'rdfs', 'label' ],
			[ RdfVocabulary::NS_SKOS, 'prefLabel' ],
			[ RdfVocabulary::NS_SCHEMA_ORG, 'name' ],
		];

		for ( $i = 0; $i < count( $labelPredicates ); $i++ ) {
			$this->writer->say( $labelPredicates[$i][0], $labelPredicates[$i][1] )->text( $entity->getPhrase(), $entity->getLanguage() );
		}
	}
}