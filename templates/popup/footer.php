<?php foreach ( $this->_get_footer_buttons() as $button ) : ?>
<button class="button <?php echo esc_attr( $button->class ); ?>"><?php echo esc_html( $button->title ); ?></button>
<?php endforeach; ?>
