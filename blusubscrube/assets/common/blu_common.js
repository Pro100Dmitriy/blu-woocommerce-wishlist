//method, url + `?action=${action}&category=${category}&min=${min}&max=${max}&order=${order}`
function subscribeSendRequest(object){
    return new Promise( (resolve, reject) => {
        const xhr = new XMLHttpRequest()
        let method = object.method
        let url = object.url
        let action = object.action
        let data = object.data

        let dataUrl = ''
        let dataKeys = Object.keys(data)
        dataKeys.forEach( key => {
           dataUrl += `&${key}=${data[key]}`
        } )

        console.log(url + `?action=${action}` + dataUrl)

        xhr.open( method, url + `?action=${action}` + dataUrl )

        xhr.onloadstart = () => {
            console.log('loaded')
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