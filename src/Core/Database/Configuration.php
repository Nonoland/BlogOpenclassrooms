<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Database;

class Configuration
{
    public static function getConfiguration(string $name): mixed
    {
        $dbInstance = Db::getInstance();
        $query = $dbInstance->select('configuration', "name = \"$name\"", ['value']);
        if (!$query || empty($query)) {
            return false;
        }

        return $query[0]['value'];
    }

    public static function setConfiguration(string $name, mixed $value): bool
    {
        $dbInstance = Db::getInstance();
        return $dbInstance->insert('configuration', ['name' => $name, 'value' => (string)$value]);
    }

    public static function updateConfiguration(string $name, mixed $value): bool
    {
        $dbInstance = Db::getInstance();
        return $dbInstance->update('configuration', ['value' => $value], "name = $name");
    }
}
