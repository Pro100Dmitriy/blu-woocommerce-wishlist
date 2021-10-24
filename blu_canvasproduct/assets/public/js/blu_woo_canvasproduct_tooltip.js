
const template = (data) => `
    <div class="tooltip-title">${data.title}</div>
    <div class="tooltip-price">${data.price}</div>
`

function tooltip($el){
    const clear = () => { $el.innerHTML = '' }
    return {
        show( {left, top}, data ) {
            const { height, width } = $el.getBoundingClientRect()
            clear()
            css($el, {
                display: 'block',
                top: top + height - 20 + 'px',
                left: left + width / 3 + 'px'
            })
            $el.insertAdjacentHTML( 'afterbegin', template(data) )
        },
        hide() {
            css($el, { display: 'none' })
        }
    }
}