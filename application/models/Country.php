<?php
class Country extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function findActive() {
        return $this->db->select()
            ->from("country")
            ->where("id <", 6)
            ->get()->result();
    }

    public function findAll() {
        return $this->db->select()
            ->from("country")
            ->get()->result();
    }

}
