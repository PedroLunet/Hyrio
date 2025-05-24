<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../database/classes/service.php');
require_once(__DIR__ . '/../database/classes/messages.php');
require_once(__DIR__ . '/../includes/database.php');

if (Auth::getInstance()->getUser() === null) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_new_messages') {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'User not authenticated']);
        exit();
    } else {
        $_SESSION['login_error'] = 'You must be logged in to message other users.';
        header('Location: /pages/login.php');
        exit();
    }
}

$loggedInUser = Auth::getInstance()->getUser();
$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_new_messages') {

    header('Content-Type: application/json');

    $otherUser = isset($_GET['other_user']) ? $_GET['other_user'] : null;
    $lastMessageId = isset($_GET['last_message_id']) ? (int) $_GET['last_message_id'] : 0;

    if (!$otherUser) {
        echo json_encode(['error' => 'Missing required parameter']);
        exit();
    }

    $username = $loggedInUser['username'];

    $newMessages = getNewMessages($db, $username, $otherUser, $lastMessageId);

    foreach ($newMessages as &$message) {
        if (isset($message['message_text'])) {
            $message['message_text'] = htmlspecialchars($message['message_text']);
        }

        $message['timestamp'] = (int) $message['timestamp'];
    }

    // Mark conversation as read when fetching new messages
    if (!empty($newMessages)) {
        markConversationAsRead($db, $username, $otherUser);
    }

    echo json_encode(['messages' => $newMessages]);
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'mark_read') {

    header('Content-Type: application/json');

    $otherUser = isset($_GET['other_user']) ? $_GET['other_user'] : null;

    if (!$otherUser) {
        echo json_encode(['error' => 'Missing required parameter']);
        exit();
    }

    $username = $loggedInUser['username'];
    markConversationAsRead($db, $username, $otherUser);

    echo json_encode(['success' => true]);
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_conversations') {

    header('Content-Type: application/json');

    $username = $loggedInUser['username'];
    $conversations = getConversations($db, $username);

    // Format the conversations for JSON response
    $formattedConversations = [];
    foreach ($conversations as $conversation) {
        $formattedConversations[] = [
            'other_user' => htmlspecialchars($conversation['other_user']),
            'last_message' => htmlspecialchars($conversation['last_message']),
            'last_message_timestamp' => (int) $conversation['last_message_timestamp'],
            'last_message_sender' => htmlspecialchars($conversation['last_message_sender']),
            'unread_count' => (int) $conversation['unread_count']
        ];
    }

    echo json_encode(['conversations' => $formattedConversations]);
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_message') {

    header('Content-Type: application/json');

    $messageText = isset($_POST['message_text']) ? trim($_POST['message_text']) : '';
    $receiverUsername = isset($_POST['receiver']) ? $_POST['receiver'] : '';

    if (empty($messageText)) {
        echo json_encode(['success' => false, 'error' => 'Message text is required']);
        exit();
    }

    if (empty($receiverUsername)) {
        echo json_encode(['success' => false, 'error' => 'Receiver is required']);
        exit();
    }

    $username = $loggedInUser['username'];

    try {
        $messageId = sendMessage($db, $username, $receiverUsername, $messageText);
        echo json_encode(['success' => true, 'messageId' => $messageId]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Failed to send message']);
    }

    exit();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {

    $userId = intval($_POST['user_id']);
    $otherUser = User::getUserById($userId);

    // Prevent users from messaging themselves
    if ($loggedInUser['id'] === $userId) {
        header("Location: /");
        exit();
    }

    $senderUsername = $loggedInUser['username'];
    $receiverUsername = $otherUser->getUsername();

    header("Location: /pages/messages.php?user=" . $receiverUsername);
    exit();
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
    } else {
        header("Location: /");
    }
    exit();
}
