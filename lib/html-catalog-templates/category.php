
<?php if ( $main_category ): ?>
    
<h1 class="category-name"><?php echo $main_category->name; ?></h1>
<?php if ( $main_category->description ): ?>
<div class="category-description"><?php echo $main_category->description; ?></div>
<?php endif; ?>

<?php endif; ?>

<?php if ( $categories ): foreach ( $categories as $category ): ?>
<div class="category-<?php echo $category->id; ?>">
	<a href="<?php echo Ecwid_Store_Page::get_category_url( $category->id ); ?>">
        <?php echo EcwidPlatform::esc_html( $category->name ); ?> 
    </a>
</div>
<?php endforeach; endif; ?>

<?php if ( $products ): foreach ( $products as $product ): ?>

<div class="product-<?php echo $product->id; ?>">
	<span class="product-name">
		<a href="<?php echo Ecwid_Store_Page::get_product_url( $product->id ); ?>">
            <?php echo EcwidPlatform::esc_html( $product->name ); ?> 
        </a>
	</span>
	<span class="product-price">
        <?php echo $product->price . ' ' . $formats->currency; ?> 
    </span>
</div>

<?php endforeach; endif; ?>
