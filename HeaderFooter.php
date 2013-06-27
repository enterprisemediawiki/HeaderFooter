<?php
/**
 * @author Jean-Lou Dupont
 * @author Jamesmontalvo3
 * @package HeaderFooter
 * @version 2.0.2
 * @Id $Id: HeaderFooter.php 821 2008-01-04 02:36:30Z jeanlou.dupont $
 * Note: increased from v2.0.1 by James Montalvo for StubManager dependency removal
 */
$wgExtensionCredits['other'][] = array( 
	'name'        => 'HeaderFooter', 
	'version'     => '2.0.2',
	'author'      => 'Jean-Lou Dupont, James Montalvo, Douglas Mason', 
	'description' => 'Enables per-page/per-namespace headers and footers',
	'url' 		=> 'http://mediawiki.org/wiki/Extension:HeaderFooter',			
);

$wgHooks['OutputPageParserOutput'][] = 'HeaderFooter::hOutputPageParserOutput';
$wgAutoloadClasses['HeaderFooter'] = dirname(__FILE__) . '/' . 'HeaderFooter.body.php';