<?php
// TO-DO вынести в js файл
?>
<script type='text/javascript'>//<![CDATA[
    jQuery(document.body).addClass('ecwid-no-padding');
    jQuery(document.body).css({
    	'font-size': '13px'
    });
    jQuery('#wpbody').css({
    	'background-color': 'rgb(240, 242, 244)'
    });
//]]>
</script>
<?php
// TO-DO вынести в css файл
?>
<style type="text/css">
	.settings-page input[type=checkbox]:disabled:before { content: ''; }
	.settings-page input[type=text] {
		min-height: unset;
	}
	.settings-page input[type=text]:focus, .settings-page select:focus, .settings-page a:focus {
	    border-color: unset;
	    box-shadow: unset;
	    outline: unset;
	}
	.settings-page .field__error { display: none; }
	.settings-page .has-error .field__error { display: block; }

	.settings-page[data-ec-storefront-status="publish"] [data-ec-storefront]:not([data-ec-storefront="publish"]) { display: none; }
	.settings-page[data-ec-storefront-status="draft"] [data-ec-storefront]:not([data-ec-storefront="draft"]) { display: none; }
</style>


<div class="settings-page" data-ec-storefront-status="<?php echo $page_status; ?>" >
	<div class="settings-page__header">
		<div class="settings-page__titles settings-page__titles--left">
			<h1 class="settings-page__title"><?php _e('Your storefront', 'ecwid-shopping-cart'); ?></h1>
			<div class="settings-page__subtitle"></div>
		</div>

		<div class="a-card a-card--compact a-card--warning" data-ec-storefront="draft">
			<div class="a-card__paddings">
				<div class="iconable-block iconable-block--hide-in-mobile iconable-block--warning">
					<div class="iconable-block__infographics">
						<span class="iconable-block__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70" focusable="false"><path d="M34.5 67C16.58 67 2 52.42 2 34.5S16.58 2 34.5 2 67 16.58 67 34.5 52.42 67 34.5 67zm0-62C18.23 5 5 18.23 5 34.5S18.23 64 34.5 64 64 50.77 64 34.5 50.77 5 34.5 5z"></path><path d="M34.5 49c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5zM35.5 38.57h-2l-1-14c0-1.17.89-2.07 2-2.07s2 .9 2 2l-1 14.07z"></path></svg></span>
					</div>
					<div class="iconable-block__content">
						<div class="cta-block">
							<div class="cta-block__central">
								<div class="cta-block__title">Store is not accessible on your site.</div>
								<div class="cta-block__content">The store page is in draft. Publish it to make your storefront available for your customers.</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php require __DIR__ . '/area-status.tpl.php'; ?>

		<?php require __DIR__ . '/area-design.tpl.php'; ?>

		<?php require __DIR__ . '/area-navigation.tpl.php'; ?>

		<?php require __DIR__ . '/area-additional.tpl.php'; ?>

	</div>

</div>

