<?php
/**
 * A MediaWiki extension to put headers and footers on a page.
 *
 * Copyright (C) 2017  Mark A. Hershberger
 * Copyright (C) 2013, 2014, 2015  James Montalvo
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace HeaderFooter;

use BaseTemplate;
use OutputPage;
use ParserOutput;
use SkinTemplate;
use Xml;

/**
 * @package HeaderFooter
 */
class Hook {
	/**
	 * Helper function to determine if the extension should alter this page.
	 *
	 * @param OutputPage $out Output page object for actions.
	 * @return bool
	 */
	protected static function shouldUse( OutputPage $out ) {
		$action = $out->parserOptions()->getUser()->getRequest()->getVal( "action" );
		if ( ( $action === 'edit' ) ||
			 ( $action === 'submit' ) ||
			 ( $action === 'history' )
		) {
			return false;
		}
		return true;
	}

	/**
	 * Hook to run to set top header stuff
	 *
	 * @param SkinTemplate $skin the skin obj
	 * @param BaseTemplate $tpl the template obj
	 * @return bool
	 */
	public static function onSkinTemplateOutputPageBeforeExec(
		SkinTemplate $skin,
		BaseTemplate $tpl
	) {
		$out = $skin->getOutput();
		if ( !self::shouldUse( $out ) ) {
			return true;
		}

		$namespace = $out->getTitle()->getNsText();
		$nsMsgText = wfMessage( 'hf-sticky-header-' . $namespace )->inContentLanguage();
		if ( $nsMsgText->isDisabled() ) {
			$nsMsgText = wfMessage( 'hf-sticky-header' )->inContentLanguage();
		}
		if ( !$nsMsgText->isDisabled() ) {
			$header = Xml::tags( "div", [ 'id' => 'hfStickyHeader' ],
									$nsMsgText->parse() );
			$tpl->set( 'sitenotice', $tpl->get( 'sitenotice' ) . $header );
		}

		return true;
	}

	/**
	 * The main hook that takes care of stuff
	 *
	 * @param OutputPage $out Output page object for context
	 * @param ParserOutput $pOut the ParserOutput object
	 * @return bool
	 */
	public static function onOutputPageParserOutput( OutputPage $out, ParserOutput $pOut ) {
		if ( !self::shouldUse( $out ) ) {
			return true;
		}
		global $wgUseStickyHeaders;
		if ( $wgUseStickyHeaders ) {
			$out->addModules( 'ext.HFsticky' );
		}

		$namespace = $out->getTitle()->getNsText();
		$name = $out->getTitle()->getPrefixedDBKey();

		$text = $pOut->getText();

		// There definitely needs to be a better way to do this.
		foreach ( [ 'header', 'footer' ] as $pos ) {
			foreach ( [ '', 'ns' ] as $ns ) {
				$word = '__NO' . strtoupper( "$ns$pos" ) . '__';
				$class = "hf-$ns$pos";
				$type = $ns === 'ns' ? $namespace : $name;
				$msg = wfMessage( "$class-$type" );

				$ret = self::conditionalInclude( $text, $word, $msg );
				if ( $ret && $pos === 'header' ) {
					$text = Xml::Element( "div", [ 'class' => $class ], $ret ) . $text;
				}
				if ( $ret && $pos === 'footer' ) {
					$text .= Xml::Element( "div", [ 'class' => $class ], $ret );
				}
			}
		}
		$pOut->setText( $text );

		return true;
	}

	/**
	 * Verifies & Strips ''disable command'', returns $content if all OK.
	 *
	 * @param string &$text to replace
	 * @param string $disableWord stop word to look for
	 * @param string &$msgId the message to use
	 * @return string
	 */
	public static function conditionalInclude( &$text, $disableWord, &$msgId ) {
		// is there a disable command lurking around?
		$disable = strpos( $text, $disableWord ) !== false;
		$msg = wfMessage( $msgId );

		// if there is a disable command, then don't return anything
		if ( $disable ) {
			// if there is, get rid of it
			$escWord = preg_quote( $disableWord, '/' );
			$text = preg_replace( "/$escWord/si", '', $text );
		}

		if ( $disable || $msg->isDisabled() || $msg->inContentLanguage()->isDisabled() ) {
			return false;
		}

		return $msg->parse();
	}
}
