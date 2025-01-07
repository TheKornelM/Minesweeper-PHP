CREATE TABLE userdata (
  id SERIAL PRIMARY KEY,
  username VARCHAR(128) NOT NULL,
  normalized_username VARCHAR(128) NOT NULL,
  email VARCHAR(128) NOT NULL,
  password VARCHAR(512) NOT NULL,
  UNIQUE (username),
  UNIQUE (email),
  UNIQUE (normalized_username)
  );

CREATE TABLE elapsed_times (
    id SERIAL PRIMARY KEY,
    hour INT NOT NULL DEFAULT 0,
    minute INT NOT NULL DEFAULT 0,
    second INT NOT NULL DEFAULT 0,
    count INT NOT NULL DEFAULT 0
);

CREATE TABLE games (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    save_name VARCHAR(255) NOT NULL,
    elapsed_time_id INT,
    board_file_name VARCHAR(255) NOT NULL,
    saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES userdata(id),
    FOREIGN KEY (elapsed_time_id) REFERENCES elapsed_times(id) ON DELETE CASCADE
);

