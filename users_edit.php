<?php
require_once __DIR__ . '/../app/controllers/AdminController.php';
$id = $_GET['id'] ?? 0;
$controller = new AdminController();
$controller->userEdit($id);
