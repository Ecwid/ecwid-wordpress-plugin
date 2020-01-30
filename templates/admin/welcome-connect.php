<div class="ec-form">
	<div class="ec-button">
		<form action="<?php echo $connect_url; ?>" method="post">
			<button type="submit" class="btn btn--medium btn--orange">Подключить существующий магазин</button>
		</form>
	</div>
	<?php if ( !Ecwid_Config::is_no_reg_wl() ) { ?>
	<a target="_blank" href="<?php echo esc_attr(ecwid_get_register_link()); ?>">Создать магазин&nbsp;&rsaquo;</a>
	<?php } ?>
</div>

<div class="ec-note">
	Нажатие кнопки откроет диалог авторизации в Эквид-магазин. Чтобы добавить магазин на сайт, подтвердите выдачу прав.
</div>