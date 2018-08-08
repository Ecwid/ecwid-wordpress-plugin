<p>
    <label>
        <?php _e( 'Layout:', 'ecwid-shopping-cart' ); ?>
        <select class="widefat" 
                name="<?php echo $this->get_field_name( self::FIELD_LAYOUT ); ?>" 
                id="<?php echo $this->get_field_id( self::FIELD_LAYOUT ); ?>"
        >
            <?php foreach( Ecwid_Floating_Minicart::get_layouts() as $value => $label ) : ?>
                <option value="<?php echo $value; ?>"<?php if ( $instance[self::FIELD_LAYOUT] == $value ): ?> selected="selected"<?php endif; ?>>
                    <?php echo $label; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
</p>

<p>
    <label>
        <?php _e( 'Cart icon:', 'ecwid-shopping-cart' ); ?>
        <select class="widefat" 
                name="<?php echo $this->get_field_name( self::FIELD_ICON ); ?>" 
                id="<?php echo $this->get_field_id( self::FIELD_ICON ); ?>"
        >
            <?php foreach( Ecwid_Floating_Minicart::get_icons() as $value => $label ) : ?>
                <option value="<?php echo $value; ?>"<?php if ( $instance[self::FIELD_ICON] == $value ): ?> selected="selected"<?php endif; ?>>
                    <?php echo $label; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
</p>

<p>
    <label>
        <?php _e( 'Border:', 'ecwid-shopping-cart' ); ?>
        <select class="widefat"
                name="<?php echo $this->get_field_name( self::FIELD_FIXED_SHAPE ); ?>" 
                id="<?php echo $this->get_field_id( self::FIELD_FIXED_SHAPE ); ?>"
        >
            <?php foreach( Ecwid_Floating_Minicart::get_fixed_shapes() as $value => $label ) : ?>
                <option value="<?php echo $value; ?>" <?php if ( $instance[self::FIELD_FIXED_SHAPE] == $value ): ?> selected="selected"<?php endif; ?>>
                    <?php echo $label; ?>
                </option>
            <?php endforeach; ?>
        </select>
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