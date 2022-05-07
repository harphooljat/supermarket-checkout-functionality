CREATE DATABASE supermarket CHARACTER SET utf8 COLLATE utf8_unicode_ci;
CREATE USER 'sm_user'@'localhost' identified by 'sm_password';
GRANT ALL on supermarket.* to 'sm_user'@'localhost';