<?php
// controllers/CatalogController.php

class CatalogController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // ── GET / ──────────────────────────────────────────────────────────────
    public function index(): void
    {
        $products = $this->pdo
            ->query("SELECT * FROM products ORDER BY id DESC")
            ->fetchAll();

        $masters = $this->pdo
            ->query("SELECT * FROM masters ORDER BY id DESC")
            ->fetchAll();

        render('catalog/index', [
            'products' => $products,
            'masters'  => $masters,
        ]);
    }
}
