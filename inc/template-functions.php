<?php
if (!defined("ABSPATH")) {
    die("You are not allowed to call this page directly.");
}

/**
 * OSH All Common Functions
 * @package OSH
 * @version 1.0.0
 */

function ohsa_login_check()
{
    global $user;

    if (!is_user_logged_in()) {
        wp_redirect(BASE_URL);
        exit();
    }
}

/**
 * Hide admin bar for front end users
 *
 * @return null
 *
 * @access all
 * @since  1.0.0
 */

if (!current_user_can("manage_options")) {
    add_filter("show_admin_bar", "__return_false");
}

/**
 * Creating custom pagination
 *
 * @return pagination
 *
 * @access all
 */
function bootstrap_pagination($total_pages, $echo = true, $params = [])
{
    $add_args = [];
    $pages = paginate_links(
        array_merge(
            [
                "base" => str_replace(
                    999999999,
                    "%#%",
                    esc_url(get_pagenum_link(999999999))
                ),
                "format" => "?paged=%#%",
                "current" => max(1, get_query_var("paged")),
                "total" => $total_pages,
                "type" => "array",
                "show_all" => false,
                "end_size" => 3,
                "mid_size" => 1,
                "prev_next" => true,
                "prev_text" => __("« Prev", "tutark"),
                "next_text" => __("Next »", "tutark"),
                "add_args" => $add_args,
                "add_fragment" => "",
            ],
            $params
        )
    );

    if (is_array($pages)) {
        $pagination = '<div class="pagination"><ul class="pagination">';

        foreach ($pages as $page) {
            $pagination .=
                '<li class="page-item' .
                (strpos($page, "current") !== false ? " active" : "") .
                '"> ' .
                str_replace("page-numbers", "page-link", $page) .
                "</li>";
        }

        $pagination .= "</ul></div>";

        if ($echo) {
            echo $pagination;
        } else {
            return $pagination;
        }
    }

    return null;
}

/**
 * Sending custom mail
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function send_custom_mail($user_id, $sub)
{
    $displayName = "";
    $user_email = "";
    $user_info = get_userdata($user_id); // gets user data
    if (empty($user_info)) {
        return "";
    }
    if (!empty($user_info->display_name)) {
        $displayName = $user_info->display_name;
    }
    if (!empty($user_info->user_email)) {
        $user_email = $user_info->user_email;
    }

    $body =
        '<table id="" style="background-color:#ffffff;border:1px solid #dedede;border-radius:3px" width="600" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td valign="top" align="center">
        <table id="" style="background-color:#1db6f2;color:#ffffff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;border-radius:3px 3px 0 0" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody><tr>
              <td id="" style="padding:36px 48px;display:block">
                <h1 style="font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;color:#ffffff;background-color:inherit">' .
        esc_html__("Welcome to OSH", "OSH") .
        '</h1>
              </td>
            </tr>
          </tbody></table>
          </td>
      </tr>
      <tr>
        <td valign="top" align="center">
        <table id="" width="600" cellspacing="0" cellpadding="0" border="0">
            <tbody><tr>
              <td id="" style="background-color:#ffffff" valign="top">
                
                <table width="100%" cellspacing="0" cellpadding="20" border="0">
                  <tbody><tr>
                    <td style="padding:48px 48px 32px" valign="top">
                      <div id="" style="color:#636363;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left">

  <p style="margin:0 0 16px">Hi ' .
        $displayName .
        ',</p>
  <p style="margin:0 0 16px">' .
        esc_html__("Thanks for creating an account on OSH.", "OSH") .
        '<br/><a href="#">' .
        esc_html__("Custom message", "OSH") .
        '</a>.</p>

  <p style="margin:0 0 16px">' .
        esc_html__("We look forward to seeing you soon", "OSH") .
        '.</p>
                        </div>
                      </td>
                    </tr>
                  </tbody></table>
                  
                </td>
              </tr>
            </tbody></table>
            
          </td>
        </tr>
      </tbody></table>';

    wc_mail($user_email, $sub, $body); // sends the email to the user
}

/**
 * Checking health check status
 *
 * @return 0 or 1
 * For production 31 and Staging 12
 * @since  1.0.0
 * @access all
 */
function checkDailyHealthCheckStatus()
{
    global $wpdb;
    $is_creted = 0;
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    $health_check_sql = $wpdb->prepare(
        "SELECT count(id) as count FROM `{$wpdb->prefix}gf_entry`  WHERE `created_by`=%d AND `form_id`=%d AND  date(`date_created`) = %s",
        $user_id,
        12,
        date("Y-m-d")
    );

    $is_creted = $wpdb->get_var($health_check_sql);
    return $is_creted;
}

/**
 * Checking health check count
 *
 * @return count no
 * For production 31 and Staging 12
 * @since  1.0.0
 * @access all
 */
function checkDailyHealthCheckedCount()
{
    global $wpdb;
    $getCount = 0;

    $health_check_sql = $wpdb->prepare(
        "SELECT count(id) as count FROM `{$wpdb->prefix}gf_entry`  WHERE `form_id`=%d AND  date(`date_created`) = %s",
        12,
        date("Y-m-d")
    );

    $getCount = $wpdb->get_var($health_check_sql);
    //echo 'qq=='.$wpdb->last_query;
    return $getCount;
}

/**
 * Course Lessons Count by course_id
 *
 * @return count number
 *
 * @since  1.0.0
 * @access all
 */
function getCourseLessonsCount($course_id)
{
    global $wpdb;
    $getCount = 0;

    $course_lession_sql = $wpdb->prepare(
        "SELECT count(section_id) as count FROM `{$wpdb->prefix}learnpress_sections`  WHERE `section_course_id`=%d",
        $course_id
    );
    $getCount = $wpdb->get_var($course_lession_sql);
    //echo 'qq=='.$wpdb->last_query;
    return $getCount;
}

/**
 * Getting quizzes count by course_id
 *
 * @return count number
 *
 * @since  1.0.0
 * @access all
 */
function getCourseQuizzesCount($course_id)
{
    global $wpdb;
    $getCount = 0;

    $course_lession_sql = $wpdb->prepare(
        "SELECT count(section_id) as count FROM `{$wpdb->prefix}learnpress_sections`  WHERE `section_course_id`=%d",
        $course_id
    );
    $getCount = $wpdb->get_var($course_lession_sql);
    //echo 'qq=='.$wpdb->last_query;
    return $getCount;
}

/**
 * Getting course trainee Count by course_id
 *
 * @return count number
 *
 * @since  1.0.0
 * @access all
 */
function getCourseTraineeCount($course_id)
{
    global $wpdb;
    $getCount = $regCount = $totalCount = 0;

    $course_lession_sql = $wpdb->prepare(
        "SELECT COUNT(*) FROM `{$wpdb->prefix}learnpress_user_items`  WHERE `item_id`=%d AND `status` = %s",
        $course_id,
        "enrolled"
    );
    $regCount = $wpdb->get_var($course_lession_sql);
    //echo 'qq=='.$wpdb->last_query;
    $getCount = get_post_meta($course_id, "_lp_students", true);
    $totalCount = (int) $regCount + (int) $getCount;

    return $totalCount;
}

/**
 * Getting course duration by key
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getCourseDurationListByKey($course_search_duration)
{
    global $wpdb;
    $metas = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT DISTINCT(meta_value) FROM $wpdb->postmeta where meta_key = %s",
            "_lp_duration"
        )
    );

    // Return null if we found no results
    if (!$metas) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="course_search_duration" id="course_search_duration" onchange="this.form.submit()">';
    $output .=
        "<option selected='" .
        ($course_search_duration == "" ? "selected" : "") .
        "' value=''>Durations</option>";

    foreach ($metas as $meta) {
        $output .=
            "<option selected='" .
            ($course_search_duration == $meta->meta_value ? "selected" : "") .
            "' value='" .
            $meta->meta_value .
            "'>" .
            $meta->meta_value .
            "</option>";
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all courses list
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllCourseList($course_search_title)
{
    //echo 'xxxxxxx='.$course_search_title;
    global $wpdb;

    $custom_post_type = "lp_course"; // define your custom post type slug here

    // A sql query to return all post titles
    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'",
            $custom_post_type
        ),
        ARRAY_A
    );

    // Return null if we found no results
    if (!$results) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="course_search_title" id="course_search_title" onchange="this.form.submit()">';

    $output .=
        "<option " .
        ($course_search_title == "" ? "selected" : "") .
        "value=''>Course</option>";

    foreach ($results as $post) {
        $output .=
            "<option " .
            ($course_search_title == $post["post_title"] ? "selected" : "") .
            " value='" .
            $post["post_title"] .
            "'>" .
            $post["post_title"] .
            "</option>";
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Search filter for title
 *
 * @return true/false
 *
 * @since  1.0.0
 * @access all
 */
function title_filter($where, $wp_query)
{
    global $wpdb;
    if ($search_term = $wp_query->get("course_search_title")) {
        $where .=
            " AND " .
            $wpdb->posts .
            '.post_title LIKE \'%' .
            esc_sql($search_term) .
            '%\'';
    }
    return $where;
}

add_filter("learn-press/override-templates", function () {
    return true;
});

/**
 * Creating custom post type for Left side menu
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_left_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x("Left Menu", "Post Type General Name", "ohs"),
        "singular_name" => _x("Left Menu", "Post Type Singular Name", "ohs"),
        "menu_name" => __("Left Menu", "ohs"),
        "parent_item_colon" => __("Parent Left Menu", "ohs"),
        "all_items" => __("All Left Menu", "ohs"),
        "view_item" => __("View Left Menu", "ohs"),
        "add_new_item" => __("Add New Left Menu", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit Left Menu", "ohs"),
        "update_item" => __("Update Left Menu", "ohs"),
        "search_items" => __("Search Left Menu", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("leftmenus", "ohs"),
        "description" => __("Left Menu news and reviews", "ohs"),
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "excerpt",
            "author",
            "thumbnail",
            "revisions",
            "custom-fields",
        ],
        "taxonomies" => ["genres"],
        "hierarchical" => false,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
    ];
    // Registering your Custom Post Type
    register_post_type("leftmenus", $args);
}
add_action("init", "admin_left_custom_post_type", 0);

/**
 * Creating custom post type for designations
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_designation_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x("Occupations", "Post Type General Name", "ohs"),
        "singular_name" => _x("Occupation", "Post Type Singular Name", "ohs"),
        "menu_name" => __("Occupations", "ohs"),
        "parent_item_colon" => __("Parent Occupation", "ohs"),
        "all_items" => __("All Occupation", "ohs"),
        "view_item" => __("View Occupation", "ohs"),
        "add_new_item" => __("Add New Occupation", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit Occupation", "ohs"),
        "update_item" => __("Update Occupation", "ohs"),
        "search_items" => __("Search Occupation", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("Occupations", "ohs"),
        "description" => __("Occupation news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "excerpt",
            "author",
            "thumbnail",
            "revisions",
            "custom-fields",
        ],
        "taxonomies" => ["genres"],
        "hierarchical" => false,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
    ];
    // Registering your Custom Post Type
    register_post_type("designations", $args);
}
add_action("init", "admin_designation_custom_post_type", 0);

/**
 * Creating custom post type for Department
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_department_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x("Department", "Post Type General Name", "ohs"),
        "singular_name" => _x("Department", "Post Type Singular Name", "ohs"),
        "menu_name" => __("Department", "ohs"),
        "parent_item_colon" => __("Parent Department", "ohs"),
        "all_items" => __("All Department", "ohs"),
        "view_item" => __("View Department", "ohs"),
        "add_new_item" => __("Add New Department", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit Department", "ohs"),
        "update_item" => __("Update Department", "ohs"),
        "search_items" => __("Search Department", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("Departments", "ohs"),
        "description" => __("Department news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "excerpt",
            "author",
            "thumbnail",
            "revisions",
            "custom-fields",
        ],
        "taxonomies" => ["genres"],
        "hierarchical" => false,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
    ];
    // Registering your Custom Post Type
    register_post_type("departments", $args);
}
add_action("init", "admin_department_custom_post_type", 0);

/**
 * Creating custom post type for Action Item Type
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_action_type_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x("Action Item Types", "Post Type General Name", "ohs"),
        "singular_name" => _x(
            "Action Item Types",
            "Post Type Singular Name",
            "ohs"
        ),
        "menu_name" => __("Action Item Types", "ohs"),
        "parent_item_colon" => __("Parent Department", "ohs"),
        "all_items" => __("All Type", "ohs"),
        "view_item" => __("View Type", "ohs"),
        "add_new_item" => __("Add New Type", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit Type", "ohs"),
        "update_item" => __("Update Type", "ohs"),
        "search_items" => __("Search Type", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("Action Item Types", "ohs"),
        "description" => __("Action Item Types", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => ["title", "editor", "excerpt", "author"],
        "taxonomies" => ["genres"],
        "hierarchical" => false,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        //'show_in_menu'  =>  'edit.php?post_type=allhraction',
        "taxonomies" => ["actioncategory"],
    ];
    // Registering your Custom Post Type
    register_post_type("action_types", $args);
}
add_action("init", "admin_action_type_custom_post_type", 0);

function create_action_category_taxonomies()
{
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = [
        "name" => _x("Categories", "taxonomy general name"),
        "singular_name" => _x("Category", "taxonomy singular name"),
        "search_items" => __("Search Category"),
        "all_items" => __("All Category"),
        "parent_item" => __("Parent Category"),
        "parent_item_colon" => __("Parent Category:"),
        "edit_item" => __("Edit Category"),
        "update_item" => __("Update Category"),
        "add_new_item" => __("Add New Category"),
        "new_item_name" => __("New Genre Category"),
        "menu_name" => __("Categories"),
    ];

    $args = [
        "hierarchical" => true,
        "labels" => $labels,
        "show_ui" => true,
        "show_admin_column" => true,
        "query_var" => true,
        //'rewrite'           => array( 'slug' => 'brands' ),
    ];

    register_taxonomy("actioncategory", ["action_types"], $args);
}
add_action("init", "create_action_category_taxonomies");

/**
 * Creating custom post type for All Actions
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_hractn_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x("All Action Items", "Post Type General Name", "ohs"),
        "singular_name" => _x(
            "All Action Items",
            "Post Type Singular Name",
            "ohs"
        ),
        "menu_name" => __("All Action Items", "ohs"),
        "parent_item_colon" => __("Parent HR Action", "ohs"),
        "all_items" => __("All  Action", "ohs"),
        "view_item" => __("View  Action", "ohs"),
        "add_new_item" => __("Add New  Action", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit  Action", "ohs"),
        "update_item" => __("Update  Action", "ohs"),
        "search_items" => __("Search  Action", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("Action", "ohs"),
        "description" => __("Action news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => ["title", "editor", "custom-fields"],
        "taxonomies" => ["genres"],
        "hierarchical" => true,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
    ];
    // Registering your Custom Post Type
    register_post_type("allhraction", $args);
}
add_action("init", "admin_hractn_custom_post_type", 0);

/**
 * Showing Action Tracking fields at wp admin
 *
 * @return url
 *
 * @since  1.0.0
 * @access all
 */

add_filter(
    "manage_allhraction_posts_columns",
    "set_custom_hr_act_edit_in_columns"
);
add_action(
    "manage_allhraction_posts_custom_column",
    "custom_hr_act_in_column",
    10,
    2
);

function set_custom_hr_act_edit_in_columns($columns)
{
    //unset( $columns['author'] );
    $columns["action_id"] = __("Action Id", "your_text_domain");
    $columns["action_type"] = __("Action Type", "your_text_domain");
    $columns["group_id"] = __("Group Name", "your_text_domain");
    $columns["assigned_to_user"] = __("Assigned To", "your_text_domain");
    $columns["assigned_by_user"] = __("Assigned By", "your_text_domain");
    $columns["supervisor_name"] = __("Supervisor", "your_text_domain");
    $columns["item_type"] = __("Item Type", "your_text_domain");
    $columns["item_name"] = __("Item Name", "your_text_domain");
    $columns["priority"] = __("Priority", "your_text_domain");
    $columns["due_date"] = __("Due Date", "your_text_domain");
    $columns["assigned_date"] = __("Assigned Date", "your_text_domain");
    $columns["completion_date"] = __("Completion Date", "your_text_domain");
    $columns["status"] = __("Status", "your_text_domain");

    return $columns;
}

function custom_hr_act_in_column($column, $post_id)
{
    switch ($column) {
        case "action_id":
            echo $ac_id = get_field("action_id", $post_id, true);
            break;

        case "action_type":
            echo $ac_id = get_field("action_type", $post_id, true);
            break;

        case "group_id":
            $group_id = get_field("group_id", $post_id, true);
            if (!empty($group_id)) {
                echo esc_html(get_the_title($group_id));
            }
            break;

        case "assigned_to_user":
            $assigned_to_user = get_field("assigned_to_user", $post_id, true);
            $author_obj = get_user_by("id", $assigned_to_user);
            $display_name = $author_obj->display_name;
            $user_email = $author_obj->user_email;
            echo $display_name . " (" . $user_email . ")";
            break;

        case "assigned_by_user":
            $assigned_by_user = get_field("assigned_by_user", $post_id, true);
            $author_obj = get_user_by("id", $assigned_by_user);
            $display_name = $author_obj->display_name;
            $user_email = $author_obj->user_email;
            echo $display_name . " (" . $user_email . ")";
            break;

        case "supervisor_name":
            $supervisor_name = get_field("supervisor_name", $post_id, true);
            // print_r($assigned_to_user);
            foreach ($supervisor_name as $key => $auid) {
                $author_obj = get_user_by("id", $auid);
                $display_name = $author_obj->display_name;
                $user_email = $author_obj->user_email;
                //echo $display_name.' ('.$user_email.')<br/>';
                echo $display_name . ",<br/>";
            }

            break;
        case "item_type":
            echo $item_type = get_field("item_type", $post_id, true);
            //echo get_the_title( $item_type );
            break;

        case "item_name":
            echo $item_name = get_field("item_name", $post_id, true);
            break;

        case "priority":
            echo $priority = get_field("priority", $post_id, true);
            break;

        case "due_date":
            echo $due_date = get_field("due_date", $post_id, true);

            break;

        case "assigned_date":
            echo $assigned_date = get_field("assigned_date", $post_id, true);
            break;

        case "completion_date":
            echo $completion_date = get_field(
                "completion_date",
                $post_id,
                true
            );
            break;

        case "status":
            $mo = get_field("mandatory_optional", $post_id, true);
            echo $status =
                get_field("status", $post_id, true) . "(" . $mo . ")";
            break;
    }
}

add_filter("manage_allhraction_posts_columns", function ($columns) {
    //$taken_out_date = $columns['date'];
    //$taken_out = $columns['author'];

    unset($columns["date"]);
    unset($columns["author"]);
    //$columns['date'] = $taken_out_date;
    //$columns['author'] = $taken_out;
    return $columns;
});

/**
 * Creating custom post type for Action Group List
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_actgr_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x("Action Group", "Post Type General Name", "ohs"),
        "singular_name" => _x("Action Group", "Post Type Singular Name", "ohs"),
        "menu_name" => __("Action Group", "ohs"),
        "parent_item_colon" => __("Parent Action", "ohs"),
        "all_items" => __("All Action Group", "ohs"),
        "view_item" => __("View Action Group", "ohs"),
        "add_new_item" => __("Add New Action Group", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit Action Group", "ohs"),
        "update_item" => __("Update Action Group", "ohs"),
        "search_items" => __("Search Action Group", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("Action Group", "ohs"),
        "description" => __("Action Group news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => ["title", "editor", "custom-fields"],
        "taxonomies" => ["genres"],
        "hierarchical" => true,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
    ];
    // Registering your Custom Post Type
    register_post_type("allactiongroup", $args);
}
add_action("init", "admin_actgr_custom_post_type", 0);

/**
 * Showing Action Group fields at wp admin
 *
 * @return url
 *
 * @since  1.0.0
 * @access all
 */

add_filter(
    "manage_allactiongroup_posts_columns",
    "set_custom_act_gr_edit_in_columns"
);
add_action(
    "manage_allactiongroup_posts_custom_column",
    "custom_act_gr_in_column",
    10,
    2
);

function set_custom_act_gr_edit_in_columns($columns)
{
    //unset( $columns['author'] );
    $columns["gr_desc"] = __("Group Description", "your_text_domain");
    $columns["assigned_to_user"] = __("Assigned To", "your_text_domain");
    $columns["assigned_by_user"] = __("Assigned By", "your_text_domain");
    $columns["assigned_date"] = __("Assigned Date", "your_text_domain");
    $columns["completion_date"] = __("Completion Date", "your_text_domain");
    $columns["status"] = __("Status", "your_text_domain");

    return $columns;
}

function custom_act_gr_in_column($column, $post_id)
{
    switch ($column) {
        case "gr_desc":
            echo $gr_desc = get_field("gr_desc", $post_id, true);
            break;

        case "assigned_to_user":
            $assigned_to_user = get_field("assigned_to_user", $post_id, true);
            // print_r($assigned_to_user);
            foreach ($assigned_to_user as $key => $auid) {
                $author_obj = get_user_by("id", $auid);
                $display_name = $author_obj->display_name;
                $user_email = $author_obj->user_email;
                //echo $display_name.' ('.$user_email.')<br/>';
                echo $display_name . ",<br/>";
            }
            break;

        case "assigned_by_user":
            $assigned_by_user = get_field("assigned_by_user", $post_id, true);
            $author_obj = get_user_by("id", $assigned_by_user);
            $display_name = $author_obj->display_name;
            $user_email = $author_obj->user_email;
            echo $display_name . " (" . $user_email . ")";
            break;

        case "assigned_date":
            echo $assigned_date = get_field("assigned_date", $post_id, true);
            break;

        case "completion_date":
            echo $completion_date = get_field(
                "completion_date",
                $post_id,
                true
            );
            break;

        case "status":
            echo $status = get_field("status", $post_id, true);
            break;
    }
}

add_filter("manage_allactiongroup_posts_columns", function ($columns) {
    //$taken_out_date = $columns['date'];
    //$taken_out = $columns['author'];

    unset($columns["date"]);
    unset($columns["author"]);
    //$columns['date'] = $taken_out_date;
    //$columns['author'] = $taken_out;
    return $columns;
});

/**
 * Creating custom post type for announcements
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_announcement_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x("All Announcements", "Post Type General Name", "ohs"),
        "singular_name" => _x("Announcement", "Post Type Singular Name", "ohs"),
        "menu_name" => __("Announcements", "ohs"),
        "parent_item_colon" => __("Parent Announcement", "ohs"),
        "all_items" => __("All Announcement", "ohs"),
        "view_item" => __("View Announcement", "ohs"),
        "add_new_item" => __("Add New Announcement", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit Announcement", "ohs"),
        "update_item" => __("Update Announcement", "ohs"),
        "search_items" => __("Search Announcement", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("Announcements", "ohs"),
        "description" => __("Announcement news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "author",
            "thumbnail",
            "revisions",
            "custom-fields",
        ],
        "taxonomies" => ["genres"],
        "hierarchical" => false,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        "taxonomies" => ["annocategory"],
    ];
    // Registering your Custom Post Type
    register_post_type("allannouncements", $args);
}
add_action("init", "admin_announcement_custom_post_type", 0);

function create_anno_category_taxonomies()
{
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = [
        "name" => _x("Categories", "taxonomy general name"),
        "singular_name" => _x("Category", "taxonomy singular name"),
        "search_items" => __("Search Category"),
        "all_items" => __("All Category"),
        "parent_item" => __("Parent Category"),
        "parent_item_colon" => __("Parent Category:"),
        "edit_item" => __("Edit Category"),
        "update_item" => __("Update Category"),
        "add_new_item" => __("Add New Category"),
        "new_item_name" => __("New Genre Category"),
        "menu_name" => __("Categories"),
    ];

    $args = [
        "hierarchical" => true,
        "labels" => $labels,
        "show_ui" => true,
        "show_admin_column" => true,
        "query_var" => true,
        //'rewrite'           => array( 'slug' => 'brands' ),
    ];

    register_taxonomy("annocategory", ["allannouncements"], $args);
}
add_action("init", "create_anno_category_taxonomies");

/**
 * Creating custom post type for Reports
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_report_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x("All Reports", "Post Type General Name", "ohs"),
        "singular_name" => _x("Report", "Post Type Singular Name", "ohs"),
        "menu_name" => __("Reports", "ohs"),
        "parent_item_colon" => __("Parent Report", "ohs"),
        "all_items" => __("All Report", "ohs"),
        "view_item" => __("View Report", "ohs"),
        "add_new_item" => __("Add New Report", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit Report", "ohs"),
        "update_item" => __("Update Report", "ohs"),
        "search_items" => __("Search Report", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("Reports", "ohs"),
        "description" => __("Report news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "excerpt",
            "author",
            "thumbnail",
            "revisions",
            "custom-fields",
        ],
        "taxonomies" => ["genres"],
        "hierarchical" => false,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        "taxonomies" => ["reportcategory"],
    ];
    // Registering your Custom Post Type
    register_post_type("allreports", $args);
}
add_action("init", "admin_report_custom_post_type", 0);

function create_report_category_taxonomies()
{
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = [
        "name" => _x("Categories", "taxonomy general name"),
        "singular_name" => _x("Category", "taxonomy singular name"),
        "search_items" => __("Search Category"),
        "all_items" => __("All Category"),
        "parent_item" => __("Parent Category"),
        "parent_item_colon" => __("Parent Category:"),
        "edit_item" => __("Edit Category"),
        "update_item" => __("Update Category"),
        "add_new_item" => __("Add New Category"),
        "new_item_name" => __("New Genre Category"),
        "menu_name" => __("Categories"),
    ];

    $args = [
        "hierarchical" => true,
        "labels" => $labels,
        "show_ui" => true,
        "show_admin_column" => true,
        "query_var" => true,
        //'rewrite'           => array( 'slug' => 'brands' ),
    ];

    register_taxonomy("reportcategory", ["allreports"], $args);
}
add_action("init", "create_report_category_taxonomies");

/**
 * Creating custom post type for Finance
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_finance_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x("All Finance", "Post Type General Name", "ohs"),
        "singular_name" => _x("Finance", "Post Type Singular Name", "ohs"),
        "menu_name" => __("Finance", "ohs"),
        "parent_item_colon" => __("Parent Finance", "ohs"),
        "all_items" => __("All Finance", "ohs"),
        "view_item" => __("View Finance", "ohs"),
        "add_new_item" => __("Add New Finance", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit Finance", "ohs"),
        "update_item" => __("Update Finance", "ohs"),
        "search_items" => __("Search Finance", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("Finance", "ohs"),
        "description" => __("Finance news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "excerpt",
            "author",
            "thumbnail",
            "revisions",
            "custom-fields",
        ],
        "taxonomies" => ["genres"],
        "hierarchical" => false,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        "taxonomies" => ["financecategory"],
    ];
    // Registering your Custom Post Type
    register_post_type("allfinance", $args);
}
add_action("init", "admin_finance_custom_post_type", 0);

function create_finance_category_taxonomies()
{
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = [
        "name" => _x("Categories", "taxonomy general name"),
        "singular_name" => _x("Category", "taxonomy singular name"),
        "search_items" => __("Search Category"),
        "all_items" => __("All Category"),
        "parent_item" => __("Parent Category"),
        "parent_item_colon" => __("Parent Category:"),
        "edit_item" => __("Edit Category"),
        "update_item" => __("Update Category"),
        "add_new_item" => __("Add New Category"),
        "new_item_name" => __("New Genre Category"),
        "menu_name" => __("Categories"),
    ];

    $args = [
        "hierarchical" => true,
        "labels" => $labels,
        "show_ui" => true,
        "show_admin_column" => true,
        "query_var" => true,
        //'rewrite'           => array( 'slug' => 'brands' ),
    ];

    register_taxonomy("financecategory", ["allfinance"], $args);
}
add_action("init", "create_finance_category_taxonomies");

/**
 * Creating custom post type for IT
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_it_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x("All IT", "Post Type General Name", "ohs"),
        "singular_name" => _x("IT", "Post Type Singular Name", "ohs"),
        "menu_name" => __("IT", "ohs"),
        "parent_item_colon" => __("Parent IT", "ohs"),
        "all_items" => __("All IT", "ohs"),
        "view_item" => __("View IT", "ohs"),
        "add_new_item" => __("Add New IT", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit IT", "ohs"),
        "update_item" => __("Update IT", "ohs"),
        "search_items" => __("Search IT", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("IT", "ohs"),
        "description" => __("IT news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "excerpt",
            "author",
            "thumbnail",
            "revisions",
            "custom-fields",
        ],
        "taxonomies" => ["genres"],
        "hierarchical" => false,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        "taxonomies" => ["itcategory"],
    ];
    // Registering your Custom Post Type
    register_post_type("allit", $args);
}
add_action("init", "admin_it_custom_post_type", 0);

function create_it_category_taxonomies()
{
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = [
        "name" => _x("Categories", "taxonomy general name"),
        "singular_name" => _x("Category", "taxonomy singular name"),
        "search_items" => __("Search Category"),
        "all_items" => __("All Category"),
        "parent_item" => __("Parent Category"),
        "parent_item_colon" => __("Parent Category:"),
        "edit_item" => __("Edit Category"),
        "update_item" => __("Update Category"),
        "add_new_item" => __("Add New Category"),
        "new_item_name" => __("New Genre Category"),
        "menu_name" => __("Categories"),
    ];

    $args = [
        "hierarchical" => true,
        "labels" => $labels,
        "show_ui" => true,
        "show_admin_column" => true,
        "query_var" => true,
        //'rewrite'           => array( 'slug' => 'brands' ),
    ];

    register_taxonomy("itcategory", ["allit"], $args);
}
add_action("init", "create_it_category_taxonomies");

/**
 * Creating custom post type for Inclusive Education
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_inclusive_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x(
            "All Inclusive Education",
            "Post Type General Name",
            "ohs"
        ),
        "singular_name" => _x(
            "Inclusive Education",
            "Post Type Singular Name",
            "ohs"
        ),
        "menu_name" => __("Inclusive Education", "ohs"),
        "parent_item_colon" => __("Parent Inclusive", "ohs"),
        "all_items" => __("All Inclusive", "ohs"),
        "view_item" => __("View Inclusive", "ohs"),
        "add_new_item" => __("Add New Inclusive", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit Inclusive", "ohs"),
        "update_item" => __("Update Inclusive", "ohs"),
        "search_items" => __("Search Inclusive", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("Inclusive", "ohs"),
        "description" => __("Inclusive news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "excerpt",
            "author",
            "thumbnail",
            "revisions",
            "custom-fields",
        ],
        "taxonomies" => ["genres"],
        "hierarchical" => false,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        "taxonomies" => ["inclusivecategory"],
    ];
    // Registering your Custom Post Type
    register_post_type("allinclusive", $args);
}
add_action("init", "admin_inclusive_custom_post_type", 0);

function create_inclusive_category_taxonomies()
{
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = [
        "name" => _x("Categories", "taxonomy general name"),
        "singular_name" => _x("Category", "taxonomy singular name"),
        "search_items" => __("Search Category"),
        "all_items" => __("All Category"),
        "parent_item" => __("Parent Category"),
        "parent_item_colon" => __("Parent Category:"),
        "edit_item" => __("Edit Category"),
        "update_item" => __("Update Category"),
        "add_new_item" => __("Add New Category"),
        "new_item_name" => __("New Genre Category"),
        "menu_name" => __("Categories"),
    ];

    $args = [
        "hierarchical" => true,
        "labels" => $labels,
        "show_ui" => true,
        "show_admin_column" => true,
        "query_var" => true,
        //'rewrite'           => array( 'slug' => 'brands' ),
    ];

    register_taxonomy("inclusivecategory", ["allinclusive"], $args);
}
add_action("init", "create_inclusive_category_taxonomies");

/**
 * Creating custom post type for Document Package
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_docpack_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x("All Document Package", "Post Type General Name", "ohs"),
        "singular_name" => _x(
            "Document Package",
            "Post Type Singular Name",
            "ohs"
        ),
        "menu_name" => __("Document Package", "ohs"),
        "parent_item_colon" => __("Parent Package", "ohs"),
        "all_items" => __("All Package", "ohs"),
        "view_item" => __("View Package", "ohs"),
        "add_new_item" => __("Add New Package", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit Package", "ohs"),
        "update_item" => __("Update Package", "ohs"),
        "search_items" => __("Search Package", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("Package", "ohs"),
        "description" => __("Package news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "excerpt",
            "author",
            "thumbnail",
            "revisions",
            "custom-fields",
        ],
        "taxonomies" => ["genres"],
        "hierarchical" => false,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 6,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        "taxonomies" => ["docpackcategory"],
    ];
    // Registering your Custom Post Type
    register_post_type("alldocpack", $args);
}
add_action("init", "admin_docpack_custom_post_type", 0);

function create_docpack_category_taxonomies()
{
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = [
        "name" => _x("Categories", "taxonomy general name"),
        "singular_name" => _x("Category", "taxonomy singular name"),
        "search_items" => __("Search Category"),
        "all_items" => __("All Category"),
        "parent_item" => __("Parent Category"),
        "parent_item_colon" => __("Parent Category:"),
        "edit_item" => __("Edit Category"),
        "update_item" => __("Update Category"),
        "add_new_item" => __("Add New Category"),
        "new_item_name" => __("New Genre Category"),
        "menu_name" => __("Categories"),
    ];

    $args = [
        "hierarchical" => true,
        "labels" => $labels,
        "show_ui" => true,
        "show_admin_column" => true,
        "query_var" => true,
        //'rewrite'           => array( 'slug' => 'brands' ),
    ];

    register_taxonomy("docpackcategory", ["alldocpack"], $args);
}
add_action("init", "create_docpack_category_taxonomies");

/**
 * Creating custom post type for Documents Under Package
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_doc_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x("All Documents", "Post Type General Name", "ohs"),
        "singular_name" => _x("Documents", "Post Type Singular Name", "ohs"),
        "menu_name" => __("Documents", "ohs"),
        "parent_item_colon" => __("Parent Document", "ohs"),
        "all_items" => __("All Document", "ohs"),
        "view_item" => __("View Document", "ohs"),
        "add_new_item" => __("Add New Document", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit Document", "ohs"),
        "update_item" => __("Update Document", "ohs"),
        "search_items" => __("Search Document", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("Document", "ohs"),
        "description" => __("Document news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "excerpt",
            "author",
            "thumbnail",
            "revisions",
            "custom-fields",
        ],
        "taxonomies" => ["genres"],
        "hierarchical" => true,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        "show_in_menu" => "edit.php?post_type=alldocpack",
    ];
    // Registering your Custom Post Type
    register_post_type("alldoc", $args);
}
add_action("init", "admin_doc_custom_post_type", 0);

/**
 * Creating custom post type for Display Forms at front-end
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
// function admin_forms_custom_post_type() {
// // Set UI labels for Custom Post Type
//     $labels = array(
//         'name'                => _x( 'All Display Forms', 'Post Type General Name', 'ohs' ),
//         'singular_name'       => _x( 'Display Form', 'Post Type Singular Name', 'ohs' ),
//         'menu_name'           => __( 'Display Forms', 'ohs' ),
//         'parent_item_colon'   => __( 'Parent Form', 'ohs' ),
//         'all_items'           => __( 'All Display Forms', 'ohs' ),
//         'view_item'           => __( 'View Form', 'ohs' ),
//         'add_new_item'        => __( 'Add New Form', 'ohs' ),
//         'add_new'             => __( 'Add New', 'ohs' ),
//         'edit_item'           => __( 'Edit Form', 'ohs' ),
//         'update_item'         => __( 'Update Form', 'ohs' ),
//         'search_items'        => __( 'Search Form', 'ohs' ),
//         'not_found'           => __( 'Not Found', 'ohs' ),
//         'not_found_in_trash'  => __( 'Not found in Trash', 'ohs' ),
//     );
// // Set other options for Custom Post Type
//     $args = array(
//         'label'               => __( 'Display Forms', 'ohs' ),
//         'description'         => __( 'Form news and reviews', 'ohs' ),
//         'labels'              => $labels,
//         'labels'              => $labels,
//         'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'custom-fields', ),
//         'taxonomies'          => array( 'genres' ),
//         'hierarchical'        => false,
//         'public'              => true,
//         'show_ui'             => true,
//         'show_in_menu'        => true,
//         'show_in_nav_menus'   => true,
//         'show_in_admin_bar'   => true,
//         'menu_position'       => 5,
//         'can_export'          => true,
//         'has_archive'         => true,
//         'exclude_from_search' => false,
//         'publicly_queryable'  => true,
//         'capability_type'     => 'post',
//         'show_in_rest' => true,
//         'taxonomies'          => array( 'formscategory' )
//     );
//     // Registering your Custom Post Type
//     register_post_type( 'displayforms', $args );
// }
// add_action( 'init', 'admin_forms_custom_post_type', 0 );

// function create_forms_category_taxonomies() {
//   // Add new taxonomy, make it hierarchical (like categories)
//   $labels = array(
//     'name'              => _x( 'Categories', 'taxonomy general name' ),
//     'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
//     'search_items'      => __( 'Search Category' ),
//     'all_items'         => __( 'All Category' ),
//     'parent_item'       => __( 'Parent Category' ),
//     'parent_item_colon' => __( 'Parent Category:' ),
//     'edit_item'         => __( 'Edit Category' ),
//     'update_item'       => __( 'Update Category' ),
//     'add_new_item'      => __( 'Add New Category' ),
//     'new_item_name'     => __( 'New Genre Category' ),
//     'menu_name'         => __( 'Categories' ),
//   );

//   $args = array(
//     'hierarchical'      => true,
//     'labels'            => $labels,
//     'show_ui'           => true,
//     'show_admin_column' => true,
//     'query_var'         => true,
//     //'rewrite'           => array( 'slug' => 'brands' ),
//   );

//   register_taxonomy( 'formscategory', array( 'displayforms' ), $args );
// }
// add_action( 'init', 'create_forms_category_taxonomies' );

/**
 * Creating custom post type for GF entries for Forms
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_gf_entries_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x(
            "All Gravity Form entries",
            "Post Type General Name",
            "ohs"
        ),
        "singular_name" => _x("GF entries", "Post Type Singular Name", "ohs"),
        "menu_name" => __("Gravity Form entries", "ohs"),
        "parent_item_colon" => __("Parent Document", "ohs"),
        "all_items" => __("All GF entries", "ohs"),
        "view_item" => __("View GF entries", "ohs"),
        "add_new_item" => __("Add New GF entries", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit GF entries", "ohs"),
        "update_item" => __("Update GF entries", "ohs"),
        "search_items" => __("Search GF entries", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("GF entries", "ohs"),
        "description" => __("GF entries news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "excerpt",
            "author",
            "thumbnail",
            "revisions",
            "custom-fields",
        ],
        "taxonomies" => ["genres"],
        "hierarchical" => true,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        //'show_in_menu'  =>  'edit.php?post_type=gf_entry_page',
    ];
    // Registering your Custom Post Type
    register_post_type("gf_entries", $args);
}
add_action("init", "admin_gf_entries_custom_post_type", 0);

/**
 * Creating custom post type for Incident Tracking
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_incident_tracking_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x("All Incident Tracking", "Post Type General Name", "ohs"),
        "singular_name" => _x(
            "Incident Tracking",
            "Post Type Singular Name",
            "ohs"
        ),
        "menu_name" => __("Incident Tracking", "ohs"),
        "parent_item_colon" => __("Parent Document", "ohs"),
        "all_items" => __("All Incident Tracking", "ohs"),
        "view_item" => __("View Incident Tracking", "ohs"),
        "add_new_item" => __("Add New Incident Tracking", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit Incident Tracking", "ohs"),
        "update_item" => __("Update Incident Tracking", "ohs"),
        "search_items" => __("Search Incident Tracking", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("Incident Tracking", "ohs"),
        "description" => __("Incident Tracking news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "excerpt",
            "author",
            "thumbnail",
            "revisions",
            "custom-fields",
        ],
        "taxonomies" => ["genres"],
        "hierarchical" => true,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        //'show_in_menu'  =>  'edit.php?post_type=gf_entry_page',
    ];
    // Registering your Custom Post Type
    register_post_type("incident_tracking", $args);
}
add_action("init", "admin_incident_tracking_custom_post_type", 0);

/**
 * Creating custom post type for Incident Tracking WorkSafeBC Form 6A Request
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_worksafebc6a_req_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x(
            "All WorkSafeBC Form 6A Request",
            "Post Type General Name",
            "ohs"
        ),
        "singular_name" => _x(
            "WorkSafeBC 6A",
            "Post Type Singular Name",
            "ohs"
        ),
        "menu_name" => __("WorkSafeBC 6A", "ohs"),
        "parent_item_colon" => __("Parent Document", "ohs"),
        "all_items" => __("All WorkSafeBC 6A Req.", "ohs"),
        "view_item" => __("View WorkSafeBC 6A", "ohs"),
        "add_new_item" => __("Add New WorkSafeBC 6A", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit WorkSafeBC 6A", "ohs"),
        "update_item" => __("Update WorkSafeBC 6A", "ohs"),
        "search_items" => __("Search WorkSafeBC 6A", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("WorkSafeBC Form 6A", "ohs"),
        "description" => __("WorkSafeBC Form 6A news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "excerpt",
            "author",
            "thumbnail",
            "revisions",
            "custom-fields",
        ],
        "taxonomies" => ["genres"],
        "hierarchical" => true,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        "show_in_menu" => "edit.php?post_type=incident_tracking",
    ];
    // Registering your Custom Post Type
    register_post_type("worksafebc6a_req", $args);
}
add_action("init", "admin_worksafebc6a_req_custom_post_type", 0);

/**
 * Creating custom post type for Incident Tracking WorkSafeBC Form 6A Receive
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_worksafebc6a_rec_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x(
            "All WorkSafeBC Form 6A Receive",
            "Post Type General Name",
            "ohs"
        ),
        "singular_name" => _x(
            "WorkSafeBC 6A",
            "Post Type Singular Name",
            "ohs"
        ),
        "menu_name" => __("WorkSafeBC 6A", "ohs"),
        "parent_item_colon" => __("Parent Document", "ohs"),
        "all_items" => __("All WorkSafeBC 6A Rec..", "ohs"),
        "view_item" => __("View WorkSafeBC 6A", "ohs"),
        "add_new_item" => __("Add New WorkSafeBC 6A", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit WorkSafeBC 6A", "ohs"),
        "update_item" => __("Update WorkSafeBC 6A", "ohs"),
        "search_items" => __("Search WorkSafeBC 6A", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("WorkSafeBC Form 6A", "ohs"),
        "description" => __("WorkSafeBC Form 6A news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "excerpt",
            "author",
            "thumbnail",
            "revisions",
            "custom-fields",
        ],
        "taxonomies" => ["genres"],
        "hierarchical" => true,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        "show_in_menu" => "edit.php?post_type=incident_tracking",
    ];
    // Registering your Custom Post Type
    register_post_type("worksafebc6a_rec", $args);
}
add_action("init", "admin_worksafebc6a_rec_custom_post_type", 0);

/**
 * Creating custom post type for Incident Tracking WorkSafeBC Form 7
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_worksafebc7_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x("All WorkSafeBC Form 7", "Post Type General Name", "ohs"),
        "singular_name" => _x("WorkSafeBC 7", "Post Type Singular Name", "ohs"),
        "menu_name" => __("WorkSafeBC 7", "ohs"),
        "parent_item_colon" => __("Parent Document", "ohs"),
        "all_items" => __("All WorkSafeBC 7", "ohs"),
        "view_item" => __("View WorkSafeBC 7", "ohs"),
        "add_new_item" => __("Add New WorkSafeBC 7", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit WorkSafeBC 7", "ohs"),
        "update_item" => __("Update WorkSafeBC 7", "ohs"),
        "search_items" => __("Search WorkSafeBC 7", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("WorkSafeBC Form 7", "ohs"),
        "description" => __("WorkSafeBC Form 7 news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "excerpt",
            "author",
            "thumbnail",
            "revisions",
            "custom-fields",
        ],
        "taxonomies" => ["genres"],
        "hierarchical" => true,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        "show_in_menu" => "edit.php?post_type=incident_tracking",
    ];
    // Registering your Custom Post Type
    register_post_type("worksafebc7", $args);
}
add_action("init", "admin_worksafebc7_custom_post_type", 0);

/**
 * Creating custom post type for FAQ
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_faq_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x("All FAQ", "Post Type General Name", "ohs"),
        "singular_name" => _x("FAQ", "Post Type Singular Name", "ohs"),
        "menu_name" => __("FAQ", "ohs"),
        "parent_item_colon" => __("Parent FAQ", "ohs"),
        "all_items" => __("All FAQ", "ohs"),
        "view_item" => __("View FAQ", "ohs"),
        "add_new_item" => __("Add New FAQ", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit FAQ", "ohs"),
        "update_item" => __("Update FAQ", "ohs"),
        "search_items" => __("Search FAQ", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("FAQ", "ohs"),
        "description" => __("FAQ news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "excerpt",
            "author",
            "thumbnail",
            "revisions",
            "custom-fields",
        ],
        "taxonomies" => ["genres"],
        "hierarchical" => false,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 5,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        "taxonomies" => ["faqcategory"],
    ];
    // Registering your Custom Post Type
    register_post_type("allfaq", $args);
}
add_action("init", "admin_faq_custom_post_type", 0);

function create_faq_category_taxonomies()
{
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = [
        "name" => _x("Categories", "taxonomy general name"),
        "singular_name" => _x("Category", "taxonomy singular name"),
        "search_items" => __("Search Category"),
        "all_items" => __("All Category"),
        "parent_item" => __("Parent Category"),
        "parent_item_colon" => __("Parent Category:"),
        "edit_item" => __("Edit Category"),
        "update_item" => __("Update Category"),
        "add_new_item" => __("Add New Category"),
        "new_item_name" => __("New Genre Category"),
        "menu_name" => __("Categories"),
    ];

    $args = [
        "hierarchical" => true,
        "labels" => $labels,
        "show_ui" => true,
        "show_admin_column" => true,
        "query_var" => true,
        //'rewrite'           => array( 'slug' => 'brands' ),
    ];

    register_taxonomy("faqcategory", ["allfaq"], $args);
}
add_action("init", "create_faq_category_taxonomies");

/**
 * Creating custom post type for Portal
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function admin_portal_custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        "name" => _x("Portals", "Post Type General Name", "ohs"),
        "singular_name" => _x("Portal", "Post Type Singular Name", "ohs"),
        "menu_name" => __("Portals", "ohs"),
        "parent_item_colon" => __("Parent Portal", "ohs"),
        "all_items" => __("All Portal", "ohs"),
        "view_item" => __("View Portal", "ohs"),
        "add_new_item" => __("Add New Portal", "ohs"),
        "add_new" => __("Add New", "ohs"),
        "edit_item" => __("Edit Portal", "ohs"),
        "update_item" => __("Update Portal", "ohs"),
        "search_items" => __("Search Portal", "ohs"),
        "not_found" => __("Not Found", "ohs"),
        "not_found_in_trash" => __("Not found in Trash", "ohs"),
    ];
    // Set other options for Custom Post Type
    $args = [
        "label" => __("Portals", "ohs"),
        "description" => __("Portal news and reviews", "ohs"),
        "labels" => $labels,
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "excerpt",
            "thumbnail",
            "custom-fields",
        ],
        "taxonomies" => ["genres"],
        "hierarchical" => false,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "show_in_admin_bar" => true,
        "menu_position" => 10,
        "can_export" => true,
        "has_archive" => true,
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
    ];
    // Registering your Custom Post Type
    register_post_type("portals", $args);
}
add_action("init", "admin_portal_custom_post_type", 0);

/**
 * Getting all designation list
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllDesignationList($user_designation)
{
    global $wpdb;

    $custom_post_type = "designations"; // define your custom post type slug here

    // A sql query to return all post titles
    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'",
            $custom_post_type
        ),
        ARRAY_A
    );

    // Return null if we found no results
    if (!$results) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="user_designation" id="user_designation">';

    //$output .=  "<option " . ( $user_designation == '' ? 'selected' : '' ) . " value=''>Designation</option>";

    foreach ($results as $index => $post) {
        $output .=
            "<option " .
            ($user_designation == $post["post_title"] ? "selected" : "") .
            " value='" .
            $post["post_title"] .
            "'>" .
            $post["post_title"] .
            "</option>";
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all department lists
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllDepartmentList($user_department)
{
    global $wpdb;

    $custom_post_type = "departments"; // define your custom post type slug here

    // A sql query to return all post titles
    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'",
            $custom_post_type
        ),
        ARRAY_A
    );

    // Return null if we found no results
    if (!$results) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="user_department" id="user_department">';

    //$output .=  "<option " . ( $user_department == '' ? 'selected' : '' ) . " value=''>Department</option>";

    foreach ($results as $index => $post) {
        $output .=
            "<option " .
            ($user_department == $post["post_title"] ? "selected" : "") .
            " value='" .
            $post["post_title"] .
            "'>" .
            $post["post_title"] .
            "</option>";
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all designation search list
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllDesignationSearchList($user_serach_designation)
{
    global $wpdb;
    //echo 'test=='.$user_serach_designation;
    $custom_post_type = "designations"; // define your custom post type slug here

    // A sql query to return all post titles
    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'",
            $custom_post_type
        ),
        ARRAY_A
    );

    // Return null if we found no results
    if (!$results) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="user_serach_designation" id="user_serach_designation"  onchange="this.form.submit()">';

    $output .= "<option value='Occupations'>Occupations</option>";

    foreach ($results as $index => $post) {
        $output .=
            "<option " .
            ($user_serach_designation == $post["post_title"]
                ? "selected"
                : "") .
            " value='" .
            $post["post_title"] .
            "'>" .
            $post["post_title"] .
            "</option>";
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all designation search list
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllDepartmentSearchList($user_serach_department)
{
    global $wpdb;
    //echo 'test=='.$user_serach_designation;
    $custom_post_type = "departments"; // define your custom post type slug here

    // A sql query to return all post titles
    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = %s and post_status = 'publish'",
            $custom_post_type
        ),
        ARRAY_A
    );

    // Return null if we found no results
    if (!$results) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="user_serach_department" id="user_serach_department"  onchange="this.form.submit()">';

    $output .= "<option value='Departments'>Departments</option>";

    foreach ($results as $index => $post) {
        $output .=
            "<option " .
            ($user_serach_department == $post["post_title"] ? "selected" : "") .
            " value='" .
            $post["post_title"] .
            "'>" .
            $post["post_title"] .
            "</option>";
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all users location meta value list
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllUsersLocationList($user_serach_location)
{
    global $wpdb;

    // A sql query to return all post titles
    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT meta_value FROM {$wpdb->usermeta} WHERE meta_key = %s",
            "user_location"
        ),
        ARRAY_A
    );

    // Return null if we found no results
    if (!$results) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="user_serach_location" id="user_serach_location"  onchange="this.form.submit()">';

    $output .= "<option value='Location'>Location</option>";

    foreach ($results as $index => $post) {
        if (!empty($post["meta_value"])) {
            $output .=
                "<option " .
                ($user_serach_location == $post["meta_value"]
                    ? "selected"
                    : "") .
                " value='" .
                $post["meta_value"] .
                "'>" .
                $post["meta_value"] .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all users list
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllUsersList($gf_user_id)
{
    global $wpdb, $users;

    // A sql query to return all post titles
    // $results = $wpdb->get_results( $wpdb->prepare( "SELECT ID, display_name FROM {$wpdb->users} WHERE 1 "), ARRAY_A );

    $args = [
        "role" => "employee",
        //'orderby' => 'id',
        "number" => 300,
        "order" => "ASC",
    ];
    $results = get_users($args);

    // Return null if we found no results
    if (!$results) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="gf_user_id" id="gf_user_id"  onchange="this.form.submit()">';

    $output .= "<option value='Users'>Users</option>";

    foreach ($results as $index => $post) {
        if (!empty($post->ID)) {
            $output .=
                "<option " .
                ($gf_user_id == $post->ID ? "selected" : "") .
                " value='" .
                $post->ID .
                "'>" .
                $post->display_name .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all users 3d flip book category list
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllDocFlipCategroyList($user_serach_doc_category)
{
    global $wpdb;

    // A sql query to return all post titles
    $taxonomies = get_terms([
        "post_type" => "r3d",
        "post_status" => "publish",
        "taxonomy" => "r3d_category",
        "hide_empty" => false,
    ]);

    // Return null if we found no results
    if (!$taxonomies) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="user_serach_doc_category" id="user_serach_doc_category"  onchange="this.form.submit()">';

    $output .=
        "<option " .
        ($user_serach_doc_category == "" ? "selected" : "") .
        " value=''>All Documents</option>";

    foreach ($taxonomies as $taxonomy) {
        $output .=
            "<option " .
            ($user_serach_doc_category == $taxonomy->term_id
                ? "selected"
                : "") .
            " value='" .
            $taxonomy->term_id .
            "'>" .
            $taxonomy->name .
            "</option>";
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all announcement category list
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllAnnoCategroyList($user_serach_anno_category)
{
    global $wpdb;

    // A sql query to return all post titles
    $taxonomies = get_terms([
        "post_type" => "allannouncements",
        "post_status" => "publish",
        "taxonomy" => "annocategory",
        "hide_empty" => true,
    ]);

    // Return null if we found no results
    if (!$taxonomies) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="user_serach_anno_category" id="user_serach_anno_category"  onchange="this.form.submit()">';

    $output .=
        "<option " .
        ($user_serach_anno_category == "" ? "selected" : "") .
        " value=''>All Announcements</option>";

    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->name != "Uncategorized") {
            $output .=
                "<option " .
                ($user_serach_anno_category == $taxonomy->term_id
                    ? "selected"
                    : "") .
                " value='" .
                $taxonomy->term_id .
                "'>" .
                $taxonomy->name .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all Events category list
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllEventCategroyList($user_serach_event_category)
{
    global $wpdb;

    // A sql query to return all post titles
    $taxonomies = get_terms([
        "post_type" => "tribe_events",
        "post_status" => "publish",
        "taxonomy" => "tribe_events_cat",
        "hide_empty" => true,
    ]);

    // Return null if we found no results
    if (!$taxonomies) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="user_serach_event_category" id="user_serach_event_category"  onchange="this.form.submit()">';

    $output .=
        "<option " .
        ($user_serach_event_category == "" ? "selected" : "") .
        " value=''>All Events</option>";

    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->name != "Uncategorized") {
            $output .=
                "<option " .
                ($user_serach_event_category == $taxonomy->term_id
                    ? "selected"
                    : "") .
                " value='" .
                $taxonomy->term_id .
                "'>" .
                $taxonomy->name .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all Reports category Search list
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllReportSearchCategroyList($user_serach_report_category)
{
    global $wpdb;

    // A sql query to return all post titles
    $taxonomies = get_terms([
        "post_type" => "allreports",
        "post_status" => "publish",
        "taxonomy" => "reportcategory",
        "hide_empty" => true,
    ]);

    // Return null if we found no results
    if (!$taxonomies) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="user_serach_report_category" id="user_serach_report_category"  onchange="this.form.submit()">';

    $output .=
        "<option " .
        ($user_serach_report_category == "" ? "selected" : "") .
        " value=''>All Reports</option>";

    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->name != "Uncategorized") {
            $output .=
                "<option " .
                ($user_serach_report_category == $taxonomy->term_id
                    ? "selected"
                    : "") .
                " value='" .
                $taxonomy->term_id .
                "'>" .
                $taxonomy->name .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all Finance category Search list
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllFinanceSearchCategroyList($user_search_finance_category)
{
    global $wpdb;

    // A sql query to return all post titles
    $taxonomies = get_terms([
        "post_type" => "allfinance",
        "post_status" => "publish",
        "taxonomy" => "financecategory",
        "hide_empty" => true,
    ]);

    // Return null if we found no results
    if (!$taxonomies) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="user_search_finance_category" id="user_search_finance_category"  onchange="this.form.submit()">';

    $output .=
        "<option " .
        ($user_search_finance_category == "" ? "selected" : "") .
        " value=''>All Finance</option>";

    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->name != "Uncategorized") {
            $output .=
                "<option " .
                ($user_search_finance_category == $taxonomy->term_id
                    ? "selected"
                    : "") .
                " value='" .
                $taxonomy->term_id .
                "'>" .
                $taxonomy->name .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all it category Search list
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllItSearchCategroyList($user_search_it_category)
{
    global $wpdb;

    // A sql query to return all post titles
    $taxonomies = get_terms([
        "post_type" => "allit",
        "post_status" => "publish",
        "taxonomy" => "itcategory",
        "hide_empty" => true,
    ]);

    // Return null if we found no results
    if (!$taxonomies) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="user_search_it_category" id="user_search_it_category"  onchange="this.form.submit()">';

    $output .=
        "<option " .
        ($user_search_it_category == "" ? "selected" : "") .
        " value=''>All IT</option>";

    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->name != "Uncategorized") {
            $output .=
                "<option " .
                ($user_search_it_category == $taxonomy->term_id
                    ? "selected"
                    : "") .
                " value='" .
                $taxonomy->term_id .
                "'>" .
                $taxonomy->name .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all Reports category list
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllReportCategroyList()
{
    global $wpdb;

    // A sql query to return all post titles
    $taxonomies = get_terms([
        "post_type" => "allreports",
        "post_status" => "publish",
        "taxonomy" => "reportcategory",
        "hide_empty" => true,
    ]);

    // Return null if we found no results
    if (!$taxonomies) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="report_category" id="report_category">';

    //$output .=  "<option " . ( $user_serach_report_category == '' ? 'selected' : '' ) . " value=''>All Reports</option>";

    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->name != "Uncategorized") {
            $output .=
                "<option value='" .
                $taxonomy->term_id .
                "'>" .
                $taxonomy->name .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all Finance category list
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllFinanceCategroyList()
{
    global $wpdb;

    // A sql query to return all post titles
    $taxonomies = get_terms([
        "post_type" => "allfinance",
        "post_status" => "publish",
        "taxonomy" => "financecategory",
        "hide_empty" => true,
    ]);

    // Return null if we found no results
    if (!$taxonomies) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="finance_category" id="finance_category">';

    //$output .=  "<option " . ( $user_serach_report_category == '' ? 'selected' : '' ) . " value=''>All Reports</option>";

    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->name != "Uncategorized") {
            $output .=
                "<option value='" .
                $taxonomy->term_id .
                "'>" .
                $taxonomy->name .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all inclusive category list
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllinclusiveCategroyList()
{
    global $wpdb;

    // A sql query to return all post titles
    $taxonomies = get_terms([
        "post_type" => "allinclusive",
        "post_status" => "publish",
        "taxonomy" => "inclusivecategory",
        "hide_empty" => true,
    ]);

    // Return null if we found no results
    if (!$taxonomies) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="inclusive_category" id="inclusive_category">';

    //$output .=  "<option " . ( $user_serach_report_category == '' ? 'selected' : '' ) . " value=''>All Reports</option>";

    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->name != "Uncategorized") {
            $output .=
                "<option data-id='" .
                $taxonomy->term_id .
                "' value='" .
                $taxonomy->term_id .
                "'>" .
                $taxonomy->name .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all Doc pack Category list
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllDocPackCategoryList()
{
    global $wpdb;

    // A sql query to return all post titles
    $taxonomies = get_terms([
        "post_type" => "alldocpack",
        "post_status" => "publish",
        "taxonomy" => "docpackcategory",
        "hide_empty" => true,
    ]);

    // Return null if we found no results
    if (!$taxonomies) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="docpack_category" id="docpack_category">';

    //$output .=  "<option " . ( $user_serach_report_category == '' ? 'selected' : '' ) . " value=''>All Reports</option>";

    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->name != "Uncategorized") {
            $output .=
                "<option data-id='" .
                $taxonomy->term_id .
                "' value='" .
                $taxonomy->term_id .
                "'>" .
                $taxonomy->name .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all department lists
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllDocPackCategoryListSelected($term_id)
{
    global $wpdb;

    // A sql query to return all post titles
    $taxonomies = get_terms([
        "post_type" => "alldocpack",
        "post_status" => "publish",
        "taxonomy" => "docpackcategory",
        "hide_empty" => true,
    ]);

    // Return null if we found no results
    if (!$taxonomies) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="edit_docpack_category" id="edit_docpack_category">';

    //$output .=  "<option " . ( $user_serach_report_category == '' ? 'selected' : '' ) . " value=''>All Reports</option>";

    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->name != "Uncategorized") {
            $output .=
                "<option data-id='" .
                $taxonomy->term_id .
                "' " .
                ($taxonomy->term_id == $term_id ? "selected" : "") .
                " value='" .
                $taxonomy->term_id .
                "'>" .
                $taxonomy->name .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all IT category list
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllItCategroyList()
{
    global $wpdb;

    // A sql query to return all post titles
    $taxonomies = get_terms([
        "post_type" => "allit",
        "post_status" => "publish",
        "taxonomy" => "itcategory",
        "hide_empty" => true,
    ]);

    // Return null if we found no results
    if (!$taxonomies) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="it_category" id="it_category">';

    //$output .=  "<option " . ( $user_serach_report_category == '' ? 'selected' : '' ) . " value=''>All Reports</option>";

    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->name != "Uncategorized") {
            $output .=
                "<option value='" .
                $taxonomy->term_id .
                "'>" .
                $taxonomy->name .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all announcements catagory list
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllAnnoCategoryList()
{
    global $wpdb;

    // A sql query to return all post titles
    $taxonomies = get_terms([
        "post_type" => "allannouncements",
        "post_status" => "publish",
        "taxonomy" => "annocategory",
        "hide_empty" => false,
    ]);

    // Return null if we found no results
    if (!$taxonomies) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="anno_category" id="anno_category">';

    //$output .=  "<option " . ( $user_serach_doc_category == '' ? 'selected' : '' ) . " value=''>All Documents</option>";

    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->name != "Uncategorized") {
            $output .=
                "<option value='" .
                $taxonomy->term_id .
                "'>" .
                $taxonomy->name .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all event catagory list
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllEventCatList()
{
    global $wpdb;

    // A sql query to return all post titles
    $taxonomies = get_terms([
        "post_type" => "tribe_events",
        "post_status" => "publish",
        "taxonomy" => "tribe_events_cat",
        "hide_empty" => false,
    ]);

    // Return null if we found no results
    if (!$taxonomies) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output = '<select class="form-control" name="event_cat" id="event_cat">';

    //$output .=  "<option " . ( $user_serach_doc_category == '' ? 'selected' : '' ) . " value=''>All Documents</option>";

    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->name != "Uncategorized") {
            $output .=
                "<option value='" .
                $taxonomy->term_id .
                "'>" .
                $taxonomy->name .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all event catagory list with editable values
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllUpdateEventCatList($term_list)
{
    global $wpdb;

    // A sql query to return all post titles
    $taxonomies = get_terms([
        "post_type" => "tribe_events",
        "post_status" => "publish",
        "taxonomy" => "tribe_events_cat",
        "hide_empty" => false,
    ]);

    // Return null if we found no results
    if (!$taxonomies) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output = '<select id="event_cat" name="event_cat">';

    //$output .=  "<option " . ( $user_serach_doc_category == '' ? 'selected' : '' ) . " value=''>All Documents</option>";

    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->name != "Uncategorized") {
            // $output .=  "<option value='".$taxonomy->term_id."'>".$taxonomy->name."</option>";
            $output .=
                "<option " .
                (in_array($taxonomy->term_id, $term_list) ? "selected" : "") .
                " value='" .
                $taxonomy->term_id .
                "'>" .
                $taxonomy->name .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all event list
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllAnnoEventList()
{
    global $wpdb, $posts;

    $args = [
        "post_type" => "tribe_events",
        "numberposts" => -1,
        "post_status" => "publish",
    ];

    $tribe_events = get_posts($args);

    // Return null if we found no results
    if (!$tribe_events) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select class="form-control" name="anno_event_id" id="anno_event_id">';

    $output .= "<option value=''>--Select Event Name--</option>";

    foreach ($tribe_events as $event) {
        //if ($event->post_title != 'Uncategorized'){
        $output .=
            "<option value='" .
            $event->ID .
            "'>" .
            $event->post_title .
            "</option>";
        // }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all 3d flip book catagory list
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllDocCategoryList()
{
    global $wpdb;

    // A sql query to return all post titles
    $taxonomies = get_terms([
        "post_type" => "r3d",
        "post_status" => "publish",
        "taxonomy" => "r3d_category",
        "hide_empty" => false,
    ]);

    // Return null if we found no results
    if (!$taxonomies) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select id="doc_cat_id" name="doc_cat_id[]" multiple="multiple">';

    //$output .=  "<option " . ( $user_serach_doc_category == '' ? 'selected' : '' ) . " value=''>All Documents</option>";

    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->name != "Uncategorized") {
            $output .=
                "<option value='" .
                $taxonomy->term_id .
                "'>" .
                $taxonomy->name .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all 3d flip book catagory list with editable values
 *
 * @return dropdown
 *
 * @since  1.0.0
 * @access all
 */
function getAllDocCategoryEditList($term_list)
{
    global $wpdb;

    // A sql query to return all post titles
    $taxonomies = get_terms([
        "post_type" => "r3d",
        "post_status" => "publish",
        "taxonomy" => "r3d_category",
        "hide_empty" => false,
    ]);

    // Return null if we found no results
    if (!$taxonomies) {
        return;
    }

    // HTML for our select printing post titles as loop
    $output =
        '<select id="doc_cat_id" name="doc_cat_id[]" multiple="multiple">';

    //$output .=  "<option " . ( $user_serach_doc_category == '' ? 'selected' : '' ) . " value=''>All Documents</option>";

    foreach ($taxonomies as $taxonomy) {
        if ($taxonomy->name != "Uncategorized") {
            // $output .=  "<option value='".$taxonomy->term_id."'>".$taxonomy->name."</option>";
            $output .=
                "<option " .
                (in_array($taxonomy->term_id, $term_list) ? "selected" : "") .
                " value='" .
                $taxonomy->term_id .
                "'>" .
                $taxonomy->name .
                "</option>";
        }
    }

    $output .= "</select>"; // end of select element

    // get the html
    return $output;
}

/**
 * Getting all users access permision by form id
 *
 * @return user ids
 *
 * @since  1.0.0
 * @access all
 */
function getAllUsersAccessByFid($fid)
{
    global $wpdb;

    // A sql query to return all post titles
    $results = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT user_ids FROM {$wpdb->prefix}gf_form_access_permision WHERE `form_id`=%d ",
            $fid
        )
    );
    //echo 'xxx=='.$wpdb->last_query; die('test');
    // Return null if we found no results
    if (!$results) {
        return;
    }

    return unserialize($results->user_ids);
}

/**
 * checking for file is reverted by post id
 *
 * @return yes/no
 *
 * @since  1.0.0
 * @access all
 */
function isFileReverted($post_id)
{
    global $wpdb;
    $user_id = get_current_user_id();
    $sql =
        "SELECT  count(ID) as count FROM " .
        $wpdb->prefix .
        "document_upload_details WHERE `post_id`='" .
        $post_id .
        "' AND `user_id`='" .
        $user_id .
        "' AND is_reverted ='no' AND  `ID` < ( SELECT MAX( ID ) FROM " .
        $wpdb->prefix .
        "document_upload_details WHERE `post_id`='" .
        $post_id .
        "' AND `user_id`='" .
        $user_id .
        "' )";

    $results = $wpdb->get_var($sql);
    //echo 'xxx=='.$wpdb->last_query; die('test');
    // Return null if we found no results
    if (!$results) {
        return;
    }

    return $results;
}

/**
 * getting File Path By Post Id
 *
 * @return url
 *
 * @since  1.0.0
 * @access all
 */
function getFilePathByPostId($post_id)
{
    global $wpdb;
    $attachmentUrl = "";
    $args = [
        "post_type" => "attachment",
        "numberposts" => -1,
        "post_status" => "any",
        "post_parent" => $post_id,
        "exclude" => get_post_thumbnail_id(),
    ];

    $attachments = get_posts($args);

    if ($attachments) {
        foreach ($attachments as $attachment) {
            $attachmentUrl = wp_get_attachment_url($attachment->ID);
        }
    }
    //echo 'xxx=='.$wpdb->last_query; die('test');
    // Return null if we found no results
    if (!$attachmentUrl) {
        return;
    }

    return $attachmentUrl;
}

/**
 * Saving data at table document_upload_details
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
function insertUploadDetails($post_id, $post)
{
    //print_r($post); die('test');
    global $wpdb, $wp_query, $post;
    // If this is a revision, don't send the email.
    if (wp_is_post_revision($post_id)) {
        return;
    }

    if ($post->post_type == "r3d") {
        $user_id = get_current_user_id();
        $flipbook_id = get_post_meta($post_id, "flipbook_id");
        $real3dflip = get_option("real3dflipbook_" . $flipbook_id[0], true);
        //saving data at table document_upload_details
        $wpdb->insert($wpdb->prefix . "document_upload_details", [
            "post_id" => $post_id,
            "user_id" => $user_id,
            "pdf_url" => $real3dflip["pdfUrl"],
            "is_reverted" => "no",
            "created_at" => date_i18n("Y-m-d H:i:s"),
        ]);
    }
}
//add_action( 'publish_r3d', 'insertUploadDetails', 99, 2 );

/**
 * Adding extra field in user profile at wp admin
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
add_action("show_user_profile", "extra_user_profile_fields");
add_action("edit_user_profile", "extra_user_profile_fields");

function extra_user_profile_fields($user)
{
    $user_serach_location = "";
    $user_serach_designation = "";
    $user_serach_department = "";
    ?>
  <h3><?php _e("Profile information", "blank"); ?></h3>

  <table class="form-table">
  <tr>
  <th><label for="user_designation"><?php _e("Department"); ?></label></th>
  <td>
  <?php echo getAllDepartmentList($user_serach_department); ?><br />
  <span class="description"><?php _e(
      "Please select your department."
  ); ?></span>
  </td>
  </tr>
  <tr>
  <th><label for="user_department"><?php _e("Occupation"); ?></label></th>
  <td>
  <?php echo getAllDesignationList($user_serach_designation); ?><br />
  <span class="description"><?php _e(
      "Please select your occupation."
  ); ?></span>
  </td>
  </tr>
  <tr>
  <th><label for="user_location"><?php _e("Location"); ?></label></th>
  <td>
  <?php //echo getAllUsersLocationList($user_serach_location);
    ?>
  <input type="text" name="user_location" id="user_location" value="<?php echo esc_attr(
      get_the_author_meta("user_location", $user->ID)
  ); ?>" class="regular-text" /><br />
  <span class="description"><?php _e("Please enter your location."); ?></span>
  </td>
  </tr>
  </table>
<?php
}

add_action("personal_options_update", "save_extra_user_profile_fields");
add_action("edit_user_profile_update", "save_extra_user_profile_fields");

function save_extra_user_profile_fields($user_id)
{
    if (!current_user_can("edit_user", $user_id)) {
        return false;
    }

    update_user_meta($user_id, "user_department", $_POST["user_department"]);
    update_user_meta($user_id, "user_designation", $_POST["user_designation"]);
    update_user_meta($user_id, "user_location", $_POST["user_location"]);
}

/**
 * convert Pdf To Image
 *
 * @return null
 *
 * @since  1.0.0
 * @access all
 */
//$image = convertPdfToImage( 'http://localhost/cnp-1450-ohsapp/cnp-1450/wp-content/uploads/2022/02/add_Smallpdf.pdf');
//print_r($image);
function convertPdfToImage($pdf)
{
    header("Content-type: image/jpeg");
    $fp_pdf = fopen($pdf, "rb");

    $img = new imagick(); // [0] can be used to set page number
    $img->setResolution(300, 300);
    $img->readImageFile($fp_pdf);
    $img->setImageFormat("jpg");
    $img->setImageCompression(imagick::COMPRESSION_JPEG);
    $img->setImageCompressionQuality(90);

    $img->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);

    $data = $img->getImageBlob();
    return $data;
}

/**
 * Get the gform title by id
 *
 * @return title
 *
 * @since  1.0.0
 * @access all
 */
function get_the_form_title($form_id)
{
    $forminfo = RGFormsModel::get_form($form_id);
    $form_title = $forminfo->title;
    return $form_title;
}

/**
 * Get entry data by form id and entry id
 *
 * @return date_created
 *
 * @since  1.0.0
 * @access all
 */
function getEntryDataByFormIdEntryId($form_id, $entry_id)
{
    global $wpdb;
    $lh_records = $wpdb->prepare(
        "SELECT `date_created` FROM `{$wpdb->prefix}gf_entry`  WHERE `id`=%d AND `form_id`=%d",
        $entry_id,
        $form_id
    );
    $lh_results = $wpdb->get_row($lh_records);

    return $lh_results;
}

/**
 * Get entry meta data by form id and entry id
 *
 * @return array
 *
 * @since  1.0.0
 * @access all
 */
function getEntryMetaDataByFormIdEntryId($form_id, $entry_id)
{
    global $wpdb;
    $lh_records = $wpdb->prepare(
        "SELECT `meta_value` FROM `{$wpdb->prefix}gf_entry_meta`  WHERE `entry_id`=%d AND `form_id`=%d",
        $entry_id,
        $form_id
    );
    $lh_results = $wpdb->get_results($lh_records);

    return $lh_results;
}

/**
 * Get entry data comment count by entry id
 *
 * @return count
 *
 * @since  1.0.0
 * @access all
 */
function getEntryDataNoteCount($entry_id)
{
    global $wpdb;
    $lh_records_count = $wpdb->prepare(
        "SELECT count(`id`) as total FROM `{$wpdb->prefix}gf_entry_notes`  WHERE `entry_id`=%d",
        $entry_id
    );
    $lh_results_count = $wpdb->get_var($lh_records_count);

    return $lh_results_count;
}

/**
 * Get all entry data comment by entry id
 *
 * @return row
 *
 * @since  1.0.0
 * @access all
 */
function getAllEntryDataNote($entry_id)
{
    global $wpdb;
    $lh_comments_records = $wpdb->prepare(
        "SELECT * FROM `{$wpdb->prefix}gf_entry_notes`  WHERE `entry_id`=%d",
        $entry_id
    );
    $lh_results_comments = $wpdb->get_results($lh_comments_records);

    return $lh_results_comments;
}

/**
 * getting Documents Count Under Package By post Id
 *
 * @return url
 *
 * @since  1.0.0
 * @access all
 */
function getDocumentsCountUnderPackageById($postID)
{
    global $wpdb;
    $doc_query_args = [
        "post_type" => "alldoc",
        "post_status" => "publish",
        "posts_per_page" => -1,
        "meta_key" => "select_package",
        "meta_value" => $postID,
    ];

    $the_query = new WP_Query($doc_query_args);
    //print_r($the_query);
    $count_query = $the_query->found_posts;

    return $count_query;

    //   $lh_records_count = $wpdb->prepare("SELECT count(`post_id`) as total FROM `{$wpdb->prefix}postmeta`  WHERE `meta_key`=%s AND `meta_value`=%d", 'select_package',$postID);
    //   $lh_results_count = $wpdb->get_var( $lh_records_count );
    //   $lh_results_count = sprintf('%02d', $lh_results_count);
    // return $lh_results_count;
}

/**
 * Showing Documents package and Attachedment at wp admin
 *
 * @return url
 *
 * @since  1.0.0
 * @access all
 */

add_filter("manage_alldoc_posts_columns", "set_custom_edit_dp_columns");
add_action("manage_alldoc_posts_custom_column", "custom_dp_column", 10, 2);

function set_custom_edit_dp_columns($columns)
{
    //unset( $columns['author'] );
    $columns["select_package"] = __("Package Name", "your_text_domain");
    //$columns['attached_documents'] = __( 'Attachment', 'your_text_domain' );

    return $columns;
}

function custom_dp_column($column, $post_id)
{
    switch ($column) {
        case "select_package":
            $packageId = get_post_meta($post_id, "select_package", true);
            $content_post = get_post($packageId);
            echo $post_title = $content_post->post_title;
            break;

        // case 'attached_documents' :
        //     echo get_post_meta( $post_id , 'attached_documents' , true );
        //     break;
    }
}

add_filter("manage_alldoc_posts_columns", function ($columns) {
    $taken_out_date = $columns["date"];
    $taken_out = $columns["author"];

    unset($columns["date"]);
    unset($columns["author"]);
    $columns["date"] = $taken_out_date;
    $columns["author"] = $taken_out;
    return $columns;
});

/**
 * Showing Event of Announcements at wp admin
 *
 * @return url
 *
 * @since  1.0.0
 * @access all
 */

add_filter(
    "manage_allannouncements_posts_columns",
    "set_custom_edit_ae_columns"
);
add_action(
    "manage_allannouncements_posts_custom_column",
    "custom_ae_column",
    10,
    2
);

function set_custom_edit_ae_columns($columns)
{
    //unset( $columns['author'] );
    $columns["anno_event_id"] = __("Event Name", "your_text_domain");
    //$columns['attached_documents'] = __( 'Attachment', 'your_text_domain' );

    return $columns;
}

function custom_ae_column($column, $post_id)
{
    switch ($column) {
        case "anno_event_id":
            $anno_event_id = get_field("anno_event_id", $post_id);
            if (!empty($anno_event_id)) {

                $event = get_post($anno_event_id);
                $event_title = $event->post_title;
                $event_slug = $event->post_name;
                ?>
                <a href="<?php echo esc_url(
                    home_url("/event/" . $event_slug)
                ); ?>" target="_blank"><?php if ($event_title) {
    echo $event_title;
} ?></a>
              <?php
            }
            break;
    }
}

add_filter("manage_allannouncements_posts_columns", function ($columns) {
    $taken_out_date = $columns["date"];
    $taken_out = $columns["author"];

    unset($columns["date"]);
    unset($columns["author"]);
    $columns["date"] = $taken_out_date;
    $columns["author"] = $taken_out;
    return $columns;
});

/**
 * Showing GF Entries fields at wp admin
 *
 * @return url
 *
 * @since  1.0.0
 * @access all
 */

add_filter("manage_gf_entries_posts_columns", "set_custom_edit_gf_columns");
add_action("manage_gf_entries_posts_custom_column", "custom_gf_column", 10, 2);

function set_custom_edit_gf_columns($columns)
{
    //unset( $columns['author'] );
    $columns["gf_form_id"] = __("Form Id", "your_text_domain");
    $columns["gf_entry_id"] = __("Entry Id", "your_text_domain");
    $columns["gf_category"] = __("Category", "your_text_domain");
    $columns["gf_user_id"] = __("User Name", "your_text_domain");
    $columns["gf_pdf"] = __("Download Now", "your_text_domain");
    //$columns['gf_form_status'] = __( 'Status', 'your_text_domain' );

    return $columns;
}

function custom_gf_column($column, $post_id)
{
    switch ($column) {
        case "gf_form_id":
            echo $gf_form_id = get_field("gf_form_id", $post_id, true);
            break;

        case "gf_entry_id":
            echo $gf_entry_id = get_field("gf_entry_id", $post_id, true);
            break;

        case "gf_category":
            $gf_category_id = get_field("gf_category", $post_id, true);
            $term_name = get_term($gf_category_id);
            echo $term_name = $term_name->name;
            break;

        case "gf_user_id":
            $gf_user_id = get_field("gf_user_id", $post_id, true);
            $author_obj = get_user_by("id", $gf_user_id);
            $display_name = $author_obj->display_name;
            $user_email = $author_obj->user_email;
            echo $display_name . " (" . $user_email . ")";
            break;

        case "gf_pdf":
            $gf_entry_id = get_field("gf_entry_id", $post_id, true);
            $gf_pdf_id = getPdfIdByEntryId($gf_entry_id);
            echo $pdf =
                '<a target="_blank" href="' .
                esc_url(home_url()) .
                "/pdf/" .
                $gf_pdf_id .
                "/" .
                $gf_entry_id .
                '" class="readnow circle_effect">PDF</a>';
            break;

        // case 'gf_form_status' :
        //     echo $gf_form_status = get_field( 'gf_form_status', $post_id, true );
        //     break;
    }
}

add_filter("manage_gf_entries_posts_columns", function ($columns) {
    $taken_out_date = $columns["date"];
    //$taken_out = $columns['author'];

    unset($columns["date"]);
    unset($columns["author"]);
    $columns["date"] = $taken_out_date;
    //$columns['author'] = $taken_out;
    return $columns;
});

/**
 * Showing Incident Tracking fields at wp admin
 *
 * @return url
 *
 * @since  1.0.0
 * @access all
 */

add_filter(
    "manage_incident_tracking_posts_columns",
    "set_custom_edit_in_columns"
);
add_action(
    "manage_incident_tracking_posts_custom_column",
    "custom_in_column",
    10,
    2
);

function set_custom_edit_in_columns($columns)
{
    //unset( $columns['author'] );
    $columns["incident_id"] = __("Incident Id", "your_text_domain");
    //$columns['gf_entry_id'] = __( 'Entry Id', 'your_text_domain' );
    $columns["staff_name"] = __("Staff Name", "your_text_domain");
    $columns["attendant_name"] = __("Attendant Name", "your_text_domain");
    $columns["incident_date"] = __("Incident Date", "your_text_domain");
    $columns["in_pdf"] = __("Download", "your_text_domain");
    $columns["in_status"] = __("Status", "your_text_domain");

    return $columns;
}

function custom_in_column($column, $post_id)
{
    switch ($column) {
        case "incident_id":
            echo $incident_id = get_field("incident_id", $post_id, true);
            break;

        case "incident_date":
            echo $in_date = get_field("incident_date", $post_id, true);
            break;

        case "staff_name":
            $staff_id = get_field("staff_name", $post_id, true);
            $author_obj = get_user_by("id", $staff_id);
            $display_name = $author_obj->display_name;
            $user_email = $author_obj->user_email;
            echo $display_name . " (" . $user_email . ")";
            break;

        case "attendant_name":
            $attnd_id = get_field("attendant_name", $post_id, true);
            $author_obj = get_user_by("id", $attnd_id);
            $display_name = $author_obj->display_name;
            $user_email = $author_obj->user_email;
            echo $display_name . " (" . $user_email . ")";
            break;

        case "in_pdf":
            $incident_id = get_field("incident_id", $post_id, true);
            $entry_id = explode("/", $incident_id);
            $gf_entry_id = $entry_id[1];
            $gf_pdf_id = getPdfIdByEntryId($gf_entry_id);
            echo $pdf =
                '<a target="_blank" href="' .
                esc_url(home_url()) .
                "/pdf/" .
                $gf_pdf_id .
                "/" .
                $gf_entry_id .
                '" class="readnow circle_effect">PDF</a>';
            break;

        case "in_status":
            echo $gf_form_status = get_field("in_status", $post_id, true);
            break;
    }
}

add_filter("manage_incident_tracking_posts_columns", function ($columns) {
    $taken_out_date = $columns["date"];
    //$taken_out = $columns['author'];

    unset($columns["date"]);
    unset($columns["author"]);
    $columns["date"] = $taken_out_date;
    //$columns['author'] = $taken_out;
    return $columns;
});

/**
 * Showing WorkSafeBC Form 6A Requested fields at wp admin
 *
 * @return url
 *
 * @since  1.0.0
 * @access all
 */

add_filter(
    "manage_worksafebc6a_req_posts_columns",
    "set_custom_edit_req_columns"
);
add_action(
    "manage_worksafebc6a_req_posts_custom_column",
    "custom_req_column",
    10,
    2
);

function set_custom_edit_req_columns($columns)
{
    //unset( $columns['author'] );
    $columns["req_inc_id"] = __("Incident Id", "your_text_domain");
    $columns["req_staff_name"] = __("Staff Name", "your_text_domain");
    $columns["req_attnd_name"] = __("Attendant Name", "your_text_domain");
    $columns["req_hr_name"] = __("HR Name", "your_text_domain");
    $columns["req_date"] = __("Requested Date", "your_text_domain");
    $columns["req_status"] = __("Status", "your_text_domain");

    return $columns;
}

function custom_req_column($column, $post_id)
{
    switch ($column) {
        case "req_inc_id":
            echo $req_inc_id = get_field("req_inc_id", $post_id, true);
            break;

        case "req_staff_name":
            $staff_id = get_field("req_staff_name", $post_id, true);
            $author_obj = get_user_by("id", $staff_id);
            $display_name = $author_obj->display_name;
            $user_email = $author_obj->user_email;
            echo $display_name . " (" . $user_email . ")";
            break;

        case "req_attnd_name":
            $attnd_id = get_field("req_attnd_name", $post_id, true);
            $author_obj = get_user_by("id", $attnd_id);
            $display_name = $author_obj->display_name;
            $user_email = $author_obj->user_email;
            echo $display_name . " (" . $user_email . ")";
            break;

        case "req_hr_name":
            $hr_id = get_field("req_hr_name", $post_id, true);
            $author_obj = get_user_by("id", $hr_id);
            $display_name = $author_obj->display_name;
            $user_email = $author_obj->user_email;
            echo $display_name . " (" . $user_email . ")";
            break;

        case "req_date":
            echo $in_date = get_field("req_date", $post_id, true);
            break;

        case "req_status":
            echo $gf_form_status = get_field("req_status", $post_id, true);
            break;
    }
}

add_filter("manage_worksafebc6a_req_posts_columns", function ($columns) {
    $taken_out_date = $columns["date"];
    //$taken_out = $columns['author'];

    unset($columns["date"]);
    unset($columns["author"]);
    $columns["date"] = $taken_out_date;
    //$columns['author'] = $taken_out;
    return $columns;
});

/**
 * Showing WorkSafeBC Form 6A Received fields at wp admin
 *
 * @return url
 *
 * @since  1.0.0
 * @access all
 */

add_filter(
    "manage_worksafebc6a_rec_posts_columns",
    "set_custom_edit_rec_columns"
);
add_action(
    "manage_worksafebc6a_rec_posts_custom_column",
    "custom_rec_column",
    10,
    2
);

function set_custom_edit_rec_columns($columns)
{
    //unset( $columns['author'] );

    $columns["rec_form6a_id"] = __("Form 6A Id", "your_text_domain");
    $columns["rec_inc_id"] = __("Incident Id", "your_text_domain");
    $columns["rec_staff_name"] = __("Staff Name", "your_text_domain");
    $columns["rec_attnd_name"] = __("Attendant Name", "your_text_domain");
    $columns["rec_hr_name"] = __("HR Name", "your_text_domain");
    $columns["rec_date"] = __("Received Date", "your_text_domain");
    $columns["rec_status"] = __("Status", "your_text_domain");
    $columns["rec_pdf"] = __("Download", "your_text_domain");

    return $columns;
}

function custom_rec_column($column, $post_id)
{
    switch ($column) {
        case "rec_form6a_id":
            echo $rec_inc_id = get_field("rec_form6a_id", $post_id, true);
            break;

        case "rec_inc_id":
            echo $rec_inc_id = get_field("rec_inc_id", $post_id, true);
            break;

        case "rec_staff_name":
            $staff_id = get_field("rec_staff_name", $post_id, true);
            $author_obj = get_user_by("id", $staff_id);
            $display_name = $author_obj->display_name;
            $user_email = $author_obj->user_email;
            echo $display_name . " (" . $user_email . ")";
            break;

        case "rec_attnd_name":
            $attnd_id = get_field("rec_attnd_name", $post_id, true);
            $author_obj = get_user_by("id", $attnd_id);
            $display_name = $author_obj->display_name;
            $user_email = $author_obj->user_email;
            echo $display_name . " (" . $user_email . ")";
            break;

        case "rec_hr_name":
            $hr_id = get_field("rec_hr_name", $post_id, true);
            $author_obj = get_user_by("id", $hr_id);
            $display_name = $author_obj->display_name;
            $user_email = $author_obj->user_email;
            echo $display_name . " (" . $user_email . ")";
            break;

        case "rec_date":
            echo $in_date = get_field("rec_date", $post_id, true);
            break;

        case "rec_status":
            echo $gf_form_status = get_field("rec_status", $post_id, true);
            break;

        case "rec_pdf":
            $rec_form6a_id = get_field("rec_form6a_id", $post_id, true);
            $entry_id = explode("/", $rec_form6a_id);
            $gf_entry_id = $entry_id[1];
            $gf_pdf_id = getPdfIdByEntryId($gf_entry_id);
            echo $pdf =
                '<a target="_blank" href="' .
                esc_url(home_url()) .
                "/pdf/" .
                $gf_pdf_id .
                "/" .
                $gf_entry_id .
                '" class="readnow circle_effect">PDF</a>';
            break;
    }
}

add_filter("manage_worksafebc6a_rec_posts_columns", function ($columns) {
    $taken_out_date = $columns["date"];
    //$taken_out = $columns['author'];

    unset($columns["date"]);
    unset($columns["author"]);
    $columns["date"] = $taken_out_date;
    //$columns['author'] = $taken_out;
    return $columns;
});

/**
 * Showing WorkSafeBC Form 7 Submit fields at wp admin
 *
 * @return url
 *
 * @since  1.0.0
 * @access all
 */

add_filter("manage_worksafebc7_posts_columns", "set_custom_edit_f7_columns");
add_action("manage_worksafebc7_posts_custom_column", "custom_f7_column", 10, 2);

function set_custom_edit_f7_columns($columns)
{
    //unset( $columns['author'] );

    $columns["f7_id"] = __("Form 7 Id", "your_text_domain");
    $columns["f7_inc_id"] = __("Incident Id", "your_text_domain");
    $columns["f7_staff_name"] = __("Staff Name", "your_text_domain");
    $columns["f7_hr_name"] = __("HR Name", "your_text_domain");
    $columns["f7_date"] = __("Submitted Date", "your_text_domain");
    $columns["f7_status"] = __("Status", "your_text_domain");
    $columns["f7_pdf"] = __("Download", "your_text_domain");

    return $columns;
}

function custom_f7_column($column, $post_id)
{
    switch ($column) {
        case "f7_id":
            echo $f7_inc_id = get_field("f7_id", $post_id, true);
            break;

        case "f7_inc_id":
            echo $f7_inc_id = get_field("f7_inc_id", $post_id, true);
            break;

        case "f7_staff_name":
            $staff_id = get_field("f7_staff_name", $post_id, true);
            $author_obj = get_user_by("id", $staff_id);
            $display_name = $author_obj->display_name;
            $user_email = $author_obj->user_email;
            echo $display_name . " (" . $user_email . ")";
            break;

        case "rec_attnd_name":
            $attnd_id = get_field("rec_attnd_name", $post_id, true);
            $author_obj = get_user_by("id", $attnd_id);
            $display_name = $author_obj->display_name;
            $user_email = $author_obj->user_email;
            echo $display_name . " (" . $user_email . ")";
            break;

        case "f7_hr_name":
            $hr_id = get_field("f7_hr_name", $post_id, true);
            $author_obj = get_user_by("id", $hr_id);
            $display_name = $author_obj->display_name;
            $user_email = $author_obj->user_email;
            echo $display_name . " (" . $user_email . ")";
            break;

        case "f7_date":
            echo $in_date = get_field("f7_date", $post_id, true);
            break;

        case "f7_status":
            echo $gf_form_status =
                get_field("f7_status", $post_id, true) .
                "</br>API Msg: " .
                ($gf_form_status = get_field(
                    "api_submission_status_message",
                    $post_id,
                    true
                ));
            break;

        case "f7_pdf":
            $f7_id = get_field("f7_id", $post_id, true);
            $entry_id = explode("/", $f7_id);
            $gf_entry_id = $entry_id[1];
            $gf_pdf_id = getPdfIdByEntryId($gf_entry_id);
            echo $pdf =
                '<a target="_blank" href="' .
                esc_url(home_url()) .
                "/pdf/" .
                $gf_pdf_id .
                "/" .
                $gf_entry_id .
                '" class="readnow circle_effect">PDF</a>';
            break;
    }
}

add_filter("manage_worksafebc7_posts_columns", function ($columns) {
    $taken_out_date = $columns["date"];
    //$taken_out = $columns['author'];

    unset($columns["date"]);
    unset($columns["author"]);
    $columns["date"] = $taken_out_date;
    //$columns['author'] = $taken_out;
    return $columns;
});

/**
 * Addign GF Entries after submission of any GF forms
 *
 * @return url
 *
 * @since  1.0.0
 * @access all
 */
function after_submission_gf_forms($entry, $form)
{
    //print_r($entry); die('test');
    global $wpdb, $post;
    $gf_category = "";
    $gf_entry_id = $entry["id"];
    $gf_form_id = $entry["form_id"];
    $form_title = $form["title"];
    $form_category = $form["gravityforms-categories"]["gforms_categories"];
    foreach ($form_category as $key => $value) {
        if ($value == 1) {
            $gf_category = $key;
        }
    }
    if (!empty($gf_category)) {
        $gf_form_status = "New";
        $gf_user_id = get_current_user_id();

        $post_id = wp_insert_post([
            "post_type" => "gf_entries",
            "post_title" => $form_title,
            //'post_content' => $docpack_desc,
            "post_status" => "publish",
            "comment_status" => "closed", // if you prefer
            "ping_status" => "closed", // if you prefer
        ]);
        if ($post_id) {
            update_field("gf_category", $gf_category, $post_id);
            update_field("gf_form_id", $gf_form_id, $post_id);
            update_field("gf_user_id", $gf_user_id, $post_id);
            update_field("gf_entry_id", $gf_entry_id, $post_id);
            update_field("gf_form_status", $gf_form_status, $post_id);
        }
    }
}
add_action("gform_after_submission", "after_submission_gf_forms", 10, 2);

/**
 * Adding Incident Tracking records after submission of First Aid Record(5) GF forms
 *
 * @return url
 *
 * @since  1.0.0
 * @access all
 */
function after_submission_gf_form_27($entry, $form)
{
    //print_r($entry); die('test');
    global $wpdb, $post;
    $gf_category = "";
    $gf_entry_id = $entry["id"];
    $gf_form_id = $entry["form_id"];
    $form_title = $form["title"];

    //========Local
    //$staff_id = $entry['33'];
    //$attnd_id = $entry['34'];
    //======Chimeric
    $staff_id = $entry["32"];
    $attnd_id = $entry["33"];
    $incident_date = $entry["4"] . "" . $entry["5"];
    $in_status = "Submitted";
    $staff_obj = get_user_by("id", $staff_id);
    $staff_name = $staff_obj->display_name;
    $new_form_title = $form_title . "(" . $staff_name . ")";

    $incident_id =
        $gf_form_id . "/" . $gf_entry_id . "/" . $staff_id . "/" . $attnd_id;

    $post_id = wp_insert_post([
        "post_type" => "incident_tracking",
        "post_title" => $new_form_title,
        //'post_content' => $docpack_desc,
        "post_status" => "publish",
        "comment_status" => "closed", // if you prefer
        "ping_status" => "closed", // if you prefer
    ]);
    if ($post_id) {
        //update_field('gf_category', $gf_category, $post_id);
        update_field("incident_id", $incident_id, $post_id);
        update_field("is_inc_re_open", "No", $post_id);
        update_field("staff_name", $staff_id, $post_id);
        update_field("attendant_name", $attnd_id, $post_id);
        update_field("incident_date", $incident_date, $post_id);
        update_field("in_status", $in_status, $post_id);

        //===========Sending mail
        $mail_sub = "First Aid Report";
        first_aid_report_submit_mail_staff($staff_id, $attnd_id, $mail_sub);
    }
}

add_action("gform_after_submission_27", "after_submission_gf_form_27", 10, 2);

/**
 * Adding Incident Tracking records after submission of Worksafe BC Form - 6A GF forms
 *
 * @return url
 *
 * @since  1.0.0
 * @access all
 */
function after_submission_gf_form_28($entry, $form)
{
    //print_r($entry); die('test');
    global $wpdb, $post;
    $gf_category = "";
    $gf_entry_id = $entry["id"];
    $gf_form_id = $entry["form_id"];
    $form_title = $form["title"];

    $action_id = $entry["82"];
    $req_array = explode("/", $action_id);
    $req_post_id = $req_array[0];
    $req_form_id = $req_array[1];
    $req_entry_id = $req_array[2];
    $req_staff_id = $req_array[3];
    $req_attnd_id = $req_array[4];

    $received_date = date("Y-m-d H:i:s");

    $hr_id = get_post_field("req_hr_name", $req_post_id, true);
    //print_r($req_array); die('test');

    $staff_obj = get_user_by("id", $req_staff_id);
    $staff_name = $staff_obj->display_name;
    $new_form_title = $form_title . "(" . $staff_name . ")";

    update_field("req_status", "Received", $req_post_id);

    $rec_form6a_id =
        $gf_form_id . "/" . $gf_entry_id . "/" . $req_staff_id . "/" . $hr_id;

    $rec_inc_id =
        $req_form_id .
        "/" .
        $req_entry_id .
        "/" .
        $req_staff_id .
        "/" .
        $req_attnd_id;

    $inc_id = get_post_id_by_meta_key_and_value("incident_id", $rec_inc_id);
    update_field("in_status", "Approved", $inc_id);

    $post_id = wp_insert_post([
        "post_type" => "worksafebc6a_rec",
        "post_title" => $new_form_title,
        //'post_content' => $docpack_desc,
        "post_status" => "publish",
        "comment_status" => "closed", // if you prefer
        "ping_status" => "closed", // if you prefer
    ]);

    if ($post_id) {
        update_field("rec_form6a_id", $rec_form6a_id, $post_id);
        update_field("rec_inc_id", $rec_inc_id, $post_id);
        update_field("rec_staff_name", $req_staff_id, $post_id);
        update_field("rec_attnd_name", $req_attnd_id, $post_id);
        update_field("rec_hr_name", $hr_id, $post_id);
        update_field("rec_date", $received_date, $post_id);
        update_field("rec_status", "Received", $post_id);

        //===========Sending mail
        //=============HR notification for form submit
        $entry_link_id = $rec_form6a_id;
        $hr_mail_sub = "WorkSafeBC Form 6A Submitted";
        workSafeBC_form6A_submitted_mail_hr(
            $entry_link_id,
            $staff_id,
            $hr_id,
            $hr_mail_sub
        );

        //=============Staff notification for form submit
        $staff_mail_sub = "WorkSafeBC Form 6A Received";
        workSafeBC_form6A_received_mail_staff(
            $staff_id,
            $hr_id,
            $staff_mail_sub
        );
    }
}

add_action("gform_after_submission_28", "after_submission_gf_form_28", 10, 2);

/**
 * Incident Tracking records after submission of Worksafe BC Form - 7 GF forms
 *
 * @return url
 *
 * @since  1.0.0
 * @access all
 */
function after_submission_gf_form_29($entry, $form)
{
    //print_r($entry); die('test');
    global $wpdb, $post;
    $gf_category = "";
    $gf_entry_id = $entry["id"];
    $gf_form_id = $entry["form_id"];
    $form_title = $form["title"];

    $action_id = $entry["137"];
    $f7_array = explode("/", $action_id);
    $f7_post_id = $f7_array[0];
    $f7_form_id = $f7_array[1];
    $f7_entry_id = $f7_array[2];
    $f7_staff_id = $f7_array[3];
    $f7_attnd_id = $f7_array[4];

    $f7_date = date("Y-m-d H:i:s");

    $f7_hr_id = get_current_user_id();

    $staff_obj = get_user_by("id", $f7_staff_id);
    $staff_name = $staff_obj->display_name;
    $form7_title = $form_title . "(" . $staff_name . ")";

    update_field("f7_status", "Resolved", $f7_post_id);

    $f7_id =
        $gf_form_id . "/" . $gf_entry_id . "/" . $f7_staff_id . "/" . $f7_hr_id;

    $f7_inc_id =
        $f7_form_id .
        "/" .
        $f7_entry_id .
        "/" .
        $f7_staff_id .
        "/" .
        $f7_attnd_id;

    //==============Updating Incident Status
    $inc_id = get_post_id_by_meta_key_and_value("incident_id", $f7_inc_id);
    update_field("in_status", "Resolved", $inc_id);

    //==============Updating Requested Status
    $f6rec_id = get_post_id_by_meta_key_and_value("req_inc_id", $f7_inc_id);
    update_field("req_status", "Resolved", $f6rec_id);

    //==============Updating Recived Status
    $f6req_id = get_post_id_by_meta_key_and_value("rec_inc_id", $f7_inc_id);
    update_field("rec_status", "Resolved", $f6req_id);

    $post_id = wp_insert_post([
        "post_type" => "worksafebc7",
        "post_title" => $form7_title,
        //'post_content' => $docpack_desc,
        "post_status" => "publish",
        "comment_status" => "closed", // if you prefer
        "ping_status" => "closed", // if you prefer
    ]);

    if ($post_id) {
        update_field("f7_id", $f7_id, $post_id);
        update_field("f7_inc_id", $f7_inc_id, $post_id);
        update_field("f7_staff_name", $f7_staff_id, $post_id);
        //update_field('f7_attnd_name', $f7_attnd_name, $post_id);
        update_field("f7_hr_name", $f7_hr_id, $post_id);
        update_field("f7_date", $f7_date, $post_id);
        update_field("f7_status", "Resolved", $post_id);
        //api_submit_to_worksafebc($post_id,$entry, $form);
    }
}

add_action("gform_after_submission_29", "after_submission_gf_form_29", 10, 2);

function create_chat_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "chat_messages";
    $charset_collate = $wpdb->get_charset_collate();
    $query = "SHOW TABLES LIKE '{$table_name}'";

    $chcek_table = $wpdb->get_var($query);

    if (!$chcek_table == $table_name) {
        $sql = "CREATE TABLE `$table_name` (
              `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `action_id` bigint(20) unsigned NOT NULL,
              `staff_id` bigint(20) unsigned NOT NULL,
              `hr_id` bigint(20) unsigned NOT NULL,
              `sender_id` bigint(20) unsigned NOT NULL,
              `message` text NULL,
              `is_read` tinyint NOT NULL DEFAULT '0' COMMENT '1=read',
              `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1=deleted',
              `created_at` datetime NOT NULL,
              PRIMARY KEY (`ID`),
              KEY `action_id` (`action_id`),
              KEY `staff_id` (`staff_id`),
              KEY `hr_id` (`hr_id`),
              KEY `sender_id` (`sender_id`),
              KEY `created_at` (`created_at`)
            )";

        $table_created = $wpdb->query($sql);
    }
}
add_action("after_setup_theme", "create_chat_table");