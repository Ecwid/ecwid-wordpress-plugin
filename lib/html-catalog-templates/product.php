
<div itemscope itemtype="http://schema.org/Product">

	<h1 itemprop="name"><?php echo EcwidPlatform::esc_html( $product->name ); ?></h1>
	<p itemprop="sku"><?php echo EcwidPlatform::esc_html( $product->sku ); ?></p>
	<img itemprop="image" src="<?php echo EcwidPlatform::esc_attr( $product->originalImageUrl ); ?>" alt="<?php echo EcwidPlatform::esc_attr( $product->name  . ' ' . $product->sku); ?>" />
	<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
		<span itemprop="price" content="<?php echo EcwidPlatform::esc_html( $product->price ); ?>"><?php 
			echo EcwidPlatform::esc_html( 
            	$formats->currencyPrefix . $product->price . $formats->currencySuffix
			); 
		?></span>
		<span itemprop="priceCurrency" content="<?php echo EcwidPlatform::esc_attr( $formats->currency ); ?>"></span>
		<?php if ( !isset( $product->quantity) || $product->quantity > 0): ?><link itemprop="availability" href="http://schema.org/InStock" />In stock<?php endif; ?> 
	</div>
	<?php if ( isset( $product->attributes ) && is_array( $product->attributes ) && !empty( $product->attributes) ): ?> 
	<div class="attributes">
	<?php foreach ( $product->attributes as $attribute ):
	  ?>    <div><?php 
			echo $attribute->name . ':';
			if ( isset( $attribute->internalName ) && $attribute->internalName == 'Brand'
			     || 
				 isset( $attribute->type ) && $attribute->type == 'BRAND'
			):
			?><span itemprop="brand"><?php echo EcwidPlatform::esc_html( $attribute->value ); ?></span><?php 
			else: 
				echo EcwidPlatform::esc_html( $attribute->value ); 
			endif; 
		?></div>
	<?php 
		endforeach; ?></div>
	<?php endif; ?>
	<?php if ( isset( $product->options) && is_array( $product->options ) && !empty( $product->options ) ): ?>
	<?php foreach ( $product->options as $option ): ?> 
	<div class="option">
		<span class="name"><?php echo $option->name; ?></span>
		<span class="input"><?php 
			if ( $option->type == 'TEXTAREA' ):
		?>
			<textarea name="<?php echo EcwidPlatform::esc_attr( $option->name ); ?>"></textarea>
			<?php elseif ( $option->type == 'SELECT' ): ?> 
			<select name="<?php echo EcwidPlatform::esc_attr( $option->name ); ?>"><?php 
				foreach( $option->choices as $param ): ?> 
				<option value="<?php echo EcwidPlatform::esc_attr( $param->text ); ?>"><?php
					echo EcwidPlatform::esc_html( $param->text );
					echo ' ';
					echo EcwidPlatform::esc_html( $param->priceModifier );
					?></option><?php endforeach; 
			?> 
			</select><?php 
			elseif ( $option->type == 'RADIO' ): 
				foreach ( $option->choices as $param ): ?> 
			<?php echo sprintf(
				'<input type="radio" name="%s" value="%s" />%s (%s)',
				EcwidPlatform::esc_attr( $option->name ),
				EcwidPlatform::esc_attr( $param->text ),
				EcwidPlatform::esc_html( $param->text ),
				EcwidPlatform::esc_html( $param->priceModifier )
			); ?>
			<?php endforeach; ?>
			<?php elseif ( $option->type == 'CHECKBOX'): foreach ( $option->choices as $param ): ?> 
			<?php echo sprintf(
					'<input type="checkbox" name="%s[]" value="%s" />%s (%s)',
					EcwidPlatform::esc_attr( $option->name ),
					EcwidPlatform::esc_attr( $param->text ),
					EcwidPlatform::esc_html( $param->text ),
					EcwidPlatform::esc_html( $param->priceModifier )
				); ?>
			<?php endforeach; ?>
			<?php else: ?> 
			<input type="text" name="<?php echo EcwidPlatform::esc_attr( $option->name ); ?>" /><?php 
			endif; 
		?> 
		</span>
	</div>
	<?php endforeach;
	endif; ?> 
	<?php if ( $product->galleryImages ) foreach ( $product->galleryImages as $image):
	?><img src="<?php echo EcwidPlatform::esc_attr( $image->url ); ?>" alt="<?php echo EcwidPlatform::esc_attr( $image->alt ? $image->alt : $product->name ); ?>" />
	<?php endforeach; ?> 
</div>