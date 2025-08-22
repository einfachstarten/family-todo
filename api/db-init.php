<?php
// Initialize database - run this once to set up the database
require_once 'config.php';

$db = getDB();
echo "Database initialized successfully at: " . DB_PATH . "\n";
echo "Table 'todos' created.\n";

// Test the database
$testId = uniqid('test_', true);
$db->exec("INSERT INTO todos (id, text, user_id) VALUES ('$testId', 'Test Todo', 'test_user')");
$db->exec("DELETE FROM todos WHERE id = '$testId'");
echo "Database test successful.\n";
?>
