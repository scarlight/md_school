<?php

require_once get_template_directory().'/php_excel/PHPExcel/Classes/PHPExcel.php';

// spreadhsheet is in memory
$obj_ex = new PHPExcel(); // create workbook, a single worksheet called "WorkSheet" is created
// hardcoded, later change
$worksheet2 = new PHPExcel_Worksheet($obj_ex, 'Designer Pendrives');
$worksheet3 = new PHPExcel_Worksheet($obj_ex, 'USB Gadgets');
$worksheet4 = new PHPExcel_Worksheet($obj_ex, 'Electronic Gadgets');

$obj_ex->getActiveSheet()->setTitle('Popular Pendrives');
$obj_ex->addSheet($worksheet2);
$obj_ex->addSheet($worksheet3);
$obj_ex->addSheet($worksheet4);

$obj_ex->getSheet(0)->getDefaultColumnDimension()->setWidth(15);
$obj_ex->getSheet(1)->getDefaultColumnDimension()->setWidth(15);
$obj_ex->getSheet(2)->getDefaultColumnDimension()->setWidth(15);
$obj_ex->getSheet(3)->getDefaultColumnDimension()->setWidth(15);

$obj_ex->getProperties()->setCreator('Massdata System'); // set Workbook metas
$obj_ex->getProperties()->setLastModifiedBy('Massdata Report System');
$obj_ex->getProperties()->setTitle('Stock Count Report of January 2014');
$obj_ex->getProperties()->setSubject('Stock Count Report of January 2014');
$obj_ex->getProperties()->setDescription('Stock count report for massdata');
$obj_ex->getProperties()->setKeywords('office 2007 openxml php');
$obj_ex->getProperties()->setCategory("Test result file");
PHPExcel_Settings::setLocale('en_us');

//products 42=>4, 41=>5 , 40=>38 , 39=>84

/////////////Start Popular Pen Drive Worksheet population
$obj_ex->setActiveSheetIndex(0);
$current_row = 1;
$current_column = 0;
$top_row_set = 0;
$popular_pen_drive = new WP_Query(
    array(
        'post_type' => 'product',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => array(39),
                'include_children' => false
            )
        ),
        'posts_per_page' => -1,
        'fields' => 'ids'
    )
);
if (!empty($popular_pen_drive->posts)) {

    $popular_children = array();
    foreach ($popular_pen_drive->posts as $index => $id) { //get all variables for each products variant

        $queried = new WP_Query(array(
            'post_type' => 'product_variation',
            'post_status' => 'publish',
            'post_parent' => $id,
            'posts_per_page' => -1,
            'fields' => 'ids',
            'order' => 'ASC',
            'orderby' => 'menu_order'

        ));
        $popular_children[$id] = $queried->posts;
    }
    foreach ($popular_children as $product_index => $children) {
        if (!empty($children)) {
            if (!$top_row_set) {


                $current_column = 0;
                $current_row++;
                $current_row++;
                $product_model = get_post_meta($product_index, 'massdata_product_model', true);

                $obj_ex->getActiveSheet()->mergeCellsByColumnAndRow($current_column, $current_row , $current_column+3, $current_row);
                $obj_ex->getActiveSheet()->getStyle(get_cell_row_range_by_number($current_column, $current_row, 4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $obj_ex->getActiveSheet()->getStyle(get_cell_row_range_by_number($current_column, $current_row, 4))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
                $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Stock Count for Product Model: '.$product_model);

                $current_column = 1;
                $current_row++;
                $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Total Reserve');
                $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $current_column++;
                $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Stock Available');
                $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


                $current_column++;
                $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Stock Total');
                $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $top_row_set = 1;
            }

            $total_reserved = 0;
            $total_available = 0;
            $total_stock = 0;
            if(!empty($children)) {
                foreach ($children as $variable_index => $variable) {

                    $current_column = 0;
                    $current_row++;

                    $color = get_post_meta($variable, 'attribute_pa_color', true);
                    if (!empty($color)) {

                        $color = explode('-', $color);
                        if (!empty($color)) {
                            foreach ($color as $color_index => $color_explode) {
                                $color[$color_index] = ucfirst($color_explode);
                            }
                            $color = implode(' ', $color);
                        } else {
                            $color = ucfirst($color);
                        }
                        $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $color);
                        $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $current_column++;

                        $reserve = (int)get_post_meta($variable, '_reserve_stock', true);
                        $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $reserve);
                        $current_column++;

                        $stock = (int)get_post_meta($variable, '_stock', true);
                        $available_stock = (int)$stock - (int)$reserve;
                        $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $available_stock);
                        $current_column++;

                        $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $stock);

                        $total_reserved = $total_reserved + $reserve;
                        $total_available = $total_available + $available_stock;
                        $total_stock = $total_stock + $stock;
                    }
                }

                if(!empty($children)){
                    $current_column = 0;
                    $current_row++;

                    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Total');
                    $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $current_column++;
                    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $total_reserved);

                    $current_column++;
                    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $total_available);

                    $current_column++;
                    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $total_stock);
                }

                $top_row_set = 0;
                $current_row++;
            }
        }
    }
}else{

    $current_column = 0;
    $current_row = 1;
    $current_row++;

    $obj_ex->getActiveSheet()->mergeCellsByColumnAndRow($current_column, $current_row , $current_column+4, $current_row);
    $obj_ex->getActiveSheet()->getStyle(get_cell_row_range_by_number($current_column, $current_row, 5))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $obj_ex->getActiveSheet()->getStyle(get_cell_row_range_by_number($current_column, $current_row, 5))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Stock count for products under "Popular Pendrive" not found');
}
/////////////End Popular Pen Drive Worksheet population

/////////////Start Designer Pen Drive Worksheet population
$obj_ex->setActiveSheetIndex(1);
$current_row = 1;
$current_column = 0;
$top_row_set = 0;
$designer_pen_drive = new WP_Query(
    array(
        'post_type' => 'product',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => array(40),
                'include_children' => false
            )
        ),
        'posts_per_page' => -1,
        'fields' => 'ids'
    )
);
if (!empty($designer_pen_drive->posts)) {

    $designer_children = array();
    foreach ($designer_pen_drive->posts as $index => $id) { //get all variables for each products variant

        $queried = new WP_Query(array(
            'post_type' => 'product_variation',
            'post_status' => 'publish',
            'post_parent' => $id,
            'posts_per_page' => -1,
            'fields' => 'ids',
            'order' => 'ASC',
            'orderby' => 'menu_order'
        ));
        $designer_children[$id] = $queried->posts;
    }
    foreach ($designer_children as $product_index => $children) {
        if (!empty($children)) {
            if (!$top_row_set) {


                $current_column = 0;
                $current_row++;
                $current_row++;
                $product_model = get_post_meta($product_index, 'massdata_product_model', true);

                $obj_ex->getActiveSheet()->mergeCellsByColumnAndRow($current_column, $current_row , $current_column+3, $current_row);
                $obj_ex->getActiveSheet()->getStyle(get_cell_row_range_by_number($current_column, $current_row, 4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $obj_ex->getActiveSheet()->getStyle(get_cell_row_range_by_number($current_column, $current_row, 4))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
                $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Stock Count for Product Model: '.$product_model);

                $current_column = 1;
                $current_row++;
                $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Total Reserve');
                $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $current_column++;
                $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Stock Available');
                $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


                $current_column++;
                $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Stock Total');
                $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $top_row_set = 1;
            }

            $total_reserved = 0;
            $total_available = 0;
            $total_stock = 0;
            if(!empty($children)) {
                foreach ($children as $variable_index => $variable) {

                    $current_column = 0;
                    $current_row++;

                    $color = get_post_meta($variable, 'attribute_pa_color', true);
                    if (!empty($color)) {

                        $color = explode('-', $color);
                        if (!empty($color)) {
                            foreach ($color as $color_index => $color_explode) {
                                $color[$color_index] = ucfirst($color_explode);
                            }
                            $color = implode(' ', $color);
                        } else {
                            $color = ucfirst($color);
                        }
                        $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $color);
                        $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $current_column++;

                        $reserve = (int)get_post_meta($variable, '_reserve_stock', true);
                        $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $reserve);
                        $current_column++;

                        $stock = (int)get_post_meta($variable, '_stock', true);
                        $available_stock = (int)$stock - (int)$reserve;
                        $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $available_stock);
                        $current_column++;

                        $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $stock);

                        $total_reserved = $total_reserved + $reserve;
                        $total_available = $total_available + $available_stock;
                        $total_stock = $total_stock + $stock;
                    }
                }

                if(!empty($children)){
                    $current_column = 0;
                    $current_row++;

                    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Total');
                    $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $current_column++;
                    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $total_reserved);

                    $current_column++;
                    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $total_available);

                    $current_column++;
                    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $total_stock);
                }

                $top_row_set = 0;
                $current_row++;
            }
        }
    }
}else{

    $current_column = 0;
    $current_row++;
    $current_row++;

    $obj_ex->getActiveSheet()->mergeCellsByColumnAndRow($current_column, $current_row , $current_column+4, $current_row);
    $obj_ex->getActiveSheet()->getStyle(get_cell_row_range_by_number($current_column, $current_row, 5))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $obj_ex->getActiveSheet()->getStyle(get_cell_row_range_by_number($current_column, $current_row, 5))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Stock count for products under "Designer Pendrive" not found');
}
/////////////End Designer Pen Drive Worksheet population

/////////////Start Usb Gadget Worksheet population
$obj_ex->setActiveSheetIndex(2);
$current_row = 1;
$current_column = 0;
$top_row_set = 0;
$usb_gadget = new WP_Query(
    array(
        'post_type' => 'product',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => array(41),
                'include_children' => false
            )
        ),
        'posts_per_page' => -1,
        'fields' => 'ids'
    )
);
if (!empty($usb_gadget->posts)) {

    $usb_children = array();
    foreach ($usb_gadget->posts as $index => $id) { //get all variables for each products variant

        $queried = new WP_Query(array(
            'post_type' => 'product_variation',
            'post_status' => 'publish',
            'post_parent' => $id,
            'posts_per_page' => -1,
            'fields' => 'ids',
            'order' => 'ASC',
            'orderby' => 'menu_order'
        ));
        $usb_children[$id] = $queried->posts;
    }
    foreach ($usb_children as $product_index => $children) {
        if (!empty($children)) {
            if (!$top_row_set) {

                $current_column = 0;
                $current_row++;
                $current_row++;
                $product_model = get_post_meta($product_index, 'massdata_product_model', true);

                $obj_ex->getActiveSheet()->mergeCellsByColumnAndRow($current_column, $current_row , $current_column+3, $current_row);
                $obj_ex->getActiveSheet()->getStyle(get_cell_row_range_by_number($current_column, $current_row, 4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $obj_ex->getActiveSheet()->getStyle(get_cell_row_range_by_number($current_column, $current_row, 4))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
                $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Stock Count for Product Model: '.$product_model);

                $current_column = 1;
                $current_row++;
                $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Total Reserve');
                $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $current_column++;
                $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Stock Available');
                $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


                $current_column++;
                $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Stock Total');
                $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $top_row_set = 1;
            }

            $total_reserved = 0;
            $total_available = 0;
            $total_stock = 0;
            if(!empty($children)) {
                foreach ($children as $variable_index => $variable) {

                    $current_column = 0;
                    $current_row++;

                    $color = get_post_meta($variable, 'attribute_pa_color', true);
                    if (!empty($color)) {

                        $color = explode('-', $color);
                        if (!empty($color)) {
                            foreach ($color as $color_index => $color_explode) {
                                $color[$color_index] = ucfirst($color_explode);
                            }
                            $color = implode(' ', $color);
                        } else {
                            $color = ucfirst($color);
                        }
                        $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $color);
                        $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $current_column++;

                        $reserve = (int)get_post_meta($variable, '_reserve_stock', true);
                        $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $reserve);
                        $current_column++;

                        $stock = (int)get_post_meta($variable, '_stock', true);
                        $available_stock = (int)$stock - (int)$reserve;
                        $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $available_stock);
                        $current_column++;

                        $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $stock);

                        $total_reserved = $total_reserved + $reserve;
                        $total_available = $total_available + $available_stock;
                        $total_stock = $total_stock + $stock;
                    }
                }

                if(!empty($children)){
                    $current_column = 0;
                    $current_row++;

                    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Total');
                    $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $current_column++;
                    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $total_reserved);

                    $current_column++;
                    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $total_available);

                    $current_column++;
                    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $total_stock);
                }

                $top_row_set = 0;
                $current_row++;
            }
        }
    }
}else{

    $current_column = 0;
    $current_row++;
    $current_row++;

    $obj_ex->getActiveSheet()->mergeCellsByColumnAndRow($current_column, $current_row , $current_column+4, $current_row);
    $obj_ex->getActiveSheet()->getStyle(get_cell_row_range_by_number($current_column, $current_row, 5))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $obj_ex->getActiveSheet()->getStyle(get_cell_row_range_by_number($current_column, $current_row, 5))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Stock count for products under "USB Gadget" not found');

}
/////////////End USB Gadget Worksheet population

/////////////Start Electronic Gadget Worksheet population
$obj_ex->setActiveSheetIndex(3);
$current_row = 1;
$current_column = 0;
$top_row_set = 0;
$electronic_gadget = new WP_Query(
    array(
        'post_type' => 'product',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => array(42),
                'include_children' => false
            )
        ),
        'posts_per_page' => -1,
        'fields' => 'ids'
    )
);
if (!empty($electronic_gadget->posts)) {

    $electronic_children = array();
    foreach ($electronic_gadget->posts as $index => $id) { //get all variables for each products variant

        $queried = new WP_Query(array(
            'post_type' => 'product_variation',
            'post_status' => 'publish',
            'post_parent' => $id,
            'posts_per_page' => -1,
            'fields' => 'ids',
            'order' => 'ASC',
            'orderby' => 'menu_order'
        ));
        $electronic_children[$id] = $queried->posts;
    }
    foreach ($electronic_children as $product_index => $children) {
        if (!empty($children)) {
            if (!$top_row_set) {

                $current_column = 0;
                $current_row++;
                $current_row++;
                $product_model = get_post_meta($product_index, 'massdata_product_model', true);

                $obj_ex->getActiveSheet()->mergeCellsByColumnAndRow($current_column, $current_row , $current_column+3, $current_row);
                $obj_ex->getActiveSheet()->getStyle(get_cell_row_range_by_number($current_column, $current_row, 4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $obj_ex->getActiveSheet()->getStyle(get_cell_row_range_by_number($current_column, $current_row, 4))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
                $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Stock Count for Product Model: '.$product_model);

                $current_column = 1;
                $current_row++;
                $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Total Reserve');
                $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $current_column++;
                $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Stock Available');
                $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


                $current_column++;
                $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Stock Total');
                $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $top_row_set = 1;
            }


            $total_reserved = 0;
            $total_available = 0;
            $total_stock = 0;
            if(!empty($children)) {
                foreach ($children as $variable_index => $variable) {

                    $current_column = 0;
                    $current_row++;

                    $color = get_post_meta($variable, 'attribute_pa_color', true);
                    if (!empty($color)) {

                        $color = explode('-', $color);
                        if (!empty($color)) {
                            foreach ($color as $color_index => $color_explode) {
                                $color[$color_index] = ucfirst($color_explode);
                            }
                            $color = implode(' ', $color);
                        } else {
                            $color = ucfirst($color);
                        }
                        $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $color);
                        $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $current_column++;

                        $reserve = (int)get_post_meta($variable, '_reserve_stock', true);
                        $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $reserve);
                        $current_column++;

                        $stock = (int)get_post_meta($variable, '_stock', true);
                        $available_stock = (int)$stock - (int)$reserve;
                        $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $available_stock);
                        $current_column++;

                        $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $stock);

                        $total_reserved = $total_reserved + $reserve;
                        $total_available = $total_available + $available_stock;
                        $total_stock = $total_stock + $stock;
                    }
                }

                if(!empty($children)){
                    $current_column = 0;
                    $current_row++;

                    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Total');
                    $obj_ex->getActiveSheet()->getStyle(get_cell($current_column, $current_row))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    $current_column++;
                    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $total_reserved);

                    $current_column++;
                    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $total_available);

                    $current_column++;
                    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, $total_stock);
                }

                $top_row_set = 0;
                $current_row++;
            }
        }
    }
}else{

    $current_column = 0;
    $current_row++;
    $current_row++;

    $obj_ex->getActiveSheet()->mergeCellsByColumnAndRow($current_column, $current_row , $current_column+4, $current_row);
    $obj_ex->getActiveSheet()->getStyle(get_cell_row_range_by_number($current_column, $current_row, 5))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $obj_ex->getActiveSheet()->getStyle(get_cell_row_range_by_number($current_column, $current_row, 5))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
    $obj_ex->getActiveSheet()->setCellValueByColumnAndRow($current_column, $current_row, 'Stock count for products under "Electronic Gadeget" not found');

}
/////////////End Electronic Gadget Worksheet population
$filename = 'massdata_stock_reservation_report_'.date('Y M d').'.xlsx';
$filename = sanitize_file_name($filename);
$report_path = get_template_directory().'/'.$filename;

$objWriter = new PHPExcel_Writer_Excel2007($obj_ex);
$objWriter->save($report_path);

//clearing memory, to prevent memory leaks
$obj_ex->disconnectWorksheets();
unset($obj_ex);

if(is_readable($report_path)){
    wp_mail(get_option('admin_email'), 'Massdata Stock Report', "Monthly report of massdata system stock reservation. \nPlease open the attachment for the report.", '', $report_path);

    $new_report_path = ABSPATH.'wp-content/stock_report';

    if(!is_dir($new_report_path)){
        mkdir($new_report_path);
        $new_report_path = $new_report_path . '/'. $filename;
    }else{
        $new_report_path = $new_report_path .'/'.$filename;
    }
    rename($report_path, $new_report_path);
}

function get_cell($current_column, $current_row){
    $alphabet = chr(65+$current_column);
    return "{$alphabet}{$current_row}";
}
function get_cell_row_range_by_number($current_column, $current_row, $range){
    $alphabet = chr(65+$current_column);
    $alphabet2 = chr(65+$range+$current_column-1);
    return "{$alphabet}{$current_row}:{$alphabet2}{$current_row}";
}
