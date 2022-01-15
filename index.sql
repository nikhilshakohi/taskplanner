/*Create a Database*/
CREATE DATABASE taskManager;

/*User Table*/
CREATE TABLE users (
	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(256) NOT NULL,
	password VARCHAR(256) NOT NULL,
	gender VARCHAR(256) NOT NULL,
	name VARCHAR(256) NOT NULL,
	email VARCHAR(256) NOT NULL
);

/*Tasks Table*/
CREATE TABLE tasks (
	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(256) NOT NULL,
	task MEDIUMTEXT NOT NULL,
	startTime TIME NOT NULL,
	endTime TIME NOT NULL,
	startDate DATE NOT NULL,
	endDate DATE NOT NULL,
	repeater VARCHAR(256) NOT NULL,
	priority VARCHAR(256) NOT NULL,
	completionStatus VARCHAR(256) NOT NULL DEFAULT '0',
	completionDates LONGTEXT NOT NULL DEFAULT '',
	details LONGTEXT NOT NULL
);
