<?php namespace BEA\Menu_Anchors\Admin;

use BEA\Menu_Anchors\Helpers;
use BEA\Menu_Anchors\Singleton;

/**
 * Basic class for Admin
 *
 * Class Main
 * @package BEA\Menu_Anchors\Admin
 */
class Main {
	/**
	 * Use the trait
	 */
	use Singleton;

	public function __construct() {
		add_filter( 'nav_menu_meta_box_object', array( $this, 'nav_menu_meta_box_object' ), 10, 1 );
	}

	/**
	 * Add menu meta box
	 *
	 * @param object $object The meta box object
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
	 */
	function nav_menu_meta_box_object( $object ) {
		add_meta_box( 'bea-anchors-menu-metabox', 'Ancres de la homepage', [
			$this,
			'custom_menu_meta_box'
		], 'nav-menus', 'side', 'default' );

		return $object;
	}

	/**
	 * Displays a metabox for anchors menu item.
	 *
	 * @global int|string $nav_menu_selected_id (id, name or slug) of the currently-selected menu
	 *
	 * @link https://core.trac.wordpress.org/browser/tags/4.5/src/wp-admin/includes/nav-menu.php
	 * @link https://core.trac.wordpress.org/browser/tags/4.5/src/wp-admin/includes/class-walker-nav-menu-edit.php
	 * @link https://core.trac.wordpress.org/browser/tags/4.5/src/wp-admin/includes/class-walker-nav-menu-checklist.php
	 */
	function custom_menu_meta_box() {
		global $_nav_menu_placeholder, $nav_menu_selected_id;

		$_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : - 1;

		$display = '<div id="my-plugin-div"><div id="tabs-panel-my-plugin-all" class="tabs-panel tabs-panel-active">';
		$homepages      = new \WP_Query( [
			'post_type'      => 'page',
			'meta_query'     => [
				[
					'key'   => '_wp_page_template',
					'value' => 'template-homepage.php'
				]
			],
			'posts_per_page' => 50,
			'no_found_rows'  => true,
			'fields'         => 'ids',
			'status'         => 'publish',
		] );

		if ( ! $homepages->have_posts() ) {
			$display .= 'Pas de page avec le template de page "Homepage".';
		} else {
			$my_items = [];
			while ( $homepages->have_posts() ) { $homepages->the_post();
				if ( ! have_rows( 'blocs' ) ) {
					continue;
				}

				while( have_rows( 'blocs' ) ) { the_row();
					$anchor = Helpers::get_anchor();
					if ( empty( $anchor ) ) {
						continue;
					}
					$anchor_label = Helpers::get_anchor_label();

					$my_items[] = (object) [
						'ID'               => 1,
						'db_id'            => 0,
						'menu_item_parent' => 0,
						'object_id'        => 1,
						'post_parent'      => 0,
						'type'             => 'custom',
						'object'           => 'my-object-slug',
						'type_label'       => esc_html( $anchor_label ),
						'title'            => esc_html( $anchor_label ),
						'url'              => sprintf( '%s#%s', get_permalink( get_the_ID() ), esc_attr( $anchor ) ),
						'target'           => '',
						'attr_title'       => '',
						'description'      => '',
						'classes'          => [],
						'xfn'              => '',
					];
				}
			}
			wp_reset_postdata();

			$walker       = new \Walker_Nav_Menu_Checklist( [ 'parent' => 'parent', 'id' => 'post_parent' ] );
			$removed_args = [
				'action',
				'customlink-tab',
				'edit-menu-item',
				'menu-item',
				'page-tab',
				'_wpnonce',
			];
			$display      .= '<ul id="my-plugin-checklist-pop" class="categorychecklist form-no-clear">';
			$display      .= walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $my_items ), 0, (object) array( 'walker' => $walker ) );
			$display      .= '</ul>';
			$display      .= '<p class="button-controls">
				<span class="list-controls">
					<a href="' . esc_url( add_query_arg( array(
					'my-plugin-all' => 'all',
					'selectall'     => 1
				), remove_query_arg( $removed_args ) ) ) . '#my-plugin-div" class="select-all">Tous selectionner</a>
				</span>
				<span class="add-to-menu">
					<input type="submit" ' . wp_nav_menu_disabled_check( $nav_menu_selected_id ) . ' class="button-secondary submit-add-to-menu right" value="Ajouter au menu" name="add-my-plugin-menu-item" id="submit-my-plugin-div" />
					<span class="spinner"></span>
				</span>
			</p>';
		}

		$display .= '</div></div>';
		echo $display;
	}
}