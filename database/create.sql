DROP TABLE IF EXISTS users;
CREATE TABLE users (id INTEGER PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  username VARCHAR(255) NOT NULL UNIQUE,
  password TEXT NOT NULL,  -- Use bcrypt for hashing
  email VARCHAR(255) NOT NULL UNIQUE,
  is_seller BOOLEAN NOT NULL DEFAULT 0,
  is_admin BOOLEAN NOT NULL DEFAULT 0,
  profile_pic TEXT NOT NULL,
  bio TEXT);

DROP TABLE IF EXISTS categories;
CREATE TABLE categories (id INTEGER PRIMARY KEY,
  name VARCHAR(255) NOT NULL UNIQUE);

DROP TABLE IF EXISTS services;
CREATE TABLE services (id INTEGER PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10, 2) NOT NULL CHECK (price >=0),
  seller INTEGER NOT NULL REFERENCES users(id),
  category INTEGER NOT NULL REFERENCES categories(id),
  delivery_time INTEGER NOT NULL CHECK (delivery_time > 0),  -- in hours
  image TEXT NOT NULL,
  rating DECIMAL(2, 1) CHECK (rating >=0 AND rating <=5));

DROP TABLE IF EXISTS favorites;
CREATE TABLE favorites (id INTEGER PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  service_id INTEGER NOT NULL REFERENCES services(id) ON DELETE CASCADE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(user_id, service_id));

DROP TABLE IF EXISTS purchases;
CREATE TABLE purchases (
  id INTEGER PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  service_id INTEGER NOT NULL REFERENCES services(id) ON DELETE CASCADE,
  message TEXT,
  status VARCHAR(50) NOT NULL DEFAULT 'pending',  -- pending, completed, cancelled
  purchased_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS ratings;
CREATE TABLE ratings (
  id INTEGER PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  service_id INTEGER NOT NULL REFERENCES services(id) ON DELETE CASCADE,
  rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
  review TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(user_id, service_id)  -- Ensure one rating per user per service
);

DROP TABLE IF EXISTS messages;
CREATE TABLE messages (
  id INTEGER PRIMARY KEY,
  sender VARCHAR(255) NOT NULL REFERENCES users(username),
  receiver VARCHAR(255) NOT NULL REFERENCES users(username),
  message_text TEXT NOT NULL,
  timestamp INTEGER NOT NULL,
  read_timestamp INTEGER DEFAULT 0  -- 0 = unread, timestamp = when read
);
