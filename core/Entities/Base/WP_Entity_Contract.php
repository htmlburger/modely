<?php

namespace Modely\Entities\Base;

interface WP_Entity_Contract {
	/**
	 * Get the Entity ID.
	 *
	 * @access public
	 * 
	 * @return integer
	 */
	public function get_id();

	/**
	 * Searches for the given Entity.
	 *
	 * @access public
	 * 
	 * @param integer $id
	 */
	public static function find( $id );

	/**
	 * Creates a new Entity.
	 *
	 * @access public
	 * 
	 * @param array $data
	 */
	public static function create( $data );

	/**
	 * Searches for the given Entity.
	 *
	 * @access public
	 * 
	 * @param array $data
	 */
	public function update( $data );
}
