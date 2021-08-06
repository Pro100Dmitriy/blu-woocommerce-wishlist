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
        subscribeSendRequest({
            method: 'GET',
            url,                            // Ajax URL
            action: 'blu_fastview',
            data: {
                product_id,                 // Product ID
            }
        }).then(resolve => {
            // *************************** Then Block
            console.log( resolve )
            // *************************** Then Block
        }).catch(reject => {
            // *************************** Catch Block

            // *************************** Catch Block
        })

    }


// *************************************************************************** DOM Content Loaded
} )