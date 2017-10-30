<?php namespace BEA\Menu_Anchors;

class Helpers {
	public static function get_anchor() {
		$anchor = get_sub_field( 'anchor' );

		return strtolower( sanitize_file_name( $anchor ) );
	}

	public static function the_anchor() {
		echo self::get_anchor();
	}

	public static function get_anchor_label( $post_id = 0 ) {
		$post_id = $post_id ?? get_the_ID();
		$anchor  = get_sub_field( 'anchor' );

		return sprintf( '%s - %s', get_the_title( $post_id ), $anchor );
	}
}