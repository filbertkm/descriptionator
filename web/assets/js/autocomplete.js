( function( $ ) {
  $( '#form_category' ).autocomplete({
    source: function( req, add ) {
		$.getJSON(
			"http://en.wikipedia.org/w/api.php?action=opensearch&search="
				+ req.term + "&namespace=14&format=json&callback=?",
			req,
			function( data ) {
				add( data[1] );
			}
		);
	}
  });
} )( jQuery );
