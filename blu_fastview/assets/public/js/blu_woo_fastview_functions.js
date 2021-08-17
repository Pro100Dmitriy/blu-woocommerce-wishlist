function fastviewSendRequest( sendObject ){
    return new Promise( (resolve, reject) => {
        const xhr = new XMLHttpRequest()

        let dataURL = ''
        let dataKeys = Object.keys( sendObject.data )
        dataKeys.forEach( key => {
            dataURL += `&${key}=${sendObject.data[key]}`
        } )

        xhr.open( sendObject.method, sendObject.url + `?action=${sendObject.action}` + dataURL )
        xhr.onloadstart = sendObject.onloadstart_callback()
        xhr.onload = () => {
            resolve( xhr.response )
        }
        xhr.onerror = () => {
            reject( xhr.response )
        }
        xhr.send()

    } )
}


function css( $el, styles = {} ){
    Object.assign( $el.style, styles)
}