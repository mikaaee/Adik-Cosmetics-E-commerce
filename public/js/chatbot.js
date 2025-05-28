function autoGrow(element) {
    element.style.height = "auto";
    element.style.height = (element.scrollHeight) + "px";
}

function toggleChat() {
    const container = document.getElementById('chatbot');
    container.classList.toggle('collapsed');
}

function sendChat() {
    const input = document.getElementById('chatInput');
    const messagesContainer = document.getElementById('chatMessages');

    if (input.value.trim() === '') return;

    const userMessage = document.createElement('div');
    userMessage.className = 'chatbot-message user';
    userMessage.innerHTML = `
        <div class="message-bubble">${input.value}</div>
        <div class="message-time">Just now</div>
    `;
    messagesContainer.appendChild(userMessage);

    const typingIndicator = document.createElement('div');
    typingIndicator.className = 'chatbot-message bot';
    typingIndicator.innerHTML = `
        <div class="message-bubble" style="color: #999;"><i>Typing...</i></div>
    `;
    messagesContainer.appendChild(typingIndicator);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    fetch(chatboxAskUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                message: input.value
            })
        })
        .then(res => res.json())
        .then(data => {
            messagesContainer.removeChild(typingIndicator);
            const botMessage = document.createElement('div');
            botMessage.className = 'chatbot-message bot';
            botMessage.innerHTML = `
                <div class="message-bubble">${data.reply}</div>
                <div class="message-time">Just now</div>
            `;
            messagesContainer.appendChild(botMessage);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        })
        .catch(error => {
            messagesContainer.removeChild(typingIndicator);
            const errorMessage = document.createElement('div');
            errorMessage.className = 'chatbot-message bot';
            errorMessage.innerHTML = `
                <div class="message-bubble">Sorry, I encountered an error. Please try again later.</div>
                <div class="message-time">Just now</div>
            `;
            messagesContainer.appendChild(errorMessage);
            console.error(error);
        });

    input.value = '';
    input.style.height = 'auto';
}

// Setup global variables from Blade
let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let chatboxAskUrl = document.querySelector('meta[name="chatbox-route"]').getAttribute('content');
