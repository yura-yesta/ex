function init() {
	const acceptCookie = document.getElementById( 'accept-cookie' );

	if ( ! acceptCookie ) {
		return;
	}

	const cookieName = 'cookie-notice';
	const cookieValue = '1';
	const cookieExpireDays = 365;
	const notice = document.getElementById( 'cookie-notice' );
	const closeCookieNotice = document.getElementById( 'cookie-notice-close' );

	const createCookie = function( name, value, days ) {
		const currentDate = new Date();
		currentDate.setTime( currentDate.getTime() + ( days * 24 * 60 * 60 * 1000 ) );
		const expires = 'expires=' + currentDate.toGMTString();
		document.cookie = name + '=' + value + '; ' + expires + '; path=/';
		if ( document.cookie ) {
			notice.classList.remove( 'active' );
		}
	};

	const getCookie = function( cookiesName ) {
		const name = cookiesName + '=';
		const decodedCookie = decodeURIComponent( document.cookie );
		const ca = decodedCookie.split( ';' );
		for ( let i = 0; i < ca.length; i++ ) {
			let c = ca[ i ];
			while ( c.charAt( 0 ) === ' ' ) {
				c = c.substring( 1 );
			}
			if ( c.indexOf( name ) === 0 ) {
				return c.substring( name.length, c.length );
			}
		}
		return '';
	};

	const checkCookie = function() {
		const check = getCookie( cookieName );
		if ( check === '' ) {
			notice.classList.add( 'active' );
		} else {
			notice.classList.remove( 'active' );
		}
	};

	acceptCookie.onclick = function( e ) {
		e.preventDefault();
		createCookie( cookieName, cookieValue, cookieExpireDays );
	};

	closeCookieNotice.onclick = function( e ) {
		e.preventDefault();
		createCookie( cookieName, cookieValue, cookieExpireDays );
	};

	window.addEventListener( 'click', checkCookie );
	window.addEventListener( 'scroll', checkCookie );
}

export default function cookiesNotice() {
	if ( /complete|interactive|loaded/.test( document.readyState ) ) {
		init();
	} else {
		document.addEventListener( 'DOMContentLoaded', init, false );
	}
}
