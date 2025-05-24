class MessagesHandler {
	constructor(config) {
		this.otherUser = config.otherUser;
		this.currentUser = config.currentUser;
		this.lastMessageId = config.lastMessageId;
		this.pollingInterval = null;
		this.pollingFrequency = 2000; // Poll every 2 seconds for better real-time feel
		this.messagesList = null;
		this.messageForm = null;
		this.messageInput = null;
		this.sendButton = null;
		this.connectionStatus = null;
		this.isPolling = false;
		this.isConnected = true;
	}

	init() {
		this.messagesList = document.getElementById('messages-list');
		this.messageForm = document.querySelector('.message-form');
		this.messageInput = document.querySelector('.message-input');
		this.sendButton = document.querySelector('.send-btn');
		this.connectionStatus = document.getElementById('connection-status');

		if (!this.messagesList || !this.messageForm) {
			console.log(
				'Messages elements not found, real-time messaging not initialized'
			);
			return;
		}

		this.scrollToBottom();
		this.setupFormSubmission();
		this.startPolling();
		this.setupKeyboardShortcuts();
		this.updateConnectionStatus(true);
	}

	scrollToBottom() {
		if (this.messagesList) {
			this.messagesList.scrollTop = this.messagesList.scrollHeight;
		}
	}

	setupFormSubmission() {
		this.messageForm.addEventListener('submit', (e) => {
			e.preventDefault();
			this.sendMessage();
		});
	}

	setupKeyboardShortcuts() {
		this.messageInput.addEventListener('keydown', (e) => {
			if (e.key === 'Enter' && !e.shiftKey) {
				e.preventDefault();
				this.sendMessage();
			}
		});
	}

	async sendMessage() {
		const messageText = this.messageInput.value.trim();

		if (!messageText) {
			return;
		}

		// Disable send button and show loading state
		this.sendButton.disabled = true;
		this.sendButton.textContent = 'Sending...';

		try {
			const formData = new FormData();
			formData.append('action', 'send_message');
			formData.append('receiver', this.otherUser);
			formData.append('message_text', messageText);

			const response = await fetch('/actions/messages_action.php', {
				method: 'POST',
				body: formData,
			});

			if (!response.ok) {
				throw new Error(`HTTP error! status: ${response.status}`);
			}

			const result = await response.json();

			// Update connection status on successful request
			this.updateConnectionStatus(true);

			if (result.success) {
				// Clear input
				this.messageInput.value = '';

				// Add message to UI immediately for better UX
				this.addMessageToUI({
					id: result.messageId,
					sender: this.currentUser,
					message_text: messageText,
					timestamp: Math.floor(Date.now() / 1000),
				});

				// Update last message ID
				this.lastMessageId = result.messageId;
			} else {
				this.showError(
					'Failed to send message: ' + (result.error || 'Unknown error')
				);
			}
		} catch (error) {
			console.error('Error sending message:', error);
			this.updateConnectionStatus(false);
			this.showError('Failed to send message. Please check your connection.');
		} finally {
			// Re-enable send button
			this.sendButton.disabled = false;
			this.sendButton.textContent = 'Send';
		}
	}

	startPolling() {
		if (this.isPolling) {
			return;
		}

		this.isPolling = true;
		this.pollingInterval = setInterval(() => {
			this.checkForNewMessages();
		}, this.pollingFrequency);
	}

	stopPolling() {
		if (this.pollingInterval) {
			clearInterval(this.pollingInterval);
			this.pollingInterval = null;
			this.isPolling = false;
		}
	}

	async checkForNewMessages() {
		try {
			const url = new URL(
				'/actions/messages_action.php',
				window.location.origin
			);
			url.searchParams.append('action', 'get_new_messages');
			url.searchParams.append('other_user', this.otherUser);
			url.searchParams.append('last_message_id', this.lastMessageId);

			const response = await fetch(url);

			if (!response.ok) {
				throw new Error(`HTTP error! status: ${response.status}`);
			}

			const data = await response.json();

			// Update connection status on successful request
			this.updateConnectionStatus(true);

			if (data.messages && data.messages.length > 0) {
				data.messages.forEach((message) => {
					this.addMessageToUI(message);
				});

				// Update last message ID
				this.lastMessageId = data.messages[data.messages.length - 1].id;

				// Scroll to bottom
				this.scrollToBottom();
			}
		} catch (error) {
			console.error('Error fetching new messages:', error);
			this.updateConnectionStatus(false);
		}
	}

	addMessageToUI(message) {
		const isOwn = message.sender === this.currentUser;
		const messageClass = isOwn ? 'message-own' : 'message-other';

		const messageElement = document.createElement('div');
		messageElement.className = `message-item ${messageClass}`;
		messageElement.innerHTML = `
            <div class="message-content">
                <p>${this.escapeHtml(message.message_text)}</p>
                <span class="message-time">${this.formatTimestamp(
									message.timestamp
								)}</span>
            </div>
        `;

		// Check if "no messages" placeholder exists and remove it
		const noMessagesElement = this.messagesList.querySelector('.no-messages');
		if (noMessagesElement) {
			noMessagesElement.remove();
		}

		this.messagesList.appendChild(messageElement);
		this.scrollToBottom();
	}

	formatTimestamp(timestamp) {
		const date = new Date(timestamp * 1000);
		return date.toLocaleString('en-US', {
			month: 'short',
			day: 'numeric',
			hour: '2-digit',
			minute: '2-digit',
		});
	}

	escapeHtml(text) {
		const div = document.createElement('div');
		div.textContent = text;
		return div.innerHTML;
	}

	showError(message) {
		// Create a simple error notification
		const errorDiv = document.createElement('div');
		errorDiv.className = 'error-notification';
		errorDiv.textContent = message;
		errorDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ff4757;
            color: white;
            padding: 12px 20px;
            border-radius: 4px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        `;

		document.body.appendChild(errorDiv);

		// Remove after 5 seconds
		setTimeout(() => {
			if (errorDiv.parentNode) {
				errorDiv.parentNode.removeChild(errorDiv);
			}
		}, 5000);
	}

	updateConnectionStatus(isConnected) {
		if (!this.connectionStatus) return;

		this.isConnected = isConnected;
		const indicator = this.connectionStatus.querySelector('.status-indicator');
		const text = this.connectionStatus.querySelector('.status-text');

		if (isConnected) {
			indicator.classList.remove('disconnected');
			text.textContent = 'Connected';
		} else {
			indicator.classList.add('disconnected');
			text.textContent = 'Disconnected';
		}
	}

	destroy() {
		this.stopPolling();
	}
}

// Handle page visibility changes to optimize polling
document.addEventListener('visibilitychange', function () {
	if (window.messagesHandler) {
		if (document.hidden) {
			// Reduce polling frequency when tab is not visible
			window.messagesHandler.pollingFrequency = 10000; // 10 seconds
		} else {
			// Restore normal polling frequency when tab becomes visible
			window.messagesHandler.pollingFrequency = 2000; // 2 seconds
			// Check for messages immediately when tab becomes visible
			window.messagesHandler.checkForNewMessages();
		}
	}
});
