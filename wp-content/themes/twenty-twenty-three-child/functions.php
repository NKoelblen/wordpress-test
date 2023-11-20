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

function cxc_single_repeatable_meta_box_callback($post) // 
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

/*
Retrieving the values:
Birthday = get_post_meta( get_the_ID(), 'advanced_options_birthday', true )
*/
class Advanced_Options
{
    private $config = '{"title":"Advanced Options","prefix":"advanced_options_","domain":"advanced-options","class_name":"Advanced_Options","post-type":["post"],"context":"normal","priority":"default","fields":[{"type":"date","label":"Birthday","id":"advanced_options_birthday"}]}';

    public function __construct()
    {
        $this->config = json_decode($this->config, true);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_post']);
    }

    public function add_meta_boxes()
    {
        foreach ($this->config['post-type'] as $screen) {
            add_meta_box(
                sanitize_title($this->config['title']),
                $this->config['title'],
                [$this, 'add_meta_box_callback'],
                $screen,
                $this->config['context'],
                $this->config['priority']
            );
        }
    }

    public function save_post($post_id)
    {
        foreach ($this->config['fields'] as $field) {
            switch ($field['type']) {
                default:
                    if (isset($_POST[$field['id']])) {
                        $sanitized = sanitize_text_field($_POST[$field['id']]);
                        update_post_meta($post_id, $field['id'], $sanitized);
                    }
            }
        }
    }

    public function add_meta_box_callback()
    {
        $this->fields_table();
    }

    private function fields_table()
    {
    ?><table class="form-table" role="presentation">
            <tbody><?php
                    foreach ($this->config['fields'] as $field) {
                    ?><tr>
                        <th scope="row"><?php $this->label($field); ?></th>
                        <td><?php $this->field($field); ?></td>
                    </tr><?php
                        }
                            ?></tbody>
        </table><?php
            }

            private function label($field)
            {
                switch ($field['type']) {
                    default:
                        printf(
                            '<label class="" for="%s">%s</label>',
                            $field['id'],
                            $field['label']
                        );
                }
            }

            private function field($field)
            {
                switch ($field['type']) {
                    case 'date':
                        $this->input_minmax($field);
                        break;
                    default:
                        $this->input($field);
                }
            }

            private function input($field)
            {
                printf(
                    '<input class="regular-text %s" id="%s" name="%s" %s type="%s" value="%s">',
                    isset($field['class']) ? $field['class'] : '',
                    $field['id'],
                    $field['id'],
                    isset($field['pattern']) ? "pattern='{$field['pattern']}'" : '',
                    $field['type'],
                    $this->value($field)
                );
            }

            private function input_minmax($field)
            {
                printf(
                    '<input class="regular-text" id="%s" %s %s name="%s" %s type="%s" value="%s">',
                    $field['id'],
                    isset($field['max']) ? "max='{$field['max']}'" : '',
                    isset($field['min']) ? "min='{$field['min']}'" : '',
                    $field['id'],
                    isset($field['step']) ? "step='{$field['step']}'" : '',
                    $field['type'],
                    $this->value($field)
                );
            }

            private function value($field)
            {
                global $post;
                if (metadata_exists('post', $post->ID, $field['id'])) {
                    $value = get_post_meta($post->ID, $field['id'], true);
                } else if (isset($field['default'])) {
                    $value = $field['default'];
                } else {
                    return '';
                }
                return str_replace('\u0027', "'", $value);
            }
        }
        new Advanced_Options;

        // Register Custom Post Type
        function products_post_type()
        {

            $labels = array(
                'name'                  => _x('Products', 'Post Type General Name', 'text_domain'),
                'singular_name'         => _x('Product', 'Post Type Singular Name', 'text_domain'),
                'menu_name'             => __('Products', 'text_domain'),
                'name_admin_bar'        => __('Product', 'text_domain'),
                'archives'              => __('Item Archives', 'text_domain'),
                'attributes'            => __('Item Attributes', 'text_domain'),
                'parent_item_colon'     => __('Parent Product:', 'text_domain'),
                'all_items'             => __('All Products', 'text_domain'),
                'add_new_item'          => __('Add New Product', 'text_domain'),
                'add_new'               => __('New Product', 'text_domain'),
                'new_item'              => __('New Item', 'text_domain'),
                'edit_item'             => __('Edit Product', 'text_domain'),
                'update_item'           => __('Update Product', 'text_domain'),
                'view_item'             => __('View Product', 'text_domain'),
                'view_items'            => __('View Items', 'text_domain'),
                'search_items'          => __('Search products', 'text_domain'),
                'not_found'             => __('No products found', 'text_domain'),
                'not_found_in_trash'    => __('No products found in Trash', 'text_domain'),
                'featured_image'        => __('Featured Image', 'text_domain'),
                'set_featured_image'    => __('Set featured image', 'text_domain'),
                'remove_featured_image' => __('Remove featured image', 'text_domain'),
                'use_featured_image'    => __('Use as featured image', 'text_domain'),
                'insert_into_item'      => __('Insert into item', 'text_domain'),
                'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
                'items_list'            => __('Items list', 'text_domain'),
                'items_list_navigation' => __('Items list navigation', 'text_domain'),
                'filter_items_list'     => __('Filter items list', 'text_domain'),
            );
            $args = array(
                'label'                 => __('Product', 'text_domain'),
                'description'           => __('Product information pages.', 'text_domain'),
                'labels'                => $labels,
                'supports'              => array('title', 'editor', 'thumbnail', 'comments', 'custom-fields'),
                'taxonomies'            => array('category', 'post_tag'),
                'hierarchical'          => false,
                'public'                => true,
                'show_ui'               => true,
                'show_in_menu'          => true,
                'menu_position'         => 5,
                'show_in_admin_bar'     => true,
                'show_in_nav_menus'     => true,
                'can_export'            => true,
                'has_archive'           => true,
                'exclude_from_search'   => false,
                'publicly_queryable'    => true,
                'capability_type'       => 'page',
            );
            register_post_type('product', $args);
        }
        add_action('init', 'products_post_type', 0);
