<?php
/*
Plugin Name: Horizontal Custom Content Scroller
Description: Plugin for scrolling custom content vertically with customizable speed and direction.
Version: 1.0
Author: Your Name
*/

function custom_content_scroller_enqueue_scripts() {
    wp_enqueue_script('custom-content-scroller-script', plugins_url('custom-content-scroller.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_style('custom-content-scroller-style', plugins_url('custom-content-scroller.css', __FILE__));
    wp_localize_script('custom-content-scroller-script', 'scroller_settings', array(
        'speed' => get_option('content_scroller_speed', '3000'),
        'direction' => get_option('content_scroller_direction', 'down')
    ));
}
add_action('wp_enqueue_scripts', 'custom_content_scroller_enqueue_scripts');

function custom_content_scroller_add_admin_menu() {
    add_menu_page('Content Scroller Settings', 'Content Scroller', 'manage_options', 'content_scroller', 'custom_content_scroller_settings_page');
}
add_action('admin_menu', 'custom_content_scroller_add_admin_menu');

function custom_content_scroller_register_settings() {
    register_setting('content_scroller_settings_group', 'content_scroller_direction');
    register_setting('content_scroller_settings_group', 'content_scroller_speed');
    for ($i = 1; $i <= 12; $i++) {
        register_setting('content_scroller_settings_group', "content_scroller_content_$i");
        register_setting('content_scroller_settings_group', "content_scroller_url_$i");
    }
}
add_action('admin_init', 'custom_content_scroller_register_settings');

function custom_content_scroller_settings_page() {
    ?>
    <div class="wrap">
        <h2>Content Scroller Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('content_scroller_settings_group'); ?>
            <?php do_settings_sections('content_scroller_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Scroll Speed (ms)</th>
                    <td><input type="number" name="content_scroller_speed" value="<?php echo esc_attr(get_option('content_scroller_speed', '3000')); ?>" /></td>
                </tr>
                <?php for ($i = 1; $i <= 12; $i++) : ?>
                <tr valign="top">
                    <th scope="row">Content <?php echo $i; ?></th>
                    <td><input type="text" name="content_scroller_content_<?php echo $i; ?>" value="<?php echo esc_attr(get_option("content_scroller_content_$i")); ?>" placeholder="Enter Content <?php echo $i; ?>" /></td>
                    <td><input type="text" name="content_scroller_url_<?php echo $i; ?>" value="<?php echo esc_url(get_option("content_scroller_url_$i")); ?>" placeholder="Enter URL <?php echo $i; ?>" /></td>
                </tr>
                <?php endfor; ?>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
add_shortcode('custom_content_scroller', 'custom_content_scroller_shortcode');

function custom_content_scroller_shortcode($atts) {
    $atts = shortcode_atts(array(
        'direction' => get_option('content_scroller_direction', 'down'),
        'speed' => get_option('content_scroller_speed', '3000')
    ), $atts);

    ob_start();
    ?>
    <div class='custom-content-scroller' style='overflow: hidden; height: 200px; width: 100%;' data-direction='<?php echo $atts['direction']; ?>' data-speed='<?php echo $atts['speed']; ?>'>
        <?php
        for ($i = 1; $i <= 12; $i++) {
            $content = get_option("content_scroller_content_$i");
            $url = get_option("content_scroller_url_$i");
            if (!empty($content)) {
                echo "<div class='scroller-content'><a href='$url'>$content</a></div>";
            }
        }
        ?>
    </div>
    <?php
    return ob_get_clean();
}
