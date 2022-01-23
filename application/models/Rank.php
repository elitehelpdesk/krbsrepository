<?php
class Rank extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function findByRankCode($rankCode) {
        return $this->db->select("*")
            ->from("cat_rank")
            ->where("rank_code", $rankCode)
            ->get()->row();
    }

}
