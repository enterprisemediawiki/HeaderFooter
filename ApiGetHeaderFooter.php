<?php

/**
 * API module for MediaWiki's HeaderFooter extension.
 *
 * @author James Montalvo
 * @since Version 1.0
 */

/**
 * API module to review revisions
 */
class ApiGetHeaderFooter extends ApiBase {

	public function execute() {
		global $wgUser;

		$params = $this->extractRequestParams();
		$contextTitle = Title::newFromDBkey( $params['contexttitle'] );
		if ( ! $contextTitle ) {
			$this->dieUsage( "Not a valid contexttitle.", 'notarget' );
		}

		// RequestContext::getMain()->setTitle( $contextTitle );

		$messageId = $params['messageid'];

		// $messageText = wfMessage( $msgId )->setContext(  $params['contexttitle'] )->parse();
		$messageText = wfMessage( $messageId )->title( $contextTitle )->parse();

		// don't need to bother if there is no content.
		if ( empty( $messageText ) ) {
			$messageText = '';
		}

		if ( wfMessage( $messageId )->inContentLanguage()->isBlank() ) {
			$messageText = '';
		}


		$this->getResult()->addValue( null, $this->getModuleName(), array( 'result' => $messageText ) );

	}

	public function getAllowedParams() {
		return array(
			'contexttitle' => array(
				ApiBase::PARAM_REQUIRED => true,
				ApiBase::PARAM_TYPE => 'string'
			),
			'messageid' => array(
				ApiBase::PARAM_REQUIRED => true,
				ApiBase::PARAM_TYPE => 'string'
			)
		);
	}

	/**
	 * @see ApiBase::getExamplesMessages()
	 */
	protected function getExamplesMessages() {
		return array(
			'action=getheaderfooter&contexttitle=Main_Page&messageid=Hf-nsfooter-'
				=> 'apihelp-getheaderfooter-example-1',
		);
	}

	// "apihelp-getheaderfooter-description": "Retrieve the parsed output of a header or footer in the context of a certain page.",
	// "apihelp-getheaderfooter-summary": "Retrieve the parsed output of a header or footer in the context of a certain page.",
	// "apihelp-getheaderfooter-param-contexttitle": "The title of the page that the header or footer is being added to.",
	// "apihelp-getheaderfooter-param-messageid": "Which header or footer is being requested (e.g. a namespace header)",
	// "apihelp-getheaderfooter-example-1": "Approve Revision 12345",



	public function mustBePosted() {
		return false;
	}

	public function isWriteMode() {
		return false;
	}

	/*
	 * CSRF Token must be POSTed
	 * use parameter name 'token'
	 * No need to document, this is automatically done by ApiBase
	 */
	// public function needsToken() {
	// 	return 'csrf';
	// }

	// public function getTokenSalt() {
	// 	return 'e-ar';
	// }
}
