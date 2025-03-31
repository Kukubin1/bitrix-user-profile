<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Bitrix\Main\Data\Cache;



class UserProfileComponent extends CBitrixComponent
{
    protected function getUserData($userId)
    {
        $cache = Cache::createInstance();
        $cacheTime = 3600; // Кеш на 1 час
        $cacheId = "user_profile_" . $userId;
        $cacheDir = "/user_profile_cache/";

        if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
            return $cache->getVars();
        } elseif ($cache->startDataCache()) {
            if (!Loader::includeModule("main")) {
                $cache->abortDataCache();
                return null;
            }

            $user = UserTable::getById($userId)->fetch();
            if ($user) {
                $userData = [
                    "ID" => $user["ID"],
                    "NAME" => $user["NAME"],
                    "LAST_NAME" => $user["LAST_NAME"],
                    "PERSONAL_BIRTHDAY" => $user["PERSONAL_BIRTHDAY"],
                    "PERSONAL_PHONE" => $user["PERSONAL_PHONE"]
                ];
                $cache->endDataCache($userData);
                return $userData;
            } else {
                $cache->abortDataCache();
            }
        }

        return null;
    }

    public function executeComponent()
    {
        global $USER;
        if (!$USER->IsAuthorized()) {
            ShowError("Доступ запрещен");
            return;
        }

        $userId = (int)$this->arParams["USER_ID"];
        $this->arResult["USER"] = $this->getUserData($userId);
        $this->arResult["IS_OWNER"] = $USER->GetID() == $userId;

        if ($_SERVER["REQUEST_METHOD"] === "POST" && $this->arResult["IS_OWNER"]) {
            $this->updateUserData();
        }

        $this->includeComponentTemplate();
    }

    protected function updateUserData()
    {
        global $USER;
        $userId = $USER->GetID();

        $fields = [
            "NAME" => htmlspecialchars(trim($_POST["NAME"])),
            "LAST_NAME" => htmlspecialchars(trim($_POST["LAST_NAME"])),
            "PERSONAL_BIRTHDAY" => htmlspecialchars(trim($_POST["PERSONAL_BIRTHDAY"])),
            "PERSONAL_PHONE" => htmlspecialchars(trim($_POST["PERSONAL_PHONE"]))
        ];



        if (!preg_match("/^[А-Яа-яA-Za-z]+$/u", $fields["NAME"])) {
            ShowError("Ошибка: Имя должно содержать только буквы.");
            return;
        }
        if (!preg_match("/^[А-Яа-яA-Za-z]+$/u", $fields["LAST_NAME"])) {
            ShowError("Ошибка: Фамилия должна содержать только буквы.");
            return;
        }

		if (!preg_match("/^\d{2}\.\d{2}\.\d{4}$/", $fields["PERSONAL_BIRTHDAY"])) {
			ShowError("Ошибка: Дата рождения должна быть в формате DD.MM.YYYY.");
			return;
		}
	 // Преобразование даты в формат Битрикса (YYYY-MM-DD)
		//$dateParts = explode(".", $fields["PERSONAL_BIRTHDAY"]);
		//$fields["PERSONAL_BIRTHDAY"] = trim($_POST["PERSONAL_BIRTHDAY"]);

		if (!preg_match("/^\\+?[0-9]{10,15}$/", $fields["PERSONAL_PHONE"])) {
		 ShowError("Ошибка: Некорректный номер телефона.");
		return;
		}

        $userObj = new CUser;
        if ($userObj->Update($userId, $fields)) {
            LocalRedirect($_SERVER["REQUEST_URI"]); // Перезагрузка страницы
        } else {
            ShowError("Ошибка обновления данных: " . $userObj->LAST_ERROR);
        }
    }
}

?>

