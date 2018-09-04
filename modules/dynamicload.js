
var extHeaderFooterBlocks = [ "hf-nsheader", "hf-header", "hf-footer", "hf-nsfooter" ];

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
			// {
			// 	action: "query",
			// 	meta: "allmessages",
			// 	ammessages: msgId,
			// 	amenableparser: 1,
			// 	amtitle: mw.config.get('wgPageName'),
			// 	format: "json"
			// },
			{
				action: "getheaderfooter",
				messageid: msgId,
				contexttitle: mw.config.get('wgPageName'),
				format: "json"
			},
			function ( response ) {
				// var blockText = response.query.allmessages[0]["*"];
				var blockText = response.getheaderfooter.result;
				$("#" + msgId).html( blockText );
			}
		)

	} );

}
