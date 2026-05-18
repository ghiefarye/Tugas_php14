<?php
/**
 * Base Model — semua model mewarisi class ini.
 * Menyediakan akses PDO bersama (singleton).
 */
require_once __DIR__ . '/../../config/database.php';

class Model {
    protected $db;

    public function __construct() {
        $this->db = getDB();
    }
}
