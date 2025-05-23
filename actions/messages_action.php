<?php

declare(strict_types=1);

require_once(__DIR__ . '/../includes/common.php');
require_once(__DIR__ . '/../includes/auth.php');
require_once(__DIR__ . '/../database/classes/user.php');
require_once(__DIR__ . '/../database/classes/service.php');
require_once(__DIR__ . '/../database/classes/messages.php');
require_once(__DIR__ . '/../includes/database.php');

if (!Auth::getInstance()->isLoggedIn()) {
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
    $lastMessageId = isset($_GET['last_message_id']) ? (int)$_GET['last_message_id'] : 0;

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
        
        $message['timestamp'] = (int)$message['timestamp'];
    }

    echo json_encode(['messages' => $newMessages]);
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

    // Check if a conversation already exists
    $messages = getMessages($db, $senderUsername, $receiverUsername);

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
