<?php
class Crew extends CI_Model {

    protected $selections = [
        "c.*",
        "r.rank_alias",
        "r.ALIAS2",
        "r.rank",
        "c.MANNING_ID",
        "m.manning_code",
        "m.manning_name",
        "m.manning_folder_name",
        "m.manning_nationality",
        "m.country_id",
        "r.rank_type_id"
    ];

    public function __construct() {
        parent::__construct();
    }

    public function findByKeyword($keyword, $key, $manningid = false) {
        $testSeasunshineCrews = "";
        if($this->session->userdata('staff_no') == "STA-0085") {
            $crewIds = [
                30916,
                26481,
                32627,
                30992,
                30897,
                19316,
                31049,
                31711,
                32624,
                30982,
                31050,
                31373,
                32246,
                31011,
                32559,
                31571,
                31306,
            ];

            $query = $this->db->select(array("c.ID", "c.CREWIPN", "c.FNAME", "c.GNAME", "c.MNAME", "r.rank_alias", "c.MANNING_ID", "m.manning_code", "m.manning_name", "m.manning_folder_name", "m.manning_nationality"))
                ->from("crew c")
                ->join("cat_rank r", "r.rank_code = c.RANK", "left")
                ->join("cat_manning_principal m", "m.manning_id = c.MANNING_ID")
                ->where_in('c.ID', $crewIds)
                ->order_by('c.FNAME', "ASC");
            $query->where("c.status", 1);
            if($manningid) $query->where("c.MANNING_ID", $manningid);
            $query = $query->get();
            return $query->result();
            $testCrewIds = implode(',', $crewIds);
            $testSeasunshineCrews = "and c.ID in ($testCrewIds)";
        }
        if($keyword == "ALL") {
            $manningQuery = ($manningid) ? "and m.manning_id = $manningid" : "";
            $sql = "
                select
                       c.ID, 
                       c.CREWIPN, 
                       c.FNAME, 
                       c.GNAME, 
                       c.MNAME, 
                       r.rank_alias, 
                       c.MANNING_ID, 
                       m.manning_code, 
                       m.manning_name, 
                       m.manning_folder_name, 
                       m.manning_nationality
                from crew c
                left join cat_rank r on r.rank_code = c.`RANK`
                left join cat_manning_principal m on m.manning_id = c.MANNING_ID
                where
                      (c.FNAME like '%$key%'
                         or c.GNAME like '%$key%'
                         or c.MNAME like '%$key%'
                         or c.CREWIPN like '%$key%')
                AND c.STATUS = 1
                $manningQuery
                ORDER BY c.FNAME ASC";
            $query = $this->db->query($sql);
        } else {
            $query = $this->db->select(array("c.ID", "c.CREWIPN", "c.FNAME", "c.GNAME", "c.MNAME", "r.rank_alias", "c.MANNING_ID", "m.manning_code", "m.manning_name", "m.manning_folder_name", "m.manning_nationality"))
                ->from("crew c")
                ->join("cat_rank r", "r.rank_code = c.RANK", "left")
                ->join("cat_manning_principal m", "m.manning_id = c.MANNING_ID")
                ->like($keyword, $key, "after")
                ->order_by($keyword, "ASC");
            $query->where("c.status", 1);
            if($manningid) $query->where("c.MANNING_ID", $manningid);
            $query = $query->get();
        }
        return $query->result();

//        $query = $this->db->select(array("c.ID", "c.CREWIPN", "c.FNAME", "c.GNAME", "c.MNAME", "r.rank_alias", "c.MANNING_ID", "m.manning_code", "m.manning_name", "m.manning_folder_name", "m.manning_nationality"))
//            ->from("crew c")
//            ->join("cat_rank r", "r.rank_code = c.RANK", "left")
//            ->join("cat_manning_principal m", "m.manning_id = c.MANNING_ID")
//            ->like($keyword, $key, "after")
//            ->where("c.status", 1)
//            ->order_by($keyword, "ASC");
//        if($manningid) $query->where("c.MANNING_ID", $manningid);
//        return $query->get()->result();
    }

    public function findById($id) {
        $query = $this->db->select($this->selections)
            ->from("crew c")
            ->join("cat_rank r", "r.rank_code = c.RANK", "LEFT")
            ->join("cat_manning_principal m", "m.manning_id = c.MANNING_ID")
            ->where("c.status", 1)
            ->where("c.ID", $id)
            ->order_by("c.FNAME", "ASC");
        return $query->get()->row();
    }

    public function findByCrewIpn($crewIpn) {
        return $this->db->select(array("ID"))
            ->from("crew")
            ->where("CREWIPN", $crewIpn)
            ->where("STATUS", 1)
            ->get()->row();
    }

    public function findCrewCer($crewipn) {
        return $this->db->select(array('cc.cer_initial_id', 'cc.id', 'v.vessel_id', 'v.vessel_name', 'm.manning_name'))
            ->from('crew_cer cc')
            ->join('cat_vessel v', 'v.vessel_id = cc.vessel_id', "LEFT")
            ->join('cat_manning_principal m', 'm.manning_id = cc.MANNING_ID', "LEFT")
            ->where('crewipn', $crewipn)
            ->where_in('v.vessel_status', [0, 1])
            ->order_by('date_uploaded', "desc")
            ->get()->result();

//        $crewCer = $this->db->select(array('cc.cer_initial_id', 'cc.id', 'v.vessel_id', 'v.vessel_name'))
//            ->from('crew_cer cc')
//            ->join('cat_vessel v', 'v.vessel_id = cc.vessel_id', "LEFT");
//
//        if($this->session->userdata('type') != "Principal") {
//            $crewCer = $crewCer->join('cat_manning_principal m', 'm.manning_id = cc.MANNING_ID', "LEFT");
//        }
//
//        return $crewCer->where('crewipn', $crewipn)
//            ->where_in('v.vessel_status', [0, 1])
//            ->order_by('date_uploaded', "desc")
//            ->get()->result();

        // if($this->session->userdata('type') != "Principal") {
    }

    public function crewComment($cerid, $crewid) {
        $commentedby = " concat(p.last_name, ', ', p.first_name, ', ', p.middle_name) as commented_by";
        return $this->db->select(['cc.id', 'cc.cer_id', 'cc.comment', 'cc.commented_at', $commentedby, 'cc.principal_id', 'cc.commentator_id'])
            ->from('crew_comments cc')
            ->join('account_principal p', "cc.principal_id=p.principal_id", "LEFT")
            ->where('cc.cer_id', $cerid)
            ->where('cc.crew_id', $crewid)
            ->order_by('commented_at', "desc")
            ->get();
    }

    public function crew_cer_counter() {
        return $this->db->select("MAX(cer_initial_id) as max_cer_id")
            ->from("crew_cer")->get()->row();
    }

    public function findCerById($id = null) {
        $query = $this->db->select(["cc.crewipn", "cc.vessel_id", "c.ID", "m.manning_folder_name", "cc.id", "cc.cer_initial_id"])
            ->from("crew_cer cc")
            ->join("crew c", "c.CREWIPN = cc.crewipn")
            ->join("cat_manning_principal m", "m.manning_id = cc.manning_id")
            ->where("cc.id", $id);
        if($this->session->userdata('type') != "Principal") $query->where("c.MANNING_ID", $this->session->userdata('manning_id'));
        return $query->get()->row();
    }

    public function getRanks() {
        return $this->db->select(["rank_code", "rank"])
            ->from("cat_rank")
            ->order_by("rank")
            ->get()->result();
    }

    public function countCrew($crewIpn = null) {
        return $this->db->select("count(CREWIPN) as count")
            ->from("crew")
            ->where("CREWIPN", $crewIpn)
            ->get()->row()->count;
    }

    public function findByManning($manningId, $keyword = null, $value = null) {
        $query = $this->db->select(["c.CREWIPN", "c.FNAME", "c.GNAME", "c.MNAME", "c.BIRTHDATE", "r.rank_alias", "c.STATUS", "c.ID"])
            ->from("crew c")
            ->join("cat_rank r", "r.rank_code = c.RANK", "left")
            ->where("c.MANNING_ID", $manningId)
            ->order_by("c.FNAME");
        if($keyword && $value) {
            $query = $query->like("c.$keyword", $value, "after");
        }
        return $query;
    }

    public function countCrewByManning($manningId = null) {
        return $this->db->select("count(*) as count")
            ->from("crew")
            ->where("MANNING_ID", $manningId)
            ->order_by("FNAME")
            ->get()->row();
    }

    public function findCrewById($id) {
        $query = $this->db->select(array("c.*", "r.rank_alias", "r.ALIAS2", "r.rank", "c.MANNING_ID", "m.manning_code", "m.manning_name", "m.manning_folder_name", "m.manning_nationality"))
            ->from("crew c")
            ->join("cat_rank r", "r.rank_code = c.RANK", 'LEFT')
            ->join("cat_manning_principal m", "m.manning_id = c.MANNING_ID", 'LEFT')
            ->where("c.ID", $id)
            ->order_by("c.FNAME", "ASC");
        return $query->get()->row();
    }

    public function commentators() {
        return $this->db->select(["id", "name"])
            ->from("commentators")
            ->get()->result();
    }

}
