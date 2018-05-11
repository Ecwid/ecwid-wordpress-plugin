<script type="text/template" id="tmpl-product-in-list">

    <tr id="product-{{ data.id }}">
        <td class="product-thumb column-product-thumb has-row-actions" data-colname="Product">
            <div><img src="{{ data.image_url }}" alt=""></div>
        </td>
        <td class="product-name column-product-name has-row-actions column-primary" data-colname="Product Name">
            <div>{{ data.name }}</div>
        </td>
        <td class="sku column-sku has-row-actions" data-colname="SKU">
            <div>{{ data.sku }}</div>
        </td>
    </tr>
</script>

<script type="text/template" id="tmpl-products-list">
    <table class="wp-list-table widefat fixed striped products">
        <thead>
        <tr>
            <td id="cb" class="manage-column column-cb check-column"></td>
            <th scope="col" id="name" class="manage-column column-name column-primary sortable">
                <a href="">
                    <span><?php _e( 'Name', 'ecwid-shopping-cart' ); ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th scope="col" id="sku" class="manage-column column-sku sortable">
                <a href="">
                    <span><?php _e( 'SKU', 'ecwid-shopping-cart' ); ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

</script>

<script type="text/template" id="tmpl-add-product-form">
    <form action="">
        <p class="products-search">
            <span class="search-input">
                <label class="screen-reader-text" for="product-search-input">
                    <?php _e( 'Search', 'ecwid-shopping-cart' ); ?>
                </label>
                <input type="search" id="product-search-input" name="s" value="" placeholder="<?php _e( 'Title or SKU', 'ecwid-shopping-cart' ); ?>">
            </span>
            <span class="search-button">
                <button type="submit" id="search-submit" class="button">
                    <span class="button-text"><?php _e( 'Search', 'ecwid-shopping-cart' ); ?></span>
                    <img class="searching-icon" src="<?php echo(esc_attr(ECWID_PLUGIN_URL)); ?>/images/download.gif" />
                </button>
                <!--input type="submit" id="search-submit" class="button" value="<?php _e( 'Search', 'ecwid-shopping-cart' ); ?>"-->
            </span>
        </p>
    </form>

    {{{ data.tableHTML }}}

    <div class="tablenav bottom">
        <div class="tablenav-pages">
            <span class="displaying-num">{{ data.total_items }}</span>
            <span class="pagination-links">
                    {{{ data.prev_pages }}}
                    <span class="paging-input">
                    <label for="current-page-selector" class="screen-reader-text"><?php _e( 'Current Page', 'ecwid-shopping-cart' ); ?></label>
                    <span class="tablenav-paging-text">{{ data.page }} of <span class="total-pages">{{ data.total_pages }}</span></span></span>
                    {{{ data.next_pages }}}

                </span>
        </div>
    </div>
</script>

<script type="text/template" id="tmpl-pagination-button-enabled">
<a class="{{ data.name }}-page" href="" data-page="{{ data.page }}">
    <span class="screen-reader-text">{{ data.label }}<?php _e( 'Next page', 'ecwid-shopping-cart' ); ?></span>
    <span aria-hidden="true">{{ data.symbol }}</span>
</a>

</script>

<script type="text/template" id="tmpl-pagination-button-disabled">
<span class="tablenav-pages-navspan" aria-hidden="true">{{ data.symbol }}</span>
</script>

<script type="text/template" id="tmpl-no-products">
    <tr class="empty">
        <td colspan="3">
            <div class="empty-page">
                <div class="empty-page__title"><?php _e( 'Nothing found for <span class="empty-page__term">"{{ data.term }}"</span>', 'ecwid-shopping-cart' ); ?></div>
                <div class="empty-page__suggestions">
                    <?php _e( 'Try another search.', 'ecwid-shopping-cart' ); ?>
                    <a href="#" id="ecwid-reset-search"><?php _e( 'Browse all products.', 'ecwid-shopping-cart' ); ?></a>
                </div>
            </div>
        </td>
    </tr>
</script>

<script type="text/template" id="tmpl-checkbox-option">
    <label class="checkbox-option">
        <span>
            <input type="checkbox" checked="checked" name="{{ data.name }}" {{{ data.additionalAttributes }}}>
        </span>
        <span class="label">
            {{ data.label }}
        </span>
    </label>
</script>

<script type="text/template" id="tmpl-selected-product">
<div class="ecwid-selected-product">
    <div class="ecwid-selected-product-image">
        <img src="{{ data.thumb }}">
    </div>
    <div class="ecwid-selected-product-details">
        <div class="ecwid-selected-product-name">{{ data.name }}</div>
        <div class="ecwid-selected-product-sku">{{ data.sku }}</div>
        <div class="ecwid-selected-product-button">
            <button class="button button-secondary" id="choose-another-product"><?php _e(' Choose another product', 'ecwid-shopping-cart' ); ?></button>
        </div>
    </div>
</div>
</script>

<div id="ecwid-product-popup-content">
    <div class="media-modal wp-core-ui">
        <div class="media-modal-content" data-mode="add-product" data-active-dialog="add-product">
            <a class="media-modal-close" href="#" title="Close"><span class="media-modal-icon"></span></a>
            <div class="media-frame wp-core-ui">
                <div class="media-frame-menu">
                    <div class="media-menu">
                        <a href="#" class="media-menu-item active" data-content="add-product"><?php _e( 'Choose Product', 'ecwid-shopping-cart' ); ?></a>
                        <a href="#" class="media-menu-item" data-content="selected-product"><?php _e( 'Selected Product', 'ecwid-shopping-cart' ); ?></a>
                        <a href="#" class="media-menu-item" data-content="customize"><?php _e( 'Customize widget', 'ecwid-shopping-cart' ); ?></a>
                    </div>
                </div>
                <div class="media-frame-title selected-product">
                    <h1><?php _e( 'Selected Product', 'ecwid-shopping-cart' ); ?><span class="dashicons dashicons-arrow-down"></span></h1>
                </div>
                
                <div class="media-frame-title add-product active">
                    <h1><?php _e( 'Choose Product', 'ecwid-shopping-cart' ); ?><span class="dashicons dashicons-arrow-down"></span></h1>
                </div>

                <div class="media-frame-title customize">
                    <h1><?php _e( 'Customize widget', 'ecwid-shopping-cart' ); ?><span class="dashicons dashicons-arrow-down"></span></h1>
                </div>

                <div class="media-frame-content ecwid-selected-product selected-product">
                </div>
                
                <div class="media-frame-content ecwid-add-product add-product">
                </div>

                <div class="media-frame-content ecwid-add-product customize">
                    <div class="store-settings-wrapper ecwid-search ecwid-minicart ecwid-categories" data-ecwid-widget-hover="">
                        <div class="widget-settings display-options">
                            <h3><?php _e( 'Choose product properties to display in widget', 'ecwid-shopping-cart' ); ?></h3>
                            <div class="widget-settings__left"></div>
                            <div class="widget-settings__right"></div>
                            <script type="text/javascript">
                                jQuery(document).ready(function() {
                                    ecwidRenderCheckboxOption.section = 'display-options';

                                    ecwidRenderCheckboxOption({
                                        'section': 'display-options',
                                        'name': 'picture',
                                        'label': '<?php _e( 'Picture', 'ecwid-shopping-cart' ); ?>'
                                    });
                                    ecwidRenderCheckboxOption({
                                        'section': 'display-options',
                                        'name': 'options',
                                        'label': '<?php _e( 'Options', 'ecwid-shopping-cart' ); ?>'
                                    });
                                    ecwidRenderCheckboxOption({
                                        'section': 'display-options',
                                        'name': 'title',
                                        'label': '<?php _e( 'Title', 'ecwid-shopping-cart' ); ?>'
                                    });
                                    ecwidRenderCheckboxOption({
                                        'section': 'display-options',
                                        'name': 'quantity',
                                        'label': '<?php _e( 'Quantity', 'ecwid-shopping-cart' ); ?>',
                                        'displayOptionName': 'qty'
                                    });
                                    ecwidRenderCheckboxOption({
                                        'section': 'display-options',
                                        'name': 'price',
                                        'label': '<?php _e( 'Price', 'ecwid-shopping-cart' ); ?>'
                                    });
                                    ecwidRenderCheckboxOption({
                                        'section': 'display-options',
                                        'name': 'addtobag',
                                        'label': '<?php _e( '«Buy now» button', 'ecwid-shopping-cart' ); ?>'
                                    });
                                });
                            </script>

                        </div>
                        <div class="widget-settings shortcode-attributes">
                            <h3><?php _e( 'Appearance', 'ecwid-shopping-cart' ); ?></h3>

                            <div class="widget-settings__left"></div>
                            <div class="widget-settings__right"></div>

                            <script type="text/javascript">
                                jQuery(document).ready(function() {
                                    ecwidRenderCheckboxOption.nextTarget = 'left';
                                    ecwidRenderCheckboxOption.section = 'shortcode-attributes';

                                    ecwidRenderCheckboxOption({
                                        'section': 'shortcode-attributes',
                                        'name': 'show_border',
                                        'label': '<?php _e( 'Add border', 'ecwid-shopping-cart' ); ?>'
                                    });
                                    ecwidRenderCheckboxOption({
                                        'section': 'shortcode-attributes',
                                        'name': 'show_price_on_button',
                                        'label': '<?php _e( 'Show price inside the "Buy now" button', 'ecwid-shopping-cart' ); ?>'
                                    });
                                    ecwidRenderCheckboxOption({
                                        'section': 'shortcode-attributes',
                                        'name': 'center_align',
                                        'label': '<?php _e( 'Center align on a page', 'ecwid-shopping-cart' ); ?>'
                                    });

                                });
                            </script>
                        </div>
                    </div>
                </div>

                <div class="media-frame-toolbar">
                    <div class="media-toolbar">
                        <div class="media-toolbar-primary add-product">
                            <a target="_blank" class="toolbar-link customize-appearance" data-content="customize" href="#"><?php _e( 'customize appearance', 'ecwid-shopping-cart' ); ?></a>
                            <a target="_blank" class="toolbar-link add-product" data-content="add-product" style="display: none" href="#"><?php _e( 'select product', 'ecwid-shopping-cart' ); ?></a>
                            <a href="#" class="button media-button button-primary button-large media-button-select"><?php _e( 'Insert', 'ecwid-shopping-cart' ); ?></a>
                        </div>
                        <div class="media-toolbar-primary selected-product">
                            <a target="_blank" class="toolbar-link customize-appearance" data-content="customize" href="#"><?php _e( 'customize appearance', 'ecwid-shopping-cart' ); ?></a>
                            <a target="_blank" class="toolbar-link add-product" data-content="selected-product" style="display: none" href="#"><?php _e( 'selected product', 'ecwid-shopping-cart' ); ?></a>
                            <a href="#" class="button media-button button-primary button-large media-button-update"><?php _e( 'Update', 'ecwid-shopping-cart' ); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="media-modal-backdrop"></div>
</div>