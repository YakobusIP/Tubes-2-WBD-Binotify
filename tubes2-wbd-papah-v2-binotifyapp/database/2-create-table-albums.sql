CREATE TABLE IF NOT EXISTS binotify_album (
    album_id SERIAL PRIMARY KEY NOT NULL,
    judul VARCHAR(64) NOT NULL,
    penyanyi VARCHAR(128) NOT NULL,
    total_duration INT NOT NULL,
    image_path VARCHAR(256) NOT NULL,
    tanggal_terbit DATE NOT NULL,
    genre VARCHAR(64)
);