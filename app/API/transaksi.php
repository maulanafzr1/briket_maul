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
    $app->get('/transaksi', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('CALL readTransaksi()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });


    // get by id
    $app->get('/transaksi/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare('SELECT * FROM transaksi WHERE id = ?');
        $query->execute([$args['id']]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results[0]));

        return $response->withHeader("Content-Type", "application/json");
    });

    
    // post data
    $app->post('/transaksi', function (Request $request, Response $response) {

        $jsonBody = $request->getParsedBody();
    
        $tanggalTransaksi = $jsonBody["tanggal_transaksi"];
        $idPembeli = $jsonBody["id_pembeli"];
        $total = $jsonBody["total"];
    
        $db = $this->get(PDO::class);
    
        $query = $db->prepare('INSERT INTO transaksi (tanggal_transaksi, id_pembeli, total) VALUES (?, ?, ?)');
        $query->execute([$tanggalTransaksi, $idPembeli, $total]);
    
        $lastId = $db->lastInsertId();
    
        $response->getBody()->write(json_encode([
            'message' => 'Transaksi disimpan dengan ID ' . $lastId
        ]));
    
        return $response->withHeader("Content-Type", "application/json")->withStatus(201); // Status 201 menunjukkan pembuatan berhasil
    });

    

    // put data
  
  
    // delete data

    $app->delete('/transaksi/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('call deleteTransaksi(?)');
            $query->execute([$currentId]);
    
            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode([
                    'message' => 'Data transaksi tidak ditemukan'
                ]));
            } else {
                $response->getBody()->write(json_encode([
                    'message' => 'Transaksi dengan ID ' . $currentId . ' telah dihapus dari database'
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
