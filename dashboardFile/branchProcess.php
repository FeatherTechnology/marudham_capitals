<?php
include '../ajaxconfig.php';

// class branchProcess
// {
//     public $branch_id;

//     function getBranchList($user_id)
//     {
//         global $connect;
//         $response = array();
//         $qry = $connect->query("SELECT bc.branch_id, bc.branch_name from branch_creation bc JOIN user u ON FIND_IN_SET (bc.branch_id, u.branch_id) where u.user_id = '$user_id' ");
//         while ($row = $qry->fetch()) {
//             $response[] = $row;
//         }


//         return $response;
//     }

//     function getSubAreaList($branch_id, $user_id)
//     {
//         global $connect;

//         $sub_area_list = array();

//         if ($branch_id == 0) { //if 0, then need to show all branches's sub area

//             $branch_list = $this->getBranchList($user_id); //calling this function because we need all branches allocated for the user to show all branch sub area
//             foreach ($branch_list as $branch) {
//                 $qry = $connect->query("SELECT sub_area_id FROM area_group_mapping where branch_id = '" . $branch['branch_id'] . "' ");
//                 while ($row = $qry->fetch()) {

//                     $sub_area_list[] = $row['sub_area_id'];
//                 }
//             }
//         } else {
//             //for particulat branch just show branch based sub area's count only
//             $qry = $connect->query("SELECT sub_area_id FROM area_group_mapping where branch_id = $branch_id ");
//             while ($row = $qry->fetch()) {

//                 $sub_area_list[] = $row['sub_area_id'];
//             }
//         }

//         $sub_area_ids = array();
//         foreach ($sub_area_list as $subarray) {
//             //store each sub area list into single sub area array
//             $sub_area_ids = array_merge($sub_area_ids, explode(',', $subarray));
//         }
//         $sub_area_list = implode(',', $sub_area_ids);
//         //check if sub area list is empty or not
//         if (!empty($sub_area_list)) {
//             return $sub_area_list;
//         } else {
//             return 'Error';
//         }
//     }
// }

class branchProcess
{
    private $connect;

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function getBranchList($user_id)
    {
        $response = array();
        $qry = $this->connect->query("SELECT bc.branch_id, bc.branch_name FROM branch_creation bc JOIN user u ON FIND_IN_SET(bc.branch_id, u.branch_id) WHERE u.user_id = '$user_id'");
        while ($row = $qry->fetch()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getSubAreaList($branch_id, $user_id)
    {
        $sub_area_list = array();

        if ($branch_id == 0) {
            $branch_list = $this->getBranchList($user_id);
            foreach ($branch_list as $branch) {
                $qry = $this->connect->query("SELECT sub_area_id FROM area_group_mapping WHERE branch_id = '" . $branch['branch_id'] . "'");
                while ($row = $qry->fetch()) {
                    $sub_area_list[] = $row['sub_area_id'];
                }
            }
        } else {
            $qry = $this->connect->query("SELECT sub_area_id FROM area_group_mapping WHERE branch_id = $branch_id");
            while ($row = $qry->fetch()) {
                $sub_area_list[] = $row['sub_area_id'];
            }
        }

        $sub_area_ids = array();
        foreach ($sub_area_list as $subarray) {
            $sub_area_ids = array_merge($sub_area_ids, explode(',', $subarray));
        }
        $sub_area_list = implode(',', $sub_area_ids);

        return !empty($sub_area_list) ? $sub_area_list : 'Error';
    }
}
