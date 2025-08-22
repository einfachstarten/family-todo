<?php
require_once 'config.php';

$db = getDB();

// Get request data
$method = $_SERVER['REQUEST_METHOD'];
$action = '';
$data = [];

if ($method === 'GET') {
    $action = $_GET['action'] ?? 'list';
    $data = $_GET;
} else if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';
    $data = $input;
}

// Process action
switch ($action) {
    case 'list':
        listTodos($db, $data);
        break;
    case 'create':
        createTodo($db, $data);
        break;
    case 'toggle':
        toggleTodo($db, $data);
        break;
    case 'delete':
        deleteTodo($db, $data);
        break;
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}

// List todos for a user
function listTodos($db, $data) {
    $userId = $data['user_id'] ?? '';
    
    if (empty($userId)) {
        echo json_encode(['success' => false, 'error' => 'User ID required']);
        return;
    }
    
    try {
        $stmt = $db->prepare("
            SELECT * FROM todos 
            WHERE user_id = :user_id 
            ORDER BY created_at DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert completed to boolean
        foreach ($todos as &$todo) {
            $todo['completed'] = (bool)$todo['completed'];
        }
        
        echo json_encode(['success' => true, 'data' => $todos]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

// Create new todo
function createTodo($db, $data) {
    $text = $data['text'] ?? '';
    $priority = $data['priority'] ?? 'medium';
    $userId = $data['user_id'] ?? '';
    
    if (empty($text) || empty($userId)) {
        echo json_encode(['success' => false, 'error' => 'Text and User ID required']);
        return;
    }
    
    try {
        $id = uniqid('todo_', true);
        $stmt = $db->prepare("
            INSERT INTO todos (id, text, priority, user_id) 
            VALUES (:id, :text, :priority, :user_id)
        ");
        $stmt->execute([
            ':id' => $id,
            ':text' => $text,
            ':priority' => $priority,
            ':user_id' => $userId
        ]);
        
        echo json_encode(['success' => true, 'id' => $id]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

// Toggle todo completion
function toggleTodo($db, $data) {
    $id = $data['id'] ?? '';
    $completed = $data['completed'] ?? false;
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'error' => 'ID required']);
        return;
    }
    
    try {
        $stmt = $db->prepare("
            UPDATE todos 
            SET completed = :completed, updated_at = CURRENT_TIMESTAMP 
            WHERE id = :id
        ");
        $stmt->execute([
            ':completed' => $completed ? 1 : 0,
            ':id' => $id
        ]);
        
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

// Delete todo
function deleteTodo($db, $data) {
    $id = $data['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'error' => 'ID required']);
        return;
    }
    
    try {
        $stmt = $db->prepare("DELETE FROM todos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>
