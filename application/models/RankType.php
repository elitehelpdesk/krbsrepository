<?php
class RankType extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function findAll() {
        return $this->db->select()
            ->from("rank_type")
            ->get()->result();
    }

    public function findByDocumentId() {

    }

}
