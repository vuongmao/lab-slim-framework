<?php
namespace App\Controller\Bike;

use App\Controller\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends Controller {
  public function login(Request $request, Response $response) {
    $session = $this->ci->get('session');
    if($request->getMethod() == 'POST') {
      $session->set('username', $request->getParsedBody()['username']);
      return $response->withHeader('Location', '/bike/blog')->withStatus(301);
    }
    
    return $this->render($response, 'bike/login');
  }

  public function blog(Request $request, Response $response) {
    $session = $this->ci->get('session');
    return $this->render($response, 'bike/blog', array('username' => $session->get('username')));
  }

  public function logout(Request $request, Response $response) {
    $session = $this->ci->get('session');
    $session->delete('username');
    return $response->withHeader('Location', '/bike/blog')->withStatus(301);
  }
}