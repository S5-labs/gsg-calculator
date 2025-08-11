<?php

if (!defined('ABSPATH')) exit;

/**
 * Adds a new settings page to the WordPress admin menu.
 */
add_action('admin_menu', function () {
    add_options_page(
        'GSG Calculator Settings',
        'GSG Calculator',
        'manage_options',
        'gsg-calculator',
        'gsg_calculator_settings_page_html'
    );
});

/**
 * Renders the HTML for the settings page.
 */
function gsg_calculator_settings_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <h2>Shortcode Builder</h2>
        <p>Use the form below to build a custom shortcode. Copy the generated shortcode and paste it into any page or post.</p>
        
        <div id="gsg-shortcode-builder">
            <form id="gsg-shortcode-form">
                <table class="form-table">
                    <?php
                    foreach ($GLOBALS['gsg_calculator_defaults'] as $key => $value) {
                        ?>
                        <tr valign="top">
                            <th scope="row"><label for="gsg-<?php echo esc_attr($key); ?>"><?php echo esc_html(ucwords(str_replace('_', ' ', $key))); ?></label></th>
                            <td><input type="text" id="gsg-<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>" class="regular-text gsg-shortcode-input" /></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </form>

            <h3>Generated Shortcode</h3>
            <textarea id="gsg-generated-shortcode" readonly style="width: 100%; min-height: 50px; font-family: monospace;">[gsg_calculator]</textarea>
            <p><button type="button" class="button button-secondary" id="gsg-copy-shortcode">Copy to Clipboard</button></p>
        </div>
    </div>
    <?php
    add_action('admin_footer', 'gsg_calculator_settings_page_js');
}

function gsg_calculator_settings_page_js() {
    ?>
    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('gsg-shortcode-form');
        const inputs = form.querySelectorAll('.gsg-shortcode-input');
        const output = document.getElementById('gsg-generated-shortcode');
        const copyBtn = document.getElementById('gsg-copy-shortcode');

        const defaults = <?php echo json_encode($GLOBALS['gsg_calculator_defaults']); ?>;

        function generateShortcode() {
            let shortcode = '[gsg_calculator';
            inputs.forEach(input => {
                const key = input.name;
                const value = input.value;
                if (value !== defaults[key]) {
                    shortcode += ` ${key}="${value}"`;
                }
            });
            shortcode += ']';
            output.value = shortcode;
        }

        inputs.forEach(input => {
            input.addEventListener('input', generateShortcode);
        });

        copyBtn.addEventListener('click', function() {
            output.select();
            document.execCommand('copy');
        });

        generateShortcode();
    });
    </script>
    <?php
}
