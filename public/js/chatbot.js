/**
 * Mjengo Challenge - Chatbot Widget
 * Modern chat interface for system assistance
 */

class ChatbotWidget {
    constructor(config = {}) {
        this.config = {
            position: config.position || 'bottom-right',
            theme: config.theme || 'light',
            title: config.title || 'Mjengo Assistant',
            subtitle: config.subtitle || 'Ask me anything!',
            apiUrl: config.apiUrl || '/api/chatbot',
            csrfToken: config.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content || '',
            ...config
        };

        this.isOpen = false;
        this.messages = [];
        this.isLoading = false;
        this.init();
    }

    init() {
        this.createWidgetHTML();
        this.attachEventListeners();
        this.loadChatHistory();
    }

    createWidgetHTML() {
        const containerId = 'chatbot-widget-container';
        if (document.getElementById(containerId)) return;

        const html = `
            <div id="${containerId}" class="chatbot-widget ${this.config.theme}">
                <!-- Chat Button -->
                <div class="chatbot-button-container">
                    <button class="chatbot-toggle-btn" id="chatbot-toggle">
                        <i class="fas fa-comments"></i>
                        <span class="unread-badge" id="unread-badge" style="display: none;">0</span>
                    </button>
                </div>

                <!-- Chat Window -->
                <div class="chatbot-window hidden" id="chatbot-window">
                    <!-- Header -->
                    <div class="chatbot-header">
                        <div class="chatbot-header-content">
                            <h3 class="chatbot-title">${this.config.title}</h3>
                            <p class="chatbot-subtitle">${this.config.subtitle}</p>
                        </div>
                        <button class="chatbot-close-btn" id="chatbot-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Messages Container -->
                    <div class="chatbot-messages" id="chatbot-messages">
                        <!-- Initial greeting -->
                        <div class="chatbot-message bot-message">
                            <div class="message-content">
                                <p>👋 Welcome! I'm your Mjengo Assistant. How can I help you today?</p>
                            </div>
                        </div>

                        <!-- Suggestions -->
                        <div class="chatbot-suggestions" id="chatbot-suggestions">
                            <p class="suggestions-label">Try asking:</p>
                            <div class="suggestions-grid" id="suggestions-grid"></div>
                        </div>
                    </div>

                    <!-- Input Area -->
                    <div class="chatbot-input-area">
                        <form id="chatbot-form" class="chatbot-input-form">
                            <input 
                                type="text" 
                                id="chatbot-input" 
                                class="chatbot-input" 
                                placeholder="Type your message..."
                                autocomplete="off"
                            >
                            <button type="submit" class="chatbot-send-btn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                        <div class="chatbot-actions">
                            <button class="chatbot-action-btn" id="clear-history-btn" title="Clear history">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', html);
    }

    attachEventListeners() {
        const toggleBtn = document.getElementById('chatbot-toggle');
        const closeBtn = document.getElementById('chatbot-close');
        const form = document.getElementById('chatbot-form');
        const clearBtn = document.getElementById('clear-history-btn');

        toggleBtn.addEventListener('click', () => this.toggleChat());
        closeBtn.addEventListener('click', () => this.toggleChat());
        form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        clearBtn.addEventListener('click', () => this.clearHistory());

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.toggleChat();
            }
        });
    }

    toggleChat() {
        const window = document.getElementById('chatbot-window');
        this.isOpen = !this.isOpen;

        if (this.isOpen) {
            window.classList.remove('hidden');
            document.getElementById('chatbot-input').focus();
        } else {
            window.classList.add('hidden');
        }
    }

    handleFormSubmit(e) {
        e.preventDefault();
        const input = document.getElementById('chatbot-input');
        const message = input.value.trim();

        if (!message || this.isLoading) return;

        this.sendMessage(message);
        input.value = '';
    }

    sendMessage(message) {
        if (this.isLoading) return;

        // Add user message to chat
        this.addMessage(message, 'user');
        this.isLoading = true;
        this.showLoadingIndicator();

        // Send to API
        fetch(`${this.config.apiUrl}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.config.csrfToken,
            },
            credentials: 'same-origin',
            body: JSON.stringify({ message })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            this.isLoading = false;
            this.removeLoadingIndicator();

            if (data.success) {
                this.addMessage(data.response, 'bot', {
                    messageId: data.message_id,
                    type: data.message_type,
                    timestamp: data.timestamp
                });
            } else {
                this.addMessage('Sorry, an error occurred. Please try again.', 'bot');
            }

            // Hide suggestions after first message
            const suggestions = document.getElementById('chatbot-suggestions');
            if (suggestions) {
                suggestions.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Chatbot error:', error);
            this.isLoading = false;
            this.removeLoadingIndicator();
            this.addMessage('Connection error. Please try again. (Check console for details)', 'bot');
        });
    }

    addMessage(text, sender, metadata = {}) {
        const messagesContainer = document.getElementById('chatbot-messages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `chatbot-message ${sender}-message`;

        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';

        // Parse markdown-like formatting
        let formattedText = this.formatMessageText(text);
        contentDiv.innerHTML = formattedText;

        messageDiv.appendChild(contentDiv);

        // Add rating for bot messages
        if (sender === 'bot' && metadata.messageId) {
            const ratingDiv = document.createElement('div');
            ratingDiv.className = 'message-rating';
            ratingDiv.innerHTML = `
                <button class="rating-btn" data-message-id="${metadata.messageId}" data-rating="1" title="Not helpful">👎</button>
                <button class="rating-btn" data-message-id="${metadata.messageId}" data-rating="5" title="Helpful">👍</button>
            `;
            ratingDiv.addEventListener('click', (e) => {
                if (e.target.classList.contains('rating-btn')) {
                    this.rateResponse(e.target.dataset.messageId, e.target.dataset.rating);
                    ratingDiv.style.opacity = '0.5';
                }
            });
            messageDiv.appendChild(ratingDiv);
        }

        messagesContainer.appendChild(messageDiv);
        this.scrollToBottom();
    }

    formatMessageText(text) {
        let formatted = text;

        // Bold text **text**
        formatted = formatted.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');

        // Line breaks
        formatted = formatted.replace(/\n/g, '<br>');

        // Bullet points
        formatted = formatted.replace(/^• (.+)$/gm, '<li>$1</li>');

        // Links
        formatted = formatted.replace(/\[(.+?)\]\((.+?)\)/g, '<a href="$2" target="_blank">$1</a>');

        // Wrap lists
        formatted = formatted.replace(/(<li>.+<\/li>)/s, '<ul>$1</ul>');

        return formatted;
    }

    showLoadingIndicator() {
        const messagesContainer = document.getElementById('chatbot-messages');
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'chatbot-message bot-message loading-message';
        loadingDiv.id = 'loading-indicator';
        loadingDiv.innerHTML = `
            <div class="message-content">
                <div class="typing-indicator">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        `;
        messagesContainer.appendChild(loadingDiv);
        this.scrollToBottom();
    }

    removeLoadingIndicator() {
        const loader = document.getElementById('loading-indicator');
        if (loader) {
            loader.remove();
        }
    }

    rateResponse(messageId, rating) {
        fetch(`${this.config.apiUrl}/rate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.config.csrfToken,
            },
            credentials: 'same-origin',
            body: JSON.stringify({ message_id: messageId, rating })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Response rated successfully');
            }
        })
        .catch(error => console.error('Rating error:', error));
    }

    loadChatHistory() {
        fetch(`${this.config.apiUrl}/history`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.config.csrfToken,
            },
            credentials: 'same-origin',
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.messages.length > 0) {
                // Load last few messages
                data.messages.slice(-5).forEach(msg => {
                    this.addMessage(msg.user_message, 'user');
                    this.addMessage(msg.bot_response, 'bot');
                });
            }
            this.loadSuggestions();
        })
        .catch(error => {
            console.error('Error loading history:', error);
            this.loadSuggestions();
        });
    }

    loadSuggestions() {
        fetch(`${this.config.apiUrl}/suggestions`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.config.csrfToken,
            },
            credentials: 'same-origin',
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const suggestionsGrid = document.getElementById('suggestions-grid');
                suggestionsGrid.innerHTML = '';
                
                data.suggestions.forEach(suggestion => {
                    const btn = document.createElement('button');
                    btn.className = 'suggestion-btn';
                    btn.textContent = suggestion;
                    btn.addEventListener('click', () => {
                        this.sendMessage(suggestion);
                    });
                    suggestionsGrid.appendChild(btn);
                });
            }
        })
        .catch(error => console.error('Error loading suggestions:', error));
    }

    clearHistory() {
        if (confirm('Are you sure you want to clear chat history?')) {
            fetch(`${this.config.apiUrl}/clear`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.config.csrfToken,
                },
                credentials: 'same-origin',
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const messagesContainer = document.getElementById('chatbot-messages');
                    messagesContainer.innerHTML = `
                        <div class="chatbot-message bot-message">
                            <div class="message-content">
                                <p>✨ Chat history cleared! How can I help you?</p>
                            </div>
                        </div>
                    `;
                    this.loadSuggestions();
                }
            })
            .catch(error => console.error('Clear history error:', error));
        }
    }

    scrollToBottom() {
        setTimeout(() => {
            const messagesContainer = document.getElementById('chatbot-messages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 100);
    }
}

// Initialize chatbot when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    if (document.body.classList.contains('authenticated')) {
        window.chatbot = new ChatbotWidget();
    }
});
