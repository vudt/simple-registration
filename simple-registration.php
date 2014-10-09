<?php

/*
  Plugin Name: Simple Registration
  Plugin URI: http://google.com
  Description: Registration for Package's Real Estate 
  Version: 1.0
  Author: Vu Dang
  Author URI: http://google.com
 */

require_once(ABSPATH . 'wp-includes/pluggable.php');

$registration = new SimpleRegistration();

class SimpleRegistration {
    
    function __construct() {
        add_action('init', array($this, 'do_output_buffer'));
        add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'register_scripts'));
        add_action('wp_ajax_registration_location', array($this, 'callback_ajax_location'));
        add_action('wp_ajax_nopriv_registration_location', array($this, 'callback_ajax_location'));
        add_action('show_user_profile', array($this, 'display_user_meta'));
        add_action('edit_user_profile', array($this, 'display_user_meta'));
        add_action('edit_user_profile_update', array($this, 'update_extra_profile_fields'));
        add_shortcode('vu_registration', array($this, 'registration_shortcode'));
    }
    
    function do_output_buffer() {
        ob_start();
    }
    
    function register_scripts() {
        wp_enqueue_script('register-scripts', plugin_dir_url(__FILE__) . 'assets/common.js', array('jquery'));
        wp_localize_script('register-scripts', 'ajaxObj', array('ajax_url' => admin_url('admin-ajax.php')));
    }
    
    function callback_ajax_location() {
        if (!isset($_GET['val']) || !isset($_GET['type']))
            return FALSE;

        if ($_GET['type'] == 'province') {
            $result = $this->get_location_where($_GET['val'], 'districts', 'districtid', 'name', 'provinceid');
            echo json_encode($result);
            exit();
        } else if ($_GET['type'] == 'district') {
            $result = $this->get_location_where($_GET['val'], 'wards', 'wardid', 'name', 'districtid');
            echo json_encode($result);
            exit();
        }
    }
    
    function get_location_where($id = null, $tblName, $col1, $col2, $where = null) {
        global $wpdb;
        $tbl = $wpdb->prefix . $tblName;
        if ($where == null) {
            $results = $wpdb->get_results("SELECT $col1 AS value, $col2 AS name FROM $tbl");
            return $results;
        }
        $results = $wpdb->get_results("SELECT $col1 AS value, $col2 AS name FROM $tbl WHERE $where = $id ");
        return $results;
    }
    
    function registration_form() {
        global $wpdb;
        $tbl = $wpdb->prefix . 'cities';
        $cities = $wpdb->get_results("SELECT provinceid AS value, name AS name FROM $tbl");
        ob_start();
        include 'html.php';
        $output = ob_get_clean();
        echo $output;
    }
    
    function vu_validate_registration($fields) {

        $errors = new WP_Error();

        if (empty($fields['fullname'])) {
            $errors->add('field', 'Vui lòng nhập đầy đủ họ tên');
        }

        if (!is_email($fields['email'])) {
            $errors->add('email_invalid', 'Email không hợp lệ');
        }

        if (email_exists($fields['email'])) {
            $errors->add('email', 'Email này đã được đăng ký');
        }

        if (strlen($fields['password']) < 6) {
            $errors->add('password', 'Password phải có ít nhất 6 ký tự');
        }

        if ($fields['password'] != $fields['confirmPassword']) {
            $errors->add('confirmPassword', 'Mật khẩu xác nhận không trùng khớp');
        }

        if (!is_numeric($fields['province'])) {
            $errors->add('province', 'Bạn chưa chọn tỉnh/thành phố');
        }

        if (!is_numeric($fields['district'])) {
            $errors->add('district', 'Bạn chưa chọn quận/huyện');
        }

        if (!is_numeric($fields['wards'])) {
            $errors->add('wards', 'Bạn chưa chọn phường/xã');
        }

        if (!is_numeric($fields['phone']) && (strlen($fields['phone']) > 11 || strlen($fields['phone']) < 10)) {
            $errors->add('phone', 'Số điện thoại không hợp lệ');
        }

        // show errors
        if (is_wp_error($errors)) {
            echo '<ul class="list-errors">';
            foreach ($errors->get_error_messages() as $error) {
                echo '<li>' . $error . '</li>';
            }
            echo '</ul>';
        }

        // insert user
        if (1 > count($errors->get_error_messages())) {
            $this->vu_registration_complete($fields);
            wp_redirect(get_site_url());
        }
    }
    
    function vu_registration_complete($fields) {
        $user_data = array(
            'user_login' => $fields['email'],
            'user_email' => $fields['email'],
            'user_pass' => $fields['password'],
            'nickname' => $fields['fullname'],
            'role' => 'subscriber'
        );

        $user_id = wp_insert_user($user_data);
        $meta_value = array(
            'address' => $fields['address'],
            'phone' => $fields['phone'],
            'province' => $fields['province'],
            'district' => $fields['district'],
            'ward' => $fields['wards']
        );
        update_user_meta($user_id, 'member_custom_fields', $meta_value);
    }
    
    function display_user_meta($user) {
        $user_meta = get_user_meta($user->ID, 'member_custom_fields'); // this variable will used in user-meta.php
        $provinceCurrent = $user_meta[0]['province'];
        $districtCurrent = $user_meta[0]['district'];
        $wardCurrent = $user_meta[0]['ward'];

        $cities     = $this->get_location_where(null, 'cities', 'provinceid', 'name', null);
        $districts  = $this->get_location_where($provinceCurrent, 'districts', 'districtid', 'name', 'provinceid');
        $wards      = $this->get_location_where($districtCurrent, 'wards', 'wardid', 'name', 'districtid');

        require_once 'user-meta.php';
    }
    
    function update_extra_profile_fields($user_id) {
        $meta_value = array(
            'address' => $_POST['address'],
            'phone' => $_POST['phone'],
            'province' => $_POST['province'],
            'district' => $_POST['district'],
            'ward' => $_POST['wards']
        );
        update_user_meta($user_id, 'member_custom_fields', $meta_value);
    }
    
    function registration_shortcode() {
        $userObj = wp_get_current_user();
        if ($userObj->ID)
            wp_redirect(get_option('siteurl'));

        if (isset($_POST['submit'])) {
            $this->vu_validate_registration($_POST);
        }
        $this->registration_form();
    }

}
