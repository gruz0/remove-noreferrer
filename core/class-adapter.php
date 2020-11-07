<?php
/**
 * Adapter class represents WordPress Core functions to mock it inside PHPUnit tests
 *
 * @package Remove_Noreferrer
 * @subpackage Core
 * @since 2.0.0
 */

namespace Remove_Noreferrer\Core;

/**
 * Adapter class
 *
 * @since 2.0.0
 */
class Adapter {
	/**
	 * Checks if it is an admin area
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public function is_admin() {
		return \is_admin();
	}

	/**
	 * Checks if it is a single post
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public function is_single() {
		return \is_single();
	}

	/**
	 * Checks if it is a page
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public function is_page() {
		return \is_page();
	}

	/**
	 * Checks if it is posts page
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public function is_posts_page() {
		return \is_home();
	}
}

