<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="user-profile">
        <p><strong>Имя:</strong> <?= htmlspecialchars($arResult['USER']['NAME']) ?></p>
        <p><strong>Фамилия:</strong> <?= htmlspecialchars($arResult['USER']['LAST_NAME']) ?></p>
        <p><strong>Дата рождения:</strong> <?= htmlspecialchars($arResult['USER']['PERSONAL_BIRTHDAY']) ?></p>
        <p><strong>Телефон:</strong> <?= htmlspecialchars($arResult['USER']['PERSONAL_PHONE']) ?></p>
</div>
		 <form method="POST" class ="fix_form">
            <label>Имя:</label>
            <input type="text" name="NAME" value="<?= htmlspecialchars($arResult['USER']['NAME']) ?>">

            <label>Фамилия:</label>
            <input type="text" name="LAST_NAME" value="<?= htmlspecialchars($arResult['USER']['LAST_NAME']) ?>">
			
           <label>Дата рождения (DD.MM.YYYY):</label>
			<input type="text" id="birthday" name="PERSONAL_BIRTHDAY" value="<?= htmlspecialchars($arResult['USER']['PERSONAL_BIRTHDAY']) ?>" placeholder="ДД.ММ.ГГГГ">


            <label>Телефон:</label>
            <input type="text" id ="phone" name="PERSONAL_PHONE" value="<?= htmlspecialchars($arResult['USER']['PERSONAL_PHONE']) ?>"placeholder="89874372253">

            <input type="submit">
        </form>


<script>
$(document).ready(function() {
    $("#birthday").inputmask("99.99.9999", { 
        placeholder: "ДД.ММ.ГГГГ",
        showMaskOnHover: false 
    });
	 $("#phone").inputmask("89999999999", { 
        placeholder: "89874372253",
        showMaskOnHover: false 
    });
});
</script>