/**
 * 
 * This Script for all web pages with products.
 * 
 */


const PAGE_URL = global_params.url
const AJAX_URL = woocommerce_params.ajax_url


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

            const BFV_variation = new BFV_Blueins_Variation({
                space: 'fastview',
                colorPaContainerId: 'setElementHere__pa_czvet',
                colorPaNameContainerId: 'setNameHere__pa_czvet',
                razmerPaContainerId: 'setElementHere__pa_razmer',
                razmerPaNameContainerId: 'setNameHere__pa_razmer',
                colorContainerId: 'setElementHere__czvet',
                colorNameContainerId: 'setNameHere__czvet',
                razmerContainerId: 'setElementHere__razmer',
                razmerNameContainerId: 'setNameHere__razmer',
                jquery: $
            })

            //let content = $fastview.element.querySelector('.fastview__container__content')
            //content.addEventListener('touchmove', scroll)

            bfv_quantity( $fastview.element )
            bfv_ajax_add_to_cart( $fastview.element )
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
            css( document.querySelector('body'), {
                'overflow-y': 'clip'
            } )

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
            css( document.querySelector('body'), {
                'overflow-y': 'scroll'
            } )

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



/**
 * ************************ Scroll Animation ************************
 */

 function scroll(e){
    let content = document.querySelector('.fastview__container__content')
    let scroll = e.changeTouches.clientY

    //console.log(scroll)
    //console.log(e)

    css( e.target, {
        height: `calc( 50% + ${scroll}px )`
    } )
}

/**
 * ************************ Scroll Animation ************************
 */



/**
 * ************************ Quantity ************************
 */

function bfv_quantity( space ){

    // Minus
    let allMinusButtons = space.querySelector('.el-quantity__minus')
  
    allMinusButtons.addEventListener( 'click', option => {
        option.preventDefault()

        let parentNodeElement = allMinusButtons.parentNode
        let input = parentNodeElement.querySelector('#quantity')
        let inputValue = parseInt( input.value )

        if( inputValue <= 1 ){
            input.setAttribute('value', 1);
        }else{
            input.setAttribute('value', inputValue - 1);
        }
    } )
  
    // Plus
    let allPlusButtons = document.querySelector('.el-quantity__plus')
  
    allPlusButtons.addEventListener( 'click', option => {
        option.preventDefault()

        let parentNodeElement = allPlusButtons.parentNode
        let input = parentNodeElement.querySelector('#quantity')
        let inputValue = parseInt( input.value )

        input.setAttribute('value', inputValue + 1)
    } )
  
}

/**
 * ************************ Quantity ************************
 */



/**
 * ************************ Add To Cart (Fast View) ************************
 */

function bfv_ajax_add_to_cart( space ){
    let button_fast_view = space.querySelector('.single_add_to_cart_button')

    if( button_fast_view ){

        button_fast_view.addEventListener('click', addToCartSingle)

        function addToCartSingle(event){
            event.preventDefault()

            let action = 'blueins_cart_add_single'                                                                                                                                                                          // Action
            let product_id = space.querySelector('input[name="product_id"]') ? space.querySelector('input[name="product_id"]').value : space.querySelector('.single_add_to_cart_button').value                     // Product ID
            let product_qty = space.querySelector('#quantity').value ? space.querySelector('#quantity').value : 1                                                                                                     // Product Quantity
            let variaction_id = space.querySelector('input[name="variation_id"]') ? space.querySelector('input[name="variation_id"]').value : ''                                                                      // Variaction ID
            let color = space.querySelector('#czvet') ? space.querySelector('#czvet').value.replace(' #', '_') : ''                                                                                                   // Color
            let size = space.querySelector('#razmer') ? space.querySelector('#razmer').value.replace(' #', '_') : ''                                                                                                  // Size

            let pa_color = space.querySelector('#pa_czvet') ? space.querySelector('#pa_czvet').value : ''
            let pa_size = space.querySelector('#pa_razmer') ? space.querySelector('#pa_razmer').value : ''

            let preloader = `
                <div class="preloader">
                    <svg version="1.1" id="L5" width="60px" height="60px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                        <circle fill="#fff" stroke="none" cx="6" cy="50" r="6">
                        <animateTransform 
                            attributeName="transform" 
                            dur="1s" 
                            type="translate" 
                            values="0 15 ; 0 -15; 0 15" 
                            repeatCount="indefinite" 
                            begin="0.1"/>
                        </circle>
                        <circle fill="#fff" stroke="none" cx="30" cy="50" r="6">
                        <animateTransform 
                            attributeName="transform" 
                            dur="1s" 
                            type="translate" 
                            values="0 10 ; 0 -10; 0 10" 
                            repeatCount="indefinite" 
                            begin="0.2"/>
                        </circle>
                        <circle fill="#fff" stroke="none" cx="54" cy="50" r="6">
                        <animateTransform 
                            attributeName="transform" 
                            dur="1s" 
                            type="translate" 
                            values="0 5 ; 0 -5; 0 5" 
                            repeatCount="indefinite" 
                            begin="0.3"/>
                        </circle>
                    </svg>
                </div>
            `

            if( product_id ){

                fastviewSendRequest( {
                    method: 'GET',
                    url: AJAX_URL,
                    action,
                    data: {
                        product_id,
                        product_qty,
                        variaction_id,
                        color,
                        size,
                        pa_color,
                        pa_size
                    },
                    onloadstart_callback(){
    
                        document.querySelector('.blueins_cart_center').insertAdjacentHTML('afterbegin', preloader)
                        $('body').css('overflow-y','hidden');
                        $('#cart-menu').addClass('right-ziro');
                        $('#cart-overlay').css('visibility','visible');
                        setTimeout(function(){
                            $('#cart-overlay').css('background','rgba(180,197,204, 0.4)');
                        }, 100);
    
                    }
                } )
                .then( data => document.querySelector('.blueins_cart_center').innerHTML = data )
                .catch( error => console.log(error) )

            }else{
                //console.log(this)
                //let event = new Event('click')
                //this.dispatchEvent(event)
            }
            
        }

    }
}
    
/**
 * ************************ Add To Cart (Fast View) ************************
 */