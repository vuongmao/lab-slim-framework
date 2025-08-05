<?php

namespace App\Controller\Album;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controller\Controller;

class SearchController extends Controller {

  public function default(Request $request, Response $response) {
    $albumJson = file_get_contents(DATA_DIR . '/albums.json');
    $albums = json_decode($albumJson, true);
    return $this->render($response, 'album/default', ['albums' => $albums]);
  }

  public function search(Request $request, Response $response) {
      $albumJson = file_get_contents(DATA_DIR . '/albums.json');
      $albums = json_decode($albumJson, true);

      $query = $request->getQueryParams()['q'] ?? '';

      if($query) {
        $albums = array_values(array_filter($albums, function($album) use ($query) {
          return strpos($album['title'], $query) !== false || strpos($album['artist'], $query) !== false;          
        }));
      }

      return $this->render($response, 'album/search', array(
        'albums' => $albums,
        'query' => $query
      ));

  }

  public function form(Request $request, Response $response) {
      $albumJson = file_get_contents(DATA_DIR . '/albums.json');
      $albums = json_decode($albumJson, true);

      $query = $request->getParsedBody()['q'] ?? '';
      print_r($query); 

      if($query) {
        $albums = array_values(array_filter($albums, function($album) use ($query) {
          return strpos($album['title'], $query) !== false || strpos($album['artist'], $query) !== false;          
        }));
      }

      return $this->render($response, 'album/form', array(
        'albums' => $albums,
        'query' => $query
      ));

  }
}