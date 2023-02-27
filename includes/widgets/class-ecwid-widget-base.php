<?php

abstract class Ecwid_Widget_Base extends WP_Widget {

	protected $_hide_title = false;

	public $widget_number;

	abstract protected function _render_widget_content( $args, $instance );

	public function __construct( $id_base, $name, $widget_options = array(), $control_options = array() ) {
		$this->widget_number = 1;

		parent::__construct( $id_base, $name, $widget_options, $control_options );
	}

	public function widget( $args, $instance ) {
		$before_widget = '';
		$before_title  = '';
		$after_title   = '';
		$after_widget  = '';

		extract( $args ); //phpcs:ignore WordPress.PHP.DontExtract.extract_extract

		$renderer = apply_filters( 'ecwid_get_custom_widget_renderer', null );

		if ( ! is_null( $renderer ) ) {
			$content = call_user_func_array( $renderer, array( $this, $args, $instance ) );
		} else {
			$content = $this->_render_widget_content( $args, $instance );
		}

		if ( empty( $content ) ) {
			return;
		}

		echo $before_widget;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		if ( ! $this->_hide_title ) {

			$title = '&nbsp;';
			if ( isset( $instance['title'] ) && ! empty( $instance['title'] ) ) {
				$title = $instance['title'];
			}

			$title = apply_filters( 'widget_title', $title );

			if ( $title ) {
				echo $before_title . $title . $after_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo $after_widget; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
