<?php
if (!defined("ABSPATH")) {
    die("You are not allowed to call this page directly.");
}
/**
 * OSH Employee Frontend Part
 * @package OSH
 * @version 1.0.0
 */

class OSHEmployeeFrontend
{
    public function __construct()
    {
        add_action("wp_enqueue_scripts", [$this, "enqueue_scripts"]); //hook to enequeue the javascript to the page.
        add_action(
            "gform_after_submission_5",
            [$this, "upload_firstaid_data_into_flipbook"],
            10,
            2
        );
        add_action(
            "gform_after_submission_7",
            [$this, "upload_pdf_data_into_flipbook"],
            10,
            2
        );
        add_action(
            "gform_after_submission_11",
            [$this, "upload_pdf_converted_data_into_flipbook"],
            10,
            2
        );

        add_action("wp_ajax_osh_search_ajax", [$this, "osh_search_ajax"]);
        add_action("wp_ajax_nopriv_osh_search_ajax", [
            $this,
            "osh_search_ajax",
        ]);

        add_action("wp_ajax_osh_course_search_ajax", [
            $this,
            "osh_course_search_ajax",
        ]);
        add_action("wp_ajax_nopriv_osh_course_search_ajax", [
            $this,
            "osh_course_search_ajax",
        ]);
        add_action("wp_ajax_osh_user_search_ajax", [
            $this,
            "osh_user_search_ajax",
        ]);
        add_action("wp_ajax_nopriv_osh_user_search_ajax", [
            $this,
            "osh_user_search_ajax",
        ]);

        add_action("wp_ajax_osh_assign_user_menu_ajax", [
            $this,
            "osh_assign_user_menu_ajax",
        ]);
        add_action("wp_ajax_nopriv_osh_assign_user_menu_ajax", [
            $this,
            "osh_assign_user_menu_ajax",
        ]);

        add_action("wp_ajax_osh_assign_user_form_ajax", [
            $this,
            "osh_assign_user_form_ajax",
        ]);
        add_action("wp_ajax_nopriv_osh_assign_user_form_ajax", [
            $this,
            "osh_assign_user_form_ajax",
        ]);

        add_action("wp_ajax_osh_upadte_user_pro_ajax", [
            $this,
            "osh_upadte_user_pro_ajax",
        ]);
        add_action("wp_ajax_nopriv_osh_upadte_user_pro_ajax", [
            $this,
            "osh_upadte_user_pro_ajax",
        ]);

        add_action("wp_ajax_osh_add_anno_form_ajax", [
            $this,
            "osh_add_anno_form_ajax",
        ]);
        add_action("wp_ajax_nopriv_osh_add_anno_form_ajax", [
            $this,
            "osh_add_anno_form_ajax",
        ]);
        add_action("wp_ajax_osh_add_doc_form_ajax", [
            $this,
            "osh_add_doc_form_ajax",
        ]);
        add_action("wp_ajax_nopriv_osh_add_doc_form_ajax", [
            $this,
            "osh_add_doc_form_ajax",
        ]);

        add_action("wp_ajax_osh_edit_doc_form_ajax", [
            $this,
            "osh_edit_doc_form_ajax",
        ]);
        add_action("wp_ajax_nopriv_osh_edit_doc_form_ajax", [
            $this,
            "osh_edit_doc_form_ajax",
        ]);
        add_action("wp_ajax_osh_revert_doc_ajax", [
            $this,
            "osh_revert_doc_ajax",
        ]);
        add_action("wp_ajax_nopriv_osh_revert_doc_ajax", [
            $this,
            "osh_revert_doc_ajax",
        ]);

        add_action("wp_ajax_osh_rep_doc_form_ajax", [
            $this,
            "osh_rep_doc_form_ajax",
        ]);
        add_action("wp_ajax_nopriv_osh_rep_doc_form_ajax", [
            $this,
            "osh_rep_doc_form_ajax",
        ]);

        add_action("wp_ajax_add_event_form_ajax", [
            $this,
            "add_event_form_ajax",
        ]);
        add_action("wp_ajax_nopriv_add_event_form_ajax", [
            $this,
            "add_event_form_ajax",
        ]);

        add_action("wp_ajax_update_event_form_ajax", [
            $this,
            "update_event_form_ajax",
        ]);
        add_action("wp_ajax_nopriv_update_event_form_ajax", [
            $this,
            "update_event_form_ajax",
        ]);
    }

    /**
     * Enqueue Frontend JS
     *
     * @return enqueue scripts
     *
     * @access all
     * @version 1.0.0
     */
    public function enqueue_scripts()
    {
        wp_deregister_script("jquery");
        wp_enqueue_script(
            "jquery",
            get_template_directory_uri() . "/assets/js/jquery.min.js",
            [],
            "3.2.1"
        );
        wp_enqueue_script(
            "osh-jquery-ui",
            get_template_directory_uri() . "/assets/js/jquery-ui.js",
            ["jquery"],
            "1.0",
            true
        );
        wp_enqueue_script(
            "validate_js",
            get_template_directory_uri() . "/assets/js/jquery.validate.min.js",
            ["jquery"],
            "1.0",
            true
        );
        wp_enqueue_script(
            "jquery-validate-additional-methods",
            get_template_directory_uri() .
                "/assets/js/jquery-validate-additional-methods.js",
            ["jquery"],
            "1.0",
            true
        );
        wp_enqueue_script(
            "employee_js",
            get_template_directory_uri() . "/assets/js/employee.js",
            ["jquery"],
            "1.0",
            true
        );
        wp_localize_script("employee_js", "frontend_ajax_object", [
            "ajaxurl" => admin_url("admin-ajax.php"),
        ]);
    }

    /**
     * Ajax User Search
     *
     * @return UL
     *
     * @since  1.0.0
     * @access all
     */
    public function osh_user_search_ajax()
    {
        global $wpdb;
        $data = [];
        if (!empty($_REQUEST["term"])) {
            $search = sanitize_text_field(wp_unslash($_REQUEST["term"]));

            $user_sql = "SELECT t1.`display_name` FROM {$wpdb->prefix}users as t1 INNER JOIN {$wpdb->prefix}usermeta as t2 ON ( t1.ID = t2.user_id ) WHERE 1=1 AND t2.meta_key = '{$wpdb->prefix}capabilities' AND t2.meta_value LIKE '%employee%' AND  `display_name` LIKE '%$search%' ORDER BY `ID` ASC LIMIT 0,10";

            $results = $wpdb->get_results($user_sql);
            if (!empty($results)) { ?>
          <ul id="user-list">
            <?php foreach ($results as $row) { ?>
              <li onClick="selectSearchUser('<?php echo remove_string_post_title(
                  $row->display_name
              ); ?>');"><?php echo remove_string_post_title(
    $row->display_name
); ?></li>
              <?php } ?>
              </ul>
              <?php }
        }

        exit();
    }

    /**
     * Ajax Assign User Menus
     *
     * @return UL
     *
     * @since  1.0.0
     * @access all
     */
    public function osh_assign_user_menu_ajax()
    {
        global $wpdb;
        //print_r($_POST); die('test');
        header("Content-Type: application/json");
        $error_msg = [];
        if (
            !wp_verify_nonce(
                $_POST["_availability_nonce"],
                "ohs_menu_availability_nonce"
            )
        ) {
            $error_msg["msg"] = esc_html__("Security error!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (!is_user_logged_in()) {
            $error_msg["msg"] = esc_html__("You are not logged in!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (!empty($_POST["menu_id"]) && !empty($_POST["assign_user_id"])) {
            $menu_id = serialize($_POST["menu_id"]);
            $assign_user_id = sanitize_text_field(
                wp_unslash($_POST["assign_user_id"])
            );
            update_user_meta($assign_user_id, "menu_lists", $menu_id);
            $error_msg["msg"] = "success";
        } else {
            $error_msg["msg"] = "error";
        }

        echo json_encode($error_msg);
        exit();
    }

    /**
     * Ajax Assign User Form
     *
     * @return UL
     *
     * @since  1.0.0
     * @access all
     */
    public function osh_assign_user_form_ajax()
    {
        global $wpdb, $wp_query, $post;
        self::create_table_access_permision();
        //print_r($_POST); die('test');
        header("Content-Type: application/json");
        $error_msg = [];
        if (
            !wp_verify_nonce(
                $_POST["_availability_nonce"],
                "ohs_form_availability_nonce"
            )
        ) {
            $error_msg["msg"] = esc_html__("Security error!", "osh");
            $error_msg["msg"] = "error";
            $error_msg["msg_html"] = esc_html__("Security error!", "sd91");
            echo json_encode($error_msg);
            exit();
        }

        if (!is_user_logged_in()) {
            $error_msg["msg"] = esc_html__("You are not logged in!", "osh");
            $error_msg["msg"] = "error";
            $error_msg["msg_html"] = esc_html__(
                "You are not logged in!",
                "sd91"
            );
            echo json_encode($error_msg);
            exit();
        }

        if (!empty($_POST["form_id"]) && !empty($_POST["emp_id"])) {
            $form_id = $_POST["form_id"];
            $emp_ids = serialize($_POST["emp_id"]);
            $toUserIds = $_POST["emp_id"];

            $formQuery = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT count(ID) as count FROM {$wpdb->prefix}gf_form_access_permision WHERE `form_id`=%d ",
                    $form_id
                )
            );

            if ($formQuery->count > 0) {
                $wpdb->update(
                    "{$wpdb->prefix}gf_form_access_permision",
                    ["user_ids" => $emp_ids],
                    ["form_id" => $form_id]
                );
            } else {
                $wpdb->insert($wpdb->prefix . "gf_form_access_permision", [
                    "form_id" => $form_id,
                    "user_ids" => $emp_ids,
                    "created_at" => date_i18n("Y-m-d H:i:s"),
                ]);

                //=================Adding records to Action Iteam

                foreach ($toUserIds as $toUserId) {
                    $user_id = get_current_user_id();
                    $user_info = get_userdata($toUserId);
                    $user_name = $user_info->display_name;
                    $user_department = get_user_meta(
                        $toUserId,
                        "user_department",
                        true
                    );
                    $user_occupation = get_user_meta(
                        $toUserId,
                        "user_occupation",
                        true
                    );
                    $user_location = get_user_meta(
                        $toUserId,
                        "user_location",
                        true
                    );

                    $item_type = "Form";
                    $priority = 1;
                    $item_id = $form_id;
                    $forminfo = RGFormsModel::get_form($form_id);
                    $form_title = $forminfo->title;

                    $note_sub = "Form Assigned";
                    $note_desc =
                        "HR has assigned a form to you. Please have a look.";

                    $args = [
                        "post_title" => $form_title,
                        "post_type" => "allhraction",
                        "post_status" => "publish",
                    ];

                    $new_post_id = wp_insert_post($args);
                    if ($new_post_id) {
                        update_field(
                            "action_id",
                            "act_id_" . $new_post_id,
                            $new_post_id
                        );
                        update_field(
                            "assigned_to_user",
                            $toUserId,
                            $new_post_id
                        );
                        update_field(
                            "assigned_by_user",
                            $user_id,
                            $new_post_id
                        );
                        update_field(
                            "user_department",
                            $user_department,
                            $new_post_id
                        );
                        update_field(
                            "user_occupation",
                            $user_occupation,
                            $new_post_id
                        );
                        update_field(
                            "user_location",
                            $user_location,
                            $new_post_id
                        );
                        update_field("item_type", $item_type, $new_post_id);
                        update_field("item_id", $item_id, $new_post_id);
                        update_field("item_name", $item_name, $new_post_id);
                        update_field("note_sub", $note_sub_me, $new_post_id);
                        update_field(
                            "note_description",
                            $note_desc_me,
                            $new_post_id
                        );
                        update_field("priority", $priority, $new_post_id);
                        update_field("status", "New", $new_post_id);
                        update_field(
                            "completion_date",
                            date_i18n("Y-m-d"),
                            $new_post_id
                        );
                        update_field(
                            "assigned_date",
                            date_i18n("Y-m-d"),
                            $new_post_id
                        );
                    }
                }
            }

            $error_msg["msg"] = "success";
            $error_msg["msg_html"] = esc_html__(
                "Form assigned successfully.",
                "sd91"
            );
        } else {
            $error_msg["msg"] = "empty";
            $error_msg["msg_html"] = esc_html__(
                "Please select atleast one user",
                "sd91"
            );
        }

        echo json_encode($error_msg);
        exit();
    }

    /**
     * Update User profile
     *
     * @return NUL
     *
     * @since  1.0.0
     * @access all
     */
    public function osh_upadte_user_pro_ajax()
    {
        global $wpdb;
        //print_r($_POST); die('test');
        header("Content-Type: application/json");

        if (!empty($_POST["user_id"])) {
            $user_id = $_POST["user_id"];
        } else {
            $user_id = get_current_user_id();
        }

        $error_msg = [];
        if (
            !wp_verify_nonce(
                $_POST["_availability_nonce"],
                "ohs_pro_up_availability_nonce"
            )
        ) {
            $error_msg["msg"] = esc_html__("Security error!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (!is_user_logged_in()) {
            $error_msg["msg"] = esc_html__("You are not logged in!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (
            !empty($_POST["user_designation"]) &&
            !empty($_POST["user_location"]) &&
            !empty($_POST["user_department"]) &&
            !empty($_POST["user_bio"])
        ) {
            $user_department = sanitize_text_field(
                wp_unslash($_POST["user_department"])
            );
            update_user_meta($user_id, "user_department", $user_department);

            $user_designation = sanitize_text_field(
                wp_unslash($_POST["user_designation"])
            );
            update_user_meta($user_id, "user_designation", $user_designation);

            $user_location = sanitize_text_field(
                wp_unslash($_POST["user_location"])
            );
            update_user_meta($user_id, "user_location", $user_location);

            $user_bio = sanitize_text_field(wp_unslash($_POST["user_bio"]));
            update_user_meta($user_id, "description", $user_bio);

            $error_msg["msg"] = "success";
            $error_msg["msg_html"] = esc_html__(
                "Profile updated successfully.",
                "sd91"
            );
        } else {
            $error_msg["msg"] = "empty";
            $error_msg["msg_html"] = esc_html__(
                "Please fill all mandatory fields.",
                "cupe"
            );
        }

        echo json_encode($error_msg);
        exit();
    }

    /**
     * Ajax Add Announcements
     *
     * @return NUL
     *
     * @since  1.0.0
     * @access all
     */
    public function osh_add_anno_form_ajax()
    {
        global $wpdb, $actions_items;
        //print_r($_POST); die('test');
        header("Content-Type: application/json");

        if (!empty($_POST["user_id"])) {
            $user_id = $_POST["user_id"];
        } else {
            $user_id = get_current_user_id();
        }

        $error_msg = [];
        if (
            !wp_verify_nonce(
                $_POST["_availability_nonce"],
                "ohs_form_anno_nonce"
            )
        ) {
            $error_msg["msg"] = esc_html__("Security error!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (!is_user_logged_in()) {
            $error_msg["msg"] = esc_html__("You are not logged in!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (
            !empty($_POST["anno_title"]) &&
            !empty($_POST["anno_category"]) &&
            !empty($_POST["anno_details"])
        ) {
            $anno_title = sanitize_text_field(wp_unslash($_POST["anno_title"]));
            $anno_details = sanitize_text_field(
                wp_unslash($_POST["anno_details"])
            );
            $anno_category = sanitize_text_field(
                wp_unslash($_POST["anno_category"])
            );
            $fount_post = post_exists($anno_title, "", "", "allannouncements");
            if ($fount_post == 0) {
                // insert the post and set the category
                $post_id = wp_insert_post([
                    "post_type" => "allannouncements",
                    "post_title" => $anno_title,
                    "post_content" => $anno_details,
                    "post_status" => "publish",
                    "comment_status" => "closed", // if you prefer
                    "ping_status" => "closed", // if you prefer
                ]);

                if ($post_id) {
                    wp_set_post_terms(
                        $post_id,
                        [$anno_category],
                        "annocategory"
                    );
                }
                $actions_items->set_action_items($anno_title);

                if (isset($_POST["anno_event_id"])) {
                    $annoEventId = sanitize_text_field(
                        wp_unslash($_POST["anno_event_id"])
                    );
                    update_field("anno_event_id", $annoEventId, $post_id);
                }
                $error_msg["msg"] = "success";
                $error_msg["msg_html"] = esc_html__(
                    "Announcement added successfully",
                    "sd91"
                );
            } else {
                $error_msg["msg"] = "error";
                $error_msg["msg_error"] = esc_html__("Duplicate title", "cupe");
            }
        } else {
            $error_msg["msg"] = "error";
            $error_msg["msg_error"] = esc_html__(
                "Contents can't be empty",
                "cupe"
            );
        }

        echo json_encode($error_msg);
        exit();
    }

    /**
     * Ajax Add Event
     *
     * @return NUL
     *
     * @since  1.0.0
     * @access all
     */
    public function add_event_form_ajax()
    {
        global $wpdb, $actions_items;
        //print_r($_POST); print_r($_FILES); die('test');
        header("Content-Type: application/json");
        $wp_upload_dir = wp_upload_dir();

        $user_id = get_current_user_id();

        $error_msg = [];
        if (
            !wp_verify_nonce($_POST["_availability_nonce"], "form_event_nonce")
        ) {
            $error_msg["msg"] = esc_html__("Security error!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (!is_user_logged_in()) {
            $error_msg["msg"] = esc_html__("You are not logged in!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (
            !empty($_POST["event_title"]) &&
            !empty($_POST["event_cat"]) &&
            !empty($_POST["start_event_date"]) &&
            !empty($_POST["end_event_date"]) &&
            !empty($_POST["event_venue"]) &&
            !empty($_POST["event_web"]) &&
            !empty($_POST["event_desc"])
        ) {
            $event_title = sanitize_text_field(
                wp_unslash($_POST["event_title"])
            );
            $event_cat = sanitize_text_field(wp_unslash($_POST["event_cat"]));
            $event_org = sanitize_text_field(
                wp_unslash($_POST["event_organizer"])
            );

            $start_event_date = sanitize_text_field(
                wp_unslash($_POST["start_event_date"])
            );
            $start_event_hour = sanitize_text_field(
                wp_unslash($_POST["start_event_hour"])
            );
            $start_event_min = sanitize_text_field(
                wp_unslash($_POST["start_event_min"])
            );
            $start_event_meri = sanitize_text_field(
                wp_unslash($_POST["start_event_meri"])
            );
            if ($start_event_meri == "PM") {
                $start_event_hour = $start_event_hour + 12;
            }

            $StartHour = date(
                "H:i:s",
                strtotime($start_event_hour . "" . $start_event_min)
            );

            $end_event_date = sanitize_text_field(
                wp_unslash($_POST["end_event_date"])
            );
            $end_event_hour = sanitize_text_field(
                wp_unslash($_POST["end_event_hour"])
            );
            $end_event_min = sanitize_text_field(
                wp_unslash($_POST["end_event_min"])
            );
            $end_event_meri = sanitize_text_field(
                wp_unslash($_POST["end_event_meri"])
            );
            if ($end_event_meri == "PM") {
                $end_event_hour = $end_event_hour + 12;
            }

            $EndHour = date(
                "H:i:s",
                strtotime($end_event_hour . "" . $end_event_min)
            );

            $event_venue = sanitize_text_field(
                wp_unslash($_POST["event_venue"])
            );
            $event_web = sanitize_text_field(wp_unslash($_POST["event_web"]));
            $event_desc = sanitize_text_field(wp_unslash($_POST["event_desc"]));

            $start_event = $start_event_date . " " . $StartHour;
            $end_event = $end_event_date . " " . $EndHour;

            $fount_post = post_exists($event_title, "", "", "tribe_events");
            if ($fount_post == 0) {
                $venue_args = [
                    "venue" => $event_venue,
                    "status" => "publish",
                ];

                $venue = tribe_venues()
                    ->set_args($venue_args)
                    ->create();

                $organizer_args = [
                    "venue" => $event_venue,
                    "status" => "publish",
                ];

                $organizer = tribe_organizers()
                    ->set_args($organizer_args)
                    ->create();

                $args = [
                    "title" => $event_title,
                    "status" => "publish",
                    "start_date" => $start_event,
                    "end_date" => $end_event,
                    "description" => $event_desc,
                    "venue" => $venue->ID,
                    "organizer" => $organizer->ID,
                    "url" => $event_web,
                    "category" => [$event_cat],
                    "show_map" => true,
                ];

                $result = tribe_events()
                    ->set_args($args)
                    ->create();

                $post_id = $result->ID;

                if ($post_id) {
                    if (!empty($_FILES["event_attachment"]["name"])) {
                        // These files need to be included as dependencies when on the front end.
                        require_once ABSPATH . "wp-admin/includes/image.php";
                        require_once ABSPATH . "wp-admin/includes/file.php";
                        require_once ABSPATH . "wp-admin/includes/media.php";

                        $attachment_id = media_handle_upload(
                            "event_attachment",
                            $post_id
                        );
                        //echo $error_string = $attachment_id->get_error_message();
                        if (!is_wp_error($attachment_id)) {
                            update_post_meta(
                                $post_id,
                                "_thumbnail_id",
                                $attachment_id
                            );
                        }
                    }
                }
                //$actions_items->set_action_items($event_title);
                $error_msg["msg"] = "success";
                $error_msg["return_url"] = esc_url(home_url("/events-admin/"));
                $error_msg["msg_html"] = esc_html__(
                    "Event created successfully",
                    "sd91"
                );
            } else {
                $error_msg["msg"] = "error";
                $error_msg["msg_error"] = esc_html__("Duplicate title", "cupe");
            }
        } else {
            $error_msg["msg"] = "error";
            $error_msg["msg_error"] = esc_html__(
                "Contents can't be empty",
                "cupe"
            );
        }

        echo json_encode($error_msg);
        exit();
    }

    /**
     * Ajax Update Event
     *
     * @return NUL
     *
     * @since  1.0.0
     * @access all
     */
    public function update_event_form_ajax()
    {
        global $wpdb, $actions_items;
        //print_r($_POST); print_r($_FILES); die('test');
        header("Content-Type: application/json");
        $wp_upload_dir = wp_upload_dir();

        $user_id = get_current_user_id();

        $error_msg = [];
        if (
            !wp_verify_nonce(
                $_POST["_availability_nonce"],
                "form_upevent_nonce"
            )
        ) {
            $error_msg["msg"] = esc_html__("Security error!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (!is_user_logged_in()) {
            $error_msg["msg"] = esc_html__("You are not logged in!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (
            !empty($_POST["event_id"]) &&
            !empty($_POST["event_title"]) &&
            !empty($_POST["event_cat"]) &&
            !empty($_POST["start_event_date"]) &&
            !empty($_POST["end_event_date"]) &&
            !empty($_POST["event_venue"]) &&
            !empty($_POST["event_web"]) &&
            !empty($_POST["event_desc"])
        ) {
            $event_id = sanitize_text_field(wp_unslash($_POST["event_id"]));
            $event_title = sanitize_text_field(
                wp_unslash($_POST["event_title"])
            );
            $event_cat = sanitize_text_field(wp_unslash($_POST["event_cat"]));
            $event_org = sanitize_text_field(
                wp_unslash($_POST["event_organizer"])
            );

            $start_event_date = sanitize_text_field(
                wp_unslash($_POST["start_event_date"])
            );
            $start_event_hour = sanitize_text_field(
                wp_unslash($_POST["start_event_hour"])
            );
            $start_event_min = sanitize_text_field(
                wp_unslash($_POST["start_event_min"])
            );
            $start_event_meri = sanitize_text_field(
                wp_unslash($_POST["start_event_meri"])
            );
            if ($start_event_meri == "PM") {
                $start_event_hour = $start_event_hour + 12;
            }

            $StartHour = date(
                "H:i:s",
                strtotime($start_event_hour . "" . $start_event_min)
            );

            $end_event_date = sanitize_text_field(
                wp_unslash($_POST["end_event_date"])
            );
            $end_event_hour = sanitize_text_field(
                wp_unslash($_POST["end_event_hour"])
            );
            $end_event_min = sanitize_text_field(
                wp_unslash($_POST["end_event_min"])
            );
            $end_event_meri = sanitize_text_field(
                wp_unslash($_POST["end_event_meri"])
            );
            if ($end_event_meri == "PM") {
                $end_event_hour = $end_event_hour + 12;
            }

            $EndHour = date(
                "H:i:s",
                strtotime($end_event_hour . "" . $end_event_min)
            );

            $event_venue = sanitize_text_field(
                wp_unslash($_POST["event_venue"])
            );
            $event_web = sanitize_text_field(wp_unslash($_POST["event_web"]));
            $event_desc = sanitize_text_field(wp_unslash($_POST["event_desc"]));

            $post = [
                "ID" => esc_sql($event_id),
                "post_content" => wp_kses_post($event_desc),
                "post_title" => wp_strip_all_tags($event_title),
            ];
            $result = wp_update_post($post, true);

            if (!is_wp_error($result)) {
                $start_event = $start_event_date . " " . $StartHour;

                $end_event = $end_event_date . " " . $EndHour;

                update_post_meta($event_id, "_EventStartDate", $start_event);
                update_post_meta($event_id, "_EventStartTime", $StartHour);
                update_post_meta($event_id, "_EventEndDate", $end_event);
                update_post_meta($event_id, "_EventEndTime", $EndHour);
                update_post_meta($event_id, "_EventURL", $event_web);

                //==================For Organizer
                $org_id = post_exists($event_org, "", "", "tribe_organizer");
                if ($org_id == 0) {
                    $org_id = wp_insert_post([
                        "post_type" => "tribe_organizer",
                        "post_title" => $event_org,
                        "post_status" => "publish",
                        "comment_status" => "closed", // if you prefer
                        "ping_status" => "closed", // if you prefer
                    ]);
                }

                update_post_meta($event_id, "_EventOrganizerID", $org_id);

                //==================For Venue
                $venue_id = post_exists($event_venue, "", "", "tribe_venue");
                if ($venue_id == 0) {
                    $venue_id = wp_insert_post([
                        "post_type" => "tribe_venue",
                        "post_title" => $event_venue,
                        "post_status" => "publish",
                        "comment_status" => "closed", // if you prefer
                        "ping_status" => "closed", // if you prefer
                    ]);
                }
                update_post_meta($event_id, "_EventVenueID", $venue_id);

                wp_set_post_terms($event_id, [$event_cat], "tribe_events_cat");

                if (!empty($_FILES["event_attachment"]["name"])) {
                    // These files need to be included as dependencies when on the front end.
                    require_once ABSPATH . "wp-admin/includes/image.php";
                    require_once ABSPATH . "wp-admin/includes/file.php";
                    require_once ABSPATH . "wp-admin/includes/media.php";

                    $attachment_id = media_handle_upload(
                        "event_attachment",
                        $event_id
                    );
                    if (!is_wp_error($attachment_id)) {
                        update_post_meta(
                            $event_id,
                            "_thumbnail_id",
                            $attachment_id
                        );
                    }
                }
                //$actions_items->set_action_items($event_title);
                $error_msg["msg"] = "success";
                $error_msg["return_url"] = esc_url(home_url("/events-admin/"));
                $error_msg["msg_html"] = esc_html__(
                    "Event updated successfully",
                    "sd91"
                );
            } else {
                $error_msg["msg"] = "error";
                $error_msg["msg_error"] = esc_html__(
                    "Unable to update",
                    "cupe"
                );
            }
        } else {
            $error_msg["msg"] = "error";
            $error_msg["msg_error"] = esc_html__(
                "Contents can't be empty",
                "cupe"
            );
        }

        echo json_encode($error_msg);
        exit();
    }

    /**
     * Ajax Add Documents
     *
     * @return NUL
     *
     * @since  1.0.0
     * @access all
     */
    public function osh_add_doc_form_ajax()
    {
        global $wpdb, $actions_items;
        self::create_table_document();
        require_once ABSPATH . "wp-admin/includes/media.php";
        require_once ABSPATH . "wp-admin/includes/file.php";
        require_once ABSPATH . "wp-admin/includes/image.php";
        header("Content-Type: application/json");
        $upload_dir = wp_upload_dir();
        $current_user = wp_get_current_user();
        $new_post_author = $current_user->ID;
        //print_r($_POST);
        //print_r($_FILES); die('test');
        if (!empty($_POST["user_id"])) {
            $user_id = $_POST["user_id"];
        } else {
            $user_id = get_current_user_id();
        }

        $error_msg = [];
        if (
            !wp_verify_nonce(
                $_POST["_availability_nonce"],
                "ohs_form_doc_nonce"
            )
        ) {
            $error_msg["msg"] = esc_html__("Security error!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (!is_user_logged_in()) {
            $error_msg["msg"] = esc_html__("You are not logged in!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (
            !empty($_POST["doc_title"]) &&
            !empty($_POST["doc_cat_id"]) &&
            !empty($_POST["doc_details"]) &&
            !empty($_FILES["doc_attachment"]["name"])
        ) {
            $doc_title = sanitize_text_field(wp_unslash($_POST["doc_title"]));
            $doc_cat_ids = explode(",", $_POST["doc_cat_id"]);
            $doc_details = sanitize_text_field(
                wp_unslash($_POST["doc_details"])
            );

            //-------Start-------Inserting into the flipbook
            $new_id = 0;
            $highest_id = 0;
            $fount_post = post_exists($doc_title, "", "", "r3d");
            if ($fount_post == 0) {
                $real3dflipbooks_ids = get_option("real3dflipbooks_ids");
                foreach ($real3dflipbooks_ids as $id) {
                    if ((int) $id > $highest_id) {
                        $highest_id = (int) $id;
                    }
                }

                $new_id = $highest_id + 1;

                array_push($real3dflipbooks_ids, $new_id);
                update_option("real3dflipbooks_ids", $real3dflipbooks_ids);
                /*
                 * new post data array
                 */
                $args = [
                    "post_title" => $doc_title,
                    "post_type" => "r3d",
                    "post_author" => $new_post_author,
                    // 'post_content'=>'demo text',
                    "post_status" => "publish",
                    "meta_input" => [
                        "flipbook_id" => $new_id,
                    ],
                ];

                $new_post_id = wp_insert_post($args);
                if ($new_post_id) {
                    foreach ($doc_cat_ids as $doc_cat_id) {
                        wp_set_post_terms(
                            $new_post_id,
                            $doc_cat_id,
                            "r3d_category",
                            true
                        );
                    }
                    update_post_meta($new_post_id, "contents", $doc_details);
                }
                //$actions_items->set_action_items($doc_title);
                $attachment_id = media_handle_upload(
                    "doc_attachment",
                    $new_post_id
                );
                $thumbUrl =
                    site_url() .
                    "/wp-content/uploads/real3d-flipbook/flipbook_" .
                    $new_id .
                    "/thumb.jpg";
                if (!is_wp_error($attachment_id)) {
                    $attachmentUrl = wp_get_attachment_url($attachment_id);

                    //save post id to book
                    $new["post_id"] = $new_post_id;
                    $new["id"] = $new_id;
                    $new["name"] = $doc_title;
                    $new["pdfUrl"] = $attachmentUrl;
                    $new["date"] = current_time("mysql");
                    $new["lightboxThumbnailUrl"] = $thumbUrl;
                    update_option("real3dflipbook_" . (string) $new_id, $new);
                    //saving data at table document_upload_details
                    $wpdb->insert($wpdb->prefix . "document_upload_details", [
                        "post_id" => $new_post_id,
                        "user_id" => $user_id,
                        "pdf_url" => $attachmentUrl,
                        "is_reverted" => "no",
                        "created_at" => date_i18n("Y-m-d H:i:s"),
                    ]);
                    $error_msg["msg"] = "success";
                    $error_msg["msg_html"] = esc_html__(
                        "Document added successfully",
                        "cupe"
                    );
                } else {
                    $error_msg["msg"] = "error";
                    $error_msg["msg_error"] = esc_html__(
                        "Unable to attached file",
                        "cupe"
                    );
                }
            } else {
                $error_msg["msg"] = "error";
                $error_msg["msg_error"] = esc_html__("Duplicate title", "cupe");
            }
        } else {
            $error_msg["msg"] = "error";
            $error_msg["msg_error"] = esc_html__(
                "Contents can't be empty",
                "cupe"
            );
        }

        echo json_encode($error_msg);
        exit();
    }

    /**
     * Ajax Edit Documents
     *
     * @return NUL
     *
     * @since  1.0.0
     * @access all
     */
    public function osh_edit_doc_form_ajax()
    {
        global $wpdb, $wp_query, $post, $actions_items;
        self::create_table_document();
        require_once ABSPATH . "wp-admin/includes/media.php";
        require_once ABSPATH . "wp-admin/includes/file.php";
        require_once ABSPATH . "wp-admin/includes/image.php";
        header("Content-Type: application/json");
        $upload_dir = wp_upload_dir();
        $current_user = wp_get_current_user();
        $new_post_author = $current_user->ID;
        //print_r($_POST);
        //print_r($_FILES); die('test');

        if (!empty($_POST["user_id"])) {
            $user_id = $_POST["user_id"];
        } else {
            $user_id = get_current_user_id();
        }

        $error_msg = [];
        if (
            !wp_verify_nonce(
                $_POST["_availability_nonce"],
                "ohs_form_edit_doc_nonce"
            )
        ) {
            $error_msg["msg"] = esc_html__("Security error!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (!is_user_logged_in()) {
            $error_msg["msg"] = esc_html__("You are not logged in!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (
            !empty($_POST["post_id"]) &&
            !empty($_POST["doc_title"]) &&
            !empty($_POST["doc_cat_id"]) &&
            !empty($_POST["doc_details"])
        ) {
            $doc_title = sanitize_text_field(wp_unslash($_POST["doc_title"]));
            $post_id = esc_sql($_POST["post_id"]);
            $doc_cat_ids = explode(",", $_POST["doc_cat_id"]);
            $doc_details = sanitize_text_field(
                wp_unslash($_POST["doc_details"])
            );

            //-------Start-------Inserting into the flipbook

            /*
             * update post data array
             */
            $post = [
                "ID" => $post_id,
                "post_title" => wp_strip_all_tags($doc_title),
            ];
            $result = wp_update_post($post, true);

            if (is_wp_error($result)) {
                //wp_die('Post not saved');
                $error_msg["msg"] = "error";
            } else {
                wp_set_post_terms(
                    $post_id,
                    $doc_cat_ids,
                    "r3d_category",
                    false
                );
                update_post_meta($post_id, "contents", $doc_details);
                //$actions_items->set_action_items($doc_title);
                if (!empty($_FILES["doc_attachment"]["name"])) {
                    $attachment_id = media_handle_upload(
                        "doc_attachment",
                        $post_id
                    );
                    //echo $error_string = $attachment_id->get_error_message();
                    if (!is_wp_error($attachment_id)) {
                        $attachmentUrl = wp_get_attachment_url($attachment_id);
                        $post_meta = get_post_meta($post_id, "flipbook_id");
                        $flipbook_id = $post_meta[0];
                        $thumbUrl =
                            site_url() .
                            "/wp-content/uploads/real3d-flipbook/flipbook_" .
                            $flipbook_id .
                            "/thumb.jpg";
                        //save post id to book
                        $new["post_id"] = $post_id;
                        $new["id"] = $flipbook_id;
                        $new["name"] = $doc_title;
                        $new["pdfUrl"] = $attachmentUrl;
                        $new["date"] = current_time("mysql");
                        $new["lightboxThumbnailUrl"] = $thumbUrl;
                        update_option(
                            "real3dflipbook_" . (string) $flipbook_id,
                            $new
                        );
                        //saving data at table document_upload_details
                        $wpdb->insert(
                            $wpdb->prefix . "document_upload_details",
                            [
                                "post_id" => $post_id,
                                "user_id" => $user_id,
                                "pdf_url" => $attachmentUrl,
                                "is_reverted" => "no",
                                "created_at" => date_i18n("Y-m-d H:i:s"),
                            ]
                        );
                    }
                }
                $error_msg["msg"] = "success";
                $error_msg["msg_html"] = esc_html__(
                    "Document edited successfully",
                    "cupe"
                );
            }
        } else {
            $error_msg["msg"] = "error";
        }

        echo json_encode($error_msg);
        exit();
    }

    /**
     * Ajax Revert Documents
     *
     * @return NUL
     *
     * @since  1.0.0
     * @access all
     */
    public function osh_revert_doc_ajax()
    {
        //print_r($_POST); die('Test');
        global $wpdb, $wp_query, $post, $actions_items;

        $user_id = get_current_user_id();

        $error_msg = [];
        if (
            !wp_verify_nonce(
                $_POST["_availability_nonce"],
                "ohs_form_revert_doc_nonce"
            )
        ) {
            $error_msg["msg"] = esc_html__("Security error!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (!is_user_logged_in()) {
            $error_msg["msg"] = esc_html__("You are not logged in!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (!empty($_POST["post_id"])) {
            $post_id = esc_sql($_POST["post_id"]);
            $post = get_post($post_id);
            setup_postdata($post);
            $post_title = $post->post_title;
            //--getting last pdf url
            $sql =
                "SELECT `pdf_url` FROM " .
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

            $prePdfUrl = $wpdb->get_var($sql);
            if ($prePdfUrl != "") {
                $post_meta = get_post_meta($post_id, "flipbook_id");
                $flipbook_id = $post_meta[0];
                $thumbUrl =
                    site_url() .
                    "/wp-content/uploads/real3d-flipbook/flipbook_" .
                    $flipbook_id .
                    "/thumb.jpg";
                //save post id to book
                $new["post_id"] = $post_id;
                $new["id"] = $flipbook_id;
                $new["name"] = $post_title;
                $new["pdfUrl"] = $prePdfUrl;
                $new["date"] = current_time("mysql");
                $new["lightboxThumbnailUrl"] = $thumbUrl;
                update_option("real3dflipbook_" . (string) $flipbook_id, $new);
                //=========Updating table for reverted yes
                $wpdb->query(
                    $wpdb->prepare(
                        "UPDATE " .
                            $wpdb->prefix .
                            "document_upload_details 
            SET is_reverted = %s WHERE post_id = %d AND user_id = %d ",
                        "yes",
                        $post_id,
                        $user_id
                    )
                );
            }
            $error_msg["msg"] = "success";
            $error_msg["msg_html"] = esc_html__(
                "Document reverted successfully",
                "cupe"
            );
        } else {
            $error_msg["msg"] = "error";
        }

        echo json_encode($error_msg);
        exit();
    }

    /**
     * Ajax Replace document
     *
     * @return null
     *
     * @since  1.0.0
     * @access all
     */
    public function osh_rep_doc_form_ajax()
    {
        global $wpdb, $wp_query, $post, $actions_items;
        self::create_table_document();
        require_once ABSPATH . "wp-admin/includes/media.php";
        require_once ABSPATH . "wp-admin/includes/file.php";
        require_once ABSPATH . "wp-admin/includes/image.php";
        header("Content-Type: application/json");
        $upload_dir = wp_upload_dir();
        $current_user = wp_get_current_user();
        $new_post_author = $current_user->ID;
        //print_r($_POST);
        //print_r($_FILES); die('test');

        if (!empty($_POST["user_id"])) {
            $user_id = $_POST["user_id"];
        } else {
            $user_id = get_current_user_id();
        }

        $error_msg = [];
        if (
            !wp_verify_nonce(
                $_POST["_availability_nonce"],
                "ohs_form_rep_doc_nonce"
            )
        ) {
            $error_msg["msg"] = esc_html__("Security error!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (!is_user_logged_in()) {
            $error_msg["msg"] = esc_html__("You are not logged in!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (
            !empty($_POST["post_id"]) &&
            !empty($_FILES["rep_attachment"]["name"])
        ) {
            $post_id = esc_sql($_POST["post_id"]);
            $attachment_id = media_handle_upload("rep_attachment", $post_id);

            if (!is_wp_error($attachment_id)) {
                $post = get_post($did);
                setup_postdata($post);
                $post_title = $post->post_title;
                $attachmentUrl = wp_get_attachment_url($attachment_id);
                $post_meta = get_post_meta($post_id, "flipbook_id");
                $flipbook_id = $post_meta[0];
                $thumbUrl =
                    site_url() .
                    "/wp-content/uploads/real3d-flipbook/flipbook_" .
                    $flipbook_id .
                    "/thumb.jpg";
                //save post id to book
                $new["post_id"] = $post_id;
                $new["id"] = $flipbook_id;
                $new["name"] = $post_title;
                $new["pdfUrl"] = $attachmentUrl;
                $new["date"] = current_time("mysql");
                $new["lightboxThumbnailUrl"] = $thumbUrl;
                update_option("real3dflipbook_" . (string) $flipbook_id, $new);
                //saving data at table document_upload_details
                $wpdb->insert($wpdb->prefix . "document_upload_details", [
                    "post_id" => $post_id,
                    "user_id" => $user_id,
                    "pdf_url" => $attachmentUrl,
                    "is_reverted" => "no",
                    "created_at" => date_i18n("Y-m-d H:i:s"),
                ]);
                //echo $wpdb->last_query;
            }

            $error_msg["msg"] = "success";
            $error_msg["msg_html"] = esc_html__(
                "Document replaced successfully",
                "cupe"
            );
        } else {
            $error_msg["msg"] = "error";
        }

        echo json_encode($error_msg);
        exit();
    }

    /**
     * Table for form access permision
     *
     * @return null
     *
     * @since  1.0.0
     * @access all
     */
    public function create_table_access_permision()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "gf_form_access_permision";
        $charset_collate = $wpdb->get_charset_collate();
        $query = "SHOW TABLES LIKE '{$table_name}'";

        $chcek_table = $wpdb->get_var($query);

        if (!$chcek_table == $table_name) {
            $sql = "CREATE TABLE `$table_name` (
                  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          `form_id` bigint(20) unsigned NOT NULL,
          `user_ids` varchar(255) NULL,
          `created_at` datetime NOT NULL,
          PRIMARY KEY (`ID`),
          KEY `form_id` (`form_id`),
          KEY `user_ids` (`user_ids`),
          KEY `created_at` (`created_at`)
        )";

            $table_created = $wpdb->query($sql);
        }
    }

    /**
     * Table for document track
     *
     * @return null
     *
     * @since  1.0.0
     * @access all
     */
    public function create_table_document()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . "document_upload_details";
        $charset_collate = $wpdb->get_charset_collate();
        $query = "SHOW TABLES LIKE '{$table_name}'";

        $chcek_table = $wpdb->get_var($query);

        if (!$chcek_table == $table_name) {
            $sql = "CREATE TABLE `$table_name` (
                `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `post_id` bigint(20) unsigned NOT NULL,
                `user_id` bigint(20) unsigned NOT NULL,
                `pdf_url` varchar(255) NULL,
                `is_reverted` varchar(20) DEFAULT NULL,
                `created_at` datetime NOT NULL,
                PRIMARY KEY (`ID`),
                KEY `post_id` (`post_id`),
                KEY `user_id` (`user_id`),
                KEY `pdf_url` (`pdf_url`),
                KEY `created_at` (`created_at`)
              )";

            $table_created = $wpdb->query($sql);
        }
    }
}

$employee_frontend = new OSHEmployeeFrontend();