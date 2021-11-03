DROP DATABASE developlyon;

CREATE DATABASE developlyon;

USE developlyon;

CREATE TABLE user(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
nickname VARCHAR(63) NOT NULL,
password VARCHAR(255) NOT NULL,
picture_link VARCHAR(511),
email VARCHAR(63),
role VARCHAR(16));
 
CREATE TABLE category(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(255),
picture_link VARCHAR(255));

CREATE TABLE theme(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
category_id INT NOT NULL,
FOREIGN KEY (category_id) REFERENCES category(id),
name VARCHAR(255));

CREATE TABLE post(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
subject VARCHAR(255) NOT NULL,
user_id INT NOT NULL, 
FOREIGN KEY (user_id) REFERENCES user(id),
post_id INT NOT NULL, 
FOREIGN KEY (post_id) REFERENCES post(id),
date DATETIME DEFAULT CURRENT_TIMESTAMP,
message TEXT NOT NULL);

CREATE TABLE message(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
user_id INT NOT NULL, 
FOREIGN KEY (user_id) REFERENCES user(id),
theme_id INT NOT NULL, 
FOREIGN KEY (theme_id) REFERENCES theme(id),
date DATETIME DEFAULT CURRENT_TIMESTAMP,
message TEXT NOT NULL);

CREATE TABLE keyword(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
keyword VARCHAR(255));
 
CREATE TABLE keyword_post(
keyword_id INT NOT NULL,
FOREIGN KEY fk_kword_kwordpost (keyword_id) REFERENCES keyword(id),
post_id INT NOT NULL,
FOREIGN KEY fk_post_kwordpost (post_id) REFERENCES post(id));

CREATE TABLE notification(
user_id INT NOT NULL,
FOREIGN KEY (user_id) REFERENCES user(id),
post_id INT NOT NULL,
FOREIGN KEY (post_id) REFERENCES post(id));

CREATE TABLE search(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
word VARCHAR(63) NOT NULL,
date_last DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
nb_searched INT NOT NULL);

INSERT INTO category(name, picture_link) VALUES("PHP", "picture.png");
INSERT INTO category(name, picture_link) VALUES("JS", "picture.png");
INSERT INTO theme(category_id, name) VALUES(1, "POO for noobs");
INSERT INTO user(nickname, password, picture_link, email, role) VALUES("Effix", "1234", "pic.gif", "fx.laroche@gmail.com", "Admin");
SELECT * FROM post;
DELETE FROM post WHERE id=1;
SELECT * FROM category;
INSERT INTO theme(category_id, name) VALUES(1, "POO for kings");