<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Database;

use PDO;
use PDOStatement;

class Db
{
    private string $hostname;
    private string $username;
    private string $password;
    private string $dbname;

    private PDO $pdo;

    private bool $persistant;

    public function __construct(bool $persistant)
    {
        $this->persistant = $persistant;

        $this->hostname = $_ENV['DB_HOSTNAME'];
        $this->username = $_ENV['DB_USERNAME'];
        $this->password = $_ENV['DB_PASSWORD'];
        $this->dbname = $_ENV['DB_NAME'];

        $this->connectToDb();
    }

    protected function connectToDb(): void
    {
        $this->pdo = new PDO(
            "mysql:host=$this->hostname;dbname=$this->dbname",
            $this->username,
            $this->password,
            $this->persistant ? PDO::ATTR_PERSISTENT : null
        );
    }

    public static function getInstance(bool $persistant = false): Db
    {
        return new Db($persistant);
    }

    public function getTables(): array
    {
        return $this->pdo->query('show tables;')->fetchAll();
    }

    public function query(string $sql): false|PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    public function select(string $tableName, string $where = '', array $values = [], $orderBy = ''): false|array
    {
        $select = '*';

        if (!empty($values)) {
            $select = implode(', ', $values);
        }

        $prepare = sprintf("SELECT %s FROM %s%s%s",
            $select,
            $tableName,
            strlen($where) > 0 ? " WHERE $where" : '',
            strlen($orderBy) > 0 ? " ORDER BY $orderBy" : ''
        );

        $prepare = $this->pdo->query($prepare, PDO::FETCH_ASSOC);

        return $prepare->fetchAll();
    }

    public function insert(string $tableName, array $values): bool
    {
        $prepare = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $tableName,
            implode(', ', array_keys($values)),
            implode(', ', array_map(
                    function (string $name) {
                        return ":$name";
                    }, array_keys($values))
            )
        );

        $prepare = $this->pdo->prepare(
            $prepare
        );

        foreach ($values as $name => &$value) {
            $prepare->bindParam(":$name", $value);
        }

        return $prepare->execute();
    }

    public function update(string $tableName, array $values, string $where): bool
    {
        $prepare = sprintf(
            "UPDATE %s SET %s WHERE %s",
            $tableName,
            implode(', ', array_map(
                function (mixed $value, string $name) {
                    if (is_string($value)) {
                        $value = "'$value'";
                    }
                    return "$name = $value";
                }, $values, array_keys($values)
            )),
            $where
        );

        $prepare = $this->pdo->prepare(
            $prepare
        );

        return $prepare->execute();
    }

    public function delete(string $tableName, string $where): bool
    {
        $prepare = sprintf(
            "DELETE FROM %s WHERE %s",
            $tableName,
            $where
        );

        $prepare = $this->pdo->prepare(
            $prepare
        );

        return $prepare->execute();
    }

    public function close(): void
    {
        $this->pdo = null;
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }
}
