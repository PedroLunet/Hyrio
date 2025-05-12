DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INTEGER PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  username VARCHAR(255) NOT NULL UNIQUE,
  password TEXT NOT NULL,  -- Use bcrypt for hashing
  email VARCHAR(255) NOT NULL UNIQUE,
  role VARCHAR(50) NOT NULL CHECK (role IN ('user', 'freelancer', 'admin')),
  profile_pic TEXT NOT NULL,
  bio TEXT
);

DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
  id INTEGER PRIMARY KEY,
  name VARCHAR(255) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS services;
CREATE TABLE services (
  id INTEGER PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10, 2) NOT NULL CHECK (price >= 0),
  seller INTEGER NOT NULL REFERENCES users(id),
  category INTEGER NOT NULL REFERENCES categories(id),
  image TEXT NOT NULL,
  rating DECIMAL(2, 1) CHECK (rating >= 0 AND rating <= 5)
);