<?php

class Documents extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('validated')) {
            $this->session->set_flashdata('errors', ["PLEASE LOGIN FIRST!"]);
            redirect('/');
        }
        $this->load->model('document');
    }

    public function index() {
        $data['documents'] = $this->document->findAllDocuments();

        $data['content'] = 'pages/document/index.php';
        $this->load->view('templates/wide_template', $data);
    }

    public function edit($documentId) {

        $this->load->model("country");
        $this->load->model("rankType");

        $data['countries'] = $this->country->findAll();
        $data['rankTypes'] = $this->rankType->findAll();

        $data['document'] = $this->document->findById($documentId);
        $data['documentTypes'] = $this->document->getDocTypes();

        $data['content'] = 'pages/document/edit.php';
        $this->load->view('templates/wide_template', $data);
    }

    public function update() {
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('errors', "UNAUTHORIZED METHOD!");
            redirect("documents");
        }

        $documentId = $this->input->post("documentId");
        $documentCode = $this->input->post("documentCode");
        $mkCode = $this->input->post("mkCode");
        $documentName = $this->input->post("documentName");
        $documentType = $this->input->post("documentType");
        $country = $this->input->post("country");
        $active = $this->input->post("status")?"1" : "0";
        $matrix = $this->input->post("matrix");

        $oldDocumentRecord = $this->db->select(["d.document_code", "d.document_name", "d.document_type", "d.document_code_mk", "d.status", "c.name", "c.nationality_code", "c.code"])
            ->from("cat_documents d")
            ->join("country c", "c.id = d.country_id", "left")
            ->where("d.id", $documentId)->get()->row();
        $oldMatrices = $this->db->select(["t.name as rankType", "c.name as country"])
            ->from("document_rank_type dr")
            ->join("rank_type t", "dr.rank_type_id = t.id", "left")
            ->join("country c", "c.id = dr.country_id", "left")
            ->where("dr.document_id", $documentId)
            ->where("dr.status", 1)
            ->get()->result();

        $this->db->where("document_id", $documentId)->update("document_rank_type", ["status" => 0]);
        foreach ($matrix as $rankTypeIndex => $rankTypes) {
            foreach ($rankTypes as $nationalityIndex => $nationality) {
                $this->db->where("document_id", $documentId)
                    ->where("rank_type_id", $rankTypeIndex)
                    ->where("country_id", $nationalityIndex)
                    ->update("document_rank_type", ["status" => 1]);
            }
        }

        $bansa = $this->db->get_where("country", ["id" => $country])->row();

        $updatedDocument = [
            "document_code" => $documentCode,
            "document_name" => $documentName,
            "document_type" => $documentType,
            "country_id" => $country,
            "status" => $active,
            "manning_nationality" => $bansa->nationality_code,
            "country_code" => $bansa->code,
            "document_code_mk" => $mkCode,
        ];

        if($this->db->where("id", $documentId)->update("cat_documents", $updatedDocument)) {

            $this->load->model("user");
            $this->load->model("historyLog");
            $this->load->model("manning");
            $userId = $this->user->findPrincipalByLogin()->principal_id;
            $oldValues = [
                "code" => $oldDocumentRecord->document_code,
                "name" => $oldDocumentRecord->document_name,
                "type" => $oldDocumentRecord->document_type,
                "country" => $oldDocumentRecord->name,
                "status" => ($oldDocumentRecord->status) ?"active" :"inactive",
                "nationality" => $oldDocumentRecord->nationality_code,
                "country code" => $oldDocumentRecord->code,
                "mk code" => $oldDocumentRecord->document_code_mk,
            ];
            $oldValues['matrix'] = [];
            foreach ($oldMatrices as $oldMatrix) {
                if(!isset($oldValues['matrix'][$oldMatrix->rankType])) $oldValues['matrix'][$oldMatrix->rankType] = "";
                $oldValues['matrix'][$oldMatrix->rankType] .= "$oldMatrix->country, ";
            }
            foreach ($oldValues['matrix'] as $w => $x) {
                if($x) $oldValues['matrix'][$w] = substr($x, 0, strripos($x, ","));
            }
            $newMatrices = $this->db->select(["t.name as rankType", "c.name as country"])
                ->from("document_rank_type dr")
                ->join("rank_type t", "dr.rank_type_id = t.id", "left")
                ->join("country c", "c.id = dr.country_id", "left")
                ->where("dr.document_id", $documentId)
                ->where("dr.status", 1)
                ->get()->result();
            $newValues = [
                "code" => $documentCode,
                "name" => $documentName,
                "type" => $documentType,
                "country" => $bansa->name,
                "status" => ($active) ?"active" :"inactive",
                "nationality" => $bansa->nationality_code,
                "country code" => $bansa->code,
                "mk code" => $mkCode,
            ];
            $newValues['matrix'] = [];
            foreach ($newMatrices as $newMatrix) {
                if(!isset($newValues['matrix'][$newMatrix->rankType])) $newValues['matrix'][$newMatrix->rankType] = "";
                $newValues['matrix'][$newMatrix->rankType] .= "$newMatrix->country, ";
            }
            foreach ($newValues['matrix'] as $w => $x) {
                if($x) $newValues['matrix'][$w] = substr($x, 0, strripos($x, ","));
            }
            $updatedColumn = "";
            foreach ($oldValues as $index => $oldValue) {
                if($oldValue != $newValues[$index]) {
                    var_dump("OLD VALUE: ", $oldValue);
                    echo "<br />";
                    var_dump("NEW VALUE: ", $newValues[$index]);
                    $updatedColumn.="$index, ";
                }
            }
            if($updatedColumn) $updatedColumn = substr($updatedColumn, 0, strripos($updatedColumn, ","))." of";
            $description = "updated $updatedColumn $oldDocumentRecord->document_name";
            $logData = [
                "account_id" => $userId,
                "principal" => 1,
                "manning_id" => $this->session->userdata('manning_id'),
                "event_description" => $description,
                "old_value" => json_encode($oldValues),
                "new_value" => json_encode($newValues),
                "logged_at" => date("Y-m-d H:i:s")
            ];
            $this->historyLog->addLog($logData);
            $this->session->set_flashdata('success', "DOCUMENT SUCCESSFULLY UPDATED!!");
        } else {
            $this->session->set_flashdata('errors', "DOCUMENT FAILED TO UPDATE!!");
        }

        redirect("documents/edit/$documentId");

    }

    public function create() {
        $this->load->model("country");
        $this->load->model("rankType");
        $data['countries'] = $this->country->findAll();
        $data['rankTypes'] = $this->rankType->findAll();
        $data['documentTypes'] = $this->document->getDocTypes();

        $data['content'] = 'pages/document/create.php';
        $this->load->view('templates/wide_template', $data);
    }

    public function store() {
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('errors', "UNAUTHORIZED METHOD!");
            redirect("documents");
        }

        $documentCode = $this->input->post("documentCode");
        $documentName = $this->input->post("documentName");
        $mkCode = $this->input->post("mkCode");
        $documentType = $this->input->post("documentType");
        $country = $this->input->post("country");
        $active = $this->input->post("status")?"1" : "0";
        $matrix = $this->input->post("matrix");

        $count = $this->db->select("count(*) as count")
            ->from("cat_documents")
            ->where("document_code", $documentCode)
            ->or_where("document_code_mk", $documentCode)
            ->get()->row()->count;

        if($count) {
            $this->session->set_flashdata('errors', "DOCUMENT CODE ALREADY EXISTS!!");
        } else {
            $bansa = $this->db->get_where("country", ["id" => $country])->row();

            $updatedDocument = [
                "document_code" => $documentCode,
                "document_name" => $documentName,
                "document_type" => $documentType,
                "country_id" => $country,
                "status" => $active,

                "manning_nationality" => $bansa->nationality_code,
                "country_code" => $bansa->code,
                "document_code_mk" => $mkCode,
                "date_created" => date("Y-m-d")
            ];

            if($this->db->insert('cat_documents', $updatedDocument)) {
                $documentId = $this->db->insert_id();
                $this->load->model("country");
                $this->load->model("rankType");
                $this->load->model("user");
                $this->load->model("historyLog");
                $this->load->model("manning");
                $userId = $this->user->findPrincipalByLogin()->principal_id;
                $countries = $this->country->findActive();
                $existingRankTypes = $this->rankType->findAll();

                foreach ($existingRankTypes as $rankType) {
                    foreach ($countries as $cntry) {
                        $documentRankType = [
                            "document_id" => $documentId,
                            "rank_type_id" => $rankType->id,
                            "country_id" => $cntry->id,
                            "status" => 0
                        ];
                        $this->db->insert('document_rank_type', $documentRankType);
                    }
                }

                $newValues = [
                    "code" => $documentCode,
                    "name" => $documentName,
                    "type" => $documentType,
                    "country" => $bansa->name,
                    "status" => ($active) ?"active" :"inactive",
                    "nationality" => $bansa->nationality_code,
                    "country code" => $bansa->code,
                    "mk code" => $mkCode,
                ];
                $newValues['matrix'] = [];
                $rts = [];
                foreach ($existingRankTypes as $t) {
                    $rts[$t->id] = $t->name;
                }

                $cs = [];
                foreach ($countries as $cntry) {
                    $cs[$cntry->id] = $cntry->name;
                }

                foreach ($matrix as $rankTypeIndex => $rankTypes) {
                    $matrixRecord = "";
                    if(!isset($newValues['matrix'][$rts[$rankTypeIndex]])) $newValues['matrix'][$rts[$rankTypeIndex]] = "";
                    foreach ($rankTypes as $nationalityIndex => $nationality) {
                        $matrixRecord .= $cs[$nationalityIndex].", ";
                        $this->db->where("document_id", $documentId)
                            ->where("rank_type_id", $rankTypeIndex)
                            ->where("country_id", $nationalityIndex)
                            ->update("document_rank_type", ["status" => 1]);
                    }
                    if($matrixRecord) $newValues['matrix'][$rts[$rankTypeIndex]] = substr($matrixRecord, 0 , strripos($matrixRecord, ","));
                }
                $description = "added $documentName with code $documentCode";
                $logData = [
                    "account_id" => $userId,
                    "principal" => 1,
                    "manning_id" => $this->session->userdata('manning_id'),
                    "event_description" => $description,
                    "new_value" => json_encode($newValues),
                    "logged_at" => date("Y-m-d H:i:s")
                ];
                $this->historyLog->addLog($logData);
                $this->session->set_flashdata('success', "$documentCode DOCUMENT SUCCESSFULLY CREATED!!");
            } else {
                $this->session->set_flashdata('errors', "$documentCode DOCUMENT FAILED TO SAVE!!");
            }
        }

        redirect("documents/create");

    }

    public function getDocumentMatrix() {
        $selections = [
            'd.id',
            'd.document_name'
        ];
        $documents = $this->db->select($selections)
            ->from('cat_documents d')
            ->where('d.status', 1)
            ->where('d.document_type', 'C')
//            ->where_in('d.document_type', ['D', 'L'])
            ->order_by('d.document_name')
            ->get()->result();
        foreach ($documents as $document) {
            $matrix = $this->db->select()
                ->from('document_rank_type dr')
                ->where('dr.document_id', $document->id)
                ->where('status', 1)
                ->where('country_id', 1)->get();
            if($matrix->num_rows()) {
                echo "$document->id: $document->document_name,  <br />";
                foreach ($matrix->result() as $mtrx) {
                    var_dump($mtrx);
                    echo "<br />";
                }
                echo "<hr />";

            }


        }
    }

}
