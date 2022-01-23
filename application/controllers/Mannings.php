<?php
class Mannings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if(! $this->session->userdata('validated')) {
            $this->session->set_flashdata('errors', ["PLEASE LOGIN FIRST!"]);
            redirect('/');
        }
//        $this->verifyPassword();
        $this->load->model("document");
        $this->load->model("manning");
    }

    public function verifyPassword() {
        $dateUpdated = date_create($this->session->userdata('password_updated_at'));
        $dateNow = date_create(date('Y-m-d'));
        $interval = date_diff($dateUpdated, $dateNow);
        $daysDifference = $interval->format('%a');

        if(!$this->session->userdata('password_updated_at') || $daysDifference > 180) {
            redirect("users/changePassword");
        }
    }

    public function index($manningId = null) {
        $this->load->model('manning');
        $data = [];
        if($this->session->userdata('type') == "Staff") {
            $manningId = $this->session->userdata("manning_id");
//            $data['uploadedDocs'] = $this->document->countUploadedDocsByManning($manningId);
            $data['uploadedDocs'] = $this->document->countUploadedDocsByUser();
        }
        if($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->load->model('crew');
            $keyword = $this->input->post('cat_keyword');
            $key = $this->input->post('keyword');
            $data['crews'] = $this->crew->findByKeyword($keyword, $key, $manningId);
            $data['foldercode'] = [
                "FIL" => "PHL", "CHI" => "CHI", "BUL" => "BUL", "UKR" => "UKR", "JPN" => "JPN",
            ];
        }

        $data['manning'] = $this->manning->getManning($manningId);

        $data['content'] = 'pages/manning/index.php';
        $this->load->view('templates/template', $data);
    }

    public function uploadedDocs() {
        $data = [];
        $data['manning'] = $this->manning->getManning($this->session->userdata("manning_id"));
        $data['crew_list'] = $this->document->uploadedDocs($this->session->userdata("manning_id"));

        $data['content'] = 'pages/manning/uploadedDocs.php';
        $this->load->view('templates/template', $data);
    }

    public function companyLicense($manningId = null) {
        if($this->session->userdata('type') !== 'Principal') {
            $manningId = $this->session->userdata("manning_id");
        }
        $data = [];
        $data['manning'] = $this->manning->getManning($manningId);
        $data['companyFiles'] = $this->document->companyFiles($manningId);

        $data['content'] = 'pages/manning/companyLicense.php';
        $this->load->view('templates/template', $data);
    }

    public function deleteCompanyLicense($path = null, $fileName = null, $documentId = null) {
        $success = unlink(DOCFOLDER.$path.'/company_license/'.$fileName.'.pdf');
        if($success) {
            $this->load->model("user");
            $this->load->model("historyLog");
            $userId = $this->user->findStaffByLogin()->staff_id;
            $documentName = $this->db->select("document_name")->from("cat_documents")->where("id", $documentId)->get()->row()->document_name;

            $description = "deleted $path $documentName";
            $logData = [
                "account_id" => $userId,
                "principal" => 0,
                "manning_id" => $this->session->userdata('manning_id'),
                "event_description" => $description,
                "logged_at" => date("Y-m-d H:i:s")
            ];
            $this->historyLog->addLog($logData);
        }
        redirect('mannings/companyLicense');
    }

    public function uploadCompanyFile($path = null, $docId = null) {
        $data = [];
        $data['manning'] = $this->manning->getManning($this->session->userdata("manning_id"));
        $data['path'] = $path;
        $data['docno'] = $docId;
        $data['document'] = $this->document->findById($docId);

        $data['content'] = 'pages/manning/uploadCompanyFile.php';
        $this->load->view('templates/template', $data);
    }

    public function addCompanyLicense($path = null, $docId = null, $countryCode = null, $mkCode = null) {
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('errors', "INVALID ACCESS METHOD");
        } elseif(!$_FILES['userfile']['size']) {
            $this->session->set_flashdata('errors', "NO FILES DETECTED");
        } else {
            $config['upload_path'] = DOCFOLDER."$path/company_license";
            if(!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'],0777,TRUE);
                chmod($config['upload_path'],0777);
            }
            $config['allowed_types'] = 'pdf';
            $config['max_size']	= '1000';
            $config['max_width']  = '1024';
            $config['max_height']  = '768';
            $config['file_name'] = "$countryCode$mkCode.pdf";
            $this->load->library('upload', $config);
            if ($this->upload->do_upload()) {
                $data = array(
                    'document_code' => $docId,
                    'uploaded_date' => date('Y-m-d H:i:s'),
                    'manning_id' => $this->session->userdata('manning_id'),
                    'staff_account_name' => $this->session->userdata('staff_no')
                );
                $this->db->insert('document_upload_history', $data);
                $file = $this->upload->data()['full_path'];
                chmod($file,0777);

                $this->load->model("user");
                $this->load->model("historyLog");
                $userId = $this->user->findStaffByLogin()->staff_id;

                $documentName = $this->db->select("document_name")->from("cat_documents")->where("id", $docId)->get()->row()->document_name;
                $description = "uploaded $path $documentName";
                $logData = [
                    "account_id" => $userId,
                    "principal" => 0,
                    "manning_id" => $this->session->userdata('manning_id'),
                    "event_description" => $description,
                    "logged_at" => date("Y-m-d H:i:s")
                ];
                $this->historyLog->addLog($logData);

                $this->session->set_flashdata('success', "COMPANY FILE SUCCESSFULLY UPLOADED");
            } else {
                $errors = $this->upload->display_errors();
            }
        }
        redirect("mannings/uploadCompanyFile/$path/$docId");
    }

    public function addVessel($manningId = null) {

        if($this->session->userdata('type') != "Principal") {
            redirect("principals");
        }
        if($manningId) {
            $data['manning'] = $this->manning->getManning($manningId);
            $data['vessels'] = $this->manning->findVesselByManningId($manningId);
        } else {
            $data['manning'] = $this->manning->findByStaffLogin();
            $data['vessels'] = $this->manning->findVesselByStaffLogin();
        }

        $data['content'] = 'pages/manning/addVessel.php';
        $this->load->view('templates/template', $data);
    }

    public function storeVessel() {
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('errors', "INVALID ACCESS METHOD");
        } else {
            $data = array(
                'vessel_name' => str_replace('"', '', $this->input->post('vessel_name')) ,
                'manning_id' => $this->input->post('manning_id')
            );
            $this->db->insert('cat_vessel', $data);

            $this->load->model("user");
            $this->load->model("historyLog");
            $userId = $this->user->findStaffByLogin()->staff_id;

            $description = "added vessel ".$this->input->post('vessel_name');
            $logData = [
                "account_id" => $userId,
                "principal" => 0,
                "manning_id" => $this->session->userdata('manning_id'),
                "event_description" => $description,
                "logged_at" => date("Y-m-d H:i:s")
            ];
            $this->historyLog->addLog($logData);

            $this->session->set_flashdata('success', "NEW VESSEL SUCCESSFULLY ADDED");
        }
        redirect("mannings/addVessel");
    }

    public function editVessel($vesselId) {
        $data['manning'] = $this->manning->findByStaffLogin();
        $data['vessel'] = $this->manning->findVesselById($vesselId);

        $data['content'] = 'pages/manning/editVessel.php';
        $this->load->view('templates/template', $data);
    }

    public function updateVessel() {
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('errors', "INVALID ACCESS METHOD");
        } else {
            $manningId = $this->input->post('manning_id');
            $data = array(
                'vessel_name' => $this->input->post('vessel_name'),
                'manning_id' => $manningId
            );

            $this->load->model("user");
            $this->load->model("historyLog");
            $userId = $this->user->findStaffByLogin()->staff_id;
            $vesselName = $this->db->select('vessel_name')->from('cat_vessel')->where('vessel_id', $this->input->post('vessel_id'))->get()->row()->vessel_name;

            $description = "updated vessel from $vesselName to ".$this->input->post('vessel_name');
            $logData = [
                "account_id" => $userId,
                "principal" => 0,
                "manning_id" => $this->session->userdata('manning_id'),
                "event_description" => $description,
                "logged_at" => date("Y-m-d H:i:s")
            ];
            $this->historyLog->addLog($logData);

            $this->db->where('vessel_id', $this->input->post('vessel_id'));
            $this->db->update('cat_vessel', $data);
            $this->session->set_flashdata('success', "VESSEL SUCCESSFULLY UPDATED");
        }
        redirect("mannings/addVessel/$manningId");
    }

}
