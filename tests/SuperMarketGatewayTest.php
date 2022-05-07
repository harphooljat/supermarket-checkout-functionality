<?php

namespace Src\TableGateways;

use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class SuperMarketGatewayTest extends TestCase
{

    public function testCheckout()
    {
        $input = array('items' => array(
            array('name' => 'D', 'quantity' => 4 ),
            array('name' => 'A', 'quantity' => 5)
        ));
        require "../bootstrap.php";
        assertEquals(250, (new SuperMarketGateway($dbConnection))->checkout($input)['totalPrice']);
    }
}
