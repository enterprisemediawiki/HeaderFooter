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
			
		global $wgTitle;
		
		$ns = $wgTitle->getNsText();
		$name = $wgTitle->getPrefixedDBKey();
		
		$text = $parserOutput->getText();
		
		$nsheader = "hf-nsheader-$ns";
		$nsfooter = "hf-nsfooter-$ns";	  
 
		$header = "hf-header-$name";
		$footer = "hf-footer-$name";		

		$text = '<div class="hf-header">'.self::conditionalInclude( $text, '__NOHEADER__', $header ).'</div>'.$text;
		$text = '<div class="hf-nsheader">'.self::conditionalInclude( $text, '__NONSHEADER__', $nsheader ).'</div>'.$text;

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
		$disable = strpos( $text, $disableWord ) !== false ;
 
		// if there is, get rid of it
		// make sure that the disableWord does not break the REGEX below!
		$text = preg_replace('/'.$disableWord.'/si', '', $text );
 
		// if there is a disable command, then don't return anything
		if ($disable)
			return null;
 
		$msgText = wfMessage( $msgId, array( 'parseinline' ) );
 
		// don't need to bother if there is no content.
		if (empty( $msgText ))
			return null;
 
		if (wfMessage( $msgId, $msgText ))
			return null;
 
		return $msgText;
	}
		
} // END CLASS DEFINITION
