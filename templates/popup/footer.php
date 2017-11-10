<?php foreach ( $this->_get_footer_buttons() as $button ): ?>
<button class="button <?php echo $button->class; ?>"><?php echo $button->title; ?></button>
<?php endforeach; ?>
