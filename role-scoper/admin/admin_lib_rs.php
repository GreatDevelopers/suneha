<?php
if( basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']) )
	die();
	
/**
 * ScoperAdminLib PHP class for the WordPress plugin Role Scoper
 * scoper_admin_lib.php
 * 
 * @author 		Kevin Behrens
 * @copyright 	Copyright 2011
 * 
 * Used by Role Scoper Plugin as a container for statically-called functions
 * These function can be used during activation, deactivation, or other 
 * scenarios where no Scoper or WP_Scoped_User object exists
 *
 */
 
add_filter('wp_dropdown_pages', array('ScoperAdminLib', 'flt_dropdown_pages') );

if ( strpos( $_SERVER['REQUEST_URI'], 'nggallery' ) ) // Role Scoping for NGG calls ScoperAdminUI::dropdown_pages
	require_once( dirname(__FILE__).'/admin_ui_lib_rs.php' );

class ScoperAdminLib {
	// filter page dropdown contents for Page Parent controls; leave others alone
	function flt_dropdown_pages($orig_options_html) {
		if ( ! strpos( $orig_options_html, 'parent_id' ) && ! strpos( $orig_options_html, 'post_parent' ) )
			return $orig_options_html;

		if ( 0 === strpos( $GLOBALS['pagenow'], 'options-' ) )
			return $orig_options_html;

		require_once( SCOPER_ABSPATH . '/hardway/hardway-parent_rs.php');
		return ScoperHardwayParent::flt_dropdown_pages($orig_options_html);
	}
	
	function get_blogrole_users($role_name, $role_type, $cols = COLS_ALL_RS) {
		global $wpdb;
		
		if ( COL_ID_RS == $cols )
			$results = scoper_get_col("SELECT user_id FROM $wpdb->user2role2object_rs WHERE scope = 'blog' AND role_type = '$role_type' AND role_name = '$role_name'");
		else {
			switch( $cols ) {
				case COLS_ID_DISPLAYNAME_RS : 
					$qcols = "u.ID, u.display_name";
					break;
				case COLS_ID_NAME_RS : 
					$qcols = "u.ID, u.user_login AS display_name";	// calling code assumes display_name property for user or group object
					break;
				default:
					$qcols = "u.*";
			}
		
			$query = "SELECT $qcols FROM $wpdb->users AS u "
					. " INNER JOIN $wpdb->user2role2object_rs AS r ON r.user_id = u.ID"
					. " WHERE r.scope = 'blog' AND r.role_type = '$role_type' AND r.role_name = '$role_name'";
					
			$results = scoper_get_results($query);
		}
		
		return $results;
	}
	
	function get_group_members($group_id, $cols = COLS_ALL_RS, $maybe_metagroup = false, $args = array() ) {
		global $wpdb;
		
		// If $group_id is an array of group objects, extract IDs into a separate array 
		if ( is_array($group_id) ) {
			$first = current($group_id);
			
			if ( is_object($first) ) {
				$actual_ids = array();
				
				foreach( $group_id as $group )
					$actual_ids []= $group->ID;
					
				$group_id = $actual_ids;
			}
		}
		
		if ( empty($args['status']) ) {
			$status = 'active';
		} elseif ( 'any' == $args['status'] ) {
			$args['no_cache'] = true;
			$status = '';
		} else {
			$args['no_cache'] = true;
			$status = $args['status'];
		}

		if ( ! is_array($group_id) && empty($args['no_cache']) ) {
			$cache_flag = 'group_members';
			$cache_id = $group_id;
			$cache = wpp_cache_get($cache_id, $cache_flag);
			$ckey = md5( serialize($cols) . $maybe_metagroup );

			if ( isset($cache[$ckey]) )
				return $cache[$ckey];
		}
		
		$group_in = "'" . implode("', '", (array) $group_id) . "'";
		
		$status_clause = ( $status ) ? "AND status = '$status'" : '';
		
		if ( COL_ID_RS == $cols ) {
			$query = "SELECT gu.$wpdb->user2group_uid_col 
	              FROM $wpdb->user2group_rs gu
	              WHERE gu.$wpdb->user2group_gid_col IN ($group_in) $status_clause";
			
	        if ( ! $results = scoper_get_col( $query ) )
	        	$results = array();
	        
		} else {
			switch( $cols ) {
				case COLS_ID_DISPLAYNAME_RS : 
					$qcols = "u.ID, u.display_name";
					break;
				case COLS_ID_NAME_RS : 
					$qcols = "u.ID, u.user_login AS display_name";	// calling code assumes display_name property for user or group object
					break;
				default:
					$qcols = "u.*";
			}

			$query = "SELECT $qcols FROM $wpdb->users AS u"
					. " INNER JOIN $wpdb->user2group_rs AS gu ON gu.$wpdb->user2group_uid_col = u.ID $status_clause "
					. " AND gu.{$wpdb->user2group_gid_col} IN ($group_in) ORDER BY u.display_name";

			$results = scoper_get_results( $query );
		}
		
		if ( $maybe_metagroup && ( is_array($group_id) || ! $results ) ) {
			$meta_ids = scoper_get_col("SELECT group_meta_id FROM $wpdb->groups_rs WHERE $wpdb->groups_id_col IN ($group_in)");
			foreach( $meta_ids as $meta_id ) {
				if ( 0 === strpos($meta_id, 'wp_role_') ) {
					$role_name = substr($meta_id, 8);
					if ( $_results = ScoperAdminLib::get_blogrole_users($role_name, 'wp', $cols) )
						$results = array_merge( $results, $_results );
				}
			}
		}
		
		if ( ! is_array($group_id) && empty($args['no_cache']) ) {
			$cache[ $ckey ] = $results;
			wpp_cache_set($cache_id, $cache, $cache_flag);
		}

		return $results;
	}
	
	
	/**
	 * Creates a new Group
	 *
	 * @param string $name - Name of the group
	 * @param string $description - Group description (optional)
	 * @return boolean True on successful creation
	 **/
	function create_group ($name, $description = ''){
		require_once( dirname(__FILE__).'/groups-support.php' );
		return UserGroups_tp::CreateGroup($name, $description);
	}
	
	
	/**
	 * Gets a group with a given identifier
	 *
	 * @param int $id - Group Identifier
	 * @return Object An object with the group details
	 **/
	function get_group($group_id) {
		global $wpdb;

		$query = "SELECT $wpdb->groups_id_col AS ID, $wpdb->groups_name_col AS display_name, $wpdb->groups_descript_col as descript, $wpdb->groups_meta_id_col as meta_id"
				. " FROM $wpdb->groups_rs WHERE $wpdb->groups_id_col='$group_id'";

		$results = scoper_get_results( $query );
		if(isset($results) && isset($results[0]))
		return $results[0];
	}
	
	
	/**
	 * Gets a group with a given name
	 *
	 * @param string $name - Group Name
	 * @return Object An object with the group details
	 **/
	function get_group_by_name($name) {
		global $wpdb;

		$query = "SELECT $wpdb->groups_id_col AS ID, $wpdb->groups_name_col AS display_name, $wpdb->groups_descript_col as descript "
				. " FROM $wpdb->groups_rs WHERE $wpdb->groups_name_col='$name'";

		$result = scoper_get_row( $query );
		return $result;
	}
	
	
    // (adapted from WP-Group-Restriction plugin)
    // returns all groups, or all groups the current user can manage
	function get_all_groups( $filtering = UNFILTERED_RS, $cols = COLS_ALL_RS, $args = array() ) {
		$defaults = array ( 'include_norole_groups' => false, 'reqd_caps' => 'manage_groups', 'where' => '' );
		$args = array_merge( $defaults, (array) $args );
		extract($args);

		if ( $filtering && is_user_administrator_rs() )
			$filtering = 0;
			
		if ( $filtering ) {
			$cache_flag = 'usergroups';
			
			global $current_rs_user;
			$cache = $current_rs_user->cache_get($cache_flag);
		
		} else {
			$cache_flag = 'all_usergroups';
			$cache_id = 'all';
			$cache = wpp_cache_get($cache_id, $cache_flag);
		}

		$ckey = md5( $cols . $reqd_caps );
		
		if ( ! isset($cache[$ckey]) ) {
			global $wpdb;
			
			if ( $filtering && ! is_user_administrator_rs() && ! cr_user_can($reqd_caps, 0, 0, array( 'skip_any_object_check' => true, 'skip_any_term_check' => true, 'skip_id_generation' => true ) ) ) {
				$duration_clause = scoper_get_duration_clause();

				global $scoper;
				$role_handles = $scoper->role_defs->qualify_roles( $reqd_caps );
				$role_names = array();
				
				foreach ( array_keys($role_handles) as $role_handle ) {
					$role = scoper_explode_role_handle( $role_handle );
					$role_names []= $role->role_name;	
				}

				$role_clause = "AND uro.role_name IN ('" . implode( "','", $role_names ) . "')";
				
				$join = "INNER JOIN $wpdb->user2role2object_rs AS uro"
						. " ON uro.obj_or_term_id = {$wpdb->groups_rs}.{$wpdb->groups_id_col}"
						. " AND uro.src_or_tx_name = 'group' AND uro.scope = 'object' $role_clause $duration_clause";
	
				$_where = "WHERE uro.user_id = $current_rs_user->ID";
			} else {
				$join = '';
				$_where = 'WHERE 1=1 ';
			}
			
			// append supplemental where clause, if any was passed in
			$_where .= $where;
			
			if ( COL_ID_RS == $cols )
				$query = "SELECT DISTINCT $wpdb->groups_id_col FROM $wpdb->groups_rs $join $_where";
			else
				$query = "SELECT DISTINCT $wpdb->groups_id_col AS ID, $wpdb->groups_name_col AS display_name, $wpdb->groups_descript_col as descript, $wpdb->groups_meta_id_col as meta_id"
						. " FROM $wpdb->groups_rs $join $_where ORDER BY $wpdb->groups_name_col";
					
			if ( COL_ID_RS == $cols )
				$cache[$ckey] = scoper_get_col($query);
			else
				$cache[$ckey] = scoper_get_results($query);
		}

		if ( $filtering )
			$current_rs_user->cache_set($cache, $cache_flag);
		else
			wpp_cache_set($cache_id, $cache, $cache_flag);
		
		
		if ( COLS_ALL_RS == $cols ) {
			// strip out anon metagroup if we're not using it (have to do this after cache storage / retrieval)
			if ( ! defined( 'SCOPER_ANON_METAGROUP' ) ) {
				foreach ( array_keys($cache[$ckey]) as $key ) {
					if ( 'wp_anon' == $cache[$ckey][$key]->meta_id ) {
						unset( $cache[$ckey][$key] );
						break;	
					}
				}	
			}
			
			// strip out groups that don't use roles, unless arg asked for them
			if ( ! $include_norole_groups ) {
				foreach ( array_keys($cache[$ckey]) as $key ) {
					if ( strpos( $cache[$ckey][$key]->meta_id, '_nr_' ) ) {
						unset( $cache[$ckey][$key] );
					}
				}	
			}
		}

		if ( ! $cache[$ckey] )
			$cache[$ckey] = array();
			
		return $cache[$ckey];
	}
	
	function get_metagroup_name( $meta_id, $default_name = '' ) {
		global $wp_roles;
		
		if ( 0 === strpos( $meta_id, 'wp_role_' ) ) {
			$role_name = substr($meta_id, strlen('wp_role_') );
			$role_display_name = isset( $wp_roles->role_names[$role_name] ) ? __($wp_roles->role_names[$role_name]) : $role_name;
	
			//return sprintf( _ x('[WP %s]', 'Readers/Contributors/Editors, etc.', 'scoper'), $role_display_name );
			return sprintf( __('[WP %s]', 'scoper'), $role_display_name );
		
		} elseif ( 'wp_anon' == $meta_id ) {
			return	__('[Anonymous]', 'scoper');
		} else {
			return $default_name;
		} 
	}
	
	function get_metagroup_descript( $meta_id, $default_descript = '' ) {
		if ( 0 === strpos( $meta_id, 'wp_role_' ) ) {
			$role_display_name = ScoperAdminLib::get_metagroup_name( $meta_id );
			$role_display_name = str_replace('[WP ', '', $role_display_name);
			$role_display_name = str_replace(']', '', $role_display_name);
			return sprintf( __( 'All users with the WordPress role of %s', 'scoper'), $role_display_name );
		
		} elseif ( 'wp_anon' == $meta_id ) {
			return __('Anonymous users (not logged in)', 'scoper');
		} else {
			return $default_descript;	
		}
	}
		
	function flush_user_cache( $user_ids ) {
		$user_ids = (array) $user_ids;
		
		scoper_flush_results_cache( ROLE_BASIS_USER, $user_ids );
		scoper_flush_roles_cache( OBJECT_SCOPE_RS, ROLE_BASIS_USER, $user_ids);
		scoper_flush_roles_cache( TERM_SCOPE_RS, ROLE_BASIS_USER, $user_ids);
		scoper_flush_roles_cache( BLOG_SCOPE_RS, ROLE_BASIS_USER, $user_ids);	
	}
	
	function flush_groups_cache_for_user( $user_ids ) {
		wpp_cache_flush();
		
		/* work around reported omission in the selective cache flush: http://agapetry.net/forum/role-scoper/issue-with-role-cache-and-add_group_user/page-1/post-5530/#p5530
		$user_ids = (array) $user_ids;
		
		wpp_cache_flush_group( 'group_members' );
		//wpp_cache_flush_group( 'group_membership_for_user' );
		
		foreach ( $user_ids as $user_id ) {
			//rs_errlog( "calling wpp_cache_delete from user $user_id group_memb" );
			wpp_cache_delete($user_id, 'group_membership_for_user');
		}
	
		scoper_flush_results_cache( ROLE_BASIS_USER_AND_GROUPS, $user_ids );
		scoper_flush_roles_cache( OBJECT_SCOPE_RS, ROLE_BASIS_USER_AND_GROUPS, $user_ids);
		scoper_flush_roles_cache( TERM_SCOPE_RS, ROLE_BASIS_USER_AND_GROUPS, $user_ids);
		scoper_flush_roles_cache( BLOG_SCOPE_RS, ROLE_BASIS_USER_AND_GROUPS, $user_ids);
		*/
	}
	
	/**
	 * Adds a user to a group  (adapted from WP-Group-Restriction plugin)
	 * @param int $groupID - Group Identifier
	 * @param int $userID - Identifier of the User to add
	 **/
	function add_group_user( $group_id, $user_ids, $status = 'active' ){
		global $wpdb;
		
		$user_ids = (array) $user_ids;
			
		foreach( $user_ids as $user_id ) {
			if ( $already_member = $wpdb->get_col( "SELECT $wpdb->user2group_uid_col FROM $wpdb->user2group_rs WHERE $wpdb->user2group_gid_col = '$group_id' AND $wpdb->user2group_uid_col = '$user_id'" ) )
				continue;

			//rs_errlog( "adding user to group $group_id" );
				
			$insert = "INSERT INTO $wpdb->user2group_rs ($wpdb->user2group_gid_col, $wpdb->user2group_uid_col, $wpdb->user2group_status_col)"
					. " VALUES ('$group_id','$user_id','$status');";
					
			scoper_query( $insert );
			
			do_action('add_group_user_rs', $group_id, $user_id, $status);	
		}
		
		ScoperAdminLib::flush_groups_cache_for_user( $user_ids );
	}
	
	/** 
	 * Removes a User from a group (adapted from WP-Group-Restriction plugin)
	 *
	 * @param int $group_id - Group Identifier
	 * @param int $user_id - Identifier of the User to remove
	 **/
	function remove_group_user($group_id, $user_ids) {
		global $wpdb;

		$user_ids = (array) $user_ids;
		
		$id_in = "'" . implode("', '", $user_ids) . "'";
		$delete = "DELETE FROM $wpdb->user2group_rs WHERE $wpdb->user2group_gid_col='$group_id' AND $wpdb->user2group_uid_col IN ($id_in)";
		scoper_query( $delete );

		foreach( $user_ids as $user_id )
			do_action('delete_group_user_rs', $group_id, $user_id);

		ScoperAdminLib::flush_groups_cache_for_user( $user_ids );
	}
	
	/** 
	 * Updates the status of a User-Group relationship
	 *
	 * @param int $group_id - Group Identifier
	 * @param int $user_id - Identifier of the User to remove
	 **/
	function update_group_user( $group_id, $user_ids, $status ) {
		global $wpdb;

		$user_ids = (array) $user_ids;
		
		$id_in = "'" . implode("', '", $user_ids) . "'";
		
		$prev_status = array();
		$qry = "SELECT $wpdb->user2group_uid_col AS user_id, $wpdb->user2group_status_col AS status FROM $wpdb->user2group_rs WHERE $wpdb->user2group_gid_col='$group_id' AND $wpdb->user2group_uid_col IN ($id_in)";
		if ( $results = scoper_get_results( $qry ) ) {
			foreach( $results as $row )
				$prev_status[ $row->user_id ] = $row->status;	
		}
		
		$qry = "UPDATE $wpdb->user2group_rs SET $wpdb->user2group_status_col='$status' WHERE $wpdb->user2group_gid_col='$group_id' AND $wpdb->user2group_uid_col IN ($id_in)";
		scoper_query( $qry );

		foreach( $user_ids as $user_id ) {
			$prev = ( isset( $prev_status[$user_id] ) ) ? $prev_status[$user_id] : '';
			do_action( 'update_group_user_rs', $group_id, $user_id, $status, $prev );
		}
			
		ScoperAdminLib::flush_groups_cache_for_user( $user_ids );
	}
	
	/**
	 * Removes a User from all groups  (adapted from WP-Group-Restriction plugin)
	 *
	 * @param int $user - User Identifier
	 **/
	function delete_user_from_groups($user_id){
		global $wpdb;

		// possible todo: pre-query user groups so we can do_action delete_group_user_rs
		
		$delete = "DELETE FROM $wpdb->user2group_rs WHERE $wpdb->user2group_uid_col='$user_id';";
		scoper_query( $delete );

		ScoperAdminLib::flush_groups_cache_for_user( $user_id );
	}

	function delete_users( $user_ids, $blog_id_arg = 0 ) {
		global $wpdb;
		
		if ( ! $user_ids )
			return;

		$user_ids = (array) $user_ids;
			
		$user_clause = "user_id IN ('" . implode("', '", $user_ids) . "')";
		
		$table_name = ( $blog_id_arg ) ? $wpdb->base_prefix . $blog_id_arg . '_' . 'user2role2object_rs' : $wpdb->user2role2object_rs;
		
		scoper_query("DELETE FROM $table_name WHERE $user_clause");
		
		foreach ( $user_ids as $user_id ) {
			if ( ! MULTISITE || ! scoper_get_site_option( 'mu_sitewide_groups' ) )
			   ScoperAdminLib::delete_user_from_groups($user_id);
			
			ScoperAdminLib::flush_user_cache( $user_id );
		}
	}

	function clear_roles( $scope, $src_or_tx_name, $obj_or_term_id, $args = array() ) {
		$defaults = array ( 'inherited_only' => false, 'clear_propagated' => false );
		$args = array_merge( $defaults, (array) $args );
		extract($args);
		
		global $wpdb;
		
		if ( ! $obj_or_term_id )
			return;
		
		$inherited_clause = ( $inherited_only ) ? "AND inherited_from > 0" : '';

		$qry = "SELECT assignment_id FROM $wpdb->user2role2object_rs WHERE scope = '$scope' AND src_or_tx_name = '$src_or_tx_name' $inherited_clause AND obj_or_term_id = '$obj_or_term_id'";
		$assignment_ids = scoper_get_col( $qry );
		
		if ( $assignment_ids ) {
			$qry = "DELETE FROM $wpdb->user2role2object_rs WHERE assignment_id IN ('" . implode( "', '", $assignment_ids) . "')";
			
			if ( $clear_propagated )
				$qry .= " OR inherited_from IN ('" . implode( "', '", $assignment_ids) . "') ";
			
			scoper_query( $qry );
		}
	}
	
	function clear_restrictions ( $scope, $src_or_tx_name, $obj_or_term_id, $args = array() ) {
		$defaults = array ( 'inherited_only' => false, 'clear_propagated' => false );
		$args = array_merge( $defaults, (array) $args );
		extract($args);	
	
		global $wpdb;

		$inherited_clause = ( $inherited_only ) ? "AND inherited_from > 0" : '';
		
		$qry = "SELECT requirement_id FROM $wpdb->role_scope_rs WHERE topic = '$scope' AND src_or_tx_name = '$src_or_tx_name' $inherited_clause AND obj_or_term_id = '$obj_or_term_id'";
		$req_ids = scoper_get_col( $qry );

		if ( $req_ids ) {
			$qry = "DELETE FROM $wpdb->role_scope_rs WHERE requirement_id IN ('" . implode( "', '", $req_ids) . "')";
			
			if ( $clear_propagated )
				$qry .= " OR inherited_from IN ('" . implode( "', '", $req_ids) . "') ";
				
			scoper_query( $qry );
		}
	}
	
	function any_custom_caps_assigned() {
		global $wpdb;
		
		$got_any = scoper_get_var("SELECT assignment_id FROM $wpdb->user2role2object_rs WHERE scope = 'blog' AND role_type = 'wp_cap' LIMIT 1");
		return ( $got_any );
	}

	// this function is currently RoleManager-specific
	function rename_role($role_name_old, $role_type = 'rs') {
		$role_name_new = $_POST['role-name'];
		if ( ! $role_name_old )
			return;

		global $wpdb;
		scoper_query("UPDATE $wpdb->user2role2object_rs SET role_name = '$role_name_new' WHERE role_type = '$role_type' AND role_name = '$role_name_old'");

		ScoperAdminLib::schedule_role_sync();	// sync_wp_roles() will also flush cache on role rename
	}

	function add_user( $user_id, $role_name = '', $blog_id = '' ) {
		// enroll user in default group(s)
		if ( $default_groups = scoper_get_option( 'default_groups' ) )
			foreach ($default_groups as $group_id)
				ScoperAdminLib::add_group_user($group_id, $user_id);
		
		global $scoper_role_types;
	
		foreach ( $scoper_role_types as $role_type ) {	
			wpp_cache_flush_group("{$role_type}_users_who_can");
			wpp_cache_flush_group("{$role_type}_groups_who_can");
		}
	
		ScoperAdminLib::sync_wproles( $user_id, $role_name, $blog_id );
	}

	function sync_wproles( $user_ids = '', $role_name = '', $blog_id_arg = '' ) {
		require_once( dirname(__FILE__).'/update_rs.php');
		scoper_sync_wproles( $user_ids, $role_name, $blog_id_arg );
	} // end sync_wproles function
	
	// simplifies attaching this function to hook which pass irrelevant argument
	function sync_all_wproles() {
		ScoperAdminLib::sync_wproles();
	} // end sync_wproles function

	function schedule_role_sync() {
		static $done;
		
		if ( ! empty($done) )
			return;	
		
		$done = true;
			
		// Role Manager / Capability Manager don't actually create the role until after the option update we're hooking on, so defer our maintenance operation
		wpp_cache_flush();
		add_action( 'shutdown', array('ScoperAdminLib', 'sync_all_wproles') );
	}

	function agent_ids_from_csv( $csv_id, $role_basis ) {
		static $groups_by_name;
	
		if ( ( ROLE_BASIS_GROUPS == $role_basis ) && ( ! empty($_POST[$csv_id]) ) ) {
			if ( ! isset($groups_by_name) ) {
				$all_groups = ScoperAdminLib::get_all_groups();
				$groups_by_name = array();
				foreach ( $all_groups as $group )
					$groups_by_name[$group->display_name] = $group->ID;
			}
		}

		$agent_ids = array();
		
		if ( ! empty($_POST[$csv_id]) ) {
			$agent_names = explode(",", $_POST[$csv_id]);

			// role assignments for item
			foreach ( $agent_names as $agent_name ) {
				if ( empty($agent_name) ) continue; else $agent_name = trim($agent_name);

				if ( ROLE_BASIS_GROUPS == $role_basis ) {
					if ( isset($groups_by_name[$agent_name]) )
						$agent_ids [] = $groups_by_name[$agent_name];
					elseif ( intval($agent_name) && in_array( $agent_name, $groups_by_name ) )
						$agent_ids [] = $agent_name;
				} else {
					require_once( dirname(__FILE__).'/user_lib_rs.php' );
					
					if ( $user = awp_get_user_by_name( $agent_name ) )
						$agent_ids [] = $user->ID;
					elseif ( intval($agent_name) && awp_get_user_by_id( $agent_name ) )
						$agent_ids [] = $agent_name;
				}
			}
		}
		
		return $agent_ids;
	}
	
	function dashboard_dismiss_msg() {
		$dismissals = get_option( 'scoper_dismissals' );
		if ( ! is_array( $dismissals ) )
			$dismissals = array();

		$msg_id = ( isset( $_REQUEST['msg_id'] ) ) ? $_REQUEST['msg_id'] : 'pp_offer';
		$dismissals[$msg_id] = true;
		update_option( 'scoper_dismissals', $dismissals );
	}
} // end ScoperAdmin class

// thanks to GravityForms for the nifty dismissal script
if ( in_array( basename($_SERVER['PHP_SELF']), array('admin.php', 'admin-ajax.php') ) ) {
	add_action( 'wp_ajax_rs_dismiss_msg', array( 'ScoperAdminLib', 'dashboard_dismiss_msg' ) );
}
?>