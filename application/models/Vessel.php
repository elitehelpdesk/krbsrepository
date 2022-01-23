<?php
class Vessel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function findByManning($manningId) {
        return $this->db->select(["vessel_id", "vessel_name"])
            ->from("cat_vessel")
            ->where("manning_id", $manningId)
            ->where("vessel_status", 1)
            ->order_by("vessel_name", "ASC")
            ->get()->result();
    }

}