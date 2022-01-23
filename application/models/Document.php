<?php
class Document extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getDocs($nationality) {
        return $this->db->select()
            ->from("cat_documents d")
            ->where("d.status", 1)
            ->where("d.manning_nationality", "")
            ->or_where("d.manning_nationality", $nationality)
            ->order_by("d.document_name", "ASC")
            ->get()->result();
    }

    public function countUploadedDocsByManning($manningId) {
        return $this->db->select("COUNT(id) as count")
            ->from("document_upload_history")
            ->where("manning_id", $manningId)
            ->where("DATE(uploaded_date)", date("Y-m-d"))
            ->get()->row();
    }

    public function countUploadedDocsByUser() {
        $staffNo = $this->session->userdata('staff_no');
        return $this->db->select("COUNT(id) as count")
            ->from("document_upload_history")
            ->where("staff_account_name", $staffNo)
            ->where("DATE(uploaded_date)", date("Y-m-d"))
            ->get()->row();
    }

    public function uploadedDocs($manningId) {
        $query = $this->db->select(["c.CREWIPN", "c.FNAME", "c.GNAME", "r.rank_alias", "d.document_name", "d.country_code", "d.document_code_mk", "s.last_name", "s.first_name", "h.uploaded_date", "h.document_code"])
            ->from("document_upload_history h")
            ->where("h.manning_id", $manningId)
            ->join("crew c", "c.CREWIPN = h.crewipn", "left")
            ->join("cat_rank r", "r.rank_code = c.RANK", "left")
            ->join("cat_documents d", "h.document_code = d.id", "left")
            ->join("account_staff s", "s.staff_no = h.staff_account_name", "left");
        if(empty($_POST['from'])) {
            $query->where("DATE(h.uploaded_date)", date("Y-m-d"));
        } else {
            $query->where("DATE(h.uploaded_date) >=", $this->input->post('from'))
                ->where("DATE(h.uploaded_date) <=", $this->input->post('to'));
        }
        return $query->order_by("h.uploaded_date", "DESC")->get()->result();
    }

    public function companyFiles($manningId = null) {
        if($this->session->userdata('type') !== 'Principal') {
            $manningId = $this->session->userdata("manning_id");
        }
//        $sql = "select  d.id as docid, d.country_code, d.document_code_mk, d.document_name, h.uploaded_date
//                  from cat_documents d
//                    left join document_upload_history h on h.document_code = d.id
//                      and h.uploaded_date = (
//                        select uploaded_date from document_upload_history
//                          where manning_id=h.manning_id
//                          and document_code=d.id
//                         order by uploaded_date desc
//                         limit 1
//                      )
//                  where d.document_type='CF'
//                  and h.manning_id=$manningid
//                  order by d.id";

        $sql = "select  d.id as docid, d.country_code, d.document_code_mk, d.document_name,
                       (
                         select MAX(uploaded_date)
                           from document_upload_history h
                         where h.document_code = d.id
                         and h.manning_id = $manningId
                         ) as uploaded_date
                from cat_documents d
                where d.document_type='CF'
                order by d.id";

        return $this->db->query($sql)->result();
    }

    public function findById($id) {
        return $this->db->select('*')
            ->from('cat_documents')
            ->where('id', $id)
            ->get()->row();
    }

    public function findAllDocuments() {
        return $this->db->select([
            "d.id",
            "d.document_code_mk",
            "d.document_name",
            "d.status",
            "d.document_type",
            "c.code",
            "c.nationality_code"
        ])->from("cat_documents d")
            ->join("country c", "c.id = d.country_id")
            ->where("document_type !=", "CF")
            ->order_by("d.document_name")
            ->get()->result();
    }

    public function getDocTypes() {
        return $this->db->distinct()
            ->select("document_type")
            ->from("cat_documents")
            ->where_not_in("document_type", ["CF", "Z"])
            ->order_by("document_type", "DESC")
            ->get()->result();
    }

    public function findByRankAndCountry($rankType, $countryId, $crewIpn) {

        return $this->db->select([
            "d.id",
            "d.document_type",
            "d.document_code",
            "d.document_code_mk",
            "d.document_name",
            "c.code as country_code"
        ])  ->from("cat_documents d")
            ->join("document_rank_type dr", "dr.document_id = d.id", "left")
            ->join("country c", "c.id = d.country_id", "left")
            ->where("d.status", 1)
            ->where("dr.status", 1)
            ->where("dr.rank_type_id", $rankType)
            ->where("dr.country_id", $countryId)
            ->group_by("d.id")
            ->order_by("d.document_type, c.id, d.document_name")
            ->get()->result();

    }

}
