<?php

class HistoryLogs extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('validated')) {
            $this->session->set_flashdata('errors', ["PLEASE LOGIN FIRST!"]);
            redirect('/');
        }
        $this->load->model('historyLog');
    }

    public function index() {
        if($this->input->server('REQUEST_METHOD') == 'POST') {
            $man = $this->input->post('manning');
            $dateFrom = $this->input->post('dateFrom');
            $dateTo = $this->input->post('dateTo');
            $page = $this->input->post('page');
            if($man == '') $man = null;
            else $data['man'] = $man;;
            if($dateFrom == '') $dateFrom = null;
            else $data['dateFrom'] = $dateFrom;
            if($dateTo == '') $dateTo = null;
            else $data['dateTo'] = $dateTo;
            $historyLogs = $this->historyLog->findByParams($man, $dateFrom, $dateTo, $page);
            $data['count'] = $this->historyLog->countByParams($man, $dateFrom, $dateTo);
            $data['page'] = $page;
        } else {
            $historyLogs = $this->historyLog->findByParams();
            $data['count'] = $this->historyLog->countByParams();
            $data['page'] = 0;
        }
        $staffList = $this->db->select(["staff_id", "last_name", "first_name", "middle_name"])->from("account_staff")->get()->result();
        $staffs = [];
        foreach ($staffList as $staff) $staffs[$staff->staff_id] = $staff;
        $principalList = $this->db->select(["principal_id", "last_name", "first_name", "middle_name"])->from("account_principal")->get()->result();
        $principals = [];
        foreach ($principalList as $principal) $principals[$principal->principal_id] = $principal;
        $manningList = $this->db->select(["manning_id", "manning_alias"])->from('cat_manning_principal')->where("manning_id !=", 13)->get()->result();
        $mannings = [];
        foreach ($manningList as $manning) $mannings[$manning->manning_id] = $manning;
        $data['historyLogs'] = $historyLogs;
        $data['staffs'] = $staffs;
        $data['principals'] = $principals;
        $data['mannings'] = $mannings;
        $data['content'] = 'pages/historyLog/index.php';
        $this->load->view('templates/wide_template', $data);
    }

    public function view($historyId) {
        $this->load->model("manning");
        $this->load->model("user");
        $historyLog = $this->historyLog->findById($historyId);
        $manning = $this->manning->getManning($historyLog->manning_id);
        if($historyLog->principal) {
            $user = $this->user->findPrinById($historyLog->account_id);
        } else {
            $user = $this->user->findStaffById($historyLog->account_id);
        }
        $data = [
            'historyLog' => $historyLog,
            'manning' => $manning,
            'user' => $user,
        ];

        $data['content'] = 'pages/historyLog/view.php';
        $this->load->view('templates/wide_template', $data);
    }

//    public function index() {
//        $historyLogs = $this->historyLog->findAll();
//        $staffList = $this->db->select(["staff_id", "last_name", "first_name", "middle_name", "manning_id"])->from("account_staff")->get()->result();
//        $staffs = [];
//        foreach ($staffList as $staff) $staffs[$staff->staff_id] = $staff;
//
//        $principalList = $this->db->select(["principal_id", "last_name", "first_name", "middle_name", "manning_id"])->from("account_principal")->get()->result();
//        $principals = [];
//        foreach ($principalList as $principal) $principals[$principal->principal_id] = $principal;
//
//        $manningList = $this->db->select(["manning_id", "manning_alias"])->from('cat_manning_principal')->get()->result();
//        $mannings = [];
//        foreach ($manningList as $manning) $mannings[$manning->manning_id] = $manning->manning_alias;
//
//        $data = [
//            'historyLogs' => $historyLogs,
//            'staffs' => $staffs,
//            'principals' => $principals,
//            'mannings' => $mannings,
//        ];
//
//        $this->load->view("templates/wide_header");
//        $this->load->view("templates/user_nav");
//        $this->load->view("pages/historyLog/index.php", $data);
//        $this->load->view("templates/wide_footer");
//    }

}