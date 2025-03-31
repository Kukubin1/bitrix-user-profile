<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Страница пользователя");
?>
<?$APPLICATION->IncludeComponent(
	"my-personal:user.profile",
    "",
    ["USER_ID" => $USER->GetID()]
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>