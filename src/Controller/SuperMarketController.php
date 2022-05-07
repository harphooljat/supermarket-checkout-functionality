<?php
namespace Src\Controller;

use Src\TableGateways\SuperMarketGateway;

class SuperMarketController {

    private $superMarketGateway;

    public function __construct($db)
    {
        $this->superMarketGateway = new SuperMarketGateway($db);
    }

    public function checkout()
    {
        $input = (array) json_decode(file_get_contents('php://input'), true);
        try {
            $result = $this->superMarketGateway->checkout($input);
        } catch (\Exception $e) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        header($response['status_code_header']);
        echo $response['body'];
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}