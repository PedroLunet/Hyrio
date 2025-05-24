<?php
function getConversations($db, $username)
{
    // First get all unique conversations
    $query = $db->prepare("
            SELECT DISTINCT 
                CASE 
                    WHEN sender = :username THEN receiver 
                    ELSE sender 
                END AS other_user
            FROM messages 
            WHERE sender = :username OR receiver = :username
        ");
    $query->execute(['username' => $username]);
    $conversations = $query->fetchAll(PDO::FETCH_ASSOC);

    // For each conversation, get the last message and unread count
    $result = [];
    foreach ($conversations as $conv) {
        $otherUser = $conv['other_user'];

        // Get last message
        $lastMsgQuery = $db->prepare("
                SELECT message_text, timestamp, sender 
                FROM messages 
                WHERE (sender = :username AND receiver = :other_user) 
                   OR (sender = :other_user AND receiver = :username)
                ORDER BY timestamp DESC 
                LIMIT 1
            ");
        $lastMsgQuery->execute(['username' => $username, 'other_user' => $otherUser]);
        $lastMessage = $lastMsgQuery->fetch(PDO::FETCH_ASSOC);

        // Count unread messages (messages from other user with read_timestamp = 0)
        $unreadQuery = $db->prepare("
                SELECT COUNT(*) as unread_count 
                FROM messages 
                WHERE sender = :other_user 
                  AND receiver = :username 
                  AND read_timestamp = 0
            ");
        $unreadQuery->execute([
            'other_user' => $otherUser,
            'username' => $username
        ]);
        $unreadData = $unreadQuery->fetch(PDO::FETCH_ASSOC);

        $result[] = [
            'other_user' => $otherUser,
            'last_message' => $lastMessage ? $lastMessage['message_text'] : '',
            'last_message_timestamp' => $lastMessage ? $lastMessage['timestamp'] : 0,
            'last_message_sender' => $lastMessage ? $lastMessage['sender'] : '',
            'unread_count' => $unreadData['unread_count']
        ];
    }

    // Sort by last message timestamp descending
    usort($result, function ($a, $b) {
        return $b['last_message_timestamp'] - $a['last_message_timestamp'];
    });

    return $result;
}

function getMessages($db, $username1, $username2)
{
    $query = $db->prepare("
            SELECT * FROM messages 
            WHERE ((sender = :username1 AND receiver = :username2) OR (sender = :username2 AND receiver = :username1)) 
            ORDER BY timestamp ASC
        ");
    $query->execute(['username1' => $username1, 'username2' => $username2]);

    $messages = $query->fetchAll(PDO::FETCH_ASSOC);

    return $messages;
}

function sendMessage($db, $sender, $receiver, $messageText)
{
    $query = $db->prepare("INSERT INTO messages (sender, receiver, message_text, timestamp, read_timestamp) VALUES (:sender, :receiver, :message_text, :timestamp, 0)");
    $query->execute([
        ':sender' => $sender,
        ':receiver' => $receiver,
        ':message_text' => $messageText,
        ':timestamp' => time()
    ]);

    return $db->lastInsertId();
}

function getNewMessages($db, $user, $otherUser, $lastMessageId)
{
    $query = $db->prepare("SELECT * FROM messages WHERE ((sender = :user AND receiver = :otherUser) OR (sender = :otherUser AND receiver = :user)) AND id > :lastMessageId ORDER BY id ASC");
    $query->execute([':user' => $user, ':otherUser' => $otherUser, ':lastMessageId' => $lastMessageId]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getLatestMessageId($db, $username, $otherUser)
{
    $stmt = $db->prepare('SELECT MAX(id) AS max_id FROM messages WHERE (sender = ? AND receiver = ? OR sender = ? AND receiver = ?)');
    $stmt->execute([$username, $otherUser, $otherUser, $username]);
    $result = $stmt->fetch();
    return $result['max_id'] ?? 0;
}

function markConversationAsRead($db, $username, $otherUser)
{
    // Mark all messages from otherUser to username as read
    $stmt = $db->prepare('
            UPDATE messages 
            SET read_timestamp = ? 
            WHERE sender = ? AND receiver = ? AND read_timestamp = 0
        ');
    $stmt->execute([time(), $otherUser, $username]);
}