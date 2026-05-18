<?php
/**
 * Router / Front Controller sederhana.
 * URL pattern: index.php?url=controller/method/param
 *
 * Default: AuthController@login
 */
class App {
    protected $controller = 'AuthController';
    protected $method     = 'login';
    protected $params     = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Controller
        if (isset($url[0]) && $url[0] !== '') {
            $ctrl = ucfirst($url[0]) . 'Controller';
            $path = __DIR__ . '/../controllers/' . $ctrl . '.php';
            if (file_exists($path)) {
                $this->controller = $ctrl;
                unset($url[0]);
            }
        }
        require_once __DIR__ . '/../controllers/' . $this->controller . '.php';
        $controllerObj = new $this->controller;

        // Method
        if (isset($url[1])) {
            if (method_exists($controllerObj, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Params
        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$controllerObj, $this->method], $this->params);
    }

    private function parseUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }
}
