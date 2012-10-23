CREATE TABLE sociallogin (
       user_id INT(10) UNSIGNED NOT NULL,
       profile VARCHAR(256) NOT NULL,
       full_name VARCHAR(256) NOT NULL
);

ALTER TABLE sociallogin ADD CONSTRAINT FOREIGN KEY (user_id) REFERENCES user (user_id) ON DELETE CASCADE;
