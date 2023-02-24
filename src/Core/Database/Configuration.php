<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Database;

class Configuration
{
    public static function getConfiguration(string $name)
    {
        $dbInstance = Db::getInstance();
        $query = $dbInstance->select('configuration', "name = \"$name\"", ['value']);
        if (!$query || empty($query)) {
            return false;
        }

        return $query[0]['value'];
    }

    public static function setConfiguration(string $name, mixed $value)
    {
        $dbInstance = Db::getInstance();
        return $dbInstance->insert('configuration', ['value' => (string)$value]);
    }

    public static function updateConfiguration(string $name, mixed $value)
    {
        $dbInstance = Db::getInstance();
        return $dbInstance->update('configuration', ['value' => $value], "name = $name");
    }
}