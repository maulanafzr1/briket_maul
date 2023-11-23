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
    $app->get('/briket', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL readBriket()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    // get by id
    $app->get('/briket/{id}', function (Request $request, Response $response, $args) {
        $pdo = $this->get(PDO::class);
        $statement = $pdo->prepare("CALL GetBriketByID(:briket_id)");
        $statement->bindParam(":briket_id", $args['id'], PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    
        if (count($results) > 0) {
            $response->getBody()->write(json_encode($results[0]));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Briket not found']));
            $response = $response->withStatus(404);
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });

    // post data
    $app->post('/briket', function (Request $request, Response $response) {
      
        $jsonBody = $request->getParsedBody();
    
        $namaBriket = $jsonBody["nama_briket"];
        $stok = $jsonBody["stok"];
        $hargaSatuan = $jsonBody["harga_satuan"];
  
        $db = $this->get(PDO::class);
        
        $query = $db->prepare('INSERT INTO briket (nama_briket, stok, harga_satuan) VALUES (?, ?, ?)');
        $query->execute([$namaBriket, $stok, $hargaSatuan]);
    
        $lastId = $db->lastInsertId();

        $response->getBody()->write(json_encode([
            'message' => 'Briket disimpan dengan ID ' . $lastId
        ]));
    
        return $response->withHeader("Content-Type", "application/json")->withStatus(201); // Status 201 menunjukkan pembuatan berhasil
    });


    // put data
    $app->put('/briket/{id}', function (Request $request, Response $response, $args) {

        $currentId = $args['id'];
        
   
        $parsedBody = $request->getParsedBody();
        $newNama = $parsedBody["nama_briket"];
        $newStok = $parsedBody["stok"];
        $newHargaSatuan = $parsedBody["harga_satuan"];
        
      
        $db = $this->get(PDO::class);
        
        
        $query = $db->prepare('CALL updateBriket(?, ?, ?, ?)');
        $query->execute([$currentId, $newNama, $newStok, $newHargaSatuan]);
        
        $response->getBody()->write(json_encode([
            'message' => 'Briket dengan ID ' . $currentId . ' telah diperbarui.'
        ]));
        
        return $response->withHeader("Content-Type", "application/json");
    });

    // delete data
    $app->delete('/briket/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('call deleteBriket(?)');
            $query->execute([$currentId]);
    
            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode([
                    'message' => 'Data tidak ditemukan'
                ]));
            } else {
                $response->getBody()->write(json_encode([
                    'message' => 'Briket dengan ID ' . $currentId . ' telah dihapus dari database'
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
