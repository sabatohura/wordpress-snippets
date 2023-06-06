<?php

/*
* Plugin Name: Simple LMS Plugin
* Description: Learning Management system Plugin
* Version: 1.0.0
* Author: Sabato
* Author URI: sabatodev.com
*/


/* Register CPT Courses*/
/**
 * Register a custom post type called "course".
 *
 * @see get_post_type_labels() for label keys.
 */
function course_cpt() {
	$labels = array(
		'name'                  => _x( 'Courses', 'Post type general name', 'textdomain' ),
		'singular_name'         => _x( 'Course', 'Post type singular name', 'textdomain' ),

	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'course' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
        'menu_icon'   => 'dashicons-database-view',
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
	);

	register_post_type( 'course', $args );
}

add_action( 'init', 'course_cpt' );


/* Register CPT Students */
/**
 * Register a custom post type called "student".
 *
 * @see get_post_type_labels() for label keys.
 */
function student_cpt() {
	$labels = array(
		'name'                  => _x( 'Students', 'Post type general name', 'textdomain' ),
		'singular_name'         => _x( 'Student', 'Post type singular name', 'textdomain' ),

	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'student' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
        'menu_icon'   => 'dashicons-database-view',
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
	);

	register_post_type( 'student', $args );
}

add_action( 'init', 'student_cpt' );


// adding custom fields to courses 

function CF_Courses_Main()
{
    add_meta_box(
        "cf_courses_id", 
        "Course Custom Fields",  // CF Title
        "CF_Courses",  //custom field function
        "course", // registered Post type
        "normal", // position
        "low", // side
    );
}

function CF_Courses()
{
    
    echo "this is our custom fields";
    wp_head();
    ?>
    <div class="bg-blue-1">
        <div class="bg-red col-90">
            <h4><b>Course Price</b></h4>
            <input type="text">
        </div>
    </div>
  
    <?php
}

add_action("admin_init","CF_Courses_Main");

/* Include CSs file in Plugin */

function add_style()
{
    wp_register_style('style', plugin_dir_url(__FILE__).'scripts/style.css');
    wp_enqueue_style('style', plugin_dir_url(__FILE__).'scripts/style.css');
}
add_action('wp_enqueue_scripts','add_style'); 

/* load course template */ 

function template_courses($template)
{
    global $post;

    if('course' === $post->post_type && locate_template(array('template_courses')) !== $template)
    {
        return plugin_dir_path(__FILE__).'templates/template_courses.php';
    }
    return $template;
}

add_filter('single_template', 'template_courses');

/* create database course details */

function database_table(){
    global $wpdb;
    $database_table_name = $wpdb->prefix."lms_course_details";
    $charset = $wpdb->get_charset_collate;
    $course_det = "CREATE TABLE $database_table_name (
        ID int(9) NOT NULL,
        title text(100) NOT NULL,
        price int(9) NOT NULL,
        thumbnail text NOT NULL,
        content text NOT NULL,
        PRIMARY KEY(ID)
    ) $charset; ";
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($course_det);
}

register_activation_hook(__FILE__,'database_table');