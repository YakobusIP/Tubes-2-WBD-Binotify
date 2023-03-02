CREATE TABLE IF NOT EXISTS binotify_song (
	song_id SERIAL PRIMARY KEY,
	judul VARCHAR(64) NOT NULL,
	penyanyi VARCHAR(128) NOT NULL,
	tanggal_terbit DATE NOT NULL,
	genre VARCHAR(64),
	duration INT NOT NULL,
	audio_path VARCHAR(256) NOT NULL,
	image_path VARCHAR(256) NOT NULL,
	album_id INT DEFAULT NULL,
	FOREIGN KEY(album_id) REFERENCES binotify_album(album_id)
);