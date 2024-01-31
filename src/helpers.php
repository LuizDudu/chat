<?php

CONST PREFIX_ENV = '_env_file_';

if (!function_exists('env')) {
    function env(string $name): string
    {
        if (!empty($_ENV[PREFIX_ENV . $name])) {
            return $_ENV[PREFIX_ENV . $name];
        }

        return false;
    }
}

if (!function_exists('setUpEnv')) {
    function setUpEnv(): void
    {
        $environmentVariables = parse_ini_file(__DIR__ . '/../.env');
        foreach ($environmentVariables as $key => $value) {
            $_ENV[PREFIX_ENV . $key] = $value;
        }
    }
}
