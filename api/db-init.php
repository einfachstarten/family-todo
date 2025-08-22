<?php
// Initialize database - run this once to set up the database
require_once 'config.php';

try {
    $db = getDB();

    // Test the database connection and permissions
    $testId = uniqid('test_', true);
    $db->exec("INSERT INTO todos (id, text, user_id) VALUES ('$testId', 'Test Todo', 'test_user')");
    $db->exec("DELETE FROM todos WHERE id = '$testId'");

    echo json_encode([
        'success' => true,
        'message' => 'Database initialized successfully',
        'path' => DB_PATH
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
