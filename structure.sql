/*il database si chiama     myfootball  */
CREATE TABLE users(
    id integer primary key auto_increment,
    name varchar(255) not null,
    surname varchar(255) not null,
    username varchar(16) not null unique,
    email varchar(255) not null unique,
    password varchar(255) not null,
    numeroPost integer default 0
) Engine = InnoDB;

CREATE TABLE posts (
    id integer primary key auto_increment,
    user integer not null,
    time timestamp not null default current_timestamp,
    nlikes integer default 0,
    ncomments integer default 0,
    content json,
    foreign key(user) references users(id) on delete cascade on update cascade
) Engine = InnoDB;

CREATE TABLE likes (
    user integer not null,
    post integer not null,
    index xuser(user),
    index xpost(post),
    foreign key(user) references users(id) on delete cascade on update cascade,
    foreign key(post) references posts(id) on delete cascade on update cascade,
    primary key(user, post)
) Engine = InnoDB;

CREATE TABLE comments (
    id integer primary key auto_increment,
    user integer not null,
    post integer not null,
    time timestamp not null default current_timestamp,
    text varchar(255),
    index xuser(user),
    index xpost(post),
    foreign key(user) references users(id) on delete cascade on update cascade,
    foreign key(post) references posts(id) on delete cascade on update cascade
) Engine = InnoDB;

DELIMITER //
CREATE TRIGGER likes_trigger
AFTER INSERT ON likes
FOR EACH ROW
BEGIN
UPDATE posts 
SET nlikes = nlikes + 1
WHERE id = new.post;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER unlikes_trigger
AFTER DELETE ON likes
FOR EACH ROW
BEGIN
UPDATE posts 
SET nlikes = nlikes - 1
WHERE id = old.post;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER comments_trigger
AFTER INSERT ON comments
FOR EACH ROW
BEGIN
UPDATE posts 
SET ncomments = ncomments + 1
WHERE id = new.post;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER posts_trigger
AFTER INSERT ON posts
FOR EACH ROW
BEGIN
UPDATE users 
SET numeroPost = numeroPost + 1
WHERE id = new.user;
END //
DELIMITER ;