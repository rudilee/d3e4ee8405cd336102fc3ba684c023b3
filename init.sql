-- Emails table
CREATE TABLE emails (
    id serial PRIMARY KEY,
    sender VARCHAR(320) NOT NULL,
    receiver VARCHAR(320) NOT NULL,
    subject VARCHAR(78) NOT NULL,
    message TEXT, 
    sent_at TIMESTAMP
);