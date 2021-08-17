/**
 * 
 * This Script for all web pages with products.
 * 
 */


document.addEventListener( 'DOMContentLoaded', event => {
// *************************************************************************** DOM Content Loaded


    let fastview_buttons = document.querySelectorAll( '[data-el="blu_fastview"]' )
    
    if( fastview_buttons ){
        fastview_buttons.forEach( button => button.addEventListener( 'click', blu_fastview ) )
    }

    function blu_fastview( event ){
        event.preventDefault()

        const $target = event.target

        // Prepare Data
        let url = woocommerce_params.ajax_url                                                                                           // Ajax URL
        let product_id = $target.getAttribute('data-blu-product-id') ? $target.getAttribute('data-blu-product-id') : false;             // Product ID


        // Send Request
        fastviewSendRequest( {
            method: 'GET',
            url,
            action: 'blu_fastview',
            data: {
                product_id
            },
            onloadstart_callback(){
                
            }
        } )
        .then( resolve => {
            // *************************** Then Block
            
            // *************************** Then Block
        } )
        .catch( reject => {
            // *************************** Catch Block

            // *************************** Catch Block
        } )

    }


// *************************************************************************** DOM Content Loaded
} )