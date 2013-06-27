<?php
/**
 * @author Jean-Lou Dupont
 * @author Jamesmontalvo3
 * @author Douglas Mason
 * @package HeaderFooter
 * @version 2.0.3
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
		
		$text = $parserOutput->getText();
		
        $nsheader = "hf-nsheader-$ns";
        $nsfooter = "hf-nsfooter-$ns";      
 
        $header = "hf-header-$name";
        $footer = "hf-footer-$name";        

		$text = '<div class="hf-header">'.$this->conditionalInclude( $text, '__NOHEADER__', $header ).'</div>'.$text;
		$text = '<div class="hf-nsheader">'.$this->conditionalInclude( $text, '__NONSHEADER__', $nsheader ).'</div>'.$text;

		$text .= '<div class="hf-footer">'.$this->conditionalInclude( $text, '__NOFOOTER__', $footer ).'</div>';
		$text .= '<div class="hf-nsfooter">'.$this->conditionalInclude( $text, '__NONSFOOTER__', $nsfooter ).'</div>';
		
		$parserOutput->setText( $text );
		
		return true;
	}	 
 
    /**
     * Verifies & Strips ''disable command'', returns $content if all OK.
     */
    protected function conditionalInclude( &$text, $disableWord, &$msgId, $protect )
    {
        // is there a disable command lurking around?
        $disable = strpos( $text, $disableWord ) !== false ;
 
        // if there is, get rid of it
        // make sure that the disableWord does not break the REGEX below!
        $text = preg_replace('/'.$disableWord.'/si', '', $text );
 
        // if there is a disable command, then don't return anything
        if ($disable)
            return null;
 
        $msgText = wfMsgExt( $msgId, array( 'parseinline' ) );
 
        // don't need to bother if there is no content.
        if (empty( $msgText ))
            return null;
 
        if (wfEmptyMsg( $msgId, $msgText ))
            return null;
 
        return $msgText;
    }
		
} // END CLASS DEFINITION
//</source>