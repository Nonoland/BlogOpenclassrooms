<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Object;

use DateTime;
use Nolandartois\BlogOpenclassrooms\Core\Database\Db;

abstract class ObjectModel
{
    const DATE_FORMAT = 'Y-m-d H:i:s';
    public static array $definitions = [
        'table' => '',
        'values' => []
    ];
    protected int $id = 0;
    protected DateTime $dateAdd;
    protected DateTime $dateUpd;

    public function __construct(int $id = 0)
    {
        if ($id === 0) {
            return;
        }

        $dbInstance = Db::getInstance();
        $result = $dbInstance->select(static::$definitions['table'], "id = $id");

        if (empty($result)) {
            return;
        }
        $result = $result[0];

        foreach ($result as $name => $value) {
            $name = $this->snakeToCamel($name);
            $value = htmlspecialchars_decode($value);

            if (!property_exists(get_class($this), $name)) {
                continue;
            }

            if ($name == 'dateAdd' || $name == 'dateUpd') {
                $this->{$name} = DateTime::createFromFormat(self::DATE_FORMAT, $value);
            } elseif (is_array($this->{$name})) {
                $this->{$name} = json_decode($value, true);
            } else {
                $this->{$name} = $value;
            }
        }
    }

    protected function snakeToCamel(string $value): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $value))));
    }

    public function add(): bool
    {
        $dbInstance = Db::getInstance();

        $data = [];
        foreach (static::$definitions['values'] as $nameValue => $parameters) {
            $camelName = $this->snakeToCamel($nameValue);

            if (!property_exists(get_class($this), $camelName)) {
                continue;
            }

            if (is_array($this->{$camelName})) {
                $data[$nameValue] = json_encode($this->{$camelName});
            } else {
                $data[$nameValue] = $this->{$camelName};
            }
        }

        return $dbInstance->insert(static::$definitions['table'], $data);
    }

    public function update(): bool
    {
        if ($this->id === 0) {
            return false;
        }

        $dbInstance = Db::getInstance();

        $data = [];
        foreach (static::$definitions['values'] as $valueName => $declaration) {
            $camelName = $this->snakeToCamel($valueName);

            if (!property_exists(get_class($this), $camelName)) {
                continue;
            }

            if (is_array($this->{$camelName})) {
                $data[$valueName] = htmlspecialchars(json_encode($this->{$camelName}));
            } else {
                $data[$valueName] = $this->{$camelName};
            }
        }

        return $dbInstance->update(
            static::$definitions['table'],
            $data,
            "id = $this->id"
        );
    }

    public function delete(): bool
    {
        if ($this->id === 0) {
            return false;
        }

        return Db::getInstance()->delete(static::$definitions['table'], "id = " . $this->id);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getDateAdd(): DateTime
    {
        return $this->dateAdd;
    }

    /**
     * @return DateTime
     */
    public function getDateUpd(): DateTime
    {
        return $this->dateUpd;
    }
}
