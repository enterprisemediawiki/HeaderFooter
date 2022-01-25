MediaWiki-HeaderFooter
======================

Installation and usage documentation is at http://www.mediawiki.org/wiki/Extension:Header_Footer

## Version history

### Header Footer 4.0.0

Released on 2022-01-25

* The extension no longer enables itself automatically when installed via composer.
  You now need to call `wfLoadExtension( 'HeaderFooter' );` in LocalSettings.php.
