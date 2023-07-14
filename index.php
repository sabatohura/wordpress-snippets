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
    
    // echo "this is our custom fields";
    wp_head();
    global $wpdb;

    global $post;
    $id = get_the_id();
    $ourdb = $wpdb->prefix."lms_course_details";
    $data = $wpdb->get_var("SELECT `*` FROM  `$ourdb` WHERE  `ID` = '".$id."'  ");
    while ($rows = $data->fetch_assoc()) {
        # code...

   ?>
    <div class="bg-blue-1">
        <div class="bg-red col-90">
            <h4><b>Course Price</b></h4>
            <input type="text" name="price" value = "<?=$rows["price"]?>">
        </div>
    </div>
  
    <?php
        }
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
        price text(9) NOT NULL,
        thumbnail text NOT NULL,
        content text NOT NULL,
        PRIMARY KEY(ID)
    ) $charset; ";
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($course_det);
}

register_activation_hook(__FILE__,'database_table');


/* save data to course details */

function save_custom_fields(){
    global $wpdb;  // to user database function features 

    // https://developer.wordpress.org/reference/functions/ link resource for most of the wp functions
    // to get post id 
    $Id = get_the_id();
    $Title = get_the_title();
    $Content = get_post_field('post_content', $Id);
    $Thumbnail = get_the_post_thumbnail_url();

    // populate with html form data 
    $price = $_POST['price'];

    $wpdb->insert(
        $wpdb->prefix.'lms_course_details',  // db table name
        [
            'ID' => $Id,
        ]
    );

    $wpdb->update(
        $wpdb->prefix.'lms_course_details',
        [
        'price' => $price,
        'title' =>$Title,
        'thumbnail' =>$Thumbnail,
        'content' => $Content,],
        ['ID' => $Id,]
    );
}
add_action('save_post', 'save_custom_fields');


/* to fetch and update recent data */ 
