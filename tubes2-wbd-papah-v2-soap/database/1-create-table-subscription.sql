CREATE TABLE IF NOT EXISTS subscription (
    creator_id INT NOT NULL,
    subscriber_id VARCHAR(128) NOT NULL,
    status enum('PENDING', 'ACCEPTED', 'REJECTED') NOT NULL DEFAULT 'PENDING',
    PRIMARY KEY(creator_id, subscriber_id)
);