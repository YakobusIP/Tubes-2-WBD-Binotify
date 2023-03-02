CREATE TYPE subscription_status AS ENUM('PENDING', 'ACCEPTED', 'REJECTED');

CREATE TABLE IF NOT EXISTS subscription (
    creator_id INT NOT NULL,
    subscriber_id uuid NOT NULL,
    status subscription_status NOT NULL DEFAULT 'PENDING',
    PRIMARY KEY(creator_id, subscriber_id),
    FOREIGN KEY(subscriber_id) REFERENCES binotify_user(user_id)
);