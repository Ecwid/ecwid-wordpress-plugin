<h4><?php _e( 'Behavior', 'ecwid-shopping-cart' ); ?></h4>

<p><?php _e( 'Sit in sidebar or float on the page?', 'ecwid-shopping-cart' ); ?></p>
<p>
    <label>
        <input type="radio" name="<?php echo $this->get_field_name( self::FIELD_FIXED ); ?>" value="FALSE"
               <?php if ( $instance[self::FIELD_FIXED] === 'FALSE' ): ?> checked="checked"<?php endif; ?>
        >
        <?php _e( 'Sit in sidebar', 'ecwid-shopping-cart' ); ?>
    </label>
    <label>
        <input type="radio" name="<?php echo $this->get_field_name( self::FIELD_FIXED ); ?>" value="TRUE"
			    <?php if ( $instance[self::FIELD_FIXED] === 'TRUE' ): ?> checked="checked"<?php endif; ?>
        >
        <?php _e( 'Float', 'ecwid-shopping-cart' ); ?>
    </label>
</p>

<p>
    <label>
        <?php _e( 'Hide when empty', 'ecwid-shopping-cart' ); ?>
        <input type="checkbox"  name="<?php echo $this->get_field_name( self::FIELD_SHOW_EMPTY_CART ); ?>" 
               id="<?php echo $this->get_field_id( self::FIELD_SHOW_EMPTY_CART ); ?>" value="FALSE"
			    <?php if ( $instance[self::FIELD_SHOW_EMPTY_CART] === 'FALSE' ): ?> checked="checked"<?php endif; ?>
        >
    </label>
</p>

<p>
    <label>
        <?php _e( 'Position on page', 'ecwid-shopping-cart' ); ?>
        <select 
                name="<?php echo $this->get_field_name( self::FIELD_FIXED_POSITION ); ?>" 
                id="<?php echo $this->get_field_id( self::FIELD_FIXED_POSITION ); ?>"
        >
            <?php foreach( $this->_get_positions() as $value => $label ) : ?>
                <option value="<?php echo $value; ?>"<?php if ( $instance[self::FIELD_FIXED_POSITION] == $value ): ?> selected="selected"<?php endif; ?>>
                    <?php echo $label; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
</p>

<h4><?php _e( 'Appearance', 'ecwid-shopping-cart' ); ?></h4>
<p>
    <label>
        <?php _e( 'Layout', 'ecwid-shopping-cart' ); ?>
        <select 
                name="<?php echo $this->get_field_name( self::FIELD_LAYOUT ); ?>" 
                id="<?php echo $this->get_field_id( self::FIELD_LAYOUT ); ?>"
        >
            <?php foreach( $this->_get_layouts() as $value => $label ) : ?>
                <option value="<?php echo $value; ?>"<?php if ( $instance[self::FIELD_LAYOUT] == $value ): ?> selected="selected"<?php endif; ?>>
                    <?php echo $label; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
</p>

<p>
    <label>
        <?php _e( 'Cart icon', 'ecwid-shopping-cart' ); ?>
        <select 
                name="<?php echo $this->get_field_name( self::FIELD_ICON ); ?>" 
                id="<?php echo $this->get_field_id( self::FIELD_ICON ); ?>"
        >
            <?php foreach( $this->_get_icons() as $value => $label ) : ?>
                <option value="<?php echo $value; ?>"<?php if ( $instance[self::FIELD_ICON] == $value ): ?> selected="selected"<?php endif; ?>>
                    <?php echo $label; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
</p>

<p>
    <label>
        <?php _e( 'Border', 'ecwid-shopping-cart' ); ?>
        <select 
                name="<?php echo $this->get_field_name( self::FIELD_FIXED_SHAPE ); ?>" 
                id="<?php echo $this->get_field_id( self::FIELD_FIXED_SHAPE ); ?>"
        >
            <?php foreach( $this->_get_fixed_shapes() as $value => $label ) : ?>
                <option value="<?php echo $value; ?>" <?php if ( $instance[self::FIELD_FIXED_SHAPE] == $value ): ?> selected="selected"<?php endif; ?>>
                    <?php echo $label; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
</p>

<p>
    <label>
        <?php _e( 'Show add to cart animation', 'ecwid-shopping-cart' ); ?>
        <input type="checkbox" value="TRUE"
               id="<?php echo $this->get_field_id( self::FIELD_SHOW_BUY_ANIMATION ); ?>" 
               name="<?php echo $this->get_field_name( self::FIELD_SHOW_BUY_ANIMATION ); ?>"
               <?php if ( $instance[self::FIELD_SHOW_BUY_ANIMATION] === 'TRUE' ): ?>checked="checked"<?php endif; ?>
        >
    </label>
</p>

<p>
    <label for="<?php echo $this->get_field_name( self::FIELD_TITLE ); ?>"><?php _e ( 'Title:' ); ?>
        <input class="widefat" 
               id="<?php echo $this->get_field_id( self::FIELD_TITLE ); ?>" 
               name="<?php echo $this->get_field_name( self::FIELD_TITLE ); ?>" 
               type="text" value="<?php esc_html( $instance['title'] ); ?>" 
        />
    </label>
</p>

<script type="text/javascript">
    jQuery('[name="<?php echo $this->get_field_name( 'fixed' ); ?>"]').click(function() {
        process_fields_visibility();
    });
    
    var process_fields_visibility = function() {
        var dep_on_fixed = jQuery('[name="<?php echo $this->get_field_name( self::FIELD_FIXED_POSITION ); ?>"], [name="<?php echo $this->get_field_name( self::FIELD_SHOW_EMPTY_CART ); ?>"]');

        if ( jQuery('[name="<?php echo $this->get_field_name( self::FIELD_FIXED ); ?>"]:checked').val() == 'FALSE' ) {
            dep_on_fixed.closest('p').hide();
        } else {
            dep_on_fixed.closest('p').show();
        }
    };
    process_fields_visibility();
</script>