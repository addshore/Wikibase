<?php

namespace Wikibase\Repo\Validators;

use Parser;
use ValueValidators\ValueValidator;

/**
 * UrlSchemeValidators is a collection of validators for some commonly used URL schemes.
 * This is intended for conveniently supplying a map of validators to UrlValidator.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 * @author Thiemo Mättig
 */
class UrlSchemeValidators {

	/**
	 * Returns a validator for the given URL scheme, or null if
	 * no validator is defined for that scheme.
	 *
	 * @todo 'bitcoin', 'geo', 'magnet', 'news', 'sip', 'sips', 'sms', 'tel', 'urn', 'xmpp'.
	 * @todo protocol relative '//'.
	 *
	 * @param string $scheme e.g. 'http'.
	 *
	 * @return ValueValidator|null
	 */
	public function getValidator( $scheme ) {
		switch ( $scheme ) {
			case 'ftp':
			case 'ftps':
			case 'git':
			case 'gopher':
			case 'http':
			case 'https':
			case 'irc':
			case 'ircs':
			case 'mms':
			case 'nntp':
			case 'redis':
			case 'sftp':
			case 'ssh':
			case 'svn':
			case 'telnet':
			case 'worldwind':
				$regex = '!^' . preg_quote( $scheme, '!' ) . '://(' . Parser::EXT_LINK_URL_CLASS . ')+$!ui';
				break;

			case 'mailto':
				$regex = '!^mailto:(' . Parser::EXT_LINK_URL_CLASS . ')+@(' . Parser::EXT_LINK_URL_CLASS . ')+$!ui';
				break;

			case '*':
			case 'any':
				$regex = '!^([a-z][a-z\d+.-]*):(' . Parser::EXT_LINK_URL_CLASS . ')+$!ui';
				break;

			default:
				return null;
		}

		return new RegexValidator( $regex, false, 'bad-url' );
	}

	/**
	 * Given a list of schemes, this function returns a mapping for each supported
	 * scheme to a corresponding ValueValidator. If the schema isn't supported,
	 * no mapping is created for it.
	 *
	 * @param string[] $schemes a list of scheme names, e.g. 'http'.
	 *
	 * @return ValueValidator[] a map of scheme names to ValueValidator objects.
	 */
	public function getValidators( array $schemes ) {
		$validators = array();

		foreach ( $schemes as $scheme ) {
			$validator = $this->getValidator( $scheme );

			if ( $validator !== null ) {
				$validators[$scheme] = $validator;
			}
		}

		return $validators;
	}

}
