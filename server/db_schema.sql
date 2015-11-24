CREATE TABLE venue (
  name VARCHAR(64) NOT NULL PRIMARY KEY
);

-- super type of course - in a way a course catagory
CREATE TABLE supercourse (
  id VARCHAR(24) NOT NULL PRIMARY KEY CHECK (id ~* '^[A-Za-z0-9_-]+$'),
  title VARCHAR(64) NOT NULL,
  summery VARCHAR(512) NOT NULL,
  displayorder INT NOT NULL
);

CREATE TYPE coursetype AS ENUM ('schoolholiday', 'afterschool');

CREATE TABLE course (
  id SERIAL NOT NULL PRIMARY KEY,
  supercourse VARCHAR(24) NOT NULL,
  type coursetype NOT NULL DEFAULT 'schoolholiday',
  title VARCHAR(64) NOT NULL,
  shortdescription VARCHAR(256) NOT NULL,
  description VARCHAR(1024) NOT NULL,
  minage SMALLINT NOT NULL DEFAULT 5 CHECK(minage >= 5),
  maxage SMALLINT NOT NULL DEFAULT 18 CHECK(maxage <= 18 AND maxage >= minage),
  price DECIMAL(5,2) NOT NULL CHECK(price > 0),
  displayorder INT NOT NULL,
  FOREIGN KEY (supercourse)
    REFERENCES supercourse (id)
    MATCH FULL
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE faq (
  id SERIAL NOT NULL PRIMARY KEY,
  question VARCHAR(128) NOT NULL,
  answer VARCHAR(1024) NOT NULL,
  displayorder INT NOT NULL
);

-- the dates that courses are available to book
CREATE TABLE coursedate (
  id SERIAL NOT NULL PRIMARY KEY,
  course INT NOT NULL,
  venue VARCHAR(64) NOT NULL,
  week date NOT NULL CHECK(EXTRACT(DOW FROM week) = 1), -- the week must starts on a monday
  days BIT(7) NOT NULL DEFAULT B'1111100',
  "full" BOOLEAN NOT NULL DEFAULT FALSE,
  UNIQUE (course, venue, week, days),
  FOREIGN KEY (course)
    REFERENCES course (id)
    MATCH FULL
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY (venue)
    REFERENCES venue (name)
    MATCH FULL
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

CREATE TABLE booking (
  id SERIAL NOT NULL PRIMARY KEY,
  course INT NOT NULL,
  coursedate INT NOT NULL,
  FOREIGN KEY (course)
    REFERENCES course (id)
    MATCH FULL
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  FOREIGN KEY (coursedate)
    REFERENCES coursedate (id)
    MATCH FULL
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);

CREATE TABLE address (
  id SERIAL NOT NULL PRIMARY KEY,
  streetnum VARCHAR(64) NOT NULL,
  street VARCHAR(64) NOT NULL,
  suburb VARCHAR(64),
  city VARCHAR(64) NOT NULL
);

CREATE TABLE attendee (
  id SERIAL NOT NULL PRIMARY KEY,
  booking INT NOT NULL,
  name VARCHAR(64) NOT NULL CHECK(LENGTH(name) > 2),
  age SMALLINT NOT NULL CHECK(age >= 5 AND age <=18),
  FOREIGN KEY (booking)
    REFERENCES booking (id)
    MATCH FULL
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE parent (
  id SERIAL NOT NULL PRIMARY KEY,
  booking INT NOT NULL,
  name VARCHAR(64) NOT NULL CHECK(LENGTH(name) > 2),
  address INT,
  phonehm VARCHAR(10),
  phonewk VARCHAR(10),
  phonemb VARCHAR(10),
  email VARCHAR(256) NOT NULL,
  FOREIGN KEY (booking)
    REFERENCES booking (id)
    MATCH FULL
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (address)
    REFERENCES address (id)
    MATCH FULL
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
