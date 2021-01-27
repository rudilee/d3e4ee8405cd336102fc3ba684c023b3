-- Users table
CREATE TABLE users (
    id serial PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(60) NOT NULL
);

-- Dummy admin user, password: pass123
INSERT INTO users (username, password)
VALUES ('admin', '$2y$10$BmkKxsQaNalxw4ep4BWEPeoK1MKyk1kCMTYulzTUj8Y4WzWVsR6zG');

-- Emails table to store sent/unsent emails
CREATE TABLE emails (
    id serial PRIMARY KEY,
    sender VARCHAR(320) NOT NULL,
    receiver VARCHAR(320) NOT NULL,
    subject VARCHAR(78) NOT NULL,
    message TEXT, 
    sent_at TIMESTAMP
);