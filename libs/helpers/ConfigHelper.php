<?php
namespace libs\helpers;

/**
 * Class ConfigHelper
 *
 * @author Yuriy Stos
 */
class ConfigHelper
{
    /**
     * Get configuration array
     *
     * @return mixed
     */
    public static function getConfig()
    {
        $config = require __DIR__ . '/../../config/config.php';

        return file_exists($path = __DIR__ . '/../../config/config-local.php') ? array_merge($config, require($path)) : $config;
    }

    /**
     * Get Database connection settings
     *
     * @return null|string - Connection string
     */
    public static function getDBConnectionSettings()
    {
        if ($config = self::getConfig()) {
            if (isset($config['path_to_vocabulary'])) {
                return $config['path_to_vocabulary'];
            }
        }
        return null;
    }
}