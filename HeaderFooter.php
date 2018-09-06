<?php
if ( function_exists( 'wfLoadExtension' ) ) {
	wfLoadExtension( 'HeaderFooter' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgMessagesDirs['HeaderFooter'] = __DIR__ . '/i18n';
	wfWarn(
		'Deprecated PHP entry point used for HeaderFooter extension. ' .
		'Please use wfLoadExtension instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	);
	return;
} else {
	die( 'This version of the HeaderFooter extension requires MediaWiki 1.25+' );
}
