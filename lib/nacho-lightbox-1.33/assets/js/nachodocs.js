/**
 * NACHO Documentation
 */
( function( $, w, ndef ){
	
	var methods = {
		
		project : function( data ){
			
			var _ = $.fn.nachodocs.project;
			
			if( data === ndef && _.productJSON ) {
				
				var s = document.createElement( 'script' );
				s.src = _.productJSON;
				
				$( 'body' ).append( s );
			
			} else {
				
				if( _.version < data.currentVersion ){
				
					$( 'body' ).addClass( 'update-available' );
					$( '#updateButton' ).attr( 'href', data.productURL );
					
				}
				
			}
		
		},
		
		navgroup : function(){
		
			var _w = $( window ), set = this, all = [], item = [], hold = $( '.nch-navgroup-hold' ), cnt = 0, _b, rp = $( '<div class="nch-navgroup-replace"></div>' );
			
			$( set[ 0 ] ).before( rp );
			
			for( var i = 0 ; i < set.length ; i ++ ){		
				hold.append( all[ i ] = $( set[ i ] ) );
				item[ i ] = all[ i ].children( 'ul' ).children( 'li' );
			}
			
			hold.attr( 'id', 'nch-nv-targetmenu' );
			
			_b = $( 'body' ).scrollspy({ target: '#nch-nv-targetmenu', offset: 25 });
			
			_w.load( function(){ window.setTimeout( function(){ _b.scrollspy( 'refresh' ) }, 1000 ) } );
			
			// state properties
			var cwidth, docpos, fclass = 'nch-navgroup-first', offset = 0, small = false;
			
			function refresh(){
				
				cwidth = rp.width();
				docpos = rp.offset()
				
				hold.width( cwidth ).css( docpos );
				
				resize();
				
			}
			
			function resize(){
				
				offset = docpos.top - _w.scrollTop();
			
				hold.css( 'top', ( offset < 0 ) ? 0 : offset ).find( '.' + fclass ).removeClass( fclass );
				
				var was = false;
				
				for( var i in all ) all[ i ].show();
				
				for( var i in all )
					if( item[ i ].hasClass( 'active' ) ){
						all[ i ].addClass( fclass );
						break;
					} else all[ i ].hide();

				hold[ ( ( small = ( _w.width() < 992 ) ) ? 'add' : 'remove' ) + 'Class' ]( 'nch-navgroup-disabled' );
				
				_b.scrollspy( 'refresh' );
				
			}
			
			_w.resize( refresh ).scroll( resize );
			
			refresh();
			resize();
		
		}
	
	};
	
	
	$.fn.nachodocs = function( method ){
		
		/**
		 * Method calling logic
		 */
		if ( methods[ method ] ) {
			return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else {
			return methods.init.apply( this, arguments );
		}
		
	}
	
	$.fn.nachodocs.project = {};
	
	
	/**
	 * Document ready hook
	 */
	$( document ).ready( function(){
	
		/**
		 * Download product JSON
		 */
		$.fn.nachodocs( 'project' );
		
		$( '.nch-doc-productver' ).html( $.fn.nachodocs.project.version );
		
		if( window.prettyPrint ) prettyPrint();
		
		$( 'body' ).addClass( 'nch-nv-enable' );
		
		$( '.nch-navgroup' ).nachodocs( 'navgroup' );
	
	});

})( jQuery, window );