<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../database/classes/messages.php');
require_once(__DIR__ . '/../database/classes/service.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../includes/database.php');

if (Auth::getInstance()->getUser() === null) {
    header('Location: /pages/login.php');
    exit();
}

$loggedInUser = Auth::getInstance()->getUser();
$username = $loggedInUser['username'];
$db = Database::getInstance();

$otherUser = isset($_GET['user']) ? $_GET['user'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_text'])) {
    $messageText = trim($_POST['message_text']);
    $receiverUsername = $_POST['receiver'];

    if (!empty($messageText)) {
        sendMessage($db, $username, $receiverUsername, $messageText);
    }
    
    header("Location: /pages/messages.php?user=$receiverUsername");
    exit();
}

$conversations = getConversations($db, $username);

head();

echo '<link rel="stylesheet" href="/css/messages.css">';

drawHeader();
?>

<main class="messages-container">
    <div class="messages-layout">
        <aside class="conversations-sidebar">
            <h2>Conversations</h2>
            <div class="conversation-list">
                <?php 
                $otherUserInList = false;
                if ($otherUser) {
                    foreach ($conversations as $conversation) {
                        if ($conversation['other_user'] === $otherUser) {
                            $otherUserInList = true;
                            break;
                        }
                    }
                }
                
                if (empty($conversations) && !$otherUser): ?>
                    <p class="no-conversations">No conversations yet.</p>
                <?php else: ?>
                    <?php 
                    if ($otherUser && !$otherUserInList) {
                        $otherUsername = htmlspecialchars($otherUser);
                    ?>
                        <a href="/pages/messages.php?user=<?= $otherUsername ?>" 
                           class="conversation-item selected">
                            <div class="conversation-info">
                                <h3><?= $otherUsername ?></h3>
                            </div>
                        </a>
                    <?php } ?>
                    
                    <?php foreach ($conversations as $conversation): 
                        $otherUsername = htmlspecialchars($conversation['other_user']);
                        
                        $isSelected = ($otherUser === $otherUsername) ? 'selected' : '';
                    ?>
                        <a href="/pages/messages.php?user=<?= $otherUsername ?>" 
                           class="conversation-item <?= $isSelected ?>">
                            <div class="conversation-info">
                                <h3><?= $otherUsername ?></h3>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </aside>

        <section class="message-area">
            <?php if ($otherUser): ?>
                <?php
                    $messages = getMessages($db, $username, $otherUser);
                ?>
                <div class="conversation-header">
                    <h2>
                        Conversation with <?= htmlspecialchars($otherUser) ?>
                    </h2>
                </div>
                
                <div class="messages-list" id="messages-list">
                    <?php if (empty($messages)): ?>
                        <p class="no-messages">No messages yet. Start the conversation!</p>
                    <?php else: ?>
                        <?php foreach ($messages as $message): ?>
                            <?php
                                $isOwn = ($message['sender'] === $username);
                                $messageClass = $isOwn ? 'message-own' : 'message-other';
                            ?>
                            <div class="message-item <?= $messageClass ?>">
                                <div class="message-content">
                                    <p><?= htmlspecialchars($message['message_text']) ?></p>
                                    <span class="message-time"><?= date('M j, H:i', $message['timestamp']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <form class="message-form" method="post">
                    <input type="hidden" name="receiver" value="<?= htmlspecialchars($otherUser) ?>">
                    
                    <div class="message-input-area">
                        <textarea name="message_text" placeholder="Type a message..." class="message-input"></textarea>
                    </div>
                    
                    <div class="message-actions">
                        <button type="submit" class="send-btn">Send</button>
                    </div>
                </form>
                
                <script>
                    // Auto-scroll to bottom of messages list on page load
                    document.addEventListener('DOMContentLoaded', function() {
                        const messagesList = document.getElementById('messages-list');
                        if (messagesList) {
                            messagesList.scrollTop = messagesList.scrollHeight;
                        }
                    });
                    
                    // Poll for new messages every 5 seconds
                    let lastMessageId = <?= !empty($messages) ? end($messages)['id'] : 0 ?>;
                    
                    function checkForNewMessages() {
                        $.ajax({
                            url: '/actions/messages_action.php',
                            type: 'GET',
                            data: {
                                action: 'get_new_messages',
                                other_user: '<?= $otherUser ?>',
                                last_message_id: lastMessageId
                            },
                            success: function(data) {
                                if (data.messages && data.messages.length > 0) {

                                    lastMessageId = data.messages[data.messages.length - 1].id; 
                                    const messagesList = document.getElementById('messages-list');

                                    data.messages.forEach(message => {
                                        const isOwn = message.sender === '<?= $username ?>';
                                        const messageClass = isOwn ? 'message-own' : 'message-other';
                                        
                                        let messageHtml = `<div class="message-item ${messageClass}">
                                            <div class="message-content">
                                                <p>${message.message_text}</p>
                                                <span class="message-time">${new Date(message.timestamp * 1000).toLocaleString('en-US', {month: 'short', day: 'numeric', hour: '2-digit', minute:'2-digit'})}</span>
                                            </div>
                                        </div>`;
                                        
                                        messagesList.innerHTML += messageHtml;
                                    });
                                    
                                    // Scroll to bottom
                                    messagesList.scrollTop = messagesList.scrollHeight;
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error fetching new messages:', error);
                            }
                        });
                    }
                    
                    // Poll every 5 seconds
                    setInterval(checkForNewMessages, 5000);
                </script>
                
            <?php else: ?>
                <div class="no-conversation-selected">
                    <div class="placeholder-message">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                        <h2>Select a conversation</h2>
                        <p>Choose a conversation from the list or start a new one from a service page.</p>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php
drawFooter();
?>