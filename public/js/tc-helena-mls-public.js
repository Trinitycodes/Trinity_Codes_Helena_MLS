(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	
	$( function() {

        $( '#tc-gallery-thumbs' ).on( 'click', 'a.tc-gallery-image', function( evt ) {
     
		    // Stop the anchor's default behavior
		    evt.preventDefault();

		    // set the id var
		    var newSrc = this.id;
		    /**
		     * Remove the selected image and the hidden input 
		     * containing the images id
		     *
		     * @param $ [ jQuery ]
		     * @param this.id [ contains the id of the clicked element ]
		     */
		    // resetUploadForm( $, this.id );
		    $( '.tc-gallery-target' ).fadeOut(500, function() {
		    	// Replace image href of .tc-gallery-target
    			$( '#tc-thumbnail-holder' ).html('<img height="500" class="tc-gallery-target" style="max-height: 450px; width: auto;" src="' + newSrc + '" alt="" />').fadeIn();
		    });
		});
 
    });

})( jQuery );