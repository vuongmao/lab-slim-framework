<?php
namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;

class ExceptionController extends Controller {
  public function notFound(Request $request) {
    $reponse = new Response();
    return $this->render($reponse, '/bike/404');
  }
}