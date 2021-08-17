/**
 * 
 * This Script for all web pages with products.
 * 
 */


document.addEventListener( 'DOMContentLoaded', event => {
// *************************************************************************** DOM Content Loaded


    const $fastview = fastview( document.querySelector('.fastview') )
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
                $fastview.load()
            }
        } )
        .then( resolve => {
            // *************************** Then Block
            $fastview.show()
            $fastview.closer.addEventListener( 'click', e => $fastview.hidden() )
            console.log(resolve)
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
    let closer = document.getElementById('close-fastview-menu-button')
    let container = $element.querySelector( '.fastview__container' )
    let preloader = $element.querySelector( '.preloader' )
    return {
        element: $element,
        closer: closer,
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