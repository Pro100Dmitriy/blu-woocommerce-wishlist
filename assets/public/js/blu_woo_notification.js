class Blueins_Wishlist_Notification{

    model = []

    constructor({$block, $list}){
        this.$block = $block
        this.$list = $list
    }

    add_event(){
        let notification_block__close = this.$list.querySelectorAll('.close-button')
        notification_block__close.forEach( close => close.addEventListener( 'click', this.close_notification.bind(this) ) )
    }

    render(){
        this.$list.innerHTML = ''
        
        this.model.forEach( (item, index) => {
            let toHTML = this[item.type](item.messege, index)
            this.$list.insertAdjacentHTML('beforeend', toHTML)
        } )

        this.add_event()
    }
    
    add( notification_messege ){
        this.model.push({
            id: this.model.length,
            type: 'seccess',
            messege: notification_messege
        })
        this.render()
    }

    remove( id ){
        delete this.model[id]
        this.render()
    }

    close_notification( event ){
        event.preventDefault()
        event.target.parentNode.classList.add('notification-item--hidden')
        
        this.remove( event.target.parentNode.getAttribute('data-id') )
    }

    seccess(messege, id){
        return `<li data-id="${id}" class="notification-item">
                    <button class="close-button notification-item__close">Remove Message</button>
                    <div class="notification-item__message">
                        <p>${messege}<p>
                    </div>
                </li>`
    }

    warning(messege){
        return `<li class="notification-item">
                    <button class="close-button notification-item__close">Remove Message</button>
                    <div class="notification-item__message">
                        <p>${messege}<p>
                    </div>
                </li>`
    }

    error(messege){
        return `<li class="notification-item">
                    <button class="close-button notification-item__close">Remove Message</button>
                    <div class="notification-item__message">
                        <p>${messege}<p>
                    </div>
                </li>`
    }

}


const Notification = new Blueins_Wishlist_Notification({
    $block: document.querySelector('#blueins__notification-block'),
    $list: document.querySelector('.blueins-notification-block__list'),
})
