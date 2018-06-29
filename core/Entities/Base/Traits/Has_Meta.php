<?php

namespace Modely\Entities\Base\Traits;

trait Has_Meta {
	/**
	 * Returns the real meta key.
	 *
	 * @access protected
	 *
	 * @param  string $key
	 * @return string
	 */
	protected function get_mapped_meta_key( $key ) {
		return $this->is_meta_key_a_map_key( $key ) ? $this->meta_map[ $key ] : $key;
	}

	/**
	 * Determines whether the given meta key is a map key.
	 *
	 * @access protected
	 *
	 * @return boolean
	 */
	protected function is_meta_key_a_map_key( $key ) {
		return property_exists( $this, 'meta_map' ) && isset( $this->meta_map[ $key ] );
	}
}
