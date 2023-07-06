
var egHeaderFooter = mw.config.get( 'egHeaderFooter' );

if ( egHeaderFooter.enableAsyncHeader ) {
	var extHeaderFooterBlocks = [ "hf-nsheader", "hf-header" ];
}
else {
	var extHeaderFooterBlocks = [];
}
extHeaderFooterBlocks = extHeaderFooterBlocks.concat( [ "hf-footer", "hf-nsfooter" ] );

for ( var i = 0; i < extHeaderFooterBlocks.length; i++ ) {
	var block = extHeaderFooterBlocks[i];

	$( "." + block ).each( function( i, e ) {

		// FIXME: At some point, put some method of indicating unloaded content here

		// FIXME: At some point, add method to further delay loading of dynamic
		//        footers. Headers should be loaded right away, but footers should
		//        only be loaded if the user can see them (or is scrolling toward
		//        them).

		// Message ID of block (header or footer) is in the HTML ID. hf-nsheader
		// will have an ID like hf-nsheader-Help for the help namespace.
		var msgId = $(e).attr('id');

		$.get(
			mw.config.get("wgScriptPath") + "/api.php",
			{
				action: "getheaderfooter",
				messageid: msgId,
				contexttitle: mw.config.get('wgPageName'),
				format: "json"
			},
			function ( response ) {
				var blockText = response.getheaderfooter.result;
				$( "#" + msgId ).html( blockText );
				$( "#" + msgId ).find( "#headertabs" ).each( function(i,e) {
					// Ensure headertabs is loaded after dynamic content added
					mw.loader.using( ['ext.headertabs'] );
				});
			}
		)

	} );

}
