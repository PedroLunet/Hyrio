<?php
    function getConversations($db, $username) {
        $query = $db->prepare("
        SELECT DISTINCT receiver AS other_user FROM messages WHERE sender = :username
        UNION
        SELECT DISTINCT sender AS other_user FROM messages WHERE receiver = :username
        ");
        $query->execute(['username' => $username]);
    
        $conversations = $query->fetchAll(PDO::FETCH_ASSOC);
    
        return $conversations;
    }

    function getMessages($db, $username1, $username2) {
        $query = $db->prepare("
            SELECT * FROM messages 
            WHERE ((sender = :username1 AND receiver = :username2) OR (sender = :username2 AND receiver = :username1)) 
            ORDER BY timestamp ASC
        ");
        $query->execute(['username1' => $username1, 'username2' => $username2]);
    
        $messages = $query->fetchAll(PDO::FETCH_ASSOC);
    
        return $messages;
    }
    
    function sendMessage($db, $sender, $receiver, $messageText) {
        $query = $db->prepare("INSERT INTO messages (sender, receiver, message_text, timestamp) VALUES (:sender, :receiver, :message_text, :timestamp)");
        $query->execute([
            ':sender' => $sender,
            ':receiver' => $receiver,
            ':message_text' => $messageText,
            ':timestamp' => time()
        ]);
        
        return $db->lastInsertId();
    }
    
    function getNewMessages($db, $user, $otherUser, $lastMessageId) {
        $query = $db->prepare("SELECT * FROM messages WHERE ((sender = :user AND receiver = :otherUser) OR (sender = :otherUser AND receiver = :user)) AND id > :lastMessageId ORDER BY id ASC");
        $query->execute([':user' => $user, ':otherUser' => $otherUser, ':lastMessageId' => $lastMessageId]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function getLatestMessageId($db, $username, $otherUser) {
        $stmt = $db->prepare('SELECT MAX(id) AS max_id FROM messages WHERE (sender = ? AND receiver = ? OR sender = ? AND receiver = ?)');
        $stmt->execute([$username, $otherUser, $otherUser, $username]);
        $result = $stmt->fetch();
        return $result['max_id'] ?? 0;
    }