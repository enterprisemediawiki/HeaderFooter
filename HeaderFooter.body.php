<?php
/**
 * @author Jean-Lou Dupont
 * @package HeaderFooter
 * @version 2.0.1
 * @Id $Id: HeaderFooter.body.php 1170 2008-05-27 12:38:24Z jeanlou.dupont $
 */
//<source lang='php'>
class HeaderFooter
{
	/**
	 * Main Hook
	 */
	public function hOutputPageParserOutput( &$op, $parserOutput )
	{
		$action = $op->parserOptions()->getUser()->getRequest()->getVal("action");
		if ( ($action == 'edit') || ($action == 'submit') || ($action == 'history') )
			return true;
			
		global $wgTitle;
		
		$ns = $wgTitle->getNsText();
		$name = $wgTitle->getPrefixedDBKey();
		$protect = $wgTitle->isProtected( 'edit' );
		
		$text = $parserOutput->getText();
		
		$nsheader = $this->getMsg( "hf-nsheader-$ns" );
		$nsfooter = $this->getMsg( "hf-nsfooter-$ns" );

		$header = $this->getMsg( "hf-header-$name" );
		$footer = $this->getMsg( "hf-footer-$name" );

		$text = '<div class="hf-header">'.$this->conditionalInclude( $text, '__NOHEADER__', $header, $protect ).'</div>'.$text;
		$text = '<div class="hf-nsheader">'.$this->conditionalInclude( $text, '__NONSHEADER__', $nsheader, $protect ).'</div>'.$text;

		$text .= '<div class="hf-footer">'.$this->conditionalInclude( $text, '__NOFOOTER__', $footer, $protect ).'</div>';
		$text .= '<div class="hf-nsfooter">'.$this->conditionalInclude( $text, '__NONSFOOTER__', $nsfooter, $protect ).'</div>';
		
		$parserOutput->setText( $text );
		
		return true;
	}	 
	/**
	 * Gets a message from the NS_MEDIAWIKI namespace
	 */
	protected function getMsg( $msgId )
	{
		$msgText = wfMsgExt( $msgId, array( 'parseinline' ) );
		
		if ( wfEmptyMsg( $msgId, $msgText ))
			return null;
			
		return $msgText;			
	}	 
	/**
	 * Verifies & Strips ''disable command'', returns $content if all OK.
	 */
	protected function conditionalInclude( &$text, $disableWord, &$content, $protect )
	{
		// is there a disable command lurking around?
		$disable = strpos( $text, $disableWord ) !== false ;
		
		// if there is, get rid of it
		// make sure that the disableWord does not break the REGEX below!
		$text = preg_replace('/'.$disableWord.'/si', '', $text );

		// don't need to bother if there is no content.
		if (empty( $content ))
			return null;
		
		// if there is a disable command, then obey IFF the page is protected on 'edit'
		if ($disable && $protect)
			return null;
		
		return $content;
	}
		
} // END CLASS DEFINITION
//</source>