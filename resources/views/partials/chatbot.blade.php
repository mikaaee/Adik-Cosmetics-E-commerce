 <meta name="csrf-token" content="{{ csrf_token() }}">
 <meta name="chatbox-route" content="{{ route('chatbox.ask') }}">

 {{-- Modern Chatbox UI --}}
 <div class="chatbot-container" id="chatbot">
     <div class="chatbot-header" onclick="toggleChat()">
         <div class="chatbot-avatar">
             <span style="font-size: 24px; position: relative; z-index: 1;">🤖</span>
         </div>
         <div class="chatbot-title">
             <h4>AdikBot</h4>
             <span class="chatbot-status">Online</span>
         </div>
         <button class="chatbot-close" onclick="event.stopPropagation(); toggleChat();">
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

     .chatbot-container.collapsed {
         height: auto;
         width: auto;
         border-radius: 50px;
         background: linear-gradient(to right, #d98ea1, #f3b6c4);
         padding: 5px 10px;
         display: flex;
         align-items: center;
         justify-content: flex-start;
         cursor: pointer;
     }

     .chatbot-container.collapsed .chatbot-messages,
     .chatbot-container.collapsed .chatbot-input,
     .chatbot-container.collapsed .chatbot-title,
     .chatbot-container.collapsed .chatbot-close {
         display: none;
     }

     .chatbot-container.collapsed .chatbot-avatar {
         margin-right: 0;
     }

     /* Chatbot Header (keep always visible) */
     .chatbot-header {
         background: linear-gradient(to right, #d98ea1, #f3b6c4);
         color: white;
         padding: 15px;
         display: flex;
         align-items: center;
         cursor: pointer;
     }

     .chatbot-avatar {
         width: 48px;
         height: 48px;
         background: linear-gradient(135deg, #FF9A8B 0%, #FF6B8B 50%, #FF8E53 100%);
         border-radius: 50%;
         display: flex;
         align-items: center;
         justify-content: center;
         margin-right: 12px;
         color: white;
         font-size: 20px;
         box-shadow: 0 4px 12px rgba(255, 107, 139, 0.3);
         position: relative;
         overflow: hidden;
         transition: all 0.3s ease;
     }

     .chatbot-avatar::before {
         content: "";
         position: absolute;
         top: -50%;
         left: -50%;
         width: 200%;
         height: 200%;
         background: linear-gradient(to bottom right, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0) 60%);
         transform: rotate(30deg);
     }

     .chatbot-container:not(.collapsed) .chatbot-avatar::after {
         content: "";
         position: absolute;
         top: -4px;
         left: -4px;
         width: calc(100% + 8px);
         height: calc(100% + 8px);
         border-radius: 50%;
         background: radial-gradient(circle at center, rgba(255, 107, 139, 0.3), transparent 70%);
         animation: pulseGlow 2.5s infinite ease-in-out;
         z-index: 0;
     }

     @keyframes pulseGlow {

         0%,
         100% {
             transform: scale(1);
             opacity: 0.6;
         }

         50% {
             transform: scale(1.2);
             opacity: 0.9;
         }
     }

     .chatbot-container:not(.collapsed) .chatbot-avatar {
         animation: float 3s ease-in-out infinite;
     }

     @keyframes float {

         0%,
         100% {
             transform: translateY(0);
         }

         50% {
             transform: translateY(-5px);
         }
     }

     .chatbot-avatar i {
         text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
         position: relative;
         z-index: 1;
     }

     .chatbot-avatar:hover {
         transform: scale(1.05) rotate(-2deg);
         box-shadow: 0 6px 16px rgba(255, 107, 139, 0.4);
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
         animation: fadeIn 0.3s ease-out;
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
         box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
     }

     .chatbot-message.bot .message-bubble {
         background: white;
         color: #333;
         border-radius: 18px 18px 18px 4px;
     }

     .chatbot-message.user .message-bubble {
         background: linear-gradient(to right, #d98ea1, #f3b6c4);
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
 </style>
