<?php
register_sidebar( array(
	'name' => 'filter_price',
	'id' => 'wc-filter_price',
	'before_widget' => '<div class="widget_price_filter">',
	'before_title' => '<div class="wc_filter_caption">',
	'after_title' => '</div>',
	'after_widget' => '</div>'
) );

register_sidebar( array(
	'name' => 'filter',
	'id' => 'wc-filter'
) );

function wc_getProp($args = array()) {
	if (!is_array($args)) parse_str($args, $args);
	$name = $args['name'];
	$type = $args['type'];
	$query = $args['query'];
	$init = $args['init'];
	$nolabel = $args['nolabel'];
	
	$tax = get_taxonomy('pa_' . $name);
	$terms = get_terms('pa_' . $name, 'hide_empty=true' );
	
	$found = false;
	$filtered_ids = WC()->query->filtered_product_ids;
	
	if ($filtered_ids == NULL) {
		global $wp_query;
		$args = array(
			'post_type' => 'product',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'product_cat' => $wp_query->query_vars['product_cat'],
			'fields' => 'ids',
			);              

		$the_query = new WP_Query( $args );
		$filtered_ids = $the_query->posts;
		wp_reset_query();
	}
	
	$filtered_terms = array();
	foreach ($terms as $term) {
		$products_in_term = get_objects_in_term( $term->term_id, $term->taxonomy );
		if (!$found) {
			foreach ($filtered_ids as $id) {
				if (in_array($id, $products_in_term)) {
					$found = true;
					break;
				}
			}
		} else {
			break;
		}
	}
	
	
	if ( $terms && ! is_wp_error( $terms ) ) {
		if ($found) {
			$filtered_terms = wp_get_object_terms( $filtered_ids,  'pa_' . $name );
			$filtered_terms = array_unique( $filtered_terms, SORT_REGULAR );
			$terms = $filtered_terms;
			
			$current_id = $_GET['filter_'.$name];
			$url_tags = explode(',',$current_id);
			
			$count = 0;
			$checked_query = '';
			foreach ( $terms as $value ) {
				$num = 0;	
				foreach ($url_tags as $key => &$val) {
					if ($val == $value->slug) {
						$checked = ' checked';
						$active = ' active';
						if($num == 0) {
							$selected1 = ' selected';
						}
						if ($num == count($url_tags) - 1) {
							$selected2 = ' selected';
						}
						$checked_query = ' checked';
						break;
					} else {
						$checked = '';
						$active = '';
						$selected1 = '';
						$selected2 = '';
					}
					$num++;
				}
				if ($type && $type == 'color') {
					$color_box = '<div class="wc_filter_color" data-color="'.$value->name.'"></div>';
				} else {
					$color_box = '';
				}
				
				if ($type && $type == 'input') {
					$property_name3 = '';
					if ($count == 0) {
						$property_name1 = '<select id="filter_'.$name.'_min" class="range">';
						$property_name1 .= '<option>От...</option>';
						$property_name2 = '<select id="filter_'.$name.'_max" class="range">';
						$property_name2 .= '<option>До...</option>';
					}
					$property_name1 .= '<option value="filter_'.$name.'='.$value->slug.'"'.$selected1.'>'.$value->name.'</option>';
					$property_name2 .= '<option value="filter_'.$name.'='.$value->slug.'"'.$selected2.'>'.$value->name.'</option>';
					if ($count == count($terms) - 1) {
						$property_name1 .= '</select>';
						$property_name2 .= '</select>';
						if ($query != 'and') {
							$property_name3 = '<input type="checkbox" name="'.$name.'_query" value="query_type_'.$name.'=or" style="display:none"'.$checked_query.'>';
						}
						$property_name .= $property_name1.$property_name2.$property_name3;
					}
				} else if ($type && $type == 'select') {
					$query = 'and';
					$property_name3 = '';
					if ($count == 0) {
						$property_name1 = '<select id="filter_'.$name.'" class="select">';
						$property_name1 .= '<option></option>';
					}
					$property_name1 .= '<option value="filter_'.$name.'='.$value->slug.'"'.$selected1.'>'.$value->name.'</option>';
					if ($count == count($terms) - 1) {
						$property_name1 .= '</select>';
						if ($query != 'and') {
							$property_name3 = '<input type="checkbox" name="'.$name.'_query" value="query_type_'.$name.'=or" style="display:none"'.$checked_query.'>';
						}
						$property_name .= $property_name1.$property_name3;
					}
				} else {
					$property_name .= '<label class="checkbox fillbox'.$active.$hidden.'" title="'.$value->name.'"><input type="checkbox" value="filter_'.$name.'='.$value->slug.'"'.$checked.'><span></span>'.$color_box.$value->name.'<div class="wc_filter_count">'.$value->count.'</div></label>';
					if ($query != 'and') {
						if ($count == count($terms) - 1) {
							$property_name .= '<input type="checkbox" name="'.$name.'_query" value="query_type_'.$name.'=or" style="display:none"'.$checked_query.'>';
						}
					}
				}
				++$count;
			}
			if (!$init) {
				$init = '';
			}
			if ($nolabel == 'nolabel') {
				$nolabel = ' nolabel';
			} else {
				$nolabel = '';
			}
			
			return '<div class="wc_filter_caption">'.$tax->label.'<span class="wp_filter_init">'.$init.'</span></div><div class="wc_filter_list'.$nolabel.'" data-list="'.$name.'">'.$property_name.'<div class="filterToggle"></div></div>';
		} else {
			return '';
		}
		
	}
	
}
?>