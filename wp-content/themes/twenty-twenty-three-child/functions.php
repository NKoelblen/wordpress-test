<?php
/* Enqueue Styles */
if (!function_exists('thr_enqueue_styles')) {
    function thr_enqueue_styles()
    {
        wp_enqueue_style('twenty-twenty-three-style', get_template_directory_uri() . '/style.css');
    }
    add_action('wp_enqueue_scripts', 'thr_enqueue_styles');
}

// Repeatable Meta Box
add_action('admin_init', 'cxc_single_repeter_meta_boxes');
function cxc_single_repeter_meta_boxes()
{
    add_meta_box('cxc-single-repeater-data', 'Single Repeater', 'cxc_single_repeatable_meta_box_callback', 'post', 'normal', 'default');
}

function cxc_single_repeatable_meta_box_callback($post)
{
    $custom_repeater_item = get_post_meta($post->ID, 'custom_repeater_item', true);
    wp_nonce_field('repeterBox', 'formType');
?>
    <table class="cxc-item-table">
        <tbody>
            <?php
            if ($custom_repeater_item) {
                foreach ($custom_repeater_item as $item_key => $item_value) {
                    $item1  = isset($item_value['item1']) ? $item_value['item1'] : '';
                    $item2  = isset($item_value['item2']) ? $item_value['item2'] : '';
            ?>
                    <tr class="cxc-sub-row">
                        <td>
                            <input type="text" name="<?php echo esc_attr('custom_repeater_item[' . $item_key . '][item1]'); ?>" value="<?php echo esc_attr($item1); ?>" placeholder="Item 1">
                        </td>
                        <td>
                            <input type="text" name="<?php echo esc_attr('custom_repeater_item[' . $item_key . '][item2]'); ?>" value="<?php echo esc_attr($item2); ?>" placeholder="Item 2" />
                        </td>
                        <td>
                            <button class="cxc-remove-item button" type="button"><?php esc_html_e('Remove', 'cxc-codexcoach'); ?></button>
                        </td>
                    </tr>
                <?php
                }
            } else {
                ?>
                <tr class="cxc-sub-row">
                    <td>
                        <input type="text" name="custom_repeater_item[0][item1]" placeholder="Item 1">
                    </td>
                    <td>
                        <input type="text" name="custom_repeater_item[0][item2]" placeholder="Item 2" />
                    </td>
                    <td>
                        <button class="cxc-remove-item button" type="button"><?php esc_html_e('Remove', 'cxc-codexcoach'); ?></button>
                    </td>
                </tr>
            <?php
            }
            ?>
            <tr class="cxc-hide-tr">
                <td>
                    <input name="hide_custom_repeater_item[rand_no][item1]" type="text" placeholder="Item 1" />
                </td>
                <td>
                    <input type="text" name="hide_custom_repeater_item[rand_no][item2]" placeholder="Item 2" />
                </td>
                <td>
                    <button class="cxc-remove-item button" type="button"><?php esc_html_e('Remove', 'cxc-codexcoach'); ?></button>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">
                    <button class="cxc-add-item button button-secondary" type="button"><?php esc_html_e('Add another', 'cxc-codexcoach'); ?></button>
                </td>
            </tr>
        </tfoot>
    </table>
<?php
}
?>

// Save Repeatable Meta Box
<?php
add_action('save_post', 'cxc_single_repeatable_meta_box_save');
function cxc_single_repeatable_meta_box_save($post_id)
{

    if (!isset($_POST['formType']) && !wp_verify_nonce($_POST['formType'], 'repeterBox')) {
        return;
    }

    if (!defined('DOING_AUTOSAVE')) {
        define('DOING_AUTOSAVE', true);
    }

    if (!current_user_can('edit_post', $post_id)) {
        return false;
    }

    if (isset($_POST['custom_repeater_item'])) {
        update_post_meta($post_id, 'custom_repeater_item', $_POST['custom_repeater_item']);
    } else {
        update_post_meta($post_id, 'custom_repeater_item', '');
    }
}
?>

//Repeatable Meta Box JQuery
<?php
add_action('admin_footer', 'cxc_single_repeatable_meta_box_footer');
function cxc_single_repeatable_meta_box_footer()
{
?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            jQuery(document).on('click', '.cxc-remove-item', function() {
                jQuery(this).parents('tr.cxc-sub-row').remove();
            });
            jQuery(document).on('click', '.cxc-add-item', function() {
                var p_this = jQuery(this);
                var row_no = parseFloat(jQuery('.cxc-item-table tr.cxc-sub-row').length);
                var row_html = jQuery('.cxc-item-table .cxc-hide-tr').html().replace(/rand_no/g, row_no).replace(/hide_custom_repeater_item/g, 'custom_repeater_item');
                jQuery('.cxc-item-table tbody').append('<tr class="cxc-sub-row">' + row_html + '</tr>');
            });
        });
    </script>
<?php
}
?>

//Repeatable Meta Box Style
<?php
add_action('admin_head', 'cxc_single_repeatable_meta_box_header');
function cxc_single_repeatable_meta_box_header()
{
?>
    <style type="text/css">
        .cxc-item-table,
        .cxc-item-table .cxc-sub-row input[type="text"] {
            width: 100%;
        }

        .cxc-hide-tr {
            display: none;
        }
    </style>
<?php
}
?>