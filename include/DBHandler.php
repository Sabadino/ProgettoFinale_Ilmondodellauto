<?php

class DBHandler {
    private static $pdo = null;

    private function __construct() {}

    public static function getPDO() {
        if (self::$pdo == null) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=localhost;dbname=concessionario;charset=utf8',
                    'root',
                    ''
                );
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Errore connessione DB: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}