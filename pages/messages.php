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

// Mark the current conversation as read if a user is selected
if ($otherUser) {
    markConversationAsRead($db, $username, $otherUser);
}

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
                        <a href="/pages/messages.php?user=<?= $otherUsername ?>" class="conversation-item selected">
                            <div class="conversation-info">
                                <div class="conversation-header-info">
                                    <h3><?= $otherUsername ?></h3>
                                </div>
                                <div class="last-message">
                                    <span class="message-preview">No messages yet</span>
                                </div>
                            </div>
                        </a>
                    <?php } ?>

                    <?php foreach ($conversations as $conversation):
                        $otherUsername = htmlspecialchars($conversation['other_user']);
                        $lastMessage = htmlspecialchars($conversation['last_message']);
                        $lastMessageTime = date('M j, H:i', $conversation['last_message_timestamp']);
                        $unreadCount = (int) $conversation['unread_count'];
                        $hasUnread = $unreadCount > 0;
                        $isFromSelf = $conversation['last_message_sender'] === $username;

                        $isSelected = ($otherUser === $otherUsername) ? 'selected' : '';
                        $unreadClass = $hasUnread ? 'unread' : '';
                        ?>
                        <a href="/pages/messages.php?user=<?= $otherUsername ?>"
                            class="conversation-item <?= $isSelected ?> <?= $unreadClass ?>">
                            <div class="conversation-info">
                                <div class="conversation-header-info">
                                    <h3><?= $otherUsername ?></h3>
                                    <?php if ($hasUnread): ?>
                                        <span class="unread-badge"><?= $unreadCount ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="last-message">
                                    <span class="message-preview">
                                        <?php if ($isFromSelf): ?>
                                            <span class="you-prefix">You: </span>
                                        <?php endif; ?>
                                        <?= mb_strlen($lastMessage) > 40 ? mb_substr($lastMessage, 0, 40) . '...' : $lastMessage ?>
                                    </span>
                                    <span class="message-time"><?= $lastMessageTime ?></span>
                                </div>
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
                    <div class="connection-status" id="connection-status">
                        <span class="status-indicator"></span>
                        <span class="status-text">Connected</span>
                    </div>
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

                <form class="message-form" method="post" data-ajax="true">
                    <input type="hidden" name="receiver" value="<?= htmlspecialchars($otherUser) ?>">

                    <div class="message-input-area">
                        <textarea name="message_text" placeholder="Type a message..." class="message-input"
                            rows="3"></textarea>
                    </div>

                    <div class="message-actions">
                        <button type="submit" class="send-btn">Send</button>
                    </div>
                </form>

                <script src="/js/messages.js"></script>
                <script>
                    // Initialize real-time messaging
                    document.addEventListener('DOMContentLoaded', function () {
                        // Initialize conversation list manager (always active)
                        window.conversationListManager = new ConversationListManager('<?= htmlspecialchars($username) ?>');
                        window.conversationListManager.init();

                        // Initialize message handler only if we have a conversation selected
                        <?php if ($otherUser): ?>
                            window.messagesHandler = new MessagesHandler({
                                otherUser: '<?= htmlspecialchars($otherUser) ?>',
                                currentUser: '<?= htmlspecialchars($username) ?>',
                                lastMessageId: <?= !empty($messages) ? end($messages)['id'] : 0 ?>
                            });
                            window.messagesHandler.init();
                        <?php endif; ?>
                    });
                </script>

            <?php else: ?>
                <div class="no-conversation-selected">
                    <div class="placeholder-message">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-message-circle">
                            <path
                                d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                            </path>
                        </svg>
                        <h2>Select a conversation</h2>
                        <p>Choose a conversation from the list or start a new one from a service page.</p>
                    </div>
                </div>

                <script src="/js/messages.js"></script>
                <script>
                    // Initialize conversation list manager even when no conversation is selected
                    document.addEventListener('DOMContentLoaded', function () {
                        window.conversationListManager = new ConversationListManager('<?= htmlspecialchars($username) ?>');
                        window.conversationListManager.init();
                    });
                </script>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php
drawFooter();
?>