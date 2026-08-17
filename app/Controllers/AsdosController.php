<?php

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../Models/User.php';

/**
 * Controller Asdos
 * 
 * Menangani seluruh fitur panel asdos.
 */
class AsdosController
{
    private function requireAsdos(): void
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'asdos') {
            header('Location: index.php?page=login');
            exit();
        }
    }

    public function marketplace(): void
    {
        $this->requireAsdos();
        require_once __DIR__ . '/../Views/Asdos/Marketplace.php';
    }
}
