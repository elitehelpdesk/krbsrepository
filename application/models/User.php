<?php
class User extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    public function loginAsStaff($username, $password) {
        $username = $this->security->xss_clean($username);
        $password = $this->security->xss_clean(md5($password));
        $query = $this->db->select(["staff_id", "staff_no", "manning_id", "last_name", "first_name", "middle_name", "staff_password_change_date"])
            ->from("account_staff")
            ->where("staff_account_name", $username)
            ->where("staff_account_password", $password)
            ->where("account_status", 1)
            ->get();
        if($query->num_rows() == 1) {
            $row = $query->row();
            $data = array(
                'staff_no' => $row->staff_no,
                'manning_id' => $row->manning_id,
                'type' => "Staff",
                'name' => ucwords(strtolower($row->last_name)).', '.ucwords(strtolower($row->first_name)).' '.substr($row->middle_name, 0, 1),
                'validated' => true,
                'password_updated_at' => $row->staff_password_change_date
            );
            $this->session->set_userdata($data);

            $this->load->model("historyLog");
            $userId = $row->staff_id;
            $description = "username $username Logged in";
            $logData = [
                "account_id" => $userId,
                "principal" => 0,
                "manning_id" => $this->session->userdata('manning_id'),
                "event_description" => $description,
                "logged_at" => date("Y-m-d H:i:s")
            ];
            $this->historyLog->addLog($logData);

            return $row->manning_id;
        }
        return false;
    }

    public function loginAsManager($username, $password) {
        $username = $this->security->xss_clean($username);
        $password = $this->security->xss_clean(md5($password));
        $query = $this->db->select(array("manning_id", "principal_no", "principal_id", "last_name", "first_name", "middle_name", "password_updated_at"))
            ->from("account_principal")
            ->where("principal_account_name", $username)
            ->where("principal_account_password", $password)
            ->where("account_status", 1)
            ->where("manning_id !=", 7)
            ->get();
        if($query->num_rows() == 1) {
            $row = $query->row();

            $mannings = array(
                '1' =>'veritas',
                '2' =>'ventis',
                '3' => 'newfil',
                '4' => 'huayang',
                '5' => 'bulga',
                '6' => 'ukra',
                '7' => 'tnkc',
                '8' => 'inter',
                '9' => 'osmchina',
                '10' => 'sinochina',
                '11' => 'seasun',
                '12' => 'filstar',
                '13' => 'elite',
            );
            $manning = $mannings[$row->manning_id];
            $data = array(
                'principal_no' => $row->principal_no,
                'principal_id' => $row->principal_id,
                'manning_id' => $row->manning_id,
                'manning' => $row->manning_id,
                'manningname' => $manning,
                'name' => ucwords(strtolower($row->last_name)).', '.ucwords(strtolower($row->first_name)).' '.substr($row->middle_name, 0, 1),
                'type' => "Manager",
                'validated' => true,
                "password_updated_at" => $row->password_updated_at
            );
            $this->session->set_userdata($data);

            $this->load->model("historyLog");
            $userId = $row->principal_id;
            $description = "username $username Logged in";
            $logData = [
                "account_id" => $userId,
                "principal" => 1,
                "manning_id" => $this->session->userdata('manning_id'),
                "event_description" => $description,
                "logged_at" => date("Y-m-d H:i:s")
            ];
            $this->historyLog->addLog($logData);

            return $row->manning_id;
        }
        return false;
    }

    public function loginAsPrincipal($username, $password) {
        $username = $this->security->xss_clean($username);
        $password = $this->security->xss_clean(md5($password));

//        $query = $this->db->select(array("manning_id", "principal_no", "principal_id", "last_name", "first_name", "middle_name", "password_updated_at"))
//            ->from("account_principal")
//            ->where("principal_account_name", $username)
//            ->where("principal_account_password", $password)
//            ->where("account_status", 1)
//            ->where("manning_id", 7)
//            ->get();

        $sql = "
                SELECT manning_id, principal_no, principal_id, last_name, first_name, middle_name, password_updated_at
                    from account_principal
                    where principal_account_name = '$username'
                    and principal_account_password = '$password'
                    and account_status = 1
                    and manning_id in (7, 14)";
        $query = $this->db->query($sql);
        if($query->num_rows() == 1) {
            $row = $query->row();
            $mannings = array(
                '1' =>'veritas',
                '2' =>'ventis',
                '3' => 'newfil',
                '4' => 'huayang',
                '5' => 'bulga',
                '6' => 'ukra',
                '7' => 'tnkc',
                '8' => 'inter',
                '9' => 'osmchina',
                '10' => 'sinochina',
                '11' => 'seasun',
                '12' => 'filstar',
                '13' => 'elite',
            );
            $manning = $mannings[$row->manning_id];
            $data = array(
                'principal_no' => $row->principal_no,
                'principal_id' => $row->principal_id,
                'manning_id' => $row->manning_id,
                'manning' => $row->manning_id,
                'manningname' => $manning,
                'name' => ucwords(strtolower($row->last_name)).', '.ucwords(strtolower($row->first_name)).' '.substr($row->middle_name, 0, 1),
                'type' => "Principal",
                'validated' => true,
                'password_updated_at' => $row->password_updated_at
            );
            $this->session->set_userdata($data);

            $this->load->model("historyLog");
            $userId = $row->principal_id;
            $description = "username $username Logged in";
            $logData = [
                "account_id" => $userId,
                "principal" => 1,
                "manning_id" => $this->session->userdata('manning_id'),
                "event_description" => $description,
                "logged_at" => date("Y-m-d H:i:s")
            ];
            $this->historyLog->addLog($logData);

            return true;
        }
        return false;
    }

    public function findPrincipalByLogin() {
        return $this->db->select()
            ->from("account_principal")
            ->where("principal_no", $this->session->userdata('principal_no'))
            ->get()->row();
    }

    public function findStaffByLogin() {
        return $this->db->select()
            ->from("account_staff")
            ->where("staff_no", $this->session->userdata('staff_no'))
            ->get()->row();
    }


    public function findAllPrincipal() {
        return $this->db->select([
            "a.principal_id",
            "a.principal_no",
            "a.first_name",
            "a.last_name",
            "a.middle_name",
            "a.position",
            "m.manning_code",
            "m.manning_name",
            "a.principal_account_name",
            "a.account_status"
        ])
            ->from("account_principal a")
            ->join("cat_manning_principal m", "a.manning_id = m.manning_id", "left")
            ->order_by("a.principal_id", "DESC")
            ->get()
            ->result();
    }

    public function findAllStaff() {
        return $this->db->select(["a.staff_id", "a.staff_no", "a.first_name", "a.last_name", "a.middle_name", "a.position", "m.manning_code", "m.manning_name", "a.staff_account_name", "a.account_status"])
            ->from("account_staff a")
            ->join("cat_manning_principal m", "a.manning_id = m.manning_id", "left")
            ->order_by("a.staff_id", "DESC")
            ->get()
            ->result();
    }

    public function findPrinById($principalId = null) {
        return $this->db->select()
            ->from("account_principal")
            ->where("principal_id", $principalId)
            ->get()->row();
    }

    public function findAllManning() {
        return $this->db->select(array("manning_id", "manning_name", "manning_code"))
            ->from("cat_manning_principal")
            ->order_by("manning_name")
            ->get()->result();
    }

    public function getMaxNumber() {
        $query = $this->db->select("principal_no")
            ->from("account_principal")
            ->order_by("principal_id", "DESC")
            ->get()->row();
        $accountNo = $query->principal_no;
        $accountNo = ((int) substr($accountNo, strripos($accountNo, "-")+1)) + 1;
        $accountNo = sprintf("%04d", $accountNo);
        $accountNo = "PRIN-$accountNo";
        return $accountNo;
    }

    public function getStaffMaxNumber() {
        $query = $this->db->select("staff_no")
            ->from("account_staff")
            ->order_by("staff_id", "DESC")
            ->get()->row();
        $accountNo = $query->staff_no;
        $accountNo = ((int) substr($accountNo, strripos($accountNo, "-")+1)) + 1;
        $accountNo = sprintf("%04d", $accountNo);
        $accountNo = "STA-$accountNo";
        return $accountNo;
    }

    public function findStaffById($staffId) {
        return $this->db->select()
            ->from("account_staff")
            ->where("staff_id", $staffId)
            ->get()->row();
    }

    public function deactivateAccount($position, $username) {
        if($position == 'staff') {
            $table = 'account_staff';
            $column = 'staff_account_name';
            $principal = 0;
            $tableId = 'staff_id';
        } else {
            $table = 'account_principal';
            $column = 'principal_account_name';
            $principal = 1;
            $tableId = 'principal_id';
        }
        $this->db->where($column, $username)
            ->update($table, ['account_status' => 0]);

        $user = $this->db
            ->select([$tableId, 'manning_id'])
            ->where($column, $username)
            ->from($table)->get()->row();

        if($user) {
            $this->load->model("historyLog");
            $userId = $user->$tableId;
            $description = "username $username deactivated due to too many login attempts";
            $logData = [
                "account_id" => $userId,
                "principal" => $principal,
                "manning_id" => $user->manning_id,
                "event_description" => $description,
                "logged_at" => date("Y-m-d H:i:s")
            ];
            $this->historyLog->addLog($logData);
        }

    }

    public function exists($position, $username) {
        if($position == 'staff') {
            $table = 'account_staff';
            $column = 'staff_account_name';
        } else {
            $table = 'account_principal';
            $column = 'principal_account_name';
        }
        return $this->db->where($column, $username)
            ->from($table)
            ->get()->row();
    }
}
