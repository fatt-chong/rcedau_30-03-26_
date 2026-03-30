<?php

class Connection41 extends PDO {
    private string $dns = "mysql";
    private ?PDO $lnk = null; // Puntero a la conexión de la base de datos
    private string $user = "vista";
    private string $pass = "123vista";
    private string $server = "10.6.21.29"; // Cambia según sea necesario
    private string $database = ""; // Nombre de la base de datos
    private array $rs = []; // Resultado de la consulta
    protected int $transactionCounter = 0;

    public function __construct() {
        parent::__construct(
            "{$this->dns}:host={$this->server};dbname={$this->database}",
            $this->user,
            $this->pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }

    /*************SOBRECARGAS*************/
    public function beginTransaction(): bool {
        if (!$this->transactionCounter++) {
            return parent::beginTransaction();
        }
        return $this->transactionCounter >= 0;
    }

    public function commit(): bool {
        if (!--$this->transactionCounter) {
            return parent::commit();
        }
        return $this->transactionCounter >= 0;
    }

    public function rollback(): bool {
        if ($this->transactionCounter >= 0) {
            $this->transactionCounter = 0;
            return parent::rollback();
        }
        $this->transactionCounter = 0;
        return false;
    }
    /************************************/

    public function db_connect(): void {
        try {
            $this->lnk = new PDO(
                "{$this->dns}:host={$this->server};dbname={$this->database}",
                $this->user,
                $this->pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            error_log("Error al conectar a la base de datos: " . $e->getMessage());
            die("Error al conectar a la base de datos.");
        }
    }

    public function db_close(): void {
        $this->lnk = null; // PDO libera automáticamente los recursos
    }

    public function consultaSQL(string $query, string $error): array {
        try {
            $this->lnk?->exec('SET NAMES UTF8'); // Configuración de codificación
            $stmt = $this->lnk?->query($query);
            return $stmt ? $stmt->fetchAll() : [];
        } catch (PDOException $e) {
            error_log("Error en consultaSQL: $error - " . $e->getMessage());
            die($error);
        }
    }

    public function ejecutarSQL(string $query, string $error): int {
        try {
            $this->lnk?->exec('SET NAMES UTF8'); // Configuración de codificación
            return $this->lnk?->exec($query) ?? 0;
        } catch (PDOException $e) {
            error_log("Error en ejecutarSQL: $error - " . $e->getMessage());
            die($error);
        }
    }

    public function setDB(string $database): void {
        $this->database = $database;
        $this->db_connect();
    }

    public function setServer(string $server): void {
        $this->server = $server;
    }
    public function lastInsertId(?string $name = null): string|false {
        if ($this->lnk) {
            return $this->lnk->lastInsertId($name);
        }
        throw new Exception("La conexión a la base de datos no está inicializada.");
    }
}
?>