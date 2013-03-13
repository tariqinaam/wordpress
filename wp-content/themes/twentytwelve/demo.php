<?php

/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */
/* * ******************* META BOX DEFINITIONS ********************** */

/**
 * Prefix of meta keys (optional)
 * Use underscore (_) at the beginning to make keys hidden
 * Alt.: You also can make prefix empty to disable it
 */
// Better has an underscore as last sign
$prefix = 'YOUR_PREFIX_';

global $meta_boxes;

$meta_boxes = array();


//youtube url
$meta_boxes[] = array(
    'id' => 'youtube',
    // Meta box title - Will appear at the drag and drop handle bar. Required.
    'title' => 'Youtube URL',
    // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
    'pages' => array('page'),
    // Where the meta box appear: normal (default), advanced, side. Optional.
    'context' => 'normal',
    // Order of meta box: high (default), low. Optional.
    'priority' => 'high',
    // Reson to visit agent meta
    'fields' => array(
        // TEXT
        array(
            // Field name - Will be used as label
            'name' => '',
            // Field ID, i.e. the meta key
            'id' => "{$prefix}youtube-url",
            // Field description (optional)
            'desc' => 'ID for youtube video',
            'type' => 'text',
            // Default value (optional)
            'size' => '60'
        // CLONES: Add to make the field cloneable (i.e. have multiple value)
        ),
        
        )
);

// 1st meta box. reason to visit ageant
$meta_boxes[] = array(
    // Meta box id, UNIQUE per meta box. Optional since 4.1.5
    'id' => 'reason',
    // Meta box title - Will appear at the drag and drop handle bar. Required.
    'title' => 'Reason to visit Travel Agents',
    // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
    'pages' => array('page'),
    // Where the meta box appear: normal (default), advanced, side. Optional.
    'context' => 'normal',
    // Order of meta box: high (default), low. Optional.
    'priority' => 'high',
    // Reson to visit agent meta
    'fields' => array(
        // TEXT
        array(
            // Field name - Will be used as label
            'name' => '',
            // Field ID, i.e. the meta key
            'id' => "{$prefix}text1",
            // Field description (optional)
            'desc' => 'Text description',
            'type' => 'textarea',
            // Default value (optional)
            'std' => 'Default text value',
            'cols' => '20',
            'rows' => '2',
            'clone' => 'true'
        // CLONES: Add to make the field cloneable (i.e. have multiple value)
        ),
        array(
            // Field name - Will be used as label
            'name' => '',
            // Field ID, i.e. the meta key
            'id' => "{$prefix}text2",
            // Field description (optional)
            'desc' => 'Text description',
            'type' => 'textarea',
            // Default value (optional)
            'std' => 'Default text value',
            'cols' => '20',
            'rows' => '2',
        // CLONES: Add to make the field cloneable (i.e. have multiple value)
        ),
        array(
            // Field name - Will be used as label
            'name' => '',
            // Field ID, i.e. the meta key
            'id' => "{$prefix}text3",
            // Field description (optional)
            'desc' => 'Text description',
            'type' => 'textarea',
            // Default value (optional)
            'std' => 'Default text value',
            'cols' => '20',
            'rows' => '2',
        // CLONES: Add to make the field cloneable (i.e. have multiple value)
        ),
        array(
            // Field name - Will be used as label
            'name' => '',
            // Field ID, i.e. the meta key
            'id' => "{$prefix}text4",
            // Field description (optional)
            'desc' => 'Text description',
            'type' => 'textarea',
            // Default value (optional)
            'std' => 'Default text value',
            'cols' => '20',
            'rows' => '2',
        // CLONES: Add to make the field cloneable (i.e. have multiple value)
        ),
        array(
            // Field name - Will be used as label
            'name' => '',
            // Field ID, i.e. the meta key
            'id' => "{$prefix}text5",
            // Field description (optional)
            'desc' => 'Text description',
            'type' => 'textarea',
            // Default value (optional)
            'std' => 'Default text value',
            'cols' => '20',
            'rows' => '2',
        // CLONES: Add to make the field cloneable (i.e. have multiple value)
        ),
    )
);

//competition meta box
$meta_boxes[] = array(
    // Meta box id, UNIQUE per meta box. Optional since 4.1.5
    'id' => 'competition-spotlight',
    // Meta box title - Will appear at the drag and drop handle bar. Required.
    'title' => 'Competition Spotlight',
    // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
    'pages' => array('page'),
    // Where the meta box appear: normal (default), advanced, side. Optional.
    'context' => 'normal',
    // Order of meta box: high (default), low. Optional.
    'priority' => 'high',
    // List of meta fields
    'fields' => array(
        // THICKBOX IMAGE UPLOAD (WP 3.3+)
        array(
            'name' => 'Image Thumbnail',
            'id' => "{$prefix}competition-image",
            'type' => 'thickbox_image',
        ),
        //url
        array(
            // Field name - Will be used as label
            'name' => 'url',
            // Field ID, i.e. the meta key
            'id' => "{$prefix}competition-url",
            // Field description (optional)
            'desc' => 'url for image',
            'type' => 'text',
            'size' => '60',
            // Default value (optional)
            'std' => 'url',
        // CLONES: Add to make the field cloneable (i.e. have multiple value)
        ),
    )
);

//2nd meta box. feature spotlight 1
$meta_boxes[] = array(
    // Meta box id, UNIQUE per meta box. Optional since 4.1.5
    'id' => 'feature-spotlight1',
    // Meta box title - Will appear at the drag and drop handle bar. Required.
    'title' => 'Feature Spotlight 1',
    // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
    'pages' => array('page'),
    // Where the meta box appear: normal (default), advanced, side. Optional.
    'context' => 'normal',
    // Order of meta box: high (default), low. Optional.
    'priority' => 'high',
    // List of meta fields
    'fields' => array(
        // TEXT
        array(
            // Field name - Will be used as label
            'name' => 'Title',
            // Field ID, i.e. the meta key
            'id' => "{$prefix}spotlight1",
            // Field description (optional)
            'desc' => 'Text description',
            'type' => 'text',
            // Default value (optional)
            'std' => 'Default text value',
        // CLONES: Add to make the field cloneable (i.e. have multiple value)
        ),
        // THICKBOX IMAGE UPLOAD (WP 3.3+)
        array(
            'name' => 'Image Thumbnail',
            'id' => "{$prefix}thickbox1",
            'type' => 'thickbox_image',
        ),
        array(
            // Field name - Will be used as label
            'name' => 'Description',
            // Field ID, i.e. the meta key
            'id' => "{$prefix}description1",
            // Field description (optional)
            'desc' => 'Text description',
            'type' => 'textarea',
            // Default value (optional)
            'std' => 'Default text value',
            'cols' => '20',
            'rows' => '2',
        // CLONES: Add to make the field cloneable (i.e. have multiple value)
        ),
        array(
            // Field name - Will be used as label
            'name' => 'url',
            // Field ID, i.e. the meta key
            'id' => "{$prefix}spotlight1-url",
            // Field description (optional)
            'desc' => 'url for view more',
            'type' => 'text',
            'size' => '60',
            // Default value (optional)
            'std' => 'url',
        // CLONES: Add to make the field cloneable (i.e. have multiple value)
        ),
    )
);


//3rd meta box. feature spotlight 2
$meta_boxes[] = array(
    // Meta box id, UNIQUE per meta box. Optional since 4.1.5
    'id' => 'feature-spotlight2',
    // Meta box title - Will appear at the drag and drop handle bar. Required.
    'title' => 'Feature Spotlight 2',
    // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
    'pages' => array('page'),
    // Where the meta box appear: normal (default), advanced, side. Optional.
    'context' => 'normal',
    // Order of meta box: high (default), low. Optional.
    'priority' => 'high',
    // List of meta fields
    'fields' => array(
        // TEXT
        array(
            // Field name - Will be used as label
            'name' => 'Title',
            // Field ID, i.e. the meta key
            'id' => "{$prefix}spotlight2",
            // Field description (optional)
            'desc' => 'Text description',
            'type' => 'text',
            // Default value (optional)
            'std' => 'Default text value',
        // CLONES: Add to make the field cloneable (i.e. have multiple value)
        ),
        // THICKBOX IMAGE UPLOAD (WP 3.3+)
        array(
            'name' => 'Image Thumbnail',
            'id' => "{$prefix}thickbox2",
            'type' => 'thickbox_image',
        ),
        array(
            // Field name - Will be used as label
            'name' => 'Description',
            // Field ID, i.e. the meta key
            'id' => "{$prefix}description2",
            // Field description (optional)
            'desc' => 'Text description',
            'type' => 'textarea',
            // Default value (optional)
            'std' => 'Default text value',
            'cols' => '20',
            'rows' => '2',
        // CLONES: Add to make the field cloneable (i.e. have multiple value)
        ),
        array(
            // Field name - Will be used as label
            'name' => 'url',
            // Field ID, i.e. the meta key
            'id' => "{$prefix}spotlight2-url",
            // Field description (optional)
            'desc' => 'url for view more',
            'type' => 'text',
            'size' => '60',
            // Default value (optional)
            'std' => 'Default text value',
        // CLONES: Add to make the field cloneable (i.e. have multiple value)
        ),
    )
);



//4th meta box. postcard
$meta_boxes[] = array(
    // Meta box id, UNIQUE per meta box. Optional since 4.1.5
    'id' => 'postcard',
    // Meta box title - Will appear at the drag and drop handle bar. Required.
    'title' => 'PostCard',
    // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
    'pages' => array('page'),
    // Where the meta box appear: normal (default), advanced, side. Optional.
    'context' => 'normal',
    // Order of meta box: high (default), low. Optional.
    'priority' => 'high',
    // List of meta fields
    'fields' => array(
        // TEXT
        array(
            // Field name - Will be used as label
            'name' => 'Title',
            // Field ID, i.e. the meta key
            'id' => "{$prefix}postcard",
            // Field description (optional)
            'desc' => 'Text description',
            'type' => 'text',
            // Default value (optional)
            'std' => 'title',
        // CLONES: Add to make the field cloneable (i.e. have multiple value)
        ),
        // THICKBOX IMAGE UPLOAD (WP 3.3+)
        array(
            'name' => 'Image Thumbnail',
            'id' => "{$prefix}postcard-image",
            'type' => 'thickbox_image',
        ),
    )
);



/* * ******************* META BOX REGISTERING ********************** */

/**
 * Register meta boxes
 *
 * @return void
 */
function YOUR_PREFIX_register_meta_boxes() {
    // Make sure there's no errors when the plugin is deactivated or during upgrade
    if (!class_exists('RW_Meta_Box'))
        return;
// Register meta boxes only for some posts/pages
    if (!rw_maybe_include())
        return;
    global $meta_boxes;
    foreach ($meta_boxes as $meta_box) {
        new RW_Meta_Box($meta_box);
    }
}

// Hook to 'admin_init' to make sure the meta box class is loaded before
// (in case using the meta box class in another plugin)
// This is also helpful for some conditionals like checking page template, categories, etc.
add_action('admin_init', 'YOUR_PREFIX_register_meta_boxes');

/**
 * Check if meta boxes is included
 *
 * @return bool
 */
function rw_maybe_include() {
    // Include in back-end only
    if (!defined('WP_ADMIN') || !WP_ADMIN)
        return false;

    // Always include for ajax
    if (defined('DOING_AJAX') && DOING_AJAX)
        return true;

    // Check for post IDs
    $checked_post_IDs = array('4');

    if (isset($_GET['post']))
        $post_id = $_GET['post'];
    elseif (isset($_POST['post_ID']))
        $post_id = $_POST['post_ID'];
    else
        $post_id = false;

    $post_id = (int) $post_id;

    if (in_array($post_id, $checked_post_IDs))
        return true;

    // Check for page template
    $checked_templates = array('full-width.php', 'sidebar-page.php');

    $template = get_post_meta($post_id, '_wp_page_template', true);
    if (in_array($template, $checked_templates))
        return true;

    // If no condition matched
    return false;
}
