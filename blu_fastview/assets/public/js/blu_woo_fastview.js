/**
 * 
 * This Script for all web pages with products.
 * 
 */

const PAGE_URL = global_params.url


document.addEventListener( 'DOMContentLoaded', event => {
// *************************************************************************** DOM Content Loaded


    const $fastview = fastview( document.querySelector('.fastview') )
    let fastview_buttons = document.querySelectorAll( '[data-el="blu_fastview"]' )
    
    if( fastview_buttons ){

        // Add Observe To "Load More" Button in Shop page
        let product_list_container = document.getElementById('blueins-load_more')

        if( product_list_container != null ){
            const shopFastviewObserve = new MutationObserver( mutation => {
                document.querySelectorAll( '[data-el="blu_fastview"]' ).forEach( button => button.addEventListener('click', blu_fastview ) )
            } )

            shopFastviewObserve.observe(product_list_container,{
                attributes: true,
                characterData: true,
                childList: true,
                subtree: true,
                attributeOldValue: true,
                characterDataOldValue: true
            })
        }

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
            action: 'BFV_fastview',
            data: {
                product_id
            },
            onloadstart_callback(){
                $fastview.load()
            }
        } )
        .then( resolve => {
            // *************************** Then Block
            $fastview.element.querySelector('.fastview__container').innerHTML = resolve
            $fastview.show()
            $fastview.element.querySelector('#close-fastview-menu-button').addEventListener( 'click', e => $fastview.hidden() )

            $('.fastview-slick-list').slick({
                dots: true,
                infinite: false,
                nextArrow: `<button type="button" class="slick-next"><img src="${PAGE_URL}/assets/img/Icon/Dark/next.svg" alt="Next"></button>`,
                prevArrow: `<button type="button" class="slick-prev"><img src="${PAGE_URL}/assets/img/Icon/Dark/prev.svg" alt="Prev"></button>`,
                responsive: [{}]
            });
            // *************************** Then Block
        } )
        .catch( reject => {
            // *************************** Catch Block
            console.log(reject)
            // *************************** Catch Block
        } )

    }


// *************************************************************************** DOM Content Loaded
} )



function fastview( $element ){
    let container = $element.querySelector( '.fastview__container' )
    let preloader = $element.querySelector( '.preloader' )
    return {
        element: $element,
        load(){
            // *************************** Load
            css( $element, {
                display: 'block'
            } )

            setTimeout( () => {
                css( $element, {
                    display: 'block',
                    opacity: 1
                } )
                css( preloader, {
                    opacity: 1
                } )
            }, 200 )    
            // *************************** Load
        },
        show(){
            // *************************** Show
            css( $element, {
                display: 'block'
            } )

            setTimeout( () => {
                css( $element, {
                    display: 'block',
                    opacity: 1
                } )
                css( container, {
                    transform: 'translate(-50%, -50%) scale(1)'
                } )
            }, 200 )
            // *************************** Show
        },
        hidden(){
            // *************************** Hidden
            css( $element, {
                opacity: 0
            } )

            css( container, {
                transform: 'translate(-50%, -50%) scale(0)'
            } )

            setTimeout( () => {
                css( $element, {
                    display: 'none'
                } )
            }, 200 )
            // *************************** Hidden
        }
    }
} 