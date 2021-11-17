DROP DATABASE developlyon;

CREATE DATABASE developlyon;

USE developlyon;

CREATE TABLE user(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
nickname VARCHAR(63) NOT NULL,
password VARCHAR(255) NOT NULL,
picture_link VARCHAR(511),
email VARCHAR(63) UNIQUE,
role VARCHAR(16));
 
CREATE TABLE category(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(63),
picture_link VARCHAR(255));

CREATE TABLE theme(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
category_id INT NOT NULL,
FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE,
name VARCHAR(255));

CREATE TABLE post(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
subject VARCHAR(255) NOT NULL,
user_id INT NOT NULL, 
FOREIGN KEY (user_id) REFERENCES user(id),
theme_id INT NOT NULL, 
FOREIGN KEY (theme_id) REFERENCES theme(id) ON DELETE CASCADE,
date DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
message TEXT NOT NULL,
keyword VARCHAR(255));

CREATE TABLE message(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
user_id INT NOT NULL, 
FOREIGN KEY (user_id) REFERENCES user(id),
post_id INT NOT NULL, 
FOREIGN KEY (post_id) REFERENCES post(id) ON DELETE CASCADE,
date DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
message TEXT NOT NULL);

CREATE TABLE search(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
word VARCHAR(63) NOT NULL,
date_last DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
nb_searched INT NOT NULL);

INSERT INTO category(name, picture_link) VALUES("PHP", "php.jpg");
INSERT INTO category(name, picture_link) VALUES("Javascript", "js.jpg");
INSERT INTO category(name, picture_link) VALUES("Java", "java.jpg");
INSERT INTO category(name, picture_link) VALUES("SQL", "sql.jpg");
INSERT INTO theme(category_id, name) VALUES(1, "POO");
INSERT INTO theme(category_id, name) VALUES(1, "PDO Initialisation");
INSERT INTO theme(category_id, name) VALUES(1, "PDO Implémentation");
INSERT INTO theme(category_id, name) VALUES(1, "PDO Déconnexion");
INSERT INTO theme(category_id, name) VALUES(1, "PDO Formulaires");
INSERT INTO theme(category_id, name) VALUES(1, "PDO Mise à jour");
INSERT INTO theme(category_id, name) VALUES(2, "Javascript pour les nuls");
INSERT INTO theme(category_id, name) VALUES(3, "Java, la POO");
INSERT INTO theme(category_id, name) VALUES(4, "SQL, premier pas");
INSERT INTO theme(category_id, name) VALUES(4, "La modélisation");
INSERT INTO theme(category_id, name) VALUES(4, "Les requêtes complexes");
INSERT INTO post (subject, user_id, theme_id, date, message, keyword) VALUES ('Pourquoi PDO', 1, 1, CURRENT_DATE, 'Voici le premier poste pour tester la recherche de post', 'PDO, PHP, WEB');
INSERT INTO post (subject, user_id, theme_id, date, message, keyword) VALUES ('Pourquoi POO', 1, 1, CURRENT_DATE, 'Voici le deuxième poste pour tester la recherche de post', 'POO, PHP, WEB');
INSERT INTO post (subject, user_id, theme_id, date, message, keyword) VALUES ('Que fait PDO', 1, 1, CURRENT_DATE, 'Voici le troisième poste pour tester la recherche de post', 'PDO, PHP, WEB');
INSERT INTO post (subject, user_id, theme_id, date, message, keyword) VALUES ('Que ne fait pas PDO', 1, 1, CURRENT_TIMESTAMP, 'Voici le quatrième poste pour tester la recherche de post', 'PDO, PHP, WEB');
INSERT INTO post (subject, user_id, theme_id, date, message, keyword) VALUES ('Que ne fait pas PDO', 2, 1, CURRENT_TIMESTAMP, 'Voici le quatrième poste pour tester la recherche de post', 'PDO, PHP, WEB');
INSERT INTO user(nickname, password, picture_link, email, role) VALUES('admin', '$2y$10$7Z5kYtIMAmm5f5bZIcvioeZZKgL0bBngfCO6onHP3TfzYVGYpSTuW', 'login.png', 'admin@develop.lyon', 'admin');
INSERT INTO user(nickname, password, picture_link, email, role) VALUES('JC', '$2y$10$.tp0f4iv6.yLI3s3BXNDM.38k3/E47s4INlnsTRWHSSGtECtDmdJC', 'login.png', 'jc@wild.com', 'user');
INSERT INTO user(nickname, password, picture_link, email, role) VALUES('Effixel', '$2y$10$834L3dsEpNMaeUu4nnpdfOYOaY7gF0JMLveXfBAC/Gmaj4vhpICGu', 'login.png', 'fxl@wild.com', 'user');
