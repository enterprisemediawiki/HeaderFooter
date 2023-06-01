<?php
/**
 * @package HeaderFooter
 */
class HeaderFooter
{
	/**
	 * Main Hook
	 * @param OutputPage $op
	 * @param ParserOutput $parserOutput
	 */
	public static function hOutputPageParserOutput( &$op, $parserOutput ) {

		$action = $op->getRequest()->getVal("action");
		if ( ($action == 'edit') || ($action == 'submit') || ($action == 'history') ) {
			return true;
		}

		global $wgTitle;

		$ns = $wgTitle->getNsText();
		$name = $wgTitle->getPrefixedDBKey();

		$nsheader = self::conditionalInclude( 'hf_nsheader', 'hf-nsheader', $ns, $parserOutput );
		$header   = self::conditionalInclude( 'hf_header', 'hf-header', $name, $parserOutput );
		$footer   = self::conditionalInclude( 'hf_footer', 'hf-footer', $name, $parserOutput );
		$nsfooter = self::conditionalInclude( 'hf_nsfooter', 'hf-nsfooter', $ns, $parserOutput );

		// Grab only raw text to prevent doubled parser-output class
		$text = $parserOutput->getRawText();
		$parserOutput->setText( $nsheader . $header . $text . $footer . $nsfooter );

		global $egHeaderFooterEnableAsyncHeader, $egHeaderFooterEnableAsyncFooter;
		if ( $egHeaderFooterEnableAsyncFooter || $egHeaderFooterEnableAsyncHeader ) {
			$op->addModules( 'ext.headerfooter.dynamicload' );
		}

		return true;
	}

	public static function onGetDoubleUnderscoreIDs( &$doubleUnderscoreIDs ) {
		$doubleUnderscoreIDs[] = 'hf_nsheader';
		$doubleUnderscoreIDs[] = 'hf_header';
		$doubleUnderscoreIDs[] = 'hf_footer';
		$doubleUnderscoreIDs[] = 'hf_nsfooter';
	}

	/**
	 * Verifies & Strips ''disable command'', returns $content if all OK.
	 */
	static function conditionalInclude( $disableWord, $class, $unique, $parser ) {
		if ( $parser->getPageProperty( $disableWord ) !== null ) {
			return null;
		}

		$msgId = "$class-$unique"; // also HTML ID
		$div = "<div class='$class' id='$msgId'>";

		global $egHeaderFooterEnableAsyncHeader, $egHeaderFooterEnableAsyncFooter;

		$isHeader = $class === 'hf-nsheader' || $class === 'hf-header';
		$isFooter = $class === 'hf-nsfooter' || $class === 'hf-footer';

		if ( ( $egHeaderFooterEnableAsyncFooter && $isFooter )
			|| ( $egHeaderFooterEnableAsyncHeader && $isHeader ) ) {

			// Just drop an empty div into the page. Will fill it with async
			// request after page load
			return $div . '</div>';
		}
		else {
			$msgText = wfMessage( $msgId )->parse();

			// don't need to bother if there is no content.
			if ( empty( $msgText ) ) {
				return null;
			}

			if ( wfMessage( $msgId )->inContentLanguage()->isBlank() ) {
				return null;
			}

			return $div . $msgText . '</div>';
		}
	}

	public static function onResourceLoaderGetConfigVars ( array &$vars ) {
		global $egHeaderFooterEnableAsyncHeader, $egHeaderFooterEnableAsyncFooter;

		$vars['egHeaderFooter'] = [
			'enableAsyncHeader' => $egHeaderFooterEnableAsyncHeader,
			'enableAsyncFooter' => $egHeaderFooterEnableAsyncFooter,
		];

		return true;
	}

}
