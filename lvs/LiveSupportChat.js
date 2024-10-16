class LiveSupportChat {

    // Executes when creating a new instance of the class
    constructor(options) {
        // Default options
        let defaults = {
            auto_login: true,
            php_directory_url: '',
            status: 'Idle',
            update_interval: 5000,
            current_chat_widget_tab: 1,
            conversation_id: null,
            notifications: true,
            files: {
                'authenticate': 'authenticate',
                'conversation': 'conversation',
                'conversations': 'conversations',
                'find_conversation': 'find_conversation',
                'post_message': 'post_message',
                'notifications': 'notifications',
                'logout': 'logout'
            }
        };
       // Assign new options
this.options = Object.assign(defaults, options);
// Chat icon template
var chatIconHTML = `
<a href="#" class="open-chat-widget"><h1>Live Support</h1>  
</a>
`;

var mylvsElement = document.getElementById("mylvs");
if (mylvsElement) {
    mylvsElement.insertAdjacentHTML('beforebegin', chatIconHTML);
}
      // Chat widget template
var chatWidgetHTML = `
<div class="chat-widget">
    <div class="chat-widget-header">
        <a href="#" class="previous-chat-tab-btn">&lsaquo;</a>
        <a href="#" class="close-chat-widget-btn">&times;</a>
    </div>
    <div class="chat-widget-content">
        <div class="chat-widget-tabs">
            <div class="chat-widget-tab chat-widget-login-tab">
                <form action="${this.options.files['authenticate']}" method="post">
                    <input type="text" name="name" placeholder="Your Name">
                    <input type="email" name="email" placeholder="Your Email" required>
                    <div class="msg"></div>
                    <button type="submit">Submit</button>
                </form>
            </div>
            <div class="chat-widget-tab chat-widget-conversations-tab"></div>
            <div class="chat-widget-tab chat-widget-conversation-tab"></div>
        </div>
    </div>
</div>
`;

var mylvscbElement = document.getElementById("mylvscb");
if (mylvscbElement) {
    mylvscbElement.insertAdjacentHTML('afterend', chatWidgetHTML);
}
        // Declare class variables for easy access
        this.openWidgetBtn = document.querySelector('.open-chat-widget');
        this.container = document.querySelector('.chat-widget');
        // Authenticate user if cookie secret exists
        if (this.autoLogin && document.cookie.match(/^(.*;)?\s*chat_secret\s*=\s*[^;]+(.*)?$/)) {
            // Execute GET AJAX request to retireve the conversations
            this.fetchConversations(data => {
                // If respone not equals error
                if (data != 'error') {
                    // User is authenticated! Update the status and conversations tab content
                    this.status = 'Idle';
                    this.container.querySelector('.chat-widget-conversations-tab').innerHTML = data;
                    // Execute the conversation handler function
                    this._eventHandlers();
                    // Transition to the conversations tab
                    this.selectChatWidgetTab(2);
                }
            });
        }
        // Execute event handlers
        this._eventHandlers();
        // Update chat every X
        setInterval(() => this.update(), this.options.update_interval);
    }

    // AJAX method that will authenticate user based on an HTML Form element
    authenticateUser(form, callback = () => {}) {   
        // Execute POST AJAX request and attempt to authenticate the user
        fetch(this.phpDirectoryUrl + this.files['authenticate'], {
            cache: 'no-store',
            method: 'POST',
            body: new FormData(form)
        }).then(response => response.text()).then(data => callback(data));      
    }

    // AJAX method that will Logout user
    logOutUser(callback = () => {}) {
        document.cookie = 'chat_secret=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        fetch(this.phpDirectoryUrl + this.files['logout'], { cache: 'no-store' }).then(response => response.text()).then(data => callback(data));
    }

    // AJAX method that will fetch the conversations list associated with the user
    fetchConversations(callback = () => {}) {
        fetch(this.phpDirectoryUrl + this.files['conversations'], { cache: 'no-store' }).then(response => response.text()).then(data => callback(data));       
    }

    // AJAX method that will fetch the conversation associated with the user and ID param
    fetchConversation(id, callback = () => {}) {
        fetch(this.phpDirectoryUrl + this.files['conversation'] + `${this.files['conversation'].includes('?')?'&':'?'}id=` + id, { cache: 'no-store' }).then(response => response.text()).then(data => callback(data));
    }

    // Retrieve a conversation method
    getConversation(id, update = false, scrollPosition = null) {
        // Execute GET AJAX request
        this.fetchConversation(id, data => {
            // Update conversation ID variable
            this.conversationId = id;
            // Update the status
            this.status = 'Occupied';
            // Update the converstaion tab content
            if (!update) {
                this.container.querySelector('.chat-widget-conversation-tab').innerHTML = data;
            } else {
                let doc = (new DOMParser()).parseFromString(data, 'text/html');
                this.container.querySelector('.chat-widget-messages').innerHTML = doc.querySelector('.chat-widget-messages').innerHTML;
                this.container.querySelector('.chat-widget-message-header').innerHTML = doc.querySelector('.chat-widget-message-header').innerHTML;
            }
            // Transition to the conversation tab (tab 3)
            this.selectChatWidgetTab(3);  
            // Retrieve the input message form element 
            let chatWidgetInputMsg = this.container.querySelector('.chat-widget-input-message');
            // If the element exists
            if (chatWidgetInputMsg) {
                // Handle the content scroll position
                if (this.container.querySelector('.chat-widget-messages').lastElementChild) {
                    if (scrollPosition == null) {
                        // Scroll to the bottom of the messages container
                        this.container.querySelector('.chat-widget-messages').scrollTop = this.container.querySelector('.chat-widget-messages').lastElementChild.offsetTop;
                    } else {
                        // Scroll to the preserved position
                        this.container.querySelector('.chat-widget-messages').scrollTop = scrollPosition;
                    }
                }
                // Message submit event handler
                chatWidgetInputMsg.onsubmit = event => {
                    event.preventDefault();
                    // Retrieve the message input element
                    let chatMsgValue = chatWidgetInputMsg.querySelector('input[type="text"]').value;
                    if (chatMsgValue) {
                        // Decode emojis
                        chatWidgetInputMsg.querySelector('input[type="text"]').value = chatWidgetInputMsg.querySelector('input[type="text"]').value.replace(/([\u2700-\u27BF]|[\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2011-\u26FF]|\uD83E[\uDD10-\uDDFF])/g, match => '&#x' + match.codePointAt(0).toString(16).toUpperCase() + ';');
                        // Execute POST AJAX request that will send the captured message to the server and insert it into the database
                        fetch(chatWidgetInputMsg.action, { 
                            cache: 'no-store',
                            method: 'POST',
                            body: new FormData(chatWidgetInputMsg)
                        });
                        // Create the new message element
                        let chatWidgetMsg = document.createElement('div');
                        chatWidgetMsg.classList.add('chat-widget-message');
                        chatWidgetMsg.textContent = chatMsgValue;
                        chatWidgetMsg.innerHTML = chatWidgetMsg.innerHTML.replace(/\n\r?/g, '<br>');
                        // Add it to the messages container, right at the bottom
                        this.container.querySelector('.chat-widget-messages').insertAdjacentElement('beforeend', chatWidgetMsg);
                        // Reset the input form
                        chatWidgetInputMsg.querySelector('input[type="text"]').value = '';
                        chatWidgetInputMsg.querySelector('.files').value = '';
                        this.container.querySelector('.chat-widget-attachments').innerHTML = '';
                        // Scroll to the bottom of the messages container
                        this.container.querySelector('.chat-widget-messages').scrollTop = chatWidgetMsg.offsetTop;
                    }
                    // Focus the input message element
                    chatWidgetInputMsg.querySelector('input[type="text"]').focus();
                };
                // on change event handlers for attachments
                chatWidgetInputMsg.querySelector('.files').onchange = event => {
                    // Reset attachment label
                    document.querySelector('.chat-widget-attachments').innerHTML = '';
                    // Create attachment label
                    let attachmentLink = document.createElement('div');
                    attachmentLink.innerText = event.target.files.length + ' Attachment' + (event.target.files.length > 1 ? 's' : '');
                    document.querySelector('.chat-widget-attachments').appendChild(attachmentLink);
                    let removeAttachmentsLink = document.createElement('a');
                    removeAttachmentsLink.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                    document.querySelector('.chat-widget-attachments').appendChild(removeAttachmentsLink);
                    removeAttachmentsLink.onclick = event => {
                        event.preventDefault();
                        document.querySelector('.chat-widget-attachments').innerHTML = '';
                        chatWidgetInputMsg.querySelector('.files').value = '';
                    };
                };
                // Iterate all attachments in chat and add the event handler that will download them once clicked
                this.container.querySelectorAll('.chat-widget-message-attachments').forEach(element => element.onclick = () => {
                    element.nextElementSibling.querySelectorAll('a').forEach(element => element.click());
                });
                // Open attachment file dialog event handler
                if (chatWidgetInputMsg.querySelector('.actions .attach-files')) {
                    chatWidgetInputMsg.querySelector('.actions .attach-files').onclick = event => {
                        event.preventDefault();
                        chatWidgetInputMsg.querySelector('.files').click();
                    };
                }
                // Event handler that will open the emojis box when clicked
                chatWidgetInputMsg.querySelector('.actions .view-emojis i').onclick = event => {
                    event.preventDefault();
                    chatWidgetInputMsg.querySelector('.actions .emoji-list').classList.toggle('open');
                };
                // Iterate all emojis and add event handler that will add the particular emoji to the input message when clicked
                chatWidgetInputMsg.querySelectorAll('.actions .emoji-list span').forEach(element => element.onclick = () => {
                    chatWidgetInputMsg.querySelector('input[type="text"]').value += element.innerText;
                    chatWidgetInputMsg.querySelector('.actions .emoji-list').classList.remove('open');
                    chatWidgetInputMsg.querySelector('input[type="text"]').focus();
                });
            }
        });
    }

    // Update method that will update various aspects of the chat widget every X miliseconds
    update() {
        // If the current tab is 2
        if (this.currentChatWidgetTab == 2) {
            // Use AJAX to update the conversations list
            this.fetchConversations(data => {
                let doc = (new DOMParser()).parseFromString(data, 'text/html');
                this.container.querySelector('.chat-widget-conversations').innerHTML = doc.querySelector('.chat-widget-conversations').innerHTML;
                this._eventHandlers();
            }); 
        // If the current tab is 3 and the conversation ID variable is not NUll               
        } else if (this.currentChatWidgetTab == 3 && this.conversationId != null) {
            // Use AJAX to update the conversation  
            let scrollPosition = null;
            if (document.querySelector('.chat-widget-messages').lastElementChild && document.querySelector('.chat-widget-messages').scrollHeight - document.querySelector('.chat-widget-messages').scrollTop != document.querySelector('.chat-widget-messages').clientHeight) {
                scrollPosition = this.container.querySelector('.chat-widget-messages').scrollTop;
            } 
            this.getConversation(this.conversationId, true, scrollPosition);
        // If the current tab is 3 and the status is Waiting           
        } else if (this.currentChatWidgetTab == 3 && this.status == 'Waiting') {
            // Attempt to find a new conversation between the user and operator (or vice-versa)
            fetch(this.phpDirectoryUrl + this.files['find_conversation'], { cache: 'no-store' }).then(response => response.text()).then(data => {
                // If data includes automated message...
                if (data.includes('Msg: ')) {
                    // Check if message exists... We wouldn't want to add duplicates
                    let elementExists = false;
                    document.querySelectorAll('.chat-widget-message').forEach(element => {
                        if (element.innerHTML == data.replace('Msg: ','')) {
                            elementExists = true;
                        }
                    });
                    // If it doesn't exist, add it to the waiting chat
                    if (!elementExists) {
                        this.container.querySelector('.chat-widget-messages').innerHTML += `
                            <div class="chat-widget-message">${data.replace('Msg: ','')}</div>
                        `;
                    }
                } else if (data != 'error') {
                    // Success! Two users are now connected! Retrieve the new conversation
                    this.getConversation(data);
                }
            });               
        }
        // If notifications are enabled
        if (this.notifications) {
            // Fetch the notifications
            fetch(this.phpDirectoryUrl + this.files['notifications'], { cache: 'no-store' }).then(response => response.text()).then(data => {
                // Determine the current number of messages
                let numMessages = document.querySelector('.open-chat-widget').dataset.messages ? parseInt(document.querySelector('.open-chat-widget').dataset.messages) : 0;
                // If total number is greater than zero, update the open chat widget button data attribute
                if (parseInt(data) > 0) {
                    if (parseInt(data) > numMessages) {
                        new Audio('notification.ogg').play();
                    }
                    document.querySelector('.open-chat-widget').dataset.messages = data;
                } else if (document.querySelector('.open-chat-widget').dataset.messages) {
                    // If there are no new messages, delete the data attribute
                    delete document.querySelector('.open-chat-widget').dataset.messages;
                }
            });
        }
    }

    // Open chat widget method
    openChatWidget() {
        this.container.style.display = 'flex';
        // Animate the chat widget
        this.container.getBoundingClientRect();
        this.container.classList.add('open');
    }

    // Close chat widget method
    closeChatWidget() {
        this.container.classList.remove('open');
        this.status = 'Idle';
    }

    // Select chat tab - it will be used to smoothly transition between tabs
    selectChatWidgetTab(value) {
        // Update the current tab variable
        this.currentChatWidgetTab = value;
        // Select all tab elements and add the CSS3 property transform
        this.container.querySelectorAll('.chat-widget-tab').forEach(element => element.style.transform = `translateX(-${(value-1)*100}%)`);
        // If the user is on the first tab, hide the prev tab button element
        this.container.querySelector('.previous-chat-tab-btn').style.display = value > 1 ? 'block' : 'none';
        // Update the conversation ID variable if the user is on the first or second tab
        if (value == 1 || value == 2) {
            this.conversationId = null;
        }
        // If the user is on the login form tab (tab 1), remove the secret code cookie (logout)
        if (value == 1) {
            this.logOutUser();
        }
    }

    /* Below are class methods for easy access to the options that are declared in the constructor */

    get phpDirectoryUrl() {
        return this.options.php_directory_url;
    }

    set phpDirectoryUrl(value) {
        this.options.php_directory_url = value;
    }

    get currentChatWidgetTab() {
        return this.options.current_chat_widget_tab;
    }

    set currentChatWidgetTab(value) {
        this.options.current_chat_widget_tab = value;
    }

    get conversationId() {
        return this.options.conversation_id;
    }

    set conversationId(value) {
        this.options.conversation_id = value;
    }

    get files() {
        return this.options.files;
    }

    set files(value) {
        this.options.files = value;
    }

    get container() {
        return this.options.container;
    }

    set container(value) {
        this.options.container = value;
    }

    get status() {
        return this.options.status;
    }

    set status(value) {
        this.options.status = value;
    }

    get notifications() {
        return this.options.notifications;
    }

    set notifications(value) {
        this.options.notifications = value;
    }

    get autoLogin() {
        return this.options.auto_login;
    }

    set autoLogin(value) {
        this.options.auto_login = value;
    }

    // Event handler method - Add events to all the chat widget interactive elements
    _eventHandlers() {
        // Open chat widget event
        this.openWidgetBtn.onclick = event => {
            event.preventDefault();
            this.openChatWidget();
        };
        // Close button OnClick event handler
        if (this.container.querySelector('.close-chat-widget-btn')) {
            this.container.querySelector('.close-chat-widget-btn').onclick = event => {
                event.preventDefault();
                // Close the chat
                this.closeChatWidget();
            };
        }
        // Previous tab button OnClick event handler
        if (this.container.querySelector('.previous-chat-tab-btn')) {
            this.container.querySelector('.previous-chat-tab-btn').onclick = event => {
                event.preventDefault();
                // Transition to the respective page
                this.selectChatWidgetTab(this.currentChatWidgetTab-1);
            };
        }
        // New chat button OnClick event handler
        if (this.container.querySelector('.chat-widget-new-conversation')) {
            this.container.querySelector('.chat-widget-new-conversation').onclick = event => {
                event.preventDefault();
                // Update the status
                this.status = 'Waiting';
                // Notify the user
                this.container.querySelector('.chat-widget-conversation-tab').innerHTML = `
                <div class="chat-widget-messages">
                    <div class="chat-widget-message">Please wait...</div>
                </div>
                `;
                // Transition to the conversation tab (tab 3)
                this.selectChatWidgetTab(3);                
            };
        }
        // Iterate the conversations and add the OnClick event handler to each element
        if (this.container.querySelectorAll('.chat-widget-user')) {
            this.container.querySelectorAll('.chat-widget-user').forEach(element => {
                element.onclick = event => {
                    event.preventDefault();
                    // Get the conversation
                    this.getConversation(element.dataset.id);
                };
            });
        }
        // Ensure the login form exists
        if (this.container.querySelector('.chat-widget-login-tab form')) {
            // Login form submit event
            this.container.querySelector('.chat-widget-login-tab form').onsubmit = event => {
                event.preventDefault();
                // Authenticate the user
                this.authenticateUser(this.container.querySelector('.chat-widget-login-tab form'), data => {
                    // If the response includes the "operator" string
                    if (data.includes('MSG_LOGIN_REQUIRED')) {
                        // Show the password field
                        this.container.querySelector('.chat-widget-login-tab .msg').insertAdjacentHTML('beforebegin', '<input type="password" name="password" placeholder="Your Password" required>');
                    } else if (data.includes('MSG_CREATE_SUCCESS')) {
                        // New user
                        // Authentication success! Execute AJAX request to retrieve the user's conversations
                        this.fetchConversations(data => {
                            // Update the status
                            this.status = 'Waiting';
                            // Notify the user
                            this.container.querySelector('.chat-widget-conversation-tab').innerHTML = `
                            <div class="chat-widget-messages">
                                <div class="chat-widget-message">Please wait...</div>
                            </div>
                            `;
                            // Update the conversations tab content
                            this.container.querySelector('.chat-widget-conversations-tab').innerHTML = data;
                            // Execute the conversation handler function
                            this._eventHandlers();
                            // Transition to the conversation tab (tab 3)
                            this.selectChatWidgetTab(3);  
                        });
                    } else if (data.includes('MSG_SUCCESS')) {
                        // Authentication success! Execute AJAX request to retrieve the user's conversations
                        this.fetchConversations(data => {
                            // Update the status
                            this.status = 'Idle';
                            // Update the conversations tab content
                            this.container.querySelector('.chat-widget-conversations-tab').innerHTML = data;
                            // Execute the conversation handler function
                            this._eventHandlers();
                            // Transition to the conversations tab
                            this.selectChatWidgetTab(2);
                        });
                    } else {
                        // Authentication failed! Show the error message on the form
                        this.container.querySelector('.chat-widget-login-tab .msg').innerHTML = data;
                    }
                });
            };
        }
    }

}