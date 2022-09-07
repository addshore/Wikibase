<?php

namespace Wikibase\Repo\OneString;

use Wikibase\Repo\Rdf\EntityRdfBuilder;
use Wikibase\Repo\Rdf\RdfVocabulary;
use Wikimedia\Purtle\RdfWriter;
use Wikibase\DataModel\Entity\EntityDocument;
use Wikibase\Repo\Rdf\TermsRdfBuilder;
use Wikibase\Lib\EntityTypeDefinitions;

class OneStringRdfBuilder implements EntityRdfBuilder {

	public function __construct(
		RdfVocabulary $vocabulary,
		RdfWriter $writer
	) {
		$this->vocabulary = $vocabulary;
		$this->writer = $writer;
	}

	public function addEntity( EntityDocument $entity ){

		// I'll avoid adding "content" to the RDF here, which is probably similar to what would happen for EntitySchema
		// as it is just a big blob of stuff
		// However, in the context of EntitySchema for example, we could extract or infer triples to add to the RDF
		// these could for example be other entities / properties that are refered to as part of the shape

		$entityTypeDefinitions = \Wikibase\Repo\WikibaseRepo::getEntityTypeDefinitions( \MediaWiki\MediaWikiServices::getInstance() );
		$termsRdfBuilder = new TermsRdfBuilder(
			$this->vocabulary,
			$this->writer,
			$entityTypeDefinitions->get( EntityTypeDefinitions::RDF_LABEL_PREDICATES )
		);
		$termsRdfBuilder->addEntity( $entity );
	}
}
