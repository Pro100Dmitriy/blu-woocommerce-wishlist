/**
 * 
 * This Script for single product page.
 * 
 */


document.addEventListener( 'DOMContentLoaded', event => {
// *************************************************************************** DOM Content Loaded

    let add_to_wishlist = document.querySelectorAll('.blu-add-ajax-wishlist')
    
    if( add_to_wishlist[0] && type_page ){
        
        add_to_wishlist.forEach( add_to => {
            add_to.addEventListener( 'click', event => {
                let spanBut = event.target
                if( spanBut.classList.length == 0 ){
                    spanBut = event.target.parentNode
                }
                
                let url = woocommerce_params.ajax_url                                                                                                   // Ajax URL
                let product_id = spanBut.getAttribute('data-product-id')                                                                                // Product ID
                let variation_id = document.querySelector('.variation_id') != null ? document.querySelector('.variation_id').value : null;              // Variation ID
                let quanity = document.getElementById('quantity').value                                                                                 // Quantity
                let color = null                                                                                                                        // Color
                let razmer = null                                                                                                                       // Razmer

                if( document.getElementById('czvet') ){
                    color = document.getElementById('czvet').value.replace(' #', '+')
                }
                if( document.getElementById('razmer') ){
                    razmer = document.getElementById('razmer').value.replace(' #', '+')
                }
                
                // Color(czvet), Razmer, quantity, price, product_id
                subscribeSendRequest({
                    method: 'GET',
                    url,                            // Ajax URL
                    action: 'wishlist',
                    data: {
                        product_id,                 // Product ID
                        variation_id,               // Variation ID
                        quanity,                    // Quantity
                        color,                      // Color
                        razmer                      // Razmer
                    }
                }).then(resolve => {
                    // *************************** Then Block
                    //notification_add(resolve)
                    Notification.add(resolve)
                    // *************************** Then Block
                }).catch(error => {
                    // *************************** Catch Block
                    // *************************** Catch Block
                })
            } )
        } )
        
    }

// *************************************************************************** DOM Content Loaded
} )