/*

    Credits go to:
    Osvaldas Valutis, www.osvaldas.info
    Available for use under the MIT License


*/


// This whole block handles the <span> innerHTML change when a file has been
// selected by [file] <input>. It was desgined to iterate through a series
// of input elements and perform the switch for each one.
//            
'use strict';

;( function( $, window, document, undefined )
{
    $( '.inputfile' ).each( function()
    {
        var $input     = $( this ),
            $label     = $input.next( 'label' ),
            $labelVal = $label.html();

        $input.on( 'change', function( e )
        {
            var fileName = '';
            
            // could probably dump this multi-file upload handler, but it doesn't hurt to keep it
            if( this.files && this.files.length > 1 )
                fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
            else if( e.target.value )
                fileName = e.target.value.split( '\\' ).pop();

            if( fileName )
                $label.find( 'span' ).html( fileName );
            else
                $label.html( labelVal );
        });

        // Firefox bug fix
        $input
        .on( 'focus', function(){ $input.addClass( 'has-focus' ); })
        .on( 'focusout', function(){ $input.removeClass( 'has-focus' ); });
    });
})( jQuery, window, document );



$( '.cancel-btn' ).click( function() {
    var $label = $( '.inputfile' ).next( 'label' );
    $label.find( 'span' ).html(' ');
    $( '.preview' ).addClass( 'disabled' );

    map.removeLayer(layer);
});





