document.addEventListener('DOMContentLoaded', () => {
    let form = document.querySelector('#blu_widget__subscribeForm')
    form.addEventListener('submit', blu_subcribe_submin )
    
    function blu_subcribe_submin(event){
        event.preventDefault()
        let email = this.bluemail.value

        subscribeSendRequest({
            method: 'GET',
            url: bluajax.url,
            action: 'blu_subscriber',
            data: {
                formData: email,
                security: bluajax.nonce
            }
        }).then(data => {
            document.querySelector('#blu_widget_messege').innerHTML = data
            document.querySelector('#blu_widget_messege').style = 'opacity: 1'
            document.querySelector('#blu_widget-email').value = ''
            document.querySelector('#blu_widget-email-label').classList.remove('el-label-focus')
            setTimeout(()=>{
                document.querySelector('#blu_widget_messege').style = 'opacity: 0'
            }, 5000)
        }).catch(error => {
            document.querySelector('#blu_widget_messege').innerHTML = 'error'
        })
    }
    
} );


