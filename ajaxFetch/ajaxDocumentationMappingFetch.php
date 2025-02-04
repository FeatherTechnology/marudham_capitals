<?php
include('../ajaxconfig.php');
@session_start();

if(isset($_SESSION["userid"])){
    $userid = $_SESSION["userid"];
}

$column = array(
    'doc_map_id',
    'loan_category',
    'sub_category',
    'doc_creation',
    'status'
);

$query = "SELECT * FROM doc_mapping ";

if($_POST['search']!="")
{
    if (isset($_POST['search'])) {

        if($_POST['search']=="Active")
        {
            $query .="WHERE status=0 "; 
        }
        else if($_POST['search']=="Inactive")
        {
            $query .="WHERE status=1 ";
        }

        else{	
            $query .= "WHERE
            doc_map_id LIKE  '%".$_POST['search']."%'
            OR loan_category LIKE '%".$_POST['search']."%'
            OR sub_category LIKE '%".$_POST['search']."%'
            OR doc_creation LIKE '%".$_POST['search']."%' ";
        }
    }
}

if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ';
}

$query1 = '';

if ($_POST['length'] != -1) {
    $query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);
$statement->execute();
$number_filter_row = $statement->rowCount();
$statement = $connect->prepare($query . $query1);
$statement->execute();
$result = $statement->fetchAll();
$data = array();

$sno = 1;
foreach ($result as $row) {
    $sub_array   = array();
    
    if($sno!="")
    {
        $sub_array[] = $sno;
    }
    
    
    //Company name Fetch
    $loan_category = $row['loan_category'];
    $getQry = "SELECT * from loan_category_creation where loan_category_creation_id = '".$loan_category."' and status = 0 ";
    $res=$connect->query($getQry);
    while($row1=$res->fetch())
    {
        $sub_array[] = $row1["loan_category_creation_name"];        
    }
    
    $sub_array[] = $row['sub_category'];
    
    $doc_creation = explode(',',$row['doc_creation']);
    $doc_list = '';
    for($i=0;$i<sizeof($doc_creation);$i++){
        if ($i > 0) {
            $doc_list .= ',';
        }
        if($doc_creation[$i] == '1'){$doc_list .= 'Sign Documents';}else
        if($doc_creation[$i] == '2'){$doc_list .= 'Cheque Details';}else
        if($doc_creation[$i] == '3'){$doc_list .= 'Mortage';}else
        if($doc_creation[$i] == '4'){$doc_list .= 'Endorsement';}else
        if($doc_creation[$i] == '5'){$doc_list .= 'Gold';}else
        if($doc_creation[$i] == '6'){$doc_list .= 'Documents';}else
        if($doc_creation[$i] == '7'){$doc_list .= 'Others';}
    }
    $sub_array[] = $doc_list;

    $status      = $row['status'];
    if($status == 1)
	{
	$sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill'>Inactive</span></span>";
	}
	else
	{
    $sub_array[] = "<span style='width: 144px;'><span class='kt-badge  kt-badge--success kt-badge--inline kt-badge--pill'>Active</span></span>";
	}
	
	$id   = $row['doc_map_id'];
	$action="<a href='doc_mapping&upd=$id' title='Edit details'><span class='icon-border_color'></span></a>&nbsp;&nbsp; 
	<a href='doc_mapping&del=$id' title='Delete details' class='delete_doc_mapping'><span class='icon-trash-2'></span></a>";

	$sub_array[] = $action;
    $data[]      = $sub_array;
    $sno = $sno + 1;
}

function count_all_data($connect)
{
    $query     = "SELECT * FROM doc_mapping";
    $statement = $connect->prepare($query);
    $statement->execute();
    return $statement->rowCount();
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => count_all_data($connect),
    'recordsFiltered' => $number_filter_row,
    'data' => $data
);

echo json_encode($output);

// Close the database connection
$connect = null;
?>