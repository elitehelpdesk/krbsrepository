<?php
class Manning extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getManning($id = false) {
        if(!$id) {
            return $this->db->get("cat_manning_principal")->result();
        } else {
            return $this->db->get_where("cat_manning_principal", ['manning_id' => $id])->row();
        }
    }

    public function findByStaffLogin() {
        return $this->db->select()
            ->from("cat_manning_principal")
            ->where("manning_id", $this->session->userdata("manning_id"))
            ->get()->row();
    }

    public function findVesselByStaffLogin() {
        return $this->db->select(["vessel_id", "vessel_name", 'vessel_status'])
            ->from("cat_vessel")
            ->where("manning_id", $this->session->userdata("manning_id"))
            ->where_in('vessel_status', [0, 1])
            ->get()->result();
    }

    public function findVesselById($vesselId = null) {
        return $this->db->select(["vessel_id", "vessel_name"])
            ->from("cat_vessel")
            ->where("vessel_id", $vesselId)
            ->get()->row();
    }

    public function findVesselByManningId($manningId) {
        return $this->db->select(["vessel_id", "vessel_name", 'vessel_status'])
            ->from("cat_vessel")
            ->where("manning_id", $manningId)
            ->where_in('vessel_status', [0, 1])
            ->order_by('vessel_status', 'DESC')
            ->order_by('vessel_name')
            ->get()->result();
    }

}
