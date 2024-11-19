<?php

namespace Devconsult\Supercomponent\Helpers;

class Helper
{
    public static function createTablesFromModels($modelsPath)
    {
        // Проверяем, существует ли папка с моделями
        if (!is_dir($modelsPath)) {
            throw new \Exception("Папка с моделями не найдена: " . $modelsPath);
        }

        $files = scandir($modelsPath);
        foreach ($files as $file) {
            // Пропускаем "." и ".."
            if ($file === '.' || $file === '..') {
                continue;
            }

            $filePath = $modelsPath . DIRECTORY_SEPARATOR . $file;

            // Проверяем, является ли файл PHP-скриптом
            if (is_file($filePath) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                // Определяем пространство имен и класс
                $className = '\\Devconsult\\Supercomponent\\Models\\' . pathinfo($file, PATHINFO_FILENAME) . 'Table';

                // Проверяем, существует ли класс
                if (class_exists($className)) {
                    $entity = $className::getEntity();
                    $connection = $entity->getConnection();

                    // Создаем таблицу, если ее еще нет
                    if (!$connection->isTableExists($entity->getDBTableName())) {
                        $entity->createDBTable();
                    }
                }
            }
        }
    }
}