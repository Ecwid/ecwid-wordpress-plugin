
<div itemscope itemtype="http://schema.org/Product">

	<h1 itemprop="name"><?php echo esc_html( $product->name ); ?></h1>
	<p itemprop="sku"><?php echo esc_html( $product->sku ); ?></p>
	<img itemprop="image" src="<?php echo esc_attr( $product->originalImageUrl ); ?>" alt="<?php echo esc_attr( $product->name  . ' ' . $product->sku); ?>" />
	<div itemprop="description"><?php echo isset( $product->seoDescription )&& !empty( $product->seoDescription ) ? wp_kses_post( $product->seoDescription ) : wp_kses_post( $product->description ); ?></div>
    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
		<span itemprop="price" content="<?php echo esc_html( $product->defaultDisplayedPrice ); ?>"><?php
			echo esc_html( 
            	$formats->currencyPrefix . $product->defaultDisplayedPrice . $formats->currencySuffix
			); 
		?></span>
		<span itemprop="priceCurrency" content="<?php echo esc_attr( $formats->currency ); ?>"></span>
		<?php if ( !isset( $product->quantity) || $product->quantity > 0): ?><link itemprop="availability" href="http://schema.org/InStock" />In stock<?php endif; ?> 
		<link itemprop="url" href="<?php if( !empty($product->seo_link) ) { echo esc_attr( $product->seo_link ); } else { echo esc_attr( $product->url ); }?>" />
	</div>
	<?php if ( isset( $product->attributes ) && is_array( $product->attributes ) && !empty( $product->attributes) ): ?> 
	<div class="attributes">
	<?php foreach ( $product->attributes as $attribute ):
	  ?>    <div><?php 
			echo esc_html( $attribute->name ) . ':';
			if ( isset( $attribute->internalName ) && $attribute->internalName == 'Brand'
			     || 
				 isset( $attribute->type ) && $attribute->type == 'BRAND'
			):
			?><span itemprop="brand"><?php echo esc_html( $attribute->value ); ?></span><?php 
			else: 
				echo esc_html( $attribute->value ); 
			endif; 
		?></div>
	<?php 
		endforeach; ?></div>
	<?php endif; ?>
	<?php if ( isset( $product->options) && is_array( $product->options ) && !empty( $product->options ) ): ?>
	<?php foreach ( $product->options as $option ): ?> 
	<div class="option">
		<span class="name"><?php echo esc_attr( $option->name ); ?></span>
		<span class="input"><?php 
			if ( $option->type == 'TEXTAREA' ):
		?>
			<textarea name="<?php echo esc_attr( $option->name ); ?>"></textarea>
			<?php elseif ( $option->type == 'SELECT' ): ?> 
			<select name="<?php echo esc_attr( $option->name ); ?>"><?php 
				foreach( $option->choices as $param ): ?> 
				<option value="<?php echo esc_attr( $param->text ); ?>"><?php
					echo esc_html( $param->text );
					echo ' ';
					echo esc_html( $param->priceModifier );
					?></option><?php endforeach; 
			?> 
			</select><?php 
			elseif ( $option->type == 'RADIO' ): 
				foreach ( $option->choices as $param ): ?> 
			<?php echo sprintf(
				'<input type="radio" name="%s" value="%s" />%s (%s)',
				esc_attr( $option->name ),
				esc_attr( $param->text ),
				esc_html( $param->text ),
				esc_html( $param->priceModifier )
			); ?>
			<?php endforeach; ?>
			<?php elseif ( $option->type == 'CHECKBOX'): foreach ( $option->choices as $param ): ?> 
			<?php echo sprintf(
					'<input type="checkbox" name="%s[]" value="%s" />%s (%s)',
					esc_attr( $option->name ),
					esc_attr( $param->text ),
					esc_html( $param->text ),
					esc_html( $param->priceModifier )
				); ?>
			<?php endforeach; ?>
			<?php else: ?> 
			<input type="text" name="<?php echo esc_attr( $option->name ); ?>" /><?php 
			endif; 
		?> 
		</span>
	</div>
	<?php endforeach;
	endif; ?> 
	<?php if ( $product->galleryImages ) foreach ( $product->galleryImages as $image):
	?><img src="<?php echo esc_attr( $image->url ); ?>" alt="<?php echo esc_attr( isset( $image->alt ) ? $image->alt : $product->name ); ?>" />
	<?php endforeach; ?> 
</div>