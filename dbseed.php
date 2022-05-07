<?php

use Src\System\DatabaseConnector;

require 'bootstrap.php';

$statement = <<<EOS
    CREATE TABLE IF NOT EXISTS item_price (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        item_name VARCHAR(100) NOT NULL,
        unit_price INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),
        KEY item_name (item_name)
    ) ENGINE=INNODB;

    INSERT INTO item_price
        (id, item_name, unit_price)
    VALUES
        (1, 'A', 50),
        (2, 'B', 30),
        (3, 'C', 20),
        (4, 'D', 15),
        (5, 'E', 5);

    CREATE TABLE IF NOT EXISTS special_price_on_quantity (
        item_id INT UNSIGNED NOT NULL,
        quantity MEDIUMINT UNSIGNED NOT NULL,
        total_price INT UNSIGNED NOT NULL,
        description VARCHAR(100) NOT NULL,
        KEY (item_id)
    ) ENGINE=INNODB;
    
    INSERT INTO special_price_on_quantity
        (item_id, quantity, total_price, description)
    VALUES
        (1, 3, 130, '3 for 130'),
        (2, 2, 45, '2 for 45'),
        (3, 2, 38, '2 for 38'),
        (3, 3, 50, '3 for 50');

    CREATE TABLE IF NOT EXISTS special_price_on_buying_together (
        item_id INT UNSIGNED NOT NULL,
        pre_bought_item_id INT UNSIGNED NOT NULL,
        unit_price INT UNSIGNED NOT NULL,
        description VARCHAR(100) NOT NULL,
        KEY (item_id)
    ) ENGINE=INNODB;

    INSERT INTO special_price_on_buying_together (item_id, pre_bought_item_id, unit_price, description)
    VALUES (4, 1, 5, '5 if purchased with a A');

EOS;

try {
    $createTable = (new DatabaseConnector())->getConnection()->exec($statement);
    echo "Success!\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}