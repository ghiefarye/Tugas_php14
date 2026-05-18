<?php
/**
 * Base Controller — semua controller MVC mewarisi class ini.
 * Menyediakan helper untuk load model dan render view.
 */
class Controller {

    /**
     * Memuat model dari folder app/models/
     * Penggunaan: $this->model('User')
     */
    public function model($model) {
        $path = __DIR__ . '/../models/' . $model . '.php';
        if (!file_exists($path)) {
            die("Model tidak ditemukan: $model");
        }
        require_once $path;
        return new $model();
    }

    /**
     * Render view dari folder views/
     * Penggunaan: $this->view('admin/dashboard', ['data' => $x])
     */
    public function view($view, $data = []) {
        extract($data);
        $path = __DIR__ . '/../../views/' . $view . '.php';
        if (!file_exists($path)) {
            die("View tidak ditemukan: $view");
        }
        require $path;
    }

    /**
     * Redirect helper.
     */
    public function redirect($url) {
        header("Location: " . $url);
        exit;
    }
}
