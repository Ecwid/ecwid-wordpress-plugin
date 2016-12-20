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
    <tr>
        <td colspan="3">
            <div class="empty-page">
                <div class="empty-page__title"><?php _e( 'Nothing found for <span class="empty-page__term">"{{ data.term }}"</span>', 'ecwid-shopping-cart' ); ?></div>
                <div class="empty-page__suggestions">
                    <div class="empty-page__suggestions-title"><?php _e( 'Suggestions:', 'ecwid-shopping-cart' ); ?></div>
                    <ul class="empty-page__suggestions-list">
                        <li><?php _e( 'Make sure that all words are spelled correctly', 'ecwid-shopping-cart' ); ?></li>
                        <li><?php _e( 'Try to search by SKU', 'ecwid-shopping-cart' ); ?></li>
                        <li><a href="#" id="ecwid-reset-search"><?php _e( 'Browse all products', 'ecwid-shopping-cart' ); ?></a></li>
                    </ul>
                </div>
            </div>
        </td>
    </tr>
</script>

<div id="ecwid-product-popup-content">
    <div class="media-modal wp-core-ui">
        <div class="media-modal-content" data-mode="add-product" data-active-dialog="add-product">
            <a class="media-modal-close" href="#" title="Close"><span class="media-modal-icon"></span></a>
            <div class="media-frame wp-core-ui">
                <div class="media-frame-menu">
                    <div class="media-menu">
                        <a href="#" class="media-menu-item active" data-content="add-product"><?php _e( 'Add Product', 'ecwid-shopping-cart' ); ?></a>
                        <a href="#" class="media-menu-item" data-content="customize"><?php _e( 'Customize widget', 'ecwid-shopping-cart' ); ?></a>
                    </div>
                </div>
                <div class="media-frame-title add-product active">
                    <h1><?php _e( 'Add Product', 'ecwid-shopping-cart' ); ?><span class="dashicons dashicons-arrow-down"></span></h1>
                </div>

                <div class="media-frame-title customize">
                    <h1><?php _e( 'Customize widget', 'ecwid-shopping-cart' ); ?><span class="dashicons dashicons-arrow-down"></span></h1>
                </div>

                <div class="media-frame-content ecwid-add-product add-product">
                </div>

                <div class="media-frame-content ecwid-add-product customize">
                    <div class="store-settings-wrapper ecwid-search ecwid-minicart ecwid-categories" data-ecwid-widget-hover="">
                        <div class="widget-settings">
                            <h3><?php _e( 'Choose product properties to display in widget:', 'ecwid-shopping-cart' ); ?></h3>
                            <div class="widget-settings__left">
                                <div class="pure-control-group">
                                    <label>
                                        <input type="checkbox" checked="checked" name="picture" data-display-option="picture">
                                        <span><?php _e( 'Picture', 'ecwid-shopping-cart' ); ?></span>
                                    </label>
                                </div>
                                <div class="pure-control-group">
                                    <label>
                                        <input type="checkbox" checked="checked" name="title" data-display-option="title">
                                        <span><?php _e( 'Title', 'ecwid-shopping-cart' ); ?></span>
                                    </label>
                                </div>
                                <div class="pure-control-group">
                                    <label>
                                        <input type="checkbox" checked="checked" name="price" data-display-option="price">
                                        <span><?php _e( 'Price', 'ecwid-shopping-cart' ); ?></span>
                                    </label>
                                </div>
                            </div>
                            <div class="widget-settings__right">
                                <div class="pure-control-group">
                                    <label>
                                        <input type="checkbox" checked="checked" name="options" data-display-option="options">
                                        <span><?php _e( 'Options', 'ecwid-shopping-cart' ); ?></span>
                                    </label>
                                </div>
                                <div class="pure-control-group">
                                    <label>
                                        <input type="checkbox" name="quantity" data-display-option="qty">
                                        <span><?php _e( 'Quantity', 'ecwid-shopping-cart' ); ?></span>
                                    </label>
                                </div>
                                <div class="pure-control-group">
                                    <label>
                                        <input type="checkbox" checked="checked" name="add-to-bag" data-display-option="addtobag">
                                        <span><?php _e( '«Add to bag» button', 'ecwid-shopping-cart' ); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="widget-settings">
                            <h3><?php _e( 'Choose CSS style options for widget:', 'ecwid-shopping-cart' ); ?></h3>
                            <div class="widget-settings__left">
                                <div class="pure-control-group">
                                    <label>
                                        <input type="checkbox" checked="checked" name="widget_frame" data-shortcode-attribute="show_border">
                                        <span><?php _e( 'Add border', 'ecwid-shopping-cart' ); ?></span>
                                    </label>
                                </div>
                                <div class="pure-control-group">
                                    <label>
                                        <input type="checkbox" checked="checked" name="widget_frame" data-shortcode-attribute="center_align">
                                        <span><?php _e( 'Center align on a page', 'ecwid-shopping-cart' ); ?></span>
                                    </label>
                                </div>
                            </div>
                            <div class="widget-settings__right">
                                <div class="pure-control-group">
                                    <label>
                                        <input type="checkbox" checked="checked" name="outside_widget" data-shortcode-attribute="show_price_on_button">
                                        <span><?php _e( 'Outside widget «Add to bag button»', 'ecwid-shopping-cart' ); ?></span>
                                    </label>
                                </div>
                            </div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="media-modal-backdrop"></div>
</div>