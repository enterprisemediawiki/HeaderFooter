<?php
if ( function_exists( 'wfLoadExtension' ) ) {
    echo "Please use<pre>wfLoadExtension('HeaderFooter');</pre>to load this extensions.";
    exit(1);
}

/**
 * @author Jean-Lou Dupont
 * @author Jamesmontalvo3
 * @package HeaderFooter
 * @version 2.1.1
 */

# Credits
$wgExtensionCredits['other'][] = array( 
	'name'		  => 'HeaderFooter', 
	'version'	  => '2.1.1',
	'author'	  => 'Jean-Lou Dupont, James Montalvo, Douglas Mason', 
	'description' => 'Enables per-page/per-namespace headers and footers',
	'url' 		  => 'http://mediawiki.org/wiki/Extension:HeaderFooter',			
);

# Hooks
$wgHooks['OutputPageParserOutput'][] = 'HeaderFooter::hOutputPageParserOutput';

# Autoload
$wgAutoloadClasses['HeaderFooter'] = __DIR__ . '/HeaderFooter.class.php';