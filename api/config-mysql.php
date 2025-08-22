<?php
// MySQL configuration for World4You
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name_here');  // Get from World4You control panel
define('DB_USER', 'your_database_user_here');   // Get from World4You control panel  
define('DB_PASS', 'your_database_pass_here');   // Get from World4You control panel

// Enable CORS for API calls
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Create MySQL connection
function getDB() {
    try {
        $db = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
            DB_USER,
            DB_PASS
        );
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create table if not exists
        $db->exec("
            CREATE TABLE IF NOT EXISTS todos (
                id VARCHAR(50) PRIMARY KEY,
                text TEXT NOT NULL,
                completed TINYINT(1) DEFAULT 0,
                priority VARCHAR(10) DEFAULT 'medium',
                user_id VARCHAR(50) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");

        return $db;
    } catch (PDOException $e) {
        die(json_encode(['success' => false, 'error' => $e->getMessage()]));
    }
}
?>
