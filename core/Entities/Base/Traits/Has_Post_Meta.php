<?php

namespace Modely\Entities\Base\Traits;

trait Has_Post_Meta {
	use Has_Meta;

	/**
	 * Get the value for the given meta key.
	 *
	 * @access public
	 *
	 * @param  string  $key
	 * @param  boolean $single
	 * @return mixed
	 */
	public function get_meta( $key, $single = true ) {
		$key = $this->get_mapped_meta_key( $key );

		return get_post_meta( $this->get_id(), $key, $single );
	}

	/**
	 * Get the value for the given meta key.
	 *
	 * @access public
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @param  boolean $unique
	 * @return mixed
	 */
	public function add_meta( $key, $value, $unique = false ) {
		$args_count = func_get_args();

		$key = $this->get_mapped_meta_key( $key );

		return add_post_meta( $this->get_id(), $key, $value, $unique );
	}

	/**
	 * Get the value for the given meta key.
	 *
	 * @access public
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @param  mixed   $prev_value
	 * @return mixed
	 */
	public function update_meta( $key, $value, $prev_value = '' ) {
		$key = $this->get_mapped_meta_key( $key );

		return update_post_meta( $this->get_id(), $key, $value, $prev_value );
	}

	/**
	 * Get the value for the given meta key.
	 *
	 * @access public
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @param  boolean $unique
	 * @return mixed
	 */
	public function delete_meta( $key, $value = '' ) {
		$key = $this->get_mapped_meta_key( $key );

		return delete_post_meta( $this->get_id(), $key, $value );
	}
}
