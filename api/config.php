<?php
// Database configuration
define('DB_PATH', __DIR__ . '/../data/todos.db');

// Ensure data directory exists
$dataDir = dirname(DB_PATH);
if (!file_exists($dataDir)) {
    mkdir($dataDir, 0755, true);
}

// Enable CORS for API calls
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Create or open SQLite database
function getDB() {
    try {
        // Check if PDO exists first
        if (!class_exists('PDO')) {
            throw new PDOException('PDO is not installed on this server');
        }

        // Check if SQLite driver is available
        $drivers = PDO::getAvailableDrivers();
        if (!in_array('sqlite', $drivers)) {
            throw new PDOException('SQLite driver not installed. Available drivers: ' . implode(', ', $drivers));
        }

        $db = new PDO('sqlite:' . DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create table if not exists
        $db->exec("
            CREATE TABLE IF NOT EXISTS todos (
                id TEXT PRIMARY KEY,
                text TEXT NOT NULL,
                completed INTEGER DEFAULT 0,
                priority TEXT DEFAULT 'medium',
                user_id TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        return $db;
    } catch (PDOException $e) {
        die(json_encode(['success' => false, 'error' => $e->getMessage()]));
    }
}
?>
