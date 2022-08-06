<?php
/**
 * Plugin Name:       WP Data Summary
 * Description:       Small plugin that displays a summary of your post types and taxonomies.
 * Version:           1.0.0
 * Author:            Solid Digital
 * Author URI:        https://www.soliddigital.com
 * License:           GPLv2
 * Text Domain:       wp-data-summary
 * Domain Path:       /languages
 */

namespace wp_data_summary;

add_action('admin_menu', 'wp_data_summary\admin_menu');

function admin_menu() {
    add_options_page( 'WP Data Summary', 'WP Data Summary', 'manage_options', 'wp-data-summary', 'wp_data_summary\admin_page' );
}

function admin_page() {
    ?>
    <h1><?php _e('WP Data Summary', 'wp-data-summary') ?></h1>
    <?php

    the_post_types_table();
    the_taxonomies_table();
}

function the_post_types_table() {
    $post_type_objects = get_post_types(array(), 'objects');
    $post_types = array();

    foreach($post_type_objects as $post_type_object) {
        $counts = wp_count_posts($post_type_object->name);

        $post_types[] = array(
            'name' => $post_type_object->name,
            'label' => $post_type_object->label,
            'publish' => $counts->publish,
            'draft' => $counts->draft
        );
    }

    usort($post_types, function ($a, $b) {
        if ($a['publish'] === $b['publish']) return 0;
        return $a['publish'] > $b['publish'] ? -1 : 1;
    });
    ?>
    <h2><?php _e('Post Types', 'wp-data-summary') ?></h2>

    <table>
        <thead>
            <tr>
                <th><?php _e('Name', 'wp-data-summary') ?></th>
                <th><?php _e('Label', 'wp-data-summary') ?></th>
                <th><?php _e('Publish', 'wp-data-summary') ?></th>
                <th><?php _e('Draft', 'wp-data-summary') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($post_types as $post_type): ?>
                <tr>
                    <td><?php echo $post_type['name']; ?></td>
                    <td><?php echo $post_type['label']; ?></td>
                    <td><?php echo $post_type['publish']; ?></td>
                    <td><?php echo $post_type['draft']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    <?php
}

function the_taxonomies_table() {
    $taxonomy_objects = get_taxonomies(array(), 'objects');
    $taxonomies = array();

    foreach($taxonomy_objects as $taxonomy_object) {
        $count = wp_count_terms($taxonomy_object->name);

        $taxonomies[] = array(
            'name' => $taxonomy_object->name,
            'label' => $taxonomy_object->label,
            'object_type' => $taxonomy_object->object_type,
            'count' => $count
        );
    }

    usort($taxonomies, function ($a, $b) {
        if ($a['count'] === $b['count']) return 0;
        return $a['count'] > $b['count'] ? -1 : 1;
    });
    ?>
    <h2><?php _e('Taxonomies', 'wp-data-summary') ?></h2>
    <table>
        <thead>
            <tr>
                <th><?php _e('Name', 'wp-data-summary') ?></th>
                <th><?php _e('Label', 'wp-data-summary') ?></th>
                <th><?php _e('Object Type', 'wp-data-summary') ?></th>
                <th><?php _e('Count', 'wp-data-summary') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($taxonomies as $taxonomy): ?>
                <tr>
                    <td><?php echo $taxonomy['name']; ?></td>
                    <td><?php echo $taxonomy['label']; ?></td>
                    <td><?php echo join(', ', $taxonomy['object_type']); ?></td>
                    <td><?php echo $taxonomy['count']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
    <?php
}