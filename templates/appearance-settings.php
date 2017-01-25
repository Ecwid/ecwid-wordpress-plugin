<h2><?php printf( __( '%s Shopping Cart — Appearance settings', 'ecwid-shopping-cart'), Ecwid_WL::get_brand() ); ?></h2>

<div class="wrap">
<form class="pure-form pure-form-aligned ecwid-settings appearance-settings" method="POST" action="options.php">

	<?php settings_fields('ecwid_options_page'); ?>
	<input type="hidden" name="settings_section" value="appearance" />

	<fieldset>

		<div class="pure-control-group small-input">
			<div class="input">
				<div>
					<input
						id="ecwid_show_search_box"
						name="ecwid_show_search_box"
						type="checkbox"
						<?php if (get_option('ecwid_show_search_box')): ?>
							checked="checked"
						<?php endif; ?>
						<?php echo $disabled_str; ?>
						/>
				</div>
			</div>
			<div class="label">
				<label for="ecwid_show_search_box">
					<?php _e('Display search box above products', 'ecwid-shopping-cart'); ?>
				</label>
			</div>
			<div class="note">
				<?php echo sprintf(__('Or you can add search box to your website\'s toolbar using <a href="%s">WordPress native widgets</a>', 'ecwid-shopping-cart'), 'widgets.php?from-ecwid=appearance'); ?>
			</div>
		</div>

		<div class="pure-control-group small-input">
			<div class="input">
				<div>
					<input
						id="ecwid_show_categories"
						name="ecwid_show_categories"
						type="checkbox"
						<?php if (get_option('ecwid_show_categories')): ?>
							checked="checked"
						<?php endif; ?>
						<?php echo $disabled_str; ?>
						/>
				</div>
			</div>
			<div class="label">
				<label for="ecwid_show_categories">
					<?php _e('Display categories above products', 'ecwid-shopping-cart'); ?>
				</label>
			</div>
			<div class="note">
				<?php echo sprintf(__('Or you can add vertical categories to your website\'s toolbar using <a href="%s">WordPress native widgets</a>', 'ecwid-shopping-cart'), 'widgets.php?from-ecwid-appearance'); ?>
			</div>
		</div>


		<div class="pure-control-group small-input">
			<div class="input">
				<div>
					<input
						id="ecwid_enable_minicart"
						name="ecwid_enable_minicart"
						type="checkbox"
						<?php if (get_option('ecwid_enable_minicart')): ?>
							checked="checked"
						<?php endif; ?>
						<?php echo $disabled_str; ?>
						/>
				</div>
			</div>
			<div class="label">
				<label for="ecwid_enable_minicart">
					<?php _e('Enable minicart attached to categories', 'ecwid-shopping-cart'); ?>
				</label>
			</div>
			<div class="note">
				<?php _e("You should disable this option, if you added minicart to your website's&nbsp;sidebar", 'ecwid-shopping-cart'); ?>
			</div>
		</div>

		<div class="pure-control-group small-input">
			<div class="input">
				<div>
					<input
						id="ecwid_pb_categoriesperrow"
						name="ecwid_pb_categoriesperrow"
						type="text"
						class="number"
						value="<?php echo esc_attr(get_option('ecwid_pb_categoriesperrow')); ?>"
						<?php echo $disabled_str; ?>
					/>
				</div>
			</div>
			<div class="label">
				<label for="ecwid_pb_categoriesperrow">
					<?php _e('Number of categories per row', 'ecwid-shopping-cart'); ?>
				</label>
			</div>
			<div class="note">
			</div>
		</div>

		<hr />


		<div class="pure-control-group">
			<label class="products-per-page-label"><?php _e('Number of products per page', 'ecwid-shopping-cart'); ?></label>
			<div class="ecwid-pb-view-size grid active" tabindex="1">
				<div class="title"><?php _e('Grid view', 'ecwid-shopping-cart'); ?></div>
				<div class="main-area">
					<?php ecwid_embed_svg('grid'); ?>
				</div>
				<div class="right">
					<div class="ruler"></div>
					<input
						type="text"
						size="2"
						name="ecwid_pb_productspercolumn_grid"
						class="number"
						value="<?php echo esc_attr(get_option('ecwid_pb_productspercolumn_grid')); ?>"
						/>
				</div>
				<div class="bottom">
					<div class="ruler"></div>
					<input
						type="text"
						size="2"
						name="ecwid_pb_productsperrow_grid"
						class="number"
						value="<?php echo esc_attr(get_option('ecwid_pb_productsperrow_grid')); ?>"
						/>
				</div>
			</div>

			<div class="ecwid-pb-view-size list" tabindex="1">
				<div class="title"><?php _e('List view', 'ecwid-shopping-cart'); ?></div>
				<div class="main-area">
					<?php ecwid_embed_svg('list'); ?>
				</div>
				<div class="right">
					<div class="ruler"></div>
					<input
						type="text"
						size="2"
						name="ecwid_pb_productsperpage_list"
						class="number"
						value="<?php echo esc_attr(get_option('ecwid_pb_productsperpage_list')); ?>" />
				</div>
			</div>


			<div class="ecwid-pb-view-size table" tabindex="1">
				<div class="title"><?php _e('Table view', 'ecwid-shopping-cart'); ?></div>
				<div class="main-area">
					<?php ecwid_embed_svg('table'); ?>
				</div>
				<div class="right">
					<div class="ruler"></div>
					<input
						type="text"
						size="2"
						name="ecwid_pb_productsperpage_table"
						class="number"
						value="<?php echo esc_attr(get_option('ecwid_pb_productsperpage_table')); ?>"
						/>
				</div>
			</div>
			<p class="note pb-note"><?php printf( __( 'Here you can control how many products will be displayed per page. These options define maximum values. If there is not enough space to show all product columns, %s will adapt the number of columns to hold all products.', 'ecwid-shopping-cart'), Ecwid_WL::get_brand() ); ?></p>
		</div>

		<hr />

		<div class="pure-control-group">
			<label for="ecwid_pb_defaultview">
				<?php _e('Default view mode on product pages', 'ecwid-shopping-cart'); ?>
			</label>

			<select	id="ecwid_pb_defaultview" name="ecwid_pb_defaultview" $disabled_str>
				<option value="grid" <?php if(get_option('ecwid_pb_defaultview') == 'grid') echo 'selected="selected"' ?> >
					<?php _e('Grid', 'ecwid-shopping-cart'); ?>
				</option>
				<option value="list" <?php if(get_option('ecwid_pb_defaultview') == 'list') echo 'selected="selected"' ?> >
					<?php _e('List', 'ecwid-shopping-cart'); ?>
				</option>
				<option value="table" <?php if(get_option('ecwid_pb_defaultview') == 'table') echo 'selected="selected"' ?> >
					<?php _e('Table', 'ecwid-shopping-cart'); ?>
				</option>
			</select>
		</div>

		<div class="pure-control-group">
			<label for="ecwid_pb_searchview">
				<?php _e('Default view mode on search results', 'ecwid-shopping-cart'); ?>
			</label>

			<select	id="ecwid_pb_searchview" name="ecwid_pb_searchview" $disabled_str>
				<option value="grid" <?php if(get_option('ecwid_pb_searchview') == 'grid') echo 'selected="selected"' ?> >
					<?php _e('Grid', 'ecwid-shopping-cart'); ?>
				</option>
				<option value="list" <?php if(get_option('ecwid_pb_searchview') == 'list') echo 'selected="selected"' ?> >
					<?php _e('List', 'ecwid-shopping-cart'); ?>
				</option>
				<option value="table" <?php if(get_option('ecwid_pb_searchview') == 'table') echo 'selected="selected"' ?> >
					<?php _e('Table', 'ecwid-shopping-cart'); ?>
				</option>
			</select>
		</div>

	</fieldset>

	<fieldset>
		<hr />
		<div class="pure-control-group">
			<button type="submit" class="<?php echo ECWID_MAIN_BUTTON_CLASS; ?>"><?php _e('Save changes', 'ecwid-shopping-cart'); ?></button>
		</div>
	</fieldset>
</form>

</div>