let todos = [];
const userId = document.getElementById('userId').value;

// Load todos on page load
document.addEventListener('DOMContentLoaded', () => {
    loadTodos();
    setInterval(loadTodos, 5000); // Auto-sync every 5 seconds
});

// Add todo form handler
document.getElementById('addTodoForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const input = document.getElementById('todoInput');
    const priority = document.getElementById('prioritySelect').value;
    const text = input.value.trim();
    
    if (!text) return;
    
    try {
        const response = await fetch('api/todos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'create',
                text: text,
                priority: priority,
                user_id: userId
            })
        });
        
        const result = await response.json();
        if (result.success) {
            input.value = '';
            loadTodos();
        }
    } catch (error) {
        console.error('Error adding todo:', error);
    }
});

// Load todos from server
async function loadTodos() {
    try {
        const response = await fetch(`api/todos.php?action=list&user_id=${userId}`);
        const result = await response.json();
        
        if (result.success) {
            todos = result.data;
            renderTodos();
            updateActiveCount();
        }
    } catch (error) {
        console.error('Error loading todos:', error);
    }
}

// Render todos to DOM
function renderTodos() {
    const todosList = document.getElementById('todosList');
    
    if (todos.length === 0) {
        todosList.innerHTML = `
            <div class="empty-state">
                <p>Noch keine Aufgaben</p>
                <p class="empty-hint">Füge deine erste Aufgabe oben hinzu</p>
            </div>
        `;
        return;
    }
    
    todosList.innerHTML = todos.map(todo => `
        <div class="todo-item priority-${todo.priority}">
            <div 
                class="todo-checkbox ${todo.completed ? 'checked' : ''}"
                onclick="toggleTodo('${todo.id}')"
            ></div>
            <span class="todo-text ${todo.completed ? 'completed' : ''}">
                ${escapeHtml(todo.text)}
            </span>
            <button class="delete-button" onclick="deleteTodo('${todo.id}')">
                ✕
            </button>
        </div>
    `).join('');
}

// Toggle todo completion
async function toggleTodo(id) {
    const todo = todos.find(t => t.id === id);
    if (!todo) return;
    
    try {
        const response = await fetch('api/todos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'toggle',
                id: id,
                completed: !todo.completed
            })
        });
        
        const result = await response.json();
        if (result.success) {
            loadTodos();
        }
    } catch (error) {
        console.error('Error toggling todo:', error);
    }
}

// Delete todo
async function deleteTodo(id) {
    if (!confirm('Diese Aufgabe wirklich löschen?')) return;
    
    try {
        const response = await fetch('api/todos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'delete',
                id: id
            })
        });
        
        const result = await response.json();
        if (result.success) {
            loadTodos();
        }
    } catch (error) {
        console.error('Error deleting todo:', error);
    }
}

// Update active count
function updateActiveCount() {
    const activeCount = todos.filter(t => !t.completed).length;
    document.getElementById('activeCount').textContent = activeCount;
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
