# Super Market Checkout

## Installation

* Create DB, DB user, DB permissions by executing commands mentioned in supermarket.sql file
* Create DB tables with dummy data by executing: php dbseed.php
* Front Controller is public/index.html
* Controller is Src\Controller\SuperMarketController.php
* SuperMarketController->checkout() checks out items from supermarket
* DB Connector is Src\System\DatabaseConnector.php
* ORM Gateway Model is Src\TableGateways\SuperMarketGateway.php
* bootstrap.php is for loading environment variables using DotEnv and creating a db connection as well
* DB configuration is in .env file


## APIs

* GET /
    * Checks out items with quantity from supermarket 

## Test Cases

* Validates checkout method of SuperMarketGateway class