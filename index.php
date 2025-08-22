<?php
session_start();

// Define your family PINs
$VALID_PINS = [
    '1234' => 'family_shared',  // Shared family list
    '5678' => 'personal_anna',  // Anna's personal list
    '9012' => 'personal_mark'   // Mark's personal list
];

// Check if PIN is submitted
if (isset($_POST['pin'])) {
    $pin = $_POST['pin'];
    if (isset($VALID_PINS[$pin])) {
        $_SESSION['user_id'] = $VALID_PINS[$pin];
        $_SESSION['logged_in'] = true;
    } else {
        $error = "Falscher PIN";
    }
}

// Check if logged in
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
$userId = $_SESSION['user_id'] ?? '';

// Logout handling
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Todo</title>
    <link rel="stylesheet" href="assets/style.css">
    <script>
        // Dark mode handling - inline to ensure it's always available
        function initTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }
    </script>
    <?php if (!$isLoggedIn): ?>
    <style>
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            max-width: 400px;
            margin: 100px auto;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            text-align: center;
        }
        .login-title {
            font-size: 2.5em;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }
        .login-subtitle {
            color: #64748b;
            margin-top: 10px;
            margin-bottom: 30px;
        }
        .pin-input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1.5em;
            text-align: center;
            letter-spacing: 10px;
            margin: 20px 0;
            background: white;
            transition: all 0.3s ease;
        }
        .pin-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .pin-button {
            width: 100%;
            padding: 14px 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px 0 rgba(102, 126, 234, 0.4);
        }
        .pin-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px 0 rgba(102, 126, 234, 0.5);
        }
        .pin-button:active {
            transform: translateY(0);
        }
        .error-message {
            color: #ef4444;
            margin: 10px 0;
            padding: 10px;
            background: #fee2e2;
            border-radius: 8px;
        }
        .pin-hint {
            color: #94a3b8;
            margin-top: 30px;
            font-size: 0.9em;
            padding: 15px;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 12px;
        }
        .logout-link {
            position: absolute;
            top: 30px;
            right: 30px;
            color: #ef4444;
            text-decoration: none;
            padding: 8px 16px;
            border: 1px solid #ef4444;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.9em;
        }
        .logout-link:hover {
            background: #ef4444;
            color: white;
        }
        .list-indicator {
            display: inline-block;
            padding: 4px 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            font-size: 0.85em;
            margin-left: 10px;
        }
    </style>
    <?php endif; ?>
</head>
<body onload="initTheme()">
    <!-- Dark mode toggle with improved design -->
    <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle dark mode">
        <svg class="moon-icon" fill="currentColor" viewBox="0 0 20 20">
            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
        </svg>
        <svg class="sun-icon" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
        </svg>
    </button>
    <div class="container">
        <?php if (!$isLoggedIn): ?>
            <div class="login-card">
                <h1 class="login-title">Family Todo</h1>
                <p class="login-subtitle">PIN eingeben für Zugang</p>
                
                <form method="POST">
                    <!-- Hidden username field for accessibility -->
                    <input type="hidden" name="username" value="family_user" autocomplete="username">
                    <?php if (isset($error)): ?>
                        <p class="error-message"><?php echo $error; ?></p>
                    <?php endif; ?>
                    <input
                        type="password"
                        name="pin"
                        class="pin-input"
                        placeholder="••••"
                        maxlength="4"
                        pattern="[0-9]{4}"
                        inputmode="numeric"
                        autocomplete="current-password"
                        required
                        autofocus
                    >
                    <button type="submit" class="pin-button">Einloggen</button>
                </form>
                <div class="pin-hint">
                    <strong>Test PINs:</strong><br>
                    1234 - Gemeinsame Familienliste<br>
                    5678 - Anna's Liste<br>
                    9012 - Mark's Liste
                </div>
            </div>
        <?php else: ?>
            <a href="?logout=1" class="logout-link">Abmelden</a>
            
            <header class="header-card">
                <div class="header-content">
                    <h1>
                        Meine Aufgaben
                        <span class="list-indicator">
                            <?php 
                            $listNames = [
                                'family_shared' => 'Familie',
                                'personal_anna' => 'Anna',
                                'personal_mark' => 'Mark'
                            ];
                            echo $listNames[$userId] ?? 'Persönlich';
                            ?>
                        </span>
                    </h1>
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

            <input type="hidden" id="userId" value="<?php echo htmlspecialchars($userId); ?>">
        <?php endif; ?>
    </div>
    <script src="assets/app.js"></script>
</body>
</html>
