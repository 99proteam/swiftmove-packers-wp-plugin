<?php
/**
 * Plugin Name: SwiftMove Packers Landing Override
 * Description: Show the SwiftMove Packers landing design on a selected page or post. No theme content or other options.
 * Version: 1.1
 * Author: 99proteam & Copilot
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define('SWIFTMOVE_PACKERS_OVERRIDE_OPT', 'swiftmove_packers_selected_id');

// Admin settings page
add_action('admin_menu', function() {
    add_options_page(
        'SwiftMove Packers Landing',
        'SwiftMove Packers',
        'manage_options',
        'swiftmove-packers',
        'swiftmove_packers_settings_page'
    );
});

// Save settings
add_action('admin_init', function() {
    register_setting('swiftmove_packers_group', SWIFTMOVE_PACKERS_OVERRIDE_OPT);
});

function swiftmove_packers_settings_page() {
    ?>
    <div class="wrap">
        <h1>SwiftMove Packers — Landing Page Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('swiftmove_packers_group');
            do_settings_sections('swiftmove_packers_group');
            $selected_id = get_option(SWIFTMOVE_PACKERS_OVERRIDE_OPT, '');
            $all_posts = get_posts([
                'post_type'   => ['page', 'post'],
                'post_status' => 'publish',
                'numberposts' => -1,
                'orderby'     => 'post_type post_title',
                'order'       => 'ASC'
            ]);
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Select the page or post to show the landing design:</th>
                    <td>
                        <select name="<?php echo esc_attr(SWIFTMOVE_PACKERS_OVERRIDE_OPT); ?>" style="min-width:280px;">
                            <option value="">— Select —</option>
                            <?php foreach($all_posts as $p): ?>
                                <option value="<?php echo $p->ID; ?>" <?php selected($selected_id, $p->ID); ?>>
                                    [<?php echo esc_html(ucfirst($p->post_type)); ?>] <?php echo esc_html($p->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description">The selected page/post will show ONLY the SwiftMove landing design. All theme content/options will be hidden on that page.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Frontend override: replace template for selected page/post
add_filter('template_include', function($template) {
    $selected_id = get_option(SWIFTMOVE_PACKERS_OVERRIDE_OPT, '');
    if ($selected_id && is_singular() && get_queried_object_id() == $selected_id) {
        return plugin_dir_path(__FILE__) . 'landing-template.php';
    }
    return $template;
});

// Enqueue assets only for the overridden page
add_action('wp_enqueue_scripts', function() {
    $selected_id = get_option(SWIFTMOVE_PACKERS_OVERRIDE_OPT, '');
    if ($selected_id && is_singular() && get_queried_object_id() == $selected_id) {
        $ver = '1.1';
        $base = plugin_dir_url( __FILE__ );
        wp_enqueue_style( 'swiftmove-packers', $base . 'assets/style.css', [], $ver );
        wp_enqueue_script( 'swiftmove-packers', $base . 'assets/script.js', [], $ver, true );
        wp_enqueue_style( 'swiftmove-packers-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap', [], null );
    }
}, 9);