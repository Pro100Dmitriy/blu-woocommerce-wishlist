class BFV_Blueins_Variation{
    constructor( options ){
        this.space = document.querySelector( `.${options.space}` ) ?? null
        if( this.space ){
            // Without Pa_
            this.setColorCircleContainer = this.space.querySelector(`#${options.colorContainerId}`)
            this.setColorNameContainer = this.space.querySelector(`#${options.colorNameContainerId}`)
            this.setRazmerSquareContainer = this.space.querySelector(`#${options.razmerContainerId}`)
            this.setRazmerNameContainer = this.space.querySelector(`#${options.razmerNameContainerId}`)
            // With Pa_
            this.setColorPaCircleContainer = this.space.querySelector(`#${options.colorPaContainerId}`)
            this.setColorPaNameContainer = this.space.querySelector(`#${options.colorPaNameContainerId}`)
            this.setRazmerPaSquareContainer = this.space.querySelector(`#${options.razmerPaContainerId}`)
            this.setRazmerPaNameContainer = this.space.querySelector(`#${options.razmerPaNameContainerId}`)
            this.$ = options.jquery
            this.init()
        }
    }

    init(){
        let optionsProductPaCzvet = this.space.querySelector('[data-attribute_name="attribute_pa_czvet"]')
        let optionsProductPaRazmer = this.space.querySelector('[data-attribute_name="attribute_pa_razmer"]')
        let optionsProductCzvet = this.space.querySelector('[data-attribute_name="attribute_czvet"]')
        let optionsProductRazmer = this.space.querySelector('[data-attribute_name="attribute_razmer"]')

        if( optionsProductPaRazmer ){
            this.razmerInit( optionsProductPaRazmer, this.setRazmerPaNameContainer, this.setRazmerPaSquareContainer )
        }
        if( optionsProductPaCzvet ){
            this.colorInit( optionsProductPaCzvet, this.setColorPaNameContainer, this.setColorPaCircleContainer )
        }
        if( optionsProductRazmer ){
            this.razmerInit( optionsProductRazmer, this.setRazmerNameContainer, this.setRazmerSquareContainer )
        }
        if( optionsProductCzvet ){
            this.colorInit( optionsProductCzvet, this.setColorNameContainer, this.setColorCircleContainer )
        }
    }

    colorInit( selectPaCzvet, nameContainer, container ){
        let optionsPaCzvet = [ ...selectPaCzvet.options ]
            
        let colors = this.createCircle( optionsPaCzvet, container )
        this.renderHTML( colors.first, optionsPaCzvet, selectPaCzvet, colors.list, nameContainer )
    
        colors.list.forEach( color => color.addEventListener( 'click', (e) => {
            e.preventDefault()
            let targetElId = e.target
            this.renderHTML( e.target, optionsPaCzvet, selectPaCzvet, colors.list, nameContainer )
            this.updateIMG( targetElId )
        } ) )
    }

    razmerInit( selectPaRazmer, nameContainer, container ){
        let optionsPaRazmer = [ ...selectPaRazmer.options ]
      
        let razmers = this.createSquare( optionsPaRazmer, container )
        this.renderHTML( razmers.first, optionsPaRazmer, selectPaRazmer, razmers.list, nameContainer )
  
        razmers.list.forEach( razmer => razmer.addEventListener( 'click', (e) => {
            e.preventDefault()
            this.renderHTML( e.target, optionsPaRazmer, selectPaRazmer, razmers.list, nameContainer )
        } ) )
    }

    createCircle( optionsArray, container ){
        optionsArray.forEach( (child, childIndex) => {
            if( childIndex != 0 ){
                let colorCod = child.innerHTML.slice( child.innerHTML.indexOf('#') ).trim()
                let colorName = child.innerHTML.slice( 0, child.innerHTML.indexOf('#') ).trim()
        
                let HTML = `
                    <li class="details__colors__list__item" name="${ colorName }">
                        <span   class="details-select-circle element-select" 
                                style="background: ${ colorCod }" 
                                id="${ colorCod }" 
                                data-value="${child.value}">
                        </span>
                    </li>
                `

                container.insertAdjacentHTML('afterbegin', HTML)
            }
        } );

        return {
            first: container.querySelector( `li:first-child span` ),
            list: container.querySelectorAll( `.details__colors__list__item span` )
        }
    }

    createSquare( optionsArray, container ){
        optionsArray.forEach( (child, childIndex) => {
            if( childIndex != 0 ){
                let razmerCod = child.innerHTML.slice( child.innerHTML.indexOf('#') ).trim()
                let razmerCodHTML = child.innerHTML.slice( child.innerHTML.indexOf('#') + 1 )
                let razmerName = child.innerHTML.slice( 0, child.innerHTML.indexOf('#') )

                let HTML = `
                    <li class="details__razmer__list__item" name="${ razmerName }">
                        <span   class="details-select-square element-select"
                                id="${ razmerCod }" 
                                data-value="${child.value}">
                        ${razmerCodHTML}
                        </span>
                    </li>
                `

                container.insertAdjacentHTML('afterbegin', HTML)
            }
        } )

        return {
            first: container.querySelector( `li:first-child span` ),
            list: container.querySelectorAll( `.details__razmer__list__item span` )
        };
    }

    renderHTML( active, optionsHTML, selectHTML, list, placeName){
        for( let item of list ){
            item.classList.remove('element-select')
        }
        active.classList.add('element-select')
      
        let circleFirstValue = active.getAttribute('data-value')
        let selectedValue = optionsHTML.map( option => {
            if( option.value == circleFirstValue ) {
                return option.value
            }
        } )
      
        selectedValue = selectedValue.filter( optVal => {
           if( optVal != undefined ) return optVal 
        } );
        
        //selectHTML.value = selectedValue
        this.$( selectHTML ).val(selectedValue[0]).change();
      
        // Set Name Color
        let name = selectHTML.querySelector(`[value="${selectedValue[0]}"]`).innerHTML
        placeName.innerHTML = name.slice( 0, name.indexOf('#') )
    }

    updateIMG( targetEl ){
        let activeID
        let src_variations = this.space.querySelectorAll('.fastview__src_variation span')
        if( src_variations ){
            src_variations.forEach( child => {
                if( child.getAttribute('data-id') == targetEl.getAttribute('data-value') ){
                    activeID = {
                        'src': child.getAttribute('src'),
                        'data-src': child.getAttribute('data-src'),
                        'data-large_image': child.getAttribute('data-large_image'),
                        'srcset': child.getAttribute('srcset')
                    }
                }
            } )
        }
        // if( img_variation_src ){
        //     if( targetEl.getAttribute('id').indexOf('#') ){
        //         img_variation_src.forEach( item => {
        //             let harpIdex = item['id'].indexOf('#')
        //             if( item['id'].slice( harpIdex ) == targetEl.getAttribute('id') ){
        //                 activeID = item
        //             }
        //         })
        //     }else{
        //         img_variation_src.forEach( item => {
        //             if( item['id'] == targetEl.getAttribute('data-value') ){
        //                 activeID = item
        //             }
        //         })     
        //     }
        // }

        let variation_slider = this.space.querySelector('.slick-item:first-child')
        //let firstElement = variation_slider.children[0]
        //let firstURL = firstElement.children[0]
        let firstIMG = variation_slider.querySelector('img')

        //let variation_control_nav = document.querySelector('.flex-control-nav')
        // let variation_control_nav = this.space.querySelector('.slick-track')
        // console.log(variation_control_nav)
        // variation_control_nav.style = 'opacity: 1; width: 1500px; transform: translate3d(-1000px, 0px, 0px);'
        // css(variation_control_nav, {
        //     opacity: 1,
        //     width: '1500px',
        //     transform: 'translate3d(0px, 0px, 0px);'
        // })
        //let controlFirst_IMG = variation_control_nav.children[0].children[0]

        //firstElement.setAttribute('data-thumb', activeID['data-thumb'])
        //firstURL.setAttribute('href', activeID['data-src'])

        firstIMG.setAttribute('src', activeID['src'])
        firstIMG.setAttribute('data-src', activeID['data-src'])
        firstIMG.setAttribute('data-large_image', activeID['data-large_image'])
        firstIMG.setAttribute('srcset', activeID['srcset'])

        //controlFirst_IMG.setAttribute('src', activeID['data-thumb'])
    }
}



/**
 * 
 * let options = {
 *  space: 'DOMElement',
 * }
 * 
 */