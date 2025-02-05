<?php
if (!defined('ABSPATH')) {
    exit;
}
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class AdminUserSubMenu { 

 	// Super admin WP_List_Table object
	public $super_admin_obj;

	// admin WP_List_Table object
	public $admin_obj;

 	// employee WP_List_Table object
	public $employee_obj;
	
    /**
     * Autoload method
     * @return void
     */
    public function __construct() {
    	add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
        add_action( 'admin_menu', array($this, 'register_sub_menu') );
    }
 
    /**
     * Register submenu
     * @return void
     */
    public function register_sub_menu() {

    	$super_adminhook = add_submenu_page( 
            'users.php', 
            __('Manage Super Admin', 'ohs'), 
            __('Manage Super Admin', 'ohs'), 
            'manage_options', 
            'super_admin-page', 
            array($this, 'super_admin_page_callback')
        );
        add_action( "load-$super_adminhook", [ $this, 'super_admin_screen_option' ] );


        $adminhook = add_submenu_page( 
            'users.php', 
            __('Manage Admin', 'ohs'), 
            __('Manage Admin', 'ohs'), 
            'manage_options', 
            'admin-page', 
            array($this, 'admin_page_callback')
        );
        add_action( "load-$adminhook", [ $this, 'admin_screen_option' ] );

    	$employeehook = add_submenu_page( 
            'users.php', 
            __('Manage Employee', 'ohs'), 
            __('Manage Employees', 'ohs'), 
            'manage_options', 
            'employee-page', 
            array($this, 'employee_page_callback')
        );
        add_action( "load-$employeehook", [ $this, 'employee_screen_option' ] );               

    }
 
    /**
     * Render submenu
     * @return void
     */
    public function super_admin_page_callback() {    	
		?>
		<div class="wrap">
	        <h2><?php esc_html_e('Manage Super Admin', 'ohs'); ?></h2>
	        <div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="get">
								<?php 
								$this->super_admin_obj->prepare_items();
								$this->super_admin_obj->search_box('Search', 'search');
								$this->super_admin_obj->display(); ?>
								<input type="hidden" name="page" value="super_admin-page">
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
       </div>
       <?php 
    }

    /**
     * Render submenu
     * @return void
     */
    public function admin_page_callback() {    	
		?>
		<div class="wrap">
	        <h2><?php esc_html_e('Manage Admin', 'ohs'); ?></h2>
	        <div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="get">
								<?php 
								$this->admin_obj->prepare_items();
								$this->admin_obj->search_box('Search', 'search');
								$this->admin_obj->display(); ?>
								<input type="hidden" name="page" value="admin-page">
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
       </div>
       <?php 
    }

    public function employee_page_callback() {    	
		?>
		<div class="wrap">
        <h2><?php esc_html_e('Manage Employees', 'ohs'); ?></h2>
        <div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="get">
								<?php 
								$this->employee_obj->prepare_items();
								$this->employee_obj->search_box('Search', 'search');
								$this->employee_obj->display(); ?>
								<input type="hidden" name="page" value="employee-page">
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
       </div>
       <?php 
    }

   
    /**
	 * Screen options
	 */
    public function super_admin_screen_option() {

		$option = 'per_page';
		$args   = [
			'label'   => __('Super Admin', 'ohs'),
			'default' => 5,
			'option'  => 'super_admin_per_page'
		];

		add_screen_option( $option, $args );

		$this->super_admin_obj = new Super_Admin_List();
	}

	public function admin_screen_option() {

		$option = 'per_page';
		$args   = [
			'label'   => __('Admin', 'ohs'),
			'default' => 5,
			'option'  => 'admin_per_page'
		];

		add_screen_option( $option, $args );

		$this->admin_obj = new Admin_List();
	}

	public function employee_screen_option() {

		$option = 'per_page';
		$args   = [
			'label'   => __('Employee', 'ohs'),
			'default' => 5,
			'option'  => 'employee_per_page'
		];

		add_screen_option( $option, $args );

		$this->employee_obj = new Employee_List();
	}

	
}

//===================Start=======Super Admin Management
class Super_Admin_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Super Admin', 'ohs' ), //singular name of the listed records
			'plural'   => __( 'Super Admins', 'ohs' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );

	}


	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_super_admins( $per_page = 5, $page_number = 1 ) {

		global $wpdb;		

		$sql = "SELECT t1.* FROM {$wpdb->prefix}users as t1 INNER JOIN {$wpdb->prefix}usermeta as t2 ON ( t1.ID = t2.user_id ) WHERE 1=1 AND t2.meta_key = '{$wpdb->prefix}capabilities' AND t2.meta_value LIKE '%administrator%' ";

		if( ! empty( $_REQUEST['s'] ) ){
            $search = esc_sql( sanitize_text_field(wp_unslash($_REQUEST['s'])) );
            $sql .= " AND ( t1.user_email LIKE '%{$search}%' OR t1.user_login LIKE '%{$search}%')";
        }

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( sanitize_text_field(wp_unslash($_REQUEST['orderby'])) );
			$sql .= ! empty( sanitize_text_field(wp_unslash($_REQUEST['order'])) ) ? ' ' . esc_sql( sanitize_text_field(wp_unslash($_REQUEST['order'])) ) : ' ASC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		//echo $sql;
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}


	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_admin( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}users",
			[ 'ID' => $id ],
			[ '%d' ]
		);
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT count(t1.ID) as total FROM {$wpdb->prefix}users as t1 INNER JOIN {$wpdb->prefix}usermeta as t2 ON ( t1.ID = t2.user_id ) WHERE 1=1 AND t2.meta_key = '{$wpdb->prefix}capabilities' AND t2.meta_value LIKE '%administrator%' ";
		if( ! empty( $_REQUEST['s'] ) ){
            $search = esc_sql( sanitize_text_field(wp_unslash($_REQUEST['s'])) );
            $sql .= " AND ( t1.user_email LIKE '%{$search}%' OR t1.user_login LIKE '%{$search}%')";
        }
		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		esc_html_e( 'No super admin avaliable.', 'ohs' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'user_login':
				return $item[$column_name];
			case 'user_email':
				return $item[$column_name];
			case 'full_name':
				$full_name =  get_user_meta($item['ID'], 'first_name', true).' '.get_user_meta($item['ID'], 'last_name', true);	
				if(trim($full_name)==""){
					return '—';
				}
				return $full_name;
			case 'is_blocked':
				$is_blocked = strtolower(get_user_meta($item['ID'], 'is_blocked', true));
				return $is_blocked == 'yes' ? 'Yes' : 'No'; 
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
		);
	}

	function column_user_login($item)
    {
    	$user_obj = get_userdata( $item['ID'] );
    	$delete_nonce = wp_create_nonce( 'sp_delete_super_admin' );
    	//$swith_url = user_switching::switch_to_url( $user_obj );
    	if(!empty($_REQUEST['page'])){
	    	$page = sanitize_text_field(wp_unslash($_REQUEST['page']));
	        $actions = array(
	            'edit' => sprintf('<a href="'.admin_url("user-edit.php").'?user_id=%d&wp_http_referer=%s">'.__('Edit', 'ohs').'</a>', $item['ID'], admin_url("user-edit.php").'?page=super_admin-page' ),
	            'delete' => sprintf( '<a href="?page=%s&action=%s&super_admin=%s&_wpnonce=%s">Delete</a>', esc_attr( $page ), 'delete', absint( $item['ID'] ), $delete_nonce ).'</a>',
	        );
	    }
        
        return sprintf(
            '%1$s %2$s',
            $item['user_login'], 
            $this->row_actions($actions)
        );
    }
	

	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'cb'      => '<input type="checkbox" />',
			'user_login'    => __( 'Username', 'ohs' ),
			'full_name'    => __( 'Name', 'ohs' ),
			'user_email' => __( 'Email', 'ohs' ),
			'is_blocked'    => __( 'Blocked', 'ohs' )
		];

		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'user_email' => array( 'user_email', true ),
			'user_login' => array( 'user_login', true )
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'super_admin_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_super_admins( $per_page, $current_page );
	}

	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {
			if(!empty($_REQUEST['_wpnonce'])){
				// In our file that handles the request, verify the nonce.
				$nonce = esc_attr( sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])) );

				if ( ! wp_verify_nonce( $nonce, 'sp_delete_super_admin' ) ) {
					die( 'Go get a life script kiddies' );
				}
				else {
					if(!empty($_GET['super_admin'])){
						self::delete_super_admin( absint( $_GET['super_admin'] ) );

		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
		                wp_redirect( admin_url('users.php?page=super_admin-page') );
						exit;
					}
				}
			}
		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {
			if(!empty($_REQUEST['bulk-delete'])){
				$delete_ids = esc_sql( sanitize_text_field(wp_unslash($_POST['bulk-delete'])) );

				// loop over the array of record IDs and delete them
				foreach ( $delete_ids as $id ) {
					self::delete_super_admin( $id );

				}
			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        wp_redirect( admin_url('users.php?page=super_admin-page') );
			exit;
		}
	}

}
//====================End=======Super Admin Management========================

//===================Start=======Admin Management========================
class Admin_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Admin', 'ohs' ), //singular name of the listed records
			'plural'   => __( 'Admins', 'ohs' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );

	}


	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_admins( $per_page = 10, $page_number = 1 ) {

		global $wpdb;		

		$sql = "SELECT t1.* FROM {$wpdb->prefix}users as t1 INNER JOIN {$wpdb->prefix}usermeta as t2 ON ( t1.ID = t2.user_id ) WHERE 1=1 AND t2.meta_key = '{$wpdb->prefix}capabilities' AND t2.meta_value LIKE '%frontadmin%' ";

		if( ! empty( $_REQUEST['s'] ) ){
            $search = esc_sql( sanitize_text_field(wp_unslash($_REQUEST['s'])) );
            $sql .= " AND ( t1.user_email LIKE '%{$search}%' OR t1.user_login LIKE '%{$search}%')";
        }

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( sanitize_text_field(wp_unslash($_REQUEST['orderby'])) );
			$sql .= ! empty( sanitize_text_field(wp_unslash($_REQUEST['order'])) ) ? ' ' . esc_sql( sanitize_text_field(wp_unslash($_REQUEST['order'])) ) : ' ASC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		//echo $sql;
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}


	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_admin( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}users",
			[ 'ID' => $id ],
			[ '%d' ]
		);
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT count(t1.ID) as total FROM {$wpdb->prefix}users as t1 INNER JOIN {$wpdb->prefix}usermeta as t2 ON ( t1.ID = t2.user_id ) WHERE 1=1 AND t2.meta_key = '{$wpdb->prefix}capabilities' AND t2.meta_value LIKE '%frontadmin%' ";
		if( ! empty( $_REQUEST['s'] ) ){
            $search = esc_sql( sanitize_text_field(wp_unslash($_REQUEST['s'])) );
            $sql .= " AND ( t1.user_email LIKE '%{$search}%' OR t1.user_login LIKE '%{$search}%')";
        }
		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		esc_html_e( 'No admin avaliable.', 'ohs' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'user_login':
				return $item[$column_name];
			case 'user_email':
				return $item[$column_name];
			case 'full_name':
				$full_name =  get_user_meta($item['ID'], 'first_name', true).' '.get_user_meta($item['ID'], 'last_name', true);	
				if(trim($full_name)==""){
					return '—';
				}
				return $full_name;
			case 'is_blocked':
				$is_blocked = strtolower(get_user_meta($item['ID'], 'is_blocked', true));
				return $is_blocked == 'yes' ? 'Yes' : 'No'; 
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
		);
	}

	function column_user_login($item)
    {
    	$user_obj = get_userdata( $item['ID'] );
    	$delete_nonce = wp_create_nonce( 'sp_delete_admin' );
    	//$swith_url = user_switching::switch_to_url( $user_obj );
    	if(!empty($_REQUEST['page'])){
	    	$page = sanitize_text_field(wp_unslash($_REQUEST['page']));
	        $actions = array(
	            'edit' => sprintf('<a href="'.admin_url("user-edit.php").'?user_id=%d&wp_http_referer=%s">'.__('Edit', 'ohs').'</a>', $item['ID'], admin_url("user-edit.php").'?page=admin-page' ),
	            'delete' => sprintf( '<a href="?page=%s&action=%s&admin=%s&_wpnonce=%s">Delete</a>', esc_attr( $page ), 'delete', absint( $item['ID'] ), $delete_nonce ).'</a>',
	        );
	    }
        
        return sprintf(
            '%1$s %2$s',
            $item['user_login'], 
            $this->row_actions($actions)
        );
    }
	

	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'cb'      => '<input type="checkbox" />',
			'user_login'    => __( 'Username', 'ohs' ),
			'full_name'    => __( 'Name', 'ohs' ),
			'user_email' => __( 'Email', 'ohs' ),
			'is_blocked'    => __( 'Blocked', 'ohs' )
		];

		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'user_email' => array( 'user_email', true ),
			'user_login' => array( 'user_login', true )
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'admin_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_admins( $per_page, $current_page );
	}

	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {
			if(!empty($_REQUEST['_wpnonce'])){
				// In our file that handles the request, verify the nonce.
				$nonce = esc_attr( sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])) );

				if ( ! wp_verify_nonce( $nonce, 'sp_delete_admin' ) ) {
					die( 'Go get a life script kiddies' );
				}
				else {
					if(!empty($_GET['admin'])){
						self::delete_admin( absint( $_GET['admin'] ) );

		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
		                wp_redirect( admin_url('users.php?page=admin-page') );
						exit;
					}
				}
			}
		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {
			if(!empty($_REQUEST['bulk-delete'])){
				$delete_ids = esc_sql( sanitize_text_field(wp_unslash($_POST['bulk-delete'])) );

				// loop over the array of record IDs and delete them
				foreach ( $delete_ids as $id ) {
					self::delete_admin( $id );

				}
			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        wp_redirect( admin_url('users.php?page=admin-page') );
			exit;
		}
	}

}
//=======End=======Admin Management============

//========Start=======Employee Management======
class Employee_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Employee', 'ohs' ), //singular name of the listed records
			'plural'   => __( 'Employees', 'ohs' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );

	}


	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_employee( $per_page = 10, $page_number = 1 ) {

		global $wpdb;		

		$sql = "SELECT t1.* FROM {$wpdb->prefix}users as t1 INNER JOIN {$wpdb->prefix}usermeta as t2 ON ( t1.ID = t2.user_id ) WHERE 1=1 AND t2.meta_key = '{$wpdb->prefix}capabilities' AND t2.meta_value LIKE '%employee%' ";

		if( ! empty( $_REQUEST['s'] ) ){
            $search = esc_sql( sanitize_text_field(wp_unslash($_REQUEST['s'])) );
            $sql .= " AND ( t1.user_email LIKE '%{$search}%' OR t1.user_login LIKE '%{$search}%')";
        }

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( sanitize_text_field(wp_unslash($_REQUEST['orderby'] )));
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( sanitize_text_field(wp_unslash($_REQUEST['order'] ))) : ' ASC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		//echo $sql;
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}


	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_employee( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}users",
			[ 'ID' => $id ],
			[ '%d' ]
		);
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT count(t1.ID) as total FROM {$wpdb->prefix}users as t1 INNER JOIN {$wpdb->prefix}usermeta as t2 ON ( t1.ID = t2.user_id ) WHERE 1=1 AND t2.meta_key = '{$wpdb->prefix}capabilities' AND t2.meta_value LIKE '%employee%' ";

		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		esc_html_e( 'No employee avaliable.', 'ohs' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'user_login':
				return $item[$column_name];
			case 'user_email':
				return $item[$column_name];
			case 'full_name':
				$full_name =  get_user_meta($item['ID'], 'first_name', true).' '.get_user_meta($item['ID'], 'last_name', true);	
				if(trim($full_name)==""){
					return '—';
				}
				return $full_name;			 
			case 'is_blocked':
				$is_blocked = strtolower(get_user_meta($item['ID'], 'is_blocked', true));
				return $is_blocked == 'yes' ? 'Yes' : 'No'; 
			case 'user_registered':				
				return date_i18n("jS F, Y h:i A", strtotime($item['user_registered']));
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
		);
	}

	function column_user_login($item)
    {
    	$user_obj = get_userdata( $item['ID'] );
    	$delete_nonce = wp_create_nonce( 'sp_delete_employee' );
    	//$swith_url = user_switching::switch_to_url( $user_obj );
    	if(!empty($_REQUEST['page'])){
	    	$page = sanitize_text_field(wp_unslash($_REQUEST['page']));

	        $actions = array(
	            'edit' => sprintf('<a href="'.admin_url("user-edit.php").'?user_id=%d&wp_http_referer=%s">'.__('Edit', 'ohs').'</a>', $item['ID'], admin_url("user-edit.php").'?page=employee-page' ),
	            'delete' => sprintf( '<a href="?page=%s&action=%s&employee=%s&_wpnonce=%s">Delete</a>', esc_attr( $page ), 'delete', absint( $item['ID'] ), $delete_nonce ).'</a>',
	        );
	    }

        
        return sprintf(
            '%1$s %2$s',
            $item['user_login'], 
            $this->row_actions($actions)
        );
    }

	


	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'cb'      => '<input type="checkbox" />',
			'user_login'    => __( 'Username', 'ohs' ),
			'full_name'    => __( 'Name', 'ohs' ),
			'user_email' => __( 'Email', 'ohs' ), 
			'is_blocked'    => __( 'Blocked', 'ohs' ),
			'user_registered'    => __( 'Reg. Date', 'ohs' )
		];

		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'user_email' => array( 'user_email', true ),
			'user_login' => array( 'user_login', true ),
			'user_registered' => array( 'user_registered', true )
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => __('Delete', 'ohs')
		];

		return $actions;
	}


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'employee_per_page', 20 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_employee( $per_page, $current_page );
	}

	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {
			if(!empty($_REQUEST['_wpnonce'])){
				// In our file that handles the request, verify the nonce.
				$nonce = esc_attr( sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])) );

				if ( ! wp_verify_nonce( $nonce, 'sp_delete_employee' ) ) {
					die( 'Go get a life script kiddies' );
				}
				else {
					if(!empty($_GET['employee'])){
						self::delete_employee( absint( $_GET['employee'] ) );

		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
		                wp_redirect( admin_url('users.php?page=employee-page') );
						exit;
					}
				}
			}

		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {
			if(!empty($_POST['bulk-delete'])){
				$delete_ids = esc_sql( sanitize_text_field(wp_unslash($_POST['bulk-delete'])) );

				// loop over the array of record IDs and delete them
				foreach ( $delete_ids as $id ) {
					self::delete_employee( $id );

				}
			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        wp_redirect( admin_url('users.php?page=employee-page') );
			exit;
		}
	}

}
//=======================End=======Employee Management

$admin_page = new AdminUserSubMenu();