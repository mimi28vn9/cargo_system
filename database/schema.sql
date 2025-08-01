CREATE DATABASE IF NOT EXISTS cargo_db;
USE cargo_db;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(50) NOT NULL,
  role ENUM('customer', 'wholesaler', 'transporter') NOT NULL
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  price DECIMAL(10,2),
  delivery_fee DECIMAL(10,2)
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  material VARCHAR(100),
  quantity INT,
  address VARCHAR(255)
);

CREATE TABLE deliveries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT,
  transporter_id INT,
  status VARCHAR(50),
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (transporter_id) REFERENCES users(id)
);
-- SQL schema for Build Cargo System