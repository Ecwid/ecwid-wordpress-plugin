<h1>Import your products from WooCommerce to Ecwid</h1>

<p>We found you have a WooCommerce installed. Your WooCommerce store has 17 products and 4 categories. Here, we will help you copy those products to your Ecwid store.</p>

<h2>Your WooCommerce store has:</h2>
<ul>
    <li>
        Products: <?php echo $this->importer->count_woo_products(); ?>
    </li>
    <li>
        Categories: <?php echo $this->importer->count_woo_categories(); ?>
    </li>
</ul>
<h2>Your Ecwid store has:</h2>
<ul>
    <li>
        Products: <?php echo $this->importer->count_ecwid_products(); ?>
    </li>
    <li>
        Categories: <?php echo $this->importer->count_ecwid_categories(); ?>
    </li>
</ul>

<p>
    The import will copy WooCommerce products and categories to your Ecwid store. New products will be created in your Ecwid store. Existing Ecwid products in your store will remain unchanged. So, your Ecwid store will have after import:
</p>
<ul>
    <li>
        Products: <?php echo $this->importer->count_woo_products() + $this->importer->count_ecwid_products(); ?>
    </li>
    <li>
        Categories: <?php echo $this->importer->count_woo_categories() + $this->importer->count_ecwid_categories(); ?>
    </li>
</ul>

<div class="importer-state importer-state-woo-initial">
    <button class="button button-primary" id="ecwid-importer-woo-go">Import from Woo to Ecwid</button>
</div>