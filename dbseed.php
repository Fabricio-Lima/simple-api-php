<?php
require 'bootstrap.php';

$statement = <<<EOS
    CREATE TABLE IF NOT EXISTS Clubes (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        clube VARCHAR(100) NOT NULL,
        saldo_disponivel DECIMAL(10, 2) NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=INNODB;

    CREATE TABLE IF NOT EXISTS Recursos (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        recurso VARCHAR(100) NOT NULL,
        saldo_disponivel DECIMAL(10, 2) NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=INNODB;

    INSERT INTO Recursos
        (id, recurso, saldo_disponivel)
    VALUES
        (1, 'Recurso para passagens', 10000.00),
        (2, 'Recurso para hospedagens', 10000.00)
EOS;

try {
    $createTable = $dbConnection->exec($statement);
    echo "Database has been created!\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}
?>