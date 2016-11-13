<?php
require_once '../includes/sit_room.php';
error_reporting(0);
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
    $aColumns = array( 'logo', 'first_name',  'company_name',"account_status",'payment_status','username','last_name', 'email','is_admin','id','days_allowed');
    
    /* Indexed column (used for fast and accurate table cardinality) */
    $sIndexColumn = "id";
    
    /* DB table to use */
    $sTable = "clients";
    
    
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
                ".($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
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
        if(strpos(($_GET['sSearch']), " ")==true){
            $sWhere .= "CONCAT(first_name, ' ', last_name) LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
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
    $sWhere .= " is_admin ='0' ";

    if(isset($_GET['status']) && $_GET['status']!="3")
    {
     if ( $sWhere == "" )
     {
        $sWhere = "WHERE ";
    }
    else
    {
        $sWhere .= " AND ";
    }
    $sWhere .= " account_status  =  " . $_GET['status'];
}


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
     //echo $sQuery; die;
    $rResult = mysql_query( $sQuery ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
    
    /* Data set length after filtering */
    $sQuery = "
    SELECT FOUND_ROWS()
    ";
    $rResultFilterTotal = mysql_query( $sQuery  ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
    $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];
    
    /* Total data set length */
    $sQuery = "
    SELECT COUNT(".$sIndexColumn.")
    FROM   $sTable
    ";
    $rResultTotal = mysql_query( $sQuery ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
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
    
    while ( $aRow = mysql_fetch_array( $rResult ) )
    {
        $row = array();
        /* $row[] = $aRow['id'];*/
        if(!file_exists("../".$aRow['logo']) || $aRow['logo']=="" )
        {
        	$file = "assets/default.png"; 
        }
        else
        {
        	$file = $aRow['logo'];
        }
        $row[] = '<div class=" image preview_image">
        <img src="'.sit_room::$base_url."/".$file.'" class="img-thumbnail" alt="'.$aRow['first_name'].'" width="50" height="87"  />
    </div>';
        /*$row[] = $aRow['first_name'];
        $row[] = $aRow['last_name'];*/
        $row[] = $aRow['first_name']. " " . $aRow['last_name'];
        
        $row[] = $aRow['company_name'];


        /*if($aRow['account_status']=="1"){
            $row[] = '<a href="ajax/client.php?account_status=change&status=0&id='.$aRow['id'].'" data-id="'.$aRow['id'].'" class="text-green change_status">Active</a>';

        }else {
            $row[] = '<a href="ajax/client.php?account_status=change&status=1&id='.$aRow['id'].'" data-id="'.$aRow['id'].'" class="text-red change_status">Inactive</a>';
            
        }*/

        $date_allowed = $aRow['days_allowed'];
        $todaysDate =  date("m/d/Y");

        $status = '';
       if($aRow['account_status']=="1")
       {
           $status = '<span class="text-primary">Active</span>';
       }
       elseif($aRow['account_status']=="2")
       {
           $status = '<span class="text-danger">Expired</span>';
       }
       else
       {
        $status = '<span class="text-danger">Inactive</span>';
       }

     $row[] = $status; 

     if(($aRow['account_status']==1 || $aRow['account_status']==0) && $aRow['payment_status'] == 'trial')
     {
        $expiry_date = strtotime($aRow['days_allowed']);
        $current_date = strtotime("now");
        if($current_date > $expiry_date)
        {
            $payment_status = 'Trial (Expired)';
        }
        else
        {
            $payment_status = 'Trial';
        }  
    } 
    elseif ($aRow['account_status']==2 && $aRow['payment_status'] == 'trial') 
    {
        $payment_status = 'Trial';
    }
    else
    {
        $payment_status = ucfirst($aRow['payment_status']);
    }

    $row[] = $payment_status;

    $row[] = date('d/m/Y',strtotime($aRow['days_allowed']));

    $row[] = '<a title="View Profile" href="#" data-href="ajax/get_client.php?client_id='.$aRow['id'].'" class="btn bg-navy btn-sm fetch_profile " data-toggle="modal" data-target="#myModal"><i class="fa fa-eye"></i></a>
    <a title="Edit Client" href="add_client.php?edit='.$aRow['id'].'" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a> 
    <a href="ajax/client.php?delete_client=delete_client&id='.$aRow['id'].'" title="Delete '.$aRow['first_name'].' Permanently" class="delete_client btn btn-danger btn-sm"><i class="fa fa-trash"></i></a> 
    <a href="assign_layer.php?client_id='.$aRow['id'].'" class="btn btn-info btn-sm assign_layer" title="Assign Layer"> <i class="fa fa-database"></i></a> 
    <a href="" data-href="data/client_sessions.php?type=1&client_id='.$aRow['id'].'" class="btn bg-orange btn-sm active_session_manager" title="Active Sessions of '.$aRow['first_name'].'"  data-toggle="modal" data-target="#active_session"> <i class="fa  fa-check-circle"></i></a>
    <a href="login_history.php?client_id='.$aRow['id'].'" class="btn btn-sm btn-warning" title="View '.$aRow['first_name'].' Login History"><i class="fa fa-clock-o"></i></a>
    ';
    $output['aaData'][] = $row;
}

echo json_encode( $output );
?>