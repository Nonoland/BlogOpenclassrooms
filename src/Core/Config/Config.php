<?php
namespace Nolandartois\BlogOpenclassrooms\Core\Config;

use InvalidArgumentException;

class Config
{
    private array $configData;

    public function __construct()
    {
        $configPath = "config/config.json";
        if (!file_exists($configPath)) {
            throw new InvalidArgumentException('Config file not exist');
        }

        $configContent = file_get_contents($configPath);
        $this->configData = json_decode($configContent, true);
    }

    public function get(): array
    {
        return $this->configData;
    }

    public static function getDatabaseInfo(): false|array
    {
        $config = new Config();

        if (!array_key_exists('database', $config->get())) {
            return false;
        }

        return $config->get()['database'];
    }
}
