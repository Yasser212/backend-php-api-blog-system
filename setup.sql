-- Create the database
CREATE DATABASE blog;
-- Use the database
USE blog;
-- Create tables
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);
CREATE TABLE permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);
CREATE TABLE user_roles (
    user_id INT,
    role_id INT,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);
CREATE TABLE role_permissions (
    role_id INT,
    permission_id INT,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author_id INT,
    category_id INT,
    description TEXT,
    body TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE
    SET NULL,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE
    SET NULL
);
-- Insert sample data
INSERT INTO users (
        first_name,
        last_name,
        email,
        password_hash,
        is_admin
    )
VALUES (
        'John',
        'Doe',
        'john.doe@example.com',
        'hashedpassword1',
        1
    ),
    (
        'Jane',
        'Smith',
        'jane.smith@example.com',
        'hashedpassword2',
        0
    );
INSERT INTO roles (name)
VALUES ('Admin'),
    ('Editor'),
    ('Author');
INSERT INTO permissions (name)
VALUES ('create_post'),
    ('edit_post'),
    ('delete_post');
INSERT INTO user_roles (user_id, role_id)
VALUES (1, 1),
    -- John Doe is an Admin
    (2, 2);
-- Jane Smith is an Editor
INSERT INTO role_permissions (role_id, permission_id)
VALUES (1, 1),
    (1, 2),
    (1, 3),
    -- Admin can create, edit, and delete posts
    (2, 1),
    (2, 2);
-- Editor can create and edit posts
INSERT INTO categories (name)
VALUES ('Technology'),
    ('Health'),
    ('Lifestyle');
INSERT INTO posts (title, author_id, category_id, description, body)
VALUES (
        'First Post',
        1,
        1,
        'This is the description of the first post',
        'This is the body of the first post.'
    ),
    (
        'Second Post',
        2,
        2,
        'This is the description of the second post',
        'This is the body of the second post.'
    );