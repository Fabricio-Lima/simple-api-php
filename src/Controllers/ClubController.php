<?php
namespace Src\Controllers;
use Src\Gateways\ClubGateway;

class ClubController {

    private $db;
    private $requestMethod;
    private $requestResource;
    private $clubGateway;

    public function __construct($db, $requestMethod, $requestResource)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->requestResource = $requestResource;
        $this->clubGateway = new ClubGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->getAllClubs();
                break;
            case 'POST':
                if ($this->requestResource)
                    $response = $this->useResource();
                else 
                    $response = $this->createClubFromRequest();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllClubs()
    {
        $result = $this->clubGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createClubFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        if (!isset($input['clube']) || !isset($input['saldo_disponivel'])) {
            $response = array(
                'status_code_header' => 'HTTP/1.1 400',
                'body' => json_encode(array('error' => 'Verifique se todos os campos estão preechidos.')),
            );
            return $response;
        }

        $this->clubGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = "OK";
        return $response;
    }

    private function useResource()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);

        if (!isset($input['clube_id']) || !isset($input['recurso_id']) || !isset($input['valor_consumo'])) {
            $response = array(
                'status_code_header' => 'HTTP/1.1 400',
                'body' => json_encode(array('error' => 'Verifique se todos os campos estão preechidos.')),
            );
            return $response;
        }

        $result = $this->clubGateway->useResource($input);

        if ($result['status'] == false) {
            $statusCode = 400;
        } else {
            $statusCode = 200;
        }
        
        $response = array(
            'status_code_header' => "HTTP/1.1 $statusCode",
            'body' => json_encode($result)
        );

        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}
