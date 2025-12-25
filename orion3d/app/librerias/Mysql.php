<?php
// App/Librerias/Mysql.php

require_once 'Base.php'; // Aseguramos que cargue la Base

class Mysql extends Base
{
    protected $pdo;
    
    public function __construct()
    {
        parent::__construct();           // crea $this->conect en Base
        $this->pdo = $this->conect;      // Lo asignamos a $pdo

        if (!$this->pdo instanceof PDO) {
            die("❌ Error: No se pudo establecer la conexión con la base de datos.");
        }
        
        // Configuraciones extra
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
    }

    public function select(string $query, array $arrValues = []): array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($arrValues);
        return $stmt->fetchAll();
    }

    public function select_one(string $query, array $arrValues = []): ?array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($arrValues);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function insert(string $query, array $arrValues = []): int
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($arrValues);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(string $query, array $arrValues = []): bool
    {
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($arrValues);
    }

    public function delete(string $query, array $arrValues = []): bool
    {
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($arrValues);
    }
}