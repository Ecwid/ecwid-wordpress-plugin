
<?php if ( $main_category ): ?>
    
<h1 class="category-name"><?php echo $main_category->name; ?></h1>
<?php if ( $main_category->description ): ?>
<div class="category-description"><?php echo $main_category->description; ?></div>
<?php endif; ?>

<?php endif; ?>

<?php if ( $categories ): foreach ( $categories as $category ): ?>
<div class="category-<?php echo $category->id; ?>">
	<a href="<?php $cat = Ecwid_Category::get_by_id( $category->id ); echo $cat->get_link( $this->store_base_url ); ?>">
        <?php echo EcwidPlatform::esc_html( $category->name ); ?> 
    </a>
</div>
<?php endforeach; endif; ?>

<?php if ( $products ): foreach ( $products as $product ): ?>

<?php $product = Ecwid_Product::get_by_id($product->id); ?>
<div class="product-<?php echo $product->id; ?>">
	<span class="product-name">
		<a href="<?php echo $product->get_link( $this->store_base_url ); ?>">
            <?php echo EcwidPlatform::esc_html( $product->name ); ?> 
        </a>
	</span>
	<span class="product-price">
        <?php echo $product->defaultDisplayedPrice . ' ' . $formats->currency; ?> 
    </span>
</div>

<?php endforeach; endif; ?>
