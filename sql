CREATE TABLE Products1 ( id int PRIMARY KEY AUTO_INCREMENT, name varchar(255), description varchar(255), price int, author_id int, FOREIGN KEY (author_id) REFERENCES userdata(id) );
CREATE TABLE userdata ( id int PRIMARY KEY AUTO_INCREMENT, username varchar(255), password varchar(255), email varchar(255) );
#data mohou být přidána až po zapnutí aplikace
