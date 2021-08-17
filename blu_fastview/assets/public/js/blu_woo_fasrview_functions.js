/*
const sendObject = {
    method: 'GET',
    url: ajax_url,
    query
    onloadstart_callback(){},
}
*/

function fastviewSendRequest( sendObject ){
    return new Promise( (resolve, reject) => {
        const xhr = new XMLHttpRequest()

        let dataURL = ''
        let dataKeys = Object.keys( sendObject.data )
        dataKeys.forEach( key => {
            dataURL += `&${key}=${data[key]}`
        } )

        xhr.open( sendObject.method, sendObject.url + `?action="${sendObject.action}"` + dataURL )
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