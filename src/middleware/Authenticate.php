<?php
namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class Authenticate {
  private $session;
  public function __construct($session) {
    $this->session = $session;
  }

  public function __invoke(Request $request, Handler $handler) {
    if($this->session->exists('username') == true) {
      return $handler->handle($request);
    }
    return $handler->handle($request)->withHeader('Location', '/bike/login')->withStatus(301);
  }
}