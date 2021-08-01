/**
 * 
 * This Script for all web pages with products.
 * 
 */


document.addEventListener( 'DOMContentLoaded', event => {
// *************************************************************************** DOM Content Loaded

    /**
     * Add SVG Button In
     */
    let target_product = document.querySelectorAll('div[data-target="blu_woo_wishlist_public"]')
    let target_template = (id) => { 
        return `
                <button class="blu__like-icon blu-add-public-ajax-wishlist" data-product-id="${id}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="33" height="33" viewBox="0 0 33 33"><g transform="translate(8.105 8.684)"><path d="M8.323,43.324a23.174,23.174,0,0,1-2.4-2.07C2.355,38.223,0,36.221,0,33.3a4.537,4.537,0,0,1,4.421-4.763c2.815,0,3.722,2.634,3.9,2.634s1.007-2.634,3.9-2.634A4.537,4.537,0,0,1,16.645,33.3c0,2.923-2.355,4.926-5.919,7.957A23.087,23.087,0,0,1,8.323,43.324Zm-3.9-13.815A3.549,3.549,0,0,0,.975,33.3c0,2.473,2.218,4.359,5.576,7.214C7.12,41,8.1,42.029,8.323,42.029s1.2-1.034,1.771-1.518c3.358-2.855,5.576-4.742,5.576-7.214a3.549,3.549,0,0,0-3.446-3.788c-1.774,0-3.024,1.59-3.431,3.056v0l-.939,0h0C7.493,31.271,6.326,29.509,4.421,29.509Z" transform="translate(0 -28.534)" fill="#fff"/></g><rect width="33" height="33" fill="none"/></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="33" height="33" viewBox="0 0 33 33"><defs><style>.cls-1{fill:#fff;}.cls-2{fill:none;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M16.43,23.47A21.47,21.47,0,0,1,14,21.4c-3.57-3-5.93-5-5.93-7.95a4.55,4.55,0,0,1,4.31-4.76h.12c2.81,0,3.72,2.63,3.9,2.63s1-2.63,3.9-2.63a4.53,4.53,0,0,1,4.42,4.64v.12c0,2.92-2.35,4.93-5.92,8A22.47,22.47,0,0,1,16.43,23.47Z"/><rect class="cls-2" width="33" height="33"/></g></g></svg>
                </button>
                `
    }
    target_product.forEach( elem => {
        target_id = elem.getAttribute('data-target-id')
        elem.insertAdjacentHTML('afterbegin', target_template(target_id) )
    } )


    /**
     * Add Event Listener
     */
    let add_to_wishlist_public = document.querySelectorAll('.blu-add-public-ajax-wishlist')

    if( add_to_wishlist_public[0] ){
    // ********************************** Start If "add_to_wishlist_public[0]"

        // Add Observe To "Load More" Button in Shop page
        let product_list_container = document.getElementById('blueins-load_more')

        if( product_list_container != null ){
            const shopWishlistObserve = new MutationObserver( mutation => {
                document.querySelectorAll('.blu-add-public-ajax-wishlist').forEach( item => item.addEventListener('click', addLikeRequest ) )
            } )

            shopWishlistObserve.observe(product_list_container,{
                attributes: true,
                characterData: true,
                childList: true,
                subtree: true,
                attributeOldValue: true,
                characterDataOldValue: true
            })
        }

        // Add Event Listener On Buttons
        add_to_wishlist_public.forEach( item => item.addEventListener('click', addLikeRequest ) )

        function addLikeRequest(event){
            event.preventDefault();

            target = event.target.hasAttribute('data-product-id') ? event.target : event.target.parentNode

            target.classList.add('color-selected-added')

            // Prepare Data
            let url = woocommerce_params.ajax_url                                                                               // Ajax URL
            let product_id = target.getAttribute('data-product-id') ? target.getAttribute('data-product-id') : false;           // Product ID
            let quanity = 1                                                                                                     // Quantity

            let product_container = document.querySelector(`div[data-blu-product-id="${product_id}"]`)
            let variation_id =  product_container.querySelector('.color-select') ?
                                product_container.querySelector('.color-select').getAttribute('data-blu-variation_id') :
                                product_container.getAttribute('data-default-variation_id')                                     // Variation ID
            let color = product_container.querySelector('.color-select') ?
                        product_container.querySelector('.color-select').getAttribute('data-color-name').replace(' #', '+') :
                        null                                                                                                    // Color
            let razmer =    product_container.getAttribute('data-default-razmer') ?
                            product_container.getAttribute('data-default-razmer').replace(' #', '+') :
                            null                                                                                                // Razmer

            // Send Request
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
            }).catch(reject => {
                // *************************** Catch Block
                // *************************** Catch Block
            })
        }

    // ********************************** End If "add_to_wishlist_public[0]"
    }


// *************************************************************************** DOM Content Loaded
} )