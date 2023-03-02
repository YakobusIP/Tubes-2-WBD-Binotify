CREATE TABLE IF NOT EXISTS binotify_user (
    user_id uuid PRIMARY KEY DEFAULT uuid_generate_v4(),
    full_name VARCHAR(256) NOT NULL,
    email VARCHAR(256) NOT NULL UNIQUE,
    password VARCHAR(256) NOT NULL,
    username VARCHAR(256) NOT NULL UNIQUE,
    isAdmin BOOLEAN NOT NULL DEFAULT FALSE
);