<?php
error_reporting(E_ALL);
include '../../includes/config.php';
include '../function.php';
$system_timezone = date_default_timezone_get();
date_default_timezone_set('UTC');
$currentDate = date('Y-m-d H:i:s');
$warning_from = date('Y-m-d H:i:s', strtotime('- 60 hours', strtotime($currentDate)));
 
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
    $aColumns = array( 'id','topic_id', 'Subject',  'CreatedDate',"Expires","is_deleted");
     
    /* Indexed column (used for fast and accurate table cardinality) */
    $sIndexColumn = "id";
     
    /* DB table to use */
    $sTable = "Alerts";
  
     
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
        $sOrder = "ORDER BY  ";
        for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
        {
            if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
            {
                $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
                    ".($_GET['sSortDir_'.$i]==='asc' ? 'desc' : 'asc') .", ";
            }
        }
         
        $sOrder = substr_replace( $sOrder, "", -2 );
        if ( $sOrder == "ORDER BY" )
        {
            $sOrder = "";
        }
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
    $sWhere.=" Expires >='".$warning_from."' ";
    
    
    /*
     * SQL queries
     * Get data to display
     */
      $sQuery = "
        SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
        FROM   $sTable
        $sWhere
        $sOrder
        $sLimit
    "; 
    // print_r($sQuery); die;
    $rResult = mysql_query( $sQuery ) or fatal_error( 'MySQL Error: ' . mysql_error() );
     
    /* Data set length after filtering */
    $sQuery = "
        SELECT FOUND_ROWS()
    ";
    $rResultFilterTotal = mysql_query( $sQuery  ) or fatal_error( 'MySQL Error: ' . mysql_error() );
    $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];
     
    /* Total data set length */
    $sQuery = "
        SELECT COUNT(".$sIndexColumn.")
        FROM   $sTable
    ";
    $rResultTotal = mysql_query( $sQuery ) or fatal_error( 'MySQL Error: ' . mysql_error() );
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
    $topics = getAllTopics();
    while ( $aRow = mysql_fetch_array( $rResult ) )
    {
        $row = array();
        $row[] = $aRow['id'];
        $row[] = $topics[$aRow['topic_id']];
        $row[] = $aRow['Subject'];
        $row[] = $aRow['CreatedDate'];
        $row[] = $aRow['Expires'];
        $alert_status=1;
        $alert_class='icon-eye-close';
        $alert_title='Hide This Alert';
        $alert_main_class='btn-danger';
        if($aRow['is_deleted']!=0){
            $alert_status=0;
            $alert_class='icon-eye-open';
            $alert_title='Show This Alert';
            $alert_main_class='btn-primary';
        }
        $row[] = '<a href="javascript:;" data-id="'.$aRow['id'].'" class="edit_warning btn btn-xs btn-success"><i class="icon-pencil"></i></a>
                  <a href="javascript:;" data-id="'.$aRow['id'].'" data-status="'.$alert_status.'" title="'.$alert_title.'" class="inactive_warning btn btn-xs '.$alert_main_class.'"><i class="'.$alert_class.'" ></i></a>
        ';
        
                  //'<a href="javascript:;" data-id="'.$aRow['id'].'"  class="btn btn-xs btn-danger confirm_delete_alert"><i class="icon-trash"></i></a>';
        $output['aaData'][] = $row;
    }
     
    echo json_encode( $output );
?>