/**
 * 
 * This Script for wishlist page.
 * 
 */


document.addEventListener( 'DOMContentLoaded', event => {
// *************************************************************************** DOM Content Loaded

    /**
     * Delete From Wishlist
     */
    function removeItemRequest(object){
        return new Promise( (resolve, reject) => {
            const xhr = new XMLHttpRequest()
            let method = object.method
            let url = object.url
            let action = object.action
            let data = object.data
            let preloader = `
                <div class="preloader">
                    <svg version="1.1" id="L5" width="60px" height="60px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                        <circle fill="#000" stroke="none" cx="6" cy="50" r="6">
                        <animateTransform 
                            attributeName="transform" 
                            dur="1s" 
                            type="translate" 
                            values="0 15 ; 0 -15; 0 15" 
                            repeatCount="indefinite" 
                            begin="0.1"/>
                        </circle>
                        <circle fill="#000" stroke="none" cx="30" cy="50" r="6">
                        <animateTransform 
                            attributeName="transform" 
                            dur="1s" 
                            type="translate" 
                            values="0 10 ; 0 -10; 0 10" 
                            repeatCount="indefinite" 
                            begin="0.2"/>
                        </circle>
                        <circle fill="#000" stroke="none" cx="54" cy="50" r="6">
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

            let dataUrl = ''
            let dataKeys = Object.keys(data)
            dataKeys.forEach( key => {
                dataUrl += `&${key}=${data[key]}`
            } )

            xhr.open( method, url + `?action=${action}` + dataUrl )

            xhr.onloadstart = () => {
                document.querySelector('.like__content__information').classList.add('like__content__information__load')
                document.querySelector('.like__content__information').insertAdjacentHTML('afterbegin', preloader)
            }

            xhr.onload = () => {
                document.querySelector('.like__content__information').classList.remove('like__content__information__load')
                document.querySelector('.like__content__information').querySelector('.preloader').remove()
                resolve( xhr.response )
            }

            xhr.onerror = () => {
                reject( xhr.response )
            }

            xhr.send()

        } )
    }


    let delete_button = document.querySelectorAll('.delete_from_wishlist')
    let delete_button_big = document.querySelectorAll('.delete_from_wishlist_big')

    if( delete_button[0] && delete_button_big[0] ){

        delete_button.forEach( item => item.addEventListener( 'click', removeFromWishlist ) )
        delete_button_big.forEach( item_big => item_big.addEventListener( 'click', removeFromWishlist ) )

        function removeFromWishlist(event){
            event.preventDefault();

            let url = woocommerce_params.ajax_url
            let wishlist_id = event.target.getAttribute( 'data-arr-id' )

            removeItemRequest({
                method: 'GET',
                url,
                action: 'wishlist_remove',
                data: {
                    wishlist_id
                }
            }).then(resolve => {
                // *************************** Then Block
                let regexp = /<messege><p>.*<\/messege>/
                let resolve_messege = resolve.match(regexp)[0].replace('<messege>','').replace('</messege>','')

                let resolve_HTML = resolve.replace(resolve.match(regexp)[0], '')
                document.querySelector('.like__content__information').innerHTML = resolve_HTML

                //notification_add(resolve_messege)
                Notification.add(resolve_messege)

                document.querySelectorAll('.add_to_cart_from_wishlist').forEach( item_add => item_add.addEventListener( 'click', addToCartFromWishlist ) )
                document.querySelectorAll('.delete_from_wishlist').forEach( item => item.addEventListener( 'click', removeFromWishlist ) )
                document.querySelectorAll('.delete_from_wishlist_big').forEach( item_big => item_big.addEventListener( 'click', removeFromWishlist ) )
                // *************************** Then Block
            }).catch(reject => {
                // *************************** Catch Block
                //console.log(reject)
                // *************************** Catch Block
            })

        }

    }





    /**
    * Add To Cart From Wishlist
    */
    function addCartRequest(object){
        return new Promise( (resolve, reject) => {
            const xhr = new XMLHttpRequest()
            let method = object.method
            let url = object.url
            let action = object.action
            let data = object.data
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

            let dataUrl = ''
            let dataKeys = Object.keys(data)
            dataKeys.forEach( key => {
                dataUrl += `&${key}=${data[key]}`
            } )

            //console.log(url + `?action=${action}` + dataUrl)

            xhr.open( method, url + `?action=${action}` + dataUrl )

            xhr.onloadstart = () => {
                document.querySelector('.blueins_cart_center').insertAdjacentHTML('afterbegin', preloader)

                $('body').css('overflow-y','hidden');
                $('#cart-menu').addClass('right-ziro');
                $('#cart-overlay').css('visibility','visible');
                setTimeout(function(){
                    $('#cart-overlay').css('background','rgba(180,197,204, 0.4)');
                }, 100);
            }

            xhr.onload = () => {
                resolve( xhr.response )
            }

            xhr.onerror = () => {
                reject( xhr.response )
            }

            xhr.send()

        } )
    }

    let add_to_cart = document.querySelectorAll('.add_to_cart_from_wishlist')

    if( add_to_cart[0] ){

        add_to_cart.forEach( item => item.addEventListener( 'click', addToCartFromWishlist ) )

        function addToCartFromWishlist(event){
            event.preventDefault()

            let url = woocommerce_params.ajax_url
            let wishlist_id = event.target.getAttribute('data-arr-id')
            if( wishlist_id == null ){
                wishlist_id = event.target.parentNode.getAttribute('data-arr-id')
            }

            addCartRequest({
                method: 'GET',
                url,
                action: 'wishlist_add_to_cart',
                data: {
                    wishlist_id
                }
            }).then(resolve => {
                // *************************** Then Block
                let regexp_messege = /<messege>[\s<a-zA-Z0-9а-яА-Я="_>\-/():,#.!?]*<\/messege>/gm
                let resolve_messege_HTML = resolve.match(regexp_messege)[0].replace('<messege>','').replace('</messege>','')

                let regexp_wishlist = /<wishlist>[\s<a-zA-Z0-9а-яА-Я="_>\-/():,#.!?&;%+]*<\/wishlist>/gm
                let resolve_wishlist_HTML = resolve.match(regexp_wishlist)[0].replace('<wishlist>','').replace('</wishlist>','')
                
                let regexp_cart = /<cart>[\s<a-zA-Z0-9а-яА-Я="_>\-/():,#.!?&;%+]*<\/cart>/gm
                let resolve_cart_HTML = resolve.match(regexp_cart)[0].replace('<cart>','').replace('</cart>','')

                //notification_add(resolve_messege_HTML)
                Notification.add(resolve_messege_HTML)

                let cart_container = document.querySelector('.blueins_cart_center')
                cart_container.innerHTML = resolve_cart_HTML

                let wishlist_container = document.querySelector('.like__content__information')
                wishlist_container.innerHTML = resolve_wishlist_HTML

                document.querySelectorAll('.delete_from_wishlist').forEach( item => item.addEventListener( 'click', removeFromWishlist ) )
                document.querySelectorAll('.delete_from_wishlist_big').forEach( item_big => item_big.addEventListener( 'click', removeFromWishlist ) )
                document.querySelectorAll('.add_to_cart_from_wishlist').forEach( item_add => item_add.addEventListener( 'click', addToCartFromWishlist ) )
                // *************************** Then Block
            }).catch(reject => {
                // *************************** Catch Block
                //console.log(reject)
                // *************************** Catch Block
            })

        }

    }

// *************************************************************************** DOM Content Loaded
} )