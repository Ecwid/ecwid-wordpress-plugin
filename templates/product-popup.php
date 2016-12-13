<script type="text/template" id="tmpl-product-in-list">

    <tr id="product-{{ data.id }}">
        <td class="product-thumb column-product-thumb has-row-actions" data-colname="Product">
            <div><img src="{{ data.image_url }}" alt=""></div>
        </td>
        <td class="product-name column-product-name has-row-actions column-primary" data-colname="Product Name">
            <div>{{ data.name }}</div>
        </td>
        <td class="sku column-sku" data-colname="SKU">
            <div>{{ data.sku }}</div>
        </td>
    </tr>
</script>

<script type="text/template" id="tmpl-products-list">
    <table class="wp-list-table widefat fixed striped products">
        <thead>
        <tr>
            <td id="cb" class="manage-column column-cb check-column"></td>
            <th scope="col" id="name" class="manage-column column-name column-primary sortable desc">
                <a href="">
                    <span><?php _e( 'Name', 'ecwid-shopping-cart' ); ?></span>
                    <span class="sorting-indicator"></span>
                </a>
            </th>
            <th scope="col" id="sku" class="manage-column column-sku">
                <?php _e( 'SKU', 'ecwid-shopping-cart' ); ?>
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
            <label class="screen-reader-text" for="product-search-input">
                <?php _e( 'Search', 'ecwid-shopping-cart' ); ?>
            </label>
            <input type="search" id="product-search-input" name="s" value="">
            <input type="submit" id="search-submit" class="button" value="<?php _e( 'Search', 'ecwid-shopping-cart' ); ?>">
        </p>
    </form>

    {{{ data.tableHTML }}}

    <div class="tablenav bottom">
        <div class="tablenav-pages">
            <span class="displaying-num">{{ data.total_items }}</span>
            <span class="pagination-links">
                    <span class="tablenav-pages-navspan" aria-hidden="true">«</span>
                    <span class="tablenav-pages-navspan" aria-hidden="true">‹</span>
                    <span class="paging-input">
                    <label for="current-page-selector" class="screen-reader-text"><?php _e( 'Current Page', 'ecwid-shopping-cart' ); ?></label>
                    <input class="current-page" id="current-page-selector" type="text"
                           name="paged" value="{{ data.current_page }}" size="2" aria-describedby="table-paging">
                    <span class="tablenav-paging-text"> of <span class="total-pages">{{ data.total_pages }}</span></span></span>
                    <a class="next-page" href="">
                        <span class="screen-reader-text"><?php _e( 'Next page', 'ecwid-shopping-cart' ); ?></span>
                        <span aria-hidden="true">›</span>
                    </a>
                    <a class="last-page" href="">
                        <span class="screen-reader-text"><?php _e( 'Last page', 'ecwid-shopping-cart' ); ?></span>
                        <span aria-hidden="true">»</span>
                    </a>
                </span>
        </div>
    </div>
</script>

<script type="text/javascript">

    addProduct = function() {
        var productTemplate = wp.template('product-in-list');

        jQuery('.wp-list-table.products tbody').append(productTemplate(
            {'name': 'Test', 'image_url': '', 'sku': 'SKUTEST', 'id': 12345}

        ));
    }
    jQuery(document).ready(function() {
        renderAddProductForm();
    });
    addTable = function() {
        tableTemplate = wp.template( 'products-list' );

        jQuery( '.ecwid-add-product.add-product' ).append(tableTemplate(

        ));
    }

    renderAddProductForm = function() {
        var formTemplate = wp.template( 'add-product-form' );

        var tableTemplate = wp.template( 'products-list' );

        var tableHTML = tableTemplate ();

        jQuery('.media-frame-content.ecwid-add-product.add-product').append(
            formTemplate( {
                'tableHTML' : tableHTML,
                'current_page': 2,
                'total_pages': 10,
                'total_items': '12345 items'
            })
        );
        addProduct();
        addProduct();
        addProduct();

    }


</script>

<div id="ecwid-product-popup-content" class="open">
    <div class="media-modal wp-core-ui">
        <div class="media-modal-content" data-mode="add-product" data-active-dialog="add-product">
            <a class="media-modal-close" href="#" title="Close"><span class="media-modal-icon"></span></a>
            <div class="media-frame wp-core-ui">

                <div class="media-frame-title add-product">
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
                            <h3>Choose product properties to display in widget:</h3>
                            <div class="widget-settings__left">
                                <div class="pure-control-group">
                                    <label data-ecwid-widget="picture">
                                        <input type="checkbox" name="picture" id="picture">
                                        Picture
                                    </label>
                                </div>
                                <div class="pure-control-group">
                                    <label data-ecwid-widget="title">
                                        <input type="checkbox" name="title" id="title">
                                        Title
                                    </label>
                                </div>
                                <div class="pure-control-group">
                                    <label data-ecwid-widget="price">
                                        <input type="checkbox" name="price" id="price">
                                        Price
                                    </label>
                                </div>
                            </div>
                            <div class="widget-settings__right">
                                <div class="pure-control-group">
                                    <label data-ecwid-widget="options">
                                        <input type="checkbox" name="options" id="options">
                                        Options
                                    </label>
                                </div>
                                <div class="pure-control-group">
                                    <label data-ecwid-widget="quantity">
                                        <input type="checkbox" name="quantity" id="quantity">
                                        Quantity
                                    </label>
                                </div>
                                <div class="pure-control-group">
                                    <label data-ecwid-widget="add_to_bag">
                                        <input type="checkbox" name="add_to_bag" id="add_to_bag">
                                        «Add to bag» button
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="widget-settings">
                            <h3>Choose CSS style options for widget:</h3>
                            <div class="widget-settings__left">
                                <div class="pure-control-group">
                                    <label data-ecwid-widget="widget_frame">
                                        <input type="checkbox" name="widget_frame" id="widget_frame">
                                        Widget frame
                                    </label>
                                </div>
                            </div>
                            <div class="widget-settings__right">
                                <div class="pure-control-group">
                                    <label data-ecwid-widget="outside_widget">
                                        <input type="checkbox" name="outside_widget" id="outside_widget">
                                        Outside widget  «Add to bag button»
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="media-frame-toolbar">
                    <div class="media-toolbar">
                        <div class="media-toolbar-primary add-product">
                            <a target="_blank" class="customize-appearance" href="admin.php?page=ecwid">customize appearance </a>
                            <a href="#" class="button media-button button-primary button-large media-button-select">Insert</a>
                        </div>
                        <div class="media-toolbar-primary store-settings">
                            <a href="#" class="button media-button button-primary button-large media-button-select">Update</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="media-modal-backdrop"></div>
</div>