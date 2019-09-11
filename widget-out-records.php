<?php
/**
 * Adds Foo_Widget widget.
 */
class OutRecord_Widget extends WP_Widget { // Название самого виджета Foo_Widget меняю на OutRecord (не забыть изменить название в widgets.php)

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'outrecord_widget', // Base ID
            esc_html__( 'Вывод произвольных записей', 'text_domain' ), // Name
            array( 'description' => esc_html__( 'Вывод произвольных записей', 'text_domain' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
//    Функция, которая отображает информацию на сайте
    public function widget( $args, $instance ) {
        echo $args['before_widget'];


        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
//        echo esc_html__( 'Hello, World!', 'text_domain' );
//        echo apply_filters( 'widget_outrecordsss', $instance['number_records'] );

        $post_type_check = empty($instance['post_type_check'])? '' : $instance['post_type_check'];
        $obj = get_post_type_object( $post_type_check );
        echo $obj->labels->singular_name;

        $custom_posts = get_posts( [
            'post_type'      => $post_type_check,
            'numberposts' => $instance['number_records'],
        ] );
        echo '<ul>';
        foreach( $custom_posts as $custom_post ) {
            setup_postdata($custom_post);
            echo '<li><a href="'.$custom_post->guid.'">'.$custom_post->post_title.'</a></li>';
        }
        echo '</ul>';

//        echo '<pre>';
//        var_dump($custom_post);
//        echo '</pre>';
        wp_reset_postdata() ;




        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
//    Функция, которая отображает виджет в админке
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
        $number_records = ! empty( $instance['number_records'] ) ? $instance['number_records'] : esc_html__( 'Количество записей', 'text_domain' );
        $post_type_check = ! empty( $instance['post_type_check'] ) ? $instance['post_type_check'] : esc_html__( 'post_type_check', 'text_domain' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
<!--        Выпадающий список со всеми произвольными типами записей-->
        <?php
        $args = array(
            'public'   => true,
            '_builtin' => false
        );
        $output   = 'names'; // names or objects, note names is the default
        $operator = 'and';   // 'and' or 'or'

        $post_types = get_post_types( $args, $output, $operator);
?>
        <p>
            <select class='widefat' id="<?php echo $this->get_field_id('post_type_check'); ?>"
            name ="<?php echo $this->get_field_name('post_type_check'); ?>" type="text">
         <?php
        foreach ( $post_types as $post_type ) {
            $obj = get_post_type_object( $post_type );
            ?>
           <option value="<?php echo $post_type ?>"<?php echo ($post_type_check == $post_type)?'selected':'' ; ?>>
                <?php echo $obj->labels->singular_name ?>
           </option>
            <?php
            }
        echo '</select></p>';
        ?>
        <!--        .Выпадающий список со всеми произвольными типами записей-->

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number_records' ) ); ?>"><?php esc_attr_e( 'Количество записей:', 'text_domain' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number_records' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_records' ) ); ?>" type="number" value="<?php echo esc_attr( $number_records ); ?>">
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
//    Функция, которая записывает информацию в базу данных
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number_records'] = ( ! empty( $new_instance['number_records'] ) ) ? sanitize_text_field( $new_instance['number_records'] ) : '';
        $instance['post_type_check'] = ( ! empty( $new_instance['post_type_check'] ) ) ? sanitize_text_field( $new_instance['post_type_check'] ) : '';

        return $instance;
    }



} // class Foo_Widget
