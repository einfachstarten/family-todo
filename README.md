# Family Todo App

A lightweight web-based todo application built with PHP, HTML, CSS, and vanilla JavaScript. Uses SQLite for storage and session-based user identification.

## Features
- Add, toggle, and delete todos
- Priority levels with visual indicators
- Auto-sync every 5 seconds
- Mobile responsive design
- No external dependencies

## Setup
1. Ensure the `data` directory is writable (`chmod 755 data`).
2. Visit `api/db-init.php` once in the browser to initialize the SQLite database.
3. Open `index.php` in your browser to start using the app.

The SQLite database file (`data/todos.db`) will be created automatically.
