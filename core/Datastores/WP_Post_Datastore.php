<?php

namespace Modely\Datastores;

class WP_Post_Datastore implements Datastore_Contract {
	/**
	 * Returns the WP_Post
	 *
	 * @access public
	 *
	 * @param  integer|WP_Post $the_post
	 * @return WP_Post|null
	 */
	public function find( $the_post ) {
		return get_post( $the_post );
	}

	/**
	 * Inserts a new Post record.
	 *
	 * @access public
	 * 
	 * @param  array $data
	 * @return WP_Comment|null
	 */
	public function create( $data ) {
		$post_id = wp_insert_post( $data );

		return $post_id ? $this->find( $post_id ) : null;
	}

	/**
	 * Updates a Comment record.
	 * 
	 * @param  array $data
	 * @return WP_Comment|null
	 */
	public function update( $data ) {
		$post_id = wp_update_post( $data );

		return $post_id ? $this->find( $post_id ) : null;
	}

	/**
	 * @todo
	 * @return [type] [description]
	 */
	public function delete() {

	}
}
