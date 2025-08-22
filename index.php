<?php
// Remove custom session path - use default
session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = uniqid('user_', true);
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Todo</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <header class="header-card">
            <div class="header-content">
                <h1>Meine Aufgaben</h1>
                <p class="date"><?php echo date('l, j. F Y'); ?></p>
            </div>
            <div class="stats">
                <div class="stat-number" id="activeCount">0</div>
                <div class="stat-label">Aktiv</div>
            </div>
        </header>

        <div class="add-todo-card">
            <form id="addTodoForm">
                <div class="form-row">
                    <input 
                        type="text" 
                        id="todoInput" 
                        class="todo-input" 
                        placeholder="Was ist zu tun?"
                        required
                    >
                    <select id="prioritySelect" class="priority-select">
                        <option value="low">Niedrig</option>
                        <option value="medium" selected>Mittel</option>
                        <option value="high">Hoch</option>
                    </select>
                    <button type="submit" class="add-button">
                        <span class="plus-icon">+</span>
                        Hinzufügen
                    </button>
                </div>
            </form>
        </div>

        <div class="todos-card">
            <div id="todosList" class="todos-list">
                <div class="empty-state">
                    <p>Noch keine Aufgaben</p>
                    <p class="empty-hint">Füge deine erste Aufgabe oben hinzu</p>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="userId" value="<?php echo $_SESSION['user_id']; ?>">
    <script src="assets/app.js"></script>
</body>
</html>
