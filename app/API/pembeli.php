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
    $app->get('/pembeli', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL readPembeli()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    // get by id
    $app->get('/pembeli/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
    
        $query = $db->prepare('CALL GetPembeliByID(?)');
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
 
    $app->post('/pembeli', function (Request $request, Response $response) {

        $jsonBody = $request->getParsedBody();
    
        $namaPembeli = $jsonBody["nama_pembeli"];
        $alamatPembeli = $jsonBody["alamat_pembeli"];
        $kontakPembeli = $jsonBody["kontak_pembeli"];
    
        $db = $this->get(PDO::class);
    
        $query = $db->prepare('INSERT INTO pembeli (nama_pembeli, alamat_pembeli, kontak_pembeli) VALUES (?, ?, ?)');
        $query->execute([$namaPembeli, $alamatPembeli, $kontakPembeli]);
    
        $lastId = $db->lastInsertId();
    
        $response->getBody()->write(json_encode([
            'message' => 'Pembeli disimpan dengan ID ' . $lastId
        ]));
    
        return $response->withHeader("Content-Type", "application/json")->withStatus(201); // Status 201 menunjukkan pembuatan berhasil
    });


    // put data
    $app->put('/pembeli/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        
        $parsedBody = $request->getParsedBody();
        $newNama = $parsedBody["nama_pembeli"];
        $newAlamat = $parsedBody["alamat_pembeli"];
        $newKontak = $parsedBody["kontak_pembeli"];
        
        $db = $this->get(PDO::class);
        
        $query = $db->prepare('CALL updatePembeli(?, ?, ?, ?)');
        $query->execute([$currentId, $newNama, $newAlamat, $newKontak]);
        
        $response->getBody()->write(json_encode([
            'message' => 'Pembeli dengan ID ' . $currentId . ' telah diperbarui.'
        ]));
        
        return $response->withHeader("Content-Type", "application/json");
    });
  
    // delete data
    $app->delete('/pembeli/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('call deletePembeli(?)');
            $query->execute([$currentId]);
    
            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode([
                    'message' => 'Data pembeli tidak ditemukan'
                ]));
            } else {
                $response->getBody()->write(json_encode([
                    'message' => 'Pembeli dengan ID ' . $currentId . ' telah dihapus dari database'
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
