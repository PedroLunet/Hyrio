DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INTEGER PRIMARY KEY,
  name VARCHAR NOT NULL,
  password VARCHAR NOT NULL,  -- password in SHA-1 format
  email VARCHAR NOT NULL UNIQUE,
  role VARCHAR NOT NULL,  -- 'user',  'admin'
  profile_pic VARCHAR NOT NULL
);

DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
  id INTEGER PRIMARY KEY,
  name VARCHAR NOT NULL UNIQUE
);

DROP TABLE IF EXISTS services;
CREATE TABLE services (
  id INTEGER PRIMARY KEY,
  name VARCHAR NOT NULL,
  description VARCHAR NOT NULL,
  price FLOAT CHECK (price >= 0),
  seller VARCHAR NOT NULL REFERENCES users,
  category VARCHAR NOT NULL REFERENCES categories,
  image VARCHAR NOT NULL,
);

