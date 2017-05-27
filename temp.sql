DROP TABLE IF EXISTS dish;
DROP TABLE IF EXISTS food;
DROP TABLE IF EXISTS clean;
DROP TABLE IF EXISTS prep;
DROP TABLE IF EXISTS dish_food;
DROP TABLE IF EXISTS dish_clean;
DROP TABLE IF EXISTS dish_prep;

CREATE TABLE dish (
	dId SMALLINT(5) AUTO_INCREMENT,
	dName VARCHAR(255) NOT NULL,
	dCost FLOAT(5,2),
	dCal FLOAT (7,2),
	dEffort INT(11),
	dText TEXT,
	PRIMARY KEY (dId),
	UNIQUE (dName)
)ENGINE=InnoDB DEFAULT CHARSET=utf8; 

CREATE TABLE food (
	fId SMALLINT(5) AUTO_INCREMENT,
	fName VARCHAR(255) NOT NULL,
	fCost FLOAT(5,2),
	fCal FLOAT (7,2),
	fWeight FLOAT(7,2),
	fText TEXT,
	PRIMARY KEY (fId),
	UNIQUE (fName)
)ENGINE=InnoDB DEFAULT CHARSET=utf8; 

CREATE TABLE prep (
	pId SMALLINT(5) AUTO_INCREMENT,
	pName VARCHAR(255) NOT NULL,
	pEffort SMALLINT(5),
	pNotes TEXT,
	PRIMARY KEY (pId),
	UNIQUE (pName)
)ENGINE=InnoDB DEFAULT CHARSET=utf8; 

CREATE TABLE clean (
	cId SMALLINT(5) AUTO_INCREMENT,
	cName VARCHAR(255) NOT NULL,
	cEffort SMALLINT(5),
	cNotes TEXT,
	PRIMARY KEY (cId),
	UNIQUE (cName)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE dish_food (
	did SMALLINT(5),
	fid SMALLINT(5),
	dfWeight INT(11),
	PRIMARY KEY (did,fid),
	FOREIGN KEY (did) REFERENCES dish (dId) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (fid) REFERENCES food (fId) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE dish_prep (
	did SMALLINT(5),
	pid SMALLINT(5),
	PRIMARY KEY (did,pid),
	FOREIGN KEY (did) REFERENCES dish (dId) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (pid) REFERENCES prep (pId) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE dish_clean (
	did SMALLINT(5),
	cid SMALLINT(5),
	PRIMARY KEY (did,cid),
	FOREIGN KEY (did) REFERENCES dish (dId) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (cid) REFERENCES clean (cId) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO prep (pName, pEffort) VALUES ("None", 0);
INSERT INTO prep (pName, pEffort) VALUES ("Cutting Board", 1);
INSERT INTO prep (pName, pEffort) VALUES ("Knife", 1);
INSERT INTO prep (pName, pEffort) VALUES ("Spoon", 1);
INSERT INTO prep (pName, pEffort) VALUES ("Stove", 4);

INSERT INTO clean (cName, cEffort) VALUES ("None", 0);
INSERT INTO clean (cName, cEffort) VALUES ("Cutlery", 1);
INSERT INTO clean (cName, cEffort) VALUES ("Cutting Board", 2);
INSERT INTO clean (cName, cEffort) VALUES ("Frying Pan", 3);

INSERT INTO food (fName, fCost, fCal, fWeight) VALUES ("None", 0, 0, 0);
INSERT INTO food (fName, fCost, fCal, fWeight, fText) VALUES ("Banana", .22, 105, 116, "(weight is peeled)");
INSERT INTO food (fName, fCost, fCal, fWeight, fText) VALUES ("Bread", .16, 100, 26, "Nature's Own - Whole Wheat");
INSERT INTO food (fName, fCost, fCal, fWeight, fText) VALUES ("Honey", .22, 30, 10.5, "SueBee Clover Honey");
INSERT INTO food (fName, fCost, fCal, fWeight, fText) VALUES ("Granola", .09, 100, 11.11, "Just guessing on weight for now" );

INSERT INTO dish (dName, dText) VALUES ("Banana Sandwich", "A very yummy sandwich");

INSERT INTO dish_food (did, fid, numServs) VALUES ((SELECT dId FROM dish WHERE dName = "Banana Sandwich"), (SELECT fId FROM food WHERE fName = "Banana"), 1);
INSERT INTO dish_food (did, fid, numServs) VALUES ((SELECT dId FROM dish WHERE dName = "Banana Sandwich"), (SELECT fId FROM food WHERE fName = "Bread"), 2);
INSERT INTO dish_food (did, fid, numServs) VALUES ((SELECT dId FROM dish WHERE dName = "Banana Sandwich"), (SELECT fId FROM food WHERE fName = "Honey"), 1);
INSERT INTO dish_food (did, fid, numServs) VALUES ((SELECT dId FROM dish WHERE dName = "Banana Sandwich"), (SELECT fId FROM food WHERE fName = "Granola"), 1);

# We need to figure out how to add a dish all at once ( dish, cal, cost, effort, etc) Probably server side php

SELECT SUM(f.fCal) FROM dish d INNER JOIN dish_food df ON df.did = d.dId INNER JOIN food f ON f.fId = df.fid WHERE d.dName = "Banana Sandwich" GROUP BY d.dName



