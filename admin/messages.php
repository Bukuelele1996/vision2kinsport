<?php
// contact_messages.php

// Database connection
$mysqli = new mysqli('localhost', 'username', 'password', 'database_name');

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Function to fetch contact messages
function fetchContactMessages($search = '', $filter = '', $readStatus = '') {
    global $mysqli;
    $query = "SELECT * FROM contact_messages WHERE 1";

    if (!empty($search)) {
        $query .= " AND (subject LIKE '%" . $mysqli->real_escape_string($search) . "%' OR message LIKE '%" . $mysqli->real_escape_string($search) . "%')";
    }

    if (!empty($filter)) {
        $query .= " AND status = '" . $mysqli->real_escape_string($filter) . "'";
    }

    if ($readStatus !== '') {
        $query .= " AND read_status = '" . ($readStatus ? '1' : '0') . "'";
    }

    $result = $mysqli->query($query);
    return $result;
}

// Function to mark a message as read
function markAsRead($id) {
    global $mysqli;
    $stmt = $mysqli->prepare("UPDATE contact_messages SET read_status = 1 WHERE id = ?");
    $stmt->bind_param('i', $id);
    return $stmt->execute();
}

// Function to reply to a message
function replyToMessage($id, $reply) {
    // Logic to send email would go here
    // After sending email, you might want to update the message status too
    markAsRead($id);
}

// Handling requests
action = $_POST['action'] ?? '';
if ($action === 'fetch') {
    $search = $_POST['search'] ?? '';
    $filter = $_POST['filter'] ?? '';
    $readStatus = $_POST['readStatus'] ?? '';
    $messages = fetchContactMessages($search, $filter, $readStatus);
    // Return messages as JSON
    echo json_encode($messages);
} elseif ($action === 'reply') {
    $id = $_POST['id'] ?? '';
    $reply = $_POST['reply'] ?? '';
    replyToMessage($id, $reply);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}