<?php
// controllers/MastersController.php

class MastersController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // ── GET /masters ───────────────────────────────────────────────────────
    public function index(): void
    {
        $masters = $this->pdo
            ->query("SELECT * FROM masters ORDER BY id DESC")
            ->fetchAll();

        render('masters/index', ['masters' => $masters]);
    }

    // ── GET /portfolio?master_id=X ─────────────────────────────────────────
    public function portfolio(): void
    {
        $master_id = (int)($_GET['master_id'] ?? 0);

        $stmt = $this->pdo->prepare("SELECT * FROM masters WHERE id = ?");
        $stmt->execute([$master_id]);
        $master = $stmt->fetch();

        if (!$master) {
            redirect(BASE_URL . '/masters');
        }

        $stmt = $this->pdo->prepare(
            "SELECT * FROM master_portfolio
             WHERE master_id = ?
             ORDER BY created_at DESC"
        );
        $stmt->execute([$master_id]);
        $portfolio = $stmt->fetchAll();

        render('masters/portfolio', [
            'master'    => $master,
            'portfolio' => $portfolio,
        ]);
    }
}
