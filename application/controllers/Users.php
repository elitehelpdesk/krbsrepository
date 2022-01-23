<?php

class Users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('validated')) {
            $this->session->set_flashdata('errors', ["PLEASE LOGIN FIRST!"]);
            redirect('/');
        }
        $this->load->model('user');
    }

    public function index() {
        if($this->session->userdata('principal_no') != 'PRIN-0030') {
            $this->session->set_flashdata('errors', ["UNAUTHORIZED ACCESS!!"]);
            redirect("welcome/logout");
        }
        $data['principals'] = $this->user->findAllPrincipal();
        $data['staffs'] = $this->user->findAllStaff();

        $data['content'] = 'pages/user/index.php';
        $this->load->view('templates/wide_template', $data);
    }

    public function editPrincipal($principalId = null) {
        if(!$principalId) {
            $this->session->set_flashdata('errors', ["UNAUTHORIZED ACCESS!"]);
            redirect("welcome");
        }
        $data['principal'] = $this->user->findPrinById($principalId);
        $data['mannings'] = $this->user->findAllManning();

        $data['content'] = 'pages/user/editPrincipalAccount.php';
        $this->load->view('templates/wide_template', $data);
    }

    public function updatePrincipalAccount() {
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('errors', ["UNAUTHORIZED METHOD!"]);
            redirect("welcome/logout");
        }

        $principalId = $this->input->post("principalId");
        $lastName = $this->input->post("lastName");
        $firstName = $this->input->post("firstName");
        $middleName = $this->input->post("middleName");
        $position = $this->input->post("positionName");
        $manningId = $this->input->post("manningId");
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $confirmPassword = $this->input->post("confirmPassword");
        $active = $this->input->post("status")?"1" : "0";

        $updatedPrincipal = [
            "last_name" => $lastName,
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "position" => $position,
            "manning_id" =>$manningId,
            "principal_account_name" => $username,
            "account_status" => $active
        ];

        $principal = $this->db->where('principal_id', $principalId)->get("account_principal")->row();

        $this->load->model("user");
        $this->load->model("historyLog");
        $this->load->model("manning");

        $oldValues = [
            'last_name' => $principal->last_name ,
            'first_name' =>  $principal->first_name ,
            'middle_name' =>  $principal->middle_name,
            'position' =>  $principal->position,
            'manning' =>  $this->manning->getManning($principal->manning_id)->manning_alias,
            'username' =>  $principal->principal_account_name,
            "status" => (($principal->account_status) ?"ACTIVE" : "INACTIVE")
        ];

        $newValues = [
            "last_name" => $lastName,
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "position" => $position,
            "manning" =>$this->manning->getManning($manningId)->manning_alias,
            "username" => $username,
            "status" => (($active) ?"ACTIVE" : "INACTIVE"),
        ];

        $userId = $this->user->findPrincipalByLogin()->principal_id;
        $updatedColumn = "";
        foreach ($oldValues as $index => $value) {
            if($value != $newValues[$index]) {
                $updatedColumn.="$index, ";
            }
        }

        if($password != "") {
            if($password == $confirmPassword) {
                $updatedPrincipal['principal_account_password'] = $this->security->xss_clean(md5($password));
                $updatedColumn.= "password, ";
            } else {
                $this->session->set_flashdata('errors', "PASSWORD NOT MATCHED");
                redirect("users/editPrincipal/$principalId");
            }
        }

        if($updatedColumn) $updatedColumn = substr($updatedColumn, 0, strripos($updatedColumn, ","))." of";
        $description = "updated $updatedColumn principal with username of $principal->principal_account_name";

        $logData = [
            "account_id" => $userId,
            "principal" => 1,
            "manning_id" => $this->session->userdata('manning_id'),
            "event_description" => $description,
            "old_value" => json_encode($oldValues),
            "new_value" => json_encode($newValues),
            "logged_at" => date("Y-m-d H:i:s")
        ];

        if($this->db->where("principal_id", $principalId)->update("account_principal", $updatedPrincipal)) {
            $this->historyLog->addLog($logData);
            $this->session->set_flashdata('success', "PRINCIPAL ACCOUNT SUCCESSFULLY UPDATED!");
        } else {
            $this->session->set_flashdata('errors', "UPDATE FAILED!!");
        }
        redirect("users/editPrincipal/$principalId");
    }

    public function addPrincipalAccount() {
        $data['maxAccountNo'] = $this->user->getMaxNumber();
        $data['mannings'] = $this->user->findAllManning();

        $data['content'] = 'pages/user/addPrincipalAccount.php';
        $this->load->view('templates/wide_template', $data);
    }

    public function storePrincipalAccount() {
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('errors', ["UNAUTHORIZED METHOD!"]);
            redirect("welcome/logout");
        }

        $accountNumber = $this->input->post("accountNumber");
        $lastName = $this->input->post("lastName");
        $firstName = $this->input->post("firstName");
        $middleName = $this->input->post("middleName");
        $position = $this->input->post("positionName");
        $manningId = $this->input->post("manningId");
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $confirmPassword = $this->input->post("confirmPassword");
        $active = $this->input->post("status")?"1" : "0";

        $principal = [
            "principal_no" => $accountNumber,
            "last_name" => $lastName,
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "position" => $position,
            "manning_id" =>$manningId,
            "principal_account_name" => $username,
            "account_status" => $active
        ];

        $this->load->model("user");
        $this->load->model("historyLog");
        $this->load->model("manning");

        $newValues = [
            "principal_no" => $accountNumber,
            "last_name" => $lastName,
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "position" => $position,
            "manning" =>$this->manning->getManning($manningId)->manning_alias,
            "username" => $username,
            "status" => (($active) ?"ACTIVE" : "INACTIVE"),
        ];
        $userId = $this->user->findPrincipalByLogin()->principal_id;
        $description = "added principal account with username of $username";
        $logData = [
            "account_id" => $userId,
            "principal" => 1,
            "manning_id" => $this->session->userdata('manning_id'),
            "event_description" => $description,
            "new_value" => json_encode($newValues),
            "logged_at" => date("Y-m-d H:i:s")
        ];

        if($password == $confirmPassword) {
            $principal['principal_account_password'] = $this->security->xss_clean(md5($password));
        } else {
            $this->session->set_flashdata('errors', "PASSWORD NOT MATCHED");
            redirect("users/addPrincipalAccount");
        }
        if($this->db->insert('account_principal', $principal)) {
            $this->historyLog->addLog($logData);
            $this->session->set_flashdata('success', "PRINCIPAL ACCOUNT SUCCESSFULLY ADDED!");
        } else {
            $this->session->set_flashdata('errors', "ADD PRINCIPAL ACCOUNT FAILED!!");
        }
        redirect("users/addPrincipalAccount");
    }

    public function deletePrincipal($principalId) {
        if($this->session->userdata('principal_no') == 'PRIN-0030') {
            $principal = $this->db->where('principal_id', $principalId)->get("account_principal")->row();
            if($this->db->delete('account_principal', array('principal_id' => $principalId))) {
                $this->load->model("user");
                $this->load->model("historyLog");
                $this->load->model("manning");

                $oldValues = [
                    'last_name' => $principal->last_name ,
                    'first_name' =>  $principal->first_name ,
                    'middle_name' =>  $principal->middle_name,
                    'position' =>  $principal->position,
                    'manning' =>  $this->manning->getManning($principal->manning_id)->manning_alias,
                    'username' =>  $principal->principal_account_name,
                    "status" => (($principal->account_status) ?"ACTIVE" : "INACTIVE")
                ];
                $description = "deleted principal account with username of $principal->principal_account_name";

                $userId = $this->user->findPrincipalByLogin()->principal_id;

                $logData = [
                    "account_id" => $userId,
                    "principal" => 1,
                    "manning_id" => $this->session->userdata('manning_id'),
                    "event_description" => $description,
                    "old_value" => json_encode($oldValues),
                    "logged_at" => date("Y-m-d H:i:s")
                ];

                $this->historyLog->addLog($logData);
                $this->session->set_flashdata('success', "PRINCIPAL ACCOUNT SUCCESSFULLY DELETED!");
            } else {
                $this->session->set_flashdata('errors', "FAILED TO DELETE PRINCIPAL ACCOUNT");
            }
            redirect("users/index");
        } else {
            $this->session->set_flashdata('errors', ["UNAUTHORIZED ACCESS!"]);
            redirect("welcome");
        }
    }

    public function editStaff($staffId = null) {
        if(!$staffId) {
            $this->session->set_flashdata('errors', ["UNAUTHORIZED ACCESS!"]);
            redirect("welcome");
        }
        $data['staff'] = $this->user->findStaffById($staffId);
        $data['mannings'] = $this->user->findAllManning();

        $data['content'] = 'pages/user/editStaffAccount.php';
        $this->load->view('templates/wide_template', $data);
    }

    public function updateStaffAccount() {
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('errors', ["UNAUTHORIZED METHOD!"]);
            redirect("welcome/logout");
        }

        $staffId = $this->input->post("staffId");
        $lastName = $this->input->post("lastName");
        $firstName = $this->input->post("firstName");
        $middleName = $this->input->post("middleName");
        $position = $this->input->post("positionName");
        $manningId = $this->input->post("manningId");
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $confirmPassword = $this->input->post("confirmPassword");
        $active = $this->input->post("status")?"1" : "0";

        $updatedStaff = [
            "last_name" => $lastName,
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "position" => $position,
            "manning_id" =>$manningId,
            "staff_account_name" => $username,
            "account_status" => $active
        ];

        $staff = $this->db->where('staff_id', $staffId)->get("account_staff")->row();

        $this->load->model("user");
        $this->load->model("historyLog");
        $this->load->model("manning");

        $oldValues = [
            'last_name' => $staff->last_name ,
            'first_name' =>  $staff->first_name ,
            'middle_name' =>  $staff->middle_name,
            'position' =>  $staff->position,
            'manning' =>  $this->manning->getManning($staff->manning_id)->manning_alias,
            'username' =>  $staff->staff_account_name,
            "status" => (($staff->account_status) ?"ACTIVE" : "INACTIVE")
        ];

        $newValues = [
            "last_name" => $lastName,
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "position" => $position,
            "manning" =>$this->manning->getManning($manningId)->manning_alias,
            "username" => $username,
            "status" => (($active) ?"ACTIVE" : "INACTIVE"),
        ];

        $userId = $this->user->findPrincipalByLogin()->principal_id;
        $updatedColumn = "";
        foreach ($oldValues as $index => $value) {
            if($value != $newValues[$index]) {
                $updatedColumn.="$index, ";
            }
        }

        if($password != "") {
            if($password == $confirmPassword) {
                $updatedStaff['staff_account_password'] = $this->security->xss_clean(md5($password));
                $updatedColumn .= "password, ";
            } else {
                $this->session->set_flashdata('errors', "PASSWORD NOT MATCHED");
                redirect("users/editStaff/$staffId");
            }
        }

        if($updatedColumn) $updatedColumn = substr($updatedColumn, 0, strripos($updatedColumn, ","))." of";
        $description = "updated $updatedColumn staff with username of $staff->staff_account_name";
        $logData = [
            "account_id" => $userId,
            "principal" => 1,
            "manning_id" => $this->session->userdata('manning_id'),
            "event_description" => $description,
            "old_value" => json_encode($oldValues),
            "new_value" => json_encode($newValues),
            "logged_at" => date("Y-m-d H:i:s")
        ];

        if($this->db->where("staff_id", $staffId)->update("account_staff", $updatedStaff)) {
            $this->historyLog->addLog($logData);
            $this->session->set_flashdata('success', "STAFF ACCOUNT SUCCESSFULLY UPDATED!");
        } else {
            $this->session->set_flashdata('errors', "UPDATE FAILED!!");
        }
        redirect("users/editStaff/$staffId");
    }

    public function addStaffAccount() {
        $data['maxAccountNo'] = $this->user->getStaffMaxNumber();
        $data['mannings'] = $this->user->findAllManning();

        $data['content'] = 'pages/user/addStaffAccount.php';
        $this->load->view('templates/wide_template', $data);
    }

    public function storeStaffAccount() {
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('errors', ["UNAUTHORIZED METHOD!"]);
            redirect("welcome/logout");
        }

        $accountNumber = $this->input->post("accountNumber");
        $lastName = $this->input->post("lastName");
        $firstName = $this->input->post("firstName");
        $middleName = $this->input->post("middleName");
        $position = $this->input->post("positionName");
        $manningId = $this->input->post("manningId");
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        $confirmPassword = $this->input->post("confirmPassword");
        $active = $this->input->post("status")?"1" : "0";

        $staff = [
            "staff_no" => $accountNumber,
            "last_name" => $lastName,
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "position" => $position,
            "manning_id" =>$manningId,
            "staff_account_name" => $username,
            "account_status" => $active
        ];
        if($password == $confirmPassword) {
            $staff['staff_account_password'] = $this->security->xss_clean(md5($password));
        } else {
            $this->session->set_flashdata('errors', "PASSWORD NOT MATCHED");
            redirect("users/addStaffAccount");
        }
        if($this->db->insert('account_staff', $staff)) {
            $this->load->model("user");
            $this->load->model("historyLog");
            $this->load->model("manning");
            $newValues = [
                "last_name" => $lastName,
                "first_name" => $firstName,
                "middle_name" => $middleName,
                "position" => $position,
                "manning" =>$this->manning->getManning($manningId)->manning_alias,
                "username" => $username,
                "status" => (($active) ?"ACTIVE" : "INACTIVE"),
            ];
            $userId = $this->user->findPrincipalByLogin()->principal_id;
            $description = "added staff account with username of $username";
            $logData = [
                "account_id" => $userId,
                "principal" => 1,
                "manning_id" => $this->session->userdata('manning_id'),
                "event_description" => $description,
                "new_value" => json_encode($newValues),
                "logged_at" => date("Y-m-d H:i:s")
            ];
            $this->historyLog->addLog($logData);
            $this->session->set_flashdata('success', "STAFF ACCOUNT SUCCESSFULLY ADDED!");
        } else {
            $this->session->set_flashdata('errors', "ADD STAFF ACCOUNT FAILED!!");
        }
        redirect("users/addStaffAccount");
    }

    public function deleteStaff($staffId) {
        $staff = $this->db->where('staff_id', $staffId)->get("account_staff")->row();
        if($this->session->userdata('principal_no') == 'PRIN-0030') {
            if($this->db->delete('account_staff', array('staff_id' => $staffId))) {
                $this->load->model("user");
                $this->load->model("historyLog");
                $this->load->model("manning");

                $oldValues = [
                    'last_name' => $staff->last_name ,
                    'first_name' =>  $staff->first_name,
                    'middle_name' =>  $staff->middle_name,
                    'position' =>  $staff->position,
                    'manning' =>  $this->manning->getManning($staff->manning_id)->manning_alias,
                    'username' =>  $staff->staff_account_name,
                    "status" => (($staff->account_status) ?"ACTIVE" : "INACTIVE")
                ];
                $description = "deleted principal account with username of $staff->staff_account_name";

                $userId = $this->user->findPrincipalByLogin()->principal_id;

                $logData = [
                    "account_id" => $userId,
                    "principal" => 1,
                    "manning_id" => $this->session->userdata('manning_id'),
                    "event_description" => $description,
                    "old_value" => json_encode($oldValues),
                    "logged_at" => date("Y-m-d H:i:s")
                ];

                $this->historyLog->addLog($logData);

                $this->session->set_flashdata('success', "STAFF ACCOUNT SUCCESSFULLY DELETED!");
            } else {
                $this->session->set_flashdata('errors', "FAILED TO DELETE STAFF ACCOUNT");
            }
            redirect("users/index");
        } else {
            $this->session->set_flashdata('errors', ["UNAUTHORIZED ACCESS!"]);
            redirect("welcome");
        }
    }

    public function changePassword() {

        $data['content'] = 'pages/user/changePassword.php';
        $this->load->view('templates/template', $data);
    }

    public function updatePassword() {
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('errors', ["UNAUTHORIZED METHOD!"]);
            redirect("welcome/logout");
        }

        if($this->session->userdata('type') == "Staff") {
            $user = $this->user->findStaffByLogin();
            $userId = $user->staff_id;
            $principal = 0;
            $passwordColumn = "staff_account_password";
            $datePasswordChangeColumn = "staff_password_change_date";
            $tableName = "account_staff";
            $uniqueColumn = "staff_no";
        } else {
            $user = $this->user->findPrincipalByLogin();
            $userId = $user->principal_id;
            $principal = 1;
            $passwordColumn = "principal_account_password";
            $datePasswordChangeColumn = "password_updated_at";
            $tableName = "account_principal";
            $uniqueColumn = "principal_no";
        }
        $oldPassword = $this->security->xss_clean(md5($this->input->post("oldPassword")));
        $newPassword = $this->security->xss_clean(md5($this->input->post("newPassword")));
        $confirmNewPassword = $this->security->xss_clean(md5($this->input->post("confirmNewPassword")));

        if($user->$passwordColumn != $oldPassword) {
            $this->session->set_flashdata('errors', "OLD PASSWORD IS INVALID!");
            redirect("users/changePassword");
        } elseif($newPassword != $confirmNewPassword) {
            $this->session->set_flashdata('errors', "NEW PASSWORD NOT MATCHED!");
            redirect("users/changePassword");
        } elseif($oldPassword == $confirmNewPassword) {
            $this->session->set_flashdata('errors', "PLEASE ENTER DIFFERENT PASSWORD!");
            redirect("users/changePassword");
        }

        if($this->db->where($uniqueColumn, $user->$uniqueColumn)->update($tableName, [$passwordColumn => $newPassword, $datePasswordChangeColumn => date("Y-m-d")])) {

            $this->load->model("user");
            $this->load->model("historyLog");

            $description = "updated password";
            $logData = [
                "account_id" => $userId,
                "principal" => $principal,
                "manning_id" => $this->session->userdata('manning_id'),
                "event_description" => $description,
                "logged_at" => date("Y-m-d H:i:s")
            ];
            $this->historyLog->addLog($logData);

            $this->session->set_flashdata('success', "PASSWORD SUCCESSFULLY UPDATED!");
        } else {
            $this->session->set_flashdata('errors', "FAILED TO UPDATE PASSWORD!!");
        }

        redirect("users/changePassword");
    }

    public function refreshSession() {
//        $sessionDate = $_POST['sessionDate'];
        $sessionDate = $this->input->post('sessionDate');
        $this->session->set_userdata('sessionDate', $sessionDate);
//        $userData = $this->session->userdata;
//        $this->config->set_item('sess_expiration', 7200);
//        $this->session = new CI_Session();
//        $this->session->userdata = $userData;
        return $this->output->set_output($sessionDate);
    }

    function sessionLogout() {
        if($this->session->userdata('type')) {
            $this->load->model("historyLog");
            if($this->session->userdata('type') == "Staff") {
                $user = $this->user->findStaffByLogin();
                $userId = $user->staff_id;
                $username = $user->staff_account_name;
                $principal = 0;
            } else {
                $user = $this->user->findPrincipalByLogin();
                $userId = $user->principal_id;
                $username = $user->principal_account_name;
                $principal = 1;
            }

            $description = "username $username Auto Logged Out";
            $logData = [
                "account_id" => $userId,
                "principal" => $principal,
                "manning_id" => $this->session->userdata('manning_id'),
                "event_description" => $description,
                "logged_at" => date("Y-m-d H:i:s")
            ];
            $this->historyLog->addLog($logData);
        }

        $this->session->sess_destroy();
        return $this->output->set_output('200');
    }

}

?>
