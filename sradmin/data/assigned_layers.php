<?php
require_once '../includes/sit_room.php';
error_reporting(0);

$layers = $db->get_client_layer($_GET['client_id']);
    /*
     * Script:    DataTables server-side script for PHP and MySQL
     * Copyright: 2010 - Allan Jardine, 2012 - Chris Wright
     * License:   GPL v2 or BSD (3-point)
     */
     
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Easy set variables
     */
     
    /* Array of database columns which should be read and sent back to DataTables. Use a space where
     * you want to insert a non-database field (for example a counter or static image)
     */
    $aColumns = array( 'ld.layer_id', 'ld.layer_type');
     
    /* Indexed column (used for fast and accurate table cardinality) */
    $sIndexColumn = "layer_id";
     
    /* DB table to use */
    $sTable = "layer_datas";
  
     
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * If you just want to use the basic configuration for DataTables with PHP server-side, there is
     * no need to edit below this line
     */
     
    /*
     * Local functions
     */
    function fatal_error ( $sErrorMessage = '' )
    {
        header( $_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error' );
        die( $sErrorMessage );
    }
 
     
     
     
    /*
     * Paging
     */
    $sLimit = "";
    if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
    {
        $sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
            intval( $_GET['iDisplayLength'] );
    }
     
     
    /*
     * Ordering
     */
    $sOrder = "";
    if ( isset( $_GET['iSortCol_0'] ) )
    {
        $sOrder = 'ORDER BY s.layer_id DESC , ';
        for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
        {
            if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
            {
                $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
                    ".($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
            }
        }
         
        $sOrder = substr_replace( $sOrder, "", -2 );
        if ( $sOrder == "ORDER BY" )
        {
            $sOrder = "";
        }
        else 
        {
            if(strpos($sOrder, "ld.layer_id"))
            {
                $sOrder = str_replace("ld.layer_id", "ld.layer_type", $sOrder);
            }
        }
    
    }
    
        
    //$groupby = "GROUP BY cl.client_id IN (".$_GET['client_id']."),ld.layer_type";
    $groupby = " GROUP BY ld.layer_id ";

    if($sOrder == "" )
    {
      $sOrder = ' ORDER BY  FIELD(`cl`.`client_id`,'.$_GET['client_id'].') DESC , `ld`.`layer_type` ASC ' ;
    }
     
     
    /*
     * Filtering
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here, but concerned about efficiency
     * on very large tables, and MySQL's regex functionality is very limited
     */
    $sWhere = "";
    if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
    {
        $sWhere = "WHERE (";
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" )
            {
                $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
            }
        }
        $sWhere = substr_replace( $sWhere, "", -3 );
        $sWhere .= ')';
    }
     
    /* Individual column filtering */
    for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
        if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
        {
            if ( $sWhere == "" )
            {
                $sWhere = "WHERE ";
            }
            else
            {
                $sWhere .= " AND ";
            }
            $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
        }
    }


            if ( $sWhere == "" )
            {
                $sWhere = "WHERE ";
            }
            else
            {
                $sWhere .= " AND ";
            }
            $sWhere .= ' `ld`.`layer_id` > 10  ';
     
    /*
     * SQL queries
     * Get data to display
     */
        /* $sQuery = "  SELECT SQL_CALC_FOUND_ROWS `ld`.`layer_id`,  `ld`.`layer_type`, `client_id` 
        FROM   $sTable AS `ld` 
        LEFT JOIN  `client_layers` AS  `cl` ON ld.layer_id = cl.layer_id
        $sWhere
        $groupby
        $sOrder
        $sLimit
    "; */

   $sQuery = "SELECT ld.layer_type, ld.layer_id
               FROM $sTable AS  `ld` 
               LEFT JOIN (
                    SELECT layer_id, client_id
                    FROM client_layers
                    WHERE  `client_id` 
                    IN ( ".$_GET['client_id']." ) 
                    GROUP BY layer_id
               ) s ON s.layer_id = ld.layer_id
                $sWhere
                $groupby
                $sOrder
                $sLimit
    ";

    /*SELECT ld.layer_type, ld.layer_id
FROM layer_datas AS  `ld` 
LEFT JOIN (

SELECT layer_id, client_id
FROM client_layers
WHERE  `client_id` 
IN ( 2 ) 
GROUP BY layer_id
)s ON s.layer_id = ld.layer_id
GROUP BY ld.layer_id
ORDER BY s.layer_id DESC , ld.layer_type ASC */
    $rResult = mysql_query( $sQuery) or fatal_error( 'MySQL Error: ' . mysql_errno() );
     
    
     $sQuery = "SELECT COUNT( DISTINCT layer_id,layer_type) FROM $sTable as ld $sWhere";
    //$sQuery = "SELECT FOUND_ROWS()";
    $rResultFilterTotal = mysql_query( $sQuery) or fatal_error( 'MySQL Error: ' . mysql_errno() );
    $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
    //print_r($aResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];
     
    /* Total data set length */
     $sQuery = "SELECT COUNT(DISTINCT layer_id,layer_type) from $sTable as ld $sWhere ";
    $rResultTotal = mysql_query( $sQuery) or fatal_error( 'MySQL Error: ' . mysql_errno() );
    $aResultTotal = mysql_fetch_array($rResultTotal);
    $iTotal = $aResultTotal[0];
     
     
    /*
     * Output
     */
    $output = array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );


     $check_array =array();
    while ( $aRow = mysql_fetch_array( $rResult ) )
    {
        $row = array();
            if(!in_array($aRow['layer_type'], $check_array)){
             $check_array[] =   $aRow['layer_type'];
             $checked = "";
             if(in_array($aRow['layer_id'], $layers)){
                $checked = 'checked="checked"';
             }
             $row[] = '<input type="checkbox"   class="layer_ids" value="'.$aRow['layer_id'].'" '.$checked.'>';
             $row[] = $aRow['layer_type'];
            $output['aaData'][] = $row;
        }

    }
     
    echo json_encode( $output );
?>