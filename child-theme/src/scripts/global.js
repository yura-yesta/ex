import cookiesNotice from './cookies-notice';

cookiesNotice();

$( '.filter-all .filter' ).on( 'click', function() {
	$( '.section-search-page .inner' ).addClass( 'active' );
} );

$( '.close' ).on( 'click', function() {
	$( '.bg-popup' ).addClass( 'hidden-popup' );
} );

$( '.section-search-page .bg' ).on( 'click', function() {
	$( '.section-search-page .inner' ).removeClass( 'active' );
} );

$( '.tabs .tabs-nav button' ).on( 'click', function( e ) {
	e.preventDefault();
	$( '.tabs .tabs-nav button' ).removeClass( 'active-hover' );
	$( this ).addClass( 'active-hover' );
} );
