<?php
namespace Devconsult\Supercomponent\Models;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\TextField;

Loc::loadMessages(__FILE__);

class SimpledataTable extends DataManager
{
    /**
     * Возвращает карту полей ORM
     *
     * @return array
     */

    public static function getMap()
    {
        return [
            new IntegerField("ID",[
                "primary" => true,
                "autocomplete" => true,
                "title" => Loc::getMessage('SUPERCOMPOENNT_SIMPLEDATA_ID_FIELD')
            ]),
            new StringField("NAME", [
                "default_value" => null,
                "title" => Loc::getMessage("SUPERCOMPONENT_SIMPLEDATA_NAME_FIELD")
            ]),
            new TextField("DESCRIPTION", [
                "default_value" => null,
                "title" => Loc::getMessage("SUPERCOMPONENT_SIMPLEDATA_DESCRIPTION_FIELD")
            ]),
            new BooleanField("SOMEBOOLEAN",[
                "values" => [
                    Loc::getMessage("MOSCOW"),
                    Loc::getMessage("SANT-PETERSBURG"),
                    Loc::getMessage("KURGAN"),
                ],
                "default_value" => Loc::getMessage("KURGAN"),
                "title" => Loc::getMessage("SUPERCOMPOENT_SIMPLEDATA_CITY_FIELD")
            ])
        ];
    }
}