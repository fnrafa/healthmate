function connectSocket(id) {
    const userId = id;
    const conn = new WebSocket('ws://localhost:8080');

    conn.onopen = function () {
        conn.send(JSON.stringify({type: 'identify', userId: userId}));
        conn.send(JSON.stringify({type: 'mark_read', userId: userId}));
    };

    conn.onmessage = function (event) {
        const data = JSON.parse(event.data);
        if (data.type === 'chat') {
            displayMessage(data);
        } else if (data.type === 'status_update') {
            updateMessageStatus(data.message_id, data.is_read);
        }
    };

    document.getElementById('sendButton').addEventListener('click', function () {
        sendMessage();
    });

    document.getElementById('message').addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            sendMessage();
        }
    });

    function sendMessage() {
        const messageInput = document.getElementById('message');
        const fileInput = document.getElementById('fileInput');
        const consultationId = document.getElementById('consultation_id').value;
        const message = messageInput.value.trim();
        const file = fileInput.files[0];

        if (!message && !file) {
            return;
        }

        const formData = new FormData();
        formData.append('consultation_id', consultationId);
        formData.append('message', message);
        formData.append('userId', userId);
        formData.append('created_at', new Date().toISOString());
        if (file) {
            formData.append('media', file);
        }

        fetch('/send-message', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayMessage({
                        message: data.message,
                        message_id: data.message_id,
                        is_read: false,
                        userId: userId,
                        created_at: data.created_at,
                        media: data.media
                    }, 'You');
                } else {
                    console.error('Error:', data.errors);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });

        messageInput.value = '';
        fileInput.value = '';
    }

    function displayMessage(data, sender = 'Server') {
        const chatBox = document.getElementById('messageContainer');
        const msgElement = document.createElement('div');
        msgElement.id = 'message-' + data.message_id;
        msgElement.className = 'fs-5 border border-secondary mt-2 p-2 pt-1 pb-1 ' + (data.userId === userId ? 'text-end bg-primary-light message-bubble-right' : 'text-start bg-white message-bubble-left');

        let content = `<div>${data.message}</div>`;
        if (data.media) {
            content += `<div><img src="${data.media}" class="img-fluid" alt="Uploaded Image"></div>`;
        }
        content += `<div class="text-secondary fs-6">
        ${data.userId === userId ? `<span>${data.created_at}</span><span> ${getMessageStatusIcon(data.is_read)}</span>` : `<span>${getMessageStatusIcon(data.is_read)}</span><span> ${data.created_at}</span>`}
    </div>`;

        msgElement.innerHTML = content;
        chatBox.appendChild(msgElement);
        chatBox.scrollTop = chatBox.scrollHeight;
    }


    function updateMessageStatus(messageId, is_read) {
        const msgElement = document.getElementById('message-' + messageId);
        if (msgElement) {
            const statusElement = msgElement.querySelector('.message-status');
            if (statusElement) {
                statusElement.innerHTML = getMessageStatusIcon(is_read);
            }
        }
    }

    function getMessageStatusIcon(is_read) {
        return is_read ? '✓✓' : '✓';
    }
}

function scrollToBottom() {
    const messageContainer = document.getElementById('messageContainer');
    messageContainer.scrollTop = messageContainer.scrollHeight;
}

document.addEventListener('DOMContentLoaded', () => {
    const scriptTag = document.querySelector('script[data-id]');
    if (scriptTag) {
        const dataId = scriptTag.getAttribute('data-id');
        connectSocket(dataId);
        scrollToBottom();
    }
});
