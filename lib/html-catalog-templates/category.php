
<?php if ( $main_category ): ?>
    
<h1 class="category-name"><?php echo esc_html( $main_category->name ); ?></h1>
<?php if ( $main_category->description ): ?>
<div class="category-description"><?php echo wp_kses_post( $main_category->description ); ?></div>
<?php endif; ?>

<?php endif; ?>

<?php if ( $categories ): foreach ( $categories as $category ): ?>
<div class="category-<?php echo esc_attr( $category->id ); ?>">
	<a href="<?php $cat = Ecwid_Category::get_by_id( $category->id ); echo esc_url( $cat->get_link( $this->store_base_url ) ); ?>">
        <?php echo esc_html( $category->name ); ?> 
    </a>
</div>
<?php endforeach; endif; ?>

<?php if ( $products ): foreach ( $products as $product ): ?>

<?php $product = Ecwid_Product::get_by_id($product->id); ?>
<div class="product-<?php echo esc_attr( $product->id ); ?>">
	<span class="product-name">
		<a href="<?php echo esc_url( $product->get_link( $this->store_base_url ) ); ?>">
            <?php echo esc_html( $product->name ); ?> 
        </a>
	</span>
	<span class="product-price">
        <?php echo esc_html( $product->defaultDisplayedPrice . ' ' . $formats->currency ); ?> 
    </span>
</div>

<?php endforeach; endif; ?>
