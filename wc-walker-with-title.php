<?php
if ( class_exists( 'WC_Product_Cat_List_Walker', false ) ) :
class My_WC_Product_Cat_List_Walker extends WC_Product_Cat_List_Walker
{
public function start_el(&$output, $cat, $depth = 0, $args = array(), $current_object_id = 0)
    {
        $output .= '<li class="cat-item cat-item-' . $cat->term_id;

        if ($args['current_category'] == $cat->term_id) {
            $output .= ' current-cat';
        }

        if ($args['has_children'] && $args['hierarchical'] && (empty($args['max_depth']) || $args['max_depth'] > $depth + 1)) {
            $output .= ' cat-parent';
        }

        if ($args['current_category_ancestors'] && $args['current_category'] && in_array($cat->term_id, $args['current_category_ancestors'])) {
            $output .= ' current-cat-parent';
        }

        $output .= '"><a href="' . get_term_link((int)$cat->term_id, $this->tree_type) . '" title="Products category - '.$cat->name.'">' . _x($cat->name, 'product category name', 'woocommerce') . '</a>';

        if ($args['show_count']) {
            $output .= ' <span class="count">(' . $cat->count . ')</span>';
        }
    }
}
endif;
?>
