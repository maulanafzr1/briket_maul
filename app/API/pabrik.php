<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {

    // get
    $app->get('/pabrik', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL readPabrik()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    // get by id
    $app->get('/pabrik/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
    
        $query = $db->prepare('CALL ReadPabrikByID(?)');
        $query->bindParam(1, $args['id'], PDO::PARAM_INT);
        $query->execute();
    
        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) > 0) {
            $response->getBody()->write(json_encode($results[0]));
        } else {

            $response->getBody()->write(json_encode(['message' => 'Data not found']));
            $response = $response->withStatus(404);
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });


    // post data
    $app->post('/pabrik', function (Request $request, Response $response) {
    
        $jsonBody = $request->getParsedBody();
    
        $namaPabrik = $jsonBody["nama_pabrik"];
        $alamatPabrik = $jsonBody["alamat_pabrik"];
        $kontakPabrik = $jsonBody["kontak_pabrik"];
        
        $db = $this->get(PDO::class);
     
        $query = $db->prepare('INSERT INTO pabrik (nama_pabrik, alamat_pabrik, kontak_pabrik) VALUES (?, ?, ?)');
        $query->execute([$namaPabrik, $alamatPabrik, $kontakPabrik]);
    
        
        $lastId = $db->lastInsertId();
    
        
        $response->getBody()->write(json_encode([
            'message' => 'Pabrik disimpan dengan ID ' . $lastId
        ]));
    
        return $response->withHeader("Content-Type", "application/json")->withStatus(201); // Status 201 menunjukkan pembuatan berhasil
    });


    // put data
  
    $app->put('/pabrik/{id}', function (Request $request, Response $response, $args) {
       
        $currentId = $args['id'];
        

        $parsedBody = $request->getParsedBody();
        $newNama = $parsedBody["nama_pabrik"];
        $newAlamat = $parsedBody["alamat_pabrik"];
        $newKontak = $parsedBody["kontak_pabrik"];
        
        
        $db = $this->get(PDO::class);
        
       
        $query = $db->prepare('CALL updatePabrik(?, ?, ?, ?)');
        $query->execute([$currentId, $newNama, $newAlamat, $newKontak]);
        
        $response->getBody()->write(json_encode([
            'message' => 'Pabrik dengan ID ' . $currentId . ' telah diperbarui.'
        ]));
        
        return $response->withHeader("Content-Type", "application/json");
    });
 
    // delete data

    $app->delete('/pabrik/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL deletePabrik(?)');
            $query->execute([$currentId]);
    
            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode([
                    'message' => 'Data tidak ditemukan'
                ]));
            } else {
                $response->getBody()->write(json_encode([
                    'message' => 'Pabrik dengan ID ' . $currentId . ' telah dihapus dari database'
                ]));
            }
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode([
                'message' => 'Database error ' . $e->getMessage()
            ]));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });      
};
