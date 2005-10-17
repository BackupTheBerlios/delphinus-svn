CREATE TABLE comments ( id INTEGER NOT NULL PRIMARY KEY, entry_id INTEGER NOT NULL, name VARCHAR , timestamp TEXT , comment VARCHAR , ip VARCHAR , ua VARCHAR , email VARCHAR , url VARCHAR );
CREATE TABLE entries ( id INTEGER NOT NULL PRIMARY KEY, rss_id INTEGER , title VARCHAR , date TIMESTAMP , description VARCHAR , link VARCHAR );
CREATE TABLE rss_list ( id INTEGER NOT NULL PRIMARY KEY, name VARCHAR , url VARCHAR , author VARCHAR , condition VARCHAR );

