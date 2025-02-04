<?php
include('../ajaxconfig.php');
if(isset($_POST["company_name"])){
    $scompany_name = $_POST["company_name"];
}
    $getBranchName = $connect->query("SELECT * FROM company_creation WHERE company_id = '".$scompany_name."' AND status=0 ORDER BY company_id DESC");
    $fetch = $getBranchName->fetch();
    $company_name1  = strip_tags($fetch['company_name']);
    $str = preg_replace('/\s+/', '', $company_name1);
    $myStr = mb_substr($str, 0, 1);
$selectIC = $connect->query("SELECT branch_code FROM branch_creation WHERE branch_code != '' ");
if($selectIC->rowCount()>0)
{
    $codeAvailable = $connect->query("SELECT branch_code FROM branch_creation WHERE branch_code != '' ORDER BY branch_id DESC LIMIT 1");
    while($row = $codeAvailable->fetch()){
        $ac2 = $row["branch_code"];
    }
    $appno2 = ltrim(strstr($ac2, '-'), '-'); $appno2 = $appno2+1;
    // $appno1 = substr($appno2, 4, strpos($appno2, "/")) + 101 ;
    $branch_code = $myStr."-". "$appno2";
	// print_r($branch_code);die;
}
else
{
    $initialapp = $myStr."-101";
    $branch_code = $initialapp;
}
echo json_encode($branch_code);

// Close the database connection
$connect = null;
?>
