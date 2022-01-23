<?php
class HistoryLog extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function findAll() {
        $historyLogDb = $this->load->database('secondary', true);

        $columns = [
            "id",
            "account_id",
            "principal",
            "event_description",
            "logged_at",
        ];

        $historyLogs = $historyLogDb->select()
            ->from("repository_logs l")
            ->order_by("l.id", "DESC")
            ->get()->result();
        return $historyLogs;
    }

    public function findByParams($manning = null, $dateFrom = null, $dateTo = null, $page = 0) {

        $historyLogDb = $this->load->database('secondary', true);

        $columns = [
            "id",
            "account_id",
            "principal",
            "manning_id",
            "event_description",
            "logged_at",
        ];

        $historyLogs = $historyLogDb->select($columns)
            ->from("repository_logs l");

        if($manning) $historyLogs->where("manning_id", $manning);
        if($dateFrom) {
            if(!$dateTo) $dateTo =  date("Y-m-d", strtotime('+1 day'.date("Y-m-d", strtotime($dateFrom))));
            $betweenDates = "logged_at between '$dateFrom' and '$dateTo'";
            $historyLogs->where($betweenDates);
        }

        if($this->session->userdata('type') == "Manager") {
            $historyLogs->where("manning_id", $this->session->userdata('manning_id'));
        }

        $historyLogs = $historyLogs->order_by("l.id", "DESC")
            ->limit(100, ($page * 100))
            ->get()->result();
//        exit($historyLogDb->last_query());
        return $historyLogs;
    }

    public function countByParams($manning = null, $dateFrom = null, $dateTo = null) {

        $historyLogDb = $this->load->database('secondary', true);

        $historyLogs = $historyLogDb->select("count(*) as count")
            ->from("repository_logs l");

        if($manning) $historyLogs->where("manning_id", $manning);
        if($dateFrom) {
            if(!$dateTo) $dateTo =  date("Y-m-d", strtotime('+1 day'.date("Y-m-d", strtotime($dateFrom))));
            $betweenDates = "logged_at between '$dateFrom' and '$dateTo'";
            $historyLogs->where($betweenDates);
        }

        if($this->session->userdata('type') == "Manager") {
            $historyLogs->where("manning_id", $this->session->userdata('manning_id'));
        }

        $historyLogs = $historyLogs->order_by("l.logged_at", "DESC")
            ->get()->row();
        return $historyLogs->count;
    }

    public function addLog($values) {
        $this->load->library('user_agent');
        if ($this->agent->is_browser()) {
            $browser = $this->agent->browser();
        } else if ($this->agent->is_robot()) {
            $browser = $this->agent->robot();
        } else if ($this->agent->is_mobile()) {
            $browser = $this->agent->mobile();
        } else {
            $browser = 'Unidentified User Agent';
        }
        $ip = $this->getRealIpAddr();
        $values['browser'] = $browser;
        $values['ip'] = $ip;

//        if($ip == '192.168.2.1') {
//            exit($this->db->last_query());
//        }

        $historyLogDb = $this->load->database('secondary', true);
        $historyLogDb->insert("repository_logs", $values);
        return true;
    }

    public function findById($id) {
        $historyLogDb = $this->load->database('secondary', true);
        $historyLog = $historyLogDb->select()
            ->from("repository_logs")
            ->where("id", $id)
            ->get()->row();
        return $historyLog;
    }

    function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}
