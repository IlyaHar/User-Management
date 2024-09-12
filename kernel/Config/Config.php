<?php

namespace App\Kernel\Config;
use Exception;

class Config implements ConfigInterface
{
    /**
     * @throws Exception
     */

    public function get(string $key): string
    {
        [$file, $key] = explode('.', $key);

        $configPath = APP_DIR . '/config/' . $file . '.php';

        if (!file_exists($configPath)) {
            throw new Exception('Configuration file not found: ' . $configPath);
        }

        $config = require $configPath;

        return $config[$key];
    }
}