/*
 Theme Customizer live preview v1.0 | @agareginyan | GPL v3 Licensed

 This file adds some LIVE to the Theme Customizer live preview. To leverage
 this, set your custom settings to 'postMessage' and then add your handling
 here. Your javascript should grab settings from customizer controls, and 
 then make any necessary changes to the page using jQuery.
*/

( function( $ ) {

	// Update the site title in real time...
	wp.customize( 'blogname', function( value ) {
		value.bind( function( newval ) {
			$( '.site-title' ).html( newval );
		} );
	} );
	
	//Update the site description in real time...
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( newval ) {
			$( '.site-description' ).html( newval );
		} );
	} );

	//Update site title color in real time...
	wp.customize( 'title_color', function( value ) {
		value.bind( function( newval ) {
			$('.site-title').css('color', newval );
		} );
	} );

        //Update site description color in real time...
        wp.customize( 'tagline_color', function( value ) {
                value.bind( function( newval ) {
                        $('.site-description').css('color', newval );
                } );
        } );

	//Update site background color...
	wp.customize( 'background_color', function( value ) {
		value.bind( function( newval ) {
			$('body').css('background-color', newval );
		} );
	} );
	
	//Update site title color in real time...
	wp.customize( 'mytheme_options[link_textcolor]', function( value ) {
		value.bind( function( newval ) {
			$('a').css('color', newval );
		} );
	} );

        //Update site description color in real time...
        wp.customize( 'header_textcolor', function( value ) {
                value.bind( function( newval ) {
                        $('.site-description').css('color', newval );
                } );
        } );

	
} )( jQuery );
