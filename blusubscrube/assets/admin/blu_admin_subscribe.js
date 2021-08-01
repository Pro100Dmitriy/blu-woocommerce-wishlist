document.addEventListener('DOMContentLoaded', event => {

    let send_button = document.getElementById('blueins_button_admin')
    let send_message = document.getElementById('blu_text')

    send_button.addEventListener('click', () => {
        let messege = send_message.value
        if( messege == '' ){
            alert('Введите текст рассылки!!!')
            return
        }

        subscribeSendRequest({
            method: 'GET',
            url: ajaxurl,
            action: 'blu_subscriber_admin',
            data: {
                formData: messege
            }
        }).then(data => {
            document.querySelector('#messege_div').insertAdjacentHTML('afterend', data)
        }).catch(error => {
            document.querySelector('#messege_div').innerHTML = 'error'
        })
    })

})