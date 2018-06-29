<?php

namespace Modely\Entities\Base\Traits;

trait Has_Attributes {
	/**
	 * Sets the value of the given attribute.
	 *
	 * @access public
	 *
	 * @param  string $key
	 * @param  mixed  $value
	 * @return self
	 */
	public function set_attribute( $key, $value ) {
		// Entity Data
		if ( isset( $this->entity_data[ $key ] ) ) {
			$this->entity_data[ $key ] = $value;
		}

		// Mapped Entity Data
		else if ( isset( $this->entity_data_map[ $key ] ) ) {
			$this->entity_data[ $this->entity_data_map[ $key ] ] = $value;
		}

		// Mapped Custom Entity Data
		else if ( isset( $this->custom_data_map[ $key ] ) ) {
			$this->entity_data[ $this->custom_data_map[ $key ] ] = $value;
		}

		// Entity Meta
		else if ( isset( $this->meta_map[ $key ] ) ) {
			$this->entity_meta_data[ $this->meta_map[ $key ] ] = $value;
		}

		// Otherwise, store as meta
		else {
			$this->entity_meta_data[ $key ] = $value;
		}
	}

	/**
	 * Get the value of the given attribute.
	 *
	 * @access public
	 */
	public function get_attribute( $key ) {
		// WP Entity Attribute
		if ( isset( $this->entity_data[ $key ] ) ) {
			return $this->format_attribute( $key, $this->entity_data[ $key ] );
		}

		// WP Entity mapped Attribute
		if ( isset( $this->entity_data_map[ $key ] ) ) {
			$wp_entity_attribute_name = $this->entity_data_map[ $key ];

			return $this->format_attribute( $key, $this->$wp_entity_attribute_name );
		}

		// Custom data map
		if ( isset( $this->custom_data_map[ $key ] ) ) {
			$mapped_key = $this->custom_data_map[ $key ];

			return $this->format_attribute( $this->custom_data_map[ $key ], $this->$mapped_key );
		}

		// Custom Attribute
		if ( $this->has_custom_attribute_getter( $key ) ) {
			return $this->get_custom_attribute_value( $key );
		}

		// Entity Mapped Meta
		if ( $this->is_meta_key_a_map_key( $key ) ) {
			if ( ( $mapped_key = $this->get_mapped_meta_key( $key ) ) && isset( $this->entity_meta_data[ $mapped_key ] ) ) {
				return $this->format_attribute( $key, $this->entity_meta_data[ $mapped_key ] );
			} else {
				return $this->format_attribute( $key, $this->get_meta( $key ) );
			}
		}

		// Entity Meta
		if ( isset( $this->entity_meta_data[ $key ] ) ) {
			return $this->format_attribute( $key, $this->entity_meta_data[ $key ] );
		}

		// Entity Attribute
		if ( property_exists( $this->entity, $key ) ) {
			return $this->format_attribute( $key, $this->entity->$key );
		}

		return $this->format_attribute( $key, $this->entity->__get( $key ) );
	}

	/**
	 * Determine if a getter exists for a custom attribute.
	 *
	 * @param  string $key
	 * @return boolean
	 */
	public function has_custom_attribute_getter( $key ) {
		return method_exists( $this, $this->get_custom_attribute_method_name( $key ) );
	}

	/**
	 * Calls the custom attribute getter method.
	 *
	 * @param  string $key
	 * @return mixed
	 */
	public function get_custom_attribute_value( $key ) {
		$getter_method_name = $this->get_custom_attribute_method_name( $key );

		return $this->$getter_method_name();
	}

	/**
	 * Get the name of a custom attribute getter method.
	 *
	 * @access protected
	 * @return string
	 */
	protected function get_custom_attribute_method_name( $key ) {
		return "get_{$key}_attribute";
	}

	/**
	 * Returns a formatted attribute.
	 *
	 * @access protected
	 *
	 * @param  string $key
	 * @param  mixed  $attribute
	 * @return mixed
	 */
	protected function format_attribute( $key, $attribute ) {
		$cast_type = null;

		if ( isset( $this->entity_data_casts[ $key ] ) ) {
			$cast_type = $this->entity_data_casts[ $key ];
		} else if ( isset( $this->casts[ $key ] ) ) {
			$cast_type = $this->casts[ $key ];
		}

		switch ( $cast_type ) {
			case 'integer':
			case 'int':
			case 'number':
				return (int) $attribute;

			case 'string':
				return (string) $attribute;

			case 'array':
				return (array) $attribute;

			case 'object':
				return (object) $attribute;

			default:
				return $attribute;
		}
	}
}
