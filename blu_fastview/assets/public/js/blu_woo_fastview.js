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

            //productVariation( $fastview.element )
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
 * ************************ Vaiable Colors Start ************************
 */

function updateHTML( circleEl, childrenArr, optionsWoo, circle, nameHere, space ){

    for( let i=0; i < circle.length; i++ ){
        circle[i].classList.remove('element-select'); 
    }
    circleEl.classList.add('element-select');

    let circleElId = circleEl.getAttribute('id');

    let fullval = childrenArr.map( opt => {
        if( opt.value.slice( opt.value.indexOf('#') ) == circleElId ){
        return opt.value;
        }
    } );

    fullval = fullval.filter( optVal => {if( optVal != undefined ) return optVal } );
    $( optionsWoo ).val(fullval[0]).change();

    // Set Name Color
    let nameContainer = space.querySelector( `#${nameHere}` );
    nameContainer.innerHTML = fullval[0].slice( 0, fullval[0].indexOf('#') );

}

function updateIMG(targetEl, space){
    let BFV_img_variation_src = [ ...space.querySelector('.fastview__src_variation').children ]

    let activeID
    if( BFV_img_variation_src ){
        BFV_img_variation_src.forEach( item => {
            let harpIdex = item.getAttribute('data-id').indexOf('#')
            if( item.getAttribute('data-id').slice( harpIdex ) == targetEl ){
                activeID = item
            }
        })
    }

    let variation_slider = space.querySelector('.fastview__container__slider')
    let firstElement = variation_slider.querySelector('.slick-track').children[0]
    let firstIMG = firstElement.querySelector('img')

    //let variation_control_nav = document.querySelector('.flex-control-nav')
    //let controlFirst_IMG = variation_control_nav.children[0].children[0]

    firstIMG.setAttribute('src', activeID.getAttribute('src') )
    firstIMG.setAttribute('data-src', activeID.getAttribute('data-src') )
    firstIMG.setAttribute('data-large_image', activeID.getAttribute('data-large_image') )
    firstIMG.setAttribute('srcset', activeID.getAttribute('srcset') )
}

function createSquare( listArray, whereId, space ){
    let arraySquare = []

    listArray.forEach( (child, childIndex) => {
        if( childIndex != 0 ){
        let listContainer = space.querySelector( `#${whereId}` )

        let liElem = document.createElement('li')
        let spanElem = document.createElement('span')

        let razmerCod = child.value.slice( child.value.indexOf('#') )
        let razmerCodHTML = child.value.slice( child.value.indexOf('#') + 1 )
        let razmerName = child.value.slice( 0, child.value.indexOf('#') )

        spanElem.setAttribute('class', 'details-select-square')
        spanElem.setAttribute('id', razmerCod.trim());
        spanElem.textContent = razmerCodHTML

        liElem.setAttribute('class', 'details__razmer__list__item')
        liElem.setAttribute('name', razmerName)
        liElem.appendChild( spanElem )

        arraySquare.push( spanElem )
        listContainer.appendChild( liElem )
        }
    } )

    return arraySquare;
}

function createCircle( listArray, whereId, space ){
    let arratCircle = [];

    listArray.forEach( (child, childIndex, childArray)=>{

        if( childIndex != 0 ){
        let listContainer = space.querySelector( `#${whereId}` );

        let liElem = document.createElement('li');
        let spanElem = document.createElement('span');

        // Fint Color #Cod
        let colorCod = child.value.slice( child.value.indexOf('#') );
        let colorName = child.value.slice( 0, child.value.indexOf('#') );

        spanElem.setAttribute('class', 'details-select-circle');
        spanElem.setAttribute('style', `background: ${ colorCod.trim() }`);
        spanElem.setAttribute('id', colorCod.trim());

        liElem.setAttribute('class', 'details__colors__list__item');
        liElem.setAttribute('name', colorName);
        liElem.appendChild( spanElem );

        arratCircle.push( spanElem );
        listContainer.appendChild( liElem );
        }
        
    } );

    return arratCircle;
}

function productVariation( space ){

    let optionsProductCzvet = space.querySelector('[data-attribute_name="attribute_czvet"]')
    let optionsProductRazmer = space.querySelector('[data-attribute_name="attribute_razmer"]')
    let optionsProductPaRazmer = space.querySelector('[data-attribute_name="attribute_pa_razmer"]')

    if( optionsProductCzvet ){

        let childrenCzvet = [ ...optionsProductCzvet.children ]
        
        let circleCzvet = createCircle( childrenCzvet, 'setElementHere__czvet', space )
        
        updateHTML( circleCzvet[0], childrenCzvet, optionsProductCzvet, circleCzvet, 'setNameHere__czvet', space )

        circleCzvet.forEach( circleEl => {
            circleEl.addEventListener('click', (event)=>{
                event.preventDefault();
                let targetEl = event.target.getAttribute('id')

                updateHTML( circleEl, childrenCzvet, optionsProductCzvet, circleCzvet, 'setNameHere__czvet', space );
                updateIMG( targetEl, space );

            })
        })

    }
    if( optionsProductRazmer ){

        let childrenRazmer = [ ...optionsProductRazmer.children ]

        let squareRazmer = createSquare( childrenRazmer, 'setElementHere__razmer', space )

        updateHTML( squareRazmer[0], childrenRazmer, optionsProductRazmer, squareRazmer, 'setNameHere__razmer', space )

        squareRazmer.forEach( circleEl => {
        circleEl.addEventListener('click', (event)=>{
            event.preventDefault();

            updateHTML( circleEl, childrenRazmer, optionsProductRazmer, squareRazmer, 'setNameHere__razmer', space );

        })
        })

    }
    if( optionsProductPaRazmer ){

        let childrenRazmer = [ ...optionsProductPaRazmer.children ]

        let squareRazmer = createSquare( childrenRazmer, 'setElementHere__pa_razmer', space )

        updateHTML( squareRazmer[0], childrenRazmer, optionsProductPaRazmer, squareRazmer, 'setNameHere__pa_razmer', space )

        squareRazmer.forEach( circleEl => {
        circleEl.addEventListener('click', (event)=>{
            event.preventDefault();

            updateHTML( circleEl, childrenRazmer, optionsProductPaRazmer, squareRazmer, 'setNameHere__pa_razmer', space );

        })
        })

    }

}

/**
 * ************************ Vaiable Colors End ************************
 */