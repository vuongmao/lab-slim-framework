<?php

namespace App\Controller\Bike;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controller\Controller;
use Slim\Exception\HttpNotFoundException;

class ShopController extends Controller {

  public function default(Request $request, Response $response) {
    $bikeJson = file_get_contents(DATA_DIR . '/bikes.json');
    $bikes = json_decode($bikeJson, true);
    return $this->render($response, 'bike/default', ['bikes' => $bikes]);
  }

  public function detail(Request $request, Response $response, $args = []) {
    $bikeJson = file_get_contents(DATA_DIR . '/bikes.json');
    $bikes = json_decode($bikeJson, true);

    $indexKey = array_search($args['id'], array_column($bikes, 'id'));

    if($indexKey === false) {
      throw new HttpNotFoundException($request);
    }
    return $this->render($response, 'bike/detail', ['bike' => $bikes[$indexKey]]);
  } 
}