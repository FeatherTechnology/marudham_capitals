-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 27, 2025 at 01:25 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `marudham_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `acknowledgement_loan_cal_category`
--

CREATE TABLE `acknowledgement_loan_cal_category` (
  `cat_id` int(11) NOT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `loan_cal_id` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acknowlegement_customer_profile`
--

CREATE TABLE `acknowlegement_customer_profile` (
  `id` int(11) NOT NULL,
  `req_id` varchar(50) DEFAULT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `cus_name` varchar(255) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `dob` varchar(50) DEFAULT NULL,
  `age` varchar(50) DEFAULT NULL,
  `blood_group` varchar(50) DEFAULT NULL,
  `mobile1` varchar(50) DEFAULT NULL,
  `mobile2` varchar(50) DEFAULT NULL,
  `whatsapp` varchar(50) DEFAULT NULL,
  `cus_pic` varchar(255) DEFAULT NULL,
  `guarentor_name` varchar(255) DEFAULT NULL,
  `guarentor_relation` varchar(100) DEFAULT NULL,
  `guarentor_photo` varchar(255) DEFAULT NULL,
  `cus_type` varchar(50) DEFAULT NULL,
  `cus_exist_type` varchar(50) DEFAULT NULL,
  `residential_type` varchar(50) DEFAULT NULL,
  `residential_details` varchar(255) DEFAULT NULL,
  `residential_address` varchar(255) DEFAULT NULL,
  `residential_native_address` varchar(255) DEFAULT NULL,
  `occupation_type` varchar(50) DEFAULT NULL,
  `occupation_details` varchar(255) DEFAULT NULL,
  `occupation_income` varchar(255) DEFAULT NULL,
  `occupation_address` varchar(255) DEFAULT NULL,
  `dow` varchar(255) DEFAULT NULL,
  `abt_occ` varchar(255) DEFAULT NULL,
  `area_confirm_type` varchar(50) DEFAULT NULL,
  `area_confirm_state` varchar(100) DEFAULT NULL,
  `area_confirm_district` varchar(100) DEFAULT NULL,
  `area_confirm_taluk` varchar(100) DEFAULT NULL,
  `area_confirm_area` varchar(255) DEFAULT NULL,
  `area_confirm_subarea` varchar(255) DEFAULT NULL,
  `latlong` varchar(255) DEFAULT NULL,
  `area_group` varchar(255) DEFAULT NULL,
  `area_line` varchar(255) DEFAULT NULL,
  `communication` varchar(50) DEFAULT NULL,
  `com_audio` varchar(255) DEFAULT NULL,
  `verification_person` varchar(255) DEFAULT NULL,
  `verification_location` varchar(255) DEFAULT NULL,
  `cus_status` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `insert_login_id` varchar(100) DEFAULT NULL,
  `update_login_id` varchar(100) DEFAULT NULL,
  `delete_login_id` varchar(100) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `acknowlegement_documentation`
--

CREATE TABLE `acknowlegement_documentation` (
  `id` int(11) NOT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `cus_id_doc` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `cus_profile_id` varchar(255) DEFAULT NULL,
  `doc_id` varchar(255) DEFAULT NULL,
  `mortgage_process` varchar(100) DEFAULT NULL,
  `mortgage_process_noc` varchar(10) NOT NULL DEFAULT '0',
  `mort_noc_date` varchar(255) DEFAULT NULL,
  `mort_noc_person` varchar(255) DEFAULT NULL,
  `mort_noc_name` varchar(255) DEFAULT NULL,
  `Propertyholder_type` varchar(100) DEFAULT NULL,
  `Propertyholder_name` varchar(255) DEFAULT NULL,
  `Propertyholder_relationship_name` varchar(100) DEFAULT NULL,
  `doc_property_relation` varchar(100) DEFAULT NULL,
  `doc_property_type` varchar(255) DEFAULT NULL,
  `doc_property_measurement` varchar(255) DEFAULT NULL,
  `doc_property_location` varchar(255) DEFAULT NULL,
  `doc_property_value` varchar(255) DEFAULT NULL,
  `mortgage_name` varchar(255) DEFAULT NULL,
  `mortgage_dsgn` varchar(255) DEFAULT NULL,
  `mortgage_nuumber` varchar(255) DEFAULT NULL,
  `reg_office` varchar(255) DEFAULT NULL,
  `mortgage_value` varchar(255) DEFAULT NULL,
  `mortgage_document` varchar(255) DEFAULT NULL,
  `mortgage_document_noc` varchar(10) NOT NULL DEFAULT '0',
  `mort_doc_noc_date` varchar(255) DEFAULT NULL,
  `mort_doc_noc_person` varchar(255) DEFAULT NULL,
  `mort_doc_noc_name` varchar(255) DEFAULT NULL,
  `mortgage_document_used` varchar(10) NOT NULL DEFAULT '0',
  `mortgage_document_upd` varchar(255) DEFAULT NULL,
  `mortgage_document_pending` varchar(150) DEFAULT NULL,
  `endorsement_process` varchar(50) DEFAULT NULL,
  `endorsement_process_noc` varchar(10) NOT NULL DEFAULT '0',
  `endor_noc_date` varchar(255) DEFAULT NULL,
  `endor_noc_person` varchar(255) DEFAULT NULL,
  `endor_noc_name` varchar(255) DEFAULT NULL,
  `owner_type` varchar(100) DEFAULT NULL,
  `owner_name` varchar(200) DEFAULT NULL,
  `ownername_relationship_name` varchar(100) DEFAULT NULL,
  `en_relation` varchar(100) DEFAULT NULL,
  `vehicle_type` varchar(50) DEFAULT NULL,
  `vehicle_process` varchar(50) DEFAULT NULL,
  `en_Company` varchar(200) DEFAULT NULL,
  `en_Model` varchar(200) DEFAULT NULL,
  `vehicle_reg_no` varchar(150) DEFAULT NULL,
  `endorsement_name` varchar(255) DEFAULT NULL,
  `en_RC` varchar(50) DEFAULT NULL,
  `en_RC_noc` varchar(10) NOT NULL DEFAULT '0',
  `en_rc_noc_date` varchar(255) DEFAULT NULL,
  `en_rc_noc_person` varchar(255) DEFAULT NULL,
  `en_rc_noc_name` varchar(255) DEFAULT NULL,
  `en_RC_used` varchar(10) NOT NULL DEFAULT '0',
  `Rc_document_upd` varchar(255) DEFAULT NULL,
  `Rc_document_pending` varchar(150) DEFAULT NULL,
  `en_Key` varchar(50) DEFAULT NULL,
  `en_Key_noc` varchar(10) NOT NULL DEFAULT '0',
  `en_key_noc_date` varchar(255) DEFAULT NULL,
  `en_key_noc_person` varchar(255) DEFAULT NULL,
  `en_key_noc_name` varchar(255) DEFAULT NULL,
  `en_Key_used` varchar(10) NOT NULL DEFAULT '0',
  `gold_info` varchar(50) DEFAULT NULL,
  `gold_sts` varchar(50) DEFAULT NULL,
  `gold_type` varchar(255) DEFAULT NULL,
  `Purity` varchar(255) DEFAULT NULL,
  `gold_Count` varchar(255) DEFAULT NULL,
  `gold_Weight` varchar(255) DEFAULT NULL,
  `gold_Value` varchar(255) DEFAULT NULL,
  `document_name` varchar(255) DEFAULT NULL,
  `document_details` varchar(255) DEFAULT NULL,
  `document_type` varchar(50) DEFAULT NULL,
  `doc_info_upload` varchar(255) DEFAULT NULL,
  `doc_info_upload_noc` varchar(10) NOT NULL DEFAULT '0',
  `doc_info_upload_used` varchar(10) NOT NULL DEFAULT '0',
  `document_holder` varchar(50) DEFAULT NULL,
  `docholder_name` varchar(255) DEFAULT NULL,
  `docholder_relationship_name` varchar(50) DEFAULT NULL,
  `doc_relation` varchar(50) DEFAULT NULL,
  `cus_status` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `submitted` varchar(10) DEFAULT NULL,
  `insert_login_id` varchar(50) DEFAULT NULL,
  `update_login_id` varchar(50) DEFAULT NULL,
  `delete_login_id` varchar(50) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `acknowlegement_loan_calculation`
--

CREATE TABLE `acknowlegement_loan_calculation` (
  `loan_cal_id` int(11) NOT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `cus_id_loan` varchar(255) DEFAULT NULL,
  `cus_name_loan` varchar(255) DEFAULT NULL,
  `cus_data_loan` varchar(255) DEFAULT NULL,
  `mobile_loan` varchar(255) DEFAULT NULL,
  `pic_loan` varchar(255) DEFAULT NULL,
  `loan_category` varchar(255) DEFAULT NULL,
  `sub_category` varchar(255) DEFAULT NULL,
  `tot_value` varchar(255) DEFAULT NULL,
  `ad_amt` varchar(255) DEFAULT NULL,
  `loan_amt` varchar(255) DEFAULT NULL,
  `profit_type` varchar(255) DEFAULT NULL,
  `due_method_calc` varchar(255) DEFAULT NULL,
  `due_type` varchar(255) DEFAULT NULL,
  `profit_method` varchar(255) DEFAULT NULL,
  `calc_method` varchar(255) DEFAULT NULL,
  `due_method_scheme` varchar(255) DEFAULT NULL,
  `profit_method_scheme` varchar(50) DEFAULT NULL,
  `day_scheme` varchar(255) DEFAULT NULL,
  `scheme_name` varchar(255) DEFAULT NULL,
  `int_rate` varchar(255) DEFAULT NULL,
  `due_period` varchar(255) DEFAULT NULL,
  `doc_charge` varchar(255) DEFAULT NULL,
  `proc_fee` varchar(255) DEFAULT NULL,
  `loan_amt_cal` varchar(255) DEFAULT NULL,
  `principal_amt_cal` varchar(255) DEFAULT NULL,
  `int_amt_cal` varchar(255) DEFAULT NULL,
  `tot_amt_cal` varchar(255) DEFAULT NULL,
  `due_amt_cal` varchar(255) DEFAULT NULL,
  `doc_charge_cal` varchar(255) DEFAULT NULL,
  `proc_fee_cal` varchar(255) DEFAULT NULL,
  `net_cash_cal` varchar(255) DEFAULT NULL,
  `due_start_from` varchar(255) DEFAULT NULL,
  `maturity_month` varchar(255) DEFAULT NULL,
  `collection_method` varchar(255) DEFAULT NULL,
  `communication` varchar(50) DEFAULT NULL,
  `com_audio` varchar(255) DEFAULT NULL,
  `verification_person` varchar(255) DEFAULT NULL,
  `verification_location` varchar(255) DEFAULT NULL,
  `verify_remark` varchar(255) DEFAULT NULL,
  `cus_status` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `create_date` datetime DEFAULT current_timestamp(),
  `update_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `agent_communication_details`
--

CREATE TABLE `agent_communication_details` (
  `comm_id` int(11) NOT NULL,
  `agent_reff_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `agent_creation`
--

CREATE TABLE `agent_creation` (
  `ag_id` int(11) NOT NULL,
  `ag_code` varchar(255) DEFAULT NULL,
  `ag_name` varchar(255) DEFAULT NULL,
  `ag_group_id` varchar(255) DEFAULT NULL,
  `company_id` varchar(255) DEFAULT NULL,
  `branch_id` varchar(255) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `taluk` varchar(255) DEFAULT NULL,
  `place` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `loan_category` varchar(255) DEFAULT NULL,
  `sub_category` varchar(255) DEFAULT NULL,
  `scheme` varchar(255) DEFAULT NULL,
  `loan_payment` varchar(255) DEFAULT NULL,
  `responsible` varchar(255) DEFAULT NULL,
  `collection_point` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_branch_name` varchar(255) DEFAULT NULL,
  `acc_no` varchar(255) DEFAULT NULL,
  `ifsc` varchar(255) DEFAULT NULL,
  `holder_name` varchar(255) DEFAULT NULL,
  `more_info` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `agent_group_creation`
--

CREATE TABLE `agent_group_creation` (
  `agent_group_id` int(11) NOT NULL,
  `agent_group_name` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `area_creation`
--

CREATE TABLE `area_creation` (
  `area_creation_id` int(11) NOT NULL,
  `area_name_id` int(11) NOT NULL,
  `sub_area` varchar(255) DEFAULT NULL,
  `taluk` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `enable` varchar(255) NOT NULL DEFAULT '0',
  `status` int(11) DEFAULT 0,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `created_date` varchar(255) DEFAULT NULL,
  `updated_date` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `area_creation`
--

INSERT INTO `area_creation` (`area_creation_id`, `area_name_id`, `sub_area`, `taluk`, `district`, `state`, `pincode`, `enable`, `status`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date`) VALUES
(1, 1, '1,2', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:02', '2024-06-24 15:17:02'),
(2, 2, '3,3,3', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '2', NULL, '2024-06-24 15:17:02', '2024-07-30 12:53:09'),
(3, 3, '4', 'Cheyyar', 'Tiruvannamalai', 'TamilNadu', '604407', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:02', NULL),
(4, 4, '5', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:02', NULL),
(5, 5, '6', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:02', NULL),
(6, 6, '7', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:02', NULL),
(7, 7, '8', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:02', NULL),
(8, 8, '9', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:02', NULL),
(9, 9, '10,11', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:02', '2024-06-24 15:17:02'),
(10, 10, '12', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:02', NULL),
(11, 11, '13', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:02', NULL),
(12, 12, '14', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:02', NULL),
(13, 13, '15', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:02', NULL),
(14, 14, '16', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(15, 15, '17', 'Cheyyar', 'Tiruvannamalai', 'TamilNadu', '604407', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(16, 16, '18', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(17, 17, '19', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(18, 18, '20', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(19, 19, '21,22', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', '7', NULL, '2024-06-24 15:17:03', '2024-06-24 15:17:03'),
(20, 20, '23', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(21, 21, '24', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(22, 22, '25', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(23, 23, '26', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '', 0, '7', '2', NULL, '2024-06-24 15:17:03', '2025-01-11 19:05:31'),
(24, 24, '27', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(25, 25, '28,890', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:03', '2024-07-18 13:34:45'),
(26, 26, '29', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(27, 27, '30', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(28, 28, '31', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(29, 29, '32', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(30, 30, '33', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(31, 31, '34', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(32, 32, '35', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(33, 33, '36', 'Cheyyar', 'Tiruvannamalai', 'TamilNadu', '604407', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(34, 34, '37', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(35, 35, '38', 'Cheyyar', 'Tiruvannamalai', 'TamilNadu', '604407', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(36, 36, '39', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(37, 37, '40', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(38, 38, '41', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(39, 1406, '1696', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:03', '2025-01-22 14:53:46'),
(40, 40, '43', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(41, 41, '44', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(42, 42, '45,46', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', '7', NULL, '2024-06-24 15:17:03', '2024-06-24 15:17:03'),
(43, 43, '47', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(44, 44, '48,49', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:03', '2024-06-24 15:17:03'),
(45, 45, '50', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(46, 46, '51', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(47, 47, '52', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(48, 48, '53', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(49, 49, '54', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:03', '2025-01-11 18:48:09'),
(50, 50, '55', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(51, 51, '56', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(52, 52, '57', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:03', '2025-01-10 19:38:36'),
(53, 53, '58', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(54, 54, '59', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '', 0, '7', '2', NULL, '2024-06-24 15:17:03', '2025-01-11 18:57:57'),
(55, 55, '60', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(56, 56, '61', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:03', '2025-01-11 18:50:14'),
(57, 57, '62', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:03', NULL),
(58, 58, '63', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(59, 59, '64', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(60, 60, '65,66', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:04', '2024-06-24 15:17:04'),
(61, 61, '67,68', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:04', '2024-06-24 15:17:04'),
(62, 62, '69', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(63, 63, '70', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(64, 64, '71', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:04', '2025-01-25 12:04:20'),
(65, 65, '72', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(66, 66, '73', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(67, 67, '74', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(68, 68, '75', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(69, 69, '76', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(70, 70, '77', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(71, 71, '78', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(72, 72, '79', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(73, 73, '80', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(74, 74, '81,1459,1460,1461,1462,1463,1464,81,1358,1465,1466,1467,1468,1469,1470,1471,1472,1473,1474,1475,1426,1476,1477,1478,1479,1449,1480,1481,1482,2089,2090,2091,2092,2093,2094,81,2095,2096,2097,2098,2099,2100,2101,2102,2103,2104,2105,2106,2107,2108,2109,2110,2', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', '2', NULL, '2024-06-24 15:17:04', '2024-07-30 14:59:39'),
(75, 75, '82', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(76, 76, '83', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(77, 77, '84', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(78, 78, '85', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(79, 79, '86', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(80, 80, '87', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(81, 81, '88', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(82, 82, '89', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(83, 83, '90', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(84, 84, '91', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(85, 85, '92', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(86, 86, '93', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(87, 87, '94', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(88, 88, '95', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(89, 89, '96', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(90, 90, '97', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(91, 91, '98', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:04', '2025-01-25 12:02:11'),
(92, 92, '99', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(93, 93, '100', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(94, 94, '101,102', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', '7', NULL, '2024-06-24 15:17:04', '2024-06-24 15:17:04'),
(95, 95, '103', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(96, 96, '104', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(97, 97, '105', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(98, 98, '106', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:04', NULL),
(99, 99, '107', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(100, 100, '108', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(101, 101, '109', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(102, 102, '110', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(103, 103, '111', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(104, 104, '112', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(105, 105, '113', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(106, 106, '114', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '', 0, '7', '2', NULL, '2024-06-24 15:17:05', '2025-01-11 19:04:52'),
(107, 107, '115,116', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:05', '2024-06-24 15:17:05'),
(108, 108, '117', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(109, 109, '118', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(110, 110, '119', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(111, 111, '120', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(112, 112, '121,122', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:05', '2024-06-24 15:17:05'),
(113, 113, '123', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '', 0, '7', '2', NULL, '2024-06-24 15:17:05', '2025-01-11 18:58:46'),
(114, 114, '124', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(115, 115, '125', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(116, 116, '126', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(117, 117, '127', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(118, 118, '128', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(119, 119, '129', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(120, 120, '130', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(121, 121, '131', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(122, 122, '132', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(123, 123, '133', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '', 0, '7', '2', NULL, '2024-06-24 15:17:05', '2025-01-23 14:54:53'),
(124, 124, '134', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(125, 125, '135', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(126, 126, '136,137', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', '7', NULL, '2024-06-24 15:17:05', '2024-06-24 15:17:05'),
(127, 127, '138', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(128, 128, '139', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(129, 129, '140', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(130, 130, '141,891', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:05', '2024-07-18 13:35:19'),
(131, 131, '142', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(132, 132, '143', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(133, 133, '144', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(134, 134, '145', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(135, 135, '146', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(136, 136, '147', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(137, 137, '148', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(138, 1411, '1701', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:05', '2025-02-03 10:02:13'),
(139, 139, '150', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:05', NULL),
(140, 140, '151', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:06', '2025-01-20 12:45:17'),
(141, 141, '152', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(142, 142, '153', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(143, 143, '154', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(144, 144, '155', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(145, 145, '156', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(146, 146, '157', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(147, 147, '158', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(148, 148, '159', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(149, 149, '160,161', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:06', '2024-06-24 15:17:06'),
(150, 150, '162', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:06', '2025-01-20 12:36:05'),
(151, 151, '163', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(152, 152, '164', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:06', '2025-01-11 18:54:55'),
(153, 153, '165', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:06', '2025-01-20 12:36:33'),
(154, 154, '166', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(155, 155, '167', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(156, 156, '168', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(157, 157, '169', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(158, 158, '170', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(159, 159, '171', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(160, 160, '172', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(161, 161, '173', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:06', '2025-01-25 12:12:26'),
(162, 162, '174,175,176,177', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:06', '2024-06-24 15:17:06'),
(163, 163, '178', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(164, 164, '179', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(165, 165, '180', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(166, 166, '181', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(167, 167, '182', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:06', '2025-01-25 12:05:42'),
(168, 168, '183', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(169, 169, '184', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(170, 170, '185', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(171, 171, '186', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(172, 172, '187', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:06', '2025-01-11 18:45:31'),
(173, 173, '188', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(174, 174, '189', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(175, 175, '190', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(176, 176, '191', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(177, 177, '192', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(178, 178, '193', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(179, 179, '194', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(180, 180, '195', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:06', '2025-01-20 12:40:36'),
(181, 181, '196', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(182, 182, '197', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:06', NULL),
(183, 183, '198', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(184, 184, '199', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(185, 185, '200', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(186, 186, '201', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(187, 187, '202', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(188, 188, '203', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(189, 189, '204', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(190, 190, '205', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(191, 191, '206', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(192, 192, '207', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(193, 193, '208', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(194, 194, '209', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(195, 195, '210', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(196, 196, '211', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(197, 197, '212,213', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:07', '2025-01-25 12:15:04'),
(198, 198, '214', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:07', '2025-01-11 18:53:53'),
(199, 199, '215', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(200, 200, '216', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(201, 201, '217', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(202, 202, '218', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(203, 203, '219', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(204, 204, '220', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(205, 205, '221', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(206, 206, '222', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(207, 207, '223', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(208, 208, '224', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(209, 209, '225', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(210, 210, '226', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(211, 211, '227', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(212, 212, '228', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(213, 213, '229', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(214, 214, '230', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(215, 215, '231', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(216, 216, '232', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(217, 217, '233', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(218, 218, '234', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(219, 219, '235', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(220, 220, '236', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(221, 221, '237', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(222, 222, '238', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(223, 223, '239', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(224, 224, '240', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:07', NULL),
(225, 225, '241', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(226, 226, '242', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(227, 227, '243', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(228, 228, '244', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(229, 229, '245', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(230, 230, '246', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(231, 231, '247', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(232, 232, '248', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(233, 233, '249', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(234, 234, '250', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '', 0, '7', '2', NULL, '2024-06-24 15:17:08', '2025-01-11 19:06:10'),
(235, 235, '251', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(236, 236, '252', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(237, 237, '253', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(238, 238, '254', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(239, 239, '255', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(240, 240, '256', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(241, 241, '257', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(242, 242, '258', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(243, 243, '259', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(244, 244, '260', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(245, 245, '261', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:08', '2025-01-20 12:35:35'),
(246, 246, '262', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(247, 247, '263', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(248, 248, '264', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(249, 249, '265', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(250, 250, '266', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:08', '2025-01-20 12:41:36'),
(251, 251, '267', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(252, 252, '268', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(253, 253, '269', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(254, 254, '270', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(255, 255, '271', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(256, 256, '272', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:08', '2025-01-11 18:52:40'),
(257, 257, '273', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(258, 258, '274', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(259, 259, '275', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(260, 260, '276', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(261, 261, '277', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(262, 262, '278', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(263, 263, '279', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(264, 264, '280', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(265, 265, '281', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(266, 266, '282,283', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', '7', NULL, '2024-06-24 15:17:08', '2024-06-24 15:17:08'),
(267, 267, '284,285', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', '7', NULL, '2024-06-24 15:17:08', '2024-06-24 15:17:08'),
(268, 268, '286,287', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:08', '2024-06-24 15:17:08'),
(269, 269, '288', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(270, 270, '289', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:08', '2025-01-25 12:00:37'),
(271, 271, '290', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(272, 272, '291', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(273, 273, '292', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:08', NULL),
(274, 274, '293', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:08', '2025-01-10 19:23:08'),
(275, 275, '294', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:09', '2025-01-20 12:35:05'),
(276, 276, '295', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(277, 277, '296', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(278, 278, '297', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(279, 279, '298', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(280, 280, '299', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(281, 281, '300', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(282, 282, '301,302', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:09', '2024-06-24 15:17:09'),
(283, 283, '303', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(284, 284, '304', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:09', '2025-01-20 12:38:04'),
(285, 285, '305', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(286, 286, '306', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(287, 287, '307', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(288, 288, '308', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(289, 289, '309', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(290, 290, '310,311,892', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:09', '2024-07-18 13:35:49'),
(291, 291, '312', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(292, 292, '313', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(293, 293, '314', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:09', '2025-01-11 18:52:01'),
(294, 294, '315', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(295, 295, '316', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(296, 296, '317', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(297, 297, '318', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(298, 298, '319', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(299, 299, '320', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(300, 300, '321', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(301, 301, '322', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(302, 302, '323', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(303, 303, '324', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(304, 304, '325', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(305, 305, '326', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(306, 306, '327,328', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:09', '2024-06-24 15:17:09'),
(307, 307, '329', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:09', '2025-01-11 18:49:03'),
(308, 308, '330', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(309, 309, '331', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(310, 310, '332', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(311, 311, '333', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(312, 312, '334', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(313, 313, '335', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(314, 314, '336', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(315, 315, '337', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(316, 316, '338', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(317, 317, '339', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:09', '2025-01-20 19:51:54'),
(318, 318, '340', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(319, 319, '341', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(320, 320, '342', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:09', NULL),
(321, 321, '343', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(322, 322, '344', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(323, 323, '345', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '', 0, '7', '2', NULL, '2024-06-24 15:17:10', '2025-01-11 18:55:48'),
(324, 324, '346', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(325, 325, '347', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:10', '2025-01-21 12:06:00'),
(326, 326, '348', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(327, 327, '349', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(328, 328, '350', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(329, 329, '351', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(330, 330, '352', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(331, 331, '353', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(332, 332, '354', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(333, 333, '355', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(334, 334, '356', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(335, 335, '357', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(336, 336, '358', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(337, 337, '359,360', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:10', '2024-06-24 15:17:10'),
(338, 338, '361', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(339, 339, '362', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(340, 340, '363', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(341, 341, '364', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(342, 342, '365', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(343, 343, '366', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(344, 344, '367', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(345, 345, '368', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(346, 346, '369', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(347, 347, '370', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(348, 348, '371', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(349, 349, '372', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(350, 350, '373,374', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', '7', NULL, '2024-06-24 15:17:10', '2024-06-24 15:17:10'),
(351, 351, '375', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(352, 352, '376', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(353, 353, '377', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(354, 354, '378', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(355, 355, '379', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(356, 356, '380,893', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:10', '2025-01-11 18:46:35'),
(357, 357, '381', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(358, 358, '382', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(359, 359, '383', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '', 0, '7', '2', NULL, '2024-06-24 15:17:10', '2025-01-11 18:56:39'),
(360, 360, '384', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(361, 361, '385', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(362, 362, '386,387', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:10', '2025-01-11 18:51:19'),
(363, 363, '388', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:10', NULL),
(364, 364, '389', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:10', '2025-01-20 12:34:00'),
(365, 365, '390,391', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', '7', NULL, '2024-06-24 15:17:10', '2024-06-24 15:17:11'),
(366, 366, '392', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(367, 367, '393,394', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:11', '2024-06-24 15:17:11'),
(368, 368, '395', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:11', '2025-01-20 14:07:28'),
(369, 369, '396', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(370, 370, '397', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '', 0, '7', '2', NULL, '2024-06-24 15:17:11', '2025-01-11 18:57:33'),
(371, 371, '398', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(372, 372, '399,399,399', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', '2', NULL, '2024-06-24 15:17:11', '2024-07-30 12:53:15'),
(373, 373, '400', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(374, 374, '401', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(375, 375, '402', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(376, 376, '403,404', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', '7', NULL, '2024-06-24 15:17:11', '2024-06-24 15:17:11'),
(377, 377, '405', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(378, 378, '406', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(379, 379, '407,408', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:11', '2024-06-24 15:17:11'),
(380, 380, '409', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(381, 381, '410', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(382, 382, '411,412,894', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:11', '2024-07-18 13:36:41'),
(383, 383, '413', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(384, 384, '414', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(385, 385, '415', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:11', '2025-01-11 18:49:47'),
(386, 386, '416', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(387, 387, '417', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(388, 388, '418', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(389, 389, '419', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(390, 390, '420', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(391, 391, '421', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL);
INSERT INTO `area_creation` (`area_creation_id`, `area_name_id`, `sub_area`, `taluk`, `district`, `state`, `pincode`, `enable`, `status`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date`) VALUES
(392, 392, '422', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(393, 393, '423', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(394, 394, '424', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(395, 395, '425', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(396, 396, '426', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(397, 397, '427', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(398, 398, '428', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(399, 399, '429,430', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:11', '2024-06-24 15:17:11'),
(400, 400, '431', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(401, 401, '432,433', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:11', '2024-06-24 15:17:11'),
(402, 402, '434', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:11', NULL),
(403, 403, '435', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(404, 404, '436', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(405, 405, '437', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(406, 406, '438', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(407, 407, '439', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(408, 408, '440', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(409, 409, '441', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(410, 410, '442', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(411, 411, '443', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(412, 412, '444', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(413, 413, '445', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(414, 414, '446', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(415, 415, '447', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:12', '2025-01-25 12:06:41'),
(416, 416, '448', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:12', '2025-01-20 12:41:04'),
(417, 417, '449', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(418, 418, '450,451', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:12', '2024-06-24 15:17:12'),
(419, 419, '452', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(420, 420, '453', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(421, 421, '454', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(422, 422, '455', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(423, 423, '456', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(424, 424, '457', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(425, 425, '458', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(426, 426, '459', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(427, 427, '460,895', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:12', '2024-07-18 13:37:10'),
(428, 428, '461', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(429, 429, '462', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(430, 430, '463', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:12', '2025-01-11 18:52:40'),
(431, 431, '464', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(432, 432, '465', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(433, 433, '466', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(434, 434, '467', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(435, 435, '468', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(436, 436, '469', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:12', '2025-01-20 12:43:12'),
(437, 437, '470,471', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:12', '2024-06-24 15:17:12'),
(438, 438, '472', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(439, 439, '473', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(440, 440, '474', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:12', '2025-01-20 12:37:31'),
(441, 441, '475', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:12', NULL),
(442, 442, '476', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:12', '2025-01-20 12:37:03'),
(443, 443, '477,478', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', '7', NULL, '2024-06-24 15:17:12', '2024-06-24 15:17:12'),
(444, 444, '479', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(445, 445, '480', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(446, 446, '481', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(447, 447, '482', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:13', '2025-01-25 12:18:05'),
(448, 448, '483', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:13', '2025-01-11 18:50:38'),
(449, 449, '484', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(450, 450, '485', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(451, 451, '486', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(452, 452, '487', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(453, 453, '488', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(454, 454, '489', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(455, 455, '490', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(456, 456, '491', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(457, 457, '492', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(458, 458, '493', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(459, 459, '494', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:13', '2025-01-18 12:00:35'),
(460, 460, '495', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(461, 461, '496', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '', 0, '7', '2', NULL, '2024-06-24 15:17:13', '2025-01-11 18:54:47'),
(462, 462, '497', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(463, 463, '498', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(464, 464, '499', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(465, 465, '500', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(466, 466, '501', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(467, 467, '502', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(468, 468, '503', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(469, 469, '504', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(470, 470, '505,1616', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:13', '2025-01-11 09:39:35'),
(471, 471, '506', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(472, 472, '507', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(473, 473, '508', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(474, 474, '509', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:13', '2025-01-11 18:44:46'),
(475, 475, '510', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:13', '2025-01-20 12:34:29'),
(476, 476, '511', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(477, 477, '512', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(478, 478, '513', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(479, 479, '514', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(480, 480, '515', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(481, 481, '516', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(482, 482, '517', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(483, 483, '518', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(484, 484, '519', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(485, 485, '520', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(486, 486, '521', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(487, 487, '522', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:13', NULL),
(488, 488, '523', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(489, 489, '524', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(490, 490, '525', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(491, 491, '526,527', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:14', '2024-06-24 15:17:14'),
(492, 492, '528', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(493, 493, '529', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(494, 494, '530', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(495, 495, '531', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:14', '2025-01-25 12:09:19'),
(496, 496, '532', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(497, 497, '533', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(498, 498, '534', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(499, 499, '535', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(500, 500, '536', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(501, 501, '537', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(502, 502, '538', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(503, 503, '539', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(504, 504, '540', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(505, 505, '541', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(506, 506, '542,542,542', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', '2', NULL, '2024-06-24 15:17:14', '2024-07-30 12:53:18'),
(507, 507, '543', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(508, 508, '544', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(509, 509, '545', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(510, 510, '546', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(511, 511, '547', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(512, 512, '548', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(513, 513, '549', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(514, 514, '550', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(515, 515, '551', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(516, 516, '552', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(517, 517, '553', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(518, 518, '554', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(519, 519, '555,556,557', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:14', '2024-06-24 15:17:14'),
(520, 520, '558', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(521, 521, '559', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(522, 522, '560', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(523, 523, '561', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(524, 524, '562', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(525, 525, '563', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(526, 526, '564', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(527, 527, '565', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:14', '2025-01-18 11:59:46'),
(528, 528, '566', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(529, 529, '567', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:14', NULL),
(530, 530, '568', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(531, 531, '569', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(532, 532, '570', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(533, 533, '571', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:15', '2025-01-25 12:08:39'),
(534, 534, '572', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(535, 535, '573', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(536, 536, '574', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(537, 537, '575', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(538, 538, '576', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(539, 539, '577', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(540, 540, '578', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(541, 541, '579', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(542, 542, '580', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(543, 543, '581', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(544, 544, '582,583,584', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', '7', NULL, '2024-06-24 15:17:15', '2024-06-24 15:17:15'),
(545, 545, '585', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(546, 546, '586', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(547, 547, '587', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(548, 548, '588', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(549, 549, '589,898', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:15', '2024-07-18 13:38:16'),
(550, 550, '590', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(551, 551, '591', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(552, 552, '592', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(553, 553, '593', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(554, 554, '594', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(555, 555, '595', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(556, 556, '596', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(557, 557, '597', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(558, 558, '598', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(559, 559, '599,1483,1484,1485,1463,1486,1487,1488,1489,1490,1491,1492,1493,1494,1495,1496,1497,1498,1499,1500,1501,1502,1503,1504,1505,1506,1507,1508,1509,1510,1511,1512,1474,1513,1514,1515,1516,1517,1518,1519,1520,1521,1522,451,1523,1524,1525,1526,1527,1528,1529,15', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '2', NULL, '2024-06-24 15:17:15', '2024-07-30 14:59:50'),
(560, 560, '600', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(561, 561, '601', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(562, 562, '602', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(563, 563, '603', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(564, 564, '604', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(565, 565, '605', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(566, 566, '606', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(567, 567, '607', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:15', NULL),
(568, 568, '608', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(569, 569, '609', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:16', '2025-01-25 12:10:26'),
(570, 570, '610', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(571, 571, '611,612', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:16', '2024-06-24 15:17:16'),
(572, 572, '613', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(573, 573, '614', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(574, 574, '615', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(575, 575, '616', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(576, 576, '617', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(577, 577, '618,619,620,621,622,623,624,1336,1337,1338,1339,1340,1341,1342,1343,1344,1345,1346,1347,1348,1349,1350,1351,1352,1353,1354,177,1355,1356,1357,1358,1359,1360,1361,1362,1363,1364,1365,1366,1367,1368,1369,1370,620,1371,1372,623,1373,1374,1375,1376,1377,1378,', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', '2', NULL, '2024-06-24 15:17:16', '2024-07-30 14:59:52'),
(578, 578, '625', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(579, 579, '626', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(580, 580, '627,628', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:16', '2025-01-25 12:17:29'),
(581, 581, '629,630', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', '7', NULL, '2024-06-24 15:17:16', '2024-06-24 15:17:16'),
(582, 582, '631', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(583, 1459, '1747', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '606801', '', 0, '7', '2', NULL, '2024-06-24 15:17:16', '2025-02-07 16:59:58'),
(584, 584, '633', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(585, 585, '634,635', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:16', '2024-06-24 15:17:16'),
(586, 586, '636', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(587, 587, '637', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(588, 588, '638', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(589, 589, '639', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(590, 590, '640', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(591, 591, '641', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(592, 592, '642', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(593, 593, '643', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(594, 594, '644', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(595, 595, '645', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(596, 596, '646', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:16', '2025-01-20 12:42:13'),
(597, 597, '647', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(598, 598, '648', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(599, 599, '649', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(600, 600, '650', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(601, 601, '651', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(602, 602, '652', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(603, 603, '653', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(604, 604, '654', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:16', NULL),
(605, 605, '655', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '', 0, '7', '2', NULL, '2024-06-24 15:17:16', '2025-01-11 18:55:19'),
(606, 606, '656', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(607, 607, '657', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:17', '2025-01-11 18:49:22'),
(608, 608, '658', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:17', '2025-01-25 12:11:21'),
(609, 609, '659', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(610, 610, '660,661,662', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:17', '2024-06-24 15:17:17'),
(611, 611, '663', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(612, 612, '664', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(613, 613, '665', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(614, 614, '666,667', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:17', '2024-06-24 15:17:17'),
(615, 615, '668', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(616, 616, '669', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(617, 617, '670', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(618, 618, '671', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(619, 619, '672', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(620, 620, '673', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(621, 621, '674', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(622, 622, '675', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(623, 623, '676', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(624, 624, '677', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(625, 625, '678', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(626, 626, '679', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(627, 627, '680', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(628, 628, '681,682,683', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', '7', NULL, '2024-06-24 15:17:17', '2024-06-24 15:17:17'),
(629, 629, '684', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(630, 630, '685', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(631, 631, '686', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(632, 632, '687', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(633, 633, '688', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(634, 634, '689', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(635, 635, '690', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(636, 636, '691', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(637, 637, '692', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(638, 638, '693', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(639, 639, '694', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(640, 640, '695', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(641, 641, '696', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(642, 642, '697', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:17', NULL),
(643, 643, '698', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '7', '2', NULL, '2024-06-24 15:17:18', '2025-01-11 18:47:03'),
(644, 644, '699', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(645, 645, '700', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(646, 646, '701', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(647, 647, '702', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(648, 648, '703', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(649, 649, '704', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(650, 650, '705', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(651, 651, '706', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(652, 652, '707', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(653, 653, '708', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(654, 654, '709', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(655, 655, '710', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(656, 656, '711,711,711', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', '2', NULL, '2024-06-24 15:17:18', '2024-07-30 12:53:12'),
(657, 657, '712', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(658, 658, '713', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(659, 659, '714', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(660, 660, '715', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(661, 661, '716,717', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', '7', NULL, '2024-06-24 15:17:18', '2024-06-24 15:17:18'),
(662, 662, '718', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(663, 663, '719', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(664, 664, '720', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(665, 665, '721', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(666, 666, '722', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(667, 667, '723', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(668, 668, '724', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(669, 669, '725', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '', 0, '7', '2', NULL, '2024-06-24 15:17:18', '2025-01-25 12:15:57'),
(670, 670, '726', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(671, 671, '727', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(672, 672, '728', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(673, 673, '729', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(674, 674, '730', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(675, 675, '731', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(676, 676, '732', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(677, 677, '733', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(678, 678, '734', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(679, 679, '735', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(680, 680, '736', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(681, 681, '737', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(682, 682, '738', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(683, 683, '739', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(684, 684, '740', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(685, 685, '741', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(686, 686, '742', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(687, 687, '743', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(688, 688, '744', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:18', NULL),
(689, 689, '745', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(690, 690, '746', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(691, 691, '747', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(692, 692, '748', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(693, 693, '749', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(694, 694, '750', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(695, 695, '751', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(696, 696, '752,753', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '7', NULL, '2024-06-24 15:17:19', '2024-06-24 15:17:19'),
(697, 697, '754', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(698, 698, '755', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(699, 699, '756', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(700, 700, '757', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(701, 701, '758', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(702, 702, '759', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(703, 703, '760', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(704, 704, '761', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(705, 705, '762', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(706, 706, '763', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(707, 707, '764', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(708, 708, '765,766', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', '7', NULL, '2024-06-24 15:17:19', '2024-06-24 15:17:19'),
(709, 709, '767', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(710, 710, '768', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(711, 711, '769', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(712, 712, '770', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(713, 713, '771', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(714, 714, '772', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(715, 715, '773', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(716, 716, '774', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(717, 717, '775', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(718, 718, '776', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '', 0, '7', '2', NULL, '2024-06-24 15:17:19', '2025-01-11 18:58:22'),
(719, 719, '777', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(720, 720, '778', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(721, 721, '779', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(722, 722, '780', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(723, 723, '781', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(724, 724, '782,896', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '7', '2', NULL, '2024-06-24 15:17:19', '2024-07-18 13:37:31'),
(725, 725, '783', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(726, 726, '784', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(727, 727, '785', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(728, 728, '786', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(729, 729, '787', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:19', NULL),
(730, 730, '788', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(731, 731, '789,897', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '', 0, '7', '2', NULL, '2024-06-24 15:17:20', '2024-07-18 13:37:52'),
(732, 732, '790', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(733, 733, '791', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(734, 734, '792', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(735, 735, '793', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(736, 736, '794', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(737, 737, '795', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(738, 738, '796', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(739, 739, '797', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(740, 740, '798', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(741, 741, '799', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(742, 742, '800', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(743, 743, '801', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(744, 744, '802', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(745, 745, '803', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(746, 746, '804', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(747, 747, '805', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(748, 748, '806', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(749, 749, '807', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(750, 750, '808', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(751, 751, '809', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(752, 752, '810', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(753, 753, '811', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(754, 754, '812', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(755, 755, '813', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(756, 756, '814', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(757, 757, '815', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(758, 758, '816', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(759, 759, '817', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(760, 760, '818', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(761, 761, '819', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(762, 762, '820', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(763, 763, '821', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(764, 764, '822', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(765, 765, '823', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(766, 766, '824', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(767, 767, '825', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(768, 768, '826', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(769, 769, '827', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(770, 770, '828', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(771, 771, '829', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(772, 772, '830', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(773, 773, '831', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(774, 774, '832', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:20', NULL),
(775, 775, '833', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(776, 776, '834', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(777, 777, '835', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(778, 778, '836', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(779, 779, '837', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(780, 780, '838', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(781, 781, '839', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL);
INSERT INTO `area_creation` (`area_creation_id`, `area_name_id`, `sub_area`, `taluk`, `district`, `state`, `pincode`, `enable`, `status`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date`) VALUES
(782, 782, '840,841', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', '7', NULL, '2024-06-24 15:17:21', '2024-06-24 15:17:21'),
(783, 783, '842', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(784, 784, '843', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(785, 785, '844', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(786, 786, '845', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(787, 787, '846', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(788, 788, '847', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(789, 789, '848', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(790, 790, '849,311,849', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '2', NULL, '2024-06-24 15:17:21', '2024-07-30 12:53:12'),
(791, 791, '850', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(792, 792, '851', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(793, 793, '852', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(794, 794, '853', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(795, 795, '854', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(796, 796, '855', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(797, 797, '856', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(798, 798, '857', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(799, 799, '858', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(800, 800, '859', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(801, 801, '860', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(802, 802, '861', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(803, 803, '862', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '', 0, '7', '2', NULL, '2024-06-24 15:17:21', '2025-01-11 18:59:09'),
(804, 804, '863', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(805, 805, '864', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(806, 806, '865', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(807, 807, '866', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(808, 808, '867', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(809, 809, '868', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(810, 810, '869', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(811, 811, '870', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(812, 812, '871', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(813, 813, '872', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(814, 814, '873', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(815, 815, '874', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(816, 816, '875', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:21', NULL),
(817, 817, '876', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:22', NULL),
(818, 818, '877', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:22', NULL),
(819, 819, '878', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:22', NULL),
(820, 820, '879', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:22', NULL),
(821, 821, '880,880,880', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', '2', NULL, '2024-06-24 15:17:22', '2024-07-30 12:53:13'),
(822, 822, '881', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:22', NULL),
(823, 823, '882', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:22', NULL),
(824, 824, '883', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:22', NULL),
(825, 825, '884', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:22', NULL),
(826, 826, '885', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:22', NULL),
(827, 827, '886', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:22', NULL),
(828, 828, '887', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:22', NULL),
(829, 829, '888', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:22', NULL),
(830, 830, '889', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '7', NULL, NULL, '2024-06-24 15:17:22', NULL),
(831, 831, '899', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:13', NULL),
(832, 832, '900,900,900', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', '2', NULL, '2024-07-18 14:01:13', '2024-07-30 12:53:11'),
(833, 833, '901,901,901', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', '2', NULL, '2024-07-18 14:01:13', '2024-07-30 12:53:11'),
(834, 834, '902', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:13', NULL),
(835, 835, '903', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:13', NULL),
(836, 836, '904', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:13', NULL),
(837, 837, '905', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:13', NULL),
(838, 838, '906', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:13', NULL),
(839, 839, '907', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:13', NULL),
(840, 840, '908', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '2', '2', NULL, '2024-07-18 14:01:13', '2025-01-10 19:24:50'),
(841, 841, '909', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:13', NULL),
(842, 842, '910', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:13', NULL),
(843, 843, '911,911,911', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', '2', NULL, '2024-07-18 14:01:13', '2024-07-30 12:53:14'),
(844, 844, '912,912,912', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', '2', NULL, '2024-07-18 14:01:13', '2024-07-30 12:53:14'),
(845, 845, '913,913,913', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', '2', NULL, '2024-07-18 14:01:13', '2024-07-30 12:53:15'),
(846, 846, '914', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:14', NULL),
(847, 847, '915,915,915', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', '2', NULL, '2024-07-18 14:01:14', '2024-07-30 12:53:15'),
(848, 848, '916', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:14', NULL),
(849, 849, '917', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:14', NULL),
(850, 850, '918,918,918', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', '2', NULL, '2024-07-18 14:01:14', '2024-07-30 12:53:17'),
(851, 851, '919,919,919', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', '2', NULL, '2024-07-18 14:01:14', '2024-07-30 12:53:17'),
(852, 852, '920', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:14', NULL),
(853, 853, '921,921,921', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', '2', NULL, '2024-07-18 14:01:14', '2024-07-30 12:53:17'),
(854, 854, '922', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:14', NULL),
(855, 855, '923', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:14', NULL),
(856, 856, '924', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:14', NULL),
(857, 857, '925', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:14', NULL),
(858, 858, '926,926,926', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', '2', NULL, '2024-07-18 14:01:14', '2024-07-30 12:53:19'),
(859, 859, '927', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-07-18 14:01:14', NULL),
(860, 860, '928', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(861, 861, '929', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(862, 862, '930', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(863, 863, '931', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(864, 864, '932', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(865, 865, '933', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(866, 866, '934', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '2', '2', NULL, '2024-07-30 14:59:38', '2025-01-25 12:07:29'),
(867, 867, '935', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(868, 868, '936', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(869, 869, '937', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(870, 870, '938', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(871, 871, '939', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(872, 872, '940', 'Uthiramerur', 'Tiruvannamalai', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(873, 873, '941', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(874, 874, '942', 'Uthiramerur', 'Tiruvannamalai', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(875, 875, '943', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(876, 876, '944', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(877, 877, '945', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(878, 878, '946', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(879, 879, '947', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:38', NULL),
(880, 880, '948', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(881, 881, '949', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(882, 882, '950', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(883, 883, '951', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(884, 884, '952', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(885, 885, '953', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(886, 886, '954', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(887, 887, '955', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(888, 888, '956', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(889, 889, '957', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(890, 890, '958', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(891, 891, '959', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(892, 892, '987', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(893, 893, '988', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(894, 894, '989', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(895, 895, '990', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(896, 896, '991', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(897, 897, '992', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(898, 898, '993', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:39', NULL),
(899, 899, '994', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(900, 900, '995', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(901, 901, '996', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(902, 902, '997', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(903, 903, '998', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604407', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(904, 904, '999', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(905, 905, '1000', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(906, 906, '1001', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(907, 907, '1002', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(908, 908, '1003', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(909, 909, '1004', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(910, 910, '1005', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(911, 911, '1006', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(912, 912, '1007', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(913, 913, '1008', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(914, 914, '1009', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(915, 915, '1010', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(916, 916, '1011', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(917, 917, '1012', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(918, 918, '1013', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(919, 919, '1014', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(920, 920, '1015', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(921, 921, '1016', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(922, 922, '1017', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(923, 923, '1018', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(924, 924, '1019', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(925, 925, '1020', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(926, 926, '1021', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(927, 927, '1022', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(928, 928, '1023', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 1, '2', NULL, '2', '2024-07-30 14:59:40', NULL),
(929, 929, '1024', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(930, 930, '1025', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(931, 931, '1026', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(932, 932, '1027', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(933, 933, '1028', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(934, 934, '1029', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(935, 935, '1030', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(936, 936, '1031', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(937, 937, '1032', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(938, 938, '1033', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:40', NULL),
(939, 939, '1034', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(940, 940, '1035', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(941, 941, '1036', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(942, 942, '1037', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(943, 943, '1038', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(944, 944, '1039', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(945, 945, '1040', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(946, 946, '1041', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(947, 947, '1042', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(948, 948, '1043', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(949, 949, '1044', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(950, 950, '1045', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(951, 951, '1046', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(952, 952, '1047', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '2', '2', NULL, '2024-07-30 14:59:41', '2025-01-25 12:13:20'),
(953, 953, '1048', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(954, 954, '1049', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(955, 955, '1050', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(956, 956, '1051', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(957, 957, '1052', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(958, 958, '1053', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(959, 959, '1054', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(960, 960, '1055', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(961, 961, '1056', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '', 0, '2', '2', NULL, '2024-07-30 14:59:41', '2025-01-11 18:57:10'),
(962, 962, '1057', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(963, 963, '1058', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(964, 964, '1059', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(965, 965, '1060', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(966, 966, '1061', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(967, 967, '1062', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(968, 968, '1063', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(969, 969, '1064', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(970, 970, '1065', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(971, 971, '1066', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(972, 972, '1067', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(973, 973, '1068', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(974, 974, '1069', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:41', NULL),
(975, 975, '1070', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(976, 976, '1071', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(977, 977, '1072', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(978, 978, '1073', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(979, 979, '1074', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(980, 980, '1075', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(981, 981, '1076', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(982, 982, '1077', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(983, 983, '1078', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(984, 984, '1079', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(985, 985, '1080', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(986, 986, '1081', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(987, 987, '1082', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(988, 988, '1083', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(989, 989, '1084', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(990, 990, '1085', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(991, 991, '1086', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(992, 992, '1087', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(993, 993, '1088', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(994, 994, '1089', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(995, 995, '1090', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(996, 996, '1091', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(997, 997, '1092', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(998, 998, '1093', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(999, 999, '1094', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(1000, 1000, '1095', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(1001, 1001, '1096,1097', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', '2', NULL, '2024-07-30 14:59:42', '2024-07-30 14:59:42'),
(1002, 1002, '1098', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(1003, 1003, '1099', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(1004, 1004, '1100', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(1005, 1005, '1101', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(1006, 1006, '1102', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(1007, 1007, '1103', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(1008, 1008, '1104', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(1009, 1009, '1105', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(1010, 1010, '1106', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(1011, 1011, '1107', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:42', NULL),
(1012, 1012, '1108', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '', 0, '2', '2', NULL, '2024-07-30 14:59:42', '2025-01-11 18:56:58'),
(1013, 1013, '1109', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1014, 1014, '1110', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1015, 1015, '1111', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1016, 1016, '1112,1113,1114,1115', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', '2', NULL, '2024-07-30 14:59:43', '2024-07-30 14:59:43'),
(1017, 1017, '1116', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1018, 1018, '1117', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1019, 1019, '1118', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1020, 1020, '1119', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1021, 1021, '1120', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1022, 1022, '1121', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1023, 1023, '1122', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1024, 1024, '1123', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1025, 1025, '1124', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1026, 1026, '1125', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1027, 1027, '1126', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1028, 1028, '1127', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1029, 1029, '1128', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1030, 1030, '1129', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 1, '2', NULL, '70', '2024-07-30 14:59:43', NULL),
(1031, 1031, '1130', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1032, 1032, '1131', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1033, 1033, '1132', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1034, 1034, '1133,1664', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '2', '2', NULL, '2024-07-30 14:59:43', '2025-01-13 10:58:31'),
(1035, 1035, '1134', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1036, 1036, '1135', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1037, 1037, '1136', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1038, 1038, '1137', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1039, 1039, '1138', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1040, 1040, '1139', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1041, 1041, '1140', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1042, 1042, '1141', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:43', NULL),
(1043, 1043, '1142', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1044, 1044, '1143', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1045, 1045, '1144', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1046, 1046, '1145,1146', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', '2', NULL, '2024-07-30 14:59:44', '2024-07-30 14:59:44'),
(1047, 1047, '1147', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1048, 1048, '1148', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1049, 1049, '1149', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1050, 1050, '1150', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1051, 1051, '1151', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1052, 1052, '1152', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1053, 1053, '1153', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1054, 1054, '1154', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1055, 1055, '1155', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1056, 1056, '1156', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1057, 1057, '1157', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1058, 1058, '1158', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1059, 1059, '1159', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1060, 1060, '1160', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1061, 1061, '1161', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1062, 1062, '1162', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1063, 1063, '1163', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1064, 1064, '1164', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1065, 1065, '1165', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1066, 1066, '1166', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1067, 1067, '1167', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1068, 1068, '1168', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1069, 1069, '1169', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1070, 1070, '1170', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1071, 1071, '1171', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1072, 1072, '1172', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1073, 1073, '1173', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1074, 1074, '1174', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1075, 1075, '1175', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1076, 1076, '1176', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1077, 1077, '1177', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1078, 1078, '1178', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1079, 1079, '1179', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1080, 1080, '1180', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1081, 1081, '1181', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:44', NULL),
(1082, 1082, '1182', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1083, 1083, '1183', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1084, 1084, '1184', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1085, 1085, '1185', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1086, 1086, '1186', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1087, 1087, '1187', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1088, 1088, '1188,1189', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', '2', NULL, '2024-07-30 14:59:45', '2024-07-30 14:59:45'),
(1089, 1089, '1190', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1090, 1090, '1191,1192', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', '2', NULL, '2024-07-30 14:59:45', '2024-07-30 14:59:45'),
(1091, 1091, '1193', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1092, 1092, '1194', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1093, 1093, '1195', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1094, 1094, '1196', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1095, 1095, '1197', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1096, 1096, '1198', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1097, 1097, '1199', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1098, 1098, '1200', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1099, 1099, '1201,1202', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', '2', NULL, '2024-07-30 14:59:45', '2024-07-30 14:59:45'),
(1100, 1100, '1203', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1101, 1101, '1204', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1102, 1102, '1205', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1103, 1103, '1206', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1104, 1104, '1207,1208', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', '2', NULL, '2024-07-30 14:59:45', '2024-07-30 14:59:45'),
(1105, 1105, '1209', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1106, 1106, '1210', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1107, 1107, '1211', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1108, 1108, '1212', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1109, 1109, '1213', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1110, 1110, '1214', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1111, 1111, '1215', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1112, 1112, '1216', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1113, 1113, '1217', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1114, 1114, '1218', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1115, 1115, '1219', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1116, 1116, '1220', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1117, 1117, '1221', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1118, 1118, '1222', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:45', NULL),
(1119, 1119, '1223', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1120, 1120, '1224', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1121, 1121, '1225', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1122, 1122, '1226', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1123, 1123, '1227', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1124, 1124, '1228', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1125, 1125, '1229', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1126, 1126, '1230', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1127, 1127, '1231', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1128, 1128, '1232', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1129, 1129, '1233', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1130, 1130, '1234', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1131, 1131, '1235', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1132, 1132, '1236', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1133, 1133, '1237', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1134, 1134, '1238', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1135, 1135, '1239', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1136, 1136, '1240', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1137, 1137, '1241', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1138, 1138, '1242', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1139, 1139, '1243', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1140, 1140, '1244', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1141, 1141, '1245', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1142, 1142, '1246', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1143, 1143, '1247', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1144, 1144, '1248', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1145, 1145, '1249', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '', 0, '2', '2', NULL, '2024-07-30 14:59:46', '2025-01-21 15:24:01'),
(1146, 1146, '1250', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1147, 1147, '1251', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1148, 1148, '1252', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1149, 1149, '1253', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1150, 1150, '1254', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1151, 1151, '1255', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1152, 1152, '1256', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1153, 1153, '1257', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:46', NULL),
(1154, 1154, '1258', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1155, 1155, '1259', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1156, 1156, '1260', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1157, 1157, '1261', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1158, 1158, '1262', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1159, 1159, '1263', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1160, 1160, '1264', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1161, 1161, '1265', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1162, 1162, '1266', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1163, 1163, '1267', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1164, 1164, '1268', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1165, 1165, '1269', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1166, 1166, '1270', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1167, 1167, '1271', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1168, 1168, '1272', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1169, 1169, '1273', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1170, 1170, '1274', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1171, 1171, '1275', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1172, 1172, '1276', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL);
INSERT INTO `area_creation` (`area_creation_id`, `area_name_id`, `sub_area`, `taluk`, `district`, `state`, `pincode`, `enable`, `status`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date`) VALUES
(1173, 1173, '1277', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1174, 1174, '1278', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1175, 1175, '1279', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1176, 1176, '1280', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1177, 1177, '1281', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1178, 1178, '1282', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1179, 1179, '1283', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1180, 1180, '1284', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1181, 1181, '1285', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1182, 1182, '1286', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1183, 1183, '1287', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1184, 1184, '1288,1652', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '2', '2', NULL, '2024-07-30 14:59:47', '2025-01-13 10:58:31'),
(1185, 1185, '1289', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1186, 1186, '1290', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:47', NULL),
(1187, 1187, '1291', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1188, 1188, '1292', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1189, 1189, '1293', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1190, 1190, '1294', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1191, 1191, '1295', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1192, 1192, '1296', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1193, 1193, '1297', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1194, 1194, '1298', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1195, 1195, '1299', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1196, 1196, '1300', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1197, 1197, '1301', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1198, 1198, '1302', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1199, 1199, '1303', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1200, 1200, '1304', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1201, 1201, '1305', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1202, 1202, '1306', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1203, 1203, '1307', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1204, 1204, '1308', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1205, 1205, '1309', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1206, 1206, '1310', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1207, 1207, '1311', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1208, 1208, '1312', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1209, 1209, '1313', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1210, 1210, '1314', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1211, 1211, '1315', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1212, 1212, '1316', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1213, 1213, '1317', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1214, 1214, '1318', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1215, 1215, '1319', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1216, 1216, '1320', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1217, 1217, '1321', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1218, 1218, '1322', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1219, 1219, '1323', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1220, 1220, '1324', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1221, 1221, '1325', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1222, 1222, '1326', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1223, 1223, '1327', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:48', NULL),
(1224, 1224, '1328', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:49', NULL),
(1225, 1225, '1329', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:49', NULL),
(1226, 1226, '1330', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:49', NULL),
(1227, 1227, '1331', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:49', NULL),
(1228, 1228, '1332', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:49', NULL),
(1229, 1229, '1333', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:49', NULL),
(1230, 1230, '1334', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:49', NULL),
(1231, 1231, '1335,1336', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', '2', NULL, '2024-07-30 14:59:49', '2024-07-30 14:59:49'),
(1232, 1232, '1337', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:49', NULL),
(1233, 1233, '1396', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:50', NULL),
(1234, 1234, '1397', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:50', NULL),
(1235, 1235, '1398', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:50', NULL),
(1236, 1236, '1399', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:50', NULL),
(1237, 1237, '1400', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:50', NULL),
(1238, 1238, '1401', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:50', NULL),
(1239, 1239, '1402', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:50', NULL),
(1240, 1240, '1403', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:50', NULL),
(1241, 1241, '1404', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:50', NULL),
(1242, 1242, '1405', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:50', NULL),
(1243, 1243, '1406', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:50', NULL),
(1244, 1244, '1407', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:50', NULL),
(1245, 1245, '1408', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:50', NULL),
(1246, 1246, '1409', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:50', NULL),
(1247, 1247, '1410', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:50', NULL),
(1248, 1248, '1534', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:52', NULL),
(1249, 1249, '1535', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:52', NULL),
(1250, 1250, '1536', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:52', NULL),
(1251, 1251, '1537', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:52', NULL),
(1252, 1252, '1538', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:52', NULL),
(1253, 1253, '1539', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:52', NULL),
(1254, 1254, '1540', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:52', NULL),
(1255, 1255, '1541', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:52', NULL),
(1256, 1256, '1542', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1257, 1257, '1543', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1258, 1258, '1544', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1259, 1259, '1545', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1260, 1260, '1546', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1261, 1261, '1547', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1262, 1262, '1548', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1263, 1263, '1549', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1264, 1264, '1550', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1265, 1265, '1551', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1266, 1266, '1552', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1267, 1267, '1553', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1268, 1268, '1554', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1269, 1269, '1555', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1270, 1270, '1556', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1271, 1271, '1557', 'Cheyyur', 'Chengalpattu', 'TamilNadu', '621002', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1272, 1272, '1558', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1273, 1273, '1559', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1274, 1274, '1560', 'Maduranthakam', 'Chengalpattu', 'TamilNadu', '603306', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1275, 1275, '1561', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-07-30 14:59:53', NULL),
(1277, 1277, '1563', 'Chetpet', 'Thiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-08-07 11:03:17', NULL),
(1278, 1278, '1564', 'Chetpet', 'Thiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-08-07 11:03:17', NULL),
(1279, 1279, '1565', 'Vandavasi', 'Thiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-08-07 11:03:17', NULL),
(1280, 1280, '1566', 'Polur', 'Thiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-08-07 11:03:17', NULL),
(1281, 1281, '1567', 'Polur', 'Thiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-08-07 11:03:17', NULL),
(1282, 1282, '1568', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '', 0, '2', '2', NULL, '2024-08-07 11:03:18', '2025-01-11 10:28:10'),
(1283, 1283, '1569', 'Polur', 'Thiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-08-07 11:03:18', NULL),
(1284, 1284, '1570', 'Vandavasi', 'Thiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-08-07 11:03:18', NULL),
(1285, 1285, '1571', 'Chetpet', 'Thiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-08-07 11:03:18', NULL),
(1286, 1286, '1572', 'Polur', 'Thiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-08-07 11:03:18', NULL),
(1287, 1287, '1573', 'Vandavasi', 'Thiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-08-07 11:03:18', NULL),
(1288, 1288, '1574', 'Polur', 'Thiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-08-07 11:03:18', NULL),
(1289, 1289, '1575', 'Polur', 'Thiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-08-07 11:03:18', NULL),
(1290, 1290, '1576', 'Polur', 'Thiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-08-07 11:03:18', NULL),
(1291, 1291, '1577', 'Polur', 'Thiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-08-07 11:03:18', NULL),
(1292, 1292, '1578', 'Polur', 'Thiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-08-07 11:03:18', NULL),
(1293, 1293, '1579', 'Polur', 'Thiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-08-07 11:03:18', NULL),
(1294, 1294, '1580', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-09-19 15:06:02', NULL),
(1295, 1295, '1581', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-09-19 15:09:11', NULL),
(1296, 1296, '1582', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-09-19 15:10:19', NULL),
(1297, 1297, '1583', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-09-19 15:11:25', NULL),
(1298, 1298, '1584', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-09-19 15:12:26', NULL),
(1299, 1299, '1585', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-09-19 15:13:32', NULL),
(1300, 1300, '1586', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-09-19 15:14:28', NULL),
(1301, 1301, '1587', 'Vandavasi', 'Thiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-10-05 10:41:14', NULL),
(1302, 1302, '1588', 'Chetpet', 'Thiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-10-05 10:41:14', NULL),
(1303, 1303, '1589', 'Chetpet', 'Thiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2024-10-05 10:41:15', NULL),
(1304, 1304, '1590', 'Polur', 'Thiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-10-05 10:41:15', NULL),
(1305, 1305, '1591', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-10-05 10:41:15', NULL),
(1306, 1306, '1592', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2024-10-05 10:41:15', NULL),
(1307, 1307, '1593', 'Vandavasi', 'Tiruvanamalai', 'Tamil Nadu', '604408', '0', 0, '2', NULL, NULL, '2024-11-06 18:44:32', NULL),
(1308, 1308, '1594', 'Chetpet', 'Tiruvanamalai', 'Tamil Nadu', '606801', '0', 0, '2', NULL, NULL, '2024-11-06 18:44:32', NULL),
(1309, 1309, '1595', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '', 0, '2', '2', NULL, '2024-11-06 18:44:32', '2025-01-10 19:41:48'),
(1310, 1310, '1596', 'Chetpet', 'Tiruvanamalai', 'Tamil Nadu', '606801', '0', 0, '2', NULL, NULL, '2024-11-06 18:44:32', NULL),
(1311, 1311, '1597', 'Chetpet', 'Tiruvanamalai', 'Tamil Nadu', '606801', '0', 0, '2', NULL, NULL, '2024-11-06 18:44:32', NULL),
(1312, 1312, '1598', 'Polur', 'Tiruvanamalai', 'Tamil Nadu', '606803', '0', 0, '2', NULL, NULL, '2024-11-06 18:44:32', NULL),
(1313, 1313, '1599', 'Polur', 'Tiruvanamalai', 'Tamil Nadu', '606803', '0', 0, '2', NULL, NULL, '2024-11-06 18:44:32', NULL),
(1314, 1314, '1600', 'Polur', 'Tiruvanamalai', 'Tamil Nadu', '606803', '0', 0, '2', NULL, NULL, '2024-11-06 18:44:32', NULL),
(1315, 1315, '1601', 'Polur', 'Tiruvanamalai', 'Tamil Nadu', '606803', '0', 0, '2', NULL, NULL, '2024-11-06 18:44:32', NULL),
(1316, 1316, '1602', 'Polur', 'Tiruvanamalai', 'Tamil Nadu', '606803', '0', 0, '2', NULL, NULL, '2024-11-06 18:44:32', NULL),
(1317, 1317, '1603', 'Polur', 'Tiruvanamalai', 'Tamil Nadu', '606803', '0', 0, '2', NULL, NULL, '2024-11-06 18:44:33', NULL),
(1318, 1318, '1604,1629', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '', 0, '2', '2', NULL, '2024-11-06 18:44:33', '2025-01-13 10:58:31'),
(1319, 1319, '1605', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-12-09 11:37:21', NULL),
(1320, 1320, '1606', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-12-09 11:38:25', NULL),
(1321, 1321, '1607', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2024-12-09 11:39:26', NULL),
(1322, 1322, '1608', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2024-12-09 11:40:19', NULL),
(1323, 1323, '1609', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-04 12:01:05', NULL),
(1324, 1324, '1610', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-04 12:01:05', NULL),
(1325, 1325, '1611', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-01-04 12:01:05', NULL),
(1326, 1326, '1612', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-01-04 12:01:05', NULL),
(1327, 1327, '1613', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-04 12:01:05', NULL),
(1328, 1328, '1614', 'Polur', 'Tiruvannamalai', 'TamilNadu', '604406', '0', 0, '2', NULL, NULL, '2025-01-08 13:43:27', NULL),
(1329, 1329, '1615', 'Polur', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-01-08 13:45:25', NULL),
(1330, 1330, '1617', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:30', NULL),
(1331, 1331, '1618', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:30', NULL),
(1332, 1332, '1619', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:30', NULL),
(1333, 1333, '1620', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:30', NULL),
(1334, 1334, '1621', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1335, 1335, '1622', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1336, 1336, '1623', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1337, 1337, '1624', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1338, 1338, '1625', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1339, 1339, '1626', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1340, 1340, '1627,1627', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', '2', NULL, '2025-01-13 10:58:31', '2025-01-13 10:58:31'),
(1341, 1341, '1628', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1342, 1342, '1630', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1343, 1343, '1631', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1344, 1344, '1632', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1345, 1345, '1633', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1346, 1346, '1634', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1347, 1347, '1635', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1348, 1348, '1636', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1349, 1349, '1637', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1350, 1350, '1638', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1351, 1351, '1639', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1352, 1352, '1640', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1353, 1353, '1641', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1354, 1354, '1642', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1355, 1355, '1643', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1356, 1356, '1644', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1357, 1357, '1645', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1358, 1358, '1646', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1359, 1359, '1647', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1360, 1360, '1648', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1361, 1361, '1649', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1362, 1362, '1650', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1363, 1363, '1651', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1364, 1364, '1653', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1365, 1365, '1654', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1366, 1366, '1655', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1367, 1367, '1656', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1368, 1368, '1657', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1369, 1369, '1658', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1370, 1370, '1659', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1371, 1371, '1660', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1372, 1372, '1661', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1373, 1373, '1662', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1374, 1374, '1663', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1375, 1375, '1665', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1376, 1376, '1666', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1377, 1377, '1667', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-13 10:58:31', NULL),
(1378, 1379, '1668', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', '2', NULL, '2025-01-18 12:03:39', '2025-02-03 10:02:13'),
(1379, 1378, '1669', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-18 12:04:42', NULL),
(1380, 1380, '1670', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-20 12:10:49', NULL),
(1381, 1381, '1671', 'Chetpet', 'Tiruvannamalai', 'Tamilnadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:10', NULL),
(1382, 1382, '1672', 'Chetpet', 'Tiruvannamalai', 'Tamilnadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:10', NULL),
(1383, 1383, '1673', 'Chetpet', 'Tiruvannamalai', 'Tamilnadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:10', NULL),
(1384, 1384, '1674', 'Chetpet', 'Tiruvannamalai', 'Tamilnadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:10', NULL),
(1385, 1385, '1675', 'Chetpet', 'Tiruvannamalai', 'Tamilnadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:10', NULL),
(1386, 1386, '1676', 'Chetpet', 'Tiruvannamalai', 'Tamilnadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:10', NULL),
(1387, 1387, '1677', 'Chetpet', 'Tiruvannamalai', 'Tamilnadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:10', NULL),
(1388, 1388, '1678', 'Chetpet', 'Tiruvannamalai', 'Tamilnadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:10', NULL),
(1389, 1389, '1679', 'Chetpet', 'Tiruvannamalai', 'Tamilnadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:10', NULL),
(1390, 1390, '1680', 'Chetpet', 'Tiruvannamalai', 'Tamilnadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:10', NULL),
(1391, 1391, '1681', 'Chetpet', 'Tiruvannamalai', 'Tamilnadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:10', NULL),
(1392, 1392, '1682', 'Chetpet', 'Tiruvannamalai', 'Tamilnadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:10', NULL),
(1393, 1403, '1693', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '', 0, '2', '2', '2', '2025-01-20 14:09:10', '2025-01-21 10:33:27'),
(1394, 1394, '1684', 'Chetpet', 'Tiruvannamalai', 'Tamilnadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:10', NULL),
(1395, 1395, '1685', 'Uthiramerur', 'Kancheepuram', 'Tamilnadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:10', NULL),
(1396, 1396, '1686', 'Uthiramerur', 'Kancheepuram', 'Tamilnadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:10', NULL),
(1397, 1397, '1687', 'Uthiramerur', 'Kancheepuram', 'Tamilnadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:11', NULL),
(1398, 1398, '1688', 'Uthiramerur', 'Kancheepuram', 'Tamilnadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:11', NULL),
(1399, 1399, '1689', 'Uthiramerur', 'Kancheepuram', 'Tamilnadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:11', NULL),
(1400, 1400, '1690', 'Uthiramerur', 'Kancheepuram', 'Tamilnadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:11', NULL),
(1401, 1401, '1691', 'Chetpet', 'Tiruvannamalai', 'Tamilnadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-20 14:09:11', NULL),
(1402, 1402, '1692', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', '2', NULL, '2025-01-20 19:59:22', '2025-02-03 10:02:13'),
(1403, 1405, '1695', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '', 0, '2', '2', NULL, '2025-01-21 12:52:21', '2025-01-21 12:52:35'),
(1404, 1407, '1697', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 1, '2', NULL, '2', '2025-01-22 15:22:29', NULL),
(1405, 1409, '1698', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-01-23 14:06:48', NULL),
(1406, 1411, '1700', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '', '0', 0, '2', NULL, NULL, '2025-01-23 15:09:30', NULL),
(1407, 1412, '1702', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:06', NULL),
(1408, 1413, '1703', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:06', NULL),
(1409, 1414, '1704', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:06', NULL),
(1410, 1415, '1705', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:06', NULL),
(1411, 1416, '1706', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:06', NULL),
(1412, 1417, '1707', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:06', NULL),
(1413, 1418, '1708', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:06', NULL),
(1414, 1419, '1709', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:06', NULL),
(1415, 1420, '1710', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:06', NULL),
(1416, 1421, '1711', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:06', NULL),
(1417, 1422, '1712', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:06', NULL),
(1418, 1423, '1713', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:06', NULL),
(1419, 1424, '1714', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:06', NULL),
(1420, 1425, '1715', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:06', NULL),
(1421, 1426, '1716', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:07', NULL),
(1422, 1427, '1717', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:07', NULL),
(1423, 1428, '1718', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:07', NULL),
(1424, 1429, '1719', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:07', NULL),
(1425, 1430, '1720', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:07', NULL),
(1426, 1431, '1721', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 10:42:07', NULL),
(1427, 1433, '1722', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 18:40:19', NULL),
(1428, 1434, '1723', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-01-25 18:41:50', NULL),
(1429, 1435, '1724', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '', 0, '2', '2', '2', '2025-01-28 12:36:33', '2025-01-28 12:37:58'),
(1430, 1436, '1725', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-02-03 10:02:13', NULL),
(1431, 1437, '1726', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-02-03 10:02:13', NULL),
(1432, 1438, '1727', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-02-03 10:02:13', NULL),
(1433, 1439, '1728', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-02-03 10:02:13', NULL),
(1434, 1440, '1729', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-02-03 10:02:13', NULL),
(1435, 1441, '1730', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-02-03 10:02:13', NULL),
(1436, 1442, '1731', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-02-03 10:02:13', NULL),
(1437, 1443, '1732', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-02-03 10:02:13', NULL),
(1438, 1444, '1733', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-02-03 10:02:13', NULL),
(1439, 1445, '1734', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-02-03 10:02:13', NULL),
(1440, 1446, '1735', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-02-03 10:02:13', NULL),
(1441, 1448, '1737', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-02-03 15:31:54', NULL),
(1442, 1449, '1738', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-02-03 15:31:54', NULL),
(1443, 1450, '1739', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-02-03 15:31:54', NULL),
(1444, 1451, '1740', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-02-03 15:31:54', NULL),
(1445, 1452, '1741', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-02-03 15:31:54', NULL),
(1446, 1453, '1742', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-02-03 15:31:54', NULL),
(1447, 1454, '1743', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-02-03 15:31:54', NULL),
(1448, 1455, '1744', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-02-03 15:31:54', NULL),
(1449, 1456, '1745', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-02-03 15:31:54', NULL),
(1450, 1457, '1746', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-02-03 15:31:54', NULL),
(1451, 1460, '1748', 'Vandavasi', 'Tiruvannamalai', 'Tamilnadu', '604408', '0', 0, '2', NULL, NULL, '2025-02-08 14:57:57', NULL),
(1452, 1461, '1749', 'Vandavasi', 'Tiruvannamalai', 'Tamilnadu', '604408', '0', 0, '2', NULL, NULL, '2025-02-08 14:57:57', NULL),
(1453, 1462, '1750', 'Vandavasi', 'Tiruvannamalai', 'Tamilnadu', '604408', '0', 0, '2', NULL, NULL, '2025-02-08 14:57:57', NULL),
(1454, 1463, '1751', 'Vandavasi', 'Tiruvannamalai', 'Tamilnadu', '604408', '0', 0, '2', NULL, NULL, '2025-02-08 14:57:57', NULL),
(1455, 1464, '1752', 'Vandavasi', 'Tiruvannamalai', 'Tamilnadu', '604408', '0', 0, '2', NULL, NULL, '2025-02-08 14:57:57', NULL),
(1456, 1465, '1753', 'Uthiramerur', 'Kanchipuram', 'Tamilnadu', '603406', '0', 0, '2', NULL, NULL, '2025-02-08 14:57:57', NULL),
(1457, 1466, '1754', 'Chetpet', 'Tiruvannamalai', 'Tamilnadu', '606801', '0', 0, '2', NULL, NULL, '2025-02-08 14:57:58', NULL),
(1458, 1467, '1755', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-02-17 16:06:30', NULL),
(1459, 1468, '1756', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-02-17 16:08:11', NULL),
(1460, 1469, '1757', 'Chetpet', 'Tiruvannamalai', 'TamilNadu', '606801', '0', 0, '2', NULL, NULL, '2025-02-25 15:34:25', NULL),
(1461, 1470, '1758', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-03-04 19:17:16', NULL),
(1462, 1471, '1759', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-03-06 18:10:45', NULL),
(1463, 1472, '1760', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-03-10 11:08:37', NULL),
(1464, 1473, '1761', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-03-10 11:10:20', NULL),
(1465, 1474, '1762', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-03-11 18:10:39', NULL),
(1466, 1475, '1763', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-03-12 10:50:56', NULL),
(1467, 1476, '1764,1765', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-03-12 11:14:26', NULL),
(1468, 1477, '1766', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-03-17 14:27:24', NULL),
(1469, 1478, '1767', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-03-18 12:19:03', NULL),
(1470, 1479, '1768', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-03-20 17:33:24', NULL),
(1471, 1480, '1769', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-03-21 18:54:48', NULL),
(1472, 1481, '1770', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-03-24 19:20:26', NULL),
(1473, 1482, '1771', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-03-25 14:53:10', NULL),
(1474, 1483, '1772', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-03-28 12:04:47', NULL),
(1475, 1484, '1773', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-03-29 15:07:37', NULL),
(1476, 1485, '1774', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-03-31 12:53:13', NULL),
(1477, 1486, '1775', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-04-08 11:26:29', NULL),
(1478, 1487, '1776', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606803', '0', 0, '2', NULL, NULL, '2025-04-11 11:11:57', NULL),
(1479, 1488, '1777', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '2', NULL, NULL, '2025-04-11 16:53:49', NULL),
(1480, 577, '1778', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-05-03 10:34:18', NULL),
(1481, 577, '1779', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-05-03 12:49:19', NULL),
(1482, 1489, '1780', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604408', '0', 0, '2', NULL, NULL, '2025-05-03 13:07:47', NULL),
(1483, 1490, '1781', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604403', '0', 0, '68', NULL, NULL, '2025-05-06 12:27:19', NULL),
(1484, 1491, '1782', 'Polur', 'Tiruvannamalai', 'TamilNadu', '', '0', 0, '68', NULL, NULL, '2025-05-06 14:06:33', NULL),
(1485, 820, '1783', 'Polur', 'Tiruvannamalai', 'TamilNadu', '606903', '0', 0, '68', NULL, NULL, '2025-05-07 13:49:31', NULL),
(1486, 451, '1784', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '604401', '0', 0, '68', NULL, NULL, '2025-05-08 17:54:33', NULL),
(1487, 362, '1785', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '', '0', 0, '68', NULL, NULL, '2025-05-09 14:04:13', NULL),
(1488, 559, '1787,1379,1380,1381,1382,1786,1383,1384,1385,1386,1387,1389,1388,1390,1391,1392,1394,1393,1395', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '', '0', 0, '68', NULL, NULL, '2025-05-09 20:45:19', NULL),
(1489, 568, '1788', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '', '0', 0, '68', NULL, NULL, '2025-05-12 10:47:45', NULL),
(1490, 162, '1789', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '68', NULL, NULL, '2025-05-13 12:01:28', NULL),
(1491, 559, '1790', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '68', NULL, NULL, '2025-05-23 19:11:59', NULL),
(1492, 559, '1791', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603406', '0', 0, '68', NULL, NULL, '2025-05-27 13:30:41', NULL),
(1493, 1492, '1792', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603201', '0', 0, '2', NULL, NULL, '2025-05-28 17:32:26', NULL),
(1494, 1493, '1793', 'Vandavasi', 'Tiruvannamalai', 'TamilNadu', '', '0', 0, '68', NULL, NULL, '2025-05-30 19:41:38', NULL),
(1495, 650, '1794', 'Polur', 'Tiruvannamalai', 'TamilNadu', '', '0', 0, '68', NULL, NULL, '2025-05-31 13:51:49', NULL),
(1496, 1494, '1795', 'Polur', 'Tiruvannamalai', 'TamilNadu', '', '0', 0, '68', NULL, NULL, '2025-05-31 18:51:16', NULL),
(1497, 1495, '1796', 'Polur', 'Tiruvannamalai', 'TamilNadu', '', '0', 0, '68', NULL, NULL, '2025-06-01 14:03:18', NULL),
(1498, 1496, '1798,1797', 'Polur', 'Tiruvannamalai', 'TamilNadu', '', '0', 0, '68', NULL, NULL, '2025-06-01 14:25:27', NULL),
(1499, 1497, '1799', 'Uthiramerur', 'Kancheepuram', 'TamilNadu', '603106', '0', 0, '68', NULL, NULL, '2025-06-05 17:36:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `area_duefollowup_mapping`
--

CREATE TABLE `area_duefollowup_mapping` (
  `map_id` int(11) NOT NULL,
  `duefollowup_name` varchar(150) DEFAULT NULL,
  `loan_category_id` varchar(20) NOT NULL,
  `line_name` varchar(150) NOT NULL,
  `customer_status` longtext NOT NULL,
  `area_id` longtext DEFAULT NULL,
  `cus_count` varchar(50) NOT NULL,
  `loan_count` varchar(50) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `branch_id` longtext DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `insert_login_id` int(11) DEFAULT NULL,
  `update_login_id` varchar(11) DEFAULT NULL,
  `delete_login_id` varchar(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `area_duefollowup_mapping`
--

INSERT INTO `area_duefollowup_mapping` (`map_id`, `duefollowup_name`, `loan_category_id`, `line_name`, `customer_status`, `area_id`, `cus_count`, `loan_count`, `company_id`, `branch_id`, `status`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date`) VALUES
(1, 'FA1 EMI', '3', '1', 'Current', '5,24,30,33,35,49,65,66,79,85,92,100,102,116,117,179,180,196,209,212,243,247,253,256,258,266,280,284,285,301,317,326,328,329,330,340,350,364,371,372,375,387,400,403,406,426,447,448,451,472,506,509,523,524,527,528,529,530,532,534,539,546,551,558,562,566,567,569,577,586,597,600,604,607,608,611,628,629,630,643,646,654,655,660,685,699,738,749,766,767,768,787,793,813,815,824,830,835,844,847,855,856,861,862,864,867,879,885,903,921,924,944,952,963,971,981,982,983,988,989,994,995,1002,1003,1004,1017,1028,1033,1038,1040,1048,1050,1055,1062,1069,1092,1101,1103,1105,1108,1111,1120,1121,1135,1138,1140,1151,1153,1154,1156,1160,1163,1167,1171,1173,1175,1179,1200,1202,1204,1207,1209,1214,1215,1219,1225,1228,1230,1234,1235,1236,1239,1240,1241,1243,1249,1253,1254,1256,1257,1260,1266,1267,1273,1284,1287,1298,1320,1322,1403,1454,1455,1456,1457,1463,1480', '687', '687', 1, '1', 0, 68, '2', NULL, '2025-05-05 10:12:24', '2025-06-03 12:59:59'),
(2, 'FA2 EMI', '3', '2', 'Current', '8,10,11,14,26,28,41,45,55,56,68,70,73,110,114,118,120,145,157,159,166,167,172,194,200,203,204,207,211,213,215,216,227,230,236,246,250,269,288,292,293,294,296,297,305,308,311,313,315,345,354,362,368,380,396,397,433,440,441,443,446,450,459,466,471,475,478,479,480,489,492,496,505,508,510,511,553,556,560,581,589,591,601,602,625,638,656,683,692,701,707,712,734,805,833,843,866,928,956,999,1007,1057,1061,1118,1152,1182,1183,1279,1301,1307,1325,1451,1453,1464,1470,1471,1489', '535', '535', 1, '1', 0, 68, '2', NULL, '2025-05-05 10:13:25', '2025-06-03 13:01:00'),
(3, 'FA3 EMI', '3', '3', 'Current', '7,13,57,58,59,63,75,93,94,95,109,111,115,128,129,130,139,140,141,142,143,147,153,160,161,168,205,210,214,217,219,221,225,229,231,237,238,239,244,245,251,259,270,274,275,300,312,314,332,356,361,374,384,385,386,412,415,416,420,428,436,442,458,460,462,467,474,477,481,483,487,488,495,500,501,504,512,513,516,517,518,526,531,533,535,536,538,540,541,543,545,568,570,575,578,579,582,587,599,613,637,640,698,745,746,748,750,762,763,777,802,840,857,876,878,887,899,916,917,922,923,1009,1011,1018,1226,1262,1296,1326,1460,1462,1475,1478,1482,1493', '530', '530', 1, '1', 0, 68, '2', NULL, '2025-05-05 10:14:23', '2025-06-03 13:01:31'),
(4, 'FD1 EMI', '3', '5', 'Current', '4,19,20,23,29,42,43,47,48,50,53,64,72,76,80,96,97,113,119,124,126,127,131,134,135,136,154,158,170,189,193,218,222,226,235,241,249,254,257,279,303,304,316,321,322,323,325,327,333,336,343,344,347,348,349,352,360,363,376,377,390,391,393,429,432,439,444,456,461,464,473,476,485,490,502,515,520,522,554,555,564,572,590,593,603,605,609,620,631,663,675,680,690,693,694,704,720,721,722,729,754,756,759,761,772,783,797,806,812,817,825,828,839,851,853,863,870,873,881,888,898,915,926,927,934,939,940,943,947,949,951,953,955,969,972,980,985,993,996,997,998,1005,1020,1025,1026,1027,1029,1035,1036,1039,1043,1058,1067,1070,1074,1077,1082,1093,1099,1100,1112,1113,1123,1142,1147,1148,1149,1150,1157,1159,1164,1166,1176,1185,1189,1194,1197,1199,1203,1206,1212,1223,1250,1263,1265,1269,1272,1275,1277,1285,1295,1302,1303,1308,1324,1378,1380,1381,1382,1383,1386,1387,1388,1389,1390,1392,1394,1402,1406,1410,1412,1413,1414,1415,1416,1417,1418,1419,1420,1421,1422,1423,1424,1425,1426,1427,1428,1429,1430,1431,1433,1434,1448,1450,1452,1461,1469', '546', '546', 1, '3', 0, 68, '2', NULL, '2025-05-05 10:15:16', '2025-06-03 13:02:25'),
(5, 'FD2 EMI', '3', '6', 'Current', '6,12,17,27,46,54,71,74,81,86,87,90,98,99,103,104,106,121,122,132,171,174,176,184,187,223,228,233,234,248,252,264,265,273,281,299,302,309,318,335,339,342,346,351,355,359,365,370,388,407,409,414,417,419,422,425,434,457,482,493,514,537,548,550,552,557,561,588,618,621,647,649,651,666,673,677,695,718,733,737,753,780,789,795,798,800,801,803,808,809,814,823,845,849,850,852,883,890,912,918,960,961,1054,1080,1087,1089,1091,1165,1278,1310,1311,1379,1384,1385,1391,1401,1466', '472', '472', 1, '3', 0, 68, '2', NULL, '2025-05-05 10:16:00', '2025-06-03 13:03:10'),
(6, 'FB1 EMI', '3', '4,11', 'Current', '38,82,83,101,105,123,125,144,163,165,169,175,177,240,260,267,272,310,319,320,338,341,369,373,394,402,404,408,453,484,612,615,617,619,622,624,626,633,639,642,644,645,650,653,657,659,661,664,665,667,668,669,672,676,678,681,684,686,691,697,702,703,705,706,710,713,719,723,726,727,728,730,731,735,741,742,744,751,757,758,770,771,774,775,776,784,785,794,804,810,811,818,822,826,827,842,846,848,854,1145,1280,1281,1282,1286,1288,1290,1291,1294,1299,1300,1304,1309,1312,1313,1314,1315,1316,1317,1319,1321,1323,1327,1328,1329,1330,1331,1332,1333,1334,1335,1336,1337,1338,1339,1341,1353,1449,1473,1496', '463', '464', 1, '4', 0, 68, '2', NULL, '2025-05-05 10:17:15', '2025-06-03 13:03:46'),
(7, 'FB2 EMI', '3', '12', 'Current', '21,32,62,164,178,182,186,190,206,220,261,291,295,544,576,616,623,627,635,641,648,662,670,671,674,682,687,688,689,700,708,709,715,716,725,732,736,752,755,760,764,769,778,779,782,786,791,796,799,816,820,831,834,836,837,838,859,936,1283,1289,1292,1293,1297,1318,1340,1342,1343,1344,1345,1346,1347,1348,1349,1350,1351,1352,1354,1355,1356,1357,1435,1474,1481,1483,1484,1486,1487,1491,1494,1495', '448', '448', 1, '4', 0, 68, '2', NULL, '2025-05-05 10:18:53', '2025-06-03 13:04:15'),
(8, 'FE1 EMI', '3', '7', 'Current', '3,15,18,25,37,51,67,77,78,107,108,133,138,150,151,152,155,181,188,271,276,283,290,331,334,353,357,392,401,427,431,437,445,455,469,491,494,519,542,547,580,584,595,596,610,632,658,679,711,724,739,743,790,821,841,858,860,872,874,880,893,894,902,914,948,958,976,1001,1006,1010,1024,1053,1063,1073,1098,1114,1117,1126,1132,1137,1158,1169,1177,1221,1222,1244,1264,1306,1395,1398,1405,1411,1465,1467,1468,1472,1476,1477', '461', '461', 1, '2', 0, 68, '2', NULL, '2025-05-05 10:19:47', '2025-06-03 13:04:45'),
(9, 'FE2 EMI', '3', '8', 'Current', '2,22,36,44,60,61,69,88,91,112,148,162,183,185,197,202,208,224,255,263,268,277,298,324,337,366,367,378,383,389,395,398,411,421,423,430,435,449,454,486,507,521,525,559,571,574,583,585,592,606,614,634,636,652,696,717,747,773,781,792,807,819,832,897,904,905,907,970,1088,1116,1143,1205,1242,1396,1397,1399,1400,1409,1459,1485,1488', '450', '450', 1, '2', 0, 68, '2', NULL, '2025-05-05 10:20:30', '2025-06-03 13:05:08'),
(10, 'FE3 EMI', '3', '13', 'Current', '1,9,16,31,34,40,52,84,89,137,146,149,156,173,191,192,195,198,199,201,232,242,262,278,282,286,287,289,306,307,358,379,381,382,788,900,925,933,959,964,965,1022,1023,1034,1037,1049,1065,1361,1363,1364,1365,1367,1369,1370,1372,1374,1375,1376,1377,1479,1492', '343', '343', 1, '2', 0, 68, '2', NULL, '2025-05-05 10:25:19', '2025-06-03 13:05:44'),
(11, 'F E4 EMI', '3', '9,10,13', 'Current', '399,405,410,413,418,424,438,452,463,465,468,470,497,498,499,503,549,563,565,573,594,598,714,740,765,829,865,868,869,871,875,877,882,884,886,889,891,892,895,896,901,906,908,909,910,911,913,919,920,929,930,931,932,935,937,938,941,942,945,946,950,954,957,962,966,967,968,973,974,975,977,978,979,984,986,987,990,991,992,1000,1008,1012,1013,1014,1015,1016,1019,1021,1031,1032,1041,1042,1044,1045,1046,1047,1051,1052,1056,1059,1060,1064,1066,1068,1071,1072,1075,1076,1078,1079,1081,1083,1084,1085,1086,1090,1094,1095,1096,1097,1102,1104,1106,1107,1109,1110,1115,1119,1122,1124,1125,1127,1128,1129,1130,1131,1133,1134,1136,1139,1141,1144,1146,1155,1161,1162,1168,1170,1172,1174,1178,1180,1181,1184,1184,1186,1187,1188,1190,1191,1192,1193,1195,1196,1198,1201,1208,1210,1211,1213,1216,1217,1218,1220,1224,1227,1229,1231,1232,1233,1237,1238,1245,1246,1247,1248,1251,1252,1255,1258,1259,1261,1268,1270,1271,1274,1305,1358,1359,1360,1362,1366,1368,1371,1373', '154', '154', 1, '2', 0, 68, '2', NULL, '2025-05-05 10:26:34', '2025-06-03 13:06:21'),
(12, 'F A1 App', '1', '1', 'Current', '5,24,30,33,35,49,65,66,79,85,92,100,102,116,117,179,180,196,209,212,243,247,253,256,258,266,280,284,285,301,317,326,328,329,330,340,350,364,371,372,375,387,400,403,406,426,447,448,451,472,506,509,523,524,527,528,529,530,532,534,539,546,551,558,562,566,567,569,577,586,597,600,604,607,608,611,628,629,630,643,646,654,655,660,685,699,738,749,766,767,768,787,793,813,815,824,830,835,844,847,855,856,861,862,864,867,879,885,903,921,924,944,952,963,971,981,982,983,988,989,994,995,1002,1003,1004,1017,1028,1033,1038,1040,1048,1050,1055,1062,1069,1092,1101,1103,1105,1108,1111,1120,1121,1135,1138,1140,1151,1153,1154,1156,1160,1163,1167,1171,1173,1175,1179,1200,1202,1204,1207,1209,1214,1215,1219,1225,1228,1230,1234,1235,1236,1239,1240,1241,1243,1249,1253,1254,1256,1257,1260,1266,1267,1273,1284,1287,1298,1320,1322,1403,1454,1455,1456,1457,1463,1480', '666', '683', 1, '1', 0, 60, '2', NULL, '2025-05-05 11:12:52', '2025-06-03 13:06:54'),
(13, 'F A2 App', '1', '2', 'Current', '8,10,11,14,26,28,41,45,55,56,68,70,73,110,114,118,120,145,157,159,166,167,172,194,200,203,204,207,211,213,215,216,227,230,236,246,250,269,288,292,293,294,296,297,305,308,311,313,315,345,625,638,656,805,833,843,866,928,956,999,1007,1057,1061,1279,1301,1307,1325,1451,1489', '295', '302', 1, '1', 0, 60, '2', NULL, '2025-05-05 11:25:06', '2025-06-03 13:07:20'),
(14, 'FA3 APP', '1', '3', 'Current', '7,13,57,58,59,63,75,93,94,95,109,111,115,128,129,130,139,140,141,142,143,147,153,160,161,168,205,210,214,217,219,221,225,229,231,237,238,239,244,245,251,259,270,274,275,300,312,314,332,356,361,374,384,385,386,412,415,416,420,428,436,613,637,640,698,802,840,876,878,887,899,916,917,922,923,1009,1011,1018,1296,1326,1460,1462,1475,1482,1493', '293', '298', 1, '1', 0, 68, '2', NULL, '2025-05-05 15:24:05', '2025-06-03 13:07:47'),
(15, 'FA4 App', '1', '2,3', 'Current', '354,362,368,380,396,397,433,440,441,442,443,446,450,458,459,460,462,466,467,471,474,475,477,478,479,480,481,483,487,488,489,492,495,496,500,501,504,505,508,510,511,512,513,516,517,518,526,531,533,535,536,538,540,541,543,545,553,556,560,568,570,575,578,579,581,582,587,589,591,599,601,602,683,692,701,707,712,734,745,746,748,750,762,763,777,857,1118,1152,1182,1183,1226,1262,1453,1464,1470,1471,1478', '281', '287', 1, '1', 0, 68, '2', NULL, '2025-05-05 15:24:57', '2025-06-03 13:08:11'),
(16, 'FD1 App', '1', '5,6', 'Current', '4,19,20,23,29,42,43,47,48,50,53,64,72,76,80,96,97,113,119,124,126,127,131,134,135,136,154,158,170,189,193,218,222,226,235,241,249,254,257,279,303,304,316,321,322,323,325,327,333,336,343,344,347,348,349,352,360,363,376,377,390,391,393,429,432,439,444,456,461,464,473,476,485,490,502,515,520,522,554,555,620,631,663,675,680,690,693,694,704,720,721,722,729,754,756,759,761,797,806,812,817,825,828,839,851,853,863,870,873,881,888,898,915,926,927,934,939,940,943,947,949,951,953,955,969,972,980,985,993,996,997,998,1005,1020,1025,1026,1027,1029,1035,1036,1039,1043,1058,1067,1070,1074,1077,1082,1093,1099,1100,1112,1113,1123,1142,1147,1148,1149,1150,1157,1159,1164,1166,1176,1185,1189,1194,1197,1199,1203,1206,1212,1223,1277,1285,1295,1302,1303,1308,1324,1378,1380,1381,1382,1386,1387,1388,1389,1390,1392,1394,1402,1406,1410,1412,1413,1414,1415,1416,1417,1418,1419,1420,1421,1422,1424,1426,1427,1428,1430,1431,1433,1434,1448,1450,1452,1461,1469', '203', '203', 1, '3', 0, 68, '2', NULL, '2025-05-05 15:26:15', '2025-06-03 13:08:43'),
(17, 'FD2 App', '1', '6', 'Current', '6,12,17,27,46,54,71,81,86,87,90,98,99,103,104,106,121,122,132,171,174,176,184,187,223,228,233,234,248,252,264,265,273,281,299,302,309,318,335,339,342,346,351,355,359,365,370,388,407,409,414,417,419,422,425,434,618,621,647,649,651,666,673,677,695,718,789,795,798,800,801,803,808,809,814,823,845,849,883,890,912,918,960,961,1054,1080,1087,1089,1091,1278,1310,1384,1391,1401,1466', '351', '352', 1, '3', 0, 68, '2', NULL, '2025-05-05 15:26:58', '2025-06-03 13:09:07'),
(18, 'FE1 App', '1', '7,8', 'Current', '2,3,15,18,22,25,36,37,44,51,60,61,67,69,77,78,88,91,107,108,112,133,138,148,150,151,152,155,162,181,183,185,188,197,202,208,224,255,263,268,271,276,277,283,290,298,324,331,334,337,353,357,366,367,378,383,389,392,395,398,401,411,421,423,427,430,431,435,437,445,449,454,455,469,486,491,494,507,519,521,525,542,547,559,571,574,580,583,584,585,592,595,596,606,610,614,632,634,636,652,658,679,696,711,717,724,739,743,747,773,781,790,792,807,819,821,832,841,858,860,872,874,880,893,894,897,902,904,905,907,914,948,958,970,976,1001,1006,1010,1024,1053,1063,1073,1088,1098,1114,1116,1117,1126,1132,1137,1143,1158,1169,1177,1205,1221,1222,1242,1244,1264,1306,1395,1396,1397,1398,1399,1400,1405,1409,1411,1459,1465,1467,1468,1472,1476,1477,1485,1488', '600', '613', 1, '2', 0, 68, '2', NULL, '2025-05-05 15:37:25', '2025-06-03 13:10:11'),
(19, 'FE2 App', '1', '9,10,13', 'Current', '1,9,16,31,34,40,52,84,89,137,146,149,156,173,191,192,195,198,199,201,232,242,262,278,282,286,287,289,306,307,358,379,381,382,399,405,410,413,418,424,438,452,463,465,468,470,497,498,499,503,549,563,565,573,594,598,714,740,765,788,829,865,868,869,871,875,877,882,884,886,889,891,892,895,896,900,901,906,908,909,910,911,913,919,920,925,929,930,931,932,933,935,937,938,941,942,945,946,950,954,957,959,962,964,965,966,967,968,973,974,975,977,978,979,984,986,987,990,991,992,1000,1008,1012,1013,1014,1015,1016,1019,1021,1022,1023,1031,1032,1034,1037,1041,1042,1044,1045,1046,1047,1049,1051,1052,1056,1059,1060,1064,1065,1066,1068,1071,1072,1075,1076,1078,1079,1081,1083,1084,1085,1086,1090,1094,1095,1096,1097,1102,1104,1106,1107,1109,1110,1115,1119,1122,1124,1125,1127,1128,1129,1130,1131,1133,1134,1136,1139,1141,1144,1146,1155,1161,1162,1168,1170,1172,1174,1178,1180,1181,1184,1186,1187,1188,1190,1191,1192,1193,1195,1196,1198,1201,1208,1210,1211,1213,1216,1217,1218,1220,1224,1227,1229,1231,1232,1233,1237,1238,1245,1246,1247,1248,1251,1252,1255,1258,1259,1261,1268,1270,1271,1274,1305,1358,1359,1360,1361,1362,1363,1364,1365,1366,1367,1368,1369,1370,1371,1372,1373,1374,1375,1376,1377,1479,1492', '286', '295', 1, '2', 0, 68, '2', NULL, '2025-05-05 15:41:12', '2025-06-03 13:10:50'),
(20, 'FD3 App', '1', '5,6', 'Current', '74,457,482,493,514,537,548,550,552,557,561,564,572,588,590,593,603,605,609,733,737,753,772,780,783,850,852,1165,1250,1263,1265,1269,1272,1275,1311,1379,1383,1385,1423,1425,1429', '103', '103', 1, '3', 0, 68, '2', NULL, '2025-05-05 16:00:07', '2025-06-03 13:11:22'),
(21, 'FA Vehicle', '2', '1,2,3', 'Current', '5,7,8,10,11,13,14,24,26,28,30,33,35,41,45,49,55,56,57,58,59,63,65,66,68,70,73,75,79,85,92,93,94,95,100,102,109,110,111,114,115,116,117,118,120,128,129,130,139,140,141,142,143,145,147,153,157,159,160,161,166,167,168,172,179,180,194,196,200,203,204,205,207,209,210,211,212,213,214,215,216,217,219,221,225,227,229,230,231,236,237,238,239,243,244,245,246,247,250,251,253,256,258,259,266,269,270,274,275,280,284,285,288,292,293,294,296,297,300,301,305,308,311,312,313,314,315,317,326,328,329,330,332,340,345,350,354,356,361,362,364,368,371,372,374,375,380,384,385,386,387,396,397,400,403,406,412,415,416,420,426,428,433,436,440,441,442,443,446,447,448,450,451,458,459,460,462,466,467,471,472,474,475,477,478,479,480,481,483,487,488,489,492,495,496,500,501,504,505,506,508,509,510,511,512,513,516,517,518,523,524,526,527,528,529,530,531,532,533,534,535,536,538,539,540,541,543,545,546,551,553,556,558,560,562,566,567,568,569,570,575,577,578,579,581,582,586,587,589,591,597,599,600,601,602,604,607,608,611,613,625,628,629,630,637,638,640,643,646,654,655,656,660,683,685,692,698,699,701,707,712,734,738,745,746,748,749,750,762,763,766,767,768,777,787,793,802,805,813,815,824,830,833,835,840,843,844,847,855,856,857,861,862,864,866,867,876,878,879,885,887,899,903,916,917,921,922,923,924,928,944,952,956,963,971,981,982,983,988,989,994,995,999,1002,1003,1004,1007,1009,1011,1017,1018,1028,1033,1038,1040,1048,1050,1055,1057,1061,1062,1069,1092,1101,1103,1105,1108,1111,1118,1120,1121,1135,1138,1140,1151,1152,1153,1154,1156,1160,1163,1167,1171,1173,1175,1179,1182,1183,1200,1202,1204,1207,1209,1214,1215,1219,1225,1226,1228,1230,1234,1235,1236,1239,1240,1241,1243,1249,1253,1254,1256,1257,1260,1262,1266,1267,1273,1279,1284,1287,1296,1298,1301,1307,1320,1322,1325,1326,1403,1451,1453,1454,1455,1456,1457,1460,1462,1463,1464,1470,1471,1475,1478,1480,1482,1489,1493', '7', '7', 1, '1', 0, 68, '2', NULL, '2025-05-05 16:01:51', '2025-06-03 13:12:17'),
(22, 'FD Vehicle', '2', '5,6', 'Current', '4,6,12,17,19,20,23,27,29,42,43,46,47,48,50,53,54,64,71,72,74,76,80,81,86,87,90,96,97,98,99,103,104,106,113,119,121,122,124,126,127,131,132,134,135,136,154,158,170,171,174,176,184,187,189,193,218,222,223,226,228,233,234,235,241,248,249,252,254,257,264,265,273,279,281,299,302,303,304,309,316,318,321,322,323,325,327,333,335,336,339,342,343,344,346,347,348,349,351,352,355,359,360,363,365,370,376,377,388,390,391,393,407,409,414,417,419,422,425,429,432,434,439,444,456,457,461,464,473,476,482,485,490,493,502,514,515,520,522,537,548,550,552,554,555,557,561,564,572,588,590,593,603,605,609,618,620,621,631,647,649,651,663,666,673,675,677,680,690,693,694,695,704,718,720,721,722,729,733,737,753,754,756,759,761,772,780,783,789,795,797,798,800,801,803,806,808,809,812,814,817,823,825,828,839,845,849,850,851,852,853,863,870,873,881,883,888,890,898,912,915,918,926,927,934,939,940,943,947,949,951,953,955,960,961,969,972,980,985,993,996,997,998,1005,1020,1025,1026,1027,1029,1035,1036,1039,1043,1054,1058,1067,1070,1074,1077,1080,1082,1087,1089,1091,1093,1099,1100,1112,1113,1123,1142,1147,1148,1149,1150,1157,1159,1164,1165,1166,1176,1185,1189,1194,1197,1199,1203,1206,1212,1223,1250,1263,1265,1269,1272,1275,1277,1278,1285,1295,1302,1303,1308,1310,1311,1324,1378,1379,1380,1381,1382,1383,1384,1385,1386,1387,1388,1389,1390,1391,1392,1394,1401,1402,1406,1410,1412,1413,1414,1415,1416,1417,1418,1419,1420,1421,1422,1423,1424,1425,1426,1427,1428,1429,1430,1431,1433,1434,1448,1450,1452,1461,1466,1469', '2', '2', 1, '3', 0, 68, '2', NULL, '2025-05-05 16:02:30', '2025-06-03 13:12:46'),
(23, 'FE Vehicle', '2', '7,8,9,10,13', 'Current', '1,2,3,9,15,16,18,22,25,31,34,36,37,40,44,51,52,60,61,67,69,77,78,84,88,89,91,107,108,112,133,137,138,146,148,149,150,151,152,155,156,162,173,181,183,185,188,191,192,195,197,198,199,201,202,208,224,232,242,255,262,263,268,271,276,277,278,282,283,286,287,289,290,298,306,307,324,331,334,337,353,357,358,366,367,378,379,381,382,383,389,392,395,398,399,401,405,410,411,413,418,421,423,424,427,430,431,435,437,438,445,449,452,454,455,463,465,468,469,470,486,491,494,497,498,499,503,507,519,521,525,542,547,549,559,563,565,571,573,574,580,583,584,585,592,594,595,596,598,606,610,614,632,634,636,652,658,679,696,711,714,717,724,739,740,743,747,765,773,781,788,790,792,807,819,821,829,832,841,858,860,865,868,869,871,872,874,875,877,880,882,884,886,889,891,892,893,894,895,896,897,900,901,902,904,905,906,907,908,909,910,911,913,914,919,920,925,929,930,931,932,933,935,937,938,941,942,945,946,948,950,954,957,958,959,962,964,965,966,967,968,970,973,974,975,976,977,978,979,984,986,987,990,991,992,1000,1001,1006,1008,1010,1012,1013,1014,1015,1016,1019,1021,1022,1023,1024,1031,1032,1034,1037,1041,1042,1044,1045,1046,1047,1049,1051,1052,1053,1056,1059,1060,1063,1064,1065,1066,1068,1071,1072,1073,1075,1076,1078,1079,1081,1083,1084,1085,1086,1088,1090,1094,1095,1096,1097,1098,1102,1104,1106,1107,1109,1110,1114,1115,1116,1117,1119,1122,1124,1125,1126,1127,1128,1129,1130,1131,1132,1133,1134,1136,1137,1139,1141,1143,1144,1146,1155,1158,1161,1162,1168,1169,1170,1172,1174,1177,1178,1180,1181,1184,1186,1187,1188,1190,1191,1192,1193,1195,1196,1198,1201,1205,1208,1210,1211,1213,1216,1217,1218,1220,1221,1222,1224,1227,1229,1231,1232,1233,1237,1238,1242,1244,1245,1246,1247,1248,1251,1252,1255,1258,1259,1261,1264,1268,1270,1271,1274,1305,1306,1358,1359,1360,1361,1362,1363,1364,1365,1366,1367,1368,1369,1370,1371,1372,1373,1374,1375,1376,1377,1395,1396,1397,1398,1399,1400,1405,1409,1411,1459,1465,1467,1468,1472,1476,1477,1479,1485,1488,1492', '1', '1', 1, '2', 0, 68, '2', NULL, '2025-05-05 16:03:15', '2025-06-03 13:13:33'),
(24, 'FB App', '1', '4,11,12', 'Current', '21,32,38,62,82,83,101,105,123,125,144,163,164,165,169,175,177,178,182,186,190,206,220,240,260,261,267,272,291,295,310,319,320,338,341,369,373,394,402,404,408,453,484,544,576,612,615,616,617,619,622,623,624,626,627,633,635,639,641,642,644,645,648,650,653,657,659,661,662,664,665,667,668,669,670,671,672,674,676,678,681,682,684,686,687,688,689,691,697,700,702,703,705,706,708,709,710,713,715,716,719,723,725,726,727,728,730,731,732,735,736,741,742,744,751,752,755,757,758,760,764,769,770,771,774,775,776,778,779,782,784,785,786,791,794,796,799,804,810,811,816,818,820,822,826,827,831,834,836,837,838,842,846,848,854,859,936,1145,1280,1281,1282,1283,1286,1288,1289,1290,1291,1292,1293,1294,1297,1299,1300,1304,1309,1312,1313,1314,1315,1316,1317,1318,1319,1321,1323,1327,1328,1329,1330,1331,1332,1333,1334,1335,1336,1337,1338,1339,1340,1341,1342,1343,1344,1345,1346,1347,1348,1349,1350,1351,1352,1353,1354,1355,1356,1357,1435,1449,1473,1474,1481,1483,1484,1486,1487,1491,1494,1495,1496', '17', '17', 1, '4', 0, 2, NULL, NULL, '2025-06-03 13:21:59', '2025-06-03 13:21:59'),
(25, 'FA pro', '7', '1,2,3,14', 'Current', '5,7,8,10,11,13,14,24,26,28,30,33,35,41,45,49,55,56,57,58,59,63,65,66,68,70,73,75,79,85,92,93,94,95,100,102,109,110,111,114,115,116,117,118,120,128,129,130,139,140,141,142,143,145,147,153,157,159,160,161,166,167,168,172,179,180,194,196,200,203,204,205,207,209,210,211,212,213,214,215,216,217,219,221,225,227,229,230,231,236,237,238,239,243,244,245,246,247,250,251,253,256,258,259,266,269,270,274,275,280,284,285,288,292,293,294,296,297,300,301,305,308,311,312,313,314,315,317,326,328,329,330,332,340,345,350,354,356,361,362,364,368,371,372,374,375,380,384,385,386,387,396,397,400,403,406,412,415,416,420,426,428,433,436,440,441,442,443,446,447,448,450,451,458,459,460,462,466,467,471,472,474,475,477,478,479,480,481,483,487,488,489,492,495,496,500,501,504,505,506,508,509,510,511,512,513,516,517,518,523,524,526,527,528,529,530,531,532,533,534,535,536,538,539,540,541,543,545,546,551,553,556,558,560,562,566,567,568,569,570,575,577,578,579,581,582,586,587,589,591,597,599,600,601,602,604,607,608,611,613,625,628,629,630,637,638,640,643,646,654,655,656,660,683,685,692,698,699,701,707,712,734,738,745,746,748,749,750,762,763,766,767,768,777,787,793,802,805,813,815,824,830,833,835,840,843,844,847,855,856,857,861,862,864,866,867,876,878,879,885,887,899,903,916,917,921,922,923,924,928,944,952,956,963,971,981,982,983,988,989,994,995,999,1002,1003,1004,1007,1009,1011,1017,1018,1028,1033,1038,1040,1048,1050,1055,1057,1061,1062,1069,1092,1101,1103,1105,1108,1111,1118,1120,1121,1135,1138,1140,1151,1152,1153,1154,1156,1160,1163,1167,1171,1173,1175,1179,1182,1183,1200,1202,1204,1207,1209,1214,1215,1219,1225,1226,1228,1230,1234,1235,1236,1239,1240,1241,1243,1249,1253,1254,1256,1257,1260,1262,1266,1267,1273,1279,1284,1287,1296,1298,1301,1307,1320,1322,1325,1326,1403,1451,1453,1454,1455,1456,1457,1460,1462,1463,1464,1470,1471,1475,1478,1480,1482,1489,1490,1493', '1', '1', 1, '1', 0, 2, NULL, NULL, '2025-06-03 13:22:59', '2025-06-03 13:22:59'),
(26, 'FD Pro', '7', '5,6', 'Current', '4,6,12,17,19,20,23,27,29,42,43,46,47,48,50,53,54,64,71,72,74,76,80,81,86,87,90,96,97,98,99,103,104,106,113,119,121,122,124,126,127,131,132,134,135,136,154,158,170,171,174,176,184,187,189,193,218,222,223,226,228,233,234,235,241,248,249,252,254,257,264,265,273,279,281,299,302,303,304,309,316,318,321,322,323,325,327,333,335,336,339,342,343,344,346,347,348,349,351,352,355,359,360,363,365,370,376,377,388,390,391,393,407,409,414,417,419,422,425,429,432,434,439,444,456,457,461,464,473,476,482,485,490,493,502,514,515,520,522,537,548,550,552,554,555,557,561,564,572,588,590,593,603,605,609,618,620,621,631,647,649,651,663,666,673,675,677,680,690,693,694,695,704,718,720,721,722,729,733,737,753,754,756,759,761,772,780,783,789,795,797,798,800,801,803,806,808,809,812,814,817,823,825,828,839,845,849,850,851,852,853,863,870,873,881,883,888,890,898,912,915,918,926,927,934,939,940,943,947,949,951,953,955,960,961,969,972,980,985,993,996,997,998,1005,1020,1025,1026,1027,1029,1035,1036,1039,1043,1054,1058,1067,1070,1074,1077,1080,1082,1087,1089,1091,1093,1099,1100,1112,1113,1123,1142,1147,1148,1149,1150,1157,1159,1164,1165,1166,1176,1185,1189,1194,1197,1199,1203,1206,1212,1223,1250,1263,1265,1269,1272,1275,1277,1278,1285,1295,1302,1303,1308,1310,1311,1324,1378,1379,1380,1381,1382,1383,1384,1385,1386,1387,1388,1389,1390,1391,1392,1394,1401,1402,1406,1410,1412,1413,1414,1415,1416,1417,1418,1419,1420,1421,1422,1423,1424,1425,1426,1427,1428,1429,1430,1431,1433,1434,1448,1450,1452,1461,1466,1469', '1', '1', 1, '3', 0, 2, NULL, NULL, '2025-06-03 13:23:35', '2025-06-03 13:23:35');

-- --------------------------------------------------------

--
-- Table structure for table `area_group_mapping`
--

CREATE TABLE `area_group_mapping` (
  `map_id` int(11) NOT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `area_id` longtext DEFAULT NULL,
  `sub_area_id` longtext DEFAULT NULL,
  `company_id` varchar(255) DEFAULT NULL,
  `branch_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT '0',
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `area_group_mapping`
--

INSERT INTO `area_group_mapping` (`map_id`, `group_name`, `area_id`, `sub_area_id`, `company_id`, `branch_id`, `status`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date`) VALUES
(1, 'GA1', '5,24,30,33,35,49,65,66,79,85,92,100,102,116,117,179,180,196,209,212,243,247,253,256,258,266,280,284,285,301,317,326,328,329,330,340,350,364,371,372,375,387,400,403,406,426,447,448,451,472,506,509,523,524,527,528,529,530,532,534,539,546,551,558,562,566,567,569,577,586,597,600,604,607,608,611,628,629,630,643,646,654,655,660,685,699,738,749,766,767,768,787,793,813,815,824,830,835,844,847,855,856,861,862,864,867,879,885,903,921,924,944,952,963,971,981,982,983,988,989,994,995,1002,1003,1004,1017,1028,1033,1038,1040,1048,1050,1055,1062,1069,1092,1101,1103,1105,1108,1111,1120,1121,1135,1138,1140,1151,1153,1154,1156,1160,1163,1167,1171,1173,1175,1179,1200,1202,1204,1207,1209,1214,1215,1219,1225,1228,1230,1234,1235,1236,1239,1240,1241,1243,1249,1253,1254,1256,1257,1260,1266,1267,1273,1284,1287,1298,1320,1322,1403,1454,1455,1456,1457,1458,1463,1480', '6,27,33,36,38,54,72,73,86,92,99,108,110,126,127,194,195,211,225,228,259,263,269,272,274,282,283,299,304,305,322,339,348,350,351,352,363,373,374,389,398,399,402,417,431,435,438,459,482,483,486,507,542,545,561,562,565,566,567,568,570,572,577,586,591,598,602,606,607,609,618,619,620,621,622,623,624,636,647,650,654,657,658,663,681,682,683,684,685,698,701,709,710,715,741,756,796,807,824,825,826,846,852,872,874,883,889,903,912,915,923,924,929,930,932,935,947,953,998,1016,1019,1039,1047,1058,1066,1076,1077,1078,1083,1084,1089,1090,1098,1099,1100,1116,1127,1132,1137,1139,1148,1150,1155,1162,1169,1194,1204,1206,1209,1212,1215,1224,1225,1239,1242,1244,1255,1257,1258,1260,1264,1267,1271,1275,1277,1279,1283,1304,1306,1308,1311,1313,1318,1319,1323,1329,1332,1334,1397,1398,1399,1402,1403,1404,1406,1411,1412,1413,1414,1415,1416,1417,1418,1419,1420,1421,1422,1423,1424,1425,1426,1427,1428,1429,1430,1431,1432,1433,1434,1435,1436,1437,1438,1439,1440,1441,1442,1443,1444,1445,1446,1447,1448,1449,1450,1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461,1462,1463,1464,1465,1466,1467,1468,1469,1470,1471,1472,1473,1474,1475,1476,1477,1478,1479,1480,1481,1482,1483,1484,1485,1486,1487,1488,1489,1490,1491,1492,1493,1494,1495,1496,1497,1498,1499,1500,1501,1502,1503,1504,1505,1506,1507,1508,1509,1510,1511,1512,1513,1514,1515,1516,1517,1518,1519,1520,1521,1522,1523,1524,1525,1526,1527,1528,1529,1530,1531,1532,1533,1535,1539,1540,1542,1543,1546,1552,1553,1559,1570,1573,1584,1606,1608,1693,1743,1744,1745,1746,1751,1769,1778,1779,1784', '1', '1', '0', '1', '68', NULL, '2024-06-19 21:45:58', '2025-05-08 17:55:59'),
(2, 'GA2', '8,10,11,14,26,28,41,45,55,56,68,70,73,110,114,118,120,145,157,159,166,167,172,194,200,203,204,207,211,213,215,216,227,230,236,246,250,269,288,292,293,294,296,297,305,308,311,313,315,345,354,362,368,380,396,397,433,440,441,443,446,450,459,466,471,475,478,479,480,489,492,496,505,508,510,511,553,556,560,581,589,591,601,602,625,638,656,683,692,701,707,712,734,805,833,843,866,928,956,999,1007,1057,1061,1118,1152,1182,1183,1279,1301,1307,1325,1451,1453,1464,1470,1471,1489,1490', '9,12,13,16,29,31,44,50,60,61,75,77,80,119,124,128,130,156,169,171,181,182,187,209,216,219,220,223,227,229,231,232,243,246,252,262,266,288,308,313,314,315,317,318,326,330,333,335,337,368,378,386,387,395,409,426,427,466,474,475,477,478,481,485,494,501,506,510,513,514,515,524,528,532,541,544,546,547,593,596,600,629,630,639,641,651,652,678,693,711,739,748,758,764,770,792,864,901,911,934,1023,1051,1094,1103,1157,1161,1222,1256,1286,1287,1565,1587,1593,1611,1740,1742,1752,1758,1759,1780,1781,1785', '1', '1', '0', '1', '68', NULL, '2024-06-19 21:45:58', '2025-05-09 14:05:27'),
(3, 'GA3', '7,13,57,58,59,63,75,93,94,95,109,111,115,128,129,130,139,140,141,142,143,147,153,160,161,168,205,210,214,217,219,221,225,229,231,237,238,239,244,245,251,259,270,274,275,300,312,314,332,356,361,374,384,385,386,412,415,416,420,428,436,442,458,460,462,467,474,477,481,483,487,488,495,500,501,504,512,513,516,517,518,526,531,533,535,536,538,540,541,543,545,568,570,575,578,579,582,587,599,613,637,640,698,745,746,748,750,762,763,777,802,840,857,876,878,887,899,916,917,922,923,1009,1011,1018,1226,1262,1296,1326,1460,1462,1475,1478,1482,1493', '8,15,62,63,64,70,82,100,101,102,103,118,120,125,139,140,141,150,151,152,153,154,158,165,172,173,183,221,226,230,233,235,237,241,245,247,253,254,255,260,261,267,275,289,293,294,321,334,336,354,380,385,401,414,415,416,444,447,448,453,461,469,476,493,495,497,502,509,512,516,518,522,523,531,536,537,540,548,549,552,553,554,564,569,571,573,574,576,578,579,581,585,608,610,616,625,626,631,637,649,665,692,695,755,803,804,806,808,820,821,835,861,891,893,908,925,944,946,955,994,1011,1012,1017,1018,1105,1107,1117,1330,1548,1582,1612,1748,1750,1763,1767,1771,1788,1793', '1', '1', '0', '1', '68', NULL, '2024-06-19 21:45:58', '2025-05-30 19:43:32'),
(4, 'GB', '369', '396', '1', '4', '0', '1', '2', NULL, '2024-06-19 21:45:58', '2025-04-19 16:26:05'),
(5, 'GD1', '4,19,20,23,29,42,43,47,48,50,53,64,72,76,80,96,97,113,119,124,126,127,131,134,135,136,154,158,170,189,193,218,222,226,235,241,249,254,257,279,303,304,316,321,322,323,325,327,333,336,343,344,347,348,349,352,360,363,376,377,390,391,393,429,432,439,444,456,461,464,473,476,485,490,502,515,520,522,554,555,564,572,590,593,603,605,609,620,631,663,675,680,690,693,694,704,720,721,722,729,754,756,759,761,772,783,797,806,812,817,825,828,839,851,853,863,870,873,881,888,898,915,926,927,934,939,940,943,947,949,951,953,955,969,972,980,985,993,996,997,998,1005,1020,1025,1026,1027,1029,1035,1036,1039,1043,1058,1067,1070,1074,1077,1082,1093,1099,1100,1112,1113,1123,1142,1147,1148,1149,1150,1157,1159,1164,1166,1176,1185,1189,1194,1197,1199,1203,1206,1212,1223,1250,1263,1265,1269,1272,1275,1277,1285,1295,1302,1303,1308,1324,1378,1380,1381,1382,1383,1386,1387,1388,1389,1390,1392,1394,1402,1406,1410,1412,1413,1414,1415,1416,1417,1418,1419,1420,1421,1422,1423,1424,1425,1426,1427,1428,1429,1430,1431,1433,1434,1448,1450,1452,1461,1469', '5,21,22,23,26,32,45,46,47,52,53,55,58,71,79,83,87,104,105,123,129,134,136,137,138,142,145,146,147,166,170,185,204,208,234,238,242,251,257,265,270,273,298,324,325,338,343,344,345,347,349,355,358,366,367,370,371,372,376,384,388,403,404,405,420,421,423,462,465,473,479,491,496,499,508,511,520,525,538,551,558,560,594,595,604,613,640,643,653,655,659,673,686,719,731,736,746,749,750,761,778,779,780,787,812,814,817,819,830,842,856,865,871,876,884,887,907,919,921,931,938,941,949,956,993,1010,1021,1022,1029,1034,1035,1038,1042,1044,1046,1048,1050,1064,1067,1075,1080,1088,1091,1092,1093,1101,1119,1124,1125,1126,1128,1134,1135,1138,1142,1158,1167,1170,1174,1177,1182,1195,1201,1202,1203,1216,1217,1227,1246,1251,1252,1253,1254,1261,1263,1268,1270,1280,1289,1293,1298,1301,1303,1307,1310,1316,1327,1536,1549,1551,1555,1558,1561,1563,1571,1581,1588,1589,1594,1610,1669,1670,1671,1672,1673,1676,1677,1678,1679,1680,1682,1684,1692,1696,1699,1702,1703,1704,1705,1706,1707,1708,1709,1710,1711,1712,1713,1714,1715,1716,1717,1718,1719,1720,1721,1722,1723,1737,1739,1741,1749,1757', '1', '3', '0', '1', '2', NULL, '2024-06-19 21:45:58', '2025-04-21 15:57:56'),
(6, 'GD2', '6,12,17,27,46,54,71,74,81,86,87,90,98,99,103,104,106,121,122,132,171,174,176,184,187,223,228,233,234,248,252,264,265,273,281,299,302,309,318,335,339,342,346,351,355,359,365,370,388,407,409,414,417,419,422,425,434,457,482,493,514,537,548,550,552,557,561,588,618,621,647,649,651,666,673,677,695,718,733,737,753,780,789,795,798,800,801,803,808,809,814,823,845,849,850,852,883,890,912,918,960,961,1054,1080,1087,1089,1091,1165,1278,1310,1311,1379,1384,1385,1391,1401,1466,1499', '7,14,19,30,51,59,78,81,88,93,94,97,106,107,111,112,114,131,132,143,186,189,191,199,202,239,244,249,250,264,268,280,281,292,300,320,323,331,340,357,362,365,369,375,379,383,390,391,397,418,439,441,446,449,452,455,458,467,492,517,529,550,575,588,590,592,597,601,638,671,674,702,704,706,722,729,733,751,776,791,795,811,838,848,854,857,859,860,862,867,868,873,882,913,917,918,920,951,958,960,961,962,963,964,965,966,967,968,969,970,971,972,973,974,975,976,977,978,979,980,981,982,983,984,985,986,1007,1013,1055,1056,1154,1180,1187,1190,1193,1269,1564,1596,1597,1668,1674,1675,1681,1691,1754,1802', '1', '3', '0', '1', '68', NULL, '2024-06-19 21:45:58', '2025-06-14 16:34:33'),
(7, 'GE1', '3,15,18,25,37,51,67,77,78,107,108,133,138,150,151,152,155,181,188,271,276,283,290,331,334,353,357,392,401,427,431,437,445,455,469,491,494,519,542,547,580,584,595,596,610,632,658,679,711,724,739,743,790,821,841,858,860,872,874,880,893,894,902,914,948,958,976,1001,1006,1010,1024,1053,1063,1073,1098,1114,1117,1126,1132,1137,1158,1169,1177,1221,1222,1244,1264,1306,1395,1398,1405,1411,1465,1467,1468,1472,1476,1477', '4,17,20,28,40,56,74,84,85,115,116,117,144,149,162,163,164,167,196,203,290,295,303,310,311,353,356,377,381,422,432,433,460,464,470,471,480,490,504,526,527,530,555,556,557,580,587,627,628,633,645,646,660,661,662,687,713,735,769,782,797,801,849,880,890,892,895,896,909,926,928,940,942,948,988,989,997,1009,1043,1053,1071,1096,1097,1102,1106,1123,1153,1163,1173,1200,1218,1221,1230,1236,1241,1262,1273,1281,1325,1326,1407,1550,1592,1685,1688,1695,1700,1701,1753,1755,1756,1760,1764,1765,1766', '1', '2', '0', '1', '2', NULL, '2024-06-19 21:50:07', '2025-04-21 11:11:49'),
(8, 'GE2', '2,22,36,44,60,61,69,88,91,112,148,162,183,185,197,202,208,224,255,263,268,277,298,324,337,366,367,378,383,389,395,398,411,421,423,430,435,449,454,486,507,521,525,559,571,574,583,585,592,606,614,634,636,652,696,717,747,773,781,792,807,819,832,897,904,905,907,970,1088,1116,1143,1205,1242,1396,1397,1399,1400,1409,1459,1485,1488', '3,25,39,48,49,65,66,67,68,76,95,98,121,122,159,174,175,176,177,198,200,212,213,218,224,240,271,279,286,287,296,319,346,359,360,392,393,394,406,413,419,425,428,443,454,456,463,468,484,489,521,543,559,563,599,611,612,615,632,634,635,642,656,666,667,689,691,707,752,753,775,805,831,839,851,866,878,900,992,999,1000,1002,1065,1188,1189,1220,1247,1309,1338,1339,1340,1341,1342,1343,1344,1345,1346,1347,1348,1349,1350,1351,1352,1353,1354,1355,1356,1357,1358,1359,1360,1361,1362,1363,1364,1365,1366,1367,1368,1369,1370,1371,1372,1373,1374,1375,1376,1377,1378,1379,1380,1381,1382,1383,1384,1385,1386,1387,1388,1389,1390,1391,1392,1393,1394,1395,1405,1686,1687,1689,1690,1698,1747,1774,1777,1786,1787,1789,1790,1791,1800', '1', '2', '0', '1', '68', NULL, '2024-06-19 21:50:07', '2025-06-14 16:40:31'),
(9, 'GG', '865,869,882,886,896,909,911,913,920,932,938,957,962,966,968,973,975,987,990,992,1000,1015,1016,1034,1041,1045,1046,1052,1056,1066,1075,1083,1086,1090,1104,1106,1110,1115,1124,1128,1129,1130,1131,1133,1136,1139,1144,1155,1162,1170,1172,1180,1181,1184,1186,1188,1195,1208,1211,1213,1216,1220,1227,1232,1237,1246,1251,1255,1261,1270,1271', '933,937,950,954,991,1004,1006,1008,1015,1027,1033,1052,1057,1061,1063,1068,1070,1082,1085,1087,1095,1111,1112,1113,1114,1115,1133,1140,1144,1145,1146,1152,1156,1166,1175,1183,1186,1191,1192,1207,1208,1210,1214,1219,1228,1232,1233,1234,1235,1237,1240,1243,1248,1259,1266,1274,1276,1284,1285,1288,1290,1292,1299,1312,1315,1317,1320,1324,1331,1337,1400,1409,1537,1541,1547,1556,1557', '1', '2', '0', '1', '2', NULL, '2024-07-30 21:50:07', '2025-05-03 13:12:23'),
(10, 'GF', '868,871,875,877,884,889,891,892,895,901,906,908,910,919,929,930,931,935,937,941,942,945,946,950,954,967,974,977,978,979,984,986,991,1008,1012,1013,1014,1019,1021,1031,1032,1042,1044,1047,1051,1059,1060,1064,1068,1071,1072,1076,1078,1079,1084,1094,1095,1096,1097,1102,1107,1109,1119,1122,1134,1141,1146,1161,1168,1174,1187,1190,1191,1192,1193,1198,1201,1210,1218,1224,1229,1231,1238,1245,1247,1252,1258,1259,1268,1274', '936,939,943,945,952,957,959,987,990,996,1001,1003,1005,1014,1024,1025,1026,1030,1032,1036,1037,1040,1041,1045,1049,1062,1069,1072,1073,1074,1079,1081,1086,1104,1108,1109,1110,1118,1120,1130,1131,1141,1143,1147,1151,1159,1160,1164,1168,1171,1172,1176,1178,1179,1184,1196,1197,1198,1199,1205,1211,1213,1223,1226,1238,1245,1250,1265,1272,1278,1291,1294,1295,1296,1297,1302,1305,1314,1322,1328,1333,1335,1336,1401,1408,1410,1538,1544,1545,1554,1560', '1', '2', '0', '1', '2', NULL, '2024-07-30 21:50:07', '2024-07-30 14:49:23'),
(11, 'GB1', '38,82,83,101,105,123,125,144,163,165,169,175,177,240,260,267,272,310,319,320,338,341,373,394,402,404,408,453,484,612,615,617,619,622,624,626,633,639,642,644,645,650,653,657,659,661,664,665,667,668,669,672,676,678,681,684,686,691,697,702,703,705,706,710,713,719,723,726,727,728,730,731,735,741,742,744,751,757,758,770,771,774,775,776,784,785,794,804,810,811,818,822,826,827,842,846,848,854,1145,1280,1281,1282,1286,1288,1290,1291,1294,1299,1300,1304,1309,1312,1313,1314,1315,1316,1317,1319,1321,1323,1327,1328,1329,1330,1331,1332,1333,1334,1335,1336,1337,1338,1339,1341,1353,1449,1473,1496', '41,89,90,109,113,133,135,155,178,180,184,190,192,256,276,284,285,291,332,341,342,361,364,400,424,434,436,440,488,519,664,668,670,672,675,677,679,688,694,697,699,700,705,708,712,714,716,717,720,721,723,724,725,728,732,734,737,740,742,747,754,759,760,762,763,768,771,777,781,784,785,786,788,789,793,799,800,802,809,815,816,828,829,832,833,834,843,844,853,863,869,870,877,881,885,886,897,910,914,916,922,1249,1566,1567,1568,1572,1574,1576,1577,1580,1585,1586,1590,1595,1598,1599,1600,1601,1602,1603,1605,1607,1609,1613,1614,1615,1617,1618,1619,1620,1621,1622,1623,1624,1625,1626,1628,1641,1738,1761,1794,1797,1798', '1', '4', '0', '1', '68', NULL, '2025-01-12 22:37:41', '2025-06-01 14:26:51'),
(12, 'GB2', '21,32,62,164,178,182,186,190,206,220,261,291,295,544,576,616,623,627,635,641,648,662,670,671,674,682,687,688,689,700,708,709,715,716,725,732,736,752,755,760,764,769,778,779,782,786,791,796,799,816,820,831,834,836,837,838,859,936,1283,1289,1292,1293,1297,1318,1340,1342,1343,1344,1345,1346,1347,1348,1349,1350,1351,1352,1354,1355,1356,1357,1435,1474,1481,1483,1484,1486,1487,1491,1494,1495', '24,35,69,179,193,197,201,205,222,236,277,312,316,582,583,584,617,669,676,680,690,696,703,718,726,727,730,738,743,744,745,757,765,766,767,773,774,783,790,794,810,813,818,822,827,836,837,840,841,845,850,855,858,875,879,899,902,904,905,906,927,1031,1569,1575,1578,1579,1583,1604,1627,1629,1630,1631,1632,1633,1634,1635,1636,1637,1638,1639,1640,1642,1643,1644,1645,1724,1762,1770,1772,1773,1775,1776,1782,1783,1795,1796', '1', '4', '0', '1', '68', NULL, '2025-01-12 22:42:29', '2025-06-01 14:04:48'),
(13, 'GE3', '1,9,16,31,34,40,52,84,89,137,146,149,156,173,191,192,195,198,199,201,232,242,262,278,282,286,287,289,306,307,358,379,381,382,399,405,410,413,418,424,438,452,463,465,468,470,497,498,499,503,549,563,565,573,594,598,714,740,765,788,829,900,925,933,959,964,965,1022,1023,1030,1034,1037,1049,1065,1081,1085,1125,1127,1178,1184,1196,1217,1233,1248,1305,1358,1359,1360,1361,1362,1363,1364,1365,1366,1367,1368,1369,1370,1371,1372,1373,1374,1375,1376,1377,1479,1492,1497,1498', '1,2,10,11,18,34,37,43,57,91,96,148,157,160,161,168,188,206,207,210,214,215,217,248,258,278,297,301,302,306,307,309,327,328,329,382,407,408,410,411,412,429,430,437,442,445,450,451,457,472,487,498,500,503,505,533,534,535,539,589,603,605,614,644,648,772,798,823,847,888,894,898,995,1020,1028,1054,1059,1060,1121,1122,1129,1136,1149,1165,1181,1185,1229,1231,1282,1300,1321,1396,1534,1591,1616,1646,1647,1648,1649,1650,1651,1652,1653,1654,1655,1656,1657,1658,1659,1660,1661,1662,1663,1664,1665,1666,1667,1768,1792,1799,1801', '1', '2', '0', '1', '68', NULL, '2025-01-12 22:46:00', '2025-06-14 16:41:39');

-- --------------------------------------------------------

--
-- Table structure for table `area_line_mapping`
--

CREATE TABLE `area_line_mapping` (
  `map_id` int(11) NOT NULL,
  `line_name` varchar(255) DEFAULT NULL,
  `area_id` longtext DEFAULT NULL,
  `sub_area_id` longtext DEFAULT NULL,
  `company_id` varchar(255) DEFAULT NULL,
  `branch_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT '0',
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `area_line_mapping`
--

INSERT INTO `area_line_mapping` (`map_id`, `line_name`, `area_id`, `sub_area_id`, `company_id`, `branch_id`, `status`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date`) VALUES
(1, 'A1', '5,24,30,33,35,49,65,66,79,85,92,100,102,116,117,179,180,196,209,212,243,247,253,256,258,266,280,284,285,301,317,326,328,329,330,340,350,364,371,372,375,387,400,403,406,426,447,448,451,472,506,509,523,524,527,528,529,530,532,534,539,546,551,558,562,566,567,569,577,586,597,600,604,607,608,611,628,629,630,643,646,654,655,660,685,699,738,749,766,767,768,787,793,813,815,824,830,835,844,847,855,856,861,862,864,867,879,885,903,921,924,944,952,963,971,981,982,983,988,989,994,995,1002,1003,1004,1017,1028,1033,1038,1040,1048,1050,1055,1062,1069,1092,1101,1103,1105,1108,1111,1120,1121,1135,1138,1140,1151,1153,1154,1156,1160,1163,1167,1171,1173,1175,1179,1200,1202,1204,1207,1209,1214,1215,1219,1225,1228,1230,1234,1235,1236,1239,1240,1241,1243,1249,1253,1254,1256,1257,1260,1266,1267,1273,1284,1287,1298,1320,1322,1403,1454,1455,1456,1457,1463,1480', '6,27,33,36,38,54,72,73,86,92,99,108,110,126,127,194,195,211,225,228,259,263,269,272,274,282,283,299,304,305,322,339,348,350,351,352,363,373,374,389,398,399,402,417,431,435,438,459,482,483,486,507,542,545,561,562,565,566,567,568,570,572,577,586,591,598,602,606,607,609,618,619,620,621,622,623,624,636,647,650,654,657,658,663,681,682,683,684,685,698,701,709,710,715,741,756,796,807,824,825,826,846,852,872,874,883,889,903,912,915,923,924,929,930,932,935,947,953,998,1016,1019,1039,1047,1058,1066,1076,1077,1078,1083,1084,1089,1090,1098,1099,1100,1116,1127,1132,1137,1139,1148,1150,1155,1162,1169,1194,1204,1206,1209,1212,1215,1224,1225,1239,1242,1244,1255,1257,1258,1260,1264,1267,1271,1275,1277,1279,1283,1304,1306,1308,1311,1313,1318,1319,1323,1329,1332,1334,1397,1398,1399,1402,1403,1404,1406,1411,1412,1413,1414,1415,1416,1417,1418,1419,1420,1421,1422,1423,1424,1425,1426,1427,1428,1429,1430,1431,1432,1433,1434,1435,1436,1437,1438,1439,1440,1441,1442,1443,1444,1445,1446,1447,1448,1449,1450,1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461,1462,1463,1464,1465,1466,1467,1468,1469,1470,1471,1472,1473,1474,1475,1476,1477,1478,1479,1480,1481,1482,1483,1484,1485,1486,1487,1488,1489,1490,1491,1492,1493,1494,1495,1496,1497,1498,1499,1500,1501,1502,1503,1504,1505,1506,1507,1508,1509,1510,1511,1512,1513,1514,1515,1516,1517,1518,1519,1520,1521,1522,1523,1524,1525,1526,1527,1528,1529,1530,1531,1532,1533,1535,1539,1540,1542,1543,1546,1552,1553,1559,1570,1573,1584,1606,1608,1693,1743,1744,1745,1746,1751,1769,1778,1779,1784', '1', '1', '0', '1', '68', NULL, '2024-06-19 21:45:58', '2025-05-08 17:57:07'),
(2, 'A2', '8,10,11,14,26,28,41,45,55,56,68,70,73,110,114,118,120,145,157,159,166,167,172,194,200,203,204,207,211,213,215,216,227,230,236,246,250,269,288,292,293,294,296,297,305,308,311,313,315,345,354,362,368,380,396,397,433,440,441,443,446,450,459,466,471,475,478,479,480,489,492,496,505,508,510,511,553,556,560,581,589,591,601,602,625,638,656,683,692,701,707,712,734,805,833,843,866,928,956,999,1007,1057,1061,1118,1152,1182,1183,1279,1301,1307,1325,1451,1453,1464,1470,1471,1489', '9,12,13,16,29,31,44,50,60,61,75,77,80,119,124,128,130,156,169,171,181,182,187,209,216,219,220,223,227,229,231,232,243,246,252,262,266,288,308,313,314,315,317,318,326,330,333,335,337,368,378,386,387,395,409,426,427,466,474,475,477,478,481,485,494,501,506,510,513,514,515,524,528,532,541,544,546,547,593,596,600,629,630,639,641,651,652,678,693,711,739,748,758,764,770,792,864,901,911,934,1023,1051,1094,1103,1157,1161,1222,1256,1286,1287,1565,1587,1593,1611,1740,1742,1752,1758,1759,1780,1785', '1', '1', '0', '1', '68', NULL, '2024-06-19 21:45:58', '2025-05-09 14:06:36'),
(3, 'A3', '7,13,57,58,59,63,75,93,94,95,109,111,115,128,129,130,139,140,141,142,143,147,153,160,161,168,205,210,214,217,219,221,225,229,231,237,238,239,244,245,251,259,270,274,275,300,312,314,332,356,361,374,384,385,386,412,415,416,420,428,436,442,458,460,462,467,474,477,481,483,487,488,495,500,501,504,512,513,516,517,518,526,531,533,535,536,538,540,541,543,545,568,570,575,578,579,582,587,599,613,637,640,698,745,746,748,750,762,763,777,802,840,857,876,878,887,899,916,917,922,923,1009,1011,1018,1226,1262,1296,1326,1460,1462,1475,1478,1482,1493', '8,15,62,63,64,70,82,100,101,102,103,118,120,125,139,140,141,150,151,152,153,154,158,165,172,173,183,221,226,230,233,235,237,241,245,247,253,254,255,260,261,267,275,289,293,294,321,334,336,354,380,385,401,414,415,416,444,447,448,453,461,469,476,493,495,497,502,509,512,516,518,522,523,531,536,537,540,548,549,552,553,554,564,569,571,573,574,576,578,579,581,585,608,610,616,625,626,631,637,649,665,692,695,755,803,804,806,808,820,821,835,861,891,893,908,925,944,946,955,994,1011,1012,1017,1018,1105,1107,1117,1330,1548,1582,1612,1748,1750,1763,1767,1771,1788,1793', '1', '1', '0', '1', '68', NULL, '2024-06-19 21:45:58', '2025-05-30 19:45:02'),
(4, 'B', '369', '396', '1', '4', '0', '1', '2', NULL, '2024-06-19 21:45:58', '2025-04-19 16:58:58'),
(5, 'D1', '4,19,20,23,29,42,43,47,48,50,53,64,72,76,80,96,97,113,119,124,126,127,131,134,135,136,154,158,170,189,193,218,222,226,235,241,249,254,257,279,303,304,316,321,322,323,325,327,333,336,343,344,347,348,349,352,360,363,376,377,390,391,393,429,432,439,444,456,461,464,473,476,485,490,502,515,520,522,554,555,564,572,590,593,603,605,609,620,631,663,675,680,690,693,694,704,720,721,722,729,754,756,759,761,772,783,797,806,812,817,825,828,839,851,853,863,870,873,881,888,898,915,926,927,934,939,940,943,947,949,951,953,955,969,972,980,985,993,996,997,998,1005,1020,1025,1026,1027,1029,1035,1036,1039,1043,1058,1067,1070,1074,1077,1082,1093,1099,1100,1112,1113,1123,1142,1147,1148,1149,1150,1157,1159,1164,1166,1176,1185,1189,1194,1197,1199,1203,1206,1212,1223,1250,1263,1265,1269,1272,1275,1277,1285,1295,1302,1303,1308,1324,1378,1380,1381,1382,1383,1386,1387,1388,1389,1390,1392,1394,1402,1406,1410,1412,1413,1414,1415,1416,1417,1418,1419,1420,1421,1422,1423,1424,1425,1426,1427,1428,1429,1430,1431,1433,1434,1448,1450,1452,1461,1469', '5,21,22,23,26,32,45,46,47,52,53,55,58,71,79,83,87,104,105,123,129,134,136,137,138,142,145,146,147,166,170,185,204,208,234,238,242,251,257,265,270,273,298,324,325,338,343,344,345,347,349,355,358,366,367,370,371,372,376,384,388,403,404,405,420,421,423,462,465,473,479,491,496,499,508,511,520,525,538,551,558,560,594,595,604,613,640,643,653,655,659,673,686,719,731,736,746,749,750,761,778,779,780,787,812,814,817,819,830,842,856,865,871,876,884,887,907,919,921,931,938,941,949,956,993,1010,1021,1022,1029,1034,1035,1038,1042,1044,1046,1048,1050,1064,1067,1075,1080,1088,1091,1092,1093,1101,1119,1124,1125,1126,1128,1134,1135,1138,1142,1158,1167,1170,1174,1177,1182,1195,1201,1202,1203,1216,1217,1227,1246,1251,1252,1253,1254,1261,1263,1268,1270,1280,1289,1293,1298,1301,1303,1307,1310,1316,1327,1536,1549,1551,1555,1558,1561,1563,1571,1581,1588,1589,1594,1610,1669,1670,1671,1672,1673,1676,1677,1678,1679,1680,1682,1684,1692,1696,1699,1702,1703,1704,1705,1706,1707,1708,1709,1710,1711,1712,1713,1714,1715,1716,1717,1718,1719,1720,1721,1722,1723,1737,1739,1741,1749,1757', '1', '3', '0', '1', '71', NULL, '2024-06-19 21:45:58', '2025-04-22 12:10:17'),
(6, 'D2', '6,12,17,27,46,54,71,74,81,86,87,90,98,99,103,104,106,121,122,132,171,174,176,184,187,223,228,233,234,248,252,264,265,273,281,299,302,309,318,335,339,342,346,351,355,359,365,370,388,407,409,414,417,419,422,425,434,457,482,493,514,537,548,550,552,557,561,588,618,621,647,649,651,666,673,677,695,718,733,737,753,780,789,795,798,800,801,803,808,809,814,823,845,849,850,852,883,890,912,918,960,961,1054,1080,1087,1089,1091,1165,1278,1310,1311,1379,1384,1385,1391,1401,1466,1499', '7,14,19,30,51,59,78,81,88,93,94,97,106,107,111,112,114,131,132,143,186,189,191,199,202,239,244,249,250,264,268,280,281,292,300,320,323,331,340,357,362,365,369,375,379,383,390,391,397,418,439,441,446,449,452,455,458,467,492,517,529,550,575,588,590,592,597,601,638,671,674,702,704,706,722,729,733,751,776,791,795,811,838,848,854,857,859,860,862,867,868,873,882,913,917,918,920,951,958,960,961,962,963,964,965,966,967,968,969,970,971,972,973,974,975,976,977,978,979,980,981,982,983,984,985,986,1007,1013,1055,1056,1154,1180,1187,1190,1193,1269,1564,1596,1597,1668,1674,1675,1681,1691,1754,1802', '1', '3', '0', '1', '68', NULL, '2024-06-19 21:45:58', '2025-06-14 16:36:08'),
(7, 'E1', '3,15,18,25,37,51,67,77,78,107,108,133,138,150,151,152,155,181,188,271,276,283,290,331,334,353,357,392,401,427,431,437,445,455,469,491,494,519,542,547,580,584,595,596,610,632,658,679,711,724,739,743,790,821,841,858,860,872,874,880,893,894,902,914,948,958,976,1001,1006,1010,1024,1053,1063,1073,1098,1114,1117,1126,1132,1137,1158,1169,1177,1221,1222,1244,1264,1306,1395,1398,1405,1411,1465,1467,1468,1472,1476,1477', '4,17,20,28,40,56,74,84,85,115,116,117,144,149,162,163,164,167,196,203,290,295,303,310,311,353,356,377,381,422,432,433,460,464,470,471,480,490,504,526,527,530,555,556,557,580,587,627,628,633,645,646,660,661,662,687,713,735,769,782,797,801,849,880,890,892,895,896,909,926,928,940,942,948,988,989,997,1009,1043,1053,1071,1096,1097,1102,1106,1123,1153,1163,1173,1200,1218,1221,1230,1236,1241,1262,1273,1281,1325,1326,1407,1550,1592,1685,1688,1695,1700,1753,1755,1756,1760,1764,1765,1766', '1', '2', '0', '1', '71', NULL, '2024-06-19 21:50:07', '2025-04-22 12:19:28'),
(8, 'E2', '2,22,36,44,60,61,69,88,91,112,148,162,183,185,197,202,208,224,255,263,268,277,298,324,337,366,367,378,383,389,395,398,411,421,423,430,435,449,454,486,507,521,525,559,571,574,583,585,592,606,614,634,636,652,696,717,747,773,781,792,807,819,832,897,904,905,907,970,1088,1116,1143,1205,1242,1396,1397,1399,1400,1409,1459,1485,1488', '3,25,39,48,49,65,66,67,68,76,95,98,121,122,159,174,175,176,177,198,200,212,213,218,224,240,271,279,286,287,296,319,346,359,360,392,393,394,406,413,419,425,428,443,454,456,463,468,484,489,521,543,559,563,599,611,612,615,632,634,635,642,656,666,667,689,691,707,752,753,775,805,831,839,851,866,878,900,992,999,1000,1002,1065,1188,1189,1220,1247,1309,1338,1339,1340,1341,1342,1343,1344,1345,1346,1347,1348,1349,1350,1351,1352,1353,1354,1355,1356,1357,1358,1359,1360,1361,1362,1363,1364,1365,1366,1367,1368,1369,1370,1371,1372,1373,1374,1375,1376,1377,1378,1379,1380,1381,1382,1383,1384,1385,1386,1387,1388,1389,1390,1391,1392,1393,1394,1395,1405,1686,1687,1689,1690,1698,1747,1774,1777,1786,1787,1789,1790,1791,1800', '1', '2', '0', '1', '70', NULL, '2024-06-19 21:50:07', '2025-06-16 16:57:10'),
(9, 'F', '868,871,875,877,884,889,891,892,895,901,906,908,910,919,929,930,931,935,937,941,942,945,946,950,954,967,974,977,978,979,984,986,991,1008,1012,1013,1014,1019,1021,1031,1032,1042,1044,1047,1051,1059,1060,1064,1068,1071,1072,1076,1078,1079,1084,1094,1095,1096,1097,1102,1107,1109,1119,1122,1134,1141,1146,1161,1168,1174,1187,1190,1191,1192,1193,1198,1201,1210,1218,1224,1229,1231,1238,1245,1247,1252,1258,1259,1268,1274', '936,939,943,945,952,957,959,987,990,996,1001,1003,1005,1014,1024,1025,1026,1030,1032,1036,1037,1040,1041,1045,1049,1062,1069,1072,1073,1074,1079,1081,1086,1104,1108,1109,1110,1118,1120,1130,1131,1141,1143,1147,1151,1159,1160,1164,1168,1171,1172,1176,1178,1179,1184,1196,1197,1198,1199,1205,1211,1213,1223,1226,1238,1245,1250,1265,1272,1278,1291,1294,1295,1296,1297,1302,1305,1314,1322,1328,1333,1335,1336,1401,1408,1410,1538,1544,1545,1554,1560', '1', '2', '0', '1', '2', NULL, '2024-07-30 21:50:07', '2024-07-30 14:49:23'),
(10, 'G', '865,869,882,886,896,909,911,913,920,932,938,957,962,966,968,973,975,987,990,992,1000,1015,1016,1034,1041,1045,1046,1052,1056,1066,1075,1083,1086,1090,1104,1106,1110,1115,1124,1128,1129,1130,1131,1133,1136,1139,1144,1155,1162,1170,1172,1180,1181,1184,1186,1188,1195,1208,1211,1213,1216,1220,1227,1232,1237,1246,1251,1255,1261,1270,1271', '933,937,950,954,991,1004,1006,1008,1015,1027,1033,1052,1057,1061,1063,1068,1070,1082,1085,1087,1095,1111,1112,1113,1114,1115,1133,1140,1144,1145,1146,1152,1156,1166,1175,1183,1186,1191,1192,1207,1208,1210,1214,1219,1228,1232,1233,1234,1235,1237,1240,1243,1248,1259,1266,1274,1276,1284,1285,1288,1290,1292,1299,1312,1315,1317,1320,1324,1331,1337,1400,1409,1537,1541,1547,1556,1557', '1', '2', '0', '1', '2', NULL, '2024-07-30 21:50:07', '2025-05-03 13:20:00'),
(11, 'B1', '38,82,83,101,105,123,125,144,163,165,169,175,177,240,260,267,272,310,319,320,338,341,373,394,402,404,408,453,484,612,615,617,619,622,624,626,633,639,642,644,645,650,653,657,659,661,664,665,667,668,669,672,676,678,681,684,686,691,697,702,703,705,706,710,713,719,723,726,727,728,730,731,735,741,742,744,751,757,758,770,771,774,775,776,784,785,794,804,810,811,818,822,826,827,842,846,848,854,1145,1280,1281,1282,1286,1288,1290,1291,1294,1299,1300,1304,1309,1312,1313,1314,1315,1316,1317,1319,1321,1323,1327,1328,1329,1330,1331,1332,1333,1334,1335,1336,1337,1338,1339,1341,1353,1449,1473,1496', '41,89,90,109,113,133,135,155,178,180,184,190,192,256,276,284,285,291,332,341,342,361,364,400,424,434,436,440,488,519,664,668,670,672,675,677,679,688,694,697,699,700,705,708,712,714,716,717,720,721,723,724,725,728,732,734,737,740,742,747,754,759,760,762,763,768,771,777,781,784,785,786,788,789,793,799,800,802,809,815,816,828,829,832,833,834,843,844,853,863,869,870,877,881,885,886,897,910,914,916,922,1249,1566,1567,1568,1572,1574,1576,1577,1580,1585,1586,1590,1595,1598,1599,1600,1601,1602,1603,1605,1607,1609,1613,1614,1615,1617,1618,1619,1620,1621,1622,1623,1624,1625,1626,1628,1641,1738,1761,1794,1797,1798', '1', '4', '0', '1', '68', NULL, '2025-01-12 23:17:04', '2025-06-01 14:28:14'),
(12, 'B2', '21,32,62,164,178,182,186,190,206,220,261,291,295,544,576,616,623,627,635,641,648,662,670,671,674,682,687,688,689,700,708,709,715,716,725,732,736,752,755,760,764,769,778,779,782,786,791,796,799,816,820,831,834,836,837,838,859,936,1283,1289,1292,1293,1297,1318,1340,1342,1343,1344,1345,1346,1347,1348,1349,1350,1351,1352,1354,1355,1356,1357,1435,1474,1481,1483,1484,1486,1487,1491,1494,1495', '24,35,69,179,193,197,201,205,222,236,277,312,316,582,583,584,617,669,676,680,690,696,703,718,726,727,730,738,743,744,745,757,765,766,767,773,774,783,790,794,810,813,818,822,827,836,837,840,841,845,850,855,858,875,879,899,902,904,905,906,927,1031,1569,1575,1578,1579,1583,1604,1627,1629,1630,1631,1632,1633,1634,1635,1636,1637,1638,1639,1640,1642,1643,1644,1645,1724,1762,1770,1772,1773,1775,1776,1782,1783,1795,1796', '1', '4', '0', '1', '68', NULL, '2025-01-12 23:17:57', '2025-06-01 14:06:11'),
(13, 'E3', '1,9,16,31,34,40,52,84,89,137,146,149,156,173,191,192,195,198,199,201,232,242,262,278,282,286,287,289,306,307,358,379,381,382,399,405,410,413,418,424,438,452,463,465,468,470,497,498,499,503,549,563,565,573,594,598,714,740,765,788,829,900,925,933,959,964,965,1022,1023,1034,1037,1049,1065,1081,1085,1125,1127,1178,1184,1196,1217,1233,1248,1305,1358,1359,1360,1361,1362,1363,1364,1365,1366,1367,1368,1369,1370,1371,1372,1373,1374,1375,1376,1377,1479,1492,1497,1498', '1,2,10,11,18,34,37,43,57,91,96,148,157,160,161,168,188,206,207,210,214,215,217,248,258,278,297,301,302,306,307,309,327,328,329,382,407,408,410,411,412,429,430,437,442,445,450,451,457,472,487,498,500,503,505,533,534,535,539,589,603,605,614,644,648,772,798,823,847,888,894,898,995,1020,1028,1054,1059,1060,1121,1122,1136,1149,1165,1181,1185,1229,1231,1282,1300,1321,1396,1534,1591,1616,1646,1647,1648,1649,1650,1651,1652,1653,1654,1655,1656,1657,1658,1659,1660,1661,1662,1663,1664,1665,1666,1667,1768,1792,1799,1801', '1', '2', '0', '1', '68', NULL, '2025-01-12 23:19:03', '2025-06-14 16:46:07'),
(14, 'A2', '1490', '1781', '1', '1', '0', '68', NULL, NULL, '2025-05-06 12:30:09', '2025-05-06 12:30:09');

-- --------------------------------------------------------

--
-- Table structure for table `area_list_creation`
--

CREATE TABLE `area_list_creation` (
  `area_id` int(11) NOT NULL,
  `area_name` varchar(255) DEFAULT NULL,
  `taluk` varchar(255) DEFAULT NULL,
  `area_enable` int(11) NOT NULL DEFAULT 0,
  `status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `area_list_creation`
--

INSERT INTO `area_list_creation` (`area_id`, `area_name`, `taluk`, `area_enable`, `status`) VALUES
(1, 'A P Chathiram', 'Uthiramerur', 0, 0),
(2, 'Aalappakkam', 'Uthiramerur', 0, 0),
(3, 'Aalathur', 'Cheyyar', 0, 0),
(4, 'Aaliyur', 'Chetpet', 0, 0),
(5, 'Aariyathur VSI', 'Vandavasi', 0, 0),
(6, 'Aathurai', 'Chetpet', 0, 0),
(7, 'Achamangalam', 'Vandavasi', 0, 0),
(8, 'Acharapakkam', 'Maduranthakam', 0, 0),
(9, 'Adhavapakkam', 'Uthiramerur', 0, 0),
(10, 'Adhiyankuppam', 'Vandavasi', 0, 0),
(11, 'Adhiyanur', 'Vandavasi', 0, 0),
(12, 'Adukkupasi', 'Chetpet', 0, 0),
(13, 'Agarakorakottai', 'Vandavasi', 0, 0),
(14, 'Agathigapuram', 'Vandavasi', 0, 0),
(15, 'Akkur', 'Cheyyar', 0, 0),
(16, 'Alancheri', 'Uthiramerur', 0, 0),
(17, 'Alanthangal CPT', 'Chetpet', 0, 0),
(18, 'Alathurai', 'Vandavasi', 0, 0),
(19, 'Aliyanthal', 'Chetpet', 0, 0),
(20, 'Alliputhur', 'Chetpet', 0, 0),
(21, 'Alliyalamangalam', 'Polur', 0, 0),
(22, 'Alwarpoondi', 'Uthiramerur', 0, 0),
(23, 'Amaravathipattinam CPT', 'Chetpet', 0, 0),
(24, 'Ammaiyapattu', 'Vandavasi', 0, 0),
(25, 'Ammaiyappanallur', 'Uthiramerur', 0, 0),
(26, 'Ammanambakkam', 'Vandavasi', 0, 0),
(27, 'Ammanthangal', 'Chetpet', 0, 0),
(28, 'Amudur', 'Vandavasi', 0, 0),
(29, 'Anadhimangalam', 'Chetpet', 0, 0),
(30, 'Anaibogi', 'Vandavasi', 0, 0),
(31, 'Anaipallam', 'Uthiramerur', 0, 0),
(32, 'Anaipettai', 'Polur', 0, 0),
(33, 'Anakkavoor', 'Cheyyar', 0, 0),
(34, 'Anambakkam', 'Uthiramerur', 0, 0),
(35, 'Anapathur', 'Cheyyar', 0, 0),
(36, 'Andavakkam', 'Maduranthakam', 0, 0),
(37, 'Andithangal', 'Uthiramerur', 0, 0),
(38, 'Aniyalai', 'Polur', 0, 0),
(39, 'Anmarudhai', 'Vandavasi', 0, 1),
(40, 'Appaiyanallur', 'Uthiramerur', 0, 0),
(41, 'Appanthangal', 'Vandavasi', 0, 0),
(42, 'Appedu', 'Chetpet', 0, 0),
(43, 'Arasampattu', 'Chetpet', 0, 0),
(44, 'Arasanimangalam', 'Uthiramerur', 0, 0),
(45, 'Aarasur', 'Vandavasi', 0, 0),
(46, 'Arayalam', 'Chetpet', 0, 0),
(47, 'Ariyapadi', 'Chetpet', 0, 0),
(48, 'Ariyapuravadai', 'Chetpet', 0, 0),
(49, 'Ariyathur VSI', 'Vandavasi', 0, 0),
(50, 'Arkampoondi', 'Chetpet', 0, 0),
(51, 'Arokiyapuram', 'Uthiramerur', 0, 0),
(52, 'Arpakkam UTR', 'Uthiramerur', 0, 0),
(53, 'Arulnadu', 'Chetpet', 0, 0),
(54, 'Arumbalur CPT', 'Chetpet', 0, 0),
(55, 'Arungunam', 'Vandavasi', 0, 0),
(56, 'Arunkundram VSI', 'Vandavasi', 0, 0),
(57, 'Aruvadaithangal', 'Vandavasi', 0, 0),
(58, 'Asthinapuram', 'Vandavasi', 0, 0),
(59, 'Athipakkam', 'Vandavasi', 0, 0),
(60, 'Athiyur', 'Uthiramerur', 0, 0),
(61, 'Athiyur Melduli', 'Uthiramerur', 0, 0),
(62, 'Athuvambadi', 'Polur', 0, 0),
(63, 'Avanavadi', 'Vandavasi', 0, 0),
(64, 'Avaniyapuram VSI', 'Vandavasi', 0, 0),
(65, 'Ayalavadi', 'Vandavasi', 0, 0),
(66, 'Ayyavadi', 'Vandavasi', 0, 0),
(67, 'Azhisoor', 'Uthiramerur', 0, 0),
(68, 'Badur', 'Vandavasi', 0, 0),
(69, 'Bharathipuram', 'Uthiramerur', 0, 0),
(70, 'Birudur', 'Vandavasi', 0, 0),
(71, 'Boothamangalam VSI', 'Vandavasi', 0, 0),
(72, 'Chandrampadi', 'Chetpet', 0, 0),
(73, 'Chennavaram', 'Vandavasi', 0, 0),
(74, 'Chetpet', 'Chetpet', 0, 0),
(75, 'Chettikulam', 'Vandavasi', 0, 0),
(76, 'Cheyyanandal', 'Chetpet', 0, 0),
(77, 'Chinna Andithangal', 'Uthiramerur', 0, 0),
(78, 'Chinna Azhisoor', 'Uthiramerur', 0, 0),
(79, 'Chinna Chetpet', 'Vandavasi', 0, 0),
(80, 'Chinna Kozhappalur', 'Chetpet', 0, 0),
(81, 'Chinna Nolambai', 'Chetpet', 0, 0),
(82, 'Chinnakallanthal', 'Polur', 0, 0),
(83, 'Chinnakalur', 'Polur', 0, 0),
(84, 'Chinnamakulam', 'Uthiramerur', 0, 0),
(85, 'Chinnathuraiyur', 'Vandavasi', 0, 0),
(86, 'Chinnavoor', 'Chetpet', 0, 0),
(87, 'Chithadurai', 'Chetpet', 0, 0),
(88, 'Chithalamangalam', 'Uthiramerur', 0, 0),
(89, 'Chithamoor', 'Uthiramerur', 0, 0),
(90, 'Chithragavoor', 'Chetpet', 0, 0),
(91, 'Chokkapallam UTR', 'Uthiramerur', 0, 0),
(92, 'Cholavaram VSI', 'Vandavasi', 0, 0),
(93, 'CM Pudur', 'Vandavasi', 0, 0),
(94, 'Desur', 'Vandavasi', 0, 0),
(95, 'Desur Madam', 'Vandavasi', 0, 0),
(96, 'Devanoor', 'Chetpet', 0, 0),
(97, 'Devanthavadi', 'Chetpet', 0, 0),
(98, 'Devigapuram', 'Chetpet', 0, 0),
(99, 'Devimangalam', 'Chetpet', 0, 0),
(100, 'Duraiyur', 'Vandavasi', 0, 0),
(101, 'Echankadu', 'Polur', 0, 0),
(102, 'Echur', 'Vandavasi', 0, 0),
(103, 'Edaiyankulathur', 'Chetpet', 0, 0),
(104, 'Edapattu', 'Chetpet', 0, 0),
(105, 'Edapirai', 'Polur', 0, 0),
(106, 'Edayampudhur CPT', 'Chetpet', 0, 0),
(107, 'Elanagar', 'Uthiramerur', 0, 0),
(108, 'Elaneer Kundram', 'Uthiramerur', 0, 0),
(109, 'Elangadu', 'Vandavasi', 0, 0),
(110, 'Elapakkam', 'Vandavasi', 0, 0),
(111, 'Embalam', 'Vandavasi', 0, 0),
(112, 'Endathur', 'Uthiramerur', 0, 0),
(113, 'Endhal CPT', 'Chetpet', 0, 0),
(114, 'Eramalur', 'Vandavasi', 0, 0),
(115, 'Eravadi', 'Vandavasi', 0, 0),
(116, 'Erumaivetti', 'Vandavasi', 0, 0),
(117, 'Erumbur', 'Vandavasi', 0, 0),
(118, 'Ettikuttai', 'Vandavasi', 0, 0),
(119, 'Ettithangal', 'Chetpet', 0, 0),
(120, 'Eyapakkam', 'Vandavasi', 0, 0),
(121, 'Eyyil', 'Chetpet', 0, 0),
(122, 'Ganapathipuram', 'Chetpet', 0, 0),
(123, 'Gandhi Nagar', 'Polur', 0, 0),
(124, 'Gangaisoodamani', 'Chetpet', 0, 0),
(125, 'Ganganallur', 'Polur', 0, 0),
(126, 'Gangapuram', 'Chetpet', 0, 0),
(127, 'Gnanothayam', 'Chetpet', 0, 0),
(128, 'Godhandapuram', 'Vandavasi', 0, 0),
(129, 'Goodalur', 'Vandavasi', 0, 0),
(130, 'Goonambadi', 'Vandavasi', 0, 0),
(131, 'Guduvampoondi', 'Chetpet', 0, 0),
(132, 'Gurukulanthangal', 'Chetpet', 0, 0),
(133, 'Hanumanthandalam', 'Uthiramerur', 0, 0),
(134, 'Immapuram', 'Chetpet', 0, 0),
(135, 'Indhiravanam', 'Chetpet', 0, 0),
(136, 'Injimedu', 'Chetpet', 0, 0),
(137, 'Irumaram', 'Uthiramerur', 0, 0),
(138, 'Irumbedu', 'Vandavasi', 0, 0),
(139, 'Irumbuli', 'Vandavasi', 0, 0),
(140, 'Isakolathur VSI', 'Vandavasi', 0, 0),
(141, 'Jambambattu', 'Vandavasi', 0, 0),
(142, 'Japthikarani', 'Vandavasi', 0, 0),
(143, 'Jengampoondi', 'Vandavasi', 0, 0),
(144, 'Kacherimangalam', 'Polur', 0, 0),
(145, 'Kadaisikulam', 'Vandavasi', 0, 0),
(146, 'Kadalmangalam', 'Uthiramerur', 0, 0),
(147, 'Kadambai', 'Vandavasi', 0, 0),
(148, 'Kadambur', 'Uthiramerur', 0, 0),
(149, 'Kaithandalam', 'Uthiramerur', 0, 0),
(150, 'Kaividanthangal UTR', 'Uthiramerur', 0, 0),
(151, 'Kakanallur', 'Uthiramerur', 0, 0),
(152, 'Kalambur UTR', 'Uthiramerur', 0, 0),
(153, 'Kalayanapuram VSI', 'Vandavasi', 0, 0),
(154, 'Kaligapuram', 'Chetpet', 0, 0),
(155, 'Kaliyampoondi', 'Uthiramerur', 0, 0),
(156, 'Kallama Nagar', 'Uthiramerur', 0, 0),
(157, 'Kallankuthu', 'Vandavasi', 0, 0),
(158, 'Kallapuliyur', 'Chetpet', 0, 0),
(159, 'Kallukollaimedu', 'Vandavasi', 0, 0),
(160, 'Kalpattu', 'Vandavasi', 0, 0),
(161, 'Kalyanamedu VSI', 'Vandavasi', 0, 0),
(162, 'Kamalampoondi', 'Uthiramerur', 0, 0),
(163, 'Kamalaputhur', 'Polur', 0, 0),
(164, 'Kamatchipuram', 'Polur', 0, 0),
(165, 'Kambattu', 'Polur', 0, 0),
(166, 'Kandaiyanallur', 'Vandavasi', 0, 0),
(167, 'Kandamanallur VSI', 'Vandavasi', 0, 0),
(168, 'Kandavaratti', 'Vandavasi', 0, 0),
(169, 'Kangeyanoor', 'Polur', 0, 0),
(170, 'Kannalam', 'Chetpet', 0, 0),
(171, 'Kannanur', 'Chetpet', 0, 0),
(172, 'Kannigapuram VSI', 'Vandavasi', 0, 0),
(173, 'Kannikulam', 'Uthiramerur', 0, 0),
(174, 'Kappalambadi', 'Chetpet', 0, 0),
(175, 'Kappalur', 'Polur', 0, 0),
(176, 'Karadikuppam', 'Chetpet', 0, 0),
(177, 'Karaipoondi', 'Polur', 0, 0),
(178, 'Karaiyambadi', 'Polur', 0, 0),
(179, 'Karam', 'Vandavasi', 0, 0),
(180, 'Karanai VSI', 'Vandavasi', 0, 0),
(181, 'Karanai mandapam', 'Uthiramerur', 0, 0),
(182, 'Karikathur', 'Polur', 0, 0),
(183, 'Karikili', 'Uthiramerur', 0, 0),
(184, 'Karipoor', 'Chetpet', 0, 0),
(185, 'Kariyamangalam', 'Uthiramerur', 0, 0),
(186, 'Karukalikuppam', 'Polur', 0, 0),
(187, 'Karungalmedu', 'Chetpet', 0, 0),
(188, 'Karuveppampoondi', 'Uthiramerur', 0, 0),
(189, 'Kasakal', 'Chetpet', 0, 0),
(190, 'Kasthambadi', 'Polur', 0, 0),
(191, 'Kattangulam', 'Uthiramerur', 0, 0),
(192, 'Kattiyampanthal', 'Uthiramerur', 0, 0),
(193, 'Kattu Thellur', 'Chetpet', 0, 0),
(194, 'Kattukaranai', 'Vandavasi', 0, 0),
(195, 'Kattukollai', 'Uthiramerur', 0, 0),
(196, 'Kattukudisai', 'Vandavasi', 0, 0),
(197, 'Kattupakkam UTR', 'Uthiramerur', 0, 0),
(198, 'Kattuputhur UTR', 'Uthiramerur', 0, 0),
(199, 'Kavampayir', 'Uthiramerur', 0, 0),
(200, 'Kavaniyathur', 'Vandavasi', 0, 0),
(201, 'Kavanthandalam', 'Uthiramerur', 0, 0),
(202, 'Kavanur Pudhucherry', 'Uthiramerur', 0, 0),
(203, 'Kavedu', 'Vandavasi', 0, 0),
(204, 'Kaveripakkam', 'Vandavasi', 0, 0),
(205, 'Kayanallur', 'Vandavasi', 0, 0),
(206, 'Kelur', 'Polur', 0, 0),
(207, 'Kil Kodungalur', 'Vandavasi', 0, 0),
(208, 'Kiliya Nagar', 'Uthiramerur', 0, 0),
(209, 'Kilkovalaivedu', 'Vandavasi', 0, 0),
(210, 'Kilnamandi', 'Vandavasi', 0, 0),
(211, 'Kilnarma', 'Vandavasi', 0, 0),
(212, 'Kilpadiri', 'Vandavasi', 0, 0),
(213, 'Kilpakkam', 'Vandavasi', 0, 0),
(214, 'Kilputhur', 'Vandavasi', 0, 0),
(215, 'Kilseesamangalam', 'Vandavasi', 0, 0),
(216, 'Kilvillivalam', 'Vandavasi', 0, 0),
(217, 'Kilvilliyur', 'Vandavasi', 0, 0),
(218, 'Kinnanur', 'Chetpet', 0, 0),
(219, 'Kizh Nanthiyampadi', 'Vandavasi', 0, 0),
(220, 'Kizh Pattu', 'Polur', 0, 0),
(221, 'Kizh Sathamangalam', 'Vandavasi', 0, 0),
(222, 'Kizh Sevalambadi', 'Chetpet', 0, 0),
(223, 'Kizhakkumedu', 'Chetpet', 0, 0),
(224, 'Kizhamur', 'Uthiramerur', 0, 0),
(225, 'KIzhangunam', 'Vandavasi', 0, 0),
(226, 'Kizhavampoondi', 'Chetpet', 0, 0),
(227, 'Kizhmaruvathoor', 'Vandavasi', 0, 0),
(228, 'Kizhmedu', 'Chetpet', 0, 0),
(229, 'Kizhputhur', 'Vandavasi', 0, 0),
(230, 'Kodanallur', 'Vandavasi', 0, 0),
(231, 'Kodiyalam', 'Vandavasi', 0, 0),
(232, 'Kodukkanthangal', 'Uthiramerur', 0, 0),
(233, 'Kolakaraivadi', 'Chetpet', 0, 0),
(234, 'Kolathur CPT', 'Chetpet', 0, 0),
(235, 'Konamangalam', 'Chetpet', 0, 0),
(236, 'Kondaiyankuppam', 'Vandavasi', 0, 0),
(237, 'Koothampattu', 'Vandavasi', 0, 0),
(238, 'Koothavedu', 'Vandavasi', 0, 0),
(239, 'Korakottai', 'Vandavasi', 0, 0),
(240, 'Koralpakkam', 'Polur', 0, 0),
(241, 'Korasalavadi CPT', 'Chetpet', 0, 0),
(242, 'Korukkanthangal', 'Uthiramerur', 0, 0),
(243, 'Kosapattu', 'Vandavasi', 0, 0),
(244, 'Kothandapuram', 'Vandavasi', 0, 0),
(245, 'Kothandavadi VSI', 'Vandavasi', 0, 0),
(246, 'Kottai', 'Vandavasi', 0, 0),
(247, 'Kottai Colony', 'Vandavasi', 0, 0),
(248, 'Kottaipoondi', 'Chetpet', 0, 0),
(249, 'Kottuppakkam', 'Chetpet', 0, 0),
(250, 'Kovalai VSI', 'Vandavasi', 0, 0),
(251, 'Kovanandhal', 'Vandavasi', 0, 0),
(252, 'Kovilpuraiyur', 'Chetpet', 0, 0),
(253, 'Kovilur', 'Vandavasi', 0, 0),
(254, 'Kozhappalur', 'Chetpet', 0, 0),
(255, 'Kozhiyalam', 'Uthiramerur', 0, 0),
(256, 'Krishnapuram VSI', 'Vandavasi', 0, 0),
(257, 'Kuduvampoondi', 'Chetpet', 0, 0),
(258, 'Kulathumedu', 'Vandavasi', 0, 0),
(259, 'Kunagampoondi', 'Vandavasi', 0, 0),
(260, 'Kunnadimedu', 'Polur', 0, 0),
(261, 'Kunnathur', 'Polur', 0, 0),
(262, 'Kunnavakkam', 'Uthiramerur', 0, 0),
(263, 'Kuppaiyanallur', 'Uthiramerur', 0, 0),
(264, 'Kuppam', 'Chetpet', 0, 0),
(265, 'Kurukulathangal', 'Chetpet', 0, 0),
(266, 'Kurumbur', 'Vandavasi', 0, 0),
(267, 'Kuruvimalai', 'Polur', 0, 0),
(268, 'L. Endathur', 'Uthiramerur', 0, 0),
(269, 'Ladakaranai', 'Vandavasi', 0, 0),
(270, 'Lakshmipuram VSI', 'Vandavasi', 0, 0),
(271, 'M Kandigai', 'Uthiramerur', 0, 0),
(272, 'M N Puthiya palayam', 'Polur', 0, 0),
(273, 'M C Raja Nagar', 'Chetpet', 0, 0),
(274, 'Maambattu VSI', 'Vandavasi', 0, 0),
(275, 'Madam VSI', 'Vandavasi', 0, 0),
(276, 'Madhura nemilipattu', 'Uthiramerur', 0, 0),
(277, 'Madhurai', 'Uthiramerur', 0, 0),
(278, 'Magaral', 'Uthiramerur', 0, 0),
(279, 'Mahadevimangalam', 'Chetpet', 0, 0),
(280, 'Malaivaiyavur', 'Vandavasi', 0, 0),
(281, 'Malaiyampuravadai', 'Chetpet', 0, 0),
(282, 'Malaiyangulam', 'Uthiramerur', 0, 0),
(283, 'Malaiyankuppam', 'Uthiramerur', 0, 0),
(284, 'Malaiyittan Kuppam UTR', 'Uthiramerur', 0, 0),
(285, 'Malikaipattu', 'Vandavasi', 0, 0),
(286, 'Malligapuram', 'Uthiramerur', 0, 0),
(287, 'Malliyangaranai', 'Uthiramerur', 0, 0),
(288, 'Mamandur', 'Vandavasi', 0, 0),
(289, 'Manalmedu', 'Uthiramerur', 0, 0),
(290, 'Manampathy', 'Uthiramerur', 0, 0),
(291, 'Mandakolathur', 'Polur', 0, 0),
(292, 'Mangala Mamandur', 'Vandavasi', 0, 0),
(293, 'Mangalam VSI', 'Vandavasi', 0, 0),
(294, 'Manganallur', 'Vandavasi', 0, 0),
(295, 'Manickavalli', 'Polur', 0, 0),
(296, 'Manimangalam', 'Vandavasi', 0, 0),
(297, 'Manipuram', 'Vandavasi', 0, 0),
(298, 'Manithottam', 'Uthiramerur', 0, 0),
(299, 'Maniyanthapathu', 'Chetpet', 0, 0),
(300, 'Manjapattu', 'Vandavasi', 0, 0),
(301, 'Mannan Kudesai', 'Vandavasi', 0, 0),
(302, 'Mansurabad', 'Chetpet', 0, 0),
(303, 'Maraikaniyanur', 'Chetpet', 0, 0),
(304, 'Marakkonam', 'Chetpet', 0, 0),
(305, 'Marudhadu', 'Vandavasi', 0, 0),
(306, 'Marudham', 'Uthiramerur', 0, 0),
(307, 'Maruthuvambadi UTR', 'Uthiramerur', 0, 0),
(308, 'Mathippankulam', 'Vandavasi', 0, 0),
(309, 'Mathuraperambattur', 'Chetpet', 0, 0),
(310, 'Mattaparaiyur', 'Polur', 0, 0),
(311, 'Mavalavadi', 'Vandavasi', 0, 0),
(312, 'Mazhaiyur', 'Vandavasi', 0, 0),
(313, 'Mazhuvangaranai', 'Vandavasi', 0, 0),
(314, 'Meesanallur', 'Vandavasi', 0, 0),
(315, 'Mel Kodungalur', 'Vandavasi', 0, 0),
(316, 'Mel Nandhiyambadi', 'Chetpet', 0, 0),
(317, 'Mel Nemili VSI', 'Vandavasi', 0, 0),
(318, 'Mel sevalambadi', 'Chetpet', 0, 0),
(319, 'Mel Villvarayanallur', 'Polur', 0, 0),
(320, 'Mel vilvarani', 'Polur', 0, 0),
(321, 'Melacheri', 'Chetpet', 0, 0),
(322, 'Melapoondi', 'Chetpet', 0, 0),
(323, 'Melathangal CPT', 'Chetpet', 0, 0),
(324, 'Melduli', 'Uthiramerur', 0, 0),
(325, 'Melkaranai', 'Vandavasi', 0, 0),
(326, 'Melma', 'Vandavasi', 0, 0),
(327, 'Melmalaiyanur', 'Chetpet', 0, 0),
(328, 'Melnarma', 'Vandavasi', 0, 0),
(329, 'Melnemili', 'Vandavasi', 0, 0),
(330, 'Melpadhiri', 'Vandavasi', 0, 0),
(331, 'Melpakkam', 'Uthiramerur', 0, 0),
(332, 'Melpathi', 'Vandavasi', 0, 0),
(333, 'Melsathamangalam', 'Chetpet', 0, 0),
(334, 'Melthandarai', 'Uthiramerur', 0, 0),
(335, 'Melvayalamur', 'Vandavasi', 0, 0),
(336, 'Melvillivalam', 'Chetpet', 0, 0),
(337, 'Menalur', 'Uthiramerur', 0, 0),
(338, 'Meppathurai', 'Polur', 0, 0),
(339, 'Mettuvayalamoor', 'Chetpet', 0, 0),
(340, 'Mettu Echur', 'Vandavasi', 0, 0),
(341, 'Mettupalayam PLR', 'Polur', 0, 0),
(342, 'Modaiyur', 'Chetpet', 0, 0),
(343, 'Modipattu', 'Chetpet', 0, 0),
(344, 'Molaipattu', 'Chetpet', 0, 0),
(345, 'Moodur', 'Vandavasi', 0, 0),
(346, 'Moongilthangal', 'Chetpet', 0, 0),
(347, 'Moraikaniyanur', 'Chetpet', 0, 0),
(348, 'Mosavadi', 'Chetpet', 0, 0),
(349, 'Mulapattu', 'Chetpet', 0, 0),
(350, 'Mummuni', 'Vandavasi', 0, 0),
(351, 'Murugamangalam', 'Chetpet', 0, 0),
(352, 'Muruganthangal', 'Chetpet', 0, 0),
(353, 'Murukkeri', 'Uthiramerur', 0, 0),
(354, 'N.S Puram', 'Vandavasi', 0, 0),
(355, 'Nachiyapuram', 'Chetpet', 0, 0),
(356, 'Nadukuppam VSI', 'Vandavasi', 0, 0),
(357, 'Nadupattu', 'Uthiramerur', 0, 0),
(358, 'Nagamedu', 'Uthiramerur', 0, 0),
(359, 'Nainavaram CPT', 'Chetpet', 0, 0),
(360, 'Nalladisenai', 'Chetpet', 0, 0),
(361, 'Nalleri', 'Vandavasi', 0, 0),
(362, 'Nallur VSI', 'Vandavasi', 0, 0),
(363, 'Namathodu', 'Chetpet', 0, 0),
(364, 'Nambedu VSI', 'Vandavasi', 0, 0),
(365, 'Nandhipuram', 'Chetpet', 0, 0),
(366, 'Nangaiyarkulam', 'Uthiramerur', 0, 0),
(367, 'Nanjipuram', 'Uthiramerur', 0, 0),
(368, 'Narasingapuram VSI', 'Vandavasi', 0, 0),
(369, 'Narasingarayan Pettai', 'Polur', 0, 0),
(370, 'Narayanamangalam CPT', 'Chetpet', 0, 0),
(371, 'Narmapallam', 'Vandavasi', 0, 0),
(372, 'Narthambadi', 'Vandavasi', 0, 0),
(373, 'Narthampoondi', 'Polur', 0, 0),
(374, 'Navalpakkam', 'Vandavasi', 0, 0),
(375, 'Nedungal', 'Vandavasi', 0, 0),
(376, 'Nedungunam', 'Chetpet', 0, 0),
(377, 'Neelam Poondi', 'Chetpet', 0, 0),
(378, 'Neeradi', 'Uthiramerur', 0, 0),
(379, 'Nelli', 'Uthiramerur', 0, 0),
(380, 'Nelliyangulam', 'Vandavasi', 0, 0),
(381, 'Nelveli', 'Uthiramerur', 0, 0),
(382, 'Nelvoy', 'Uthiramerur', 0, 0),
(383, 'Nemili', 'Uthiramerur', 0, 0),
(384, 'Nerkunam', 'Vandavasi', 0, 0),
(385, 'Nerkundram VSI', 'Vandavasi', 0, 0),
(386, 'New Vanakkambadi', 'Vandavasi', 0, 0),
(387, 'Noothambadi', 'Vandavasi', 0, 0),
(388, 'Odanagaram', 'Chetpet', 0, 0),
(389, 'Oddanthangal', 'Uthiramerur', 0, 0),
(390, 'Olagampattu', 'Chetpet', 0, 0),
(391, 'Old Gangaisodamani', 'Chetpet', 0, 0),
(392, 'Old Kaliyampoondi', 'Uthiramerur', 0, 0),
(393, 'Old Marakonanam', 'Chetpet', 0, 0),
(394, 'Old Vinnuvampattu', 'Polur', 0, 0),
(395, 'Ongoor', 'Uthiramerur', 0, 0),
(396, 'Orathi', 'Vandavasi', 0, 0),
(397, 'Osur', 'Vandavasi', 0, 0),
(398, 'Ottanthangal', 'Uthiramerur', 0, 0),
(399, 'Ozhaiyur', 'Uthiramerur', 0, 0),
(400, 'Ozhapakkam', 'Vandavasi', 0, 0),
(401, 'Ozhugarai', 'Uthiramerur', 0, 0),
(402, 'P.A.Pettai', 'Polur', 0, 0),
(403, 'Padhiri', 'Vandavasi', 0, 0),
(404, 'Padiyam Puthur', 'Polur', 0, 0),
(405, 'Padoor', 'Uthiramerur', 0, 0),
(406, 'Paiyur', 'Vandavasi', 0, 0),
(407, 'Palampoondi', 'Chetpet', 0, 0),
(408, 'Palankoil', 'Polur', 0, 0),
(409, 'Palayam', 'Chetpet', 0, 0),
(410, 'Palliyagaram', 'Uthiramerur', 0, 0),
(411, 'Pambaiyambattu', 'Uthiramerur', 0, 0),
(412, 'Pancharai', 'Vandavasi', 0, 0),
(413, 'Pandavakkam', 'Uthiramerur', 0, 0),
(414, 'Pandiyapuram', 'Chetpet', 0, 0),
(415, 'Papanthangal VSI', 'Vandavasi', 0, 0),
(416, 'Pappanallur VSI', 'Vandavasi', 0, 0),
(417, 'Parikalpattu', 'Chetpet', 0, 0),
(418, 'Paruthi Kollai', 'Uthiramerur', 0, 0),
(419, 'Paruthipuram', 'Chetpet', 0, 0),
(420, 'Paruvathampoondi', 'Vandavasi', 0, 0),
(421, 'Pasuvangaranai', 'Uthiramerur', 0, 0),
(422, 'Pathiyavaram', 'Chetpet', 0, 0),
(423, 'Pattancheri', 'Uthiramerur', 0, 0),
(424, 'Pattangulam', 'Uthiramerur', 0, 0),
(425, 'Pazhampet', 'Chetpet', 0, 0),
(426, 'Pazhanjur', 'Vandavasi', 0, 0),
(427, 'Pennalur', 'Uthiramerur', 0, 0),
(428, 'Pennatagaram', 'Vandavasi', 0, 0),
(429, 'Peranamallur', 'Chetpet', 0, 0),
(430, 'Peranambakkam UTR', 'Uthiramerur', 0, 0),
(431, 'Periya Andithangal', 'Uthiramerur', 0, 0),
(432, 'Periya Kozhapalur', 'Chetpet', 0, 0),
(433, 'Periya Kuppam', 'Vandavasi', 0, 0),
(434, 'Periya Nolambai', 'Chetpet', 0, 0),
(435, 'Pernambakkam', 'Uthiramerur', 0, 0),
(436, 'Perumbakkam VSI', 'Vandavasi', 0, 0),
(437, 'Perunagar', 'Uthiramerur', 0, 0),
(438, 'Perungozhi', 'Uthiramerur', 0, 0),
(439, 'Peruvalur', 'Chetpet', 0, 0),
(440, 'Pinnanur VSI', 'Vandavasi', 0, 0),
(441, 'Pinnathur', 'Vandavasi', 0, 0),
(442, 'Ponnur VSI', 'Vandavasi', 0, 0),
(443, 'Poondi VSI', 'Vandavasi', 0, 0),
(444, 'Poongunam', 'Chetpet', 0, 0),
(445, 'Poonthandalam', 'Uthiramerur', 0, 0),
(446, 'Poosari Colony', 'Vandavasi', 0, 0),
(447, 'Pudur VSI', 'Vandavasi', 0, 0),
(448, 'Pulivoy VSI', 'Vandavasi', 0, 0),
(449, 'Puliyur', 'Uthiramerur', 0, 0),
(450, 'Punnai', 'Vandavasi', 0, 0),
(451, 'Purisai', 'Vandavasi', 0, 0),
(452, 'Puthali', 'Uthiramerur', 0, 0),
(453, 'Puthupalayam', 'Polur', 0, 0),
(454, 'Puzhuthivakkam', 'Uthiramerur', 0, 0),
(455, 'R. N. Kandigai', 'Uthiramerur', 0, 0),
(456, 'Raganatha Samuthiram', 'Chetpet', 0, 0),
(457, 'Rajampuram', 'Chetpet', 0, 0),
(458, 'Ramalingapuram', 'Vandavasi', 0, 0),
(459, 'Ramapuram VSI', 'Vandavasi', 0, 0),
(460, 'Ramsamuthiram', 'Vandavasi', 0, 0),
(461, 'Randham CPT', 'Chetpet', 0, 0),
(462, 'Rayananthal', 'Vandavasi', 0, 0),
(463, 'Reddikollai', 'Uthiramerur', 0, 0),
(464, 'Reddykuppam', 'Chetpet', 0, 0),
(465, 'Retta Mangalam', 'Uthiramerur', 0, 0),
(466, 'S Mottur', 'Vandavasi', 0, 0),
(467, 'S Navalpakkam', 'Vandavasi', 0, 0),
(468, 'Sadachivakkam', 'Uthiramerur', 0, 0),
(469, 'Sagayapuram', 'Uthiramerur', 0, 0),
(470, 'Salavakkam', 'Uthiramerur', 0, 0),
(471, 'Salavedu', 'Vandavasi', 0, 0),
(472, 'Salukkai', 'Vandavasi', 0, 0),
(473, 'Samanthipuram', 'Chetpet', 0, 0),
(474, 'Samathuvapuram VSI', 'Vandavasi', 0, 0),
(475, 'Sanjivarayanpettai VSI', 'Vandavasi', 0, 0),
(476, 'Sathampadi', 'Chetpet', 0, 0),
(477, 'Sathampoondi', 'Vandavasi', 0, 0),
(478, 'Sathanoor', 'Vandavasi', 0, 0),
(479, 'Sathyavadi', 'Vandavasi', 0, 0),
(480, 'Seeyalam', 'Maduranthakam', 0, 0),
(481, 'Seeyamangalam', 'Vandavasi', 0, 0),
(482, 'Sekkadikuppam', 'Chetpet', 0, 0),
(483, 'Sembur', 'Vandavasi', 0, 0),
(484, 'Semmaiyamangalam', 'Polur', 0, 0),
(485, 'Semmambadi', 'Chetpet', 0, 0),
(486, 'Sempoondi', 'Uthiramerur', 0, 0),
(487, 'Senal', 'Vandavasi', 0, 0),
(488, 'Senanthal', 'Vandavasi', 0, 0),
(489, 'Sengenikuppam', 'Vandavasi', 0, 0),
(490, 'Septangulam', 'Chetpet', 0, 0),
(491, 'Serpakkam', 'Uthiramerur', 0, 0),
(492, 'Setharakuppam', 'Vandavasi', 0, 0),
(493, 'Sevarampoondi', 'Chetpet', 0, 0),
(494, 'Silambakkam', 'Uthiramerur', 0, 0),
(495, 'Singampoondi VSI', 'Vandavasi', 0, 0),
(496, 'Singapalli', 'Vandavasi', 0, 0),
(497, 'Sirunallur', 'Uthiramerur', 0, 0),
(498, 'Sirungozhi', 'Uthiramerur', 0, 0),
(499, 'Sithamalli', 'Uthiramerur', 0, 0),
(500, 'Sivanam', 'Vandavasi', 0, 0),
(501, 'Sogathur', 'Vandavasi', 0, 0),
(502, 'Solai Arugavur', 'Chetpet', 0, 0),
(503, 'Somanathapuram', 'Uthiramerur', 0, 0),
(504, 'Soraputhur', 'Vandavasi', 0, 0),
(505, 'Sothupakkam', 'Vandavasi', 0, 0),
(506, 'Soundariyapuram', 'Vandavasi', 0, 0),
(507, 'Sozhanur', 'Uthiramerur', 0, 0),
(508, 'Sri Rangarajapuram', 'Vandavasi', 0, 0),
(509, 'Sunnambumedu', 'Vandavasi', 0, 0),
(510, 'Suriyakuppam', 'Vandavasi', 0, 0),
(511, 'Suriyanthangal', 'Vandavasi', 0, 0),
(512, 'T Mambattu', 'Vandavasi', 0, 0),
(513, 'T Thangal', 'Vandavasi', 0, 0),
(514, 'Thachambadi', 'Chetpet', 0, 0),
(515, 'Thachampattu', 'Chetpet', 0, 0),
(516, 'Thadinolambai', 'Chetpet', 0, 0),
(517, 'Thakkandarayapuram', 'Vandavasi', 0, 0),
(518, 'Thandalam', 'Vandavasi', 0, 0),
(519, 'Thandarai', 'Uthiramerur', 0, 0),
(520, 'Thathanur', 'Chetpet', 0, 0),
(521, 'Thattampoondi', 'Uthiramerur', 0, 0),
(522, 'Thavani', 'Chetpet', 0, 0),
(523, 'Thazhampallam', 'Vandavasi', 0, 0),
(524, 'Thazhuthazhai', 'Vandavasi', 0, 0),
(525, 'Theetalam', 'Uthiramerur', 0, 0),
(526, 'Thellar', 'Vandavasi', 0, 0),
(527, 'Thellur VSI', 'Vandavasi', 0, 0),
(528, 'Thenalapakkam', 'Vandavasi', 0, 0),
(529, 'Theniluppai', 'Vandavasi', 0, 0),
(530, 'Thenkalpakkam', 'Vandavasi', 0, 0),
(531, 'Thenkarai', 'Vandavasi', 0, 0),
(532, 'Thennagur', 'Vandavasi', 0, 0),
(533, 'Thennathur VSI', 'Vandavasi', 0, 0),
(534, 'Thensenthamangalam', 'Vandavasi', 0, 0),
(535, 'Thenthinnalur', 'Vandavasi', 0, 0),
(536, 'Thenvanakkampadi', 'Vandavasi', 0, 0),
(537, 'Theppirampattu', 'Chetpet', 0, 0),
(538, 'Therasapuram', 'Vandavasi', 0, 0),
(539, 'Thethurai', 'Vandavasi', 0, 0),
(540, 'Theyyar', 'Vandavasi', 0, 0),
(541, 'Theyyar Madam', 'Vandavasi', 0, 0),
(542, 'Thinaiyampoondi', 'Uthiramerur', 0, 0),
(543, 'Thirakovil', 'Vandavasi', 0, 0),
(544, 'Thirumalai', 'Polur', 0, 0),
(545, 'Thirumalapadi', 'Vandavasi', 0, 0),
(546, 'Thirumpoondi', 'Vandavasi', 0, 0),
(547, 'Thirupulivanam', 'Uthiramerur', 0, 0),
(548, 'Thorappadi', 'Chetpet', 0, 0),
(549, 'Thotanaval', 'Uthiramerur', 0, 0),
(550, 'Thozhupedu', 'Chetpet', 0, 0),
(551, 'Thukkuvadi', 'Vandavasi', 0, 0),
(552, 'Thumbur', 'Chetpet', 0, 0),
(553, 'Thunaiyambattu', 'Vandavasi', 0, 0),
(554, 'Udaiyanthangal', 'Chetpet', 0, 0),
(555, 'Ulagampattu', 'Chetpet', 0, 0),
(556, 'Ulundhai', 'Vandavasi', 0, 0),
(557, 'Unnamanandal', 'Chetpet', 0, 0),
(558, 'Urgudi', 'Vandavasi', 0, 0),
(559, 'Uthiramerur', 'Uthiramerur', 0, 0),
(560, 'Uthukulam', 'Vandavasi', 0, 0),
(561, 'Uthur', 'Chetpet', 0, 0),
(562, 'V. Pudhur', 'Vandavasi', 0, 0),
(563, 'Vaadadhavoor', 'Uthiramerur', 0, 0),
(564, 'Vadakkapattu', 'Chetpet', 0, 0),
(565, 'Vadanallur', 'Uthiramerur', 0, 0),
(566, 'Vadanangur', 'Vandavasi', 0, 0),
(567, 'Vadasenthamangalam', 'Vandavasi', 0, 0),
(568, 'Vadavanakkampadi', 'Vandavasi', 0, 0),
(569, 'Vadavetti VSI', 'Vandavasi', 0, 0),
(570, 'Vadugamangalam', 'Vandavasi', 0, 0),
(571, 'Vaippanai', 'Uthiramerur', 0, 0),
(572, 'Valathi', 'Chetpet', 0, 0),
(573, 'Valathodu', 'Uthiramerur', 0, 0),
(574, 'Valayaputhur', 'Uthiramerur', 0, 0),
(575, 'Vallam', 'Vandavasi', 0, 0),
(576, 'Vambalur', 'Polur', 0, 0),
(577, 'Vandavasi', 'Vandavasi', 0, 0),
(578, 'Vangaram', 'Vandavasi', 0, 0),
(579, 'Vayalamoor', 'Vandavasi', 0, 0),
(580, 'Vayalur UTR', 'Uthiramerur', 0, 0),
(581, 'Vazhur', 'Vandavasi', 0, 0),
(582, 'Vedal', 'Vandavasi', 0, 0),
(583, 'Vedanthangal', 'Chetpet', 0, 0),
(584, 'Vedapalayam', 'Uthiramerur', 0, 0),
(585, 'Vedavakkam', 'Uthiramerur', 0, 0),
(586, 'Veerambakkam', 'Vandavasi', 0, 0),
(587, 'Veeranamoor', 'Vandavasi', 0, 0),
(588, 'Veerasambanur', 'Chetpet', 0, 0),
(589, 'Velacherry', 'Vandavasi', 0, 0),
(590, 'Velanthangal', 'Chetpet', 0, 0),
(591, 'Veliyambakkam', 'Vandavasi', 0, 0),
(592, 'Vellaputhur', 'Uthiramerur', 0, 0),
(593, 'Velukkampattu', 'Chetpet', 0, 0),
(594, 'Vendivakkam', 'Uthiramerur', 0, 0),
(595, 'Vengaram', 'Uthiramerur', 0, 0),
(596, 'Vengodu VSI', 'Vandavasi', 0, 0),
(597, 'Vengunam', 'Vandavasi', 0, 0),
(598, 'Venkacherry', 'Uthiramerur', 0, 0),
(599, 'Venkatapuram', 'Vandavasi', 0, 0),
(600, 'Venkundram', 'Vandavasi', 0, 0),
(601, 'Venmandhai', 'Vandavasi', 0, 0),
(602, 'Vepangaranai', 'Vandavasi', 0, 0),
(603, 'Veppampattu', 'Chetpet', 0, 0),
(604, 'Vilangadu', 'Vandavasi', 0, 0),
(605, 'VilVarayanallur CPT', 'Chetpet', 0, 0),
(606, 'Vinayaganallur', 'Uthiramerur', 0, 0),
(607, 'Vinayagapuram VSI', 'Vandavasi', 0, 0),
(608, 'Vinnamangalam VSI', 'Vandavasi', 0, 0),
(609, 'Visamangalam', 'Chetpet', 0, 0),
(610, 'Visur', 'Uthiramerur', 0, 0),
(611, 'Vizhudhupattu', 'Vandavasi', 0, 0),
(612, 'Aanaivadi', 'Polur', 0, 0),
(613, 'Abdullapuram', 'Vandavasi', 0, 0),
(614, 'Agaram Duli', 'Uthiramerur', 0, 0),
(615, 'Agaramsibbandi', 'Polur', 0, 0),
(616, 'Alampoondi', 'Polur', 0, 0),
(617, 'Alangaramangalam', 'Polur', 0, 0),
(618, 'Ammeri', 'Chetpet', 0, 0),
(619, 'Anaivadi', 'Polur', 0, 0),
(620, 'Andipuravadai', 'Chetpet', 0, 0),
(621, 'Arani palayam', 'Chetpet', 0, 0),
(622, 'Arunagirimangalam', 'Polur', 0, 0),
(623, 'Arundhadhiyar Palayam', 'Polur', 0, 0),
(624, 'Athamangalam Puthur', 'Polur', 0, 0),
(625, 'Ayapakkam', 'Vandavasi', 0, 0),
(626, 'Banampattu', 'Polur', 0, 0),
(627, 'Bangalamedu', 'Polur', 0, 0),
(628, 'Chennai', 'Vandavasi', 0, 0),
(629, 'Cheyyar', 'Vandavasi', 0, 0),
(630, 'Cheyyatraivendran', 'Vandavasi', 0, 0),
(631, 'Chinna Kozhipuliyur', 'Chetpet', 0, 0),
(632, 'Chinna Ozhugarai', 'Uthiramerur', 0, 0),
(633, 'Chinnavada villampakkam', 'Polur', 0, 0),
(634, 'Chithathur', 'Uthiramerur', 0, 0),
(635, 'Chitheri', 'Polur', 0, 0),
(636, 'Chithrakulam', 'Uthiramerur', 0, 0),
(637, 'Chittikulam', 'Vandavasi', 0, 0),
(638, 'Dhadapuram', 'Vandavasi', 0, 0),
(639, 'Dhanakottipuram', 'Polur', 0, 0),
(640, 'Dr Abdulkalampuram', 'Vandavasi', 0, 0),
(641, 'Eathuvambadi', 'Polur', 0, 0),
(642, 'Echampoondi', 'Polur', 0, 0),
(643, 'Elluparai VSI', 'Vandavasi', 0, 0),
(644, 'Emamangalam', 'Polur', 0, 0),
(645, 'Ernamangalam', 'Polur', 0, 0),
(646, 'Ethanemilli', 'Vandavasi', 0, 0),
(647, 'Ethuvaipettai', 'Chetpet', 0, 0),
(648, 'Ettivadi', 'Polur', 0, 0),
(649, 'Ezhadhapattu', 'Chetpet', 0, 0),
(650, 'Gangeyanoor', 'Polur', 0, 0),
(651, 'Gobalapuram', 'Chetpet', 0, 0),
(652, 'Gudappakkam', 'Uthiramerur', 0, 0),
(653, 'Kalur', 'Polur', 0, 0),
(654, 'Kilkolathur', 'Vandavasi', 0, 0),
(655, 'Kilpudhupakkam', 'Vandavasi', 0, 0),
(656, 'Kilsembedu', 'Vandavasi', 0, 0),
(657, 'Kizh Colony', 'Polur', 0, 0),
(658, 'Kizhneerkundram', 'Uthiramerur', 0, 0),
(659, 'Kizhpotharai', 'Polur', 0, 0),
(660, 'Kosapalayam', 'Vandavasi', 0, 0),
(661, 'Ladavaram', 'Polur', 0, 0),
(662, 'Malaiyambattu', 'Polur', 0, 0),
(663, 'Melpalayam', 'Chetpet', 0, 0),
(664, 'Melpazhankoil', 'Polur', 0, 0),
(665, 'Melpriyampattu', 'Polur', 0, 0),
(666, 'Melsevur', 'Chetpet', 0, 0),
(667, 'Melvanniyanur', 'Polur', 0, 0),
(668, 'Melvarayanallur', 'Polur', 0, 0),
(669, 'Mottur PLR', 'Polur', 0, 0),
(670, 'Mukkurumbai', 'Polur', 0, 0),
(671, 'Munivanthangal', 'Polur', 0, 0),
(672, 'Muniyappanthangal', 'Polur', 0, 0),
(673, 'Muruganthangasspatti', 'Chetpet', 0, 0),
(674, 'Murugapadi', 'Polur', 0, 0),
(675, 'Murungampakkam', 'Chetpet', 0, 0),
(676, 'Naidumangalam', 'Polur', 0, 0),
(677, 'Natchiyapuram', 'Chetpet', 0, 0),
(678, 'Nellimedu', 'Polur', 0, 0),
(679, 'Noonampoondi', 'Uthiramerur', 0, 0),
(680, 'Old Bandamangalam', 'Chetpet', 0, 0),
(681, 'Omudi', 'Polur', 0, 0),
(682, 'Othiyanthangal', 'Polur', 0, 0),
(683, 'Ottakovil', 'Vandavasi', 0, 0),
(684, 'Padagam', 'Polur', 0, 0),
(685, 'Paduvanchery', 'Vandavasi', 0, 0),
(686, 'Paleshwaram', 'Polur', 0, 0),
(687, 'Palvarthuvendram', 'Polur', 0, 0),
(688, 'Papampoondi', 'Polur', 0, 0),
(689, 'Pappampadi', 'Polur', 0, 0),
(690, 'Parayanthangal', 'Chetpet', 0, 0),
(691, 'Pathiyavadi', 'Polur', 0, 0),
(692, 'Pazhaiya Ettikuttai', 'Vandavasi', 0, 0),
(693, 'Pazhaiyur', 'Chetpet', 0, 0),
(694, 'Pazhampoondi', 'Chetpet', 0, 0),
(695, 'Pazhangamoor', 'Chetpet', 0, 0),
(696, 'Pazhathottam', 'Uthiramerur', 0, 0),
(697, 'Periya Kappalur', 'Polur', 0, 0),
(698, 'Periya korakottai', 'Vandavasi', 0, 0),
(699, 'Periya Sengadu', 'Vandavasi', 0, 0),
(700, 'Periyakaram', 'Polur', 0, 0),
(701, 'Periyakuppam', 'Vandavasi', 0, 0),
(702, 'Periyampattu Poondi', 'Polur', 0, 0),
(703, 'Perumalpalayam', 'Polur', 0, 0),
(704, 'Perunkadaputhur CPT', 'Chetpet', 0, 0),
(705, 'Pettai', 'Polur', 0, 0),
(706, 'Pillur', 'Polur', 0, 0),
(707, 'Pinna Poondi', 'Vandavasi', 0, 0),
(708, 'Polur', 'Polur', 0, 0),
(709, 'Pongambadi', 'Polur', 0, 0),
(710, 'Ponnagar', 'Polur', 0, 0),
(711, 'Ponnankulam', 'Uthiramerur', 0, 0),
(712, 'Poriyur', 'Vandavasi', 0, 0),
(713, 'Porkunam', 'Polur', 0, 0),
(714, 'Porpandhal', 'Uthiramerur', 0, 0),
(715, 'Potharai', 'Polur', 0, 0),
(716, 'Potheri', 'Polur', 0, 0),
(717, 'Kavanur Pudhuchery', 'Uthiramerur', 0, 0),
(718, 'Pulivananthal CPT', 'Chetpet', 0, 0),
(719, 'Puthirambattu', 'Polur', 0, 0),
(720, 'Puthiya Manjanoor', 'Chetpet', 0, 0),
(721, 'Puthu Jayamangalam', 'Chetpet', 0, 0),
(722, 'Puthu Marakkoanam', 'Chetpet', 0, 0),
(723, 'Puthupettai', 'Polur', 0, 0),
(724, 'Ravathanallur', 'Uthiramerur', 0, 0),
(725, 'Renderipattu', 'Polur', 0, 0),
(726, 'Salaiyanoor', 'Polur', 0, 0),
(727, 'Sanikkavadi', 'Polur', 0, 0),
(728, 'Sankarapalayam', 'Polur', 0, 0),
(729, 'Santhanandal', 'Chetpet', 0, 0),
(730, 'Sathampattu', 'Polur', 0, 0),
(731, 'Sathiyapuram', 'Polur', 0, 0),
(732, 'Sathuperipalayam', 'Polur', 0, 0),
(733, 'Savarapoondi', 'Chetpet', 0, 0),
(734, 'Sedharakuppam', 'Vandavasi', 0, 0),
(735, 'Seetampattu', 'Polur', 0, 0),
(736, 'Sengunam', 'Polur', 0, 0),
(737, 'Sevalambadi', 'Chetpet', 0, 0),
(738, 'Seviyarpalayam', 'Vandavasi', 0, 0),
(739, 'Sirukaveripakkam', 'Uthiramerur', 0, 0),
(740, 'Siruthamur', 'Uthiramerur', 0, 0),
(741, 'Somanatha puthur', 'Polur', 0, 0),
(742, 'Sorakolathur', 'Polur', 0, 0),
(743, 'Sorapakkam', 'Uthiramerur', 0, 0),
(744, 'Sothukanni', 'Polur', 0, 0),
(745, 'South Vangaram', 'Vandavasi', 0, 0),
(746, 'Su Kateri', 'Vandavasi', 0, 0),
(747, 'Sunaiyambarai', 'Uthiramerur', 0, 0),
(748, 'T Vanakkambadi', 'Vandavasi', 0, 0),
(749, 'Tambaram', 'Vandavasi', 0, 0),
(750, 'Thalampoondi', 'Vandavasi', 0, 0),
(751, 'Thanakottipuram', 'Polur', 0, 0),
(752, 'Thasarampattu', 'Polur', 0, 0),
(753, 'Thatchur', 'Chetpet', 0, 0),
(754, 'Thayanur', 'Chetpet', 0, 0),
(755, 'Theepanandhal', 'Polur', 0, 0),
(756, 'Thenkadapanthangal', 'Chetpet', 0, 0),
(757, 'Thenmathimangalam', 'Polur', 0, 0),
(758, 'Thenpallipattu', 'Polur', 0, 0),
(759, 'Thevandhavadi', 'Chetpet', 0, 0),
(760, 'Thirisoor', 'Polur', 0, 0),
(761, 'Thirumani', 'Chetpet', 0, 0),
(762, 'Thirumanithangal', 'Vandavasi', 0, 0),
(763, 'Thittakudi', 'Vandavasi', 0, 0),
(764, 'Thurinjikuppam', 'Polur', 0, 0),
(765, 'Uchikollaimedu', 'Uthiramerur', 0, 0),
(766, 'Ukkamperumbakkam', 'Vandavasi', 0, 0),
(767, 'Urapakkam', 'Vandavasi', 0, 0),
(768, 'Vachanoor', 'Vandavasi', 0, 0),
(769, 'Vadamadhimangalam', 'Polur', 0, 0),
(770, 'Vadapulithiur', 'Polur', 0, 0),
(771, 'Vadavilapakkam', 'Polur', 0, 0),
(772, 'Valakuravanpatti', 'Chetpet', 0, 0),
(773, 'Vappanai', 'Uthiramerur', 0, 0),
(774, 'Vasanthapuram', 'Polur', 0, 0),
(775, 'Vasur', 'Polur', 0, 0),
(776, 'Vellankuppam', 'Polur', 0, 0),
(777, 'Velliyur', 'Vandavasi', 0, 0),
(778, 'Vellore', 'Polur', 0, 0),
(779, 'Venmani', 'Polur', 0, 0),
(780, 'Vettavalam', 'Chetpet', 0, 0),
(781, 'Vikramanallur', 'Uthiramerur', 0, 0),
(782, 'Vilankuppam', 'Polur', 0, 0),
(783, 'Villivalam', 'Chetpet', 0, 0),
(784, 'Villvarani', 'Polur', 0, 0),
(785, 'Vinnuvampattu', 'Polur', 0, 0),
(786, 'Yenthuvambadi', 'Polur', 0, 0),
(787, 'Dharmapuri', 'Vandavasi', 0, 0),
(788, 'Kadambar Koil', 'Uthiramerur', 0, 0),
(789, 'Kanagambattu', 'Chetpet', 0, 0),
(790, 'Kandigai', 'Vandavasi', 0, 0),
(791, 'Kanniyapuram', 'Polur', 0, 0),
(792, 'Kariamangalam', 'Vandavasi', 0, 0),
(793, 'Karivelpattu', 'Vandavasi', 0, 0),
(794, 'Karkonam', 'Polur', 0, 0),
(795, 'Karnambadi', 'Chetpet', 0, 0),
(796, 'Kasaipoondi', 'Polur', 0, 0),
(797, 'Kathamangalam', 'Chetpet', 0, 0),
(798, 'Katrampatti', 'Chetpet', 0, 0),
(799, 'Kattipoondi', 'Polur', 0, 0),
(800, 'Keerthampattu', 'Chetpet', 0, 0),
(801, 'Kiluvanatham', 'Chetpet', 0, 0),
(802, 'Kodandapuram', 'Vandavasi', 0, 0),
(803, 'Kommananthal CPT', 'Chetpet', 0, 0),
(804, 'Kondam', 'Polur', 0, 0),
(805, 'Kovilkuppam', 'Vandavasi', 0, 0),
(806, 'Kozhipuliyur', 'Chetpet', 0, 0),
(807, 'Kudappakkam', 'Uthiramerur', 0, 0),
(808, 'Kudimithangal', 'Chetpet', 0, 0),
(809, 'Kuluvantham', 'Chetpet', 0, 0),
(810, 'Kusalpettai', 'Polur', 0, 0),
(811, 'Pelasur', 'Polur', 0, 0),
(812, 'Samathuvapuram CPT', 'Chetpet', 0, 0),
(813, 'Adambakkam', 'Vandavasi', 0, 0),
(814, 'Badimpetti', 'Chetpet', 0, 0),
(815, 'Arisanapoondi', 'Vandavasi', 0, 0),
(816, 'Illupagunam', 'Polur', 0, 0),
(817, 'Jaganathapuram', 'Chetpet', 0, 0),
(818, 'Kalasapakkam', 'Polur', 0, 0),
(819, 'Guruvaadi', 'Uthiramerur', 0, 0),
(820, 'Kalvasal', 'Polur', 0, 0),
(821, 'Magajanapakkam', 'Uthiramerur', 0, 0),
(822, 'Mallapanayakkam palayam', 'Polur', 0, 0),
(823, 'Manapakkam', 'Chetpet', 0, 0),
(824, 'Mangaranai', 'Vandavasi', 0, 0),
(825, 'Manickkamangalam', 'Chetpet', 0, 0),
(826, 'Mattaparai', 'Polur', 0, 0),
(827, 'Melarani', 'Polur', 0, 0),
(828, 'Pulimanthangal', 'Chetpet', 0, 0),
(829, 'Sthamalli', 'Uthiramerur', 0, 0),
(830, 'Thideer Kuppam', 'Vandavasi', 0, 0),
(831, 'Jadathariyakuppam', 'Polur', 0, 0),
(832, 'KallanKollai', 'Uthiramerur', 0, 0),
(833, 'Kalpakkam', 'Vandavasi', 0, 0),
(834, 'Kamakurpalayam', 'Polur', 0, 0),
(835, 'Karunkuzhi', 'Vandavasi', 0, 0),
(836, 'Kil Karikkathur', 'Polur', 0, 0),
(837, 'Kilkarikathur', 'Polur', 0, 0),
(838, 'Kizhkarikathur', 'Polur', 0, 0),
(839, 'Konaiyur', 'Chetpet', 0, 0),
(840, 'Mambattu VSI', 'Vandavasi', 0, 0),
(841, 'Mannan Kudisai', 'Uthiramerur', 0, 0),
(842, 'Mashar', 'Polur', 0, 0),
(843, 'Mocherry', 'Vandavasi', 0, 0),
(844, 'Moranam', 'Vandavasi', 0, 0),
(845, 'Othalavadi', 'Chetpet', 0, 0),
(846, 'Padiyampattu', 'Polur', 0, 0),
(847, 'Palla Echur', 'Vandavasi', 0, 0),
(848, 'Pryampattu', 'Polur', 0, 0),
(849, 'Pulavampadi', 'Chetpet', 0, 0),
(850, 'Renugapuram', 'Chetpet', 0, 0),
(851, 'Samanthakuppam', 'Chetpet', 0, 0),
(852, 'Sanjeeverayapuram', 'Chetpet', 0, 0),
(853, 'Satathangal', 'Chetpet', 0, 0),
(854, 'Sengaputheri', 'Polur', 0, 0),
(855, 'Tavanathanallur', 'Vandavasi', 0, 0),
(856, 'Thalarapadi', 'Vandavasi', 0, 0),
(857, 'Thunaiyan Kuppam', 'Vandavasi', 0, 0),
(858, 'Ukkal', 'Uthiramerur', 0, 0),
(859, 'Vellur', 'Polur', 0, 0),
(860, 'A T Palayam', 'Uthiramerur', 0, 0),
(861, 'Aalapiranthan', 'Vandavasi', 0, 0),
(862, 'Aanaikunnam', 'Vandavasi', 0, 0),
(863, 'Aandalpatti', 'Chetpet', 0, 0),
(864, 'Aandiyappanur', 'Vandavasi', 0, 0),
(865, 'Aaryapakkam', 'Cheyyur', 0, 0),
(866, 'Agaram VSI', 'Vandavasi', 0, 0),
(867, 'Agatheripattu', 'Vandavasi', 0, 0),
(868, 'Agathipattu', 'Maduranthakam', 0, 0),
(869, 'Akili', 'Cheyyur', 0, 0),
(870, 'Alapattaraithangal', 'Chetpet', 0, 0),
(871, 'Ammanur', 'Maduranthakam', 0, 0),
(872, 'Andhra', 'Uthiramerur', 0, 0),
(873, 'Andipatti', 'Chetpet', 0, 0),
(874, 'Anthoniyar Puram', 'Uthiramerur', 0, 0),
(875, 'Anumandhakuppam', 'Maduranthakam', 0, 0),
(876, 'Aravadai Thangal', 'Vandavasi', 0, 0),
(877, 'Arayapakkam', 'Maduranthakam', 0, 0),
(878, 'Ariyampoondi', 'Vandavasi', 0, 0),
(879, 'Arukavur', 'Vandavasi', 0, 0),
(880, 'Arumpakkam', 'Uthiramerur', 0, 0),
(881, 'Arunthodu', 'Chetpet', 0, 0),
(882, 'Athimanam', 'Cheyyur', 0, 0),
(883, 'Avalurpet', 'Chetpet', 0, 0),
(884, 'Ayakunnam', 'Maduranthakam', 0, 0),
(885, 'Azhividaithangi', 'Vandavasi', 0, 0),
(886, 'Bang Kolathoor', 'Cheyyur', 0, 0),
(887, 'Belagampoondi', 'Vandavasi', 0, 0),
(888, 'Bhagavathapuram', 'Chetpet', 0, 0),
(889, 'Boothur', 'Maduranthakam', 0, 0),
(890, 'Chekkadikuppam', 'Vandavasi', 0, 0),
(891, 'Chengalpattu', 'Maduranthakam', 0, 0),
(892, 'cheyyur', 'Maduranthakam', 0, 0),
(893, 'Chinna Athiyur', 'Uthiramerur', 0, 0),
(894, 'Chinna Elachery', 'Uthiramerur', 0, 0),
(895, 'Chinna Kalakadi', 'Maduranthakam', 0, 0),
(896, 'Chinna Kayapakam', 'Cheyyur', 0, 0),
(897, 'Chinna Narasampettai', 'Uthiramerur', 0, 0),
(898, 'Chinna Sathambadi', 'Chetpet', 0, 0),
(899, 'Chinnagaram', 'Vandavasi', 0, 0),
(900, 'Chinnalambadi', 'Uthiramerur', 0, 0),
(901, 'Chinnalkalani', 'Maduranthakam', 0, 0),
(902, 'Chinnapuram', 'Uthiramerur', 0, 0),
(903, 'Chithi vinayagapuram', 'Vandavasi', 0, 0),
(904, 'Chithiraikoodam', 'Uthiramerur', 0, 0),
(905, 'Chithirakudam', 'Uthiramerur', 0, 0),
(906, 'Chithiravadi', 'Maduranthakam', 0, 0),
(907, 'Chithlamangalam', 'Uthiramerur', 0, 0),
(908, 'Chrompet', 'Maduranthakam', 0, 0),
(909, 'Citalapakkam', 'Cheyyur', 0, 0),
(910, 'Devadur', 'Maduranthakam', 0, 0),
(911, 'Dhimmapuram', 'Cheyyur', 0, 0),
(912, 'Edaiyanthangal', 'Chetpet', 0, 0),
(913, 'Edayalam', 'Cheyyur', 0, 0),
(914, 'Edayankulam', 'Uthiramerur', 0, 0),
(915, 'Enathavadi', 'Chetpet', 0, 0),
(916, 'Eraiyur', 'Vandavasi', 0, 0),
(917, 'Eripattu', 'Vandavasi', 0, 0),
(918, 'Erumboondi', 'Chetpet', 0, 0),
(919, 'Erusamanallur', 'Maduranthakam', 0, 0),
(920, 'Esur', 'Cheyyur', 0, 0),
(921, 'Ganesapuram', 'Vandavasi', 0, 0),
(922, 'Gangampoondi', 'Vandavasi', 0, 0),
(923, 'Gayanallur', 'Vandavasi', 0, 0),
(924, 'Gidangal', 'Vandavasi', 0, 0),
(925, 'Gidangarai', 'Uthiramerur', 0, 0),
(926, 'Gothandavai', 'Chetpet', 0, 0),
(927, 'Guduvancherry', 'Chetpet', 0, 0),
(928, 'Gunankaranai G', 'Cheyyur', 0, 0),
(929, 'Gurukulam', 'Maduranthakam', 0, 0),
(930, 'Ilathur', 'Maduranthakam', 0, 0),
(931, 'Illedu', 'Maduranthakam', 0, 0),
(932, 'Indhaloor', 'Cheyyur', 0, 0),
(933, 'Indhirapuram', 'Uthiramerur', 0, 0),
(934, 'Indhiresan Kuppam', 'Chetpet', 0, 0),
(935, 'Irusamanallur', 'Maduranthakam', 0, 0),
(936, 'Jadatharikuppam', 'Polur', 0, 0),
(937, 'Jamin Endathur', 'Maduranthakam', 0, 0),
(938, 'Janakipuram', 'Cheyyur', 0, 0),
(939, 'Jothimanagar', 'Chetpet', 0, 0),
(940, 'Kadali', 'Chetpet', 0, 0),
(941, 'Kadamalaiputhur', 'Maduranthakam', 0, 0),
(942, 'Kadaperi', 'Maduranthakam', 0, 0),
(943, 'Kadappanthangal', 'Chetpet', 0, 0),
(944, 'Kaduganur', 'Vandavasi', 0, 0),
(945, 'Kadugupattu', 'Maduranthakam', 0, 0),
(946, 'Kadukalur', 'Maduranthakam', 0, 0),
(947, 'Kalarpalayam', 'Chetpet', 0, 0),
(948, 'Kallakollai', 'Uthiramerur', 0, 0),
(949, 'Kallakurichi', 'Chetpet', 0, 0),
(950, 'Kallanbiranpuram', 'Maduranthakam', 0, 0),
(951, 'Kalleri', 'Chetpet', 0, 0),
(952, 'Kalyanpuram VSI', 'Vandavasi', 0, 0),
(953, 'Kammanthangal', 'Chetpet', 0, 0),
(954, 'Kamsalapuram', 'Maduranthakam', 0, 0),
(955, 'Kanagampattu', 'Chetpet', 0, 0),
(956, 'KandiayanKuppam', 'Vandavasi', 0, 0),
(957, 'Kannimangalam', 'Cheyyur', 0, 0),
(958, 'Kanniyamangalam', 'Uthiramerur', 0, 0),
(959, 'Karanagarachery', 'Uthiramerur', 0, 0),
(960, 'KaranthaPattu', 'Chetpet', 0, 0),
(961, 'Karapoondi CPT', 'Chetpet', 0, 0),
(962, 'Karikanthangal', 'Cheyyur', 0, 0),
(963, 'Kariyathur', 'Vandavasi', 0, 0),
(964, 'Karukanthangal', 'Uthiramerur', 0, 0),
(965, 'Karunagarcherry', 'Uthiramerur', 0, 0),
(966, 'Karungaraivilagam', 'Cheyyur', 0, 0),
(967, 'Karunguzhi', 'Maduranthakam', 0, 0),
(968, 'Karuvadhamedu', 'Cheyyur', 0, 0),
(969, 'Kattamangalam', 'Chetpet', 0, 0),
(970, 'Kattapakam', 'Uthiramerur', 0, 0),
(971, 'Katteri', 'Vandavasi', 0, 0),
(972, 'Kattupuravadai', 'Chetpet', 0, 0),
(973, 'Kavanur', 'Cheyyur', 0, 0),
(974, 'Kavathur', 'Maduranthakam', 0, 0),
(975, 'Kayapakkam', 'Cheyyur', 0, 0),
(976, 'Kazhanipakkam', 'Uthiramerur', 0, 0),
(977, 'Keelakandai', 'Maduranthakam', 0, 0),
(978, 'Keelavalam', 'Maduranthakam', 0, 0),
(979, 'Keeralvadi', 'Maduranthakam', 0, 0),
(980, 'Kilathur', 'Chetpet', 0, 0),
(981, 'Kiliyanur', 'Vandavasi', 0, 0),
(982, 'Kilnelli', 'Vandavasi', 0, 0),
(983, 'Kilnemili', 'Vandavasi', 0, 0),
(984, 'Kilsiviri', 'Maduranthakam', 0, 0),
(985, 'Kiranthupattu', 'Chetpet', 0, 0),
(986, 'Kizh andai', 'Maduranthakam', 0, 0),
(987, 'Kizh athivakkam', 'Cheyyur', 0, 0),
(988, 'Kizh Malayanur', 'Vandavasi', 0, 0),
(989, 'Kizh Puthupakam', 'Vandavasi', 0, 0),
(990, 'Kizhathipakam', 'Cheyyur', 0, 0),
(991, 'Kizhkandai', 'Maduranthakam', 0, 0),
(992, 'KK Pudhur', 'Cheyyur', 0, 0),
(993, 'Kodaiyur', 'Chetpet', 0, 0),
(994, 'Koilkuppam', 'Vandavasi', 0, 0),
(995, 'Kolavedu', 'Vandavasi', 0, 0),
(996, 'Kolipuliyur', 'Chetpet', 0, 0),
(997, 'Kollakottai', 'Chetpet', 0, 0),
(998, 'Komalapettai', 'Chetpet', 0, 0),
(999, 'Kondaiya nallur', 'Vandavasi', 0, 0),
(1000, 'Koodaloor', 'Cheyyur', 0, 0),
(1001, 'Koozhamandal', 'Vandavasi', 0, 0),
(1002, 'Kottaimangalam', 'Vandavasi', 0, 0),
(1003, 'Kovilacherry', 'Vandavasi', 0, 0),
(1004, 'Kulamandhai', 'Vandavasi', 0, 0),
(1005, 'Kundalampattu', 'Chetpet', 0, 0),
(1006, 'Kundiaynthandalam', 'Uthiramerur', 0, 0),
(1007, 'Kuripedu', 'Vandavasi', 0, 0),
(1008, 'kurunagar', 'Maduranthakam', 0, 0),
(1009, 'Kuthampattu', 'Vandavasi', 0, 0),
(1010, 'Kuzhamanthal', 'Vandavasi', 0, 0),
(1011, 'Maambakam', 'Vandavasi', 0, 0),
(1012, 'Madavilagam UTR', 'Maduranthakam', 0, 0),
(1013, 'Madayambakkam', 'Maduranthakam', 0, 0),
(1014, 'madhukarai', 'Maduranthakam', 0, 0),
(1015, 'Madhuraputhur', 'Cheyyur', 0, 0),
(1016, 'Madurantakam', 'Maduranthakam', 0, 0),
(1017, 'Maikal Puram', 'Vandavasi', 0, 0),
(1018, 'Malai Chathiram', 'Vandavasi', 0, 0),
(1019, 'Malai Palayam', 'Maduranthakam', 0, 0),
(1020, 'MalaiKoil', 'Chetpet', 0, 0),
(1021, 'Malaipalayam', 'Maduranthakam', 0, 0),
(1022, 'Malaiyangaranai', 'Uthiramerur', 0, 0),
(1023, 'Mallicherry', 'Uthiramerur', 0, 0),
(1024, 'Manamankollai', 'Uthiramerur', 0, 0),
(1025, 'Mandagapallam', 'Chetpet', 0, 0),
(1026, 'Mandavelli', 'Chetpet', 0, 0),
(1027, 'Manjanur', 'Chetpet', 0, 0),
(1028, 'Mannur', 'Vandavasi', 0, 0),
(1029, 'Manthopu', 'Chetpet', 0, 0),
(1030, 'Marangal', 'Uthiramerur', 0, 0),
(1031, 'Marapakkam', 'Maduranthakam', 0, 0),
(1032, 'Mariputhur', 'Maduranthakam', 0, 0),
(1033, 'Mathangal', 'Vandavasi', 0, 0),
(1034, 'Mathur UTR', 'Cheyyur', 0, 0),
(1035, 'Mayanur', 'Chetpet', 0, 0),
(1036, 'Meiyur', 'Chetpet', 0, 0),
(1037, 'Meiyurodai', 'Uthiramerur', 0, 0),
(1038, 'Mel Athipakam', 'Vandavasi', 0, 0),
(1039, 'Mel Karanai', 'Chetpet', 0, 0),
(1040, 'Mel Puthupakkam', 'Vandavasi', 0, 0),
(1041, 'Mel Vasalai', 'Cheyyur', 0, 0),
(1042, 'Melakandai', 'Maduranthakam', 0, 0),
(1043, 'Melathoppu', 'Chetpet', 0, 0),
(1044, 'Melavalam', 'Maduranthakam', 0, 0),
(1045, 'Melavalampettai', 'Cheyyur', 0, 0),
(1046, 'Melmaruvathur', 'Cheyyur', 0, 0),
(1047, 'Melpattu', 'Maduranthakam', 0, 0),
(1048, 'Melperadikuppam', 'Vandavasi', 0, 0),
(1049, 'Melpernamalur', 'Uthiramerur', 0, 0),
(1050, 'Melsembedu', 'Vandavasi', 0, 0),
(1051, 'Melvalavampettai', 'Maduranthakam', 0, 0),
(1052, 'Melvasalai', 'Cheyyur', 0, 0),
(1053, 'Mettu Kollai', 'Uthiramerur', 0, 0),
(1054, 'Mettukudisai', 'Chetpet', 0, 0),
(1055, 'Mettukuppam', 'Vandavasi', 0, 0),
(1056, 'Minnal sithamur', 'Cheyyur', 0, 0),
(1057, 'Mogalvadi', 'Vandavasi', 0, 0),
(1058, 'Mohanapalayam', 'Chetpet', 0, 0),
(1059, 'Moosivakkam', 'Maduranthakam', 0, 0),
(1060, 'Morapakkam', 'Maduranthakam', 0, 0),
(1061, 'Mudur', 'Vandavasi', 0, 0),
(1062, 'Mukkur', 'Vandavasi', 0, 0),
(1063, 'Mungil Mandabam', 'Uthiramerur', 0, 0),
(1064, 'Munuthikuppam', 'Maduranthakam', 0, 0),
(1065, 'Nachupettai', 'Uthiramerur', 0, 0),
(1066, 'Nadayampakkam', 'Cheyyur', 0, 0),
(1067, 'Nagaram', 'Chetpet', 0, 0),
(1068, 'Nainarkuppam', 'Maduranthakam', 0, 0),
(1069, 'Nandhamangalam', 'Vandavasi', 0, 0),
(1070, 'Nandhiyambadi', 'Chetpet', 0, 0),
(1071, 'Nangalathur', 'Maduranthakam', 0, 0),
(1072, 'Narasapakkam', 'Maduranthakam', 0, 0),
(1073, 'Nathapettai', 'Uthiramerur', 0, 0),
(1074, 'Nayakampuravadai', 'Chetpet', 0, 0),
(1075, 'Nedumpirai', 'Cheyyur', 0, 0),
(1076, 'Neer Payir', 'Maduranthakam', 0, 0),
(1077, 'Nesal', 'Chetpet', 0, 0),
(1078, 'Nettupalayam', 'Maduranthakam', 0, 0),
(1079, 'New Mambakam', 'Maduranthakam', 0, 0),
(1080, 'New Mangalam', 'Chetpet', 0, 0),
(1081, 'Neyyadupakkam', 'Uthiramerur', 0, 0),
(1082, 'Nochalur', 'Chetpet', 0, 0),
(1083, 'Old Edayalam', 'Cheyyur', 0, 0),
(1084, 'Old Maambakkam', 'Maduranthakam', 0, 0),
(1085, 'Old seevaram', 'Uthiramerur', 0, 0),
(1086, 'onambakkam', 'Cheyyur', 0, 0),
(1087, 'Oothur', 'Chetpet', 0, 0),
(1088, 'Orathur', 'Uthiramerur', 0, 0),
(1089, 'Ottankudisai', 'Chetpet', 0, 0),
(1090, 'Paakam', 'Cheyyur', 0, 0),
(1091, 'Paalayam', 'Chetpet', 0, 0),
(1092, 'Padithangal', 'Vandavasi', 0, 0),
(1093, 'Pagavadhapuram', 'Chetpet', 0, 0),
(1094, 'Paiyampadi', 'Maduranthakam', 0, 0),
(1095, 'Paiyampettai', 'Maduranthakam', 0, 0),
(1096, 'Palaiyur', 'Maduranthakam', 0, 0),
(1097, 'palayar Madam', 'Maduranthakam', 0, 0),
(1098, 'Pallikaranai', 'Uthiramerur', 0, 0),
(1099, 'Pallipettai', 'Chetpet', 0, 0),
(1100, 'Pandhamangalam', 'Chetpet', 0, 0),
(1101, 'Panjalapuram', 'Vandavasi', 0, 0),
(1102, 'Parasanallur', 'Maduranthakam', 0, 0),
(1103, 'Parasur', 'Vandavasi', 0, 0),
(1104, 'Parukkal', 'Cheyyur', 0, 0),
(1105, 'Pasathan', 'Vandavasi', 0, 0),
(1106, 'Pasumbur', 'Cheyyur', 0, 0),
(1107, 'Pavunchoor', 'Maduranthakam', 0, 0),
(1108, 'Pazhavanthangal', 'Vandavasi', 0, 0),
(1109, 'Pekkarani', 'Maduranthakam', 0, 0),
(1110, 'Perampakam', 'Cheyyur', 0, 0),
(1111, 'Perani', 'Vandavasi', 0, 0),
(1112, 'Peravalur', 'Vandavasi', 0, 0),
(1113, 'Periya Elavanthangal', 'Vandavasi', 0, 0),
(1114, 'Periya Kalathumedu', 'Uthiramerur', 0, 0),
(1115, 'Periya Kayapakkam', 'Cheyyur', 0, 0),
(1116, 'Periya Narasampettai', 'Uthiramerur', 0, 0),
(1117, 'Periya Vaiyavur', 'Uthiramerur', 0, 0),
(1118, 'Perpanangadu', 'Vandavasi', 0, 0),
(1119, 'Perukaranai', 'Maduranthakam', 0, 0),
(1120, 'Perumanthangal', 'Vandavasi', 0, 0),
(1121, 'Perumpalai', 'Vandavasi', 0, 0),
(1122, 'Perumper Kandigai', 'Maduranthakam', 0, 0),
(1123, 'Perungalathur', 'Chetpet', 0, 0),
(1124, 'Perunkaranai', 'Cheyyur', 0, 0),
(1125, 'Pilanjimedu', 'Uthiramerur', 0, 0),
(1126, 'Pillaiyar Palayam', 'Uthiramerur', 0, 0),
(1127, 'Pillayar Odai', 'Uthiramerur', 0, 0),
(1128, 'Polambakkam', 'Cheyyur', 0, 0),
(1129, 'Poragal', 'Cheyyur', 0, 0),
(1130, 'Poraiyur', 'Cheyyur', 0, 0),
(1131, 'Porur', 'Cheyyur', 0, 0),
(1132, 'Pothamporadai', 'Uthiramerur', 0, 0),
(1133, 'Pozhachalur', 'Cheyyur', 0, 0),
(1134, 'Pukkathurai', 'Maduranthakam', 0, 0),
(1135, 'Pulavanpadi', 'Vandavasi', 0, 0),
(1136, 'Pulikoradu', 'Cheyyur', 0, 0),
(1137, 'Pulipakkam', 'Uthiramerur', 0, 0),
(1138, 'Pulivalam', 'Vandavasi', 0, 0),
(1139, 'Puliyani', 'Cheyyur', 0, 0),
(1140, 'Puliyarambakkam', 'Vandavasi', 0, 0),
(1141, 'Puliyarankottai', 'Maduranthakam', 0, 0),
(1142, 'Punagambadi', 'Chetpet', 0, 0),
(1143, 'Purushothapoondi', 'Uthiramerur', 0, 0),
(1144, 'Puthamangalam', 'Cheyyur', 0, 0),
(1145, 'Puthiran Pettai', 'Polur', 0, 0),
(1146, 'Puthu Manapakkam', 'Maduranthakam', 0, 0),
(1147, 'Puthu Pandamangalam', 'Vandavasi', 0, 0),
(1148, 'Ragunadha Samuthiram', 'Chetpet', 0, 0),
(1149, 'Ragunadhapuram', 'Chetpet', 0, 0),
(1150, 'Rajapalayam', 'Chetpet', 0, 0),
(1151, 'Ramanathapuram', 'Vandavasi', 0, 0),
(1152, 'Rangarajapuram', 'Vandavasi', 0, 0),
(1153, 'Ranipet', 'Vandavasi', 0, 0),
(1154, 'Rayapuram', 'Vandavasi', 0, 0),
(1155, 'Reddy Palayam', 'Cheyyur', 0, 0),
(1156, 'S Kalpattu', 'Vandavasi', 0, 0),
(1157, 'S R Pettai', 'Chetpet', 0, 0),
(1158, 'Sadagam', 'Uthiramerur', 0, 0),
(1159, 'Saidapet', 'Chetpet', 0, 0),
(1160, 'Sakaranthi', 'Vandavasi', 0, 0),
(1161, 'Salathangal', 'Maduranthakam', 0, 0),
(1162, 'Saliyur', 'Cheyyur', 0, 0),
(1163, 'Samuthiram', 'Vandavasi', 0, 0),
(1164, 'Sandror Thoppu', 'Chetpet', 0, 0),
(1165, 'Sangilikuppam', 'Chetpet', 0, 0),
(1166, 'Santhaimedu', 'Chetpet', 0, 0),
(1167, 'Saram', 'Vandavasi', 0, 0),
(1168, 'Saravampakam', 'Maduranthakam', 0, 0),
(1169, 'Sayaburam', 'Uthiramerur', 0, 0),
(1170, 'Seethapuram', 'Cheyyur', 0, 0),
(1171, 'Sembedu', 'Vandavasi', 0, 0),
(1172, 'Sendivakam', 'Cheyyur', 0, 0),
(1173, 'Sengadu', 'Vandavasi', 0, 0),
(1174, 'Sengundharpettai', 'Maduranthakam', 0, 0),
(1175, 'Seniyanallur', 'Vandavasi', 0, 0),
(1176, 'Senthamangalam', 'Chetpet', 0, 0),
(1177, 'Senthamp', 'Uthiramerur', 0, 0),
(1178, 'Senthangulam', 'Uthiramerur', 0, 0),
(1179, 'Sholinganallur', 'Vandavasi', 0, 0),
(1180, 'Silavattam', 'Cheyyur', 0, 0),
(1181, 'Sindhamani', 'Cheyyur', 0, 0),
(1182, 'Singanikuppam', 'Vandavasi', 0, 0),
(1183, 'Singanikuppm', 'Vandavasi', 0, 0),
(1184, 'Sirumailur UTR', 'Cheyyur', 0, 0),
(1185, 'Sirumur', 'Chetpet', 0, 0),
(1186, 'Sirunagar', 'Cheyyur', 0, 0),
(1187, 'Sirungatur', 'Maduranthakam', 0, 0),
(1188, 'Siruperpandi', 'Cheyyur', 0, 0),
(1189, 'Siruthalaipoondi', 'Chetpet', 0, 0),
(1190, 'Siruvanguanam', 'Maduranthakam', 0, 0),
(1191, 'Solanthangal', 'Maduranthakam', 0, 0),
(1192, 'Soorai', 'Maduranthakam', 0, 0),
(1193, 'Soorakottai', 'Maduranthakam', 0, 0),
(1194, 'Sozhangunam', 'Chetpet', 0, 0),
(1195, 'Sozhanthangal', 'Cheyyur', 0, 0),
(1196, 'Sundupallam', 'Uthiramerur', 0, 0),
(1197, 'Thachanur', 'Chetpet', 0, 0),
(1198, 'Thakkaraithangal', 'Maduranthakam', 0, 0),
(1199, 'Thalarbadi', 'Chetpet', 0, 0),
(1200, 'Thamara Pakkam', 'Vandavasi', 0, 0),
(1201, 'Thandaraipettai', 'Maduranthakam', 0, 0),
(1202, 'Thateri', 'Vandavasi', 0, 0),
(1203, 'Thathankuppam', 'Chetpet', 0, 0),
(1204, 'Thattancherry', 'Vandavasi', 0, 0),
(1205, 'Thattangulam', 'Uthiramerur', 0, 0),
(1206, 'Thazhangunam', 'Chetpet', 0, 0),
(1207, 'Then Aalapiranthan', 'Vandavasi', 0, 0),
(1208, 'Theneripattu', 'Cheyyur', 0, 0),
(1209, 'Thenkaranai', 'Vandavasi', 0, 0),
(1210, 'Thennampattu', 'Maduranthakam', 0, 0),
(1211, 'Thenpakkam', 'Cheyyur', 0, 0),
(1212, 'Thimiri', 'Chetpet', 0, 0),
(1213, 'Thimmapuram', 'Cheyyur', 0, 0),
(1214, 'Thinnalur', 'Vandavasi', 0, 0),
(1215, 'Thirumangalam', 'Vandavasi', 0, 0),
(1216, 'Thirumukkadu', 'Cheyyur', 0, 0),
(1217, 'Thirumukoodal', 'Uthiramerur', 0, 0),
(1218, 'Thiruvadur', 'Maduranthakam', 0, 0),
(1219, 'Thiruvakkarai', 'Vandavasi', 0, 0),
(1220, 'Thiruvalacherry', 'Cheyyur', 0, 0),
(1221, 'Thiruvathavar', 'Uthiramerur', 0, 0),
(1222, 'Thiruvenkatapuram', 'Uthiramerur', 0, 0),
(1223, 'Thondur', 'Chetpet', 0, 0),
(1224, 'Thonnadu', 'Maduranthakam', 0, 0),
(1225, 'Thottancherry', 'Vandavasi', 0, 0),
(1226, 'Thunaiyamkuppam', 'Vandavasi', 0, 0),
(1227, 'Thuthuvilampattu', 'Cheyyur', 0, 0),
(1228, 'Tiruvannamalai', 'Vandavasi', 0, 0),
(1229, 'Ulundhumangalam', 'Maduranthakam', 0, 0),
(1230, 'Ulundurpettai', 'Vandavasi', 0, 0),
(1231, 'Unamalai', 'Maduranthakam', 0, 0),
(1232, 'Uthamanallur', 'Cheyyur', 0, 0),
(1233, 'Vaadanallur', 'Uthiramerur', 0, 0),
(1234, 'Vaanur', 'Vandavasi', 0, 0),
(1235, 'Vada Aalapiranthan', 'Vandavasi', 0, 0),
(1236, 'Vada Kalpakkam', 'Vandavasi', 0, 0),
(1237, 'Vadamanapakkam', 'Cheyyur', 0, 0),
(1238, 'Vadamangalam', 'Maduranthakam', 0, 0),
(1239, 'Vadampoondi', 'Vandavasi', 0, 0),
(1240, 'Vaduvankudisai', 'Vandavasi', 0, 0),
(1241, 'Vairapuram', 'Vandavasi', 0, 0),
(1242, 'Vaiyavur', 'Uthiramerur', 0, 0),
(1243, 'Vakadai', 'Vandavasi', 0, 0),
(1244, 'Valalur', 'Uthiramerur', 0, 0),
(1245, 'Valarpirai', 'Maduranthakam', 0, 0),
(1246, 'Valasiramani', 'Cheyyur', 0, 0),
(1247, 'Valluvapakkam', 'Maduranthakam', 0, 0),
(1248, 'Vandivakam', 'Uthiramerur', 0, 0),
(1249, 'Vanganur', 'Vandavasi', 0, 0),
(1250, 'Vannankuttai', 'Chetpet', 0, 0),
(1251, 'Vanniyanallur', 'Cheyyur', 0, 0),
(1252, 'Vanniyar pettai', 'Maduranthakam', 0, 0),
(1253, 'Varagur', 'Vandavasi', 0, 0),
(1254, 'Varatharajapuram', 'Vandavasi', 0, 0),
(1255, 'Vasanthavadi', 'Cheyyur', 0, 0),
(1256, 'Vazhaipanthal', 'Vandavasi', 0, 0),
(1257, 'Vazhkudai', 'Vandavasi', 0, 0),
(1258, 'Veeranakunam', 'Maduranthakam', 0, 0),
(1259, 'Velaamur UTR', 'Maduranthakam', 0, 0),
(1260, 'Velapadi', 'Vandavasi', 0, 0),
(1261, 'Velari', 'Cheyyur', 0, 0),
(1262, 'Velimedupettai', 'Vandavasi', 0, 0),
(1263, 'Veliyampettai', 'Chetpet', 0, 0),
(1264, 'Vellamur', 'Uthiramerur', 0, 0),
(1265, 'Velleripattu', 'Chetpet', 0, 0),
(1266, 'Vempakkam', 'Vandavasi', 0, 0),
(1267, 'Vengikkal', 'Vandavasi', 0, 0),
(1268, 'Vennagupattu', 'Maduranthakam', 0, 0),
(1269, 'Vettaikaranpettai', 'Chetpet', 0, 0),
(1270, 'Vettam Perumbakkam', 'Cheyyur', 0, 0),
(1271, 'Vettur', 'Cheyyur', 0, 0);
INSERT INTO `area_list_creation` (`area_id`, `area_name`, `taluk`, `area_enable`, `status`) VALUES
(1272, 'Vilakkanandal', 'Chetpet', 0, 0),
(1273, 'Villanallur', 'Vandavasi', 0, 0),
(1274, 'Vilvarayanpettai', 'Maduranthakam', 0, 0),
(1275, 'Vyasarbadi', 'Chetpet', 0, 0),
(1277, 'Alliyanthangal', 'Chetpet', 0, 0),
(1278, 'Alwarpettai', 'Chetpet', 0, 0),
(1279, 'Avathikapuram', 'Vandavasi', 0, 0),
(1280, 'Chinnakalandhal', 'Polur', 0, 0),
(1281, 'Devanampattu', 'Polur', 0, 0),
(1282, 'Elathur PLR', 'Polur', 0, 0),
(1283, 'Erikuppam', 'Polur', 0, 0),
(1284, 'Nallalam', 'Vandavasi', 0, 0),
(1285, 'Namamangalam', 'Chetpet', 0, 0),
(1286, 'Nammiyandhal', 'Polur', 0, 0),
(1287, 'Pulunthai', 'Vandavasi', 0, 0),
(1288, 'Setlam pattu', 'Polur', 0, 0),
(1289, 'Theppananthal', 'Polur', 0, 0),
(1290, 'Vilapakkam', 'Polur', 0, 0),
(1291, 'Kozhavur', 'Polur', 0, 0),
(1292, 'Eluvambadi', 'Polur', 0, 0),
(1293, 'Kallarapadi', 'Polur', 0, 0),
(1294, 'Devanambattu', 'Polur', 0, 0),
(1295, 'Chinnandal', 'Chetpet', 0, 0),
(1296, 'Pazhavery', 'Vandavasi', 0, 0),
(1297, 'Kaikilanthangal', 'Polur', 0, 0),
(1298, 'Chinnasandiram', 'Vandavasi', 0, 0),
(1299, 'Siruvallur', 'Polur', 0, 0),
(1300, 'Namathaguttai', 'Polur', 0, 0),
(1301, 'Ananthamangalam', 'Vandavasi', 0, 0),
(1302, 'Eraiyankadu', 'Chetpet', 0, 0),
(1303, 'Periyanthangal', 'Chetpet', 0, 0),
(1304, 'Sirukilambadi', 'Polur', 0, 0),
(1305, 'Thalavarampoondi', 'Uthiramerur', 0, 0),
(1306, 'Vellamalai', 'Uthiramerur', 0, 0),
(1307, 'Abiramapuram', 'Vandavasi', 0, 0),
(1308, 'Mettur', 'Chetpet', 0, 0),
(1309, 'Arpaakkam PLR', 'Polur', 0, 0),
(1310, 'Mulapuratai', 'Chetpet', 0, 0),
(1311, 'Yesurajapuram', 'Chetpet', 0, 0),
(1312, 'Thennagaram', 'Polur', 0, 0),
(1313, 'Karapallam', 'Polur', 0, 0),
(1314, 'Venkatapalayam', 'Polur', 0, 0),
(1315, 'Ayyampalayam', 'Polur', 0, 0),
(1316, 'Periya Kilambadi', 'Polur', 0, 0),
(1317, 'Kovur Neelanthangal', 'Polur', 0, 0),
(1318, 'Mambattu PLR', 'Polur', 0, 0),
(1319, 'Anandal', 'Polur', 0, 0),
(1320, 'Jalaganda vinayagapuram', 'Vandavasi', 0, 0),
(1321, 'Karalpakkam', 'Polur', 0, 0),
(1322, 'Settupattu', 'Vandavasi', 0, 0),
(1323, 'Natchathra Koil', 'Polur', 0, 0),
(1324, 'Thellarampattu', 'Chetpet', 0, 0),
(1325, 'Bosco puram', 'Vandavasi', 0, 0),
(1326, 'Mahamayee Thirumani', 'Vandavasi', 0, 0),
(1327, 'Periyakallanthal', 'Polur', 0, 0),
(1328, 'Kettavaram Palayam', 'Polur', 0, 0),
(1329, 'Kattavaram', 'Polur', 0, 0),
(1330, 'Poondi PLR', 'Polur', 0, 0),
(1331, 'Maruthuvambadi PLR', 'Polur', 0, 0),
(1332, 'VilVarayanallur PLR', 'Polur', 0, 0),
(1333, 'Arpakkam PLR', 'Polur', 0, 0),
(1334, 'Randham PLR', 'Polur', 0, 0),
(1335, 'Melkaranai PLR', 'Polur', 0, 0),
(1336, 'Melathangal PLR', 'Polur', 0, 0),
(1337, 'Peranambakkam PLR', 'Polur', 0, 0),
(1338, 'Kattuputhur PLR', 'Polur', 0, 0),
(1339, 'Cholavaram PLR', 'Polur', 0, 0),
(1340, 'Arumbalur PLR', 'Polur', 0, 0),
(1341, 'Boothamangalam PLR', 'Polur', 0, 0),
(1342, 'Aariyathur PLR', 'Polur', 0, 0),
(1343, 'Samathuvapuram PLR', 'Polur', 0, 0),
(1344, 'Kalambur PLR', 'Polur', 0, 0),
(1345, 'Nainavaram PLR', 'Polur', 0, 0),
(1346, 'Karapoondi PLR', 'Polur', 0, 0),
(1347, 'Narayanamangalam PLR', 'Polur', 0, 0),
(1348, 'Kannigapuram PLR', 'Polur', 0, 0),
(1349, 'Krishnapuram PLR', 'Polur', 0, 0),
(1350, 'Pulivananthal PLR', 'Polur', 0, 0),
(1351, 'Endhal PLR', 'Polur', 0, 0),
(1352, 'Nadukuppam PLR', 'Polur', 0, 0),
(1353, 'Elluparai PLR', 'Polur', 0, 0),
(1354, 'Ariyathur PLR', 'Polur', 0, 0),
(1355, 'Kommananthal PLR', 'Polur', 0, 0),
(1356, 'Jothi Nagar PLR', 'Polur', 0, 0),
(1357, 'Madavilagam PLR', 'Polur', 0, 0),
(1358, 'Samathuvapuram UTR', 'Uthiramerur', 0, 0),
(1359, 'Vinayagapuram UTR', 'Uthiramerur', 0, 0),
(1360, 'Paleshwaram UTR', 'Uthiramerur', 0, 0),
(1361, 'Edayampudhur UTR', 'Uthiramerur', 0, 0),
(1362, 'Nerkundram UTR', 'Uthiramerur', 0, 0),
(1363, 'Neerkundram UTR', 'Uthiramerur', 0, 0),
(1364, 'AmaravathiPattinam UTR', 'Uthiramerur', 0, 0),
(1365, 'Arunkundram UTR', 'Uthiramerur', 0, 0),
(1366, 'Pulivoy UTR', 'Uthiramerur', 0, 0),
(1367, 'Elayanarvelur UTR', 'Uthiramerur', 0, 0),
(1368, 'Vayalakkavoor UTR', 'Uthiramerur', 0, 0),
(1369, 'Nallur UTR', 'Uthiramerur', 0, 0),
(1370, 'Kolathur UTR', 'Uthiramerur', 0, 0),
(1371, 'Salavakkam Koot Road UTR', 'Uthiramerur', 0, 0),
(1372, 'Kilakadi UTR', 'Uthiramerur', 0, 0),
(1373, 'Vinnavadi UTR', 'Uthiramerur', 0, 0),
(1374, 'Mangalam UTR', 'Uthiramerur', 0, 0),
(1375, 'Arayanipalai UTR', 'Uthiramerur', 0, 0),
(1376, 'Krishnapuram UTR', 'Uthiramerur', 0, 0),
(1377, 'Nellimodhu UTR', 'Uthiramerur', 0, 0),
(1378, 'Ramapuram CPT', 'Chetpet', 0, 0),
(1379, 'Thellur CPT', 'Chetpet', 0, 0),
(1380, 'Sindagam Poondi', 'Chetpet', 0, 0),
(1381, 'Maruthuvambadi CPT', 'Chetpet', 0, 0),
(1382, 'Kannigapuram cpt', 'Chetpet', 0, 0),
(1383, 'Vinayagapuram CPT', 'Chetpet', 0, 0),
(1384, 'Nambedu CPT', 'Chetpet', 0, 0),
(1385, 'Sanjivarayanpettai CPT', 'Chetpet', 0, 0),
(1386, 'Madam CPT', 'Chetpet', 0, 0),
(1387, 'Kothandavadi CPT', 'Chetpet', 0, 0),
(1388, 'Kaividanthangal CPT', 'Chetpet', 0, 0),
(1389, 'Kalayanapuram CPT', 'Chetpet', 0, 0),
(1390, 'Ponnur CPT', 'Chetpet', 0, 0),
(1391, 'Peranambakkam CPT', 'Chetpet', 0, 0),
(1392, 'Pinnanur CPT', 'Chetpet', 0, 0),
(1393, 'Malaiyittan Kuppam VSI', 'Chetpet', 0, 1),
(1394, 'Isakolathur CPT', 'Chetpet', 0, 0),
(1395, 'Karanai UTR', 'Uthiramerur', 0, 0),
(1396, 'Papanallur UTR', 'Uthiramerur', 0, 0),
(1397, 'Kovalai UTR', 'Uthiramerur', 0, 0),
(1398, 'Vengodu UTR', 'Uthiramerur', 0, 0),
(1399, 'Kannigapuram UTR', 'Uthiramerur', 0, 0),
(1400, 'Perumbakkam UTR', 'Uthiramerur', 0, 0),
(1401, 'Narasingapuram CPT', 'Chetpet', 0, 0),
(1402, 'MelNemili CPT', 'Chetpet', 0, 0),
(1403, 'Malaiyittankuppam VSI', 'Vandavasi', 0, 0),
(1404, 'Melkaranai CPT', 'Chetpet', 1, 0),
(1405, 'Kodithandalam', 'Uthiramerur', 0, 0),
(1406, 'Anmarudhai CPT', 'Chetpet', 0, 0),
(1407, 'Reddikuppam', 'Vandavasi', 0, 1),
(1408, 'Reddi kuppam', 'Chetpet', 0, 0),
(1409, 'Kalyanamedu UTR', 'Uthiramerur', 0, 0),
(1410, 'Gandhinagar', 'Chetpet', 0, 0),
(1411, 'Irumbedu UTR', 'Uthiramerur', 0, 0),
(1412, 'Lakshmipuram CPT', 'Chetpet', 0, 0),
(1413, 'Chokkapallam CPT', 'Chetpet', 0, 0),
(1414, 'Cholavaram CPT', 'Chetpet', 0, 0),
(1415, 'Anathamangalam CPT', 'Chetpet', 0, 0),
(1416, 'Krishnapuram CPT', 'Chetpet', 0, 0),
(1417, 'Avaniyapuram CPT', 'Chetpet', 0, 0),
(1418, 'Kandamanallur CPT', 'Chetpet', 0, 0),
(1419, 'Papanthangal CPT', 'Chetpet', 0, 0),
(1420, 'Agaram CPT', 'Chetpet', 0, 0),
(1421, 'Thennathur CPT', 'Chetpet', 0, 0),
(1422, 'Singampoondi CPT', 'Chetpet', 0, 0),
(1423, 'Vadavetti CPT', 'Chetpet', 0, 0),
(1424, 'Sennanthal CPT', 'Chetpet', 0, 0),
(1425, 'Vinnamangalam CPT', 'Chetpet', 0, 0),
(1426, 'Kalyanapuram CPT', 'Chetpet', 0, 0),
(1427, 'Kattupakkam CPT', 'Chetpet', 0, 0),
(1428, 'Mottur CPT', 'Chetpet', 0, 0),
(1429, 'Vayaloor CPT', 'Chetpet', 0, 0),
(1430, 'Udiyanthangal CPT', 'Chetpet', 0, 0),
(1431, 'Pudur CPT', 'Chetpet', 0, 0),
(1432, 'KANDA', 'Vandavasi', 0, 1),
(1433, 'Melanur', 'Chetpet', 0, 0),
(1434, 'Puthupatti', 'Chetpet', 0, 0),
(1435, 'Sambuvaraya Nallur', 'Polur', 0, 0),
(1448, 'Poongavanam', 'Chetpet', 0, 0),
(1449, 'Kariyambadi', 'Polur', 0, 0),
(1450, 'Gudisaikarai', 'Chetpet', 0, 0),
(1451, 'Agaram Pallipattu', 'Vandavasi', 0, 0),
(1452, 'Sandisatchi', 'Chetpet', 0, 0),
(1453, 'Puthupattu', 'Vandavasi', 0, 0),
(1454, 'Kulakkarai', 'Vandavasi', 0, 0),
(1455, 'Kizh Peranamalur', 'Vandavasi', 0, 0),
(1456, 'Vijayaragavapuram', 'Vandavasi', 0, 0),
(1457, 'Janagipuram', 'Vandavasi', 0, 0),
(1458, 'Kattupakkam', 'Uthiramerur', 1, 0),
(1459, 'Vedanthangal UTR', 'Uthiramerur', 0, 0),
(1460, 'Perunkadaputhur VSI', 'Vandavasi', 0, 0),
(1461, 'Marakunam VSI', 'Vandavasi', 0, 0),
(1462, 'Korasalavadi VSI', 'Vandavasi', 0, 0),
(1463, 'Aalanthangal VSI', 'Vandavasi', 0, 0),
(1464, 'Velamur VSI', 'Vandavasi', 0, 0),
(1465, 'Mettupalayam UTR', 'Uthiramerur', 0, 0),
(1466, 'Alliyamangalam CPT', 'Chetpet', 0, 0),
(1467, 'Alathur UTR', 'Uthiramerur', 0, 0),
(1468, 'Alathurai UTR', 'Uthiramerur', 0, 0),
(1469, 'Nadupattu CPT', 'Chetpet', 0, 0),
(1470, 'Padur', 'Vandavasi', 0, 0),
(1471, 'Vadamanipakkam', 'Vandavasi', 0, 0),
(1472, 'Murukkeri VSI', 'Vandavasi', 0, 0),
(1473, 'Mutharasampoondi', 'Polur', 0, 0),
(1474, 'Vilakanthal  PLR', 'Polur', 0, 0),
(1475, 'Maruthuvambadi VSI', 'Vandavasi', 0, 0),
(1476, 'Chethupet', 'Uthiramerur', 0, 0),
(1477, 'Mottur UTR', 'Uthiramerur', 0, 0),
(1478, 'Vengaram VSI', 'Vandavasi', 0, 0),
(1479, 'Elapakkam UTR', 'Uthiramerur', 0, 0),
(1480, 'Aalathur VSI', 'Vandavasi', 0, 0),
(1481, 'Pulavanpadi PLR', 'Polur', 0, 0),
(1482, 'Arasur', 'Vandavasi', 0, 0),
(1483, 'Saanarpalayam', 'Polur', 0, 0),
(1484, 'Thacharampattu', 'Polur', 0, 0),
(1485, 'Thandarai Pettai', 'Uthiramerur', 0, 0),
(1486, 'Ogur', 'Polur', 0, 0),
(1487, 'Kilankuppam', 'Polur', 0, 0),
(1488, 'Thuraiyur', 'Uthiramerur', 0, 0),
(1489, 'Gunankaranai', 'Vandavasi', 0, 0),
(1490, 'Mettupalayam vsi', 'Vandavasi', 0, 0),
(1491, 'Nariyampettai', 'Polur', 0, 0),
(1492, 'Thimmapuram UTR', 'Uthiramerur', 0, 0),
(1493, 'Mudhalur', 'Vandavasi', 0, 0),
(1494, 'Mattapiraiyur Alliyalamangalam', 'Polur', 0, 0),
(1495, 'Chennanathal', 'Polur', 0, 0),
(1496, 'Vanniyanoor', 'Polur', 0, 0),
(1497, 'Sirumailur', 'Uthiramerur', 0, 0),
(1498, 'Edamachi', 'Uthiramerur', 0, 0),
(1499, 'Naranamangalam', 'Thiruvannamalai', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bank_creation`
--

CREATE TABLE `bank_creation` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `bank_name` varchar(255) DEFAULT NULL,
  `short_name` varchar(255) NOT NULL,
  `acc_no` varchar(255) DEFAULT NULL,
  `ifsc` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `gpay` varchar(255) DEFAULT NULL,
  `company` varchar(255) NOT NULL,
  `under_branch` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT '0',
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bank_creation`
--

INSERT INTO `bank_creation` (`id`, `bank_name`, `short_name`, `acc_no`, `ifsc`, `branch`, `qr_code`, `gpay`, `company`, `under_branch`, `status`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date`) VALUES
(2, 'KVB VSI', 'KVB ', '1183135000003883', 'KVBL0001183', 'Vandavasi', '677e4b1c0e299.', '', '1', '1,2,3,5', '0', '2', NULL, NULL, '2025-01-08 15:23:32', '2025-01-08 15:23:32'),
(3, 'CUB UTR', 'CUB UTR', '51090901064527', 'CIUB0000451', 'Vandavasi', '677e640c155c1.', '', '1', '2', '0', '2', NULL, NULL, '2025-01-08 17:09:56', '2025-01-08 17:09:56');

-- --------------------------------------------------------

--
-- Table structure for table `bank_stmt`
--

CREATE TABLE `bank_stmt` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `bank_id` varchar(255) DEFAULT NULL,
  `trans_date` date DEFAULT NULL,
  `narration` varchar(255) NOT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `credit` varchar(255) DEFAULT NULL,
  `debit` varchar(255) DEFAULT NULL,
  `balance` varchar(255) DEFAULT NULL,
  `clr_status` varchar(10) NOT NULL DEFAULT '0' COMMENT '0 - unclear,1-cleared',
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `branch_creation`
--

CREATE TABLE `branch_creation` (
  `branch_id` int(11) NOT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `branch_name` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `address1` text DEFAULT NULL,
  `address2` text DEFAULT NULL,
  `whatsapp_number` text DEFAULT NULL,
  `place` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `email_id` varchar(255) DEFAULT NULL,
  `landline_number` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `taluk` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `insert_login_id` int(11) DEFAULT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `delete_login_id` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `branch_creation`
--

INSERT INTO `branch_creation` (`branch_id`, `branch_code`, `branch_name`, `company_name`, `mobile_number`, `address1`, `address2`, `whatsapp_number`, `place`, `pincode`, `email_id`, `landline_number`, `state`, `district`, `taluk`, `status`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date`) VALUES
(1, 'M-101', 'Vandavasi', '1', '', '1, Kasthuri bai St, Vandavasi', '', '', 'Vandavasi', '604408', '', '', 'TamilNadu', 'Tiruvannamalai', 'Vandavasi', 0, 1, 7, 7, '2023-10-05 20:06:21', '2023-10-05 20:06:21'),
(2, 'M-102', 'Uthiramerur', '1', '', 'Kanchipuram road , Uthiramerur', '', '', 'Uhtiramerur', '604403', '', '', 'TamilNadu', 'Kancheepuram', 'Uthiramerur', 0, 1, NULL, NULL, '2023-10-05 20:07:52', '2023-10-05 20:07:52'),
(3, 'M-103', 'Chetpet', '1', '', 'Near KVB , Chetpet', '', '', 'Chetpet', '604402', '', '', 'TamilNadu', 'Tiruvannamalai', 'Chetpet', 0, 1, NULL, NULL, '2023-10-05 20:09:35', '2023-10-05 20:09:35'),
(4, 'M-104', 'Polur', '1', '', 'Cheongsam road , Polur', '', '', 'Polur', '604401', '', '', 'TamilNadu', 'Tiruvannamalai', 'Polur', 0, 1, NULL, NULL, '2023-10-05 20:11:52', '2023-10-05 20:11:52'),
(5, 'M-105', 'Cheyyar', '1', '', 'Arcot road, Cheyyar', '', '', 'Cheyyar', '604407', '', '', 'TamilNadu', 'Tiruvannamalai', 'Cheyyar', 0, 1, NULL, NULL, '2023-10-05 20:13:03', '2023-10-05 20:13:03');

-- --------------------------------------------------------

--
-- Table structure for table `cash_tally`
--

CREATE TABLE `cash_tally` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `op_hand` varchar(255) DEFAULT NULL,
  `op_date` varchar(10) NOT NULL,
  `op_bank` varchar(255) DEFAULT NULL,
  `op_agent` varchar(255) DEFAULT NULL,
  `opening_bal` varchar(255) DEFAULT NULL,
  `cl_date` date NOT NULL,
  `cl_hand` varchar(255) DEFAULT NULL,
  `cl_bank` varchar(255) DEFAULT NULL,
  `bank_untrkd` varchar(255) NOT NULL,
  `cl_agent` varchar(255) DEFAULT NULL,
  `closing_bal` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cash_tally_modes`
--

CREATE TABLE `cash_tally_modes` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `modes` varchar(255) DEFAULT NULL,
  `admin_access` varchar(10) NOT NULL DEFAULT '1',
  `handcredit` varchar(10) NOT NULL DEFAULT '1',
  `bankcredit` varchar(10) NOT NULL DEFAULT '1',
  `handdebit` varchar(10) NOT NULL DEFAULT '1',
  `bankdebit` varchar(10) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cash_tally_modes`
--

INSERT INTO `cash_tally_modes` (`id`, `modes`, `admin_access`, `handcredit`, `bankcredit`, `handdebit`, `bankdebit`) VALUES
(1, 'Collection', '1', '0', '0', '1', '1'),
(2, 'Bank Withdrawal', '1', '0', '1', '1', '1'),
(3, 'Other Income', '1', '0', '0', '1', '1'),
(4, 'Exchange', '1', '0', '0', '0', '0'),
(5, 'Cash Deposit', '1', '1', '0', '1', '1'),
(6, 'Bank Deposit', '1', '1', '1', '0', '1'),
(7, 'Cash Withdrawal', '1', '1', '1', '1', '0'),
(8, 'Agent', '1', '0', '0', '0', '0'),
(9, 'Investment', '0', '0', '0', '0', '0'),
(10, 'Deposit', '0', '0', '0', '0', '0'),
(11, 'EL', '0', '0', '0', '0', '0'),
(12, 'Excess Fund', '0', '1', '1', '1', '0'),
(13, 'Issued', '1', '1', '1', '0', '0'),
(14, 'Expenses', '1', '1', '1', '0', '0');

-- --------------------------------------------------------

--
-- Table structure for table `cheque_info`
--

CREATE TABLE `cheque_info` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(250) DEFAULT NULL,
  `req_id` varchar(50) DEFAULT NULL,
  `cus_profile_id` varchar(50) DEFAULT NULL,
  `holder_type` varchar(255) DEFAULT NULL,
  `holder_name` varchar(255) DEFAULT NULL,
  `holder_relationship_name` varchar(255) DEFAULT NULL,
  `cheque_relation` varchar(255) DEFAULT NULL,
  `chequebank_name` varchar(255) DEFAULT NULL,
  `cheque_count` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cheque_no_list`
--

CREATE TABLE `cheque_no_list` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(250) DEFAULT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `cheque_table_id` varchar(255) DEFAULT NULL,
  `cheque_holder_type` varchar(255) DEFAULT NULL,
  `cheque_holder_name` varchar(255) DEFAULT NULL,
  `cheque_no` varchar(200) DEFAULT NULL,
  `used_status` varchar(255) NOT NULL DEFAULT '0',
  `noc_given` varchar(10) NOT NULL DEFAULT '0',
  `noc_date` varchar(255) DEFAULT NULL,
  `noc_person` varchar(255) DEFAULT NULL,
  `noc_name` varchar(255) DEFAULT NULL,
  `temp_sts` varchar(10) NOT NULL DEFAULT '0',
  `temp_date` date DEFAULT NULL,
  `temp_person` varchar(255) DEFAULT NULL,
  `temp_purpose` varchar(255) DEFAULT NULL,
  `temp_remarks` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(100) DEFAULT NULL,
  `update_login_id` varchar(100) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cheque_upd`
--

CREATE TABLE `cheque_upd` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(250) DEFAULT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `cheque_table_id` varchar(255) DEFAULT NULL,
  `upload_cheque_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `closed_status`
--

CREATE TABLE `closed_status` (
  `id` int(11) NOT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `closed_sts` varchar(50) DEFAULT NULL,
  `consider_level` varchar(50) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `cus_sts` varchar(50) DEFAULT NULL,
  `insert_login_id` varchar(100) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `closing_customer`
--

CREATE TABLE `closing_customer` (
  `id` int(11) NOT NULL,
  `req_id` int(11) NOT NULL,
  `cus_id` varchar(255) NOT NULL,
  `closing_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `collection`
--

CREATE TABLE `collection` (
  `coll_id` int(11) NOT NULL COMMENT 'Primary Key',
  `coll_code` varchar(255) DEFAULT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `cus_name` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `sub_area` varchar(255) DEFAULT NULL,
  `line` varchar(255) DEFAULT NULL,
  `loan_category` varchar(255) DEFAULT NULL,
  `sub_category` varchar(255) DEFAULT NULL,
  `coll_status` varchar(255) DEFAULT NULL,
  `coll_sub_status` varchar(255) DEFAULT NULL,
  `tot_amt` varchar(255) DEFAULT NULL,
  `paid_amt` varchar(255) DEFAULT NULL,
  `bal_amt` varchar(255) DEFAULT NULL,
  `due_amt` varchar(255) DEFAULT NULL,
  `pending_amt` varchar(255) DEFAULT NULL,
  `payable_amt` varchar(255) DEFAULT NULL,
  `penalty` varchar(255) DEFAULT NULL,
  `coll_charge` varchar(255) DEFAULT NULL,
  `coll_mode` varchar(255) DEFAULT NULL,
  `bank_id` varchar(10) NOT NULL,
  `cheque_no` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `trans_date` date DEFAULT NULL,
  `coll_location` varchar(255) DEFAULT NULL,
  `coll_date` datetime DEFAULT current_timestamp(),
  `due_amt_track` varchar(255) NOT NULL DEFAULT '0',
  `princ_amt_track` varchar(255) DEFAULT '0',
  `int_amt_track` varchar(255) DEFAULT '0',
  `penalty_track` varchar(255) NOT NULL DEFAULT '0',
  `coll_charge_track` varchar(255) NOT NULL DEFAULT '0',
  `total_paid_track` varchar(255) NOT NULL DEFAULT '0',
  `pre_close_waiver` varchar(255) NOT NULL DEFAULT '0',
  `penalty_waiver` varchar(255) NOT NULL DEFAULT '0',
  `coll_charge_waiver` varchar(255) NOT NULL DEFAULT '0',
  `total_waiver` varchar(255) NOT NULL DEFAULT '0',
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL COMMENT 'Create Time',
  `updated_date` datetime DEFAULT current_timestamp() COMMENT 'Update Time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `collection_charges`
--

CREATE TABLE `collection_charges` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `req_id` varchar(255) DEFAULT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `coll_date` varchar(255) DEFAULT NULL,
  `coll_purpose` varchar(255) DEFAULT NULL,
  `coll_charge` varchar(255) NOT NULL DEFAULT '0',
  `paid_date` varchar(255) DEFAULT NULL,
  `paid_amnt` varchar(255) DEFAULT '0',
  `waiver_amnt` varchar(255) DEFAULT '0',
  `status` int(11) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL COMMENT 'Create Time',
  `updated_date` datetime DEFAULT current_timestamp() COMMENT 'Update Time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `commitment`
--

CREATE TABLE `commitment` (
  `id` int(11) NOT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `cus_id` varchar(255) NOT NULL,
  `ftype` varchar(10) DEFAULT NULL,
  `fstatus` varchar(55) DEFAULT NULL,
  `person_type` varchar(255) DEFAULT NULL,
  `person_name` varchar(255) DEFAULT NULL,
  `relationship` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `comm_date` date DEFAULT NULL,
  `hint` varchar(255) DEFAULT NULL,
  `comm_err` varchar(10) NOT NULL,
  `insert_login_id` varchar(55) DEFAULT NULL,
  `updated_login_id` varchar(55) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `company_creation`
--

CREATE TABLE `company_creation` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `taluk` varchar(255) DEFAULT NULL,
  `place` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `mailid` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `landline` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT '0',
  `insert_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `company_creation`
--

INSERT INTO `company_creation` (`company_id`, `company_name`, `address1`, `address2`, `state`, `district`, `taluk`, `place`, `pincode`, `website`, `mailid`, `mobile`, `whatsapp`, `landline`, `status`, `insert_user_id`, `update_user_id`, `created_date`, `updated_date`) VALUES
(1, 'Marudham Capitals', '1, Kasthuri bai  st, Vandavasi', '', 'TamilNadu', 'Tiruvannamalai', 'Vandavasi', 'Vandavasi', '604408', 'www.marudhamcapitals.com', 'mail@marudhamcapitals.com', '9842152211', '9842152211', '', '0', 1, NULL, '2023-10-05 20:05:04', '2023-10-05 20:05:04');

-- --------------------------------------------------------

--
-- Table structure for table `concern_creation`
--

CREATE TABLE `concern_creation` (
  `id` int(11) NOT NULL,
  `raising_for` varchar(50) DEFAULT NULL,
  `self_name` varchar(255) DEFAULT NULL,
  `self_code` varchar(150) DEFAULT NULL,
  `staff_name` varchar(255) DEFAULT NULL,
  `staff_dept_name` varchar(255) DEFAULT NULL,
  `staff_team_name` varchar(255) DEFAULT NULL,
  `ag_name` varchar(255) DEFAULT NULL,
  `ag_grp` varchar(255) DEFAULT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `cus_name` varchar(255) DEFAULT NULL,
  `cus_area` varchar(255) DEFAULT NULL,
  `cus_sub_area` varchar(255) DEFAULT NULL,
  `cus_group` varchar(255) DEFAULT NULL,
  `cus_line` varchar(255) DEFAULT NULL,
  `com_date` varchar(50) DEFAULT NULL,
  `com_code` varchar(50) DEFAULT NULL,
  `branch_name` varchar(50) DEFAULT NULL,
  `concern_to` varchar(50) DEFAULT NULL,
  `to_dept_name` varchar(255) DEFAULT NULL,
  `to_team_name` varchar(255) DEFAULT NULL,
  `com_sub` varchar(50) DEFAULT NULL,
  `com_remark` varchar(255) DEFAULT NULL,
  `com_priority` varchar(10) DEFAULT NULL,
  `staff_assign_to` varchar(50) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `solution_date` varchar(50) DEFAULT NULL,
  `communication` varchar(50) DEFAULT NULL,
  `uploads` varchar(255) DEFAULT NULL,
  `solution_remark` varchar(255) DEFAULT NULL,
  `feedback_date` varchar(10) DEFAULT NULL,
  `feedback_rating` varchar(10) DEFAULT NULL,
  `insert_user_id` varchar(50) DEFAULT NULL,
  `update_user_id` varchar(50) DEFAULT NULL,
  `delete_user_id` varchar(50) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `concern_subject`
--

CREATE TABLE `concern_subject` (
  `concern_sub_id` int(11) NOT NULL,
  `concern_subject` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `insert_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `delete_user_id` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `confirmation_followup`
--

CREATE TABLE `confirmation_followup` (
  `id` int(11) NOT NULL,
  `req_id` varchar(255) NOT NULL,
  `cus_id` varchar(255) NOT NULL,
  `person_type` varchar(255) NOT NULL,
  `person_name` varchar(255) NOT NULL,
  `relationship` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `upload` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `sub_status` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `remove_status` varchar(10) NOT NULL DEFAULT '0',
  `insert_login_id` varchar(50) NOT NULL,
  `update_login_id` varchar(50) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ct_bank_collection`
--

CREATE TABLE `ct_bank_collection` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `bank_id` varchar(255) DEFAULT NULL,
  `credited_amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_cr_bag`
--

CREATE TABLE `ct_cr_bag` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `ag_id` varchar(10) NOT NULL,
  `bank_id` varchar(255) DEFAULT NULL,
  `ref_code` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_cr_bank_withdraw`
--

CREATE TABLE `ct_cr_bank_withdraw` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `db_ref_id` varchar(255) DEFAULT NULL,
  `ref_code` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `from_bank_id` varchar(20) DEFAULT NULL,
  `cheque_no` varchar(35) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_dae` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_cr_bdeposit`
--

CREATE TABLE `ct_cr_bdeposit` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `bank_id` varchar(255) DEFAULT NULL,
  `ref_code` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `name_id` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `ident` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_cr_bel`
--

CREATE TABLE `ct_cr_bel` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `bank_id` varchar(255) DEFAULT NULL,
  `ref_code` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `name_id` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `ident` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_cr_bexchange`
--

CREATE TABLE `ct_cr_bexchange` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `db_ref_id` varchar(255) DEFAULT NULL,
  `from_bank_id` varchar(10) NOT NULL,
  `to_bank_id` varchar(10) NOT NULL,
  `from_user_id` varchar(10) NOT NULL,
  `to_user_id` varchar(10) NOT NULL,
  `ref_code` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `remark` varchar(255) NOT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_cr_binvest`
--

CREATE TABLE `ct_cr_binvest` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `bank_id` varchar(255) DEFAULT NULL,
  `ref_code` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `name_id` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `ident` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_cr_boti`
--

CREATE TABLE `ct_cr_boti` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `ref_code` varchar(255) DEFAULT NULL,
  `to_bank_id` varchar(10) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_cr_cash_deposit`
--

CREATE TABLE `ct_cr_cash_deposit` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `db_ref_id` varchar(255) DEFAULT NULL,
  `to_bank_id` varchar(20) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `ref_code` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(10) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_cr_hag`
--

CREATE TABLE `ct_cr_hag` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `ag_id` varchar(10) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_cr_hdeposit`
--

CREATE TABLE `ct_cr_hdeposit` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `name_id` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `ident` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_cr_hel`
--

CREATE TABLE `ct_cr_hel` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `name_id` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `ident` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_cr_hexchange`
--

CREATE TABLE `ct_cr_hexchange` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `db_ref_id` varchar(255) DEFAULT NULL,
  `to_user_id` varchar(10) NOT NULL,
  `from_user_id` varchar(10) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_cr_hinvest`
--

CREATE TABLE `ct_cr_hinvest` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `name_id` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `ident` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_cr_hoti`
--

CREATE TABLE `ct_cr_hoti` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `category` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_db_bag`
--

CREATE TABLE `ct_db_bag` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `ag_id` varchar(10) NOT NULL,
  `bank_id` varchar(255) DEFAULT NULL,
  `ref_code` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_db_bank_deposit`
--

CREATE TABLE `ct_db_bank_deposit` (
  `id` int(11) NOT NULL,
  `to_bank_id` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `received` int(11) NOT NULL DEFAULT 1,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_db_bdeposit`
--

CREATE TABLE `ct_db_bdeposit` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `bank_id` varchar(255) DEFAULT NULL,
  `ref_code` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `name_id` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `ident` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_db_bel`
--

CREATE TABLE `ct_db_bel` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `bank_id` varchar(255) DEFAULT NULL,
  `ref_code` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `name_id` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `ident` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_db_bexchange`
--

CREATE TABLE `ct_db_bexchange` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `ref_code` varchar(255) DEFAULT NULL,
  `from_acc_id` varchar(255) DEFAULT NULL,
  `to_bank_id` varchar(255) DEFAULT NULL,
  `to_user_id` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `received` int(11) NOT NULL DEFAULT 1,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_db_bexpense`
--

CREATE TABLE `ct_db_bexpense` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `username` varchar(255) DEFAULT NULL,
  `usertype` varchar(255) DEFAULT NULL,
  `ref_code` varchar(255) DEFAULT NULL,
  `bank_id` varchar(10) NOT NULL,
  `cat` varchar(255) DEFAULT NULL,
  `part` varchar(255) DEFAULT NULL,
  `vou_id` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) NOT NULL,
  `rec_per` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `upload` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_db_binvest`
--

CREATE TABLE `ct_db_binvest` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `bank_id` varchar(255) DEFAULT NULL,
  `ref_code` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `name_id` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `ident` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_db_bissued`
--

CREATE TABLE `ct_db_bissued` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `ref_code` varchar(255) DEFAULT NULL,
  `li_id` varchar(10) NOT NULL,
  `li_user_id` varchar(255) DEFAULT NULL,
  `li_bank_id` varchar(10) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `usertype` varchar(255) DEFAULT NULL,
  `cheque_no` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `netcash` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_db_cash_withdraw`
--

CREATE TABLE `ct_db_cash_withdraw` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `ref_code` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `from_bank_id` varchar(255) DEFAULT NULL,
  `cheque_no` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `received` varchar(10) NOT NULL DEFAULT '1',
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_db_exf`
--

CREATE TABLE `ct_db_exf` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `username` varchar(255) DEFAULT NULL,
  `usertype` varchar(255) DEFAULT NULL,
  `bank_id` varchar(10) NOT NULL,
  `ucl_ref_code` varchar(255) DEFAULT NULL,
  `ref_code` varchar(255) DEFAULT NULL,
  `ucl_trans_id` varchar(255) DEFAULT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_db_hag`
--

CREATE TABLE `ct_db_hag` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `ag_id` varchar(10) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_db_hdeposit`
--

CREATE TABLE `ct_db_hdeposit` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `name_id` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `ident` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_db_hel`
--

CREATE TABLE `ct_db_hel` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `name_id` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `ident` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_db_hexchange`
--

CREATE TABLE `ct_db_hexchange` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `to_user_id` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `received` int(11) NOT NULL DEFAULT 1,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_db_hexpense`
--

CREATE TABLE `ct_db_hexpense` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `username` varchar(255) DEFAULT NULL,
  `usertype` varchar(255) DEFAULT NULL,
  `cat` varchar(255) DEFAULT NULL,
  `part` varchar(255) DEFAULT NULL,
  `vou_id` varchar(255) DEFAULT NULL,
  `rec_per` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `upload` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_db_hinvest`
--

CREATE TABLE `ct_db_hinvest` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `name_id` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `ident` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_db_hissued`
--

CREATE TABLE `ct_db_hissued` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `li_user_id` varchar(255) DEFAULT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `netcash` varchar(255) DEFAULT NULL,
  `amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(10) NOT NULL,
  `update_login_id` varchar(10) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ct_hand_collection`
--

CREATE TABLE `ct_hand_collection` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `user_id` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `branch_id` varchar(255) DEFAULT NULL,
  `line_id` varchar(255) DEFAULT NULL,
  `pre_bal` varchar(255) DEFAULT NULL,
  `coll_amt` varchar(255) DEFAULT NULL,
  `tot_amt` varchar(255) DEFAULT NULL,
  `rec_amt` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_profile`
--

CREATE TABLE `customer_profile` (
  `id` int(11) NOT NULL,
  `req_id` varchar(50) DEFAULT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `cus_name` varchar(255) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `dob` varchar(50) DEFAULT NULL,
  `age` varchar(50) DEFAULT NULL,
  `blood_group` varchar(50) DEFAULT NULL,
  `mobile1` varchar(50) DEFAULT NULL,
  `mobile2` varchar(50) DEFAULT NULL,
  `whatsapp` varchar(50) DEFAULT NULL,
  `cus_pic` varchar(255) DEFAULT NULL,
  `guarentor_name` varchar(255) DEFAULT NULL,
  `guarentor_relation` varchar(100) DEFAULT NULL,
  `guarentor_photo` varchar(255) DEFAULT NULL,
  `cus_type` varchar(50) DEFAULT NULL,
  `cus_exist_type` varchar(50) DEFAULT NULL,
  `residential_type` varchar(50) DEFAULT NULL,
  `residential_details` varchar(255) DEFAULT NULL,
  `residential_address` varchar(255) DEFAULT NULL,
  `residential_native_address` varchar(255) DEFAULT NULL,
  `occupation_type` varchar(50) DEFAULT NULL,
  `occupation_details` varchar(255) DEFAULT NULL,
  `occupation_income` varchar(255) DEFAULT NULL,
  `occupation_address` varchar(255) DEFAULT NULL,
  `dow` varchar(255) DEFAULT NULL,
  `abt_occ` varchar(255) DEFAULT NULL,
  `area_confirm_type` varchar(50) DEFAULT NULL,
  `area_confirm_state` varchar(100) DEFAULT NULL,
  `area_confirm_district` varchar(100) DEFAULT NULL,
  `area_confirm_taluk` varchar(100) DEFAULT NULL,
  `area_confirm_area` varchar(255) DEFAULT NULL,
  `area_confirm_subarea` varchar(255) DEFAULT NULL,
  `latlong` varchar(255) DEFAULT NULL,
  `area_group` varchar(255) DEFAULT NULL,
  `area_line` varchar(255) DEFAULT NULL,
  `communication` varchar(50) DEFAULT NULL,
  `com_audio` varchar(255) DEFAULT NULL,
  `verification_person` varchar(255) DEFAULT NULL,
  `verification_location` varchar(255) DEFAULT NULL,
  `cus_status` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `insert_login_id` varchar(100) DEFAULT NULL,
  `update_login_id` varchar(100) DEFAULT NULL,
  `delete_login_id` varchar(100) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customer_register`
--

CREATE TABLE `customer_register` (
  `cus_reg_id` int(11) NOT NULL COMMENT 'Primary Key',
  `req_ref_id` varchar(255) DEFAULT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `taluk` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `sub_area` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mobile1` varchar(255) DEFAULT NULL,
  `mobile2` varchar(255) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `marital` varchar(255) DEFAULT NULL,
  `spouse` varchar(255) DEFAULT NULL,
  `occupation_type` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `how_to_know` varchar(100) DEFAULT NULL,
  `loan_count` varchar(50) DEFAULT NULL,
  `first_loan_date` varchar(255) DEFAULT NULL,
  `travel_with_company` varchar(255) DEFAULT NULL,
  `monthly_income` varchar(255) DEFAULT NULL,
  `other_income` varchar(255) DEFAULT NULL,
  `support_income` varchar(255) DEFAULT NULL,
  `commitment` varchar(255) DEFAULT NULL,
  `monthly_due_capacity` varchar(255) DEFAULT NULL,
  `loan_limit` varchar(255) DEFAULT NULL,
  `about_customer` varchar(255) DEFAULT NULL,
  `residential_type` varchar(10) DEFAULT NULL,
  `residential_details` varchar(150) DEFAULT NULL,
  `residential_address` varchar(255) DEFAULT NULL,
  `residential_native_address` varchar(255) DEFAULT NULL,
  `occupation_info_occ_type` varchar(10) DEFAULT NULL,
  `occupation_details` varchar(255) DEFAULT NULL,
  `occupation_income` varchar(255) DEFAULT NULL,
  `occupation_address` varchar(255) DEFAULT NULL,
  `dow` varchar(255) DEFAULT NULL,
  `abt_occ` varchar(255) DEFAULT NULL,
  `area_confirm_type` varchar(50) DEFAULT NULL,
  `area_confirm_state` varchar(50) DEFAULT NULL,
  `area_confirm_district` varchar(50) DEFAULT NULL,
  `area_confirm_taluk` varchar(50) DEFAULT NULL,
  `area_confirm_area` varchar(50) DEFAULT NULL,
  `area_confirm_subarea` varchar(50) DEFAULT NULL,
  `latlong` varchar(255) DEFAULT NULL,
  `area_group` varchar(50) DEFAULT NULL,
  `area_line` varchar(50) DEFAULT NULL,
  `cus_status` varchar(255) DEFAULT '0',
  `create_time` datetime DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_status`
--

CREATE TABLE `customer_status` (
  `id` int(11) NOT NULL,
  `req_id` varchar(255) NOT NULL,
  `cus_id` varchar(150) NOT NULL,
  `sub_status` varchar(50) NOT NULL,
  `payable_amnt` varchar(255) NOT NULL,
  `bal_amnt` varchar(255) NOT NULL,
  `last_paid_date` int(11) DEFAULT 0,
  `current_month_paid` int(11) DEFAULT 0,
  `insert_login_id` varchar(150) NOT NULL,
  `created_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cus_old_data`
--

CREATE TABLE `cus_old_data` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `cus_id` varchar(50) DEFAULT NULL,
  `cus_name` varchar(200) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `sub_area` varchar(100) DEFAULT NULL,
  `loan_cat` varchar(100) DEFAULT NULL,
  `sub_cat` varchar(100) DEFAULT NULL,
  `loan_amt` varchar(100) DEFAULT NULL,
  `due_chart_file` varchar(100) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `director_creation`
--

CREATE TABLE `director_creation` (
  `dir_id` int(11) NOT NULL,
  `dir_code` varchar(255) DEFAULT NULL,
  `dir_type` varchar(255) DEFAULT NULL,
  `dir_name` varchar(255) DEFAULT NULL,
  `company_id` varchar(255) DEFAULT NULL,
  `branch_id` varchar(255) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `taluk` varchar(255) DEFAULT NULL,
  `place` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `mail_id` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(255) DEFAULT NULL,
  `whatsapp_no` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT '0',
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `director_creation`
--

INSERT INTO `director_creation` (`dir_id`, `dir_code`, `dir_type`, `dir_name`, `company_id`, `branch_id`, `address1`, `address2`, `state`, `district`, `taluk`, `place`, `pincode`, `mail_id`, `mobile_no`, `whatsapp_no`, `status`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date`) VALUES
(1, 'EXD-101', '2', 'Prabakaran P', '1', '', '', '', 'TamilNadu', 'Tiruvannamalai', 'Vandavasi', 'Vandavasi', '604408', '', '9842322151', '', '0', '1', '1', NULL, '2023-10-06 13:52:00', '2023-10-06 13:52:43'),
(2, 'EXD-102', '2', 'Suresh G', '1', '', '', '', 'TamilNadu', 'Tiruvannamalai', 'Vandavasi', 'Vandavasi', '604408', '', '', '', '0', '1', NULL, NULL, '2023-10-06 13:53:16', '2023-10-06 13:53:16'),
(3, 'EXD-103', '2', 'Saravanan R', '1', '', '', '', 'TamilNadu', 'Tiruvannamalai', 'Vandavasi', 'Vandavasi', '604408', '', '', '', '0', '1', NULL, NULL, '2023-10-06 13:54:01', '2023-10-06 13:54:01'),
(4, 'D-104', '1', 'Jayakumar', '1', '', '', '', 'TamilNadu', 'Dindigul', 'Vedasandur', 'Vedasandur', '600000', '', '', '', '0', '1', NULL, NULL, '2023-10-06 13:55:06', '2023-10-06 13:55:06'),
(5, 'D-105', '1', 'Ashok kumar T', '1', '', '', '', 'TamilNadu', 'Kancheepuram', 'Kancheepuram', 'Kancheepuram', '604205', '', '', '', '0', '1', '1', NULL, '2023-10-06 13:55:55', '2023-10-06 17:27:39');

-- --------------------------------------------------------

--
-- Table structure for table `document_info`
--

CREATE TABLE `document_info` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `cus_id` varchar(250) DEFAULT NULL,
  `req_id` varchar(255) NOT NULL,
  `doc_name` varchar(255) DEFAULT NULL,
  `doc_detail` varchar(255) DEFAULT NULL,
  `doc_type` varchar(255) DEFAULT NULL,
  `doc_holder` varchar(255) DEFAULT NULL,
  `holder_name` varchar(255) DEFAULT NULL,
  `relation_name` varchar(255) NOT NULL,
  `relation` varchar(255) DEFAULT NULL,
  `doc_upload` varchar(255) DEFAULT NULL,
  `doc_info_upload_noc` int(11) DEFAULT 0,
  `noc_date` varchar(255) DEFAULT NULL,
  `noc_person` varchar(255) DEFAULT NULL,
  `noc_name` varchar(255) DEFAULT NULL,
  `doc_info_upload_used` int(11) DEFAULT 0,
  `temp_sts` varchar(10) NOT NULL DEFAULT '0',
  `temp_date` date DEFAULT NULL,
  `temp_person` varchar(255) DEFAULT NULL,
  `temp_purpose` varchar(255) DEFAULT NULL,
  `temp_remarks` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `document_track`
--

CREATE TABLE `document_track` (
  `id` int(11) NOT NULL,
  `req_id` varchar(100) NOT NULL,
  `cus_id` varchar(255) NOT NULL,
  `sign_doc_id` varchar(255) DEFAULT NULL,
  `cheque_doc_id` varchar(255) DEFAULT NULL,
  `ack_doc_id` varchar(100) DEFAULT NULL,
  `gold_doc_id` varchar(50) DEFAULT NULL,
  `doc_id` varchar(50) DEFAULT NULL,
  `track_status` varchar(50) DEFAULT NULL,
  `insert_login_id` varchar(50) NOT NULL,
  `update_login_id` varchar(50) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `doc_mapping`
--

CREATE TABLE `doc_mapping` (
  `doc_map_id` int(11) NOT NULL,
  `loan_category` varchar(255) DEFAULT NULL,
  `sub_category` varchar(255) DEFAULT NULL,
  `doc_creation` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT '0',
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `expense_category`
--

CREATE TABLE `expense_category` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fingerprints`
--

CREATE TABLE `fingerprints` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `adhar_num` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `hand` varchar(50) DEFAULT NULL,
  `ansi_template` longtext NOT NULL,
  `bitmap_data` longtext DEFAULT NULL,
  `insert_user_id` varchar(50) DEFAULT NULL,
  `update_user_id` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gold_info`
--

CREATE TABLE `gold_info` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(250) DEFAULT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `gold_sts` varchar(255) DEFAULT NULL,
  `gold_type` varchar(255) DEFAULT NULL,
  `Purity` varchar(255) DEFAULT NULL,
  `gold_Count` varchar(255) DEFAULT NULL,
  `gold_Weight` varchar(255) DEFAULT NULL,
  `gold_Value` varchar(255) DEFAULT NULL,
  `gold_upload` varchar(100) NOT NULL,
  `noc_given` varchar(10) NOT NULL DEFAULT '0',
  `noc_date` varchar(255) DEFAULT NULL,
  `noc_person` varchar(255) DEFAULT NULL,
  `noc_name` varchar(255) DEFAULT NULL,
  `used_status` varchar(10) NOT NULL DEFAULT '0',
  `temp_sts` varchar(10) NOT NULL DEFAULT '0',
  `temp_date` date DEFAULT NULL,
  `temp_person` varchar(255) DEFAULT NULL,
  `temp_purpose` varchar(255) DEFAULT NULL,
  `temp_remarks` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(100) DEFAULT NULL,
  `update_login_id` varchar(100) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `holiday_creation`
--

CREATE TABLE `holiday_creation` (
  `holiday_id` int(11) NOT NULL,
  `holiday_name` varchar(255) DEFAULT NULL,
  `holiday_date` varchar(250) DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `insert_login_id` int(11) DEFAULT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `delete_login_id` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `in_acknowledgement`
--

CREATE TABLE `in_acknowledgement` (
  `id` int(11) NOT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `cus_status` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `insert_login_id` varchar(50) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL DEFAULT current_timestamp(),
  `inserted_user` varchar(10) DEFAULT NULL,
  `inserted_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `in_approval`
--

CREATE TABLE `in_approval` (
  `req_id` varchar(255) DEFAULT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `cus_status` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT '0',
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `in_issue`
--

CREATE TABLE `in_issue` (
  `id` int(11) NOT NULL,
  `loan_id` varchar(255) DEFAULT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `cus_status` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `insert_login_id` varchar(50) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT NULL,
  `inserted_user` varchar(10) DEFAULT NULL,
  `inserted_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `in_verification`
--

CREATE TABLE `in_verification` (
  `req_id` int(11) NOT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `agent_id` varchar(255) DEFAULT NULL,
  `responsible` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `declaration` varchar(255) DEFAULT NULL,
  `req_code` varchar(255) DEFAULT NULL,
  `dor` date DEFAULT NULL,
  `cus_reg_id` varchar(255) DEFAULT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `cus_data` varchar(255) DEFAULT NULL,
  `cus_name` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `taluk` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `sub_area` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mobile1` varchar(255) DEFAULT NULL,
  `mobile2` varchar(255) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `marital` varchar(255) DEFAULT NULL,
  `spouse_name` varchar(255) DEFAULT NULL,
  `occupation_type` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `loan_category` varchar(255) DEFAULT NULL,
  `sub_category` varchar(255) DEFAULT NULL,
  `tot_value` varchar(255) DEFAULT NULL,
  `ad_amt` varchar(255) DEFAULT NULL,
  `ad_perc` varchar(255) DEFAULT NULL,
  `loan_amt` varchar(255) DEFAULT NULL,
  `poss_type` varchar(255) DEFAULT NULL,
  `due_amt` varchar(255) DEFAULT NULL,
  `due_period` varchar(255) DEFAULT NULL,
  `cus_status` varchar(255) DEFAULT '0',
  `prompt_remark` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT '0',
  `issue_by` int(11) NOT NULL DEFAULT 1,
  `issue_mode` varchar(50) DEFAULT NULL,
  `payment_type` varchar(50) DEFAULT NULL,
  `bank_id` varchar(50) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_calculation`
--

CREATE TABLE `loan_calculation` (
  `loan_cal_id` int(11) NOT NULL,
  `loan_category` varchar(250) DEFAULT NULL,
  `sub_category` varchar(250) DEFAULT NULL,
  `due_method` varchar(250) DEFAULT NULL,
  `due_type` varchar(250) DEFAULT NULL,
  `profit_method` varchar(250) DEFAULT NULL,
  `calculate_method` varchar(250) DEFAULT NULL,
  `intrest_rate_min` varchar(250) DEFAULT NULL,
  `intrest_rate_max` varchar(250) DEFAULT NULL,
  `due_period_min` varchar(250) DEFAULT NULL,
  `due_period_max` varchar(250) DEFAULT NULL,
  `doc_charge_type` varchar(11) DEFAULT NULL,
  `document_charge_min` varchar(250) DEFAULT NULL,
  `document_charge_max` varchar(250) DEFAULT NULL,
  `proc_fee_type` varchar(11) DEFAULT NULL,
  `processing_fee_min` varchar(250) DEFAULT NULL,
  `processing_fee_max` varchar(250) DEFAULT NULL,
  `loan_limit` varchar(255) DEFAULT NULL,
  `due_date` varchar(250) DEFAULT NULL,
  `grace_period` varchar(250) DEFAULT NULL,
  `penalty` varchar(250) DEFAULT NULL,
  `overdue` varchar(250) DEFAULT NULL,
  `collection_info` varchar(250) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `insert_login_id` int(11) DEFAULT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `delete_login_id` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_category`
--

CREATE TABLE `loan_category` (
  `loan_category_id` int(11) NOT NULL,
  `loan_category_name` varchar(255) DEFAULT NULL,
  `sub_category_name` varchar(255) DEFAULT NULL,
  `loan_limit` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `insert_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `delete_user_id` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_category_creation`
--

CREATE TABLE `loan_category_creation` (
  `loan_category_creation_id` int(11) NOT NULL,
  `loan_category_creation_name` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `insert_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `delete_user_id` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_category_ref`
--

CREATE TABLE `loan_category_ref` (
  `loan_category_ref_id` int(11) NOT NULL,
  `loan_category_ref_name` varchar(255) DEFAULT NULL,
  `loan_category_id` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `insert_user_id` int(11) DEFAULT NULL,
  `update_user_id` int(11) DEFAULT NULL,
  `delete_user_id` int(11) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_followup`
--

CREATE TABLE `loan_followup` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(255) NOT NULL,
  `stage` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `follow_date` date NOT NULL,
  `insert_login_id` varchar(50) NOT NULL,
  `update_login_id` varchar(50) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_issue`
--

CREATE TABLE `loan_issue` (
  `id` int(11) NOT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `issued_to` varchar(255) NOT NULL,
  `agent_id` varchar(255) DEFAULT NULL,
  `issued_mode` varchar(50) DEFAULT NULL,
  `payment_type` varchar(50) DEFAULT NULL,
  `cash` varchar(255) DEFAULT NULL,
  `bank_id` varchar(10) DEFAULT NULL,
  `cheque_no` varchar(255) DEFAULT NULL,
  `cheque_value` varchar(255) DEFAULT NULL,
  `cheque_remark` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `transaction_value` varchar(255) DEFAULT NULL,
  `transaction_remark` varchar(255) DEFAULT NULL,
  `balance_amount` varchar(255) NOT NULL,
  `loan_amt` varchar(255) NOT NULL,
  `net_cash` varchar(255) NOT NULL,
  `cash_guarentor_name` varchar(255) DEFAULT NULL,
  `relationship` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `insert_login_id` varchar(50) DEFAULT NULL,
  `update_login_id` varchar(50) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_scheme`
--

CREATE TABLE `loan_scheme` (
  `scheme_id` int(11) NOT NULL,
  `scheme_name` varchar(255) DEFAULT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `loan_category` varchar(255) DEFAULT NULL,
  `sub_category` varchar(255) DEFAULT NULL,
  `due_method` varchar(255) DEFAULT NULL,
  `profit_method` varchar(255) DEFAULT NULL,
  `intrest_rate` varchar(255) DEFAULT NULL,
  `total_due` varchar(255) DEFAULT NULL,
  `advance_due` varchar(255) DEFAULT NULL,
  `due_period` varchar(255) DEFAULT NULL,
  `intreset_type` varchar(50) DEFAULT NULL,
  `intreset_min` varchar(50) DEFAULT NULL,
  `intreset_max` varchar(50) DEFAULT NULL,
  `doc_charge_type` varchar(255) DEFAULT NULL,
  `doc_charge_min` varchar(255) DEFAULT NULL,
  `doc_charge_max` varchar(255) DEFAULT NULL,
  `proc_fee_type` varchar(255) DEFAULT NULL,
  `proc_fee_min` varchar(255) DEFAULT NULL,
  `proc_fee_max` varchar(255) DEFAULT NULL,
  `due_date` varchar(255) DEFAULT NULL,
  `overdue` varchar(255) DEFAULT NULL,
  `grace_period` varchar(255) DEFAULT NULL,
  `penalty` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `insert_login_id` int(11) DEFAULT NULL,
  `update_login_id` int(11) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_summary_feedback`
--

CREATE TABLE `loan_summary_feedback` (
  `id` int(11) NOT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `feedback_label` varchar(255) DEFAULT NULL,
  `cus_feedback` varchar(255) DEFAULT NULL,
  `feedback_remark` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `screens` varchar(255) DEFAULT NULL,
  `modules` varchar(255) DEFAULT NULL,
  `access` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `screens`, `modules`, `access`) VALUES
(61, 'Company Creation', 'edit_company_creation', 'company_creation'),
(62, 'Branch Creation', 'edit_branch_creation', 'branch_creation'),
(63, 'Loan Category', 'edit_loan_category', 'loan_category'),
(64, 'Loan Calculation', 'edit_loan_calculation', 'loan_calculation'),
(65, 'Loan Scheme', 'edit_loan_scheme', 'loan_scheme'),
(66, 'Area Creation', 'edit_area_creation', 'area_creation'),
(67, 'Area Mapping', 'edit_area_mapping', 'area_mapping'),
(68, 'Area Approval', 'area_status', 'area_approval'),
(69, 'Director Creation', 'edit_director_creation', 'director_creation'),
(70, 'Agent Creation', 'edit_agent_creation', 'agent_creation'),
(71, 'Staff Creation', 'edit_staff_creation', 'staff_creation'),
(72, 'Bank Creation', 'edit_bank_creation', 'bank_creation'),
(73, 'Manage Users', 'edit_manage_user', 'manage_user'),
(74, 'Request', 'edit_request', 'request'),
(75, 'Verification', 'verification_list', 'verification'),
(76, 'Acknowledgement', 'edit_acknowledgement_list', 'acknowledgement'),
(77, 'Loan Issue', 'edit_loan_issue', 'loan_issue'),
(78, 'Collection', 'edit_collection', 'collection'),
(79, 'Closed', 'edit_closed', 'closed'),
(80, 'NOC', 'edit_noc', 'noc'),
(81, 'Update', 'edit_update', 'update_screen'),
(82, 'Concern Creation', 'edit_concern_creation', 'concern_creation'),
(83, 'Concern Solution', 'edit_concern_solution', 'concern_solution'),
(84, 'Concern Feedback', 'edit_concern_feedback', 'concern_feedback'),
(85, 'Bank Clearance', 'edit_bank_clearance', 'bank_clearance'),
(86, 'Due Followup', 'edit_due_followup', 'due_followup'),
(87, 'Approval', 'approval_list', 'approvalmodule'),
(88, 'Document Track', 'document_track', 'doctrack'),
(89, 'Cash Tally', 'cash_tally', 'cash_tally'),
(90, 'Financial Insights', 'finance_insight', 'finance_insight'),
(91, 'Promotion Activity', 'promotion_activity', 'promotion_activity'),
(92, 'Loan Followup', 'loan_followup', 'loan_followup'),
(93, 'Confirmation Followup', 'confirmation_followup', 'confirmation_followup'),
(94, 'Dashboard', 'dashboard', 'dashboard');

-- --------------------------------------------------------

--
-- Table structure for table `name_detail_creation`
--

CREATE TABLE `name_detail_creation` (
  `name_id` int(11) NOT NULL COMMENT 'Primary Key',
  `name` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `ident` varchar(255) DEFAULT NULL,
  `opt_for` enum('inv','dep','el') DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT '0',
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `new_cus_promo`
--

CREATE TABLE `new_cus_promo` (
  `id` int(11) NOT NULL COMMENT 'Primary Key',
  `cus_id` varchar(255) DEFAULT NULL,
  `cus_name` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `sub_area` varchar(255) DEFAULT NULL,
  `int_status` varchar(10) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `new_promotion`
--

CREATE TABLE `new_promotion` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `follow_date` datetime DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `noc`
--

CREATE TABLE `noc` (
  `noc_id` int(11) NOT NULL COMMENT 'Primary Key',
  `req_id` varchar(255) DEFAULT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `sign_checklist` varchar(255) DEFAULT NULL,
  `cheque_checklist` varchar(255) DEFAULT NULL,
  `gold_checklist` varchar(255) DEFAULT NULL,
  `mort_checklist` varchar(255) DEFAULT NULL,
  `endorse_checklist` varchar(255) DEFAULT NULL,
  `doc_checklist` varchar(255) DEFAULT NULL,
  `noc_date` varchar(255) DEFAULT NULL,
  `noc_member` varchar(255) DEFAULT NULL,
  `mem_name` varchar(255) DEFAULT NULL,
  `cus_status` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `penalty_charges`
--

CREATE TABLE `penalty_charges` (
  `req_id` varchar(255) DEFAULT NULL,
  `penalty_date` varchar(255) DEFAULT NULL,
  `penalty` varchar(255) DEFAULT NULL,
  `paid_date` varchar(255) DEFAULT NULL,
  `paid_amnt` varchar(255) DEFAULT '0',
  `waiver_amnt` varchar(255) DEFAULT '0',
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `request_category_info`
--

CREATE TABLE `request_category_info` (
  `cat_info` int(11) NOT NULL,
  `req_ref_id` varchar(255) DEFAULT NULL,
  `category_info` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `request_creation`
--

CREATE TABLE `request_creation` (
  `req_id` int(11) NOT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `agent_id` varchar(255) DEFAULT NULL,
  `responsible` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `declaration` varchar(255) DEFAULT NULL,
  `req_code` varchar(255) DEFAULT NULL,
  `dor` date DEFAULT NULL,
  `cus_reg_id` varchar(255) DEFAULT NULL,
  `cus_id` varchar(255) DEFAULT NULL,
  `cus_data` varchar(255) DEFAULT NULL,
  `cus_name` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `taluk` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `sub_area` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mobile1` varchar(255) DEFAULT NULL,
  `mobile2` varchar(255) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `marital` varchar(255) DEFAULT NULL,
  `spouse_name` varchar(255) DEFAULT NULL,
  `occupation_type` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `loan_category` varchar(255) DEFAULT NULL,
  `sub_category` varchar(255) DEFAULT NULL,
  `tot_value` varchar(255) DEFAULT NULL,
  `ad_amt` varchar(255) DEFAULT NULL,
  `ad_perc` varchar(255) DEFAULT NULL,
  `loan_amt` varchar(255) DEFAULT NULL,
  `poss_type` varchar(255) DEFAULT NULL,
  `due_amt` varchar(255) DEFAULT NULL,
  `due_period` varchar(255) DEFAULT NULL,
  `cus_status` varchar(255) DEFAULT '0',
  `prompt_remark` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT '0',
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `signed_doc`
--

CREATE TABLE `signed_doc` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(250) DEFAULT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `signed_doc_id` varchar(255) DEFAULT NULL,
  `upload_doc_name` varchar(255) DEFAULT NULL,
  `noc_given` varchar(10) NOT NULL DEFAULT '0',
  `noc_date` varchar(255) DEFAULT NULL,
  `noc_person` varchar(255) DEFAULT NULL,
  `noc_name` varchar(255) DEFAULT NULL,
  `used_status` varchar(10) NOT NULL DEFAULT '0',
  `temp_sts` varchar(10) NOT NULL DEFAULT '0',
  `temp_date` date DEFAULT NULL,
  `temp_person` varchar(255) DEFAULT NULL,
  `temp_purpose` varchar(255) DEFAULT NULL,
  `temp_remarks` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(100) DEFAULT NULL,
  `update_login_id` varchar(100) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `signed_doc_info`
--

CREATE TABLE `signed_doc_info` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(250) DEFAULT NULL,
  `doc_name` varchar(255) DEFAULT NULL,
  `sign_type` varchar(255) DEFAULT NULL,
  `signType_relationship` varchar(255) DEFAULT NULL,
  `doc_Count` varchar(255) DEFAULT NULL,
  `req_id` varchar(150) DEFAULT NULL,
  `cus_profile_id` varchar(150) DEFAULT NULL,
  `noc_given` varchar(10) NOT NULL DEFAULT '0',
  `noc_date` varchar(150) DEFAULT NULL,
  `noc_person` varchar(150) DEFAULT NULL,
  `noc_name` varchar(150) DEFAULT NULL,
  `used_status` varchar(10) NOT NULL DEFAULT '0',
  `temp_sts` varchar(10) NOT NULL DEFAULT '0',
  `temp_date` date DEFAULT NULL,
  `temp_person` varchar(150) DEFAULT NULL,
  `temp_purpose` varchar(150) DEFAULT NULL,
  `temp_remarks` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(100) DEFAULT NULL,
  `update_login_id` varchar(100) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `staff_creation`
--

CREATE TABLE `staff_creation` (
  `staff_id` int(11) NOT NULL,
  `staff_code` varchar(255) DEFAULT NULL,
  `staff_name` varchar(255) DEFAULT NULL,
  `staff_type` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `taluk` varchar(255) DEFAULT NULL,
  `place` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `mobile1` varchar(255) DEFAULT NULL,
  `mobile2` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `cug_no` varchar(255) DEFAULT NULL,
  `company_id` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `team` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT '0',
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_type_creation`
--

CREATE TABLE `staff_type_creation` (
  `staff_type_id` int(11) NOT NULL,
  `staff_type_name` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sub_area_list_creation`
--

CREATE TABLE `sub_area_list_creation` (
  `sub_area_id` int(11) NOT NULL,
  `area_id_ref` varchar(255) DEFAULT NULL,
  `sub_area_name` varchar(255) DEFAULT NULL,
  `sub_area_enable` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sub_area_list_creation`
--

INSERT INTO `sub_area_list_creation` (`sub_area_id`, `area_id_ref`, `sub_area_name`, `sub_area_enable`, `status`) VALUES
(1, '1', 'A P Chathiram', 0, '0'),
(2, '1', 'A P Chathiram Colony', 0, '0'),
(3, '2', 'Aalappakkam', 0, '0'),
(4, '3', 'Aalathur', 0, '0'),
(5, '4', 'Aaliyur', 0, '0'),
(6, '5', 'Aariyathur', 0, '0'),
(7, '6', 'Aathurai', 0, '0'),
(8, '7', 'Achamangalam', 0, '0'),
(9, '8', 'Acharapakkam', 0, '0'),
(10, '9', 'Adhavapakkam', 0, '0'),
(11, '9', 'Adhavapakkam Colony', 0, '0'),
(12, '10', 'Adhiyankuppam', 0, '0'),
(13, '11', 'Adhiyanur', 0, '0'),
(14, '12', 'Adukkupasi', 0, '0'),
(15, '13', 'Agarakorakottai', 0, '0'),
(16, '14', 'Agathigapuram', 0, '0'),
(17, '15', 'Akkur', 0, '0'),
(18, '16', 'Alancheri', 0, '0'),
(19, '17', 'Alanthangal', 0, '0'),
(20, '18', 'Alathurai', 0, '0'),
(21, '19', 'Aliyanthal', 0, '0'),
(22, '19', 'Aliyanthal Colony', 0, '0'),
(23, '20', 'Alliputhur', 0, '0'),
(24, '21', 'Alliyalamangalam', 0, '0'),
(25, '22', 'Alwarpoondi', 0, '0'),
(26, '23', 'Amaravathipattinam', 0, '0'),
(27, '24', 'Ammaiyapattu', 0, '0'),
(28, '25', 'Ammaiyappanallur', 0, '0'),
(29, '26', 'Ammanambakkam', 0, '0'),
(30, '27', 'Ammanthangal', 0, '0'),
(31, '28', 'Amudur', 0, '0'),
(32, '29', 'Anadhimangalam', 0, '0'),
(33, '30', 'Anaibogi', 0, '0'),
(34, '31', 'Anaipallam', 0, '0'),
(35, '32', 'Anaipettai', 0, '0'),
(36, '33', 'Anakkavoor', 0, '0'),
(37, '34', 'Anambakkam', 0, '0'),
(38, '35', 'Anapathur', 0, '0'),
(39, '36', 'Andavakkam', 0, '0'),
(40, '37', 'Andithangal', 0, '0'),
(41, '38', 'Aniyalai', 0, '0'),
(42, '39', 'Anmarudhai', 0, '0'),
(43, '40', 'Appaiyanallur', 0, '0'),
(44, '41', 'Appanthangal', 0, '0'),
(45, '42', 'Appedu', 0, '0'),
(46, '42', 'Appedu Colony', 0, '0'),
(47, '43', 'Arasampattu', 0, '0'),
(48, '44', 'Arasanimangalam', 0, '0'),
(49, '44', 'Arasanimangalam colony', 0, '0'),
(50, '45', 'Aarasur', 0, '0'),
(51, '46', 'Arayalam', 0, '0'),
(52, '47', 'Ariyapadi', 0, '0'),
(53, '48', 'Ariyapuravadai', 0, '0'),
(54, '49', 'Ariyathur', 0, '0'),
(55, '50', 'Arkampoondi', 0, '0'),
(56, '51', 'Arokiyapuram', 0, '0'),
(57, '52', 'Arpakkam', 0, '0'),
(58, '53', 'Arulnadu', 0, '0'),
(59, '54', 'Arumbalur', 0, '0'),
(60, '55', 'Arungunam', 0, '0'),
(61, '56', 'Arunkundram', 0, '0'),
(62, '57', 'Aruvadaithangal', 0, '0'),
(63, '58', 'Asthinapuram', 0, '0'),
(64, '59', 'Athipakkam', 0, '0'),
(65, '60', 'Athiyur', 0, '0'),
(66, '60', 'Athiyur Colony', 0, '0'),
(67, '61', 'Athiyur Melduli', 0, '0'),
(68, '61', 'Athiyur Melduli Colony', 0, '0'),
(69, '62', 'Athuvambadi', 0, '0'),
(70, '63', 'Avanavadi', 0, '0'),
(71, '64', 'Avaniyapuram', 0, '0'),
(72, '65', 'Ayalavadi', 0, '0'),
(73, '66', 'Ayyavadi', 0, '0'),
(74, '67', 'Azhisoor', 0, '0'),
(75, '68', 'Badur', 0, '0'),
(76, '69', 'Bharathipuram', 0, '0'),
(77, '70', 'Birudur', 0, '0'),
(78, '71', 'Boothamangalam', 0, '0'),
(79, '72', 'Chandrampadi', 0, '0'),
(80, '73', 'Chennavaram', 0, '0'),
(81, '74', 'Chetpet', 0, '0'),
(82, '75', 'Chettikulam', 0, '0'),
(83, '76', 'Cheyyanandal', 0, '0'),
(84, '77', 'Chinna Andithangal', 0, '0'),
(85, '78', 'Chinna Azhisoor', 0, '0'),
(86, '79', 'Chinna Chetpet', 0, '0'),
(87, '80', 'Chinna Kozhappalur', 0, '0'),
(88, '81', 'Chinna Nolambai', 0, '0'),
(89, '82', 'Chinnakallanthal', 0, '0'),
(90, '83', 'Chinnakalur', 0, '0'),
(91, '84', 'Chinnamakulam', 0, '0'),
(92, '85', 'Chinnathuraiyur', 0, '0'),
(93, '86', 'Chinnavoor', 0, '0'),
(94, '87', 'Chithadurai', 0, '0'),
(95, '88', 'Chithalamangalam', 0, '0'),
(96, '89', 'Chithamoor', 0, '0'),
(97, '90', 'Chithragavoor', 0, '0'),
(98, '91', 'Chokkapallam', 0, '0'),
(99, '92', 'Cholavaram', 0, '0'),
(100, '93', 'CM Pudur', 0, '0'),
(101, '94', 'Desur', 0, '0'),
(102, '94', 'East', 0, '0'),
(103, '95', 'Desur Madam', 0, '0'),
(104, '96', 'Devanoor', 0, '0'),
(105, '97', 'Devanthavadi', 0, '0'),
(106, '98', 'Devigapuram', 0, '0'),
(107, '99', 'Devimangalam', 0, '0'),
(108, '100', 'Duraiyur', 0, '0'),
(109, '101', 'Echankadu', 0, '0'),
(110, '102', 'Echur', 0, '0'),
(111, '103', 'Edaiyankulathur', 0, '0'),
(112, '104', 'Edapattu', 0, '0'),
(113, '105', 'Edapirai', 0, '0'),
(114, '106', 'Edayampudhur', 0, '0'),
(115, '107', 'Elanagar', 0, '0'),
(116, '107', 'Elanagar Colony', 0, '0'),
(117, '108', 'Elaneer Kundram', 0, '0'),
(118, '109', 'Elangadu', 0, '0'),
(119, '110', 'Elapakkam', 0, '0'),
(120, '111', 'Embalam', 0, '0'),
(121, '112', 'Endathur', 0, '0'),
(122, '112', 'Endathur Colony', 0, '0'),
(123, '113', 'Endhal', 0, '0'),
(124, '114', 'Eramalur', 0, '0'),
(125, '115', 'Eravadi', 0, '0'),
(126, '116', 'Erumaivetti', 0, '0'),
(127, '117', 'Erumbur', 0, '0'),
(128, '118', 'Ettikuttai', 0, '0'),
(129, '119', 'Ettithangal', 0, '0'),
(130, '120', 'Eyapakkam', 0, '0'),
(131, '121', 'Eyyil', 0, '0'),
(132, '122', 'Ganapathipuram', 0, '0'),
(133, '123', 'Gandhi Nagar', 0, '0'),
(134, '124', 'Gangaisoodamani', 0, '0'),
(135, '125', 'Ganganallur', 0, '0'),
(136, '126', 'Gangapuram', 0, '0'),
(137, '126', 'Merku Mada Veethi', 0, '0'),
(138, '127', 'Gnanothayam', 0, '0'),
(139, '128', 'Godhandapuram', 0, '0'),
(140, '129', 'Goodalur', 0, '0'),
(141, '130', 'Goonambadi', 0, '0'),
(142, '131', 'Guduvampoondi', 0, '0'),
(143, '132', 'Gurukulanthangal', 0, '0'),
(144, '133', 'Hanumanthandalam', 0, '0'),
(145, '134', 'Immapuram', 0, '0'),
(146, '135', 'Indhiravanam', 0, '0'),
(147, '136', 'Injimedu', 0, '0'),
(148, '137', 'Irumaram', 0, '0'),
(149, '138', 'Irumbedu', 0, '0'),
(150, '139', 'Irumbuli', 0, '0'),
(151, '140', 'Isakolathur', 0, '0'),
(152, '141', 'Jambambattu', 0, '0'),
(153, '142', 'Japthikarani', 0, '0'),
(154, '143', 'Jengampoondi', 0, '0'),
(155, '144', 'Kacherimangalam', 0, '0'),
(156, '145', 'Kadaisikulam', 0, '0'),
(157, '146', 'Kadalmangalam', 0, '0'),
(158, '147', 'Kadambai', 0, '0'),
(159, '148', 'Kadambur', 0, '0'),
(160, '149', 'Kaithandalam', 0, '0'),
(161, '149', 'Kaithandalam colony', 0, '0'),
(162, '150', 'Kaividanthangal', 0, '0'),
(163, '151', 'Kakanallur', 0, '0'),
(164, '152', 'Kalambur', 0, '0'),
(165, '153', 'Kalayanapuram', 0, '0'),
(166, '154', 'Kaligapuram', 0, '0'),
(167, '155', 'Kaliyampoondi', 0, '0'),
(168, '156', 'Kallama Nagar', 0, '0'),
(169, '157', 'Kallankuthu', 0, '0'),
(170, '158', 'Kallapuliyur', 0, '0'),
(171, '159', 'Kallukollaimedu', 0, '0'),
(172, '160', 'Kalpattu', 0, '0'),
(173, '161', 'Kalyanamedu', 0, '0'),
(174, '162', 'Kamalampoondi', 0, '0'),
(175, '162', 'Kizh Colony', 0, '0'),
(176, '162', 'Mettu Colony', 0, '0'),
(177, '162', 'Bothavan Poradai', 0, '0'),
(178, '163', 'Kamalaputhur', 0, '0'),
(179, '164', 'Kamatchipuram', 0, '0'),
(180, '165', 'Kambattu', 0, '0'),
(181, '166', 'Kandaiyanallur', 0, '0'),
(182, '167', 'Kandamanallur', 0, '0'),
(183, '168', 'Kandavaratti', 0, '0'),
(184, '169', 'Kangeyanoor', 0, '0'),
(185, '170', 'Kannalam', 0, '0'),
(186, '171', 'kannanur', 0, '0'),
(187, '172', 'Kannigapuram', 0, '0'),
(188, '173', 'Kannikulam', 0, '0'),
(189, '174', 'Kappalambadi', 0, '0'),
(190, '175', 'Kappalur', 0, '0'),
(191, '176', 'Karadikuppam', 0, '0'),
(192, '177', 'Karaipoondi', 0, '0'),
(193, '178', 'Karaiyambadi', 0, '0'),
(194, '179', 'Karam', 0, '0'),
(195, '180', 'Karanai', 0, '0'),
(196, '181', 'Karanai mandapam', 0, '0'),
(197, '182', 'Karikathur', 0, '0'),
(198, '183', 'Karikili', 0, '0'),
(199, '184', 'Karipoor', 0, '0'),
(200, '185', 'Kariyamangalam', 0, '0'),
(201, '186', 'Karukalikuppam', 0, '0'),
(202, '187', 'Karungalmedu', 0, '0'),
(203, '188', 'Karuveppampoondi', 0, '0'),
(204, '189', 'Kasakal', 0, '0'),
(205, '190', 'Kasthambadi', 0, '0'),
(206, '191', 'Kattangulam', 0, '0'),
(207, '192', 'Kattiyampanthal', 0, '0'),
(208, '193', 'Kattu Thellur', 0, '0'),
(209, '194', 'Kattukaranai', 0, '0'),
(210, '195', 'Kattukollai', 0, '0'),
(211, '196', 'Kattukudisai', 0, '0'),
(212, '197', 'Kattupakkam', 0, '0'),
(213, '197', 'Kattupakkam Colony', 0, '0'),
(214, '198', 'Kattuputhur', 0, '0'),
(215, '199', 'Kavampayir', 0, '0'),
(216, '200', 'Kavaniyathur', 0, '0'),
(217, '201', 'Kavanthandalam', 0, '0'),
(218, '202', 'Kavanur Pudhucherry', 0, '0'),
(219, '203', 'Kavedu', 0, '0'),
(220, '204', 'Kaveripakkam', 0, '0'),
(221, '205', 'Kayanallur', 0, '0'),
(222, '206', 'Kelur', 0, '0'),
(223, '207', 'Kil Kodungalur', 0, '0'),
(224, '208', 'Kiliya Nagar', 0, '0'),
(225, '209', 'Kilkovalaivedu', 0, '0'),
(226, '210', 'Kilnamandi', 0, '0'),
(227, '211', 'Kilnarma', 0, '0'),
(228, '212', 'Kilpadiri', 0, '0'),
(229, '213', 'Kilpakkam', 0, '0'),
(230, '214', 'Kilputhur', 0, '0'),
(231, '215', 'Kilseesamangalam', 0, '0'),
(232, '216', 'Kilvillivalam', 0, '0'),
(233, '217', 'Kilvilliyur', 0, '0'),
(234, '218', 'Kinnanur', 0, '0'),
(235, '219', 'Kizh Nanthiyampadi', 0, '0'),
(236, '220', 'Kizh Pattu', 0, '0'),
(237, '221', 'Kizh Sathamangalam', 0, '0'),
(238, '222', 'Kizh Sevalambadi', 0, '0'),
(239, '223', 'Kizhakkumedu', 0, '0'),
(240, '224', 'Kizhamur', 0, '0'),
(241, '225', 'KIzhangunam', 0, '0'),
(242, '226', 'Kizhavampoondi', 0, '0'),
(243, '227', 'Kizhmaruvathoor', 0, '0'),
(244, '228', 'Kizhmedu', 0, '0'),
(245, '229', 'Kizhputhur', 0, '0'),
(246, '230', 'Kodanallur', 0, '0'),
(247, '231', 'Kodiyalam', 0, '0'),
(248, '232', 'Kodukkanthangal', 0, '0'),
(249, '233', 'Kolakaraivadi', 0, '0'),
(250, '234', 'Kolathur', 0, '0'),
(251, '235', 'Konamangalam', 0, '0'),
(252, '236', 'Kondaiyankuppam', 0, '0'),
(253, '237', 'Koothampattu', 0, '0'),
(254, '238', 'Koothavedu', 0, '0'),
(255, '239', 'Korakottai', 0, '0'),
(256, '240', 'Koralpakkam', 0, '0'),
(257, '241', 'Korasalavadi', 0, '0'),
(258, '242', 'Korukkanthangal', 0, '0'),
(259, '243', 'Kosapattu', 0, '0'),
(260, '244', 'Kothandapuram', 0, '0'),
(261, '245', 'Kothandavadi', 0, '0'),
(262, '246', 'Kottai', 0, '0'),
(263, '247', 'Kottai Colony', 0, '0'),
(264, '248', 'Kottaipoondi', 0, '0'),
(265, '249', 'Kottuppakkam', 0, '0'),
(266, '250', 'Kovalai', 0, '0'),
(267, '251', 'Kovanandhal', 0, '0'),
(268, '252', 'Kovilpuraiyur', 0, '0'),
(269, '253', 'Kovilur', 0, '0'),
(270, '254', 'Kozhappalur', 0, '0'),
(271, '255', 'Kozhiyalam', 0, '0'),
(272, '256', 'Krishnapuram', 0, '0'),
(273, '257', 'Kuduvampoondi', 0, '0'),
(274, '258', 'Kulathumedu', 0, '0'),
(275, '259', 'Kunagampoondi', 0, '0'),
(276, '260', 'Kunnadimedu', 0, '0'),
(277, '261', 'Kunnathur', 0, '0'),
(278, '262', 'Kunnavakkam', 0, '0'),
(279, '263', 'Kuppaiyanallur', 0, '0'),
(280, '264', 'Kuppam', 0, '0'),
(281, '265', 'Kurukulathangal', 0, '0'),
(282, '266', 'Kurumbur', 0, '0'),
(283, '266', 'Kattukudisai', 0, '0'),
(284, '267', 'Kuruvimalai', 0, '0'),
(285, '267', 'Gandhi Nagar', 0, '0'),
(286, '268', 'L. Endathur', 0, '0'),
(287, '268', 'L. Endathur Colony', 0, '0'),
(288, '269', 'Ladakaranai', 0, '0'),
(289, '270', 'Lakshmipuram', 0, '0'),
(290, '271', 'M Kandigai', 0, '0'),
(291, '272', 'M N Puthiya palayam', 0, '0'),
(292, '273', 'm.c raja nagar', 0, '0'),
(293, '274', 'Maambattu VSI', 0, '0'),
(294, '275', 'Madam', 0, '0'),
(295, '276', 'Madhura nemilipattu', 0, '0'),
(296, '277', 'Madhurai', 0, '0'),
(297, '278', 'Magaral', 0, '0'),
(298, '279', 'Mahadevimangalam', 0, '0'),
(299, '280', 'Malaivaiyavur', 0, '0'),
(300, '281', 'Malaiyampuravadai', 0, '0'),
(301, '282', 'Malaiyangulam', 0, '0'),
(302, '282', 'Malaiyangulam Colony', 0, '0'),
(303, '283', 'Malaiyankuppam', 0, '0'),
(304, '284', 'Malaiyittan Kuppam', 0, '0'),
(305, '285', 'Malikaipattu', 0, '0'),
(306, '286', 'Malligapuram', 0, '0'),
(307, '287', 'Malliyangaranai', 0, '0'),
(308, '288', 'Mamandur', 0, '0'),
(309, '289', 'Manalmedu', 0, '0'),
(310, '290', 'Manampathy', 0, '0'),
(311, '290', 'Kandigai', 0, '0'),
(312, '291', 'Mandakolathur', 0, '0'),
(313, '292', 'Mangala Mamandur', 0, '0'),
(314, '293', 'Mangalam', 0, '0'),
(315, '294', 'Manganallur', 0, '0'),
(316, '295', 'Manickavalli', 0, '0'),
(317, '296', 'Manimangalam', 0, '0'),
(318, '297', 'Manipuram', 0, '0'),
(319, '298', 'Manithottam', 0, '0'),
(320, '299', 'Maniyanthapathu', 0, '0'),
(321, '300', 'Manjapattu', 0, '0'),
(322, '301', 'Mannan Kudesai', 0, '0'),
(323, '302', 'Mansurabad', 0, '0'),
(324, '303', 'Maraikaniyanur', 0, '0'),
(325, '304', 'Marakkonam', 0, '0'),
(326, '305', 'Marudhadu', 0, '0'),
(327, '306', 'Marudham', 0, '0'),
(328, '306', 'Marudham Colony', 0, '0'),
(329, '307', 'Maruthuvambadi', 0, '0'),
(330, '308', 'Mathippankulam', 0, '0'),
(331, '309', 'Mathuraperambattur', 0, '0'),
(332, '310', 'Mattaparaiyur', 0, '0'),
(333, '311', 'Mavalavadi', 0, '0'),
(334, '312', 'Mazhaiyur', 0, '0'),
(335, '313', 'Mazhuvangaranai', 0, '0'),
(336, '314', 'Meesanallur', 0, '0'),
(337, '315', 'Mel Kodungalur', 0, '0'),
(338, '316', 'Mel Nandhiyambadi', 0, '0'),
(339, '317', 'Mel Nemili', 0, '0'),
(340, '318', 'Mel sevalambadi', 0, '0'),
(341, '319', 'Mel Villvarayanallur', 0, '0'),
(342, '320', 'Mel vilvarani', 0, '0'),
(343, '321', 'Melacheri', 0, '0'),
(344, '322', 'Melapoondi', 0, '0'),
(345, '323', 'Melathangal', 0, '0'),
(346, '324', 'Melduli', 0, '0'),
(347, '325', 'Melkaranai', 0, '0'),
(348, '326', 'Melma', 0, '0'),
(349, '327', 'Melmalaiyanur', 0, '0'),
(350, '328', 'Melnarma', 0, '0'),
(351, '329', 'Melnemili', 0, '0'),
(352, '330', 'Melpadhiri', 0, '0'),
(353, '331', 'Melpakkam', 0, '0'),
(354, '332', 'Melpathi', 0, '0'),
(355, '333', 'Melsathamangalam', 0, '0'),
(356, '334', 'Melthandarai', 0, '0'),
(357, '335', 'Melvayalamur', 0, '0'),
(358, '336', 'Melvillivalam', 0, '0'),
(359, '337', 'Menalur', 0, '0'),
(360, '337', 'Koot road', 0, '0'),
(361, '338', 'Meppathurai', 0, '0'),
(362, '339', 'Mettuvayalamoor', 0, '0'),
(363, '340', 'Mettu Echur', 0, '0'),
(364, '341', 'Mettupalayam', 0, '0'),
(365, '342', 'Modaiyur', 0, '0'),
(366, '343', 'Modipattu', 0, '0'),
(367, '344', 'Molaipattu', 0, '0'),
(368, '345', 'Moodur', 0, '0'),
(369, '346', 'Moongilthangal', 0, '0'),
(370, '347', 'Moraikaniyanur', 0, '0'),
(371, '348', 'Mosavadi', 0, '0'),
(372, '349', 'Mulapattu', 0, '0'),
(373, '350', 'Mummuni', 0, '0'),
(374, '350', 'Old Mummuni', 0, '0'),
(375, '351', 'Murugamangalam', 0, '0'),
(376, '352', 'Muruganthangal', 0, '0'),
(377, '353', 'Murukkeri', 0, '0'),
(378, '354', 'N.S Puram', 0, '0'),
(379, '355', 'Nachiyapuram', 0, '0'),
(380, '356', 'Nadukuppam', 0, '0'),
(381, '357', 'Nadupattu', 0, '0'),
(382, '358', 'Nagamedu', 0, '0'),
(383, '359', 'Nainavaram', 0, '0'),
(384, '360', 'Nalladisenai', 0, '0'),
(385, '361', 'Nalleri', 0, '0'),
(386, '362', 'Nallur', 0, '0'),
(387, '362', 'Nallur Colony', 0, '0'),
(388, '363', 'Namathodu', 0, '0'),
(389, '364', 'Nambedu', 0, '0'),
(390, '365', 'Nandhipuram', 0, '0'),
(391, '365', 'Nandhipuram Colony', 0, '0'),
(392, '366', 'Nangaiyarkulam', 0, '0'),
(393, '367', 'Nanjipuram', 0, '0'),
(394, '367', 'Kattikollai', 0, '0'),
(395, '368', 'Narasingapuram', 0, '0'),
(396, '369', 'Narasingarayan Pettai', 0, '0'),
(397, '370', 'Narayanamangalam', 0, '0'),
(398, '371', 'Narmapallam', 0, '0'),
(399, '372', 'Narthambadi', 0, '0'),
(400, '373', 'Narthampoondi', 0, '0'),
(401, '374', 'Navalpakkam', 0, '0'),
(402, '375', 'Nedungal', 0, '0'),
(403, '376', 'Nedungunam', 0, '0'),
(404, '376', 'New Colony', 0, '0'),
(405, '377', 'Neelam Poondi', 0, '0'),
(406, '378', 'Neeradi', 0, '0'),
(407, '379', 'Nelli', 0, '0'),
(408, '379', 'Nelli Colony', 0, '0'),
(409, '380', 'Nelliyangulam', 0, '0'),
(410, '381', 'Nelveli', 0, '0'),
(411, '382', 'Nelvoy', 0, '0'),
(412, '382', 'Koot road', 0, '0'),
(413, '383', 'Nemili', 0, '0'),
(414, '384', 'Nerkunam', 0, '0'),
(415, '385', 'Nerkundram', 0, '0'),
(416, '386', 'New Vanakkambadi', 0, '0'),
(417, '387', 'Noothambadi', 0, '0'),
(418, '388', 'Odanagaram', 0, '0'),
(419, '389', 'Oddanthangal', 0, '0'),
(420, '390', 'Olagampattu', 0, '0'),
(421, '391', 'Old Gangaisodamani', 0, '0'),
(422, '392', 'Old Kaliyampoondi', 0, '0'),
(423, '393', 'Old Marakonanam', 0, '0'),
(424, '394', 'Old Vinnuvampattu', 0, '0'),
(425, '395', 'Ongoor', 0, '0'),
(426, '396', 'Orathi', 0, '0'),
(427, '397', 'Osur', 0, '0'),
(428, '398', 'Ottanthangal', 0, '0'),
(429, '399', 'Ozhaiyur', 0, '0'),
(430, '399', 'Ozhaiyur Colony', 0, '0'),
(431, '400', 'Ozhapakkam', 0, '0'),
(432, '401', 'Ozhugarai', 0, '0'),
(433, '401', 'Ozhugarai Colony', 0, '0'),
(434, '402', 'P.A.Pettai', 0, '0'),
(435, '403', 'Padhiri', 0, '0'),
(436, '404', 'Padiyam Puthur', 0, '0'),
(437, '405', 'Padoor', 0, '0'),
(438, '406', 'Paiyur', 0, '0'),
(439, '407', 'Palampoondi', 0, '0'),
(440, '408', 'Palankoil', 0, '0'),
(441, '409', 'Palayam', 0, '0'),
(442, '410', 'Palliyagaram', 0, '0'),
(443, '411', 'Pambaiyambattu', 0, '0'),
(444, '412', 'Pancharai', 0, '0'),
(445, '413', 'Pandavakkam', 0, '0'),
(446, '414', 'Pandiyapuram', 0, '0'),
(447, '415', 'Papanthangal', 0, '0'),
(448, '416', 'Pappanallur', 0, '0'),
(449, '417', 'Parikalpattu', 0, '0'),
(450, '418', 'Paruthi Kollai', 0, '0'),
(451, '418', 'Perumal Koil St', 0, '0'),
(452, '419', 'Paruthipuram', 0, '0'),
(453, '420', 'Paruvathampoondi', 0, '0'),
(454, '421', 'Pasuvangaranai', 0, '0'),
(455, '422', 'Pathiyavaram', 0, '0'),
(456, '423', 'Pattancheri', 0, '0'),
(457, '424', 'Pattangulam', 0, '0'),
(458, '425', 'Pazhampet', 0, '0'),
(459, '426', 'Pazhanjur', 0, '0'),
(460, '427', 'Pennalur', 0, '0'),
(461, '428', 'Pennatagaram', 0, '0'),
(462, '429', 'Peranamallur', 0, '0'),
(463, '430', 'Peranambakkam', 0, '0'),
(464, '431', 'Periya Andithangal', 0, '0'),
(465, '432', 'Periya Kozhapalur', 0, '0'),
(466, '433', 'Periya Kuppam', 0, '0'),
(467, '434', 'Periya Nolambai', 0, '0'),
(468, '435', 'Pernambakkam', 0, '0'),
(469, '436', 'Perumbakkam', 0, '0'),
(470, '437', 'Perunagar', 0, '0'),
(471, '437', 'Perunagar Colony', 0, '0'),
(472, '438', 'Perungozhi', 0, '0'),
(473, '439', 'Peruvalur', 0, '0'),
(474, '440', 'Pinnanur', 0, '0'),
(475, '441', 'Pinnathur', 0, '0'),
(476, '442', 'Ponnur', 0, '0'),
(477, '443', 'Poondi', 0, '0'),
(478, '443', 'Poondi Colony', 0, '0'),
(479, '444', 'Poongunam', 0, '0'),
(480, '445', 'Poonthandalam', 0, '0'),
(481, '446', 'Poosari Colony', 0, '0'),
(482, '447', 'Pudur', 0, '0'),
(483, '448', 'Pulivoy', 0, '0'),
(484, '449', 'Puliyur', 0, '0'),
(485, '450', 'Punnai', 0, '0'),
(486, '451', 'Purisai', 0, '0'),
(487, '452', 'Puthali', 0, '0'),
(488, '453', 'Puthupalayam', 0, '0'),
(489, '454', 'Puzhuthivakkam', 0, '0'),
(490, '455', 'R. N. Kandigai', 0, '0'),
(491, '456', 'Raganatha Samuthiram', 0, '0'),
(492, '457', 'Rajampuram', 0, '0'),
(493, '458', 'Ramalingapuram', 0, '0'),
(494, '459', 'Ramapuram', 0, '0'),
(495, '460', 'Ramsamuthiram', 0, '0'),
(496, '461', 'Randham', 0, '0'),
(497, '462', 'Rayananthal', 0, '0'),
(498, '463', 'Reddikollai', 0, '0'),
(499, '464', 'Reddykuppam', 0, '0'),
(500, '465', 'Retta Mangalam', 0, '0'),
(501, '466', 'S Mottur', 0, '0'),
(502, '467', 'S Navalpakkam', 0, '0'),
(503, '468', 'Sadachivakkam', 0, '0'),
(504, '469', 'Sagayapuram', 0, '0'),
(505, '470', 'Salavakkam', 0, '0'),
(506, '471', 'Salavedu', 0, '0'),
(507, '472', 'Salukkai', 0, '0'),
(508, '473', 'Samanthipuram', 0, '0'),
(509, '474', 'Samathuvapuram', 0, '0'),
(510, '475', 'Sanjivarayanpettai', 0, '0'),
(511, '476', 'Sathampadi', 0, '0'),
(512, '477', 'Sathampoondi', 0, '0'),
(513, '478', 'Sathanoor', 0, '0'),
(514, '479', 'sathiyavadi', 0, '0'),
(515, '480', 'Seeyalam', 0, '0'),
(516, '481', 'Seeyamangalam', 0, '0'),
(517, '482', 'Sekkadikuppam', 0, '0'),
(518, '483', 'Sembur', 0, '0'),
(519, '484', 'Semmaiyamangalam', 0, '0'),
(520, '485', 'Semmambadi', 0, '0'),
(521, '486', 'Sempoondi', 0, '0'),
(522, '487', 'Senal', 0, '0'),
(523, '488', 'Senanthal', 0, '0'),
(524, '489', 'Sengenikuppam', 0, '0'),
(525, '490', 'Septangulam', 0, '0'),
(526, '491', 'Serpakkam', 0, '0'),
(527, '491', 'Serpakkam Colony', 0, '0'),
(528, '492', 'Setharakuppam', 0, '0'),
(529, '493', 'Sevarampoondi', 0, '0'),
(530, '494', 'Silambakkam', 0, '0'),
(531, '495', 'Singampoondi', 0, '0'),
(532, '496', 'Singapalli', 0, '0'),
(533, '497', 'Sirunallur', 0, '0'),
(534, '498', 'Sirungozhi', 0, '0'),
(535, '499', 'Sithamalli', 0, '0'),
(536, '500', 'Sivanam', 0, '0'),
(537, '501', 'Sogathur', 0, '0'),
(538, '502', 'Solai Arugavur', 0, '0'),
(539, '503', 'Somanathapuram', 0, '0'),
(540, '504', 'Soraputhur', 0, '0'),
(541, '505', 'Sothupakkam', 0, '0'),
(542, '506', 'Soundariyapuram', 0, '0'),
(543, '507', 'Sozhanur', 0, '0'),
(544, '508', 'Sri Rangarajapuram', 0, '0'),
(545, '509', 'Sunnambumedu', 0, '0'),
(546, '510', 'Suriyakuppam', 0, '0'),
(547, '511', 'Suriyanthangal', 0, '0'),
(548, '512', 'T Mambattu', 0, '0'),
(549, '513', 'T Thangal', 0, '0'),
(550, '514', 'Thachambadi', 0, '0'),
(551, '515', 'Thachampattu', 0, '0'),
(552, '516', 'Thadinolambai', 0, '0'),
(553, '517', 'Thakkandarayapuram', 0, '0'),
(554, '518', 'Thandalam', 0, '0'),
(555, '519', 'Thandarai', 0, '0'),
(556, '519', 'Vada Colony', 0, '0'),
(557, '519', 'Koot road', 0, '0'),
(558, '520', 'Thathanur', 0, '0'),
(559, '521', 'Thattampoondi', 0, '0'),
(560, '522', 'Thavani', 0, '0'),
(561, '523', 'Thazhampallam', 0, '0'),
(562, '524', 'Thazhuthazhai', 0, '0'),
(563, '525', 'Theetalam', 0, '0'),
(564, '526', 'Thellar', 0, '0'),
(565, '527', 'Thellur', 0, '0'),
(566, '528', 'Thenalapakkam', 0, '0'),
(567, '529', 'Theniluppai', 0, '0'),
(568, '530', 'Thenkalpakkam', 0, '0'),
(569, '531', 'Thenkarai', 0, '0'),
(570, '532', 'Thennagur', 0, '0'),
(571, '533', 'Thennathur', 0, '0'),
(572, '534', 'Thensenthamangalam', 0, '0'),
(573, '535', 'Thenthinnalur', 0, '0'),
(574, '536', 'Thenvanakkampadi', 0, '0'),
(575, '537', 'Theppirampattu', 0, '0'),
(576, '538', 'Therasapuram', 0, '0'),
(577, '539', 'Thethurai', 0, '0'),
(578, '540', 'Theyyar', 0, '0'),
(579, '541', 'Theyyar Madam', 0, '0'),
(580, '542', 'Thinaiyampoondi', 0, '0'),
(581, '543', 'Thirakovil', 0, '0'),
(582, '544', 'Thirumalai', 0, '0'),
(583, '544', 'Old Colony', 0, '0'),
(584, '544', 'Kollaimedu', 0, '0'),
(585, '545', 'Thirumalapadi', 0, '0'),
(586, '546', 'Thirumpoondi', 0, '0'),
(587, '547', 'Thirupulivanam', 0, '0'),
(588, '548', 'Thorappadi', 0, '0'),
(589, '549', 'Thotanaval', 0, '0'),
(590, '550', 'Thozhupedu', 0, '0'),
(591, '551', 'Thukkuvadi', 0, '0'),
(592, '552', 'Thumbur', 0, '0'),
(593, '553', 'Thunaiyambattu', 0, '0'),
(594, '554', 'Udaiyanthangal', 0, '0'),
(595, '555', 'Ulagampattu', 0, '0'),
(596, '556', 'Ulundhai', 0, '0'),
(597, '557', 'Unnamanandal', 0, '0'),
(598, '558', 'Urgudi', 0, '0'),
(599, '559', 'Uthiramerur', 0, '0'),
(600, '560', 'Uthukulam', 0, '0'),
(601, '561', 'Uthur', 0, '0'),
(602, '562', 'V. Pudhur', 0, '0'),
(603, '563', 'Vaadadhavoor', 0, '0'),
(604, '564', 'Vadakkapattu', 0, '0'),
(605, '565', 'Vadanallur', 0, '0'),
(606, '566', 'Vadanangur', 0, '0'),
(607, '567', 'Vadasenthamangalam', 0, '0'),
(608, '568', 'Vadavanakkampadi', 0, '0'),
(609, '569', 'Vadavetti', 0, '0'),
(610, '570', 'Vadugamangalam', 0, '0'),
(611, '571', 'Vaippanai', 0, '0'),
(612, '571', 'Vaippanai Colony', 0, '0'),
(613, '572', 'Valathi', 0, '0'),
(614, '573', 'Valathodu', 0, '0'),
(615, '574', 'Valayaputhur', 0, '0'),
(616, '575', 'Vallam', 0, '0'),
(617, '576', 'Vambalur', 0, '0'),
(618, '577', 'Vandavasi', 0, '0'),
(619, '577', 'Aarumugam St', 0, '0'),
(620, '577', 'Kadhar Janda st', 0, '0'),
(621, '577', 'New colony 3rd cross st', 0, '0'),
(622, '577', 'Kadharmeeran st', 0, '0'),
(623, '577', 'Kalungu Maraikayar St', 0, '0'),
(624, '577', 'Krishtappa Udaiyar St', 0, '0'),
(625, '578', 'Vangaram', 0, '0'),
(626, '579', 'Vayalamoor', 0, '0'),
(627, '580', 'Vayalur', 0, '0'),
(628, '580', 'Koot road', 0, '0'),
(629, '581', 'Vazhur', 0, '0'),
(630, '581', 'Vazhur Agaram', 0, '0'),
(631, '582', 'Vedal', 0, '0'),
(632, '583', 'Vedanthangal', 0, '0'),
(633, '584', 'Vedapalayam', 0, '0'),
(634, '585', 'Vedavakkam', 0, '0'),
(635, '585', 'Vedavakkam Colony', 0, '0'),
(636, '586', 'Veerambakkam', 0, '0'),
(637, '587', 'Veeranamoor', 0, '0'),
(638, '588', 'Veerasambanur', 0, '0'),
(639, '589', 'Velacherry', 0, '0'),
(640, '590', 'Velanthangal', 0, '0'),
(641, '591', 'Veliyambakkam', 0, '0'),
(642, '592', 'Vellaputhur', 0, '0'),
(643, '593', 'Velukkampattu', 0, '0'),
(644, '594', 'Vendivakkam', 0, '0'),
(645, '595', 'Vengaram', 0, '0'),
(646, '596', 'Vengodu', 0, '0'),
(647, '597', 'Vengunam', 0, '0'),
(648, '598', 'Venkacherry', 0, '0'),
(649, '599', 'Venkatapuram', 0, '0'),
(650, '600', 'Venkundram', 0, '0'),
(651, '601', 'Venmandhai', 0, '0'),
(652, '602', 'Vepangaranai', 0, '0'),
(653, '603', 'Veppampattu', 0, '0'),
(654, '604', 'Vilangadu', 0, '0'),
(655, '605', 'VilVarayanallur', 0, '0'),
(656, '606', 'Vinayaganallur', 0, '0'),
(657, '607', 'Vinayagapuram', 0, '0'),
(658, '608', 'Vinnamangalam', 0, '0'),
(659, '609', 'Visamangalam', 0, '0'),
(660, '610', 'visur', 0, '0'),
(661, '610', 'Then Colony', 0, '0'),
(662, '610', 'Vada Colony', 0, '0'),
(663, '611', 'Vizhudhupattu', 0, '0'),
(664, '612', 'Aanaivadi', 0, '0'),
(665, '613', 'Abdullapuram', 0, '0'),
(666, '614', 'Agaram Duli', 0, '0'),
(667, '614', 'Agaram Duli Colony', 0, '0'),
(668, '615', 'Agaramsibbandi', 0, '0'),
(669, '616', 'Alampoondi', 0, '0'),
(670, '617', 'Alangaramangalam', 0, '0'),
(671, '618', 'Ammeri', 0, '0'),
(672, '619', 'Anaivadi', 0, '0'),
(673, '620', 'Andipuravadai', 0, '0'),
(674, '621', 'Arani palayam', 0, '0'),
(675, '622', 'Arunagirimangalam', 0, '0'),
(676, '623', 'Arundhadhiyar Palayam', 0, '0'),
(677, '624', 'Athamangalam Puthur', 0, '0'),
(678, '625', 'Ayapakkam', 0, '0'),
(679, '626', 'Banampattu', 0, '0'),
(680, '627', 'Bangalamedu', 0, '0'),
(681, '628', 'Chennai', 0, '0'),
(682, '628', 'Choolaipallam', 0, '0'),
(683, '628', 'Kattupakkam', 0, '0'),
(684, '629', 'Cheyyar', 0, '0'),
(685, '630', 'Cheyyatraivendran', 0, '0'),
(686, '631', 'Chinna Kozhipuliyur', 0, '0'),
(687, '632', 'Chinna Ozhugarai', 0, '0'),
(688, '633', 'Chinnavada villampakkam', 0, '0'),
(689, '634', 'Chithathur', 0, '0'),
(690, '635', 'Chitheri', 0, '0'),
(691, '636', 'Chithrakulam', 0, '0'),
(692, '637', 'Chittikulam', 0, '0'),
(693, '638', 'Dhadapuram', 0, '0'),
(694, '639', 'Dhanakottipuram', 0, '0'),
(695, '640', 'Dr Abdulkalampuram', 0, '0'),
(696, '641', 'Eathuvambadi', 0, '0'),
(697, '642', 'Echampoondi', 0, '0'),
(698, '643', 'Elluparai', 0, '0'),
(699, '644', 'Emamangalam', 0, '0'),
(700, '645', 'Ernamangalam', 0, '0'),
(701, '646', 'Ethanemilli', 0, '0'),
(702, '647', 'Ethuvaipettai', 0, '0'),
(703, '648', 'Ettivadi', 0, '0'),
(704, '649', 'Ezhadhapattu', 0, '0'),
(705, '650', 'Gangeyanoor', 0, '0'),
(706, '651', 'Gobalapuram', 0, '0'),
(707, '652', 'Gudappakkam', 0, '0'),
(708, '653', 'Kalur', 0, '0'),
(709, '654', 'Kilkolathur', 0, '0'),
(710, '655', 'Kilpudhupakkam', 0, '0'),
(711, '656', 'Kilsembedu', 0, '0'),
(712, '657', 'Kizh Colony', 0, '0'),
(713, '658', 'Kizhneerkundram', 0, '0'),
(714, '659', 'Kizhpotharai', 0, '0'),
(715, '660', 'Kosapalayam', 0, '0'),
(716, '661', 'Ladavaram', 0, '0'),
(717, '661', 'Ladavaram Colony', 0, '0'),
(718, '662', 'Malaiyambattu', 0, '0'),
(719, '663', 'Melpalayam', 0, '0'),
(720, '664', 'Melpazhankoil', 0, '0'),
(721, '665', 'Melpriyampattu', 0, '0'),
(722, '666', 'Melsevur', 0, '0'),
(723, '667', 'Melvanniyanur', 0, '0'),
(724, '668', 'Melvarayanallur', 0, '0'),
(725, '669', 'Mottur', 0, '0'),
(726, '670', 'Mukkurumbai', 0, '0'),
(727, '671', 'Munivanthangal', 0, '0'),
(728, '672', 'Muniyappanthangal', 0, '0'),
(729, '673', 'Muruganthangasspatti', 0, '0'),
(730, '674', 'Murugapadi', 0, '0'),
(731, '675', 'Murungampakkam', 0, '0'),
(732, '676', 'Naidumangalam', 0, '0'),
(733, '677', 'Natchiyapuram', 0, '0'),
(734, '678', 'Nellimedu', 0, '0'),
(735, '679', 'Noonampoondi', 0, '0'),
(736, '680', 'Old Bandamangalam', 0, '0'),
(737, '681', 'Omudi', 0, '0'),
(738, '682', 'Othiyanthangal', 0, '0'),
(739, '683', 'Ottakovil', 0, '0'),
(740, '684', 'Padagam', 0, '0'),
(741, '685', 'Paduvanchery', 0, '0'),
(742, '686', 'Paleshwaram', 0, '0'),
(743, '687', 'Palvarthuvendram', 0, '0'),
(744, '688', 'Papampoondi', 0, '0'),
(745, '689', 'Pappampadi', 0, '0'),
(746, '690', 'Parayanthangal', 0, '0'),
(747, '691', 'Pathiyavadi', 0, '0'),
(748, '692', 'Pazhaiya Ettikuttai', 0, '0'),
(749, '693', 'Pazhaiyur', 0, '0'),
(750, '694', 'Pazhampoondi', 0, '0'),
(751, '695', 'Pazhangamoor', 0, '0'),
(752, '696', 'Pazhathottam', 0, '0'),
(753, '696', 'Pazhathottam Colony', 0, '0'),
(754, '697', 'Periya Kappalur', 0, '0'),
(755, '698', 'Periya korakottai', 0, '0'),
(756, '699', 'Periya Sengadu', 0, '0'),
(757, '700', 'Periyakaram', 0, '0'),
(758, '701', 'Periyakuppam', 0, '0'),
(759, '702', 'Periyampattu Poondi', 0, '0'),
(760, '703', 'Perumalpalayam', 0, '0'),
(761, '704', 'Perunkadaputhur', 0, '0'),
(762, '705', 'Pettai', 0, '0'),
(763, '706', 'Pillur', 0, '0'),
(764, '707', 'Pinna Poondi', 0, '0'),
(765, '708', 'Polur', 0, '0'),
(766, '708', 'Housing Board', 0, '0'),
(767, '709', 'Pongambadi', 0, '0'),
(768, '710', 'Ponnagar', 0, '0'),
(769, '711', 'Ponnankulam', 0, '0'),
(770, '712', 'Poriyur', 0, '0'),
(771, '713', 'Porkunam', 0, '0'),
(772, '714', 'Porpandhal', 0, '0'),
(773, '715', 'Potharai', 0, '0'),
(774, '716', 'Potheri', 0, '0'),
(775, '717', 'Kavanur Pudhuchery', 0, '0'),
(776, '718', 'Pulivananthal', 0, '0'),
(777, '719', 'Puthirambattu', 0, '0'),
(778, '720', 'Puthiya Manjanoor', 0, '0'),
(779, '721', 'Puthu Jayamangalam', 0, '0'),
(780, '722', 'Puthu Marakkoanam', 0, '0'),
(781, '723', 'Puthupettai', 0, '0'),
(782, '724', 'Ravathanallur', 0, '0'),
(783, '725', 'Renderipattu', 0, '0'),
(784, '726', 'Salaiyanoor', 0, '0'),
(785, '727', 'Sanikkavadi', 0, '0'),
(786, '728', 'Sankarapalayam', 0, '0'),
(787, '729', 'Santhanandal', 0, '0'),
(788, '730', 'Sathampattu', 0, '0'),
(789, '731', 'Sathiyapuram', 0, '0'),
(790, '732', 'Sathuperipalayam', 0, '0'),
(791, '733', 'Savarapoondi', 0, '0'),
(792, '734', 'Sedharakuppam', 0, '0'),
(793, '735', 'Seetampattu', 0, '0'),
(794, '736', 'Sengunam', 0, '0'),
(795, '737', 'Sevalambadi', 0, '0'),
(796, '738', 'Seviyarpalayam', 0, '0'),
(797, '739', 'Sirukaveripakkam', 0, '0'),
(798, '740', 'Siruthamur', 0, '0'),
(799, '741', 'Somanatha puthur', 0, '0'),
(800, '742', 'Sorakolathur', 0, '0'),
(801, '743', 'Sorapakkam', 0, '0'),
(802, '744', 'Sothukanni', 0, '0'),
(803, '745', 'South Vangaram', 0, '0'),
(804, '746', 'Su Kateri', 0, '0'),
(805, '747', 'Sunaiyambarai', 0, '0'),
(806, '748', 'T Vanakkambadi', 0, '0'),
(807, '749', 'Tambaram', 0, '0'),
(808, '750', 'Thalampoondi', 0, '0'),
(809, '751', 'Thanakottipuram', 0, '0'),
(810, '752', 'Thasarampattu', 0, '0'),
(811, '753', 'Thatchur', 0, '0'),
(812, '754', 'Thayanur', 0, '0'),
(813, '755', 'Theepanandhal', 0, '0'),
(814, '756', 'Thenkadapanthangal', 0, '0'),
(815, '757', 'Thenmathimangalam', 0, '0'),
(816, '758', 'Thenpallipattu', 0, '0'),
(817, '759', 'Thevandhavadi', 0, '0'),
(818, '760', 'Thirisoor', 0, '0'),
(819, '761', 'Thirumani', 0, '0'),
(820, '762', 'Thirumanithangal', 0, '0'),
(821, '763', 'Thittakudi', 0, '0'),
(822, '764', 'Thurinjikuppam', 0, '0'),
(823, '765', 'Uchikollaimedu', 0, '0'),
(824, '766', 'Ukkamperumbakkam', 0, '0'),
(825, '767', 'Urapakkam', 0, '0'),
(826, '768', 'Vachanoor', 0, '0'),
(827, '769', 'Vadamadhimangalam', 0, '0'),
(828, '770', 'Vadapulithiur', 0, '0'),
(829, '771', 'Vadavilapakkam', 0, '0'),
(830, '772', 'Valakuravanpatti', 0, '0'),
(831, '773', 'Vappanai', 0, '0'),
(832, '774', 'Vasanthapuram', 0, '0'),
(833, '775', 'Vasur', 0, '0'),
(834, '776', 'Vellankuppam', 0, '0'),
(835, '777', 'Velliyur', 0, '0'),
(836, '778', 'Vellore', 0, '0'),
(837, '779', 'Venmani', 0, '0'),
(838, '780', 'Vettavalam', 0, '0'),
(839, '781', 'Vikramanallur', 0, '0'),
(840, '782', 'Vilankuppam', 0, '0'),
(841, '782', 'Vilankuppam Colony', 0, '0'),
(842, '783', 'Villivalam', 0, '0'),
(843, '784', 'Villvarani', 0, '0'),
(844, '785', 'Vinnuvampattu', 0, '0'),
(845, '786', 'Yenthuvambadi', 0, '0'),
(846, '787', 'Periyanahalli', 0, '0'),
(847, '788', 'Kadambar Koil', 0, '0'),
(848, '789', 'Kanagambattu', 0, '0'),
(849, '790', 'Kandigai', 0, '0'),
(850, '791', 'Kanniyapuram', 0, '0'),
(851, '792', 'Kariamangalam', 0, '0'),
(852, '793', 'Karivelpattu', 0, '0'),
(853, '794', 'Karkonam', 0, '0'),
(854, '795', 'Karnambadi', 0, '0'),
(855, '796', 'Kasaipoondi', 0, '0'),
(856, '797', 'Kathamangalam', 0, '0'),
(857, '798', 'Katrampatti', 0, '0'),
(858, '799', 'Kattipoondi', 0, '0'),
(859, '800', 'Keerthampattu', 0, '0'),
(860, '801', 'Kiluvanatham', 0, '0'),
(861, '802', 'Kodandapuram', 0, '0'),
(862, '803', 'Kommananthal', 0, '0'),
(863, '804', 'Kondam', 0, '0'),
(864, '805', 'Kovilkuppam', 0, '0'),
(865, '806', 'Kozhipuliyur', 0, '0'),
(866, '807', 'Kudappakkam', 0, '0'),
(867, '808', 'Kudimithangal', 0, '0'),
(868, '809', 'Kuluvantham', 0, '0'),
(869, '810', 'Kusalpettai', 0, '0'),
(870, '811', 'Pelasur', 0, '0'),
(871, '812', 'Old Samathuvapuram', 0, '0'),
(872, '813', 'Adambakkam', 0, '0'),
(873, '814', 'Badimpetti', 0, '0'),
(874, '815', 'Arisanapoondi', 0, '0'),
(875, '816', 'Illupagunam', 0, '0'),
(876, '817', 'Jaganathapuram', 0, '0'),
(877, '818', 'Kalasapakkam', 0, '0'),
(878, '819', 'Guruvaadi', 0, '0'),
(879, '820', 'Kalvasal', 0, '0'),
(880, '821', 'Magajanapakkam', 0, '0'),
(881, '822', 'Mallapanayakkam palayam', 0, '0'),
(882, '823', 'Manapakkam', 0, '0'),
(883, '824', 'Mangaranai', 0, '0'),
(884, '825', 'Manickkamangalam', 0, '0'),
(885, '826', 'Mattaparai', 0, '0'),
(886, '827', 'Melarani', 0, '0'),
(887, '828', 'Pulimanthangal', 0, '0'),
(888, '829', 'Sthamalli', 0, '0'),
(889, '830', 'Thideer Kuppam', 0, '0'),
(890, '25', 'Ammaiyappanallur colony', 0, '0'),
(891, '130', 'Goonambadi colony', 0, '0'),
(892, '290', 'Manampathy colony', 0, '0'),
(893, '356', 'Nadukuppam colony', 0, '0'),
(894, '382', 'Nelvoy colony', 0, '0'),
(895, '427', 'Pennalur colony', 0, '0'),
(896, '724', 'Ravathanallur Colony', 0, '0'),
(897, '731', 'Sathiyapuram Mashar', 0, '0'),
(898, '549', 'Thotanaval Colony', 0, '0'),
(899, '831', 'Jadathariyakuppam', 0, '0'),
(900, '832', 'KallanKollai', 0, '0'),
(901, '833', 'Kalpakkam', 0, '0'),
(902, '834', 'Kamakurpalayam', 0, '0'),
(903, '835', 'Karunkuzhi', 0, '0'),
(904, '836', 'Kil Karikkathur', 0, '0'),
(905, '837', 'Kilkarikathur', 0, '0'),
(906, '838', 'Kizhkarikathur', 0, '0'),
(907, '839', 'Konaiyur', 0, '0'),
(908, '840', 'Mambattu', 0, '0'),
(909, '841', 'Mannan Kudisai', 0, '0'),
(910, '842', 'Mashar', 0, '0'),
(911, '843', 'Mocherry', 0, '0'),
(912, '844', 'Moranam', 0, '0'),
(913, '845', 'Othalavadi', 0, '0'),
(914, '846', 'Padiyampattu', 0, '0'),
(915, '847', 'Palla Echur', 0, '0'),
(916, '848', 'Pryampattu', 0, '0'),
(917, '849', 'Pulavampadi', 0, '0'),
(918, '850', 'Renugapuram', 0, '0'),
(919, '851', 'Samanthakuppam', 0, '0'),
(920, '852', 'Sanjeeverayapuram', 0, '0'),
(921, '853', 'Satathangal', 0, '0'),
(922, '854', 'Sengaputheri', 0, '0'),
(923, '855', 'Tavanathanallur', 0, '0'),
(924, '856', 'Thalarapadi', 0, '0'),
(925, '857', 'Thunaiyan Kuppam', 0, '0'),
(926, '858', 'Ukkal', 0, '0'),
(927, '859', 'Vellur', 0, '0'),
(928, '860', 'A T Palayam', 0, '0'),
(929, '861', 'Aalapiranthan', 0, '0'),
(930, '862', 'Aanaikunnam', 0, '0'),
(931, '863', 'Aandalpatti', 0, '0'),
(932, '864', 'Aandiyappanur', 0, '0'),
(933, '865', 'Aaryapakkam', 0, '0'),
(934, '866', 'Agaram', 0, '0'),
(935, '867', 'Agatheripattu', 0, '0'),
(936, '868', 'Agathipattu', 0, '0'),
(937, '869', 'Akili', 0, '0'),
(938, '870', 'Alapattaraithangal', 0, '0'),
(939, '871', 'Ammanur', 0, '0'),
(940, '872', 'Chithur', 0, '0'),
(941, '873', 'Andipatti', 0, '0'),
(942, '874', 'Anthoniyar Puram', 0, '0'),
(943, '875', 'Anumandhakuppam', 0, '0'),
(944, '876', 'Aravadai Thangal', 0, '0'),
(945, '877', 'Arayapakkam', 0, '0'),
(946, '878', 'Ariyampoondi', 0, '0'),
(947, '879', 'Arukavur', 0, '0'),
(948, '880', 'arumpakkam', 0, '0'),
(949, '881', 'Arunthodu', 0, '0'),
(950, '882', 'Athimanam', 0, '0'),
(951, '883', 'Avalurpet', 0, '0'),
(952, '884', 'Ayakunnam', 0, '0'),
(953, '885', 'Azhividaithangi', 0, '0'),
(954, '886', 'Bang Kolathoor', 0, '0'),
(955, '887', 'Belagampoondi', 0, '0'),
(956, '888', 'Bhagavathapuram', 0, '0'),
(957, '889', 'Boothur', 0, '0'),
(958, '890', 'Chekkadikuppam', 0, '0'),
(959, '891', 'Chengalpattu Periya Nagar', 0, '0'),
(960, '74', 'Arokiyanadhar St', 0, '0'),
(961, '74', 'Arulappar St', 0, '0'),
(962, '74', 'Arundhathi Nagar', 0, '0'),
(963, '74', 'Azhagiri St', 0, '0'),
(964, '74', 'Bazar St', 0, '0'),
(965, '74', 'Bright St', 0, '0'),
(966, '74', 'Dharmaraja Koil St', 0, '0'),
(967, '74', 'German hospital Opposite', 0, '0'),
(968, '74', 'Gingee Main Rd', 0, '0'),
(969, '74', 'Govindhan St', 0, '0'),
(970, '74', 'Guindy Venkatasamy Naidu St', 0, '0'),
(971, '74', 'Kadai St', 0, '0'),
(972, '74', 'Kosa St', 0, '0'),
(973, '74', 'Kottai Mettu St', 0, '0'),
(974, '74', 'Loordhu Nagar', 0, '0'),
(975, '74', 'Maariyamman Koil St', 0, '0'),
(976, '74', 'Mettu St', 0, '0'),
(977, '74', 'Nirmal Nagar', 0, '0'),
(978, '74', 'Pillayar Koil St', 0, '0'),
(979, '74', 'Pop Andavar St', 0, '0'),
(980, '74', 'Rajaji St', 0, '0'),
(981, '74', 'School St', 0, '0'),
(982, '74', 'Subedhar St', 0, '0'),
(983, '74', 'Tindivanam Rd', 0, '0'),
(984, '74', 'Vengatachari St', 0, '0'),
(985, '74', 'VOC St', 0, '0'),
(986, '74', 'Xavier St', 0, '0'),
(987, '892', 'cheyyur', 0, '0'),
(988, '893', 'Chinna Athiyur', 0, '0'),
(989, '894', 'chinna elachery', 0, '0'),
(990, '895', 'Chinna Kalakadi', 0, '0'),
(991, '896', 'Chinna Kayapakam', 0, '0'),
(992, '897', 'Chinna Narasampettai', 0, '0'),
(993, '898', 'Chinna Sathambadi', 0, '0'),
(994, '899', 'Chinnagaram', 0, '0'),
(995, '900', 'Chinnalambadi', 0, '0'),
(996, '901', 'Chinnalkalani', 0, '0'),
(997, '902', 'Chinnapuram', 0, '0'),
(998, '903', 'Chithi vinayagapuram', 0, '0'),
(999, '904', 'Chithiraikoodam', 0, '0'),
(1000, '905', 'Chithirakudam', 0, '0'),
(1001, '906', 'Chithiravadi', 0, '0'),
(1002, '907', 'Chithlamangalam', 0, '0'),
(1003, '908', 'Chrompet', 0, '0'),
(1004, '909', 'Citalapakkam', 0, '0'),
(1005, '910', 'Devadur', 0, '0'),
(1006, '911', 'Dhimmapuram', 0, '0'),
(1007, '912', 'Edaiyanthangal', 0, '0'),
(1008, '913', 'Edayalam', 0, '0'),
(1009, '914', 'Edayankulam', 0, '0'),
(1010, '915', 'Enathavadi', 0, '0'),
(1011, '916', 'Eraiyur', 0, '0'),
(1012, '917', 'Eripattu', 0, '0'),
(1013, '918', 'Erumboondi', 0, '0'),
(1014, '919', 'Erusamanallur', 0, '0'),
(1015, '920', 'Esur', 0, '0'),
(1016, '921', 'Ganesapuram', 0, '0'),
(1017, '922', 'Gangampoondi', 0, '0'),
(1018, '923', 'Gayanallur', 0, '0'),
(1019, '924', 'Gidangal', 0, '0'),
(1020, '925', 'Gidangarai', 0, '0'),
(1021, '926', 'Gothandavai', 0, '0'),
(1022, '927', 'Guduvancherry', 0, '0'),
(1023, '928', 'Gunankaranai', 0, '0'),
(1024, '929', 'Gurukulam', 0, '0'),
(1025, '930', 'Ilathur', 0, '0'),
(1026, '931', 'Illedu', 0, '0'),
(1027, '932', 'Indhaloor', 0, '0'),
(1028, '933', 'Indhirapuram', 0, '0'),
(1029, '934', 'Indhiresan Kuppam', 0, '0'),
(1030, '935', 'Irusamanallur', 0, '0'),
(1031, '936', 'Jadatharikuppam', 0, '0'),
(1032, '937', 'Jamin Endathur', 0, '0'),
(1033, '938', 'Janakipuram', 0, '0'),
(1034, '939', 'Jothimanagar', 0, '0'),
(1035, '940', 'Kadali', 0, '0'),
(1036, '941', 'Kadamalaiputhur', 0, '0'),
(1037, '942', 'Kadaperi', 0, '0'),
(1038, '943', 'Kadappanthangal', 0, '0'),
(1039, '944', 'Kaduganur', 0, '0'),
(1040, '945', 'Kadugupattu', 0, '0'),
(1041, '946', 'Kadukalur', 0, '0'),
(1042, '947', 'Kalarpalayam', 0, '0'),
(1043, '948', 'Kallakollai', 0, '0'),
(1044, '949', 'Kallakurichi', 0, '0'),
(1045, '950', 'Kallanbiranpuram', 0, '0'),
(1046, '951', 'Kalleri', 0, '0'),
(1047, '952', 'Kalyanpuram', 0, '0'),
(1048, '953', 'Kammanthangal', 0, '0'),
(1049, '954', 'Kamsalapuram', 0, '0'),
(1050, '955', 'Kanagampattu', 0, '0'),
(1051, '956', 'KandiayanKuppam', 0, '0'),
(1052, '957', 'Kannimangalam', 0, '0'),
(1053, '958', 'Kanniyamangalam', 0, '0'),
(1054, '959', 'Karanagarachery', 0, '0'),
(1055, '960', 'KaranthaPattu', 0, '0'),
(1056, '961', 'karapoondi', 0, '0'),
(1057, '962', 'Karikanthangal', 0, '0'),
(1058, '963', 'Kariyathur', 0, '0'),
(1059, '964', 'Karukanthangal', 0, '0'),
(1060, '965', 'Karunagarcherry', 0, '0'),
(1061, '966', 'Karungaraivilagam', 0, '0'),
(1062, '967', 'Karunguzhi', 0, '0'),
(1063, '968', 'Karuvadhamedu', 0, '0'),
(1064, '969', 'Kattamangalam', 0, '0'),
(1065, '970', 'Kattapakam', 0, '0'),
(1066, '971', 'Katteri', 0, '0'),
(1067, '972', 'Kattupuravadai', 0, '0'),
(1068, '973', 'Kavanur', 0, '0'),
(1069, '974', 'Kavathur', 0, '0'),
(1070, '975', 'Kayapakkam', 0, '0'),
(1071, '976', 'Kazhanipakkam', 0, '0'),
(1072, '977', 'Keelakandai', 0, '0'),
(1073, '978', 'Keelavalam', 0, '0'),
(1074, '979', 'Keeralvadi', 0, '0'),
(1075, '980', 'Kilathur', 0, '0'),
(1076, '981', 'Kiliyanur', 0, '0'),
(1077, '982', 'Kilnelli', 0, '0'),
(1078, '983', 'Kilnemili', 0, '0'),
(1079, '984', 'Kilsiviri', 0, '0'),
(1080, '985', 'Kiranthupattu', 0, '0'),
(1081, '986', 'Kizh andai', 0, '0'),
(1082, '987', 'Kizh athivakkam', 0, '0'),
(1083, '988', 'Kizh Malayanur', 0, '0'),
(1084, '989', 'Kizh Puthupakam', 0, '0'),
(1085, '990', 'Kizhathipakam', 0, '0'),
(1086, '991', 'Kizhkandai', 0, '0'),
(1087, '992', 'KK Pudhur', 0, '0'),
(1088, '993', 'Kodaiyur', 0, '0'),
(1089, '994', 'Koilkuppam', 0, '0'),
(1090, '995', 'Kolavedu', 0, '0'),
(1091, '996', 'Kolipuliyur', 0, '0'),
(1092, '997', 'Kollakottai', 0, '0'),
(1093, '998', 'komalapettai', 0, '0'),
(1094, '999', 'Kondaiya nallur', 0, '0'),
(1095, '1000', 'Koodaloor', 0, '0'),
(1096, '1001', 'Koozhamandal', 0, '0'),
(1097, '1001', 'Koozhamanthal Colony', 0, '0'),
(1098, '1002', 'Kottaimangalam', 0, '0'),
(1099, '1003', 'Kovilacherry', 0, '0'),
(1100, '1004', 'kulamandhai', 0, '0'),
(1101, '1005', 'Kundalampattu', 0, '0'),
(1102, '1006', 'Kundiaynthandalam', 0, '0'),
(1103, '1007', 'Kuripedu', 0, '0'),
(1104, '1008', 'kurunagar', 0, '0'),
(1105, '1009', 'Kuthampattu', 0, '0'),
(1106, '1010', 'Kuzhamanthal', 0, '0'),
(1107, '1011', 'Maambakam', 0, '0'),
(1108, '1012', 'Madavilagam', 0, '0'),
(1109, '1013', 'Madayambakkam', 0, '0'),
(1110, '1014', 'madhukarai', 0, '0'),
(1111, '1015', 'Madhuraputhur', 0, '0'),
(1112, '1016', 'Madurantakam', 0, '0'),
(1113, '1016', 'GST Road', 0, '0'),
(1114, '1016', 'Housing Board', 0, '0'),
(1115, '1016', 'Kamban Nagar', 0, '0'),
(1116, '1017', 'maikal Puram', 0, '0'),
(1117, '1018', 'Malai Chathiram', 0, '0'),
(1118, '1019', 'Malai Palayam', 0, '0'),
(1119, '1020', 'malaiKoil', 0, '0'),
(1120, '1021', 'Malaipalayam', 0, '0'),
(1121, '1022', 'Malaiyangaranai', 0, '0'),
(1122, '1023', 'Mallicherry', 0, '0'),
(1123, '1024', 'Manamankollai', 0, '0'),
(1124, '1025', 'Mandagapallam', 0, '0'),
(1125, '1026', 'Mandavelli', 0, '0'),
(1126, '1027', 'Manjanur', 0, '0'),
(1127, '1028', 'Mannur', 0, '0'),
(1128, '1029', 'Manthopu', 0, '0'),
(1129, '1030', 'Marangal', 0, '0'),
(1130, '1031', 'Marapakkam', 0, '0'),
(1131, '1032', 'Mariputhur', 0, '0'),
(1132, '1033', 'Mathangal', 0, '0'),
(1133, '1034', 'Mathur', 0, '0'),
(1134, '1035', 'Mayanur', 0, '0'),
(1135, '1036', 'Meiyur', 0, '0'),
(1136, '1037', 'Meiyurodai', 0, '0'),
(1137, '1038', 'Mel Athipakam', 0, '0'),
(1138, '1039', 'Mel Karanai', 0, '0'),
(1139, '1040', 'Mel Puthupakkam', 0, '0'),
(1140, '1041', 'Mel Vasalai', 0, '0'),
(1141, '1042', 'Melakandai', 0, '0'),
(1142, '1043', 'melathopu', 0, '0'),
(1143, '1044', 'Melavalam', 0, '0'),
(1144, '1045', 'Melavalampettai', 0, '0'),
(1145, '1046', 'Melmaruvathur', 0, '0'),
(1146, '1046', 'Adigalar St', 0, '0'),
(1147, '1047', 'Melpattu', 0, '0'),
(1148, '1048', 'Melperadikuppam', 0, '0'),
(1149, '1049', 'Melpernamalur', 0, '0'),
(1150, '1050', 'Melsembedu', 0, '0'),
(1151, '1051', 'Melvalavampettai', 0, '0'),
(1152, '1052', 'Melvasalai', 0, '0'),
(1153, '1053', 'Mettu Kollai', 0, '0'),
(1154, '1054', 'Mettukudisai', 0, '0'),
(1155, '1055', 'Mettukuppam', 0, '0'),
(1156, '1056', 'Minnal sithamur', 0, '0'),
(1157, '1057', 'Mogalvadi', 0, '0'),
(1158, '1058', 'Mohanapalayam', 0, '0'),
(1159, '1059', 'Moosivakkam', 0, '0'),
(1160, '1060', 'Morapakkam', 0, '0'),
(1161, '1061', 'Mudur', 0, '0'),
(1162, '1062', 'Mukkur', 0, '0'),
(1163, '1063', 'Mungil Mandabam', 0, '0'),
(1164, '1064', 'Munuthikuppam', 0, '0'),
(1165, '1065', 'Nachupettai', 0, '0'),
(1166, '1066', 'Nadayampakkam', 0, '0'),
(1167, '1067', 'Nagaram', 0, '0'),
(1168, '1068', 'Nainarkuppam', 0, '0'),
(1169, '1069', 'Nandhamangalam', 0, '0'),
(1170, '1070', 'Nandhiyambadi', 0, '0'),
(1171, '1071', 'Nangalathur', 0, '0'),
(1172, '1072', 'Narasapakkam', 0, '0'),
(1173, '1073', 'Nathapettai', 0, '0'),
(1174, '1074', 'Nayakampuravadai', 0, '0'),
(1175, '1075', 'Nedumpirai', 0, '0'),
(1176, '1076', 'Neer Payir', 0, '0'),
(1177, '1077', 'Nesal', 0, '0'),
(1178, '1078', 'Nettupalayam', 0, '0'),
(1179, '1079', 'New Mambakam', 0, '0'),
(1180, '1080', 'New Mangalam', 0, '0'),
(1181, '1081', 'Neyyadupakkam', 0, '0'),
(1182, '1082', 'Nochalur', 0, '0'),
(1183, '1083', 'Old Edayalam', 0, '0'),
(1184, '1084', 'Old Maambakkam', 0, '0'),
(1185, '1085', 'Old seevaram', 0, '0'),
(1186, '1086', 'onambakkam', 0, '0'),
(1187, '1087', 'Oothur', 0, '0'),
(1188, '1088', 'Orathur', 0, '0'),
(1189, '1088', 'Orathur Vadaku Colony', 0, '0'),
(1190, '1089', 'Ottankudisai', 0, '0'),
(1191, '1090', 'Paakam', 0, '0'),
(1192, '1090', 'Paakam Colony', 0, '0'),
(1193, '1091', 'Paalayam', 0, '0'),
(1194, '1092', 'Padithangal', 0, '0'),
(1195, '1093', 'Pagavadhapuram', 0, '0'),
(1196, '1094', 'Paiyampadi', 0, '0'),
(1197, '1095', 'Paiyampettai', 0, '0'),
(1198, '1096', 'Palaiyur', 0, '0'),
(1199, '1097', 'palayar Madam', 0, '0'),
(1200, '1098', 'Pallikaranai', 0, '0'),
(1201, '1099', 'Pallipettai', 0, '0'),
(1202, '1099', 'Pallipettai Colony', 0, '0'),
(1203, '1100', 'Pandhamangalam', 0, '0'),
(1204, '1101', 'Panjalapuram', 0, '0'),
(1205, '1102', 'Parasanallur', 0, '0'),
(1206, '1103', 'Parasur', 0, '0'),
(1207, '1104', 'Parukkal', 0, '0'),
(1208, '1104', 'Parukkal Colony', 0, '0'),
(1209, '1105', 'Pasathan', 0, '0'),
(1210, '1106', 'Pasumbur', 0, '0'),
(1211, '1107', 'Pavunchoor', 0, '0'),
(1212, '1108', 'Pazhavanthangal', 0, '0'),
(1213, '1109', 'Pekkarani', 0, '0'),
(1214, '1110', 'Perampakam', 0, '0'),
(1215, '1111', 'Perani', 0, '0'),
(1216, '1112', 'Peravalur', 0, '0'),
(1217, '1113', 'Periya Elavanthangal', 0, '0'),
(1218, '1114', 'Periya Kalathumedu', 0, '0'),
(1219, '1115', 'Periya Kayapakkam', 0, '0'),
(1220, '1116', 'Periya Narasampettai', 0, '0'),
(1221, '1117', 'Periya Vaiyavur', 0, '0'),
(1222, '1118', 'Perpanangadu', 0, '0'),
(1223, '1119', 'Perukaranai', 0, '0'),
(1224, '1120', 'Perumanthangal', 0, '0'),
(1225, '1121', 'Perumpalai', 0, '0'),
(1226, '1122', 'Perumper Kandigai', 0, '0'),
(1227, '1123', 'Perungalathur', 0, '0'),
(1228, '1124', 'Perunkaranai', 0, '0'),
(1229, '1125', 'Pilanjimedu', 0, '0'),
(1230, '1126', 'Pillaiyar Palayam', 0, '0'),
(1231, '1127', 'Pillayar Odai', 0, '0'),
(1232, '1128', 'Polambakkam', 0, '0'),
(1233, '1129', 'Poragal', 0, '0'),
(1234, '1130', 'Poraiyur', 0, '0'),
(1235, '1131', 'Porur', 0, '0'),
(1236, '1132', 'Pothamporadai', 0, '0'),
(1237, '1133', 'Pozhachalur', 0, '0'),
(1238, '1134', 'Pukkathurai', 0, '0'),
(1239, '1135', 'Pulavanpadi', 0, '0'),
(1240, '1136', 'Pulikoradu', 0, '0'),
(1241, '1137', 'Pulipakkam', 0, '0'),
(1242, '1138', 'Pulivalam', 0, '0'),
(1243, '1139', 'Puliyani', 0, '0'),
(1244, '1140', 'Puliyarambakkam', 0, '0'),
(1245, '1141', 'Puliyarankottai', 0, '0'),
(1246, '1142', 'Punagambadi', 0, '0'),
(1247, '1143', 'Purushothapoondi', 0, '0'),
(1248, '1144', 'Puthamangalam', 0, '0'),
(1249, '1145', 'Puthiran pettai', 0, '0'),
(1250, '1146', 'Puthu Manapakkam', 0, '0'),
(1251, '1147', 'Puthu Pandamangalam', 0, '0'),
(1252, '1148', 'Ragunadha Samuthiram', 0, '0'),
(1253, '1149', 'Ragunadhapuram', 0, '0'),
(1254, '1150', 'Rajapalayam', 0, '0'),
(1255, '1151', 'Ramanathapuram', 0, '0'),
(1256, '1152', 'Rangarajapuram', 0, '0'),
(1257, '1153', 'Ranipet', 0, '0'),
(1258, '1154', 'Rayapuram', 0, '0'),
(1259, '1155', 'Reddy Palayam', 0, '0'),
(1260, '1156', 'S Kalpattu', 0, '0'),
(1261, '1157', 'S R Pettai', 0, '0'),
(1262, '1158', 'Sadagam', 0, '0'),
(1263, '1159', 'Saidapet', 0, '0'),
(1264, '1160', 'Sakaranthi', 0, '0'),
(1265, '1161', 'Salathangal', 0, '0'),
(1266, '1162', 'Saliyur', 0, '0'),
(1267, '1163', 'Samuthiram', 0, '0'),
(1268, '1164', 'Sandror Thoppu', 0, '0'),
(1269, '1165', 'Sangilikuppam', 0, '0'),
(1270, '1166', 'Santhaimedu', 0, '0'),
(1271, '1167', 'Saram', 0, '0'),
(1272, '1168', 'Saravampakam', 0, '0'),
(1273, '1169', 'Sayaburam', 0, '0'),
(1274, '1170', 'Seethapuram', 0, '0'),
(1275, '1171', 'Sembedu', 0, '0'),
(1276, '1172', 'Sendivakam', 0, '0'),
(1277, '1173', 'Sengadu', 0, '0'),
(1278, '1174', 'Sengundharpettai', 0, '0'),
(1279, '1175', 'Seniyanallur', 0, '0'),
(1280, '1176', 'Senthamangalam', 0, '0'),
(1281, '1177', 'Senthamp', 0, '0'),
(1282, '1178', 'Senthangulam', 0, '0'),
(1283, '1179', 'Sholinganallur', 0, '0'),
(1284, '1180', 'Silavattam', 0, '0'),
(1285, '1181', 'Sindhamani', 0, '0'),
(1286, '1182', 'Singanikuppam', 0, '0'),
(1287, '1183', 'Singanikuppm', 0, '0'),
(1288, '1184', 'Sirumailur', 0, '0'),
(1289, '1185', 'Sirumur', 0, '0'),
(1290, '1186', 'Sirunagar', 0, '0'),
(1291, '1187', 'Sirungatur', 0, '0'),
(1292, '1188', 'Siruperpandi', 0, '0'),
(1293, '1189', 'Siruthalaipoondi', 0, '0'),
(1294, '1190', 'Siruvanguanam', 0, '0'),
(1295, '1191', 'Solanthangal', 0, '0'),
(1296, '1192', 'Soorai', 0, '0'),
(1297, '1193', 'Soorakottai', 0, '0'),
(1298, '1194', 'Sozhangunam', 0, '0'),
(1299, '1195', 'Sozhanthangal', 0, '0'),
(1300, '1196', 'Sundupallam', 0, '0'),
(1301, '1197', 'Thachanur', 0, '0'),
(1302, '1198', 'Thakkaraithangal', 0, '0'),
(1303, '1199', 'Thalarbadi', 0, '0'),
(1304, '1200', 'Thamara Pakkam', 0, '0'),
(1305, '1201', 'Thandaraipettai', 0, '0'),
(1306, '1202', 'Thateri', 0, '0'),
(1307, '1203', 'Thathankuppam', 0, '0'),
(1308, '1204', 'Thattancherry', 0, '0'),
(1309, '1205', 'Thattangulam', 0, '0'),
(1310, '1206', 'Thazhangunam', 0, '0'),
(1311, '1207', 'Then Aalapiranthan', 0, '0'),
(1312, '1208', 'Theneripattu', 0, '0'),
(1313, '1209', 'Thenkaranai', 0, '0'),
(1314, '1210', 'Thennampattu', 0, '0'),
(1315, '1211', 'Thenpakkam', 0, '0'),
(1316, '1212', 'Thimiri', 0, '0'),
(1317, '1213', 'Thimmapuram', 0, '0'),
(1318, '1214', 'Thinnalur', 0, '0'),
(1319, '1215', 'Thirumangalam', 0, '0'),
(1320, '1216', 'Thirumukkadu', 0, '0'),
(1321, '1217', 'Thirumukoodal', 0, '0'),
(1322, '1218', 'Thiruvadur', 0, '0'),
(1323, '1219', 'Thiruvakkarai', 0, '0'),
(1324, '1220', 'Thiruvalacherry', 0, '0'),
(1325, '1221', 'Thiruvathavar', 0, '0'),
(1326, '1222', 'Thiruvenkatapuram', 0, '0'),
(1327, '1223', 'Thondur', 0, '0'),
(1328, '1224', 'Thonnadu', 0, '0'),
(1329, '1225', 'Thottancherry', 0, '0'),
(1330, '1226', 'Thunaiyamkuppam', 0, '0'),
(1331, '1227', 'Thuthuvilampattu', 0, '0'),
(1332, '1228', 'Tiruvannamalai', 0, '0'),
(1333, '1229', 'Ulundhumangalam', 0, '0'),
(1334, '1230', 'Ulundurpettai', 0, '0'),
(1335, '1231', 'Unamalai', 0, '0'),
(1336, '1231', 'Mettu Unamalai', 0, '0'),
(1337, '1232', 'Uthamanallur', 0, '0'),
(1338, '559', 'Angalamman Koil St', 0, '0'),
(1339, '559', 'Anna Nagar', 0, '0'),
(1340, '559', 'Ayyadurai Naidu St', 0, '0'),
(1341, '559', 'Bazar St', 0, '0'),
(1342, '559', 'Chappani Goundar St', 0, '0'),
(1343, '559', 'Chinna Colony', 0, '0'),
(1344, '559', 'Chinna Narasampet St', 0, '0'),
(1345, '559', 'Dance Pettai St', 0, '0'),
(1346, '559', 'Durgaiyamman Koil St', 0, '0'),
(1347, '559', 'Gangaiyamman koil St', 0, '0'),
(1348, '559', 'Godhanda Ramaiyangar St', 0, '0'),
(1349, '559', 'Jaheer Ussain St', 0, '0'),
(1350, '559', 'Jayalakshmi St', 0, '0'),
(1351, '559', 'JP Nagar', 0, '0'),
(1352, '559', 'Kamarajar St', 0, '0'),
(1353, '559', 'Kannadasan St', 0, '0'),
(1354, '559', 'Karuneegar St', 0, '0'),
(1355, '559', 'Kedareeswarar Koil St', 0, '0'),
(1356, '559', 'Kizh Venkacherry St', 0, '0'),
(1357, '559', 'Kollamedu Melandai St', 0, '0'),
(1358, '559', 'Kollamedu St', 0, '0'),
(1359, '559', 'Kulakarai St', 0, '0'),
(1360, '559', 'Kulambara Koil St', 0, '0'),
(1361, '559', 'Kuzhambara koil St', 0, '0'),
(1362, '559', 'KV Achari St', 0, '0'),
(1363, '559', 'Loyolo School Op', 0, '0'),
(1364, '559', 'Madhura Krishnapuram', 0, '0'),
(1365, '559', 'Maduriyamman Koil St', 0, '0'),
(1366, '559', 'Mahalakshmi Nagar', 0, '0'),
(1367, '559', 'Mailan St', 0, '0'),
(1368, '559', 'Main Rd', 0, '0'),
(1369, '559', 'Mettu St', 0, '0'),
(1370, '559', 'MGR Nagar', 0, '0'),
(1371, '559', 'Mission School St', 0, '0'),
(1372, '559', 'Murugan Koil St', 0, '0'),
(1373, '559', 'Muthu Krishna Avenue', 0, '0'),
(1374, '559', 'Naidu St', 0, '0'),
(1375, '559', 'Narasimman Nagar', 0, '0'),
(1376, '559', 'North Reddi St', 0, '0'),
(1377, '559', 'Old hospital Rd', 0, '0'),
(1378, '559', 'Pavodumthoppu St', 0, '0'),
(1379, '559', 'Periya St', 0, '0'),
(1380, '559', 'Puthu koil St', 0, '0'),
(1381, '559', 'Ragavendrar St', 0, '0'),
(1382, '559', 'Sadhukam New St', 0, '0'),
(1383, '559', 'Sunnambukara St', 0, '0'),
(1384, '559', 'Thamaraikulam St', 0, '0'),
(1385, '559', 'Thandukara St', 0, '0'),
(1386, '559', 'Thirumalai Pillai St', 0, '0'),
(1387, '559', 'Thiruvalluvar St', 0, '0');
INSERT INTO `sub_area_list_creation` (`sub_area_id`, `area_id_ref`, `sub_area_name`, `sub_area_enable`, `status`) VALUES
(1388, '559', 'Vadaku Reddi St', 0, '0'),
(1389, '559', 'Vaigunda Perumal Koil St', 0, '0'),
(1390, '559', 'Vandikara St', 0, '0'),
(1391, '559', 'Vannara St', 0, '0'),
(1392, '559', 'Vanniya Pillayar Koil St', 0, '0'),
(1393, '559', 'Vazhathottam Back Side', 0, '0'),
(1394, '559', 'Vellai Chetti St', 0, '0'),
(1395, '559', 'Vengadaiyar Pillai St', 0, '0'),
(1396, '1233', 'Vaadanallur', 0, '0'),
(1397, '1234', 'Vaanur', 0, '0'),
(1398, '1235', 'Vada Aalapiranthan', 0, '0'),
(1399, '1236', 'Vada Kalpakkam', 0, '0'),
(1400, '1237', 'Vadamanapakkam', 0, '0'),
(1401, '1238', 'Vadamangalam', 0, '0'),
(1402, '1239', 'Vadampoondi', 0, '0'),
(1403, '1240', 'Vaduvankudisai', 0, '0'),
(1404, '1241', 'Vairapuram', 0, '0'),
(1405, '1242', 'Vaiyavur', 0, '0'),
(1406, '1243', 'Vakadai', 0, '0'),
(1407, '1244', 'Valalur', 0, '0'),
(1408, '1245', 'Valarpirai', 0, '0'),
(1409, '1246', 'Valasiramani', 0, '0'),
(1410, '1247', 'Valluvapakkam', 0, '0'),
(1411, '577', 'Aalamara St', 0, '0'),
(1412, '577', 'Acharapakkam Rd', 0, '0'),
(1413, '577', 'Agathiyappa Nagar', 0, '0'),
(1414, '577', 'Akbar Rd', 0, '0'),
(1415, '577', 'Akilandeshwari St', 0, '0'),
(1416, '577', 'Ambetkar St', 0, '0'),
(1417, '577', 'Annasamy Mudhali St', 0, '0'),
(1418, '577', 'Appavu Mudhali St', 0, '0'),
(1419, '577', 'Arani Rd', 0, '0'),
(1420, '577', 'Aravindhar St', 0, '0'),
(1421, '577', 'Baba Sahip St', 0, '0'),
(1422, '577', 'Bajanai Koil St', 0, '0'),
(1423, '577', 'Balu Udaiyar St', 0, '0'),
(1424, '577', 'Bharath Madha Nagar', 0, '0'),
(1425, '577', 'Big Colony', 0, '0'),
(1426, '577', 'Big Masuthi St', 0, '0'),
(1427, '577', 'Big New Colony', 0, '0'),
(1428, '577', 'Binag Kadharsha St', 0, '0'),
(1429, '577', 'Bommidinadoor', 0, '0'),
(1430, '577', 'Braminar St', 0, '0'),
(1431, '577', 'Chetpet Rd', 0, '0'),
(1432, '577', 'Cycle Varadhara Mudhali St', 0, '0'),
(1433, '577', 'Dharmaraja Koil St', 0, '0'),
(1434, '577', 'Durairaj St', 0, '0'),
(1435, '577', 'Earimalai', 0, '0'),
(1436, '577', 'Earimedu', 0, '0'),
(1437, '577', 'East Kammala St', 0, '0'),
(1438, '577', 'Eda St', 0, '0'),
(1439, '577', 'Gajalakshmi Nagar', 0, '0'),
(1440, '577', 'Gandhi Rd', 0, '0'),
(1441, '577', 'GH Rd', 0, '0'),
(1442, '577', 'Indhira Nagar', 0, '0'),
(1443, '577', 'Irattaivadai Chetti St', 0, '0'),
(1444, '577', 'Irattavadai Chetti St', 0, '0'),
(1445, '577', 'Kaatunayakan St', 0, '0'),
(1446, '577', 'Kadhar Meeran St', 0, '0'),
(1447, '577', 'Kalaikara St', 0, '0'),
(1448, '577', 'Kalungu Othavadai St', 0, '0'),
(1449, '577', 'Kamaraj Nagar', 0, '0'),
(1450, '577', 'Kamban Nagar', 0, '0'),
(1451, '577', 'Kammala St', 0, '0'),
(1452, '577', 'Kanaga Ramasamy Pillai St', 0, '0'),
(1453, '577', 'Kanchipuram Rd', 0, '0'),
(1454, '577', 'Kannara St', 0, '0'),
(1455, '577', 'Kanniyappan St', 0, '0'),
(1456, '577', 'Kasthuribai St', 0, '0'),
(1457, '577', 'Kasthurinai St', 0, '0'),
(1458, '577', 'Kattu Nayakkan St', 0, '0'),
(1459, '577', 'Kavarai St', 0, '0'),
(1460, '577', 'Kayalar Othavadai St', 0, '0'),
(1461, '577', 'Kayeethamillah St', 0, '0'),
(1462, '577', 'Kayeethe Millah Nagar', 0, '0'),
(1463, '577', 'Kesavan Nagar', 0, '0'),
(1464, '577', 'KMP Garden', 0, '0'),
(1465, '577', 'Kottai moolai', 0, '0'),
(1466, '577', 'Kottai Old colony', 0, '0'),
(1467, '577', 'Kottai Periya Colony', 0, '0'),
(1468, '577', 'kottaikul New St', 0, '0'),
(1469, '577', 'Kottaikul St', 0, '0'),
(1470, '577', 'Krishtappa Udayar St', 0, '0'),
(1471, '577', 'KRK St', 0, '0'),
(1472, '577', 'KSK Nagar', 0, '0'),
(1473, '577', 'KVT Nagar', 0, '0'),
(1474, '577', 'Madha Koil St', 0, '0'),
(1475, '577', 'Mahaveer St', 0, '0'),
(1476, '577', 'Makka Palli Vasal', 0, '0'),
(1477, '577', 'Makthum Maraikayar St', 0, '0'),
(1478, '577', 'Mandaiveli St', 0, '0'),
(1479, '577', 'Meenavar St', 0, '0'),
(1480, '577', 'Meera Kadharsha St', 0, '0'),
(1481, '577', 'Meera Ushain Nagar', 0, '0'),
(1482, '577', 'MGR Dippo Opposite', 0, '0'),
(1483, '577', 'MGR Nagar Colony', 0, '0'),
(1484, '577', 'Miyanna Kadharsha St', 0, '0'),
(1485, '577', 'Muthiyal Pettai', 0, '0'),
(1486, '577', 'Nadhamuni St', 0, '0'),
(1487, '577', 'Namanthakarai St', 0, '0'),
(1488, '577', 'New Bus Stand St', 0, '0'),
(1489, '577', 'New Colony St', 0, '0'),
(1490, '577', 'New Kottai Colony', 0, '0'),
(1491, '577', 'New Kottai St', 0, '0'),
(1492, '577', 'New St', 0, '0'),
(1493, '577', 'Othavadai Chetti St', 0, '0'),
(1494, '577', 'Othavadai Chetty St', 0, '0'),
(1495, '577', 'Ottar St', 0, '0'),
(1496, '577', 'Pakir Dharga St', 0, '0'),
(1497, '577', 'Pakkukara St', 0, '0'),
(1498, '577', 'Pennagu Kadharsha St', 0, '0'),
(1499, '577', 'Periya Colony', 0, '0'),
(1500, '577', 'Periya Masuthi st', 0, '0'),
(1501, '577', 'Pillayar Koil St', 0, '0'),
(1502, '577', 'Penangu Kadharsha St', 0, '0'),
(1503, '577', 'Poonga Nagar', 0, '0'),
(1504, '577', 'Potti Naidu St', 0, '0'),
(1505, '577', 'Power Station Rd', 0, '0'),
(1506, '577', 'Radio Room St', 0, '0'),
(1507, '577', 'Rajiv Gandhi St', 0, '0'),
(1508, '577', 'Ramadass St', 0, '0'),
(1509, '577', 'Ramaiyar St', 0, '0'),
(1510, '577', 'Ramasami Udaiyar St', 0, '0'),
(1511, '577', 'RV Chetti St', 0, '0'),
(1512, '577', 'Sakthi Ganabadhi Hotel', 0, '0'),
(1513, '577', 'Sakthi Nagar', 0, '0'),
(1514, '577', 'Sakthi Vinayagar Koil St', 0, '0'),
(1515, '577', 'Sannathi St', 0, '0'),
(1516, '577', 'Sarkar New Complex', 0, '0'),
(1517, '577', 'Sembada St', 0, '0'),
(1518, '577', 'Shanmugam St', 0, '0'),
(1519, '577', 'Sivaraj Nagar', 0, '0'),
(1520, '577', 'Thenaruvi Nagar', 0, '0'),
(1521, '577', 'Theradi', 0, '0'),
(1522, '577', 'Thiru Neelakandar St', 0, '0'),
(1523, '577', 'Thulukaanathamman Koil St', 0, '0'),
(1524, '577', 'Tindivanam Rd', 0, '0'),
(1525, '577', 'Tourist Banglow ', 0, '0'),
(1526, '577', 'Vadivel St', 0, '0'),
(1527, '577', 'Vanniyar St', 0, '0'),
(1528, '577', 'Veerasami Mudhali St', 0, '0'),
(1529, '577', 'Veerasamy Mudhali St', 0, '0'),
(1530, '577', 'Venkundram Road', 0, '0'),
(1531, '577', 'Vijayaranga Mudhali St', 0, '0'),
(1532, '577', 'Vinayaga Mudhali St', 0, '0'),
(1533, '577', 'Yadhavar St', 0, '0'),
(1534, '1248', 'Vandivakam', 0, '0'),
(1535, '1249', 'Vanganur', 0, '0'),
(1536, '1250', 'Vannankuttai', 0, '0'),
(1537, '1251', 'Vanniyanallur', 0, '0'),
(1538, '1252', 'Vanniyar pettai', 0, '0'),
(1539, '1253', 'Varagur', 0, '0'),
(1540, '1254', 'Varatharajapuram', 0, '0'),
(1541, '1255', 'Vasanthavadi', 0, '0'),
(1542, '1256', 'Vazhaipanthal', 0, '0'),
(1543, '1257', 'Vazhkudai', 0, '0'),
(1544, '1258', 'Veeranakunam', 0, '0'),
(1545, '1259', 'Velaamur', 0, '0'),
(1546, '1260', 'Velapadi', 0, '0'),
(1547, '1261', 'Velari', 0, '0'),
(1548, '1262', 'Velimedupettai', 0, '0'),
(1549, '1263', 'Veliyampettai', 0, '0'),
(1550, '1264', 'Vellamur', 0, '0'),
(1551, '1265', 'Velleripattu', 0, '0'),
(1552, '1266', 'Vempakkam', 0, '0'),
(1553, '1267', 'Vengikkal', 0, '0'),
(1554, '1268', 'Vennagupattu', 0, '0'),
(1555, '1269', 'Vettaikaranpettai', 0, '0'),
(1556, '1270', 'Vettam Perumbakkam', 0, '0'),
(1557, '1271', 'Vettur', 0, '0'),
(1558, '1272', 'Vilakkanandal', 0, '0'),
(1559, '1273', 'Villanallur', 0, '0'),
(1560, '1274', 'Vilvarayanpettai', 0, '0'),
(1561, '1275', 'vyasarbadi', 0, '0'),
(1563, '1277', 'Alliyanthangal', 0, '0'),
(1564, '1278', 'Alwarpettai', 0, '0'),
(1565, '1279', 'Avathikapuram', 0, '0'),
(1566, '1280', 'Chinnakalandhal', 0, '0'),
(1567, '1281', 'Devanampattu', 0, '0'),
(1568, '1282', 'Elathur', 0, '0'),
(1569, '1283', 'Erikuppam', 0, '0'),
(1570, '1284', 'Nallalam', 0, '0'),
(1571, '1285', 'Namamangalam', 0, '0'),
(1572, '1286', 'Nammiyandhal', 0, '0'),
(1573, '1287', 'Pulunthai', 0, '0'),
(1574, '1288', 'Setlam pattu', 0, '0'),
(1575, '1289', 'Theppananthal', 0, '0'),
(1576, '1290', 'Vilapakkam', 0, '0'),
(1577, '1291', 'Kozhavur', 0, '0'),
(1578, '1292', 'Eluvambadi', 0, '0'),
(1579, '1293', 'Kallarapadi', 0, '0'),
(1580, '1294', 'Devanambattu', 0, '0'),
(1581, '1295', 'Chinnandal', 0, '0'),
(1582, '1296', 'Pazhavery', 0, '0'),
(1583, '1297', 'Kaikilanthangal', 0, '0'),
(1584, '1298', 'Chinnasandiram', 0, '0'),
(1585, '1299', 'Siruvallur', 0, '0'),
(1586, '1300', 'Namathaguttai', 0, '0'),
(1587, '1301', 'Ananthamangalam', 0, '0'),
(1588, '1302', 'Eraiyankadu', 0, '0'),
(1589, '1303', 'Periyanthangal', 0, '0'),
(1590, '1304', 'Sirukilambadi', 0, '0'),
(1591, '1305', 'Thalavarampoondi', 0, '0'),
(1592, '1306', 'Vellamalai', 0, '0'),
(1593, '1307', 'Abiramapuram', 0, '0'),
(1594, '1308', 'Mettur', 0, '0'),
(1595, '1309', 'Arpaakkam', 0, '0'),
(1596, '1310', 'Mulapuratai', 0, '0'),
(1597, '1311', 'Yesurajapuram', 0, '0'),
(1598, '1312', 'Thennagaram', 0, '0'),
(1599, '1313', 'Karapallam', 0, '0'),
(1600, '1314', 'Venkatapalayam', 0, '0'),
(1601, '1315', 'Ayyampalayam', 0, '0'),
(1602, '1316', 'Periya Kilambadi', 0, '0'),
(1603, '1317', 'Kovur Neelanthangal', 0, '0'),
(1604, '1318', 'Mampattu', 0, '0'),
(1605, '1319', 'Anandal', 0, '0'),
(1606, '1320', 'Jalaganda vinayagapuram', 0, '0'),
(1607, '1321', 'Karalpakkam', 0, '0'),
(1608, '1322', 'Settupattu', 0, '0'),
(1609, '1323', 'Natchathra Koil', 0, '0'),
(1610, '1324', 'Thellarampattu', 0, '0'),
(1611, '1325', 'Bosco puram', 0, '0'),
(1612, '1326', 'Mahamayee Thirumani', 0, '0'),
(1613, '1327', 'Periyakallanthal', 0, '0'),
(1614, '1328', 'kettavaram palayam', 0, '0'),
(1615, '1329', 'Kattavaram', 0, '0'),
(1616, '470', 'Salavakkam Koot Road', 0, '0'),
(1617, '1330', 'Poondi PLR', 0, '0'),
(1618, '1331', 'Maruthuvambadi PLR', 0, '0'),
(1619, '1332', 'VilVarayanallur PLR', 0, '0'),
(1620, '1333', 'Arpakkam PLR', 0, '0'),
(1621, '1334', 'Randham PLR', 0, '0'),
(1622, '1335', 'Melkaranai PLR', 0, '0'),
(1623, '1336', 'Melathangal PLR', 0, '0'),
(1624, '1337', 'Peranambakkam PLR', 0, '0'),
(1625, '1338', 'Kattuputhur PLR', 0, '0'),
(1626, '1339', 'Cholavaram PLR', 0, '0'),
(1627, '1340', 'Arumbalur PLR', 0, '0'),
(1628, '1341', 'Boothamangalam PLR', 0, '0'),
(1629, '1318', 'Mambattu PLR', 0, '0'),
(1630, '1342', 'Aariyathur PLR', 0, '0'),
(1631, '1343', 'Samathuvapuram PLR', 0, '0'),
(1632, '1344', 'Kalambur PLR', 0, '0'),
(1633, '1345', 'Nainavaram PLR', 0, '0'),
(1634, '1346', 'karapoondi PLR', 0, '0'),
(1635, '1347', 'Narayanamangalam PLR', 0, '0'),
(1636, '1348', 'Kannigapuram PLR', 0, '0'),
(1637, '1349', 'Krishnapuram PLR', 0, '0'),
(1638, '1350', 'Pulivananthal PLR', 0, '0'),
(1639, '1351', 'Endhal PLR', 0, '0'),
(1640, '1352', 'Nadukuppam PLR', 0, '0'),
(1641, '1353', 'Elluparai PLR', 0, '0'),
(1642, '1354', 'Ariyathur PLR', 0, '0'),
(1643, '1355', 'Kommananthal PLR', 0, '0'),
(1644, '1356', 'Jothi Nagar PLR', 0, '0'),
(1645, '1357', 'Madavilagam PLR', 0, '0'),
(1646, '1358', 'Samathuvapuram UTR', 0, '0'),
(1647, '1359', 'Vinayagapuram UTR', 0, '0'),
(1648, '1360', 'Paleshwaram UTR', 0, '0'),
(1649, '1361', 'Edayampudhur UTR', 0, '0'),
(1650, '1362', 'Nerkundram UTR', 0, '0'),
(1651, '1363', 'Neerkundram UTR', 0, '0'),
(1652, '1184', 'Sirumailur UTR', 0, '0'),
(1653, '1364', 'AmaravathiPattinam UTR', 0, '0'),
(1654, '1365', 'Arunkundram UTR', 0, '0'),
(1655, '1366', 'Pulivoy UTR', 0, '0'),
(1656, '1367', 'Elayanarvelur UTR', 0, '0'),
(1657, '1368', 'Vayalakkavoor UTR', 0, '0'),
(1658, '1369', 'Nallur UTR', 0, '0'),
(1659, '1370', 'Kolathur UTR', 0, '0'),
(1660, '1371', 'Salavakkam Koot Road UTR', 0, '0'),
(1661, '1372', 'Kilakadi UTR', 0, '0'),
(1662, '1373', 'Vinnavadi UTR', 0, '0'),
(1663, '1374', 'Mangalam UTR', 0, '0'),
(1664, '1034', 'Mathur UTR', 0, '0'),
(1665, '1375', 'Arayanipalai UTR', 0, '0'),
(1666, '1376', 'Krishnapuram UTR', 0, '0'),
(1667, '1377', 'Nellimodhu UTR', 0, '0'),
(1668, '1379', 'Thellur CPT', 0, '0'),
(1669, '1378', 'Ramapuram CPT', 0, '0'),
(1670, '1380', 'Sindagam Poondi', 0, '0'),
(1671, '1381', 'Maruthuvambadi CPT', 0, '0'),
(1672, '1382', 'Kannigapuram cpt', 0, '0'),
(1673, '1383', 'Vinayagapuram CPT', 0, '0'),
(1674, '1384', 'Nambedu CPT', 0, '0'),
(1675, '1385', 'sanjivarayanpettai CPT', 0, '0'),
(1676, '1386', 'Madam CPT', 0, '0'),
(1677, '1387', 'Kothandavadi CPT', 0, '0'),
(1678, '1388', 'Kaividanthangal CPT', 0, '0'),
(1679, '1389', 'Kalayanapuram CPT', 0, '0'),
(1680, '1390', 'Ponnur CPT', 0, '0'),
(1681, '1391', 'Peranambakkam CPT', 0, '0'),
(1682, '1392', 'Pinnanur CPT', 0, '0'),
(1683, '1393', 'Malaiyittan Kuppam VSI', 0, '0'),
(1684, '1394', 'Isakolathur CPT', 0, '0'),
(1685, '1395', 'Karanai UTR', 0, '0'),
(1686, '1396', 'Papanallur UTR', 0, '0'),
(1687, '1397', 'Kovalai UTR', 0, '0'),
(1688, '1398', 'Vengodu UTR', 0, '0'),
(1689, '1399', 'Kannigapuram UTR', 0, '0'),
(1690, '1400', 'Perumbakkam UTR', 0, '0'),
(1691, '1401', 'Narasingapuram CPT', 0, '0'),
(1692, '1402', 'MelNemili CPT', 0, '0'),
(1693, '1403', 'Malaiyittankuppam VSI', 0, '0'),
(1694, '1404', 'Melkaranai CPT', 1, '0'),
(1695, '1405', 'Kodithandalam', 0, '0'),
(1696, '1406', 'Anmarudhai CPT', 0, '0'),
(1697, '1407', 'Reddikuppam', 0, '0'),
(1698, '1409', 'Kalyanamedu UTR', 0, '0'),
(1699, '1410', 'Gandhinagar', 0, '0'),
(1700, '1411', 'Irumbedu UTR', 0, '0'),
(1701, '1411', 'Irumbeduu', 0, '0'),
(1702, '1412', 'Lakshmipuram CPT', 0, '0'),
(1703, '1413', 'Chokkapallam CPT', 0, '0'),
(1704, '1414', 'Cholavaram CPT', 0, '0'),
(1705, '1415', 'Anathamangalam CPT', 0, '0'),
(1706, '1416', 'Krishnapuram CPT', 0, '0'),
(1707, '1417', 'Avaniyapuram CPT', 0, '0'),
(1708, '1418', 'Kandamanallur CPT', 0, '0'),
(1709, '1419', 'Papanthangal CPT', 0, '0'),
(1710, '1420', 'Agaram CPT', 0, '0'),
(1711, '1421', 'Thennathur CPT', 0, '0'),
(1712, '1422', 'Singampoondi CPT', 0, '0'),
(1713, '1423', 'Vadavetti CPT', 0, '0'),
(1714, '1424', 'Sennanthal CPT', 0, '0'),
(1715, '1425', 'Vinnamangalam CPT', 0, '0'),
(1716, '1426', 'Kalyanapuram CPT', 0, '0'),
(1717, '1427', 'Kattupakkam CPT', 0, '0'),
(1718, '1428', 'Mottur CPT', 0, '0'),
(1719, '1429', 'Vayaloor CPT', 0, '0'),
(1720, '1430', 'Udiyanthangal CPT', 0, '0'),
(1721, '1431', 'Pudur CPT', 0, '0'),
(1722, '1433', 'Melanur', 0, '0'),
(1723, '1434', 'Puthupatti', 0, '0'),
(1724, '1435', 'Sambuvaraya Nallur', 0, '0'),
(1725, '1436', 'Poongavanam', 0, '0'),
(1726, '1437', 'Sambuvarayanallur', 0, '0'),
(1727, '1438', 'Kariyambadi', 0, '0'),
(1728, '1439', 'Gudisaikarai', 0, '0'),
(1729, '1440', 'Sandisatchi', 0, '0'),
(1730, '1441', 'Puthupattu', 0, '0'),
(1731, '1442', 'Kulakkarai', 0, '0'),
(1732, '1443', 'Kizh Peranamalur', 0, '0'),
(1733, '1444', 'Vijayaragavapuram', 0, '0'),
(1734, '1445', 'Agaram Pallipattu', 0, '0'),
(1735, '1446', 'Janagipuram', 0, '0'),
(1736, '1447', 'Poongavanam', 0, '0'),
(1737, '1448', 'Poongavanam', 0, '0'),
(1738, '1449', 'Kariyambadi', 0, '0'),
(1739, '1450', 'Gudisaikarai', 0, '0'),
(1740, '1451', 'Agaram Pallipattu', 0, '0'),
(1741, '1452', 'Sandisatchi', 0, '0'),
(1742, '1453', 'Puthupattu', 0, '0'),
(1743, '1454', 'Kulakkarai', 0, '0'),
(1744, '1455', 'Kizh Peranamalur', 0, '0'),
(1745, '1456', 'Vijayaragavapuram', 0, '0'),
(1746, '1457', 'Janagipuram', 0, '0'),
(1747, '1459', 'Vedanthangal UTR', 0, '0'),
(1748, '1460', 'Perunkadaputhur VSI', 0, '0'),
(1749, '1461', 'Marakunam VSI', 0, '0'),
(1750, '1462', 'Korasalavadi VSI', 0, '0'),
(1751, '1463', 'Aalanthangal VSI', 0, '0'),
(1752, '1464', 'Velamur VSI', 0, '0'),
(1753, '1465', 'Mettupalayam UTR', 0, '0'),
(1754, '1466', 'Alliyamangalam CPT', 0, '0'),
(1755, '1467', 'Alathur', 0, '0'),
(1756, '1468', 'Alathurai UTR', 0, '0'),
(1757, '1469', 'Nadupattu CPT', 0, '0'),
(1758, '1470', 'Padur', 0, '0'),
(1759, '1471', 'Vadamanipakkam ', 0, '0'),
(1760, '1472', 'Murukkeri VSI', 0, '0'),
(1761, '1473', 'Mutharasampoondi', 0, '0'),
(1762, '1474', 'Vilakanthal ', 0, '0'),
(1763, '1475', 'Maruthuvambadi Vsi', 0, '0'),
(1764, '1476', 'Chethupet', 0, '0'),
(1765, '1476', 'Chethupet Colony', 0, '0'),
(1766, '1477', 'Mottur UTR', 0, '0'),
(1767, '1478', 'Vengaram VSI', 0, '0'),
(1768, '1479', 'Elapakkam UTR', 0, '0'),
(1769, '1480', 'Aalathur VSI', 0, '0'),
(1770, '1481', 'Pulavanpadi PLR', 0, '0'),
(1771, '1482', 'Arasur', 0, '0'),
(1772, '1483', 'Saanarpalayam', 0, '0'),
(1773, '1484', 'Thacharampattu', 0, '0'),
(1774, '1485', 'Thandarai Pettai', 0, '0'),
(1775, '1486', 'Ogur', 0, '0'),
(1776, '1487', 'Kilankuppam', 0, '0'),
(1777, '1488', 'Thuraiyur', 0, '0'),
(1778, '577', 'Elluparai vsi', 0, '0'),
(1779, '577', 'Srinivasan Nagar', 0, '0'),
(1780, '1489', 'Gunankaranai vsi', 0, '0'),
(1781, '1490', 'Mettupalayam vsi', 0, '0'),
(1782, '1491', 'Nariyampettai', 0, '0'),
(1783, '820', 'Arunthathiyar palayam', 0, '0'),
(1784, '451', 'keezhmedu', 0, '0'),
(1785, '362', 'Arunthathiyarpalayam', 0, '0'),
(1786, '559', 'S P Kovil street', 0, '0'),
(1787, '559', 'Anaikatti St', 0, '0'),
(1788, '568', 'vinayagapuram vadavanakampadi', 0, '0'),
(1789, '162', 'Nelli kammalapoondi', 0, '0'),
(1790, '559', 'Rice Mill St', 0, '0'),
(1791, '559', 'Kizhakku Kammala st', 0, '0'),
(1792, '1492', 'Thimmapuram UTR', 0, '0'),
(1793, '1493', 'Mudhalur', 0, '0'),
(1794, '650', 'Arunthathiyarpalayam gangeyanoor', 0, '0'),
(1795, '1494', 'Arunthathiyarpalayam mattapiraiyur', 0, '0'),
(1796, '1495', 'chennanathal', 0, '0'),
(1797, '1496', 'Kil Vanniyanoor', 0, '0'),
(1798, '1496', 'Mel Vanniyanoor', 0, '0'),
(1799, '1497', 'Sirumailoor', 0, '0'),
(1800, '559', 'Sathani St', 0, '0'),
(1801, '1498', 'Edamachi', 0, '0'),
(1802, '1499', 'Naranamangalam', 0, '0');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `emailid` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `role_type` varchar(255) DEFAULT NULL,
  `dir_id` varchar(255) DEFAULT NULL,
  `ag_id` varchar(255) DEFAULT NULL,
  `staff_id` varchar(255) DEFAULT NULL,
  `company_id` varchar(255) DEFAULT NULL,
  `branch_id` varchar(255) DEFAULT NULL,
  `loan_cat` varchar(255) DEFAULT NULL,
  `agentforstaff` varchar(255) DEFAULT NULL,
  `line_id` varchar(255) DEFAULT NULL,
  `group_id` varchar(255) DEFAULT NULL,
  `download_access` varchar(10) NOT NULL DEFAULT '1',
  `report_access` varchar(10) DEFAULT NULL,
  `pro_aty_access` varchar(100) DEFAULT '1,2,3',
  `mastermodule` varchar(255) DEFAULT '1',
  `company_creation` varchar(255) DEFAULT '1',
  `branch_creation` varchar(255) DEFAULT '1',
  `loan_category` varchar(255) DEFAULT '1',
  `loan_calculation` varchar(255) DEFAULT '1',
  `loan_scheme` varchar(255) DEFAULT '1',
  `area_creation` varchar(255) DEFAULT '1',
  `area_mapping` varchar(255) DEFAULT '1',
  `area_approval` varchar(255) DEFAULT '1',
  `adminmodule` varchar(255) DEFAULT '1',
  `director_creation` varchar(255) DEFAULT '1',
  `agent_creation` varchar(255) DEFAULT '1',
  `staff_creation` varchar(255) DEFAULT '1',
  `manage_user` varchar(255) DEFAULT '1',
  `doc_mapping` varchar(255) NOT NULL DEFAULT '1',
  `bank_creation` varchar(10) NOT NULL DEFAULT '1',
  `requestmodule` varchar(255) DEFAULT '1',
  `request` varchar(255) DEFAULT '1',
  `request_list_access` varchar(255) NOT NULL DEFAULT '1',
  `verificationmodule` varchar(255) DEFAULT '1',
  `verification` varchar(255) DEFAULT '1',
  `approvalmodule` varchar(255) NOT NULL DEFAULT '1',
  `approval` varchar(255) NOT NULL DEFAULT '1',
  `acknowledgementmodule` varchar(255) NOT NULL DEFAULT '1',
  `acknowledgement` varchar(255) NOT NULL DEFAULT '1',
  `loanissuemodule` varchar(255) DEFAULT '1',
  `loan_issue` varchar(255) DEFAULT '1',
  `collectionmodule` varchar(25) NOT NULL DEFAULT '1',
  `collection` varchar(25) NOT NULL DEFAULT '1',
  `collection_access` varchar(25) NOT NULL DEFAULT '1',
  `closedmodule` varchar(10) NOT NULL DEFAULT '1',
  `closed` varchar(10) NOT NULL DEFAULT '1',
  `nocmodule` varchar(10) NOT NULL DEFAULT '1',
  `noc` varchar(10) NOT NULL DEFAULT '1',
  `doctrackmodule` varchar(50) NOT NULL DEFAULT '1',
  `doctrack` varchar(50) NOT NULL DEFAULT '1',
  `doc_rec_access` varchar(50) NOT NULL DEFAULT '1',
  `updatemodule` varchar(10) NOT NULL DEFAULT '1',
  `update_screen` varchar(10) NOT NULL DEFAULT '1',
  `update_screen_id` varchar(10) DEFAULT NULL,
  `concernmodule` varchar(10) DEFAULT '1',
  `concern_creation` varchar(10) DEFAULT '1',
  `concern_solution` varchar(10) DEFAULT '1',
  `concern_feedback` varchar(50) DEFAULT '1',
  `accountsmodule` varchar(10) NOT NULL DEFAULT '1',
  `cash_tally` varchar(10) NOT NULL DEFAULT '1',
  `bank_details` varchar(255) DEFAULT NULL,
  `cash_tally_admin` varchar(10) NOT NULL DEFAULT '1',
  `bank_clearance` varchar(10) NOT NULL DEFAULT '1',
  `finance_insight` varchar(10) NOT NULL DEFAULT '1',
  `accounts_loan_issue` varchar(11) NOT NULL DEFAULT '1',
  `followupmodule` varchar(10) DEFAULT '1',
  `promotion_activity` varchar(10) DEFAULT '1',
  `loan_followup` varchar(10) DEFAULT '1',
  `confirmation_followup` varchar(10) DEFAULT '1',
  `due_followup` varchar(10) DEFAULT '1',
  `due_followup_lines` varchar(100) DEFAULT NULL,
  `reportmodule` varchar(10) NOT NULL DEFAULT '1',
  `ledger_report` varchar(10) NOT NULL DEFAULT '1',
  `request_report` varchar(10) NOT NULL DEFAULT '1',
  `cancel_revoke_report` varchar(10) NOT NULL DEFAULT '1',
  `cus_profile_report` varchar(10) NOT NULL DEFAULT '1',
  `loan_issue_report` varchar(10) NOT NULL DEFAULT '1',
  `collection_report` varchar(10) NOT NULL DEFAULT '1',
  `principal_interest_report` int(11) NOT NULL DEFAULT 1,
  `balance_report` varchar(10) NOT NULL DEFAULT '1',
  `due_list_report` varchar(10) NOT NULL DEFAULT '1',
  `in_closed_report` int(11) NOT NULL DEFAULT 1,
  `closed_report` varchar(10) NOT NULL DEFAULT '1',
  `confirmation_followup_report` varchar(10) NOT NULL DEFAULT '1',
  `agent_report` varchar(10) NOT NULL DEFAULT '1',
  `no_due_pay_report` int(11) DEFAULT NULL,
  `other_trans_report` varchar(10) DEFAULT NULL,
  `search_module` varchar(10) NOT NULL DEFAULT '1',
  `search` varchar(10) NOT NULL DEFAULT '1',
  `bulk_upload_module` varchar(10) NOT NULL DEFAULT '1',
  `bulk_upload` varchar(10) NOT NULL DEFAULT '1',
  `loan_track_module` varchar(10) NOT NULL DEFAULT '1',
  `loan_track` varchar(10) NOT NULL DEFAULT '1',
  `sms_module` int(11) NOT NULL DEFAULT 1,
  `sms_generation` int(11) NOT NULL DEFAULT 1,
  `status` varchar(255) NOT NULL DEFAULT '0',
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `delete_login_id` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `updated_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `firstname`, `lastname`, `fullname`, `title`, `emailid`, `user_name`, `user_password`, `role`, `role_type`, `dir_id`, `ag_id`, `staff_id`, `company_id`, `branch_id`, `loan_cat`, `agentforstaff`, `line_id`, `group_id`, `download_access`, `report_access`, `pro_aty_access`, `mastermodule`, `company_creation`, `branch_creation`, `loan_category`, `loan_calculation`, `loan_scheme`, `area_creation`, `area_mapping`, `area_approval`, `adminmodule`, `director_creation`, `agent_creation`, `staff_creation`, `manage_user`, `doc_mapping`, `bank_creation`, `requestmodule`, `request`, `request_list_access`, `verificationmodule`, `verification`, `approvalmodule`, `approval`, `acknowledgementmodule`, `acknowledgement`, `loanissuemodule`, `loan_issue`, `collectionmodule`, `collection`, `collection_access`, `closedmodule`, `closed`, `nocmodule`, `noc`, `doctrackmodule`, `doctrack`, `doc_rec_access`, `updatemodule`, `update_screen`, `update_screen_id`, `concernmodule`, `concern_creation`, `concern_solution`, `concern_feedback`, `accountsmodule`, `cash_tally`, `bank_details`, `cash_tally_admin`, `bank_clearance`, `finance_insight`, `accounts_loan_issue`, `followupmodule`, `promotion_activity`, `loan_followup`, `confirmation_followup`, `due_followup`, `due_followup_lines`, `reportmodule`, `ledger_report`, `request_report`, `cancel_revoke_report`, `cus_profile_report`, `loan_issue_report`, `collection_report`, `principal_interest_report`, `balance_report`, `due_list_report`, `in_closed_report`, `closed_report`, `confirmation_followup_report`, `agent_report`, `no_due_pay_report`, `other_trans_report`, `search_module`, `search`, `bulk_upload_module`, `bulk_upload`, `loan_track_module`, `loan_track`, `sms_module`, `sms_generation`, `status`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date`) VALUES
(1, 'Super', 'Admin', 'Super Admin', 'Super Admin', 'support@feathertechnology.in', 'support@feathertechnology.in', '123', '1', NULL, NULL, NULL, NULL, '1', '1,2,3,4,5', NULL, NULL, '1,2,3,4,5,6,7,8', '1,2,3,4,5,6,7,8', '1', '1', '1,2,3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NULL, '0', '0', '0', '0', '0', '0', NULL, '0', '0', '0', '1', '0', '0', '0', '0', '0', NULL, '0', '0', '0', '1', '0', '0', '0', 1, '0', '0', 1, '0', '1', '1', NULL, NULL, '0', '0', '0', '0', '0', '0', 0, 0, '0', NULL, NULL, NULL, '2024-06-13 11:13:00', '2024-06-13 11:13:00'),
(2, NULL, NULL, 'Naren', NULL, '', 'admin', 'Galaxy@2024', '3', '7', '', '', '1', '1', '1,2,3,4,5', '1,2,3', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1,2,3,4,5,6,7,8,9,10,11,12,13', '0', '2', '1,2,3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0', NULL, '0', '0', '0', '0', '0', '0', '2,3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26', '0', '0', '0', '0', '0', '0', '0', 0, '0', '0', 0, '0', '0', '0', 0, NULL, '0', '0', '0', '0', '0', '0', 0, 0, '0', '1', '2', NULL, '2024-06-26 15:01:20', '2025-06-03 13:24:23'),
(3, NULL, NULL, 'Dinesh Kumar K', NULL, '', 'prakash', '123', '3', '1', '', '', '2', '1', '1', '1,2', '', '3', '3', '1', '2', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '1', '1', '1', '0', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '1', '1', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2024-10-25 13:36:01', '2025-02-14 20:18:51'),
(4, NULL, NULL, 'Kathiravan', NULL, '', 'kathir', '123', '3', '1', '', '', '3', '1', '1', '1', '', '3', '3', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '1', '1', '1', '0', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '1', '1', '1', '1', '1', '1', 1, 1, '1', '2', '2', '2', '2024-10-25 13:44:14', '2024-10-25 13:53:35'),
(5, NULL, NULL, 'Hemalatha G', NULL, '', 'hemalatha g', 'hema19807200', '3', '6', '', '', '4', '1', '2', '3', '', '7', '7', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '0', '0', '8,10', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', '2', '2025-01-05 18:18:30', '2025-05-05 21:50:27'),
(6, NULL, NULL, 'Bhuvana G', NULL, '', 'bhuvana g', 'bhuv19869698', '3', '6', '', '', '5', '1', '2', '3', '', '8', '8', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '0', '0', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-05 18:20:12', '2025-04-21 12:05:43'),
(7, NULL, NULL, 'Bhuvaneshwari V', NULL, '', 'bhuvaneshwari v', 'bhuv19998220', '3', '6', '', '', '15', '1', '1', '3', '', '1,3', '1,3', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '0', '0', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '1', '2', '2', '70', '2025-01-05 18:22:00', '2025-03-03 10:19:46'),
(8, NULL, NULL, 'Dharmadevi J', NULL, '', 'DharmadeviJ', '123', '3', '5', '', '', '10', '1', '1,2,3,4', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75', '1,2,3,4,5,6,7,8,9,10', '1,2,3,4,5,6,7,8,9,10', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '0', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '1', '2', '2', '2', '2025-01-05 18:23:37', '2025-01-05 18:44:38'),
(9, NULL, NULL, 'Hemalatha S', NULL, '', 'hemalatha s', 'hema19886382', '3', '6', '', '', '14', '1', '2', '3', '', '7,8,13', '7,8,13', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '0', '0', '7,24', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-05 18:25:27', '2025-06-03 15:33:31'),
(10, NULL, NULL, 'Hemapriya S', NULL, '', 'hemapriya s', 'hema20039597', '3', '6', '', '', '9', '1', '1,2,3,4', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1,2,3,4,5,6,7,8,9,10,11,12,13', '0', '2', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '0', '0', '0', '0', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '0', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '0', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-05 18:27:55', '2025-04-21 12:09:12'),
(11, NULL, NULL, 'Mohanapriya E', NULL, '', 'mohanapriya e', 'moha20009344', '3', '6', '', '', '6', '1', '3', '3', '', '5', '5', '0', '2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '0', '0', '4,16,26', '0', '1', '1', '1', '1', '1', '0', 1, '1', '1', 1, '1', '0', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-05 18:29:28', '2025-06-03 15:34:13'),
(12, NULL, NULL, 'Monika M', NULL, '', 'monika m', 'moni19918248', '3', '6', '', '', '13', '1', '1', '3', '', '3', '3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '0', '0', '3,14', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '', '70', '2', '2025-01-05 18:31:09', '2025-05-05 16:17:45'),
(13, NULL, NULL, 'Naveena R', NULL, '', 'naveena r', 'nave20016374', '3', '6', '', '', '11', '1', '1', '3', '', '2', '2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '0', '0', '2,13', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '70', NULL, '2025-01-05 18:33:15', '2025-05-05 16:17:13'),
(14, NULL, NULL, 'Paviya J', NULL, '', 'paviya j', 'pavi20048056', '3', '6', '', '', '12', '1', '1', '3', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '0', '0', '1,25', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-05 18:35:27', '2025-06-03 15:34:37'),
(15, NULL, NULL, 'Saridha S', NULL, '', 'saridha s', 'sari19889629', '3', '6', '', '', '8', '1', '3', '3', '', '6', '6', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '0', '0', '5,17', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '70', NULL, '2025-01-05 18:36:58', '2025-05-05 16:21:11'),
(16, NULL, NULL, 'Uma Mageshwari J', NULL, '', 'umamageshwari j', 'umam20008940', '3', '6', '', '', '7', '1', '4', '3', '', '4,11,12', '4,11,12', '0', '2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '0', '0', '6,20,22', '0', '1', '1', '1', '1', '1', '0', 1, '1', '1', 1, '1', '0', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '70', NULL, '2025-01-05 18:38:40', '2025-05-05 16:27:56'),
(17, NULL, NULL, 'Arul S', NULL, '', 'ArulS', '123', '3', '2', '', '', '21', '1', '1', '1,2,3,4', '', '1,2,3', '1,2,3', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '1', '2', '2', '2', '2025-01-06 09:39:05', '2025-01-06 09:41:15'),
(18, NULL, NULL, 'Dinesh S', NULL, '', 'DineshS', '123', '3', '2', '', '', '23', '1', '1', '1,2,3,4', '', '1,2,3', '1,2,3', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '1', '2', NULL, '2', '2025-01-06 09:40:49', '2025-01-06 09:40:49'),
(19, NULL, NULL, 'Mahaveer Prasad', NULL, '', 'Mahaveer', '123', '3', '2', '', '', '22', '1', '1,2,3,4,5', '1,2,3,4', '', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', '2', '2025-01-06 09:42:48', '2025-04-03 09:46:12'),
(20, NULL, NULL, 'Manikandan E', NULL, '', 'ManikandanE', '123', '3', '2', '', '', '20', '1', '1', '1,2,3,4', '', '1,2,3', '1,2,3', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '1', '2', NULL, '2', '2025-01-06 09:44:55', '2025-01-06 09:44:55'),
(21, NULL, NULL, 'Padmanaban M', NULL, '', 'padmanaban m', 'padm19792568', '3', '2', '', '', '25', '1', '1,2,3,4,5', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,85', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '0', '1', '1', '1', '1', '1', '0', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-07 18:45:32', '2025-06-14 18:46:50'),
(22, NULL, NULL, 'Dillibabu C', NULL, '', 'dillibabu c', 'dill19884735', '3', '2', '', '', '24', '1', '1,2,3,4,5', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,85', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '0', '1', '1', '1', '1', '1', '0', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-07 18:48:14', '2025-06-14 18:47:13'),
(23, NULL, NULL, 'Dinesh S', NULL, '', 'dinesh s', 'dine19958762', '3', '2', '', '', '23', '1', '1,2,3,4', '1,2,3,4,6,7,8,9,10', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '0', '1', '1', '1', '1', '1', '0', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '1', '2', '2', '70', '2025-01-07 18:50:22', '2025-04-03 09:41:47'),
(24, NULL, NULL, 'Arul S', NULL, '', 'arul s', 'arul19926358', '3', '2', '', '', '21', '1', '1,3', '1,2,3,4,6,7,8,9,10', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,71,72,73,74,75,76', '1,2,3,5,6', '1,2,3,5,6', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '0', '0', '1', '0', '0', '0', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '1', '2', '2', '70', '2025-01-07 18:52:27', '2025-02-07 12:20:54'),
(25, NULL, NULL, 'Manikandan E', NULL, '', 'manikandan e', 'mani19989791', '3', '2', '', '', '20', '1', '1,2,3,4,5', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,85', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '0', '1', '1', '1', '1', '1', '0', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-07 18:56:39', '2025-06-14 18:47:42'),
(26, NULL, NULL, 'Jegan Kumar A', NULL, '', 'jegankumar a', 'jega19926489', '3', '2', '', '', '19', '1', '1,2,3,4,5', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,85', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '0', '1', '1', '1', '1', '1', '0', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-07 19:00:37', '2025-06-14 18:41:44'),
(27, NULL, NULL, 'Ramachandran G', NULL, '', 'ramachandran g', 'rama19949626', '3', '9', '', '', '18', '1', '1,2,3,4', '3', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,85', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '1,2,3,4,5,6,7,8,9,10,11,12,13', '0', '2', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '0', '0', '1', '1', '1', '1', '1', '1', '0', '0', '0', '1', '1', '0', '0', '1', '0', '0', '0', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '0', '1', '1', '1', '1', '1', '0', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-07 19:07:36', '2025-06-14 18:42:09'),
(28, NULL, NULL, 'Mahaveer Prasad', NULL, '', 'mahaveerprasad', 'maha19982622', '3', '2', '', '', '22', '1', '1,2,3,4,5', '1,2,3,4,6,7,8,9,10', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '0', '1', '1', '1', '1', '1', '0', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '70', NULL, '2025-01-07 19:37:54', '2025-03-11 14:59:17'),
(29, NULL, NULL, 'Deepak E', NULL, '', 'deepak e', 'deep19936677', '3', '1', '', '', '28', '1', '2', '1,2,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,85', '7,8,13', '7,8,13', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-07 22:02:12', '2025-06-14 18:43:49'),
(30, NULL, NULL, 'Manivannan J', NULL, '', 'manivannan j', 'mani19855985', '3', '2', '', '', '30', '1', '4', '3,7', '36', '4,11,12', '4,11,12', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '70', NULL, '2025-01-07 22:05:53', '2025-03-05 19:27:59'),
(31, NULL, NULL, 'Ramesh V', NULL, '', 'ramesh v', 'rame19968124', '3', '2', '', '', '31', '1', '1', '1,2', '1,2,13,14,15,17,18,19,21,22,23,24,25,26,27,28,33,36,37,39,40,46,48,50,52,60,61,62,63,65,69,70,71,76,77', '1,2,3', '1,2,3', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '1', '2', '70', '60', '2025-01-07 22:11:30', '2025-03-17 11:37:44'),
(32, NULL, NULL, 'Diwakar  E', NULL, '', 'diwakar e', 'diwa19967708', '3', '2', '', '', '33', '1', '1', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77', '1,2,3', '1,2,3', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '1', '2', '2', '70', '2025-01-07 22:15:32', '2025-02-15 16:02:46'),
(33, NULL, NULL, 'JohnPaul P', NULL, '', 'johnpaul p', 'john19782300', '3', '2', '', '', '34', '1', '1', '1', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,81,83,84', '1,2,3,14', '1,2,3', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-07 22:17:58', '2025-05-26 12:35:21'),
(34, NULL, NULL, 'Sathishkumar R', NULL, '', 'sathishkumar r', 'sath20011549', '3', '2', '', '', '35', '1', '2', '1', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,85', '7,8,13', '7,8,13', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-07 22:20:33', '2025-06-14 18:43:27'),
(35, NULL, NULL, 'Mohana Sundaram R', NULL, '', 'mohanasundaram r', 'moha19966380', '3', '2', '', '', '36', '1', '1', '1,2', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77', '1,2,3', '1,2,3', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '1', '2', '70', '70', '2025-01-07 22:22:57', '2025-03-17 11:38:21'),
(36, NULL, NULL, 'KrishnaRaw S', NULL, '', 'krishnaraw s', 'kris19968438', '3', '2', '', '', '37', '1', '3', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75', '5,6', '5,6', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '70', NULL, '2025-01-07 22:25:39', '2025-06-07 17:49:19'),
(37, NULL, NULL, 'Dinesh M', NULL, '', 'dinesh m', 'dine19991918', '3', '2', '', '', '38', '1', '1', '1', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,81,83,84', '1,2,3', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-07 22:27:49', '2025-05-26 12:29:03'),
(38, NULL, NULL, 'Logeshwaran R', NULL, '', 'logeshwaran r', 'loge19952539', '3', '2', '', '', '40', '1', '1', '1,2,3,7', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77', '1,2,3', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '1', '2', '70', '60', '2025-01-07 22:30:16', '2025-03-05 19:24:55'),
(39, NULL, NULL, 'Rakesh R', NULL, '', 'rakesh r', 'rake19958088', '3', '2', '', '', '41', '1', '4', '1,2,3,4,6,7,8,9', '4', '4,11,12', '4,11,12', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '1', '', '0', '1', '1', '1', '1', '1', '0', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-07 22:32:54', '2025-04-29 09:31:15'),
(40, NULL, NULL, 'Madhan Kumar L', NULL, '', 'madhankumar l', 'madh19969597', '3', '2', '', '', '42', '1', '3', '1', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75', '5,6', '5,6', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '70', NULL, '2025-01-07 22:35:13', '2025-04-25 17:18:56'),
(41, NULL, NULL, 'Kathiravan K', NULL, '', 'kathiravan k', 'kath19991006', '3', '2', '', '', '43', '1', '1', '1', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,81,83,84', '1,2,3', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-07 22:37:35', '2025-05-26 12:28:33'),
(42, NULL, NULL, 'Siva S', NULL, '', 'siva s', 'siva19906380', '3', '2', '', '', '17', '1', '4', '1,2,3,4,7,8,9', '4', '4,11,12', '4,11,12', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '0', '1', '1', '1', '1', '1', '0', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '70', NULL, '2025-01-08 07:48:12', '2025-03-11 14:55:39'),
(44, NULL, NULL, 'Manikandan K', NULL, '', 'ManikandanK', 'Mani19929342', '3', '2', '', '', '55', '1', '2', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,85', '7,8,13', '7,8,13', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-08 10:01:09', '2025-06-14 18:46:10'),
(45, NULL, NULL, 'Jayaseelan R', NULL, '', 'jayaseelan r', 'jaya19968807', '3', '2', '', '', '54', '1', '3', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75', '6', '6', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-08 10:04:32', '2025-05-03 19:31:48'),
(46, NULL, NULL, 'Parthiban R', NULL, '', 'parthiban r', 'part19959944', '3', '2', '', '', '52', '1', '4', '1,2,3,4', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75', '4,11,12', '4,11,12', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-08 10:07:48', '2025-05-03 19:30:07'),
(47, NULL, NULL, 'Baskaran K', NULL, '', 'baskaran k', 'bask19929043', '3', '2', '', '', '51', '1', '4', '1,2,3,4,6,7,8,9', '', '4,11,12', '4,11,12', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-08 10:12:06', '2025-05-03 19:30:59'),
(48, NULL, NULL, 'Ezhil Kumar E', NULL, '', 'ezhilkumar e', 'ezhi19939042', '3', '2', '', '', '50', '1', '3', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75', '5', '5', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-08 10:15:32', '2025-05-03 19:25:59'),
(49, NULL, NULL, 'Thamizharasan K', NULL, '', 'tamilarasan k', 'tami19839597', '3', '2', '', '', '49', '1', '1', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,81,83,84', '2', '2', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-08 10:19:29', '2025-05-26 12:28:10'),
(50, NULL, NULL, 'Vignesh M', NULL, '', 'vignesh m', 'vign19967639', '3', '2', '', '', '47', '1', '2', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,85', '7,8,13', '7,8,13', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-08 10:26:30', '2025-06-14 18:44:48'),
(51, NULL, NULL, 'Vijayan S', NULL, '', 'vijayans', 'vija19897010', '3', '2', '', '', '46', '1', '1', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77', '3', '3', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-08 10:29:16', '2025-05-03 19:20:16'),
(52, NULL, NULL, 'Harishankar K', NULL, '', 'harishankar k', 'hari19939944', '3', '2', '', '', '45', '1', '2', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75', '7,13', '7,13', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '1', '2', '2', '70', '2025-01-08 10:32:08', '2025-05-03 19:19:44'),
(53, NULL, NULL, 'Chinnadurai V', NULL, '', 'chinnadurai v', 'chin20259080', '3', '2', '', '', '48', '1', '1', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77', '1', '1', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-08 10:35:41', '2025-05-03 19:26:49'),
(54, NULL, NULL, 'Parthiban C', NULL, '', 'parthiban c', 'part19909944', '3', '2', '', '', '59', '1', '2', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,85', '8,13', '8,13', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-08 10:53:35', '2025-06-14 18:34:42'),
(55, NULL, NULL, 'Manikandan D', NULL, '', 'manikandan d', 'mani19907504', '3', '1', '', '', '56', '1', '1,2', '1,2,3,4,6,7,8,9', '', '1,2,3,7,8,9,10,13,14', '1,2,3,7,8,9,10,13', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '70', NULL, '2025-01-08 11:56:11', '2025-05-27 15:44:43'),
(56, NULL, NULL, 'Prakash P', NULL, '', 'prakash p', 'prak19862229', '3', '2', '', '', '29', '1', '1', '3,7', '', '1,2,3', '1,2,3', '1', '1', '2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '70', NULL, '2025-01-08 11:59:34', '2025-03-05 19:18:22'),
(57, NULL, NULL, 'Arun Kumar V', NULL, '', 'arunkumar v', 'arun19959952', '3', '2', '', '', '32', '1', '2', '3,7', '', '7,8,13', '7,8,13', '1', '1', '2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '70', NULL, '2025-01-08 12:02:31', '2025-03-05 19:15:36'),
(58, NULL, NULL, 'Rajasekar E', NULL, '', 'rajasekar e', 'raja19836883', '3', '2', '', '', '39', '1', '3', '3,7', '', '5,6', '5,6', '1', '1', '2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '70', NULL, '2025-01-08 12:04:38', '2025-03-05 19:16:15'),
(59, NULL, NULL, 'Vinothkuamr A', NULL, '', 'vinothkumar a', 'vino19929003', '3', '1', '', '', '27', '1', '3,4', '1,2,3,4,6,7,8,9', '12', '4,5,6,11,12', '4,5,6,11,12', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '70', NULL, '2025-01-08 12:06:40', '2025-05-26 16:03:16'),
(60, NULL, NULL, 'Prabakaran P', NULL, '', 'prabha', 'prabha$1356', '1', '12', '1', '', '', '1', '1,2,3,4', '', '', '1,2,3,4,5,6,7,8,11,12,13', '1,2,3,4,5,6,7,8,11,12,13', '0', '2', '1,2,3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '0', '1', '0', '0', NULL, '0', '0', '0', '0', '0', '0', '', '0', '0', '0', '1', '0', '0', '1', '0', '0', '', '0', '1', '0', '0', '0', '0', '0', 0, '0', '0', 0, '0', '0', '0', 0, NULL, '0', '0', '1', '1', '0', '0', 1, 1, '0', '2', '60', NULL, '2025-01-08 13:27:11', '2025-05-21 13:59:35'),
(61, NULL, NULL, 'Kathiravan E', NULL, '', 'kathiravan e', 'kath19928101', '3', '3', '', '', '44', '1', '1,2,3,4,5', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '1,2,3,4,5,6,7,8,9,10,11,12,13', '0', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '0', '1', '1', '1', '1', '1', '1', '0', '0', '0', '0', '0', '0', '0', '1', '1', '1', '1', '1', '0', '0', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '0', '1', '1', '1', '1', '1', '0', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-08 14:18:59', '2025-06-14 18:33:35'),
(62, NULL, NULL, 'Prakash Kumar J', NULL, '', 'prakashkumar j', 'prak19923003', '3', '3', '', '', '26', '1', '1,2,3,4', '1,2,3', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '1,2,3,4,5,6,7,8,9,10,11,12,13', '0', '2', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '1', '', '0', '1', '0', '0', '1', '0', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '0', '0', 1, 1, '0', '2', '2', NULL, '2025-01-08 14:21:59', '2025-06-14 18:33:16'),
(63, NULL, NULL, 'Dinesh K', NULL, '', 'dinesh k', 'dine19949820', '3', '1', '', '', '60', '1', '1,2,3,4', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1,2,3,4,5,6,7,8,9,10,11,12,13', '0', '2', '2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '0', '0', '2,3', '1', '0', '1', '0', '1', '1', '1', '1', '1', '', '0', '1', '1', '1', '1', '0', '0', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-01-17 18:08:07', '2025-06-14 18:32:56'),
(64, NULL, NULL, 'Verifi Kathir', NULL, '', 'kathirapp', '4545', '3', '5', '', '', '61', '1', '1', '1', '', '1,2,3', '1,2,3', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '0', '0', 1, 1, '1', '2', '2', '70', '2025-01-18 18:47:50', '2025-02-04 18:42:45');
INSERT INTO `user` (`user_id`, `firstname`, `lastname`, `fullname`, `title`, `emailid`, `user_name`, `user_password`, `role`, `role_type`, `dir_id`, `ag_id`, `staff_id`, `company_id`, `branch_id`, `loan_cat`, `agentforstaff`, `line_id`, `group_id`, `download_access`, `report_access`, `pro_aty_access`, `mastermodule`, `company_creation`, `branch_creation`, `loan_category`, `loan_calculation`, `loan_scheme`, `area_creation`, `area_mapping`, `area_approval`, `adminmodule`, `director_creation`, `agent_creation`, `staff_creation`, `manage_user`, `doc_mapping`, `bank_creation`, `requestmodule`, `request`, `request_list_access`, `verificationmodule`, `verification`, `approvalmodule`, `approval`, `acknowledgementmodule`, `acknowledgement`, `loanissuemodule`, `loan_issue`, `collectionmodule`, `collection`, `collection_access`, `closedmodule`, `closed`, `nocmodule`, `noc`, `doctrackmodule`, `doctrack`, `doc_rec_access`, `updatemodule`, `update_screen`, `update_screen_id`, `concernmodule`, `concern_creation`, `concern_solution`, `concern_feedback`, `accountsmodule`, `cash_tally`, `bank_details`, `cash_tally_admin`, `bank_clearance`, `finance_insight`, `accounts_loan_issue`, `followupmodule`, `promotion_activity`, `loan_followup`, `confirmation_followup`, `due_followup`, `due_followup_lines`, `reportmodule`, `ledger_report`, `request_report`, `cancel_revoke_report`, `cus_profile_report`, `loan_issue_report`, `collection_report`, `principal_interest_report`, `balance_report`, `due_list_report`, `in_closed_report`, `closed_report`, `confirmation_followup_report`, `agent_report`, `no_due_pay_report`, `other_trans_report`, `search_module`, `search`, `bulk_upload_module`, `bulk_upload`, `loan_track_module`, `loan_track`, `sms_module`, `sms_generation`, `status`, `insert_login_id`, `update_login_id`, `delete_login_id`, `created_date`, `updated_date`) VALUES
(65, NULL, NULL, 'Verifi Loke', NULL, '', 'verifyloke', '5656', '3', '5', '', '', '63', '1', '1', '1,3', '', '1,2,3', '1,2,3', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '1', '2', '70', '70', '2025-01-18 18:55:25', '2025-03-01 16:29:34'),
(66, NULL, NULL, 'Verifi diva', NULL, '', 'divaapp', '9889', '3', '5', '', '', '62', '1', '1', '1', '', '1,2,3', '1,2,3', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '1', '2', '60', '70', '2025-01-18 18:57:19', '2025-01-19 12:28:52'),
(67, NULL, NULL, 'Verifi raw', NULL, '', 'rawapp', '0909', '3', '5', '', '', '64', '1', '3', '1', '', '5,6', '5,6', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '1', '2', NULL, '70', '2025-01-18 19:02:31', '2025-01-18 19:02:31'),
(68, NULL, NULL, 'Suresh G', NULL, '', 'suresh', 'suresh0208', '1', '12', '2', '', '', '1', '1,2,3,4,5', '', '', '1,2,3,4,5,6,7,8,11,12,13', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1', '1', '1,2,3', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0', NULL, '1', '1', '1', '1', '0', '0', '2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '0', '1', '0', '1', '1', '0', '0', 1, '0', '1', 1, '0', '1', '1', 1, NULL, '0', '0', '1', '1', '0', '0', 1, 1, '0', '2', '70', NULL, '2025-01-20 12:13:57', '2025-06-01 14:58:57'),
(69, NULL, NULL, 'Prabhakaran J', NULL, '', 'prabha j', 'prab19949500', '3', '2', '', '', '16', '1', '1,2,3,4,5', '1,2,3', '2,7,8,9,10,11,12,13,15,17,19,21,22,24,26,27,28,32,33,34,36,38,39,41,43,44,45,46,47,48,51,52,54,55,60,64,66,76', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1,2,3,4,5,6,7,8,9,10,11,12,13', '0', '2', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '0', '0', '2,3', '1', '0', '1', '1', '1', '1', '1', '1', '1', NULL, '0', '1', '1', '1', '1', '1', '0', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-02-06 11:09:43', '2025-02-18 20:00:51'),
(70, NULL, NULL, 'Naren', NULL, '', 'Naren', '2020', '3', '7', '', '', '1', '1', '1,2,3,4,5', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1,2,3,4,5,6,7,8,9,10,11,12,13', '0', '2', '1,2,3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0', '1,2', '0', '0', '0', '0', '0', '0', '2,3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '12', '0', '0', '0', '0', '0', '0', '0', 0, '0', '0', 1, '0', '0', '0', 1, NULL, '0', '0', '0', '0', '0', '0', 0, 0, '0', '2', '70', NULL, '2025-02-13 19:02:07', '2025-05-31 19:36:53'),
(71, NULL, NULL, 'Santhosh', NULL, '', 'Santhosh', '1993', '3', '5', '', '', '65', '1', '1,2,3,4,5', '1,2,3,7', '2', '1,2,3,4,5,6,7,8,9,10,11,12,13', '1,2,3,4,5,6,7,8,9,10,11,12,13', '0', '2', '1,2,3', '0', '1', '1', '1', '1', '1', '0', '0', '1', '0', '1', '0', '0', '0', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '0', '0', '12', '0', '0', '0', '0', '0', '0', '0', 0, '0', '0', 0, '0', '0', '0', 0, NULL, '0', '0', '1', '1', '0', '0', 1, 1, '0', '2', '2', NULL, '2025-02-14 19:26:55', '2025-05-29 15:41:16'),
(72, NULL, NULL, 'Logeshwaran R', NULL, '', 'proloke', '12341234', '3', '2', '', '', '40', '1', '1', '1,2,3,4,6,7,8,9,10', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77', '1', '1', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '1', '2', NULL, '60', '2025-02-15 19:28:37', '2025-02-15 19:28:37'),
(73, NULL, NULL, 'Dinesh M', NULL, '', 'prodine', '12341234', '3', '2', '', '', '38', '1', '1', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,83', '2', '2', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-02-15 19:30:36', '2025-05-21 14:26:21'),
(74, NULL, NULL, 'Kathiravan K', NULL, '', 'prokathir', '12341234', '3', '2', '', '', '43', '1', '1', '1,2,3,4,6,7,8,9', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,71,72,73,74,75,76,77,81,83', '3', '3', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-02-15 19:32:47', '2025-05-21 14:23:26'),
(75, NULL, NULL, 'Sathishkumar R', NULL, '', 'prosat', '12341234', '3', '2', '', '', '35', '1', '2', '1,2,3', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,85', '7', '7', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '70', '2', NULL, '2025-02-19 11:36:37', '2025-06-14 18:32:22'),
(76, NULL, NULL, 'JohnPaul P', NULL, '', 'projp', '12341234', '3', '2', '', '', '34', '1', '1', '1,2,3', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,83', '1', '1', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '70', '2', '70', '2025-02-19 11:41:29', '2025-05-21 14:22:57'),
(77, NULL, NULL, 'Deepak E', NULL, '', 'prodeep', '12341234', '3', '1', '', '', '28', '1', '2', '1,2,3', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,85', '13', '13', '1', '', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '70', '2', NULL, '2025-02-19 11:43:52', '2025-06-14 18:31:56'),
(78, NULL, NULL, 'KrishnaRaw S', NULL, '', 'prokrish', '12341234', '3', '2', '', '', '37', '1', '3', '1,2,3', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77', '5', '5', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '70', NULL, NULL, '2025-02-19 11:46:54', '2025-02-19 11:46:54'),
(79, NULL, NULL, 'Madhan Kumar L', NULL, '', 'promaddy', '12341234', '3', '2', '', '', '42', '1', '3', '1,2,3', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77', '6', '6', '1', '1', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '70', NULL, NULL, '2025-02-19 11:48:26', '2025-02-19 11:48:26'),
(80, NULL, NULL, 'Naren', NULL, '', 'fua1app', '123', '3', '7', '', '', '1', '1', '1', '1,3', '2', '1', '1', '1', '', '1,2,3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '1', '1', '1', '0', NULL, '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '1', '1', '1', '1', '1', '1', 1, 1, '0', '70', NULL, NULL, '2025-03-01 13:28:09', '2025-03-01 13:28:09'),
(81, NULL, NULL, 'Rajeshwari S', NULL, '', 'rajeshwari S', 'raje20019895', '3', '6', '', '', '66', '1', '2', '1,2,3,7', '', '7,8,13', '7,8,13', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '0', '0', '9,11', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '70', '60', NULL, '2025-03-20 11:11:43', '2025-05-05 10:35:04'),
(82, NULL, NULL, 'Deepak Mobiles', NULL, 'DeepakMobiles@gmail.com', 'Deepak', 'deepak@123', '2', '', '', '12', '', '1', '3', '', '', '5,6', '5,6', '1', '1', '2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '0', '1', '1', '1', '1', '1', '0', 1, '1', '1', 1, '1', '1', '1', NULL, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '2', '2', NULL, '2025-04-01 17:44:00', '2025-04-01 18:09:40'),
(83, NULL, NULL, 'Uma Mageshwari J', NULL, '', 'Verifybo', 'backoffc123', '3', '6', '', '', '7', '1', '1', '1', '2,13,14,15,17,19,21,22,25,27,28,33,36,37,39,46,48,50,52,60,61,62,63,83', '1,2,3', '1,2,3', '1', '1', '2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '0', '0', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '70', '2', NULL, '2025-04-25 13:27:17', '2025-05-21 14:21:09'),
(84, NULL, NULL, 'Dhanalakshmi R', NULL, 'rddhanalakshmi160@gmail.com', 'dhanalakshmi r', 'dhan20038925', '3', '6', '', '', '69', '1', '1', '1,2,3', '', '1', '1', '1', '1', '2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '1', '1', '1', '0', '12,21', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '70', '70', NULL, '2025-05-05 16:15:52', '2025-05-05 16:32:02'),
(85, NULL, NULL, 'Kaviyarasi AR', NULL, 'kaviar1991@gmail.com', 'kaviyarasi ar', 'kavi19913755', '3', '6', '', '', '67', '1', '1', '1,2,3', '', '3', '3', '1', '1', '2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '1', '1', '1', '0', '15,19', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '70', '70', NULL, '2025-05-05 16:19:43', '2025-05-05 16:22:15'),
(86, NULL, NULL, 'Deepika AJ', NULL, 'deepijoe2001@gmail.com', 'deepika aj', 'deep20019915', '3', '6', '', '', '68', '1', '2', '1,2,3', '', '7', '7', '1', '1', '2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '1', '1', '1', '0', '18,23', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '70', '70', NULL, '2025-05-05 16:25:10', '2025-05-05 16:32:45'),
(87, NULL, NULL, 'Pravin S', NULL, '', 'pravin s', 'prav19991245', '3', '2', '', '', '70', '1', '2', '1', '3,4,5,6,7,9,13,16,17,24,30,31,38,40,41,43,44,46,49,50,51,54,57,58,59,61,64,66,67,68,70,72,73,74,77,79,80,81,85', '7,8,9,10,13', '7,8,9,10,13', '1', '1', '1,2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '70', '2', NULL, '2025-05-12 15:44:27', '2025-06-14 18:31:15'),
(88, NULL, NULL, 'Pravin S', NULL, '', 'propravin', 'pravin1234', '3', '2', '', '', '70', '1', '2', '1,2,3', '1,3,4,5,7,8,9,16,24,29,30,31,38,40,41,42,43,44,49,51,54,55,56,58,59,64,66,67,68,72,73,76,77,78,79,81,85', '8', '8', '1', '1', '1,2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '70', '2', NULL, '2025-05-12 15:53:05', '2025-06-14 18:30:49'),
(89, NULL, NULL, 'Jeevanraj M', NULL, 'jeevaviji774@gmail.com', 'jeevan m', 'jeev19926377', '3', '2', '', '', '71', '1', '1', '1,2,3,4,7', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84', '1,2,3,14', '1,2,3', '1', '1', '1,2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '0', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '', '2', NULL, '2025-05-24 18:57:36', '2025-05-26 12:24:39'),
(90, NULL, NULL, 'Arunkumar P', NULL, '', 'arunkumar p', 'arun19951088', '3', '2', '', '', '72', '1', '2', '1,2,3,7', '4,5,24,30,43,44,49,54,64,67,85', '7', '7', '1', '1', '2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '0', '1', '1', '1', '0', '', '0', '1', '1', '1', '1', '1', '0', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '70', '2', NULL, '2025-06-01 10:54:55', '2025-06-14 18:30:24'),
(91, NULL, NULL, 'Govindaraj J', NULL, '', 'govindaraj j', 'govi19989500', '3', '2', '', '', '73', '1', '1', '1,2,3,7', '2', '1,2,3,14', '1,2,3', '1', '1', '2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '0', '1', '1', '1', '1', '1', '0', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '70', '70', NULL, '2025-06-01 12:17:40', '2025-06-03 12:35:56'),
(92, NULL, NULL, 'KrishnaRaw S', NULL, '', 'collkrishna', 'kris123', '3', '2', '', '', '37', '1', '1,2,3,4', '1,2,3,7', '32', '1,2,3,4,5,6,7,8,9,10,11,12,13,14', '1,2,3,4,5,6,7,8,9,10,11,12,13', '0', '1', '2', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', NULL, '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', '1', '1', '', '1', '1', '1', '1', '1', '1', '1', 1, '1', '1', 1, '1', '1', '1', 1, NULL, '0', '0', '1', '1', '1', '1', 1, 1, '0', '70', '70', NULL, '2025-06-07 17:51:29', '2025-06-07 17:52:37');

-- --------------------------------------------------------

--
-- Table structure for table `verification_bank_info`
--

CREATE TABLE `verification_bank_info` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(250) DEFAULT NULL,
  `req_id` int(11) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `acc_holder_name` varchar(100) NOT NULL,
  `acc_no` bigint(20) NOT NULL,
  `ifsc_code` varchar(50) NOT NULL,
  `upload` varchar(100) DEFAULT NULL,
  `issue_status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `verification_cus_feedback`
--

CREATE TABLE `verification_cus_feedback` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(250) DEFAULT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `feedback_label` varchar(255) DEFAULT NULL,
  `cus_feedback` varchar(255) DEFAULT NULL,
  `feedback_remark` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `verification_documentation`
--

CREATE TABLE `verification_documentation` (
  `id` int(11) NOT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `cus_id_doc` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `cus_profile_id` varchar(255) DEFAULT NULL,
  `doc_id` varchar(255) DEFAULT NULL,
  `mortgage_process` varchar(100) DEFAULT NULL,
  `Propertyholder_type` varchar(100) DEFAULT NULL,
  `Propertyholder_name` varchar(255) DEFAULT NULL,
  `Propertyholder_relationship_name` varchar(100) DEFAULT NULL,
  `doc_property_relation` varchar(100) DEFAULT NULL,
  `doc_property_type` varchar(255) DEFAULT NULL,
  `doc_property_measurement` varchar(255) DEFAULT NULL,
  `doc_property_location` varchar(255) DEFAULT NULL,
  `doc_property_value` varchar(255) DEFAULT NULL,
  `mortgage_name` varchar(255) DEFAULT NULL,
  `mortgage_dsgn` varchar(255) DEFAULT NULL,
  `mortgage_nuumber` varchar(255) DEFAULT NULL,
  `reg_office` varchar(255) DEFAULT NULL,
  `mortgage_value` varchar(255) DEFAULT NULL,
  `mortgage_document` varchar(255) DEFAULT NULL,
  `mortgage_document_upd` varchar(255) DEFAULT NULL,
  `mortgage_document_pending` varchar(150) DEFAULT NULL,
  `endorsement_process` varchar(50) DEFAULT NULL,
  `owner_type` varchar(100) DEFAULT NULL,
  `owner_name` varchar(200) DEFAULT NULL,
  `ownername_relationship_name` varchar(100) DEFAULT NULL,
  `en_relation` varchar(100) DEFAULT NULL,
  `vehicle_type` varchar(50) DEFAULT NULL,
  `vehicle_process` varchar(50) DEFAULT NULL,
  `en_Company` varchar(200) DEFAULT NULL,
  `en_Model` varchar(200) DEFAULT NULL,
  `vehicle_reg_no` varchar(150) DEFAULT NULL,
  `endorsement_name` varchar(255) DEFAULT NULL,
  `en_RC` varchar(50) DEFAULT NULL,
  `Rc_document_upd` varchar(255) DEFAULT NULL,
  `Rc_document_pending` varchar(150) DEFAULT NULL,
  `en_Key` varchar(50) DEFAULT NULL,
  `gold_info` varchar(50) DEFAULT NULL,
  `gold_sts` varchar(50) DEFAULT NULL,
  `gold_type` varchar(255) DEFAULT NULL,
  `Purity` varchar(255) DEFAULT NULL,
  `gold_Count` varchar(255) DEFAULT NULL,
  `gold_Weight` varchar(255) DEFAULT NULL,
  `gold_Value` varchar(255) DEFAULT NULL,
  `document_name` varchar(255) DEFAULT NULL,
  `document_details` varchar(255) DEFAULT NULL,
  `document_type` varchar(50) DEFAULT NULL,
  `document_holder` varchar(50) DEFAULT NULL,
  `docholder_name` varchar(255) DEFAULT NULL,
  `docholder_relationship_name` varchar(50) DEFAULT NULL,
  `doc_relation` varchar(50) DEFAULT NULL,
  `cus_status` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `insert_login_id` varchar(50) DEFAULT NULL,
  `update_login_id` varchar(50) DEFAULT NULL,
  `delete_login_id` varchar(50) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `verification_family_info`
--

CREATE TABLE `verification_family_info` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(100) DEFAULT NULL,
  `req_id` int(11) DEFAULT NULL,
  `famname` varchar(100) DEFAULT NULL,
  `relationship` text DEFAULT NULL,
  `other_remark` varchar(100) DEFAULT NULL,
  `other_address` varchar(200) DEFAULT NULL,
  `relation_age` varchar(50) DEFAULT NULL,
  `relation_aadhar` varchar(50) DEFAULT NULL,
  `relation_Mobile` double DEFAULT NULL,
  `relation_Occupation` text DEFAULT NULL,
  `relation_Income` varchar(50) DEFAULT NULL,
  `relation_Blood` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `verification_group_info`
--

CREATE TABLE `verification_group_info` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(250) DEFAULT NULL,
  `req_id` int(11) NOT NULL,
  `group_name` varchar(50) NOT NULL,
  `group_age` int(11) NOT NULL,
  `group_aadhar` text NOT NULL,
  `group_mobile` double NOT NULL,
  `group_gender` text NOT NULL,
  `group_designation` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `verification_kyc_info`
--

CREATE TABLE `verification_kyc_info` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(250) DEFAULT NULL,
  `req_id` int(11) NOT NULL,
  `proofOf` varchar(50) NOT NULL,
  `fam_mem` varchar(255) NOT NULL,
  `guarantor_mem` varchar(100) DEFAULT NULL,
  `proof_type` varchar(50) NOT NULL,
  `proof_no` varchar(50) NOT NULL,
  `upload` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `verification_loan_calculation`
--

CREATE TABLE `verification_loan_calculation` (
  `loan_cal_id` int(11) NOT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `cus_id_loan` varchar(255) DEFAULT NULL,
  `cus_name_loan` varchar(255) DEFAULT NULL,
  `cus_data_loan` varchar(255) DEFAULT NULL,
  `mobile_loan` varchar(255) DEFAULT NULL,
  `pic_loan` varchar(255) DEFAULT NULL,
  `loan_category` varchar(255) DEFAULT NULL,
  `sub_category` varchar(255) DEFAULT NULL,
  `tot_value` varchar(255) DEFAULT NULL,
  `ad_amt` varchar(255) DEFAULT NULL,
  `loan_amt` varchar(255) DEFAULT NULL,
  `profit_type` varchar(255) DEFAULT NULL,
  `due_method_calc` varchar(255) DEFAULT NULL,
  `due_type` varchar(255) DEFAULT NULL,
  `profit_method` varchar(255) DEFAULT NULL,
  `calc_method` varchar(255) DEFAULT NULL,
  `due_method_scheme` varchar(255) DEFAULT NULL,
  `profit_method_scheme` varchar(100) DEFAULT NULL,
  `day_scheme` varchar(255) DEFAULT NULL,
  `scheme_name` varchar(255) DEFAULT NULL,
  `int_rate` varchar(255) DEFAULT NULL,
  `due_period` varchar(255) DEFAULT NULL,
  `doc_charge` varchar(255) DEFAULT NULL,
  `proc_fee` varchar(255) DEFAULT NULL,
  `loan_amt_cal` varchar(255) DEFAULT NULL,
  `principal_amt_cal` varchar(255) DEFAULT NULL,
  `int_amt_cal` varchar(255) DEFAULT NULL,
  `tot_amt_cal` varchar(255) DEFAULT NULL,
  `due_amt_cal` varchar(255) DEFAULT NULL,
  `doc_charge_cal` varchar(255) DEFAULT NULL,
  `proc_fee_cal` varchar(255) DEFAULT NULL,
  `net_cash_cal` varchar(255) DEFAULT NULL,
  `due_start_from` varchar(255) DEFAULT NULL,
  `maturity_month` varchar(255) DEFAULT NULL,
  `collection_method` varchar(255) DEFAULT NULL,
  `communication` varchar(50) DEFAULT NULL,
  `com_audio` varchar(255) DEFAULT NULL,
  `verification_person` varchar(255) DEFAULT NULL,
  `verification_location` varchar(255) DEFAULT NULL,
  `verify_remark` varchar(255) DEFAULT NULL,
  `cus_status` varchar(255) DEFAULT NULL,
  `insert_login_id` varchar(255) DEFAULT NULL,
  `update_login_id` varchar(255) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `verification_property_info`
--

CREATE TABLE `verification_property_info` (
  `id` int(11) NOT NULL,
  `cus_id` varchar(250) DEFAULT NULL,
  `req_id` int(11) NOT NULL,
  `property_type` varchar(50) NOT NULL,
  `property_measurement` varchar(50) NOT NULL,
  `property_value` varchar(50) NOT NULL,
  `property_holder` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `verif_loan_cal_category`
--

CREATE TABLE `verif_loan_cal_category` (
  `cat_id` int(11) NOT NULL,
  `req_id` varchar(255) DEFAULT NULL,
  `loan_cal_id` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acknowledgement_loan_cal_category`
--
ALTER TABLE `acknowledgement_loan_cal_category`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `acknowlegement_customer_profile`
--
ALTER TABLE `acknowlegement_customer_profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_acknowlegement_customer_profile` (`req_id`,`area_confirm_subarea`,`area_confirm_area`) USING BTREE,
  ADD KEY `idx_cus_id` (`cus_id`);

--
-- Indexes for table `acknowlegement_documentation`
--
ALTER TABLE `acknowlegement_documentation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acknowlegement_loan_calculation`
--
ALTER TABLE `acknowlegement_loan_calculation`
  ADD PRIMARY KEY (`loan_cal_id`),
  ADD KEY `idx_req_id` (`req_id`,`cus_id_loan`);

--
-- Indexes for table `agent_communication_details`
--
ALTER TABLE `agent_communication_details`
  ADD PRIMARY KEY (`comm_id`);

--
-- Indexes for table `agent_creation`
--
ALTER TABLE `agent_creation`
  ADD PRIMARY KEY (`ag_id`);

--
-- Indexes for table `agent_group_creation`
--
ALTER TABLE `agent_group_creation`
  ADD PRIMARY KEY (`agent_group_id`);

--
-- Indexes for table `area_creation`
--
ALTER TABLE `area_creation`
  ADD PRIMARY KEY (`area_creation_id`);

--
-- Indexes for table `area_duefollowup_mapping`
--
ALTER TABLE `area_duefollowup_mapping`
  ADD PRIMARY KEY (`map_id`);

--
-- Indexes for table `area_group_mapping`
--
ALTER TABLE `area_group_mapping`
  ADD PRIMARY KEY (`map_id`),
  ADD KEY `idx_area_group_mapping` (`map_id`,`branch_id`);

--
-- Indexes for table `area_line_mapping`
--
ALTER TABLE `area_line_mapping`
  ADD PRIMARY KEY (`map_id`),
  ADD KEY `idx_line_name` (`map_id`,`line_name`);

--
-- Indexes for table `area_list_creation`
--
ALTER TABLE `area_list_creation`
  ADD PRIMARY KEY (`area_id`),
  ADD KEY `idx_area_list_creation` (`area_id`);

--
-- Indexes for table `bank_creation`
--
ALTER TABLE `bank_creation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_stmt`
--
ALTER TABLE `bank_stmt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch_creation`
--
ALTER TABLE `branch_creation`
  ADD PRIMARY KEY (`branch_id`);

--
-- Indexes for table `cash_tally`
--
ALTER TABLE `cash_tally`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cash_tally_modes`
--
ALTER TABLE `cash_tally_modes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cheque_info`
--
ALTER TABLE `cheque_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cheque_no_list`
--
ALTER TABLE `cheque_no_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cheque_upd`
--
ALTER TABLE `cheque_upd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `closed_status`
--
ALTER TABLE `closed_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `req_id` (`req_id`),
  ADD KEY `cus_id` (`req_id`,`cus_id`,`closed_sts`,`consider_level`,`cus_sts`,`created_date`) USING BTREE;

--
-- Indexes for table `closing_customer`
--
ALTER TABLE `closing_customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `req_uni` (`req_id`),
  ADD KEY `req_id` (`req_id`,`cus_id`,`closing_date`);

--
-- Indexes for table `collection`
--
ALTER TABLE `collection`
  ADD PRIMARY KEY (`coll_id`),
  ADD UNIQUE KEY `coll_code` (`coll_code`),
  ADD KEY `idx_collection` (`req_id`,`cus_id`,`coll_date`,`trans_date`,`coll_id`);

--
-- Indexes for table `collection_charges`
--
ALTER TABLE `collection_charges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commitment`
--
ALTER TABLE `commitment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_commitment` (`req_id`,`cus_id`,`comm_date`) USING BTREE;

--
-- Indexes for table `company_creation`
--
ALTER TABLE `company_creation`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `concern_creation`
--
ALTER TABLE `concern_creation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `concern_subject`
--
ALTER TABLE `concern_subject`
  ADD PRIMARY KEY (`concern_sub_id`);

--
-- Indexes for table `confirmation_followup`
--
ALTER TABLE `confirmation_followup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_bank_collection`
--
ALTER TABLE `ct_bank_collection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_cr_bag`
--
ALTER TABLE `ct_cr_bag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_cr_bank_withdraw`
--
ALTER TABLE `ct_cr_bank_withdraw`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_cr_bdeposit`
--
ALTER TABLE `ct_cr_bdeposit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_cr_bel`
--
ALTER TABLE `ct_cr_bel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_cr_bexchange`
--
ALTER TABLE `ct_cr_bexchange`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_cr_binvest`
--
ALTER TABLE `ct_cr_binvest`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_cr_boti`
--
ALTER TABLE `ct_cr_boti`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_cr_cash_deposit`
--
ALTER TABLE `ct_cr_cash_deposit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_cr_hag`
--
ALTER TABLE `ct_cr_hag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_cr_hdeposit`
--
ALTER TABLE `ct_cr_hdeposit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_cr_hel`
--
ALTER TABLE `ct_cr_hel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_cr_hexchange`
--
ALTER TABLE `ct_cr_hexchange`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_cr_hinvest`
--
ALTER TABLE `ct_cr_hinvest`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_cr_hoti`
--
ALTER TABLE `ct_cr_hoti`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_db_bag`
--
ALTER TABLE `ct_db_bag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_db_bank_deposit`
--
ALTER TABLE `ct_db_bank_deposit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_db_bdeposit`
--
ALTER TABLE `ct_db_bdeposit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_db_bel`
--
ALTER TABLE `ct_db_bel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_db_bexchange`
--
ALTER TABLE `ct_db_bexchange`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_db_bexpense`
--
ALTER TABLE `ct_db_bexpense`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_db_binvest`
--
ALTER TABLE `ct_db_binvest`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_db_bissued`
--
ALTER TABLE `ct_db_bissued`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_db_cash_withdraw`
--
ALTER TABLE `ct_db_cash_withdraw`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_db_exf`
--
ALTER TABLE `ct_db_exf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_db_hag`
--
ALTER TABLE `ct_db_hag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_db_hdeposit`
--
ALTER TABLE `ct_db_hdeposit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_db_hel`
--
ALTER TABLE `ct_db_hel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_db_hexchange`
--
ALTER TABLE `ct_db_hexchange`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_db_hexpense`
--
ALTER TABLE `ct_db_hexpense`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_db_hinvest`
--
ALTER TABLE `ct_db_hinvest`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_db_hissued`
--
ALTER TABLE `ct_db_hissued`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_hand_collection`
--
ALTER TABLE `ct_hand_collection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_profile`
--
ALTER TABLE `customer_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_register`
--
ALTER TABLE `customer_register`
  ADD PRIMARY KEY (`cus_reg_id`);

--
-- Indexes for table `customer_status`
--
ALTER TABLE `customer_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_req_id` (`req_id`) USING BTREE,
  ADD KEY `idx_cus_id` (`cus_id`,`payable_amnt`,`bal_amnt`,`sub_status`) USING BTREE;

--
-- Indexes for table `cus_old_data`
--
ALTER TABLE `cus_old_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `director_creation`
--
ALTER TABLE `director_creation`
  ADD PRIMARY KEY (`dir_id`);

--
-- Indexes for table `document_info`
--
ALTER TABLE `document_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_track`
--
ALTER TABLE `document_track`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doc_mapping`
--
ALTER TABLE `doc_mapping`
  ADD PRIMARY KEY (`doc_map_id`);

--
-- Indexes for table `expense_category`
--
ALTER TABLE `expense_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fingerprints`
--
ALTER TABLE `fingerprints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gold_info`
--
ALTER TABLE `gold_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `holiday_creation`
--
ALTER TABLE `holiday_creation`
  ADD PRIMARY KEY (`holiday_id`);

--
-- Indexes for table `in_acknowledgement`
--
ALTER TABLE `in_acknowledgement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `in_issue`
--
ALTER TABLE `in_issue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_loanid_reqid_cusid` (`loan_id`,`req_id`,`cus_id`),
  ADD KEY `idx_cus_status` (`status`,`cus_status`);

--
-- Indexes for table `in_verification`
--
ALTER TABLE `in_verification`
  ADD PRIMARY KEY (`req_id`),
  ADD KEY `idx_req-id_loancat_sts` (`req_id`,`loan_category`,`cus_status`,`cus_id`);

--
-- Indexes for table `loan_calculation`
--
ALTER TABLE `loan_calculation`
  ADD PRIMARY KEY (`loan_cal_id`);

--
-- Indexes for table `loan_category`
--
ALTER TABLE `loan_category`
  ADD PRIMARY KEY (`loan_category_id`),
  ADD KEY `idx_loan_category` (`loan_category_id`,`loan_category_name`);

--
-- Indexes for table `loan_category_creation`
--
ALTER TABLE `loan_category_creation`
  ADD PRIMARY KEY (`loan_category_creation_id`),
  ADD KEY `idx_loan_category_creation` (`loan_category_creation_id`);

--
-- Indexes for table `loan_category_ref`
--
ALTER TABLE `loan_category_ref`
  ADD PRIMARY KEY (`loan_category_ref_id`);

--
-- Indexes for table `loan_followup`
--
ALTER TABLE `loan_followup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_issue`
--
ALTER TABLE `loan_issue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_loan_issue` (`req_id`) USING BTREE;

--
-- Indexes for table `loan_scheme`
--
ALTER TABLE `loan_scheme`
  ADD PRIMARY KEY (`scheme_id`);

--
-- Indexes for table `loan_summary_feedback`
--
ALTER TABLE `loan_summary_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `name_detail_creation`
--
ALTER TABLE `name_detail_creation`
  ADD PRIMARY KEY (`name_id`);

--
-- Indexes for table `new_cus_promo`
--
ALTER TABLE `new_cus_promo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `new_promotion`
--
ALTER TABLE `new_promotion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cus_id` (`cus_id`,`follow_date`);

--
-- Indexes for table `noc`
--
ALTER TABLE `noc`
  ADD PRIMARY KEY (`noc_id`);

--
-- Indexes for table `request_category_info`
--
ALTER TABLE `request_category_info`
  ADD PRIMARY KEY (`cat_info`);

--
-- Indexes for table `request_creation`
--
ALTER TABLE `request_creation`
  ADD PRIMARY KEY (`req_id`),
  ADD KEY `idx_request_creation` (`cus_status`,`area`,`sub_area`,`req_id`),
  ADD KEY `idx_cus_id` (`cus_id`);

--
-- Indexes for table `signed_doc`
--
ALTER TABLE `signed_doc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `signed_doc_info`
--
ALTER TABLE `signed_doc_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_creation`
--
ALTER TABLE `staff_creation`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `staff_type_creation`
--
ALTER TABLE `staff_type_creation`
  ADD PRIMARY KEY (`staff_type_id`);

--
-- Indexes for table `sub_area_list_creation`
--
ALTER TABLE `sub_area_list_creation`
  ADD PRIMARY KEY (`sub_area_id`),
  ADD KEY `idx_sub_area_list_creation` (`sub_area_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD KEY `idx_user` (`user_name`,`user_password`,`agentforstaff`),
  ADD KEY `idx_map_ids` (`branch_id`,`loan_cat`,`line_id`);

--
-- Indexes for table `verification_bank_info`
--
ALTER TABLE `verification_bank_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verification_cus_feedback`
--
ALTER TABLE `verification_cus_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verification_documentation`
--
ALTER TABLE `verification_documentation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verification_family_info`
--
ALTER TABLE `verification_family_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verification_group_info`
--
ALTER TABLE `verification_group_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verification_kyc_info`
--
ALTER TABLE `verification_kyc_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verification_loan_calculation`
--
ALTER TABLE `verification_loan_calculation`
  ADD PRIMARY KEY (`loan_cal_id`);

--
-- Indexes for table `verification_property_info`
--
ALTER TABLE `verification_property_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verif_loan_cal_category`
--
ALTER TABLE `verif_loan_cal_category`
  ADD PRIMARY KEY (`cat_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acknowledgement_loan_cal_category`
--
ALTER TABLE `acknowledgement_loan_cal_category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acknowlegement_customer_profile`
--
ALTER TABLE `acknowlegement_customer_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acknowlegement_documentation`
--
ALTER TABLE `acknowlegement_documentation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acknowlegement_loan_calculation`
--
ALTER TABLE `acknowlegement_loan_calculation`
  MODIFY `loan_cal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agent_communication_details`
--
ALTER TABLE `agent_communication_details`
  MODIFY `comm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agent_creation`
--
ALTER TABLE `agent_creation`
  MODIFY `ag_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agent_group_creation`
--
ALTER TABLE `agent_group_creation`
  MODIFY `agent_group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `area_creation`
--
ALTER TABLE `area_creation`
  MODIFY `area_creation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1500;

--
-- AUTO_INCREMENT for table `area_duefollowup_mapping`
--
ALTER TABLE `area_duefollowup_mapping`
  MODIFY `map_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `area_group_mapping`
--
ALTER TABLE `area_group_mapping`
  MODIFY `map_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `area_line_mapping`
--
ALTER TABLE `area_line_mapping`
  MODIFY `map_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `area_list_creation`
--
ALTER TABLE `area_list_creation`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1500;

--
-- AUTO_INCREMENT for table `bank_creation`
--
ALTER TABLE `bank_creation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bank_stmt`
--
ALTER TABLE `bank_stmt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `branch_creation`
--
ALTER TABLE `branch_creation`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cash_tally`
--
ALTER TABLE `cash_tally`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `cash_tally_modes`
--
ALTER TABLE `cash_tally_modes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key', AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `cheque_info`
--
ALTER TABLE `cheque_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cheque_no_list`
--
ALTER TABLE `cheque_no_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cheque_upd`
--
ALTER TABLE `cheque_upd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `closed_status`
--
ALTER TABLE `closed_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `closing_customer`
--
ALTER TABLE `closing_customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collection`
--
ALTER TABLE `collection`
  MODIFY `coll_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `collection_charges`
--
ALTER TABLE `collection_charges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `commitment`
--
ALTER TABLE `commitment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_creation`
--
ALTER TABLE `company_creation`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `concern_creation`
--
ALTER TABLE `concern_creation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `concern_subject`
--
ALTER TABLE `concern_subject`
  MODIFY `concern_sub_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `confirmation_followup`
--
ALTER TABLE `confirmation_followup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_bank_collection`
--
ALTER TABLE `ct_bank_collection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_cr_bag`
--
ALTER TABLE `ct_cr_bag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_cr_bank_withdraw`
--
ALTER TABLE `ct_cr_bank_withdraw`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_cr_bdeposit`
--
ALTER TABLE `ct_cr_bdeposit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_cr_bel`
--
ALTER TABLE `ct_cr_bel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_cr_bexchange`
--
ALTER TABLE `ct_cr_bexchange`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_cr_binvest`
--
ALTER TABLE `ct_cr_binvest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_cr_boti`
--
ALTER TABLE `ct_cr_boti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_cr_cash_deposit`
--
ALTER TABLE `ct_cr_cash_deposit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_cr_hag`
--
ALTER TABLE `ct_cr_hag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_cr_hdeposit`
--
ALTER TABLE `ct_cr_hdeposit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_cr_hel`
--
ALTER TABLE `ct_cr_hel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_cr_hexchange`
--
ALTER TABLE `ct_cr_hexchange`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_cr_hinvest`
--
ALTER TABLE `ct_cr_hinvest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_cr_hoti`
--
ALTER TABLE `ct_cr_hoti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_db_bag`
--
ALTER TABLE `ct_db_bag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_db_bank_deposit`
--
ALTER TABLE `ct_db_bank_deposit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_db_bdeposit`
--
ALTER TABLE `ct_db_bdeposit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_db_bel`
--
ALTER TABLE `ct_db_bel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_db_bexchange`
--
ALTER TABLE `ct_db_bexchange`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_db_bexpense`
--
ALTER TABLE `ct_db_bexpense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_db_binvest`
--
ALTER TABLE `ct_db_binvest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_db_bissued`
--
ALTER TABLE `ct_db_bissued`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_db_cash_withdraw`
--
ALTER TABLE `ct_db_cash_withdraw`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_db_exf`
--
ALTER TABLE `ct_db_exf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_db_hag`
--
ALTER TABLE `ct_db_hag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_db_hdeposit`
--
ALTER TABLE `ct_db_hdeposit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_db_hel`
--
ALTER TABLE `ct_db_hel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_db_hexchange`
--
ALTER TABLE `ct_db_hexchange`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_db_hexpense`
--
ALTER TABLE `ct_db_hexpense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_db_hinvest`
--
ALTER TABLE `ct_db_hinvest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_db_hissued`
--
ALTER TABLE `ct_db_hissued`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `ct_hand_collection`
--
ALTER TABLE `ct_hand_collection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `customer_profile`
--
ALTER TABLE `customer_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_register`
--
ALTER TABLE `customer_register`
  MODIFY `cus_reg_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `customer_status`
--
ALTER TABLE `customer_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cus_old_data`
--
ALTER TABLE `cus_old_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `director_creation`
--
ALTER TABLE `director_creation`
  MODIFY `dir_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `document_info`
--
ALTER TABLE `document_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `document_track`
--
ALTER TABLE `document_track`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doc_mapping`
--
ALTER TABLE `doc_mapping`
  MODIFY `doc_map_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_category`
--
ALTER TABLE `expense_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `fingerprints`
--
ALTER TABLE `fingerprints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `gold_info`
--
ALTER TABLE `gold_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holiday_creation`
--
ALTER TABLE `holiday_creation`
  MODIFY `holiday_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `in_acknowledgement`
--
ALTER TABLE `in_acknowledgement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `in_issue`
--
ALTER TABLE `in_issue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_calculation`
--
ALTER TABLE `loan_calculation`
  MODIFY `loan_cal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_category`
--
ALTER TABLE `loan_category`
  MODIFY `loan_category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_category_creation`
--
ALTER TABLE `loan_category_creation`
  MODIFY `loan_category_creation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_category_ref`
--
ALTER TABLE `loan_category_ref`
  MODIFY `loan_category_ref_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_followup`
--
ALTER TABLE `loan_followup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_issue`
--
ALTER TABLE `loan_issue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_scheme`
--
ALTER TABLE `loan_scheme`
  MODIFY `scheme_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_summary_feedback`
--
ALTER TABLE `loan_summary_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key', AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `name_detail_creation`
--
ALTER TABLE `name_detail_creation`
  MODIFY `name_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `new_cus_promo`
--
ALTER TABLE `new_cus_promo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `new_promotion`
--
ALTER TABLE `new_promotion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `noc`
--
ALTER TABLE `noc`
  MODIFY `noc_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key';

--
-- AUTO_INCREMENT for table `request_category_info`
--
ALTER TABLE `request_category_info`
  MODIFY `cat_info` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request_creation`
--
ALTER TABLE `request_creation`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `signed_doc`
--
ALTER TABLE `signed_doc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `signed_doc_info`
--
ALTER TABLE `signed_doc_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_creation`
--
ALTER TABLE `staff_creation`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_type_creation`
--
ALTER TABLE `staff_type_creation`
  MODIFY `staff_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_area_list_creation`
--
ALTER TABLE `sub_area_list_creation`
  MODIFY `sub_area_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1803;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `verification_bank_info`
--
ALTER TABLE `verification_bank_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verification_cus_feedback`
--
ALTER TABLE `verification_cus_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verification_documentation`
--
ALTER TABLE `verification_documentation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verification_family_info`
--
ALTER TABLE `verification_family_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verification_group_info`
--
ALTER TABLE `verification_group_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verification_kyc_info`
--
ALTER TABLE `verification_kyc_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verification_loan_calculation`
--
ALTER TABLE `verification_loan_calculation`
  MODIFY `loan_cal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verification_property_info`
--
ALTER TABLE `verification_property_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verif_loan_cal_category`
--
ALTER TABLE `verif_loan_cal_category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `closing_customer`
--
ALTER TABLE `closing_customer`
  ADD CONSTRAINT `requestForegin` FOREIGN KEY (`req_id`) REFERENCES `request_creation` (`req_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
