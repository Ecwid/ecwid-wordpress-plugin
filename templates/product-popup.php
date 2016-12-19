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
                <input type="search" id="product-search-input" name="s" value="" placeholder="<?php _e( 'Search products', 'ecwid-shopping-cart' ); ?>">
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

<script type="text/javascript">

jQuery(document).ready(function() {

    var popup = function() {
        return jQuery('#ecwid-product-popup-content');
    };

    ecwidSpwSearchProducts = function() {

        var data = {
            'action': 'ecwid-search-products'
        };

        var params = popup().data('searchParams');

        if (params) {
            if (params.keyword) {
                data.keyword = params.keyword;
            }

            if (params.sortBy) {
                data.sortBy = params.sortBy;
            }

            if (params.page) {
                data.page = params.page;
            }
        }

        jQuery('#search-submit').addClass('searching');

        jQuery.getJSON(ajaxurl, data, function(data) {

            if (Math.ceil(data.total / data.limit) < getSearchParams().page) {
                params = getSearchParams();
                params.page = 1;
                setSearchParams(params);
            }

            var enabledPageTemplate = wp.template( 'pagination-button-enabled' );
            var disabledPageTemplate = wp.template( 'pagination-button-disabled' );

            var prevPages = '';
            if (getSearchParams() && getSearchParams().page == 1) {
                prevPages = disabledPageTemplate( { symbol: '«' } ) + disabledPageTemplate( { symbol: '‹' } );
            } else {
                prevPages = enabledPageTemplate({
                        'symbol': '«',
                        'name': 'first',
                        'label': '<?php _e( 'First Page', 'ecwid-shopping-cart' ); ?>'
                    }) + enabledPageTemplate({
                        'symbol': '‹',
                        'name': 'prev',
                        'label': '<?php _e( 'Previous Page', 'ecwid-shopping-cart' ); ?>'
                    });
            }

            var nextPages = '';
            if (getSearchParams().page >= Math.ceil(data.total / data.limit)) {
                nextPages = disabledPageTemplate( { symbol: '›' } ) + disabledPageTemplate( { symbol: '»' } );
            } else {
                nextPages = enabledPageTemplate({
                        'symbol': '›',
                        'name': 'next',
                        'label': '<?php _e( 'Next Page', 'ecwid-shopping-cart' ); ?>'
                    }) + enabledPageTemplate({
                        'symbol': '»',
                        'name': 'last',
                        'label': '<?php _e( 'Last Page', 'ecwid-shopping-cart' ); ?>',
                        'page': Math.ceil(data.total / data.limit)
                    });
            }

            var formTemplate = wp.template( 'add-product-form' );

            var tableTemplate = wp.template( 'products-list' );

            var tableHTML = tableTemplate();

            jQuery('.media-frame-content.ecwid-add-product.add-product').empty().append(
                formTemplate( {
                    'tableHTML' : tableHTML,
                    'page': data.offset / data.limit + 1,
                    'total_pages': Math.ceil(data.total / data.limit),
                    'total_items': data.total + ' items',
                    'prev_pages': prevPages,
                    'next_pages': nextPages
                })
            );

            if (data.total > 0) {
                for (var i = 0; i < data.items.length; i++) {
                    addProduct(data.items[i]);
                }
            } else {
                showEmpty(params.keyword);
            }

            renderSearchParams();
            assignHandlers();
            setCurrentProduct(null);
            jQuery('#search-submit').removeClass('searching');
        });
    }

    renderAddProductForm();
});

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
                                        <input type="checkbox" name="picture" data-display-option="picture">
                                        <span><?php _e( 'Picture', 'ecwid-shopping-cart' ); ?></span>
                                    </label>
                                </div>
                                <div class="pure-control-group">
                                    <label>
                                        <input type="checkbox" name="title" data-display-option="title">
                                        <span><?php _e( 'Title', 'ecwid-shopping-cart' ); ?></span>
                                    </label>
                                </div>
                                <div class="pure-control-group">
                                    <label>
                                        <input type="checkbox" name="price" data-display-option="price">
                                        <span><?php _e( 'Price', 'ecwid-shopping-cart' ); ?></span>
                                    </label>
                                </div>
                            </div>
                            <div class="widget-settings__right">
                                <div class="pure-control-group">
                                    <label>
                                        <input type="checkbox" name="options" data-display-option="options">
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
                                        <input type="checkbox" name="add-to-bag" data-display-option="addtobag">
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
                                        <input type="checkbox" name="widget_frame" data-shortcode-attribute="show_border">
                                        <span><?php _e( 'Widget frame', 'ecwid-shopping-cart' ); ?></span>
                                    </label>
                                </div>
                                <div class="pure-control-group">
                                    <label>
                                        <input type="checkbox" name="widget_frame" data-shortcode-attribute="center_align">
                                        <span><?php _e( 'Center align widget', 'ecwid-shopping-cart' ); ?></span>
                                    </label>
                                </div>
                            </div>
                            <div class="widget-settings__right">
                                <div class="pure-control-group">
                                    <label>
                                        <input type="checkbox" name="outside_widget" data-shortcode-attribute="show_price_on_button">
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