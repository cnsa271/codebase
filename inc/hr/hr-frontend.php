<?php
if (!defined("ABSPATH")) {
    die("You are not allowed to call this page directly.");
}
/**
 * OSH HR Frontend Part
 */
class OSHHRFrontend
{
    public function __construct()
    {
        add_action("wp_enqueue_scripts", [$this, "enqueue_scripts"]); //hook to enequeue the javascript to the page.
        add_action("wp_ajax_inc_action_close", [$this, "inc_action_close"]);
        add_action("wp_ajax_nopriv_inc_action_close", [
            $this,
            "inc_action_close",
        ]);

        add_action("wp_ajax_inc_action_request", [$this, "inc_action_request"]);
        add_action("wp_ajax_nopriv_inc_action_request", [
            $this,
            "inc_action_request",
        ]);

        add_action("wp_ajax_inc_action_request_reopen", [
            $this,
            "inc_action_request_reopen",
        ]);
        add_action("wp_ajax_nopriv_inc_action_request_reopen", [
            $this,
            "inc_action_request_reopen",
        ]);

        add_action("wp_ajax_inc_f6a_approve", [$this, "inc_f6a_approve"]);
        add_action("wp_ajax_nopriv_inc_f6a_approve", [
            $this,
            "inc_f6a_approve",
        ]);

        add_action("wp_ajax_inc_f6a_close", [$this, "inc_f6a_close"]);
        add_action("wp_ajax_nopriv_inc_f6a_close", [$this, "inc_f6a_close"]);
    }

    /*
      Enqueue Frontend JS
  */
    public function enqueue_scripts()
    {
        wp_enqueue_script(
            "hr_js",
            get_template_directory_uri() . "/assets/js/hr.js",
            ["jquery"],
            "1.1",
            true
        );
        wp_localize_script("hr_ajax_js", "frontend_ajax_object", [
            "ajaxurl" => admin_url("admin-ajax.php"),
        ]);
    }

    /**
     * Incident Report Mark As Resolved
     *
     * @return msg
     *
     * @since  1.0.0
     * @access all
     */
    public function inc_action_close()
    {
        //print_r($_POST); die('test');
        global $wpdb, $wp_query, $post;
        header("Content-Type: application/json");
        $error_msg = [];

        if (!is_user_logged_in()) {
            $error_msg["msg"] = esc_html__("You are not logged in!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (empty($_POST["inc_id"])) {
            $error_msg["msg"] = "error";
        }

        if (empty($error_msg)) {
            $user_id = get_current_user_id();
            $inc_id = sanitize_text_field(wp_unslash($_POST["inc_id"]));
            $inc_id_array = explode("/", $inc_id);
            $post_id = $inc_id_array[0];
            $staff_id = $inc_id_array[3];
            $admin_id = $inc_id_array[4];
            //wp_update_post(array( 'ID' =>  $post_id, 'post_status' => 'Resolved' ));
            update_field("in_status", "Resolved", $post_id);

            $error_msg["msg"] = "success";
            $error_msg["msg_html"] = esc_html__(
                "Mark as Resolved successfully",
                "in"
            );

            //$this->inc_action_mail($staff_id, $admin_id);
        } else {
            $error_msg["msg"] = "error";
            $error_msg["msg_html"] = esc_html__(
                "Try after some times",
                "incident"
            );
        }
        echo json_encode($error_msg);
        exit();
    }

    /**
     * Incident Report Mark As Resolved
     *
     * @return msg
     *
     * @since  1.0.0
     * @access all
     */
    public function inc_f6a_approve()
    {
        //print_r($_POST); die('test');
        global $wpdb, $wp_query, $post;
        header("Content-Type: application/json");
        $error_msg = [];

        if (!is_user_logged_in()) {
            $error_msg["msg"] = esc_html__("You are not logged in!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (empty($_POST["f6a_app_id"])) {
            $error_msg["msg"] = "error";
        }

        if (empty($error_msg)) {
            $user_id = get_current_user_id();
            $f6a_app_id = sanitize_text_field(wp_unslash($_POST["f6a_app_id"]));
            $inc_id_array = explode("/", $f6a_app_id);
            $post_id = $inc_id_array[0];
            $staff_id = $inc_id_array[3];
            $admin_id = $inc_id_array[4];

            update_field("rec_status", "Approved", $post_id);

            $error_msg["msg"] = "success";
            $error_msg["msg_html"] = esc_html__(
                "Mark as Approved successfully",
                "in"
            );

            //$this->inc_action_mail($staff_id, $admin_id);
        } else {
            $error_msg["msg"] = "error";
            $error_msg["msg_html"] = esc_html__(
                "Try after some times",
                "incident"
            );
        }
        echo json_encode($error_msg);
        exit();
    }

    /**
     * Incident Report Mark As Resolved
     *
     * @return msg
     *
     * @since  1.0.0
     * @access all
     */
    public function inc_f6a_close()
    {
        global $wpdb, $wp_query, $post;
        header("Content-Type: application/json");
        $error_msg = [];

        if (!is_user_logged_in()) {
            $error_msg["msg"] = esc_html__("You are not logged in!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (empty($_POST["f6a_close_id"])) {
            $error_msg["msg"] = "error";
        }

        if (empty($error_msg)) {
            $user_id = get_current_user_id();
            $f6a_close_id = sanitize_text_field(
                wp_unslash($_POST["f6a_close_id"])
            );
            $inc_id_array = explode("/", $f6a_close_id);
            $post_id = $inc_id_array[0];
            $from_id = $inc_id_array[1];
            $entry_id = $inc_id_array[2];
            $staff_id = $inc_id_array[3];
            $attnd_id = $inc_id_array[4];

            $incident_id =
                $from_id . "/" . $entry_id . "/" . $staff_id . "/" . $attnd_id;

            update_field("rec_status", "Resolved", $post_id);

            //==============Updating Incident Status
            $inc_id = $this->get_post_id_by_meta_key_and_value_class(
                "incident_id",
                $incident_id
            );
            update_field("in_status", "Resolved", $inc_id);

            //==============Updating Requested Status
            $f6rec_id = $this->get_post_id_by_meta_key_and_value_class(
                "req_inc_id",
                $incident_id
            );
            update_field("req_status", "Resolved", $f6rec_id);

            $error_msg["msg"] = "success";
            $error_msg["msg_html"] = esc_html__(
                "Mark as Resolved successfully",
                "in"
            );

            //$this->inc_action_mail($staff_id, $admin_id);
        } else {
            $error_msg["msg"] = "error";
            $error_msg["msg_html"] = esc_html__(
                "Try after some times",
                "incident"
            );
        }
        echo json_encode($error_msg);
        exit();
    }

    /**
     * Incident Report Mark As Requested
     *
     * @return msg
     *
     * @since  1.0.0
     * @access all
     */
    public function inc_action_request()
    {
        //print_r($_POST); die('test');
        global $wpdb, $wp_query, $post;
        header("Content-Type: application/json");
        $error_msg = [];

        if (!is_user_logged_in()) {
            $error_msg["msg"] = esc_html__("You are not logged in!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (empty($_POST["req_inc_id"])) {
            $error_msg["msg"] = "error";
        }

        if (empty($error_msg)) {
            $hr_id = get_current_user_id();
            $inc_id = sanitize_text_field(wp_unslash($_POST["req_inc_id"]));
            $inc_id_array = explode("/", $inc_id);
            $post_id = $inc_id_array[0];
            $staff_id = $inc_id_array[3];
            $admin_id = $inc_id_array[4];

            $staff_obj = get_user_by("id", $staff_id);
            $staff_name = $staff_obj->display_name;
            $form_title = "WorkSafeBC Form 6A Requested(" . $staff_name . ")";
            update_field("in_status", "Approved", $post_id);

            $post_req_id = wp_insert_post([
                "post_type" => "worksafebc6a_req",
                "post_title" => $form_title,
                "post_status" => "publish",
                "comment_status" => "closed", // if you prefer
                "ping_status" => "closed", // if you prefer
            ]);
            if ($post_req_id) {
                $incident_id = get_field("incident_id", $post_id, true);
                $staff_id = get_field("staff_name", $post_id, true);
                $attnd_id = get_field("attendant_name", $post_id, true);
                $request_date = date("Y-m-d H:i:s");
                $req_status = "Requested";

                update_field("req_inc_id", $incident_id, $post_req_id);
                update_field("req_staff_name", $staff_id, $post_req_id);
                update_field("req_attnd_name", $attnd_id, $post_req_id);
                update_field("req_hr_name", $hr_id, $post_req_id);
                update_field("req_date", $request_date, $post_req_id);
                update_field("req_status", $req_status, $post_req_id);

                $form_link_id = $post_req_id . "/" . $incident_id;

                $mail_sub = "WorkSafeBC Form 6A Requested";
                $this->workSafeBC_form6A_requested_mail_staff(
                    $form_link_id,
                    $staff_id,
                    $attnd_id,
                    $hr_id,
                    $request_date,
                    $mail_sub
                );
            }

            $error_msg["msg"] = "success";
            $error_msg["msg_html"] = esc_html__("Requested successfully", "in");

            //$this->inc_action_mail($staff_id, $admin_id);
        } else {
            $error_msg["msg"] = "error";
            $error_msg["msg_html"] = esc_html__(
                "Try after some times",
                "incident"
            );
        }
        echo json_encode($error_msg);
        exit();
    }

    /**
     * Incident Report Mark As Requested For Reopen Tasks
     *
     * @return msg
     *
     * @since  1.0.0
     * @access all
     */
    public function inc_action_request_reopen()
    {
        //print_r($_POST); die('test');
        global $wpdb, $wp_query, $post;
        header("Content-Type: application/json");
        $error_msg = [];

        if (!is_user_logged_in()) {
            $error_msg["msg"] = esc_html__("You are not logged in!", "osh");
            echo json_encode($error_msg);
            exit();
        }

        if (empty($_POST["re_req_inc_id"])) {
            $error_msg["msg"] = "error";
        }

        if (empty($error_msg)) {
            $hr_id = get_current_user_id();
            $inc_id = sanitize_text_field(wp_unslash($_POST["re_req_inc_id"]));
            $inc_id_array = explode("/", $inc_id);
            $post_id = $inc_id_array[0];
            $staff_id = $inc_id_array[3];
            $admin_id = $inc_id_array[4];

            $staff_obj = get_user_by("id", $staff_id);
            $staff_name = $staff_obj->display_name;
            $form_title = "WorkSafeBC Form 6A Requested(" . $staff_name . ")";
            update_field("in_status", "Approved", $post_id);

            $post_req_id = wp_insert_post([
                "post_type" => "worksafebc6a_req",
                "post_title" => $form_title,
                "post_status" => "publish",
                "comment_status" => "closed", // if you prefer
                "ping_status" => "closed", // if you prefer
            ]);
            if ($post_req_id) {
                update_field("is_inc_re_open", "Yes", $post_id);
                $incident_id = get_field("incident_id", $post_id, true);
                $staff_id = get_field("staff_name", $post_id, true);
                $attnd_id = get_field("attendant_name", $post_id, true);
                $request_date = date("Y-m-d H:i:s");
                $req_status = "Requested";

                update_field("req_inc_id", $incident_id, $post_req_id);
                update_field("req_staff_name", $staff_id, $post_req_id);
                update_field("req_attnd_name", $attnd_id, $post_req_id);
                update_field("req_hr_name", $hr_id, $post_req_id);
                update_field("req_date", $request_date, $post_req_id);
                update_field("req_status", $req_status, $post_req_id);

                $form_link_id = $post_req_id . "/" . $incident_id;

                $mail_sub = "WorkSafeBC Form 6A Requested(Reopen)";
                $this->workSafeBC_form6A_requested_mail_staff(
                    $form_link_id,
                    $staff_id,
                    $attnd_id,
                    $hr_id,
                    $request_date,
                    $mail_sub
                );
            }

            $error_msg["msg"] = "success";
            $error_msg["msg_html"] = esc_html__(
                "Requested(Reopen) successfully",
                "in"
            );

            //$this->inc_action_mail($staff_id, $admin_id);
        } else {
            $error_msg["msg"] = "error";
            $error_msg["msg_html"] = esc_html__(
                "Try after some times",
                "incident"
            );
        }
        echo json_encode($error_msg);
        exit();
    }

    /**
     * Get post id from meta key and value
     * @param string $key
     * @param mixed $value
     * @return int|bool
     * @author David M&aring;rtensson <david.martensson@gmail.com>
     */
    public function get_post_id_by_meta_key_and_value_class($key, $value)
    {
        global $wpdb;
        $tbl = $wpdb->prefix . "postmeta";
        $meta = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $tbl WHERE meta_key=%s AND meta_value=%s",
                $key,
                $value
            )
        );
        if (is_array($meta) && !empty($meta) && isset($meta[0])) {
            $meta = $meta[0];
        }
        if (is_object($meta)) {
            return $meta->post_id;
        } else {
            return false;
        }
    }

    /**
     * sending mail as per HR actions
     *
     * @return msg
     *
     * @since  1.0.0
     * @access all
     */
    public function workSafeBC_form6A_requested_mail_staff(
        $form_link_id,
        $staff_id,
        $attnd_id,
        $hr_id,
        $request_date,
        $mail_sub
    ) {
        global $user, $wp_roles;
        $staff_info = get_userdata($staff_id); // gets staff data
        if (empty($staff_info)) {
            return "";
        }
        if (!empty($staff_info->display_name)) {
            $satff_name = $staff_info->display_name;
        }
        if (!empty($staff_info->user_email)) {
            $staff_email = $staff_info->user_email;
        }

        $hr_info = get_userdata($hr_id); // gets HR data
        if (empty($hr_info)) {
            return "";
        }
        if (!empty($hr_info->display_name)) {
            $hr_name = $hr_info->display_name;
        }
        if (!empty($hr_info->user_email)) {
            $hr_email = $hr_info->user_email;
        }

        $form_link_url =
            esc_url(home_url("/incident-worksafebc-form-6a-form/")) .
            "?in_req_id=" .
            base64_encode($form_link_id);

        $body =
            '<table cellspacing="0" cellpadding="0" style="border-collapse:collapse;border:none;width:600px;margin:0 auto"><thead><tr><th style="padding:7px 30px;text-align:center;background:#202849"><a href="' .
            site_url("/") .
            '" style="display:inline-block;vertical-align:text-top"><img src="' .
            get_template_directory_uri() .
            '/assets/images/chimeric-logo.png" alt="Logo" style="display:inline-block;vertical-align:top;width:58px;height:auto"></a></th></tr></thead><tbody><tr><td style="background:#151934;padding:30px;font-family:Arial,Helvetica,sans-serif;color:#fff;font-size:14px;line-height:24px"><h3 style="color:#fff;margin:0 0 20px;text-transform:uppercase;font-weight:700;text-align:center;font-size:20px">' .
            $mail_sub .
            '</h3><p style="margin:0 0 11px;font-size:16px">Hi <strong style="color:#00aebd">' .
            $satff_name .
            '</strong>,</p><p style="margin:0 0 11px">You are required to fill out a WorkSafeBC Form 6A for your injury reported on ' .
            $request_date .
            '.</p><p style="margin:0 0 11px">Please click the link below to fill out the WorkSafeBC Form 6A.</p><p style="margin:0"><strong>Link:<br><a href="' .
            $form_link_url .
            '" style="text-decoration:none;color:#7593ff">' .
            $form_link_url .
            '</a></strong></p></td></tr><tr><td style="background:#151934;padding:5px 30px 55px;font-family:Arial,Helvetica,sans-serif;color:#fff;font-size:14px;line-height:24px"><p style="margin:0 0 11px">Thank you.</p><p style="margin:0"><a href="' .
            site_url("/") .
            '" style="display:inline-block;vertical-align:top"><img src="' .
            get_template_directory_uri() .
            '/assets/images/chimeric-logo.png" alt="Logo" style="display:block;width:78px;height:auto"></a></p></td></tr></tbody><tfoot><tr><td style="background:#202849;padding:11px 30px;text-align:center;font-size:11px;line-height:22px;color:#c9cbd0;font-family:Arial,Helvetica,sans-serif">By using this service you are agreeing to the Terms of Use and Privacy Policy</td></tr></tfoot></table>';

        $headers[] = "Content-Type: text/html; charset=UTF-8";
        $headers[] = "From: The HR Team at Chimeric <test@testlab.com>";
        $headers[] = "Reply-To: The HR Team at Chimeric <test@testlab.com>";

        wp_mail($staff_email, $mail_sub, $body, $headers);
    }
}

$hr_frontend = new OSHHRFrontend();