<?php

////////////////////// PDFS GENERATE /////////////////////////////

require_once get_template_directory().'/pdfexcel/PHPExcel/Classes/PHPExcel.php';


$html = <<<'ENDHTML'
<html>
    <head>

    </head>
    <body>
        <h1>Hello World!!!!!</h1>
    </body>
</html>
ENDHTML;

$args = array(
    'post_type' => 'product',
    'cat__in' => array(39, 40, 41),
    'fields' => 'ids',
);
$value = get_posts($args);
var_dump($value);

foreach($value as $index => $id){

    $args = array(
        'post_type' => 'product_variation',
        'post_parent' => $id,
    );
}


$filename = 'massdata_stock_reservation_report_'.date('Y M d').'.xlsx';
$filename = sanitize_file_name($filename);
$report_path = get_template_directory().'/'.$filename;

$obj_ex = new PHPExcel();
$obj_ex->getProperties()->setCreator('Massdata System');
$obj_ex->getProperties()->setLastModifiedBy('Massdata Report System');
$obj_ex->getProperties()->setTitle('Stock Count Report of January 2014');
$obj_ex->getProperties()->setSubject('Stock Count Report of January 2014');
$obj_ex->getProperties()->setDescription('Stock count report for massdata');

$obj_ex->setActiveSheetIndex(0);
$obj_ex->getActiveSheet()->setCellValue('B1', 'Total Reserve');
$obj_ex->getActiveSheet()->getCell('B1')->setCalculatedValue(strlen('Total Reserve')+20);
$obj_ex->getActiveSheet()->setCellValue('C1', 'Stock Available');
$obj_ex->getActiveSheet()->getCell('C1')->setCalculatedValue(strlen('Stock Available')+20);
$obj_ex->getActiveSheet()->setCellValue('D1', 'Stock Total');
$obj_ex->getActiveSheet()->getCell('D1')->setCalculatedValue(strlen('Stock Total')+20);

// Stock count of each reserved product

$obj_ex->getActiveSheet()->setCellValue('A2', 'Blue');
$obj_ex->getActiveSheet()->setCellValue('A3', 'White');
$obj_ex->getActiveSheet()->setCellValue('A4', 'Black');


//
//$obj_ex->getActiveSheet()->setCellValue('A1', 'Hello');
//$obj_ex->getActiveSheet()->setCellValue('B1', 'world');
//$obj_ex->getActiveSheet()->setCellValue('C1', 'Hello');
//$obj_ex->getActiveSheet()->setCellValue('D1', 'world');


$objWriter = new PHPExcel_Writer_Excel2007($obj_ex);
$objWriter->save($report_path);


if(is_readable($report_path)){
    wp_mail(get_option('admin_email'), 'Massdata Stock Report', "Monthly report of massdata system stock reservation. \nPlease open the attachment for the report.", '', $report_path);
    unlink($report_path);
}



/*
 *
Template Name: Stock Report :)
*/
if (!is_user_logged_in() || !current_user_can('manage_options')) wp_die('This page is private.');
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php _e('Stock Report'); ?></title>
    <style>
        body { background:white; color:black; width: 95%; margin: 0 auto; }
        table { border: 1px solid #000; width: 100%; }
        table td, table th { border: 1px solid #000; padding: 6px; }
    </style>
</head>
<body>
<header>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <h1 class="title"><?php the_title(); ?></h1>

        <?php the_content(); ?>

    <?php endwhile; endif; ?>
</header>
<section>
    <?php

    global $woocommerce;
    ?>
    <table cellspacing="0" cellpadding="2">
        <thead>
        <tr>
            <th scope="col" style="text-align:left;"><?php _e('Product', 'woothemes'); ?></th>
            <th scope="col" style="text-align:left;"><?php _e('SKU', 'woothemes'); ?></th>
            <th scope="col" style="text-align:left;"><?php _e('Stock', 'woothemes'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php

        $args = array(
            'post_type'			=> 'product',
            'post_status' 		=> 'publish',
            'posts_per_page' 	=> -1,
            'orderby'			=> 'title',
            'order'				=> 'ASC',
            'meta_query' 		=> array(
                array(
                    'key' 	=> '_manage_stock',
                    'value' => 'yes'
                )
            ),
            'tax_query' => array(
                array(
                    'taxonomy' 	=> 'product_type',
                    'field' 	=> 'slug',
                    'terms' 	=> array('simple'),
                    'operator' 	=> 'IN'
                )
            )
        );

        $loop = new WP_Query( $args );

        while ( $loop->have_posts() ) : $loop->the_post();

            global $product;
            ?>
            <tr>
                <td><?php echo $product->get_title(); ?></td>
                <td><?php echo $product->sku; ?></td>
                <td><?php echo $product->stock; ?></td>
            </tr>
        <?php
        endwhile;

        ?>
        </tbody>
    </table>

    <h2>Variations</h2>
    <table cellspacing="0" cellpadding="2">
        <thead>
        <tr>
            <th scope="col" style="text-align:left;"><?php _e('Variation', 'woothemes'); ?></th>
            <th scope="col" style="text-align:left;"><?php _e('Parent', 'woothemes'); ?></th>
            <th scope="col" style="text-align:left;"><?php _e('SKU', 'woothemes'); ?></th>
            <th scope="col" style="text-align:left;"><?php _e('Stock', 'woothemes'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php

        $args = array(
            'post_type'			=> 'product_variation',
            'post_status' 		=> 'publish',
            'posts_per_page' 	=> -1,
            'orderby'			=> 'title',
            'order'				=> 'ASC',
            'meta_query' => array(
                array(
                    'key' 		=> '_stock',
                    'value' 	=> array('', false, null),
                    'compare' 	=> 'NOT IN'
                )
            )
        );

        $loop = new WP_Query( $args );

        while ( $loop->have_posts() ) : $loop->the_post();
            $product = new WC_Product_Variation( $loop->post->ID );
            ?>
            <tr>
                <td><?php echo $product->get_title(); ?></td>
                <td><?php echo get_the_title( $loop->post->post_parent ); ?></td>
                <td><?php echo $product->sku; ?></td>
                <td><?php echo $product->stock; ?></td>
            </tr>
        <?php
        endwhile;

        ?>
        </tbody>
    </table>
</body>
</html>