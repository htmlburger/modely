<?php

namespace Modely\Entities\Base;

use Modely\Datastores\WP_Post_Datastore;
use WP_Post;

abstract class WP_Post_Entity implements WP_Entity_Contract {
	use Traits\Has_Attributes;
	use Traits\Has_Post_Meta;

	const POST_TYPE = 'post';

	/**
	 * Entity Datastore.
	 *
	 * @var \Modely\Datastores\WP_Post_Datastore
	 */
	protected $datastore = null;

	/**
	 * Entity Object.
	 *
	 * @var \WP_Post
	 */
	protected $entity = null;

	/**
	 * Entity data.
	 *
	 * @var array
	 */
	protected $entity_data = [
		'ID'                    => 0,
		'post_author'           => 0,
		'post_date'             => '0000-00-00 00:00:00',
		'post_date_gmt'         => '0000-00-00 00:00:00',
		'post_modified'         => '0000-00-00 00:00:00',
		'post_modified_gmt'     => '0000-00-00 00:00:00',
		'post_content'          => '',
		'post_content_filtered' => '',
		'post_title'            => '',
		'post_excerpt'          => '',
		'post_status'           => 'publish',
		'comment_status'        => 'open',
		'ping_status'           => 'open',
		'post_password'         => '',
		'post_name'             => '',
		'post_parent'           => 0,
		'guid'                  => '',
		'menu_order'            => 0,
		'to_ping'               => '',
		'pinged'                => '',
		'post_type'             => 'post',
		'post_mime_type'        => '',
		'comment_count'         => '0',
		'filter'                => 'raw',
	];

	/**
	 * Entity meta data.
	 *
	 * @var array
	 */
	protected $entity_meta_data = [];

	/**
	 * WP_Post data map.
	 *
	 * @access protected
	 *
	 * @var array
	 */
	protected $entity_data_map = [
		'id'           => 'ID',
		'title'        => 'post_title',
		'content'      => 'post_content',
		'created_on'   => 'post_date',
		'status'       => 'post_status',
		'slug'         => 'post_name',
		'type'         => 'post_type',
		'author_id'    => 'post_author',
	];

	/**
	 * Custom entity data map.
	 *
	 * @access protected
	 *
	 * @var array
	 */
	protected $custom_data_map = [];

	/**
	 * Entity data casts.
	 *
	 * @access protected
	 */
	protected $entity_data_casts = [
		'id' => 'integer',
	];

	/**
	 * Custom model casts.
	 *
	 * @access protected
	 */
	protected $casts = [];

	/**
	 * Constructor.
	 *
	 * @access public
	 *
	 * @param  mixed $data
	 * @return void
	 */
	public function __construct( $data = [] ) {
		if ( is_a( $data, '\WP_Post' ) ) {
			$this->entity = $data;
		}

		$this->post_type = static::POST_TYPE;
		$this->fill( $data );
	}

	/**
	 * Fills the Entity attributes.
	 *
	 * @access protected
	 *
	 * @param  array $attributes
	 * @return self
	 */
	protected function fill( $attributes ) {
		if ( is_a( $attributes, '\WP_Post' ) ) {
			$this->entity = $attributes;
		}

		foreach ( $attributes as $key => $value ) {
			$this->set_attribute( $key, $value );
		}

		return $this;
	}

	/**
	 * Get the Entity ID.
	 *
	 * @access public
	 *
	 * @return integer
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get the Entity Title.
	 *
	 * @access public
	 *
	 * @return string
	 */
	public function get_title() {
		return get_the_title( $this->id );
	}

	/**
	 * Searches for the given post.
	 *
	 * @static
	 * @access public
	 *
	 * @param  integer|\WP_Post
	 * @return self
	 */
	public static function find( $the_post ) {
		$model   = (new static)->set_datastore();
		$wp_post = $model->datastore->find( $the_post );

		echo '<pre>';
		echo 'LINE: ' . __LINE__ . '<br/>';
		var_dump($model);
		exit;

		if ( ! $wp_post || $wp_post->post_type !== static::POST_TYPE ) {
			return null;
		}

		$model->fill( $wp_post );

		return $model;
	}

	/**
	 * Creates a new WP_Post.
	 *
	 * @static
	 * @access public
	 *
	 * @param  array
	 * @return self
	 */
	public static function create( $data = [] ) {
		return (new static( $data ))->save();
	}

	/**
	 * Saves a WP_Post.
	 *
	 * @access public
	 *
	 * @return self
	 */
	public function save() {
		$this->set_datastore();

		$post_data               = $this->entity_data;
		$post_data['meta_input'] = $this->entity_meta_data;

		$wp_post = $this->datastore->create( $post_data );

		if ( ! $wp_post ) {
			return null;
		}

		$this->entity_meta_data = [];
		$this->fill( $wp_post );

		return $this;
	}

	/**
	 * Updates a WP_Post.
	 *
	 * @access public
	 *
	 * @param  array
	 * @return self
	 */
	public function update( $data ) {
		$this->fill( $data );

		$post_data               = $this->entity_data;
		$post_data['meta_input'] = $this->entity_meta_data;

		$wp_post = $this->datastore->update( $post_data );

		$this->refresh();

		return $this;
	}

	/**
	 * Refreshes the WP_Post data.
	 *
	 * @access public
	 *
	 * @return self
	 */
	public function refresh() {
		$wp_post = $this->datastore->find( $this->id );

		if ( ! $wp_post ) {
			return null;
		}

		$this->entity_meta_data = [];
		$this->fill( $wp_post );

		return $this;
	}

	/**
	 * Sets the Datastore for the instance.
	 *
	 * @access protected
	 *
	 * @return self
	 */
	protected function set_datastore() {
		$this->datastore = new WP_Post_Datastore;

		return $this;
	}

	/**
	 * Magic Getter.
	 *
	 * @access public
	 *
	 * @return mixed
	 */
	public function __get( $property ) {
		return $this->get_attribute( $property );
	}

	/**
	 * Magic setter.
	 *
	 * @access public
	 *
	 * @return self
	 */
	public function __set( $property, $value ) {
		return $this->set_attribute( $property, $value );
	}
}
