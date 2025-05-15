@extends('layouts.main')

@section('title', 'Home')

@section('header')
    @include('partials.header-home', ['categories' => $categories])
@endsection

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    {{-- Hero Ads --}}
    @include('partials.hero', ['ads' => $ads])

    <section class="categories">
        <h2>Browse by Categories</h2>

        <div class="category-grid">
            @forelse($categories as $cat)
                <a href="{{ route('category.products', $cat['id']) }}" class="category-card">
                    @switch(strtolower($cat['name']))
                        @case('makeup')
                            <i class="fas fa-paint-brush"></i>
                        @break

                        @case('skincare')
                            <i class="fas fa-spa"></i>
                        @break

                        @case('fragrance')
                            <i class="fas fa-spray-can"></i>
                        @break

                        @case('haircare')
                            <i class="fas fa-cut"></i>
                        @break

                        @case('perfume')
                            <i class="fas fa-spray-can-sparkles"></i>
                        @break

                        @case('bodycare')
                            <i class="fas fa-hand-holding-heart"></i>
                        @break

                        @case('henna')
                            <i class="fas fa-hand-dots"></i>
                        @break

                        @default
                            <i class="fas fa-tag"></i>
                    @endswitch
                    <h3>{{ $cat['name'] }}</h3>
                </a>
                @empty
                    <p>No categories available.</p>
                @endforelse
            </div>
        </section>

        {{-- Modern Chatbox UI --}}
        <div class="chatbot-container">
            <div class="chatbot-header">
                <div class="chatbot-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="chatbot-title">
                    <h4>AdikBot</h4>
                    <span class="chatbot-status">Online</span>
                </div>
                <button class="chatbot-close" onclick="toggleChat()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="chatbot-messages" id="chatMessages">
                <div class="chatbot-message bot">
                    <div class="message-bubble">
                        Hi there! How can I help you with your beauty needs today? 💄
                    </div>
                    <div class="message-time">Just now</div>
                </div>
            </div>
            
            <div class="chatbot-input">
                <textarea id="chatInput" placeholder="Ask about products or promotions..." rows="1" oninput="autoGrow(this)"></textarea>
                <button class="chatbot-send" onclick="sendChat()">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>

        <style>
            /* Categories styling remains the same */
            .categories {
                padding: 40px 20px;
                text-align: center;
            }

            .categories h2 {
                font-size: 28px;
                margin-bottom: 30px;
            }

            .category-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
                justify-items: center;
                padding: 0 20px;
            }

            .category-card {
                background: #fff;
                border-radius: 10px;
                padding: 25px 20px;
                text-align: center;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                width: 100%;
                max-width: 220px;
                text-decoration: none;
                color: #333;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .category-card i {
                font-size: 30px;
                margin-bottom: 10px;
                color: #c69c9c;
            }

            .category-card:hover {
                transform: translateY(-5px);
                background-color: #f9f9f9;
            }

            .category-card h3 {
                font-size: 18px;
                font-weight: 600;
                margin: 0;
            }

            @media (max-width: 992px) {
                .category-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 600px) {
                .category-grid {
                    grid-template-columns: 1fr;
                }
            }

            /* New Chatbot Styling */
            .chatbot-container {
                position: fixed;
                bottom: 30px;
                right: 30px;
                width: 350px;
                max-width: 90%;
                background: white;
                border-radius: 16px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                font-family: 'Segoe UI', system-ui, sans-serif;
                z-index: 9999;
                overflow: hidden;
                transform: translateY(0);
                transition: all 0.3s ease;
            }

            .chatbot-header {
                background: linear-gradient(to right, #d98ea1, #f3b6c4);
                color: white;
                padding: 15px;
                display: flex;
                align-items: center;
                cursor: pointer;
            }

            .chatbot-avatar {
                width: 40px;
                height: 40px;
                background: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 12px;
                color: #FF6B8B;
                font-size: 18px;
            }

            .chatbot-title {
                flex: 1;
            }

            .chatbot-title h4 {
                margin: 0;
                font-size: 16px;
                font-weight: 600;
            }

            .chatbot-status {
                font-size: 12px;
                opacity: 0.9;
            }

            .chatbot-close {
                background: transparent;
                border: none;
                color: white;
                font-size: 16px;
                cursor: pointer;
                padding: 5px;
            }

            .chatbot-messages {
                height: 300px;
                padding: 15px;
                overflow-y: auto;
                background: #fafafa;
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .chatbot-message {
                max-width: 80%;
                display: flex;
                flex-direction: column;
            }

            .chatbot-message.bot {
                align-self: flex-start;
            }

            .chatbot-message.user {
                align-self: flex-end;
            }

            .message-bubble {
                padding: 12px 16px;
                border-radius: 18px;
                font-size: 14px;
                line-height: 1.4;
                box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            }

            .chatbot-message.bot .message-bubble {
                background: white;
                color: #333;
                border-radius: 18px 18px 18px 4px;
            }

            .chatbot-message.user .message-bubble {
               background: linear-gradient(to right, #d98ea1, #f3b6c4)
                color: white;
                border-radius: 18px 18px 4px 18px;
            }

            .message-time {
                font-size: 11px;
                color: #999;
                padding: 4px 8px;
            }

            .chatbot-input {
                padding: 15px;
                background: white;
                border-top: 1px solid #eee;
                position: relative;
            }

            .chatbot-input textarea {
                width: 100%;
                border: 1px solid #e0e0e0;
                border-radius: 24px;
                padding: 12px 50px 12px 16px;
                resize: none;
                outline: none;
                min-height: 48px;
                max-height: 120px;
                font-family: inherit;
                transition: all 0.3s;
            }

            .chatbot-input textarea:focus {
                border-color: #FF6B8B;
            }

            .chatbot-send {
                position: absolute;
                right: 25px;
                bottom: 25px;
               background-color: #c96c9c;
                color: white;
                border: none;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s;
            }

            .chatbot-send:hover {
                transform: scale(1.1);
            }

            /* Animation for new messages */
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .chatbot-message {
                animation: fadeIn 0.3s ease-out;
            }
        </style>
    @endsection

    @section('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
        <script>
            function autoGrow(element) {
                element.style.height = "auto";
                element.style.height = (element.scrollHeight) + "px";
            }

            function sendChat() {
                const input = document.getElementById('chatInput');
                const messagesContainer = document.getElementById('chatMessages');
                
                if (input.value.trim() === '') return;
                
                // Add user message
                const userMessage = document.createElement('div');
                userMessage.className = 'chatbot-message user';
                userMessage.innerHTML = `
                    <div class="message-bubble">${input.value}</div>
                    <div class="message-time">Just now</div>
                `;
                messagesContainer.appendChild(userMessage);
                
                // Show typing indicator
                const typingIndicator = document.createElement('div');
                typingIndicator.className = 'chatbot-message bot';
                typingIndicator.innerHTML = `
                    <div class="message-bubble" style="color: #999;"><i>Typing...</i></div>
                `;
                messagesContainer.appendChild(typingIndicator);
                
                // Scroll to bottom
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                
                // Send to server
                fetch('{{ route('chatbox.ask') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: input.value
                    })
                })
                .then(res => res.json())
                .then(data => {
                    // Remove typing indicator
                    messagesContainer.removeChild(typingIndicator);
                    
                    // Add bot response
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
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    console.error(error);
                });
                
                input.value = '';
                input.style.height = 'auto';
            }

            function toggleChat() {
                const container = document.querySelector('.chatbot-container');
                container.style.transform = container.style.transform === 'translateY(400px)' 
                    ? 'translateY(0)' 
                    : 'translateY(400px)';
            }

            // Optional: Auto-focus input when clicking header
            document.querySelector('.chatbot-header').addEventListener('click', function() {
                document.getElementById('chatInput').focus();
            });
        </script>
    @endsection