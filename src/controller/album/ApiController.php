<?php

namespace App\Controller\Album;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controller\Controller;

class ApiController extends Controller {

  public function api(Request $request, Response $response) {
      $albumJson = file_get_contents(DATA_DIR . '/albums.json');
      $albums = json_decode($albumJson, true);

      $query = $request->getQueryParams()['q'] ?? '';

      if($query == '') {
        $response->getBody()->write(json_encode(['error' => 'invalid request']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
      }

      if($query) {
        $albums = array_values(array_filter($albums, function($album) use ($query) {
          return strpos($album['title'], $query) !== false || strpos($album['artist'], $query) !== false;          
        }));
      }

      $payload = json_encode($albums);
      $response->getBody()->write($payload);

      return $response->withHeader('Content-Type', 'application/json');
  }
}