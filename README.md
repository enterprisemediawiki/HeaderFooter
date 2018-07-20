# HeaderFooter extension for MediaWiki

Provides per-namespace and per-page header and footer inclusion.

## Features

* **Speed**: integrated with parser caching
* **Secure**: header and footer articles are located in the <code>NS_MEDIAWIKI</code> namespace
* **Controllable**: headers and/or footers can be disabled on pages which are edit protected
* **Customizable**: headers and footers are wrapped in &lt;div> elements

## Usage


### Per-Namespace Header and Footer
Edit the pages:
* `[[MediaWiki:Hf-nsheader-`*`namespace name`*`]]`
* `[[MediaWiki:Hf-nsfooter-`*`namespace name`*`]]`

For the 'main' namespace, just use ''blank'' i.e. no string, but with the hyphen:
* `[[MediaWiki:Hf-nsheader-]]`
* `[[MediaWiki:Hf-nsfooter-]]`

### Per-Page Header and Footer
Edit the pages:
* `[[MediaWiki:Hf-header-`*`page name`*`]]`
* `[[MediaWiki:Hf-footer-`*`page name`*`]]`

### Disable commands
On ''edit'' protected pages, one can add
* `__NOHEADER__` to suppress the page level header
* `__NOFOOTER__` to suppress the page level footer
* `__NONSHEADER__` to suppress the namespace level header
* `__NONSFOOTER__` to suppress the namespace level footer

## CSS
* Page Level Header: &lt;div class="hf-header">
* Namespace Level Header: &lt;div class="hf-nsheader">
* Page Level Footer: &lt;div class="hf-footer">
* Namespace Level Footer: &lt;div class="hf-nsfooter">

See http://www.mediawiki.org/wiki/Extension:Header_Footer
