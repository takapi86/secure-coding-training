CREATE DATABASE app;
use app;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `encrypted_password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

INSERT INTO users (email, name, encrypted_password) VALUES ("taro@example.com", "taro", "test_password");
INSERT INTO users (email, name, encrypted_password) VALUES ("hanako@example.com", "hanako", "test_password");
INSERT INTO users (email, name, encrypted_password) VALUES ("jiro@example.com", "jiro", "test_password");
