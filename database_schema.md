# Database Schema Documentation

The application uses a single MySQL database named `blog`.

## Database: `blog`

### Table: `users`

This table stores user credentials for authentication. Passwords are stored as secure hashes using PHP's `password_hash()` function.

| Column Name | Data Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | `INT` | `PRIMARY KEY`, `AUTO_INCREMENT` | Unique identifier for the user. |
| `username` | `VARCHAR(50)` | `NOT NULL`, `UNIQUE` | The user's login name. |
| `password` | `VARCHAR(255)` | `NOT NULL` | The securely hashed password. |

### Table: `posts`

This table stores the blog post data.

| Column Name | Data Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | `INT` | `PRIMARY KEY`, `AUTO_INCREMENT` | Unique identifier for the post. |
| `title` | `VARCHAR(255)` | `NOT NULL` | The title of the blog post. |
| `content` | `TEXT` | `NOT NULL` | The main content of the blog post. |
| `created_at` | `TIMESTAMP` | `DEFAULT CURRENT_TIMESTAMP` | The date and time the post was created. |
