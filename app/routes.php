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

    $app->get('/detail_transaksi', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('call readDetailTransaksi()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/pabrik', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('call readPabrik()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/pembeli', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('call readPembeli()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/transaksi', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        $query = $db->query('call readTransaksi()');
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results));

        return $response->withHeader("Content-Type", "application/json");
    });

    // get by id
    $app->get('/pabrik/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare('SELECT * FROM pabrik WHERE id = ?');
        $query->execute([$args['id']]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results[0]));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/briket/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare('SELECT * FROM briket WHERE id = ?');
        $query->execute([$args['id']]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results[0]));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/detail_transaksi/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare('SELECT * FROM detai9l_transaksi WHERE id = ?');
        $query->execute([$args['id']]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results[0]));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/pembeli/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare('SELECT * FROM pembeli WHERE id = ?');
        $query->execute([$args['id']]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results[0]));

        return $response->withHeader("Content-Type", "application/json");
    });

    $app->get('/transaksi/{id}', function (Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);

        $query = $db->prepare('SELECT * FROM transaksi WHERE id = ?');
        $query->execute([$args['id']]);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($results[0]));

        return $response->withHeader("Content-Type", "application/json");
    });

    // post data
    // $app->post('/countries', function (Request $request, Response $response) {
    //     $parsedBody = $request->getParsedBody();

    //     $id = $parsedBody["id"]; // menambah dengan kolom baru
    //     $countryName = $parsedBody["name"];

    //     $db = $this->get(PDO::class);

    //     $query = $db->prepare('INSERT INTO countries (id, name) values (?, ?)');

    //     // urutan harus sesuai dengan values
    //     $query->execute([$id, $countryName]);

    //     $lastId = $db->lastInsertId();

    //     $response->getBody()->write(json_encode(
    //         [
    //             'message' => 'country disimpan dengan id ' . $lastId
    //         ]
    //     ));

    //     return $response->withHeader("Content-Type", "application/json");
    // });

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

    $app->post('/detail_transaksi', function (Request $request, Response $response) {

        $jsonBody = $request->getParsedBody();

        $idTransaksi = $jsonBody["id_transaksi"];
        $idBriket = $jsonBody["id_briket"];
        $jumlah = $jsonBody["jumlah"];
        $subtotal = $jsonBody["subtotal"];
    
        $db = $this->get(PDO::class);

        $query = $db->prepare('INSERT INTO detail_transaksi (id_transaksi, id_briket, jumlah, subtotal) VALUES (?, ?, ?, ?)');
        $query->execute([$idTransaksi, $idBriket, $jumlah, $subtotal]);
    
        $lastId = $db->lastInsertId();
    
        $response->getBody()->write(json_encode([
            'message' => 'Detail Transaksi disimpan dengan ID ' . $lastId
        ]));
    
        return $response->withHeader("Content-Type", "application/json")->withStatus(201); // Status 201 menunjukkan pembuatan berhasil
    });
    
    

    // put data
    // $app->put('/countries/{id}', function (Request $request, Response $response, $args) {
    //     $parsedBody = $request->getParsedBody();

    //     $currentId = $args['id'];
    //     $countryName = $parsedBody["name"];
    //     $db = $this->get(PDO::class);

    //     $query = $db->prepare('UPDATE countries SET name = ? WHERE id = ?');
    //     $query->execute([$countryName, $currentId]);

    //     $response->getBody()->write(json_encode(
    //         [
    //             'message' => 'country dengan id ' . $currentId . ' telah diupdate dengan nama ' . $countryName
    //         ]
    //     ));

    //     return $response->withHeader("Content-Type", "application/json");
    // });

    $app->put('/pabrik/update/{id}', function (Request $request, Response $response, $args) {
       //
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

    $app->put('/briket/update/{id}', function (Request $request, Response $response, $args) {

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

    $app->put('/pembeli/update/{id}', function (Request $request, Response $response, $args) {
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
    
    $app->put('/transaksi/update/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        
        
        $parsedBody = $request->getParsedBody();
        $newTanggal = $parsedBody["tanggal_transaksi"];
        $newTotal = $parsedBody["total"];
        
        $db = $this->get(PDO::class);
        
        
        $query = $db->prepare('CALL updateTransaksi(?, ?, ?)');
        $query->execute([$currentId, $newTanggal, $newTotal]);
        
        $response->getBody()->write(json_encode([
            'message' => 'Transaksi dengan ID ' . $currentId . ' telah diperbarui.'
        ]));
        
        return $response->withHeader("Content-Type", "application/json");
    });

    $app->put('/detail_transaksi/update/{id}', function (Request $request, Response $response, $args) {

        $currentId = $args['id'];
        
        //
        $parsedBody = $request->getParsedBody();
        $newIdTransaksi = $parsedBody["id_transaksi"];
        $newIdBriket = $parsedBody["id_briket"];
        $newJumlah = $parsedBody["jumlah"];
        $newSubtotal = $parsedBody["subtotal"];
        
        //
        $db = $this->get(PDO::class);
        
        //
        $query = $db->prepare('CALL updateDetailTransaksi(?, ?, ?, ?, ?)');
        $query->execute([$currentId, $newIdTransaksi, $newIdBriket, $newJumlah, $newSubtotal]);
        
        $response->getBody()->write(json_encode([
            'message' => 'Detail Transaksi dengan ID ' . $currentId . ' telah diperbarui.'
        ]));
        
        return $response->withHeader("Content-Type", "application/json");
    });
    
    
    

    // delete data
    // $app->delete('/countries/{id}', function (Request $request, Response $response, $args) {
    //     $currentId = $args['id'];
    //     $db = $this->get(PDO::class);

    //     try {
    //         $query = $db->prepare('DELETE FROM countries WHERE id = ?');
    //         $query->execute([$currentId]);

    //         if ($query->rowCount() === 0) {
    //             $response = $response->withStatus(404);
    //             $response->getBody()->write(json_encode(
    //                 [
    //                     'message' => 'Data tidak ditemukan'
    //                 ]
    //             ));
    //         } else {
    //             $response->getBody()->write(json_encode(
    //                 [
    //                     'message' => 'country dengan id ' . $currentId . ' dihapus dari database'
    //                 ]
    //             ));
    //         }
    //     } catch (PDOException $e) {
    //         $response = $response->withStatus(500);
    //         $response->getBody()->write(json_encode(
    //             [
    //                 'message' => 'Database error ' . $e->getMessage()
    //             ]
    //         ));
    //     }

    //     return $response->withHeader("Content-Type", "application/json");
    // });


    $app->delete('/pabrik/delete/{id}', function (Request $request, Response $response, $args) {
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

    $app->delete('/briket/delete/{id}', function (Request $request, Response $response, $args) {
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

    $app->delete('/pembeli/delete/{id}', function (Request $request, Response $response, $args) {
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

    $app->delete('/transaksi/delete/{id}', function (Request $request, Response $response, $args) {
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
    

    $app->delete('/detail_transaksi/delete/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('call deleteDetailTransaksi(?)');
            $query->execute([$currentId]);
    
            if ($query->rowCount() === 0) {
                $response = $response->withStatus(404);
                $response->getBody()->write(json_encode([
                    'message' => 'Data detail_transaksi tidak ditemukan'
                ]));
            } else {
                $response->getBody()->write(json_encode([
                    'message' => 'Detail Transaksi dengan ID ' . $currentId . ' telah dihapus dari database'
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
