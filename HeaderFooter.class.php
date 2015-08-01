<?php
/**
 * @package HeaderFooter
 */
class HeaderFooter
{
	/**
	 * Main Hook
	 */
	public static function hOutputPageParserOutput( &$op, $parserOutput )
	{
		$action = $op->parserOptions()->getUser()->getRequest()->getVal("action");
		if ( ($action == 'edit') || ($action == 'submit') || ($action == 'history') )
			return true;

		global $wgTitle, $wgOut;

		$ns = $wgTitle->getNsText();
		$name = $wgTitle->getPrefixedDBKey();
		$text = $parserOutput->getText();

		$categories = $wgTitle->getParentCategories();
		$categories = array_keys( $categories) ;
		$categories = array_map(
			function( $cat ){ return Title::newFromText( $cat, NS_CATEGORY )->getText(); },
			$categories
		);

		$nsheader = "hf-nsheader-$ns";
		$nsfooter = "hf-nsfooter-$ns";

		$header = "hf-header-$name";
		$footer = "hf-footer-$name";

		/**
		 * headers/footers are wrapped around page content.
		 * header:	page + namespace + categories in reverse alphabetical order
		 * footer:	categories in alphabetical order + namespace + page
		 */
		$text = '<div class="hf-header">'.self::conditionalInclude( $text, '__NOHEADER__', $header ).'</div>'.$text;
		$text = '<div class="hf-nsheader">'.self::conditionalInclude( $text, '__NONSHEADER__', $nsheader ).'</div>'.$text;

		foreach( $categories as &$category ) {
			$catheader = "hf-catheader-$category" ;
			$text = '<div class="hf-catheader">'.self::conditionalInclude( $text, '__NOCATHEADER__', $catheader ).'</div>'.$text;
			$catfooter = "hf-catfooter-$category" ;
			$text .= '<div class="hf-catfooter">'.self::conditionalInclude( $text, '__NOCATFOOTER__', $catfooter ).'</div>';
		}

		$text .= '<div class="hf-footer">'.self::conditionalInclude( $text, '__NOFOOTER__', $footer ).'</div>';
		$text .= '<div class="hf-nsfooter">'.self::conditionalInclude( $text, '__NONSFOOTER__', $nsfooter ).'</div>';

		$parserOutput->setText( $text );

		return true;
	}

	/**
	 * Verifies & Strips ''disable command'', returns $content if all OK.
	 */
	static function conditionalInclude( &$text, $disableWord, &$msgId )
	{
		// is there a disable command lurking around?
		$disable = strpos( $text, $disableWord ) !== false;

		// if there is, get rid of it
		// make sure that the disableWord does not break the REGEX below!
		$text = preg_replace('/'.$disableWord.'/si', '', $text );

		// if there is a disable command, then don't return anything
		if ($disable)
			return null;

		//$msgText = wfMsgExt( $msgId, array( 'parseinline' ) );
		//https://www.mediawiki.org/wiki/Manual:Messages_API
		$msgText = wfMessage( $msgId) -> parse() ;

		// don't need to bother if there is no content.
		if (empty( $msgText ))
			return null;

		//if (wfEmptyMsg( $msgId, $msgText ))
		//https://www.mediawiki.org/wiki/Manual:Messages_API
		if ( wfMessage( $msgId )->inContentLanguage()->isBlank() )
			return null;

		return $msgText;
	}
} // END CLASS DEFINITION
