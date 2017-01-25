<script data-cfasync="false"  type="text/javascript">
	var ecwid_store_svg = '<?php echo get_site_url('', 'index.php?file=ecwid_store_svg.svg'); ?>';
</script>
<div id="ecwid-store-popup-content">
	<div class="media-modal wp-core-ui">
		<div class="media-modal-content">
  		<a class="media-modal-close" href="#" title="Close"><span class="media-modal-icon"></span></a>
			<div class="media-frame wp-core-ui hide-router">
				<div class="media-frame-menu">
					<div class="media-menu">
						<a href="#" class="media-menu-item" data-content="add-store"><?php _e('Add Store', 'ecwid-shopping-cart'); ?></a>
						<a href="#" class="media-menu-item" data-content="store-settings"><?php _e('Store elements', 'ecwid-shopping-cart'); ?></a>
						<a href="#" class="media-menu-item" data-content="appearance"><?php _e('Appearance', 'ecwid-shopping-cart'); ?></a>
					</div>
				</div>

				<div class="media-frame-title add-store">
					<h1>
						<?php _e('Add Store', 'ecwid-shopping-cart'); ?><span class="dashicons dashicons-arrow-down"></span>
					</h1>
				</div>

				<div class="media-frame-title store-settings">
					<h1>
						<?php _e('Store elements', 'ecwid-shopping-cart'); ?><span class="dashicons dashicons-arrow-down"></span>
					</h1>
				</div>

				<div class="media-frame-title appearance">
					<h1>
						<?php _e('Appearance', 'ecwid-shopping-cart'); ?><span class="dashicons dashicons-arrow-down"></span>
					</h1>
				</div>

				<div class="media-frame-content ecwid-store-editor store-settings">

					<div class="store-settings-wrapper">
						<div class="store-settings-preview">
							<?php ecwid_embed_svg('add-store'); ?>
							<label for="show_search"     class="ecwid-search"     data-ecwid-widget="search"></label>
							<label for="show_categories" class="ecwid-categories" data-ecwid-widget="categories"></label>
							<label for="show_minicart"   class="ecwid-minicart"   data-ecwid-widget="minicart"></label>
						</div>

						<div class="store-settings">
							<h3><?php _e('Choose widgets to show', 'ecwid-shopping-cart'); ?></h3>
							<p class="note"><?php _e('Product catalog will be shown automatically', 'ecwid-shopping-cart'); ?></p>

							<div class="pure-control-group">
								<label data-ecwid-widget="search">
									<input type="checkbox" name="show_search" id="show_search" />
									<?php _e('Show search', 'ecwid-shopping-cart'); ?>
								</label>
							</div>

							<div class="pure-control-group">
								<label data-ecwid-widget="minicart">
									<input type="checkbox" name="show_minicart" id="show_minicart" />
									<?php _e('Show minicart', 'ecwid-shopping-cart'); ?>
								</label>
							</div>

							<div class="pure-control-group">
								<label data-ecwid-widget="categories">
									<input type="checkbox" name="show_categories" id="show_categories" />
									<?php _e('Show categories', 'ecwid-shopping-cart'); ?>
								</label>
							</div>
						</div>
						<div class="note">
							<?php echo sprintf(
									__('Additionally, you can add store controls to your website\'s toolbar using <a %s>WordPress native widgets</a>', 'ecwid-shopping-cart'),
									' target="_blank" href="widgets.php?from-ecwid=' . (isset($_GET['post']) ? $_GET['post'] : 'new') . '"'
								);
							?>
						</div>
					</div>

				</div>

				<div class="media-frame-content ecwid-store-editor appearance">

					<div class="pure-control-group pb-views">
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
									name="grid_rows"
									class="number"
									value="<?php echo esc_attr(get_option('ecwid_pb_productspercolumn_grid')); ?>"
									/>
							</div>
							<div class="bottom">
								<div class="ruler"></div>
								<input
									type="text"
									size="2"
									name="grid_columns"
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
									name="list_rows"
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
									name="table_rows"
									class="number"
									value="<?php echo esc_attr(get_option('ecwid_pb_productsperpage_table')); ?>"
									/>
							</div>
						</div>
						<p class="note pb-note"><?php printf( __( 'Here you can control how many products will be displayed per page. These options define maximum values. If there is not enough space to show all product columns, %s will adapt the number of columns to hold all products.', 'ecwid-shopping-cart' ), Ecwid_WL::get_brand() ); ?></p>
					</div>

					<hr class="after-pb" />

					<div class="pure-control-group params-list default-category-id">

					<?php if ($categories): ?>
					<label for="ecwid_default_category_id">
						<?php _e('Category shown by default', 'ecwid-shopping-cart'); ?>
					</label>


					<div class="value">

						<select name="default_category_id" id="ecwid_default_category_id">
							<option value=""><?php _e('Store root category', 'ecwid-shopping-cart'); ?></option>
							<?php foreach ($categories as $category): ?>
								<option
									value="<?php echo esc_attr($category->id); ?>"
									<?php if ($category->id == get_option('ecwid_default_category_id')): ?>
										selected="selected"
									<?php endif; ?>
									>
									<?php echo esc_html($category->path); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<?php endif; ?>
					</div>

					<div class="pure-control-group params-list">
						<label for="ecwid_pb_categoriesperrow">
							<?php _e('Number of categories per row', 'ecwid-shopping-cart'); ?>
						</label>
						<input
							id="ecwid_pb_categoriesperrow"
							name="categories_per_row"
							type="text"
							class="number"
							value="<?php echo esc_attr(get_option('ecwid_pb_categoriesperrow')); ?>"
							/>
					</div>

					<div class="pure-control-group params-list">
						<label for="ecwid_pb_defaultview">
							<?php _e('Default view mode on product pages', 'ecwid-shopping-cart'); ?>
						</label>
						<select	id="ecwid_pb_defaultview" name="category_view">
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

					<div class="pure-control-group params-list">
						<label for="ecwid_pb_searchview">
							<?php _e('Default view mode on search results', 'ecwid-shopping-cart'); ?>
						</label>

						<select	id="ecwid_pb_searchview" name="search_view">
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
				</div>

				<div class="media-frame-toolbar">
					<div class="media-toolbar">
						<div class="media-toolbar-secondary">
							<?php if (get_ecwid_store_id() != ECWID_DEMO_STORE_ID): ?>
								<div class="store-id"><?php _e('Store ID', 'ecwid-shopping-cart'); ?>: <?php echo esc_attr(get_ecwid_store_id()); ?></div>
							<?php else: ?>
								<div class="store-id"><?php _e('Demo store', 'ecwid-shopping-cart'); ?></div>
							<?php endif; ?>
								<div class="setting-link">
									<a target="_blank" href="admin.php?page=ecwid"><?php _e('Open store dashboard', 'ecwid-shopping-cart'); ?>
								</div>
						</div>
						<div class="media-toolbar-primary add-store">
							<a href="#" class="button media-button button-primary button-large media-button-select"><?php _e('Insert into page'); ?></a>
						</div>
						<div class="media-toolbar-primary store-settings">
							<a href="#" class="button media-button button-primary button-large media-button-select"><?php _e('Update'); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="media-modal-backdrop"></div>
</div>
