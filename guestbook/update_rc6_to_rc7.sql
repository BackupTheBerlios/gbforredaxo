ALTER TABLE rex_9_gbook ADD COLUMN status tinyint(1) NOT NULL default '1' after id;
ALTER TABLE rex_9_gbook ADD COLUMN city varchar(255) NOT NULL default '' after email;
