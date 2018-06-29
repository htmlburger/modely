<?php

namespace Modely\Datastores;

interface Datastore_Contract {
	/**
	 * Searches the Database for the given data.
	 *
	 * @static
	 * @access public
	 *
	 * @param mixed $data
	 */
	public function find( $data );

	/**
	 * Creates a record for the given data.
	 *
	 * @static
	 * @access public
	 *
	 * @param mixed $data
	 */
	public function create( $data );

	/**
	 * Updates a record with the given data.
	 *
	 * @access public
	 *
	 * @param mixed $data
	 */
	public function update( $data );

	/**
	 * Deletes a record with the given data.
	 *
	 * @access public
	 */
	public function delete();
}
