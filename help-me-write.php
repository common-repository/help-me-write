<?php
/*
Plugin Name: Help Me Write
Plugin URI: http://helpmewrite.co
Description: Display all your awesome HelpMeWrite.co content on your blog!
Version: 1.0
Author: Makeshift Studios
Author URI: http://makeshift.io
License: GPL2
*/

/*  Copyright 2013  Makeshift Studios  (email : jon@makeshift.io )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
* Help_Me_Write
*/
class Help_Me_Write extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::WP_Widget(
      'help_me_write', // Base ID
      'Help Me Write',       // Name
      array(
        'description' => 'Display all your awesome HelpMeWrite.co content on your blog!'
      )
    );

    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
  }

  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   *
   * @param array $args     Widget arguments.
   * @param array $instance Saved values from database.
   */
  public function widget( $args, $instance ) {
    extract( $args );
    $person = $instance['person'];
    $width = $instance['width'];
    $stats = $instance['stats'];
    $ideas = $instance['ideas'];
    $supporters = $instance['supporters'];
    $rows = $instance['rows'];


    if ( ! empty( $person ) ) {
      echo $before_widget;
      echo __('<a href="http://helpmewrite.co/people/' . $person . '" class="hmw__init" data-person="' . $person . '" data-width="' . $width .'" data-stats="' . $stats . '" data-ideas="' . $ideas . '" data-supporters="' . $supporters . '" data-rows="' . $rows . '">Follow me on HelpMeWrite</a>', 'text_domain');
      echo $after_widget;
    }
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  public function form( $instance ) {

    $instance = wp_parse_args( (array) $instance, array( 'person' => '', 'width' => 240, 'stats' => true, 'ideas' => true, 'supporters' => true, rows => 2 ) );

    ?>

    <p>
    <label for="<?php echo $this->get_field_id( 'person' ); ?>"><?php _e( 'Help Me Write username' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'person' ); ?>" name="<?php echo $this->get_field_name( 'person' ); ?>" type="text" value="<?php echo esc_attr( $instance['person'] ); ?>" required="true" placeholder="e.g. stef" />
    <span>This is the bit at the end of your URL - e.g. http://helpmewrite.co/people/<strong>stef</strong></span>
    </p>

    <p>
    <label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width (px)' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="number" min="200" value="<?php echo esc_attr( $instance['width'] ); ?>" />
    </p>

    <p>

    <label for="<?php echo $this->get_field_id( 'stats' ); ?>">
      <input type="checkbox" class="checkbox" <?php checked($instance['stats'], true ) ?> id="<?php echo $this->get_field_id( 'stats' ); ?>" name="<?php echo $this->get_field_name( 'stats' ); ?>"  >
      <?php _e( 'Show stats?'); ?></label>
    </p>

    <p>
    <label for="<?php echo $this->get_field_id( 'ideas' ); ?>">
      <input type="checkbox" class="checkbox" <?php checked($instance['ideas'], true ) ?> id="<?php echo $this->get_field_id( 'ideas' ); ?>" name="<?php echo $this->get_field_name( 'ideas' ); ?>">
      <?php _e( 'Show ideas?'); ?></label>
    </p>

    <p>
    <label for="<?php echo $this->get_field_id( 'supporters' ); ?>">
      <input type="checkbox" class="checkbox" <?php checked($instance['supporters'], true ) ?> id="<?php echo $this->get_field_id( 'supporters' ); ?>" name="<?php echo $this->get_field_name( 'supporters' ); ?>">
      <?php _e( 'Show supporters?'); ?></label>
    </p>

    <p>
    <label for="<?php echo $this->get_field_id( 'rows' ); ?>"><?php _e( 'Rows of supporters to show' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'rows' ); ?>" name="<?php echo $this->get_field_name( 'rows' ); ?>" type="number" value="<?php echo esc_attr( $instance['rows'] ); ?>" />
    </p>

    <?php
  }

  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  public function update( $new_instance, $old_instance ) {

    $new_instance = (array) $new_instance;

    $instance = array( 'stats' => 0, 'ideas' => 0, 'supporters' => 0 );

    foreach ($instance as $field => $value) {
      if ( isset($new_instance[$field]) ) {
        $instance[$field] = 1;
      }
    }

    $instance['person'] = ( !empty( $new_instance['person'] ) ) ? strip_tags( $new_instance['person'] ) : '';
    $instance['width'] = ( !empty( $new_instance['width'] ) ) ? strip_tags( $new_instance['width'] ) : '240';
    $instance['rows'] = ( !empty( $new_instance['rows'] ) ) ? strip_tags( $new_instance['rows'] ) : '2';

    return $instance;
  }

  /**
   * Register and enqueues public-facing JavaScript files.
   *
   */
  public function enqueue_scripts() {
    wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/help-me-write.js', __FILE__ ), false, $this->version, true );
  }
}

function do_widget_registration() {
  register_widget('Help_Me_Write');
}

add_action( 'widgets_init', 'do_widget_registration' );

?>
