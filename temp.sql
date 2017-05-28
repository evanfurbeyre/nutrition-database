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
	dFat FLOAT(7,2),
	dSatFat FLOAT(7,2),
	dCarb FLOAT(7,2),
	dSug FLOAT(7,2),
	dProt FLOAT(7,2),
	dSod FLOAT(7,2),
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
	fFat FLOAT(7,2),
	fSatFat FLOAT(7,2),
	fCarb FLOAT(7,2),
	fSug FLOAT(7,2),
	fProt FLOAT(7,2),
	fSod FLOAT(7,2),
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

INSERT INTO prep (pName, pEffort) VALUES ("Cutting Board", 1);
INSERT INTO prep (pName, pEffort) VALUES ("Plate", 1);
INSERT INTO prep (pName, pEffort) VALUES ("Knife", 1);
INSERT INTO prep (pName, pEffort) VALUES ("Spoon", 1);
INSERT INTO prep (pName, pEffort) VALUES ("Cut Vegetables", 2);
INSERT INTO prep (pName, pEffort) VALUES ("Finger Food", 1);
INSERT INTO prep (pName, pEffort) VALUES ("Frying Pan", 3);
INSERT INTO prep (pName, pEffort) VALUES ("Pot", 3);
INSERT INTO prep (pName, pEffort) VALUES ("Stove", 3);

INSERT INTO clean (cName, cEffort) VALUES ("Fork", 1);
INSERT INTO clean (cName, cEffort) VALUES ("Knife", 1);
INSERT INTO clean (cName, cEffort) VALUES ("Spoon", 1);
INSERT INTO clean (cName, cEffort) VALUES ("Plate", 1);
INSERT INTO clean (cName, cEffort) VALUES ("Cutting Board", 2);
INSERT INTO clean (cName, cEffort) VALUES ("Frying Pan", 3);
INSERT INTO clean (cName, cEffort) VALUES ("Spatula", 3);
INSERT INTO clean (cName, cEffort) VALUES ("Wipe Counter", 1);
INSERT INTO clean (cName, cEffort) VALUES ("French Press", 5);

INSERT INTO food (fName, fCost, fCal, fWeight, fFat, fSatFat, fCarb, fSug, fProt,  fSod, fText) VALUES ("Banana", .22, 105, 118, .4, .13, 27, 14.4, 1.3, .00118, "Medium, Peeled");
INSERT INTO food (fName, fCost, fCal, fWeight, fFat, fSatFat, fCarb, fSug, fProt,  fSod, fText) VALUES ("Granola", .20, 100, 23, 3, 1, 17, 5, 1, .090, "Sprouts - 1/4 cup with chocolate nugs");
INSERT INTO food (fName, fCost, fCal, fWeight, fFat, fSatFat, fCarb, fSug, fProt,  fSod, fText) VALUES ("Honey", .16, 30, 10.5, 0, 0, 8.5, 8.5, 0, 0, ".5 Tbsp of WF365 organic");
INSERT INTO food (fName, fCost, fCal, fWeight, fFat, fSatFat, fCarb, fSug, fProt,  fSod, fText) VALUES ("Bread", .16, 60, 26, .5, 0, 11, 1, 4, .110, "Nature's Own 100% Whole Wheat");
INSERT INTO food (fName, fCost, fCal, fWeight, fFat, fSatFat, fCarb, fSug, fProt,  fSod, fText) VALUES ("Almond Butter", .36, 95, 16, 8.5, 1.25, 3.5, 1.5, 3, .065, "MaraNatha No Stir - 1 Tbsp");
INSERT INTO food (fName, fCost, fCal, fWeight, fFat, fSatFat, fCarb, fSug, fProt,  fSod, fText) VALUES ("Yogurt", 1.00, 220, 227, 9, 5, 27, 25, 8, .115, "Medium, Peeled");

INSERT INTO food (fName, fCost, fCal, fWeight, fFat, fSatFat, fCarb, fSug, fProt, fSod, fText) VALUES ("Mayonaisse", .13, 110, 14, 12, 1.5, 0, 0, 0, .095, "Organics brand");
INSERT INTO food (fName, fCost, fCal, fWeight, fFat, fSatFat, fCarb, fSug, fProt, fSod, fText) VALUES ("Horse-Radish", .05, 5, 5, 0, 0, 0, 0, 0, .060, "Sprouts");
INSERT INTO food (fName, fCost, fCal, fWeight, fFat, fSatFat, fCarb, fSug, fProt, fSod, fText) VALUES ("Sweet Corn Salsa", .21, 35, 32, 0, 0, 8, 5, .3, .1, "Sprouts");
INSERT INTO food (fName, fCost, fCal, fWeight, fFat, fSatFat, fCarb, fSug, fProt, fSod, fText) VALUES ("Ham", .75, 70, 56, 1, 0, 2, 1, 12, .410, "Trader Joe's Uncured Black Forest");
INSERT INTO food (fName, fCost, fCal, fWeight, fFat, fSatFat, fCarb, fSug, fProt, fSod, fText) VALUES ("Tortilla Chips", .4, 140, 28, 7, 1, 17, 0, 2, .1, "Sprouts Extra Thick");
