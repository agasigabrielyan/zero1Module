<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

class devconsult_supercomponent extends CModule
{
    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . "/version.php";

        $this->MODULE_ID = "devconsult.supercomponent";
        $this->MODULE_NAME = Loc::getMessage('MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage("MODULE_DESCRIPTION");

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->MODULE_SORT = 0;

        $this->PARTNER_NAME = Loc::getMessage("PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("PARTNER_URI");
    }

    public function DoInstall()
    {
        if($this->isVersionD7()) {
            ModuleManager::registerModule($this->MODULE_ID);
            $this->InstallDB();
            $this->InstallFiles();
            $this->InstallEvents();
        } else {
            CAdminMessage::ShowMessage(
                array(
                    "TYPE" => "ERROR",
                    "MESSAGE" => Loc::getMessage("ERROR_VERSION_D7_REQUIRED"),
                    "DETAILS" => Loc::getMessage("ERROR_VERSION_D7_REQUIRED_DETAIL"),
                    "HTML" => true
                )
            );
        }
    }

    public function DoUninstall()
    {
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        $this->UnInstallDB();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function InstallFiles()
    {

    }

    public function InstallDB()
    {
        if (\Bitrix\Main\Loader::includeModule($this->MODULE_ID)) {
            $modelsPath = $this->getPath(true) . '/lib/models';
            \Devconsult\Supercomponent\Helpers\Helper::createTablesFromModels($modelsPath);
        }
    }

    public function UnInstallDB()
    {
        if (\Bitrix\Main\Loader::includeModule($this->MODULE_ID)) {
            $modelsPath = $this->getPath(true) . '/lib/models';

            if (is_dir($modelsPath)) {
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

                            // Удаляем таблицу, если она существует
                            if ($connection->isTableExists($entity->getDBTableName())) {
                                $connection->dropTable($entity->getDBTableName());
                            }
                        }
                    }
                }
            }
        }
    }

    public function InstallEvents()
    {

    }

    public function InstallAgents() {

    }

    public function UnInstallFiles()
    {

    }

    public function UnInstallEvents()
    {

    }

    public function getPath($notDocumentRoot = false) {
        return $notDocumentRoot ? str_ireplace(Application::getDocumentRoot(), "", dirname(__DIR__)) : dirname(__DIR__);
    }

    public function isVersionD7() {
        return CheckVersion(ModuleManager::getVersion("main"),"18.00.00");
    }


}