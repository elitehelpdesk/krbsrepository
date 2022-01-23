<?php
class Crews extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('validated')) {
            $this->session->set_flashdata('errors', ["PLEASE LOGIN FIRST!"]);
            redirect('/');
        }
        $this->load->model('crew');
        $this->load->model('document');
    }

    public function show($id) {

//        if($this->session->userdata('principal_no') != 'PRIN-0030') {
        if(true) {

            $data = [];

            $crew = $this->crew->findById($id);
            $data['crew'] = $crew;
            if ($this->session->userdata('type') == "Staff") {
                if($crew->MANNING_ID != $this->session->userdata('manning_id')) {
                    $this->session->set_flashdata('errors', ["UNAUTHORIZED CREW ACCESS!"]);
                    redirect('/');
                }
            }
            $data['flags'] = [
                ['code'=>'PNM', 'id'=>'panama', 'name'=>'PANAMA', 'count'=>0],
                ['code'=>'MAR', 'id'=>'marshall', 'name'=>'MARSHALL ISLANDS', 'count'=>0],
                ['code'=>'PHL', 'id'=>'philippines', 'name'=>'PHILIPPINES', 'count'=>0],
//                ['code'=>'PHL', 'id'=>'philippines', 'name'=>'CERT. & LIC.', 'count'=>0],
                ['code'=>'CHI', 'id'=>'china', 'name'=>'CHINA', 'count'=>0],
                ['code'=>'BUL', 'id'=>'bulgaria', 'name'=>'BULGARIA', 'count'=>0],
                ['code'=>'UKR', 'id'=>'ukraine', 'name'=>'UKRAINE', 'count'=>0],
                ['code'=>'JPN', 'id'=>'japan', 'name'=>'JAPAN', 'count'=>0],
                ['code'=>'HKG', 'id'=>'hongkong', 'name'=>'HONG KONG', 'count'=>0],
                ['code'=>'CAY', 'id'=>'cayman', 'name'=>'CAYMAN ISLANDS', 'count'=>0],
                ['code'=>'MAL', 'id'=>'malaysia', 'name'=>'MALAYSIA', 'count'=>0],
                ['code'=>'LIB', 'id'=>'libya', 'name'=>'LIBERIA', 'count'=>0],
                ['code'=>'SIN', 'id'=>'singapore', 'name'=>'SINGAPORE', 'count'=>0],
            ];
//            $data['documents'] = $this->document->findByRankAndCountry($crew->rank_type_id, $crew->country_id, $crew->CREWIPN);
            $this->load->model("country");
//            $data['countries'] = $this->country->findAll();
//            $data['crew_cer_list'] = $this->crew->findCrewCer($crew->CREWIPN);
//            $vesselCrewComments = array();
//            foreach ($data['crew_cer_list'] as $cer) {
//                $vesselCrewComments[$cer->id] = $this->crew->crewComment($cer->id, $data['crew']->ID)->result();
//            }
//            $data['vesselCrewComments'] = $vesselCrewComments;
//            $data['commentators'] = $this->crew->commentators();

            $data['content'] = 'pages/crew/show1.php';
            $this->load->view('templates/template', $data);
        } else {

            $data = [];
            $data['crew'] = $this->crew->findById($id);

            if ($this->session->userdata('type') == "Staff") {
                if($data['crew']->MANNING_ID != $this->session->userdata('manning_id')) {
                    $this->session->set_flashdata('errors', ["UNAUTHORIZED CREW ACCESS!"]);
                    redirect('/');
                }
            }

            $data['documents'] = $this->document->getDocs($data['crew']->manning_nationality);

            $data['crew_cer_list'] = $this->crew->findCrewCer($data['crew']->CREWIPN);

            $vesselCrewComments = array();
            foreach ($data['crew_cer_list'] as $cer) {
                $vesselCrewComments[$cer->id] = $this->crew->crewComment($cer->id, $data['crew']->ID)->result();
            }
            $data['vesselCrewComments'] = $vesselCrewComments;
            $data['commentators'] = $this->crew->commentators();

            $data['con'] = mysqli_connect("127.0.0.1","tnkcgrp","tnkcgroup","tnkc_bassnet");
            $data['flags'] = [
                ['code'=>'PNM', 'id'=>'panama', 'name'=>'PANAMA', 'count'=>0],
                ['code'=>'MAR', 'id'=>'marshall', 'name'=>'MARSHALL ISLANDS', 'count'=>0],
                ['code'=>'PHL', 'id'=>'philippines', 'name'=>'PHILIPPINES', 'count'=>0],
                ['code'=>'CHI', 'id'=>'china', 'name'=>'CHINA', 'count'=>0],
                ['code'=>'BUL', 'id'=>'bulgaria', 'name'=>'BULGARIA', 'count'=>0],
                ['code'=>'UKR', 'id'=>'ukraine', 'name'=>'UKRAINE', 'count'=>0],
                ['code'=>'JPN', 'id'=>'japan', 'name'=>'JAPAN', 'count'=>0],
                ['code'=>'HKG', 'id'=>'hongkong', 'name'=>'HONG KONG', 'count'=>0],
                ['code'=>'CAY', 'id'=>'cayman', 'name'=>'CAYMAN ISLANDS', 'count'=>0],
                ['code'=>'MAL', 'id'=>'malaysia', 'name'=>'MALAYSIA', 'count'=>0],
                ['code'=>'LIB', 'id'=>'libya', 'name'=>'LIBYA', 'count'=>0],
                ['code'=>'SIN', 'id'=>'singapore', 'name'=>'SINGAPORE', 'count'=>0]
            ];

            $this->load->view("templates/header");
            $this->load->view("templates/user_nav");
            $this->load->view("pages/crew/show.php", $data);
            $this->load->view("templates/footer");

        }
    }

    public function saveCrewComment() {
        $count = count($_FILES['userfile']['name']);

        $crewid = $this->input->post('crewid');
        $vesselId = $this->input->post('vesselId');
        $crewComment = $this->input->post('comment');
        $principal_id = $this->session->userdata('principal_id');
        $commentator = $this->input->post('commentator');
        $withAttachment = "";

        if($_FILES['userfile']['size'][0]) {
            for($i=0; $i<$count; $i++) {
                if($_FILES['userfile']['error'][$i] != UPLOAD_ERR_OK) {
                    $this->session->set_flashdata('errors', "Failed to save comment, File ".$_FILES['userfile']['name'][$i]." have errors.");
                    redirect("crews/show/$crewid");
                }
            }
        }

        $data = array(
            "crew_id"=>$crewid,
            "cer_id"=>$vesselId,
            "comment"=>$crewComment,
            "principal_id"=>$principal_id,
            "commentator_id"=>$commentator,
            "commented_at"=>date('Y-m-d H:i:s'),
            "last_updated_at"=>date('Y-m-d H:i:s')
        );
        $this->db->insert('crew_comments', $data);
        if($_FILES['userfile']['size'][0]) {
            $withAttachment = "(with attachment)";
            include_once APPPATH.'vendor/autoload.php';
            $pdf = new erc\pdfmerger\PDFMerger();
            $newFiles = [];
            $name = $this->db->insert_id();
            $crew = $this->crew->findById($crewid);
            $uploadPath = DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN";

            if(!is_dir($uploadPath)) {
                mkdir($uploadPath,0770,TRUE);
                chmod($uploadPath,0770);
            }
            $uploadPath .= "/comments";
            if(!is_dir($uploadPath)) {
                mkdir($uploadPath,0770,TRUE);
                chmod($uploadPath,0770);
            }

            try{
                for($i=0; $i<$count; $i++) {
                    $newFilePath = ROOTFOLDER.time()."$crewid$i.pdf";
                    $file = $_FILES['userfile']['tmp_name'][$i];
                    shell_exec("gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=$newFilePath $file");
                    $pdf->addPDF($newFilePath);
                    $newFiles[] = $newFilePath;
                }
                if(file_exists("$uploadPath/$name.pdf")) {
                    unlink("$uploadPath/$name.pdf");
                }
                $pdf->merge('file', "$uploadPath/$name.pdf");
            } catch(Exception $e){
                exit($e->getMessage());
            }
            foreach ($newFiles as $newFile)  unlink($newFile);
            $this->session->set_flashdata('success', "CREW COMMENT SUCCESSFULLY SAVED");
        }

        $crew = $this->db->where('ID', $crewid)->get("crew")->row();

        $this->load->model("user");
        $this->load->model("historyLog");
        $vessel = $this->db->query("select vessel_name from cat_vessel v where v.vessel_id = (select c.vessel_id from crew_cer c where c.id = $vesselId)")->row();
        $commentatorList = $this->db->select()->from("commentators")->get()->result();
        $commentators = [];
        foreach ($commentatorList as $com) {
            $commentators[$com->id] = $com->name;
        }

        $newValues = [
            "comment"=>$crewComment,
            "commentator"=>$commentators[$commentator],
        ];

        $userId = $this->user->findPrincipalByLogin()->principal_id;



        $description = "Added crew comment to crew with Crew IPN of $crew->CREWIPN for vessel $vessel->vessel_name $withAttachment";

        $logData = [
            "account_id" => $userId,
            "principal" => 1,
            "manning_id" => $this->session->userdata('manning_id'),
            "event_description" => $description,
            "new_value" => json_encode($newValues),
            "logged_at" => date("Y-m-d H:i:s")
        ];

        $this->historyLog->addLog($logData);

        redirect("crews/show/$crewid");
    }

    public function updateCrewComment($crewid) {
        $count = count($_FILES['userfile']['name']);
        if($_FILES['userfile']['size'][0]) {
            for($i=0; $i<$count; $i++) {
                if($_FILES['userfile']['error'][$i] != UPLOAD_ERR_OK) {
                    $this->session->set_flashdata('errors', "Failed to save comment, File ".$_FILES['userfile']['name'][$i]." have errors.");
                    redirect("crews/show/$crewid");
                }
            }
        }
        $ccid = $this->input->post('crewcommentid');
        $comment = trim($this->input->post('crewcommenttext'));
        $comentator = trim($this->input->post('comentator'));
        $crewComment = $this->db->where('id', $ccid)->get('crew_comments')->row();
        $data = array(
            'comment' => $comment,
            'commentator_id' => $comentator,
            "last_updated_at"=>date('Y-m-d H:i:s')
        );
        $this->db->where('id', $ccid);
        $this->db->update('crew_comments', $data);
        $withAttachment = "";
        if($_FILES['userfile']['size'][0]) {
            $withAttachment = "(with attachment)";
            include_once APPPATH.'vendor/autoload.php';
            $pdf = new erc\pdfmerger\PDFMerger();
            $newFiles = [];
            $crew = $this->crew->findById($crewid);
            $uploadPath = DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN";

            if(!is_dir($uploadPath)) {
                mkdir($uploadPath,0770,TRUE);
                chmod($uploadPath,0770);
            }

            $uploadPath = DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/comments";
            if(file_exists("$uploadPath/$ccid.pdf")) {
                unlink("$uploadPath/$ccid.pdf");
            }
            if(!is_dir($uploadPath)) {
                mkdir($uploadPath,0770,TRUE);
                chmod($uploadPath,0770);
            }

            try{
                for($i=0; $i<$count; $i++) {
                    $newFilePath = ROOTFOLDER.time()."$crewid$i.pdf";
                    $file = $_FILES['userfile']['tmp_name'][$i];
                    shell_exec("gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=$newFilePath $file");
                    $pdf->addPDF($newFilePath);
                    $newFiles[] = $newFilePath;
                }
                if(file_exists("$uploadPath/$ccid.pdf")) {
                    unlink("$uploadPath/$ccid.pdf");
                }
                $pdf->merge('file', "$uploadPath/$ccid.pdf");
            } catch(Exception $e){
                exit($e->getMessage());
            }
            foreach ($newFiles as $newFile)  unlink($newFile);
            $this->session->set_flashdata('success', "CREW COMMENT SUCCESSFULLY SAVED");

//            $config['upload_path'] = $uploadPath;
//            $config['allowed_types'] = 'pdf';
//            $config['max_size']	= '1000';
//            $config['max_width']  = '1024';
//            $config['max_height']  = '768';
//            $config['file_name'] = "$ccid.pdf";
//            $this->load->library('upload', $config);
//            if ( ! $this->upload->do_upload()) {
//                $this->session->set_flashdata('errors', $this->upload->display_errors());
////                $data['main_content'] = 'staff_document_upload';
////                $this->load->view('includes/template_topnav_staff', $data);
//            } else {
//                $uploadData = $this->upload->data();
//                $file = $uploadData['full_path'];
//                chmod($file,0770);
//                $this->session->set_flashdata('success', "CREW COMMENT SUCCESSFULLY UPDATED");
//            }

        }

        $crew = $this->db->where('ID', $crewid)->get("crew")->row();

        $this->load->model("user");
        $this->load->model("historyLog");
        $vessel = $this->db->query("select vessel_name from cat_vessel v where v.vessel_id = (select c.vessel_id from crew_cer c where c.id = $crewComment->cer_id)")->row();
        $commentatorList = $this->db->select()->from("commentators")->get()->result();
        $commentators = [];
        foreach ($commentatorList as $com) {
            $commentators[$com->id] = $com->name;
        }

        $oldValues = [
            "comment"=>$crewComment->comment,
            "commentator"=>$commentators[$crewComment->commentator_id],
        ];

        $newValues = [
            "comment"=>$comment,
            "commentator"=>$commentators[$comentator],
        ];

        $userId = $this->user->findPrincipalByLogin()->principal_id;

        $description = "Updated crew comment to crew with Crew IPN of $crew->CREWIPN to vessel $vessel->vessel_name $withAttachment";

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

        redirect("crews/show/$crewid");
    }

    public function addcer($crewid) {
        $data = [];
        $data['crew'] = $this->crew->findById($crewid);
        $this->load->model('vessel');
        $data['vessels'] = $this->vessel->findByManning($data['crew']->MANNING_ID);
        $data['crew_cer_counter_list'] = $this->crew->crew_cer_counter();

        $data['content'] = 'pages/crew/addcer.php';
        $this->load->view('templates/template', $data);
    }

    public function storecer($crewid) {
        if($this->input->server('REQUEST_METHOD') == 'POST') {
            $crewIpn = $this->input->post('crewipn');
            $vesselId = $this->input->post('vessel_id');
            $manningId = $this->input->post('manning_id');
            $cerInitialId = $this->input->post('cercounter');
            $data = array(
                'crewipn' => $crewIpn ,
                'vessel_id' => $vesselId,
                'manning_id' => $manningId,
                'cer_initial_id' => $cerInitialId,
                'date_uploaded' => date('Y-m-d H:i:s')
            );
            if($this->db->insert('crew_cer', $data)) {

                $crew = $this->db->where('CREWIPN', $crewIpn)->get("crew")->row();

                $this->load->model("user");
                $this->load->model("historyLog");
                $vessel = $this->db->query("select vessel_name from cat_vessel v where v.vessel_id = $vesselId")->row();

                if($this->session->userdata('type') == 'Staff') {
                    $userId = $this->user->findStaffByLogin()->staff_id;
                    $principal = 0;
                } else {
                    $userId = $this->user->findPrincipalByLogin()->principal_id;
                    $principal = 1;
                }



                $description = "Added $vessel->vessel_name CER for crew with Crew IPN of $crew->CREWIPN";

                $logData = [
                    "account_id" => $userId,
                    "principal" => $principal,
                    "manning_id" => $this->session->userdata('manning_id'),
                    "event_description" => $description,
                    "logged_at" => date("Y-m-d H:i:s")
                ];

                $this->historyLog->addLog($logData);

                $this->session->set_flashdata('success', "CER SUCCESSFULLY SAVED!");
            } else {
                $this->session->set_flashdata('errors', "CER WAS NOT SUCCESSFULLY SAVED!");
            }
            redirect("crews/show/$crewid");
        }
    }

    public function deleteDoc($crewipn = null, $path = null, $docName = null, $crewid = null, $documentId = null) {
        if(file_exists(DOCFOLDER.$path.'/'.$crewipn.'/'.$docName.'.pdf'))
            $document = DOCFOLDER.$path.'/'.$crewipn.'/'.$docName.'.pdf';
        else
            $document = DOCFOLDER.$path.'/'.$crewipn.'/'.$docName.'.PDF';
        if(unlink($document)) {

            $crew = $this->db->where('CREWIPN', $crewipn)->get("crew")->row();
            $documentName = $this->db->select("document_name")->from("cat_documents")->where("id", $documentId)->get()->row();

            $this->load->model("user");
            $this->load->model("historyLog");

            $userId = $this->user->findStaffByLogin()->staff_id;

            $description = "deleted $documentName->document_name document of $crew->FNAME, $crew->GNAME, $crew->MNAME";

            $logData = [
                "account_id" => $userId,
                "principal" => 0,
                "manning_id" => $this->session->userdata('manning_id'),
                "event_description" => $description,
                "logged_at" => date("Y-m-d H:i:s")
            ];

            $this->historyLog->addLog($logData);

            $this->session->set_flashdata('success', "DOCUMENT SUCCESSFULLY DELETED!");
        }
        else
            $this->session->set_flashdata('errors', "DOCUMENT NOT DELETED!");

        redirect("crews/show/$crewid");
    }

    public function uploadDoc($path = null, $docId = null, $crewId = null) {
        $data = [];
        $data['crew'] = $this->crew->findById($crewId);
        $data['path'] = $path;
        $data['document'] = $this->document->findById($docId);

        $data['content'] = 'pages/crew/uploadCrewDocument.php';
        $this->load->view('templates/template', $data);
    }

    public function storeDoc($manningId = null, $docId = null, $crewIpn = null) {
        $crew = $this->crew->findByCrewIpn($crewIpn);
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('errors', "INVALID ACCESS METHOD");
//            redirect("/");
        } elseif(!$_FILES['userfile']['size']) {
            $this->session->set_flashdata('errors', "NO FILES DETECTED");
            redirect("crews/show/$crew->ID");
        } else {
//            $document = $this->document->findById($docId);
//            $this->load->model('manning');
//            $manning = $this->manning->getManning($manningId);
//            $config['upload_path'] = DOCFOLDER."$manning->manning_folder_name/$crewIpn";
//            if(!is_dir($config['upload_path'])) {
//                mkdir($config['upload_path'],0777,TRUE);
//                chmod($config['upload_path'],0777);
//            }
//            $config['allowed_types'] = 'pdf';
//            $config['max_size']	= '1000';
//            $config['max_width']  = '1024';
//            $config['max_height']  = '768';
//            $config['file_name'] = "$crewIpn$document->country_code$document->document_code_mk.pdf";
//            $this->load->library('upload', $config);
//            if (!$this->upload->do_upload()) {
//                $this->session->set_flashdata('errors', $this->upload->display_errors());
//            } else {
//
//                $crew = $this->db->where('CREWIPN', $crewIpn)->get("crew")->row();
//                $documentName = $this->db->select("document_name")->from("cat_documents")->where("id", $docId)->get()->row();
//
//                $this->load->model("user");
//                $this->load->model("historyLog");
//
//                $userId = $this->user->findStaffByLogin()->staff_id;
//
//                $description = "uploaded $documentName->document_name document of $crew->FNAME, $crew->GNAME, $crew->MNAME";
//
//                $logData = [
//                    "account_id" => $userId,
//                    "principal" => 0,
//                    "manning_id" => $this->session->userdata('manning_id'),
//                    "event_description" => $description,
//                    "logged_at" => date("Y-m-d H:i:s")
//                ];
//
//                $this->historyLog->addLog($logData);
//
//                $data = array(
//                    'crewipn' => $crewIpn,
//                    'applicantno' => $crewIpn,
//                    'document_code' => $docId,
//                    'uploaded_date' => date('Y-m-d H:i:s'),
//                    'manning_id' => $manning->manning_id,
//                    'staff_account_name' => $this->session->userdata('staff_no')
//                );
//                $this->db->insert('document_upload_history', $data);
//                $this->session->set_flashdata('success', "FILE SUCCESSFULLY UPLOADED!");
//            }

            $document = $this->document->findById($docId);

            $this->load->model('manning');
            $manning = $this->manning->getManning($manningId);
            $config['upload_path'] = DOCFOLDER."$manning->manning_folder_name/$crewIpn";
            if(!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'],0777,TRUE);
                chmod($config['upload_path'],0777);
            }
            $config['allowed_types'] = 'pdf';
            $config['max_size']	= '1000';
            $config['max_width']  = '1024';
            $config['max_height']  = '768';

            if($document->document_type == 'R') {

                $data = array(
                    'crewipn' => $crewIpn,
                    'applicantno' => $crewIpn,
                    'document_code' => $docId,
                    'uploaded_date' => date('Y-m-d H:i:s'),
                    'manning_id' => $manning->manning_id,
                    'staff_account_name' => $this->session->userdata('principal_no')
                );
                $this->db->insert('document_upload_history', $data);

                $name = $this->db->insert_id();
                $config['file_name'] = "$name.pdf";
                $config['upload_path'] = DOCFOLDER."$manning->manning_folder_name/$crewIpn/$document->id";
                $this->load->library('upload', $config);
                if(!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'],0777,TRUE);
                    chmod($config['upload_path'],0777);
                }
                if (!$this->upload->do_upload()) {
                    $this->session->set_flashdata('errors', $this->upload->display_errors());
                } else {
                    $this->session->set_flashdata('success', "FILE SUCCESSFULLY UPLOADED!");
                }
            } else {
                $config['file_name'] = "$crewIpn$document->country_code$document->document_code_mk.pdf";
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload()) {
                    $this->session->set_flashdata('errors', $this->upload->display_errors());
                } else {

                    $documentNumber = trim($this->input->post('documentNumber'));
                    $issuedDate = trim($this->input->post('issuedDate'));
                    $expiryDate = trim($this->input->post('expiryDate'));


                    $data = array(
                        'crewipn' => $crewIpn,
                        'applicantno' => $crewIpn,
                        'document_code' => $docId,
                        'uploaded_date' => date('Y-m-d H:i:s'),
                        'manning_id' => $manning->manning_id,
                        'staff_account_name' => $this->session->userdata('staff_no')
                    );

                    if($documentNumber) $data['doc_no'] = $documentNumber;
                    if($issuedDate) $data['date_issued'] = $issuedDate;
                    if($expiryDate) $data['date_expiry'] = $expiryDate;


                    $this->db->insert('document_upload_history', $data);
                    $this->session->set_flashdata('success', "FILE SUCCESSFULLY UPLOADED!");
                }
//                exit("crews/show/$crew->ID");
                redirect("crews/show/$crew->ID");
            }

            redirect("crews/show/$crew->ID");
        }
    }

    public function cerDelete($cerId = null, $crewId) {
        $cer = $this->crew->findCerById($cerId);
        $this->db->where('id', $cerId);
        $this->db->delete('crew_cer');
        $this->session->set_flashdata('success', "CER SUCCESSFULLY DELETED!");

        $crew = $this->db->where('ID', $crewId)->get("crew")->row();

        $this->load->model("user");
        $this->load->model("historyLog");
        $vessel = $this->db->query("select vessel_name from cat_vessel v where v.vessel_id = $cer->vessel_id")->row();

        $userId = $this->user->findStaffByLogin()->staff_id;

        $description = "Deleted $vessel->vessel_name CER for crew $crew->FNAME, $crew->GNAME, $crew->MNAME";

        $logData = [
            "account_id" => $userId,
            "principal" => 0,
            "manning_id" => $this->session->userdata('manning_id'),
            "event_description" => $description,
            "logged_at" => date("Y-m-d H:i:s")
        ];

        $this->historyLog->addLog($logData);

        redirect("crews/show/$crewId");
    }

    public function uploadCrewCer($number = null, $cerId = null) {
        $data['cer'] = $this->crew->findCerById($cerId);
        $data['crew'] = $this->crew->findById($data['cer']->ID);
        $data['cerId'] = $cerId;
        $data['number'] = $number;

        $data['content'] = 'pages/crew/uploadCrewCer.php';
        $this->load->view('templates/template', $data);
    }

    public function storeCrewCer($number = null, $cerId = null) {
        $cer = $this->crew->findCerById($cerId);
        $crew = $this->crew->findById($cer->ID);
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('errors', "INVALID ACCESS METHOD");
        } elseif(!$_FILES['userfile']['size']) {
            $this->session->set_flashdata('errors', "NO FILES DETECTED");
        } else {
            $config['upload_path'] = CERDOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN";
            if(!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'],0777,TRUE);
                chmod($config['upload_path'],0777);
            }
//            var_dump($config['upload_path'], $crew, $this->session->userdata('manning_id')); exit();
            $config['allowed_types'] = 'pdf';
            $config['max_size']	= '500';
            $config['max_width']  = '1024';
            $config['max_height']  = '768';
            $config['file_name'] = $crew->CREWIPN."_V$cer->vessel_id"."_N$number"."_C$cer->cer_initial_id.pdf";
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload()) {
                $errors = $this->upload->display_errors();
            } else {
                $file = $this->upload->data()['full_path'];
                chmod($file,0777);
                $this->session->set_flashdata('success', "CER FILE SUCCESSFULLY UPLOADED!");

                $cer = $this->db->select()
                    ->from("crew_cer cer")
                    ->where("id", $cerId)
                    ->get()->row();


                $this->load->model("user");
                $this->load->model("historyLog");
                $vessel = $this->db->query("select vessel_name from cat_vessel v where v.vessel_id = $cer->vessel_id")->row();

                $userId = $this->user->findStaffByLogin()->staff_id;

                $description = "Uploaded $vessel->vessel_name CER #$number for crew $crew->FNAME, $crew->GNAME, $crew->MNAME";

                $logData = [
                    "account_id" => $userId,
                    "principal" => 0,
                    "manning_id" => $this->session->userdata('manning_id'),
                    "event_description" => $description,
                    "logged_at" => date("Y-m-d H:i:s")
                ];

                $this->historyLog->addLog($logData);
            }
        }
        if(count($errors)) $this->session->set_flashdata('errors', $errors);
        redirect("crews/show/$crew->ID");
    }

    public function deleteCrewCer($number = null, $cerId = null) {
        $cer = $this->crew->findCerById($cerId);
        $crew = $this->crew->findById($cer->ID);
        if(unlink(CERDOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN"."_V$cer->vessel_id"."_N$number"."_C$cer->cer_initial_id.pdf")) {
            $this->session->set_flashdata('success', "CER FILE SUCCESSFULLY DELETED!");

            $this->load->model("user");
            $this->load->model("historyLog");
            $vessel = $this->db->query("select vessel_name from cat_vessel v where v.vessel_id = $cer->vessel_id")->row();

            $userId = $this->user->findStaffByLogin()->staff_id;

            $description = "deleted $vessel->vessel_name CER #$number for crew $crew->FNAME, $crew->GNAME, $crew->MNAME";

            $logData = [
                "account_id" => $userId,
                "principal" => 0,
                "manning_id" => $this->session->userdata('manning_id'),
                "event_description" => $description,
                "logged_at" => date("Y-m-d H:i:s")
            ];

            $this->historyLog->addLog($logData);

        } else {
            $this->session->set_flashdata('errors', "CER FILE FAILED TO DELETE!!");
        }
        redirect("crews/show/$crew->ID");
    }

    public function deletePicture($crewId = null) {
        $crew = $this->crew->findById($crewId);
        if(unlink(DOCFOLDER."$crew->manning_folder_name/picture/$crew->CREWIPN.jpg")) {
            $this->session->set_flashdata('success', "CREW PICTURE SUCCESSFULLY DELETED!");

            $this->load->model("user");
            $this->load->model("historyLog");

            $userId = $this->user->findStaffByLogin()->staff_id;

            $description = "deleted picture of crew $crew->FNAME, $crew->GNAME, $crew->MNAME";

            $logData = [
                "account_id" => $userId,
                "principal" => 0,
                "manning_id" => $this->session->userdata('manning_id'),
                "event_description" => $description,
                "logged_at" => date("Y-m-d H:i:s")
            ];

            $this->historyLog->addLog($logData);
        } else {
            $this->session->set_flashdata('errors', "CREW PICTURE FAILED TO DELETE!!");
        }
        redirect("crews/show/$crewId");
    }

    public function uploadPicture($crewId = null) {
        $data['crew'] = $this->crew->findById($crewId);

        $data['content'] = 'pages/crew/uploadPicture.php';
        $this->load->view('templates/template', $data);
    }

    public function storePicture($crewId = null) {
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('errors', "INVALID ACCESS METHOD");
        } elseif(!$_FILES['userfile']['size']) {
            $this->session->set_flashdata('errors', "NO FILES DETECTED");
        } else {
            $crew = $this->crew->findById($crewId);
            $config['upload_path'] = DOCFOLDER."$crew->manning_folder_name/picture";
            $config['allowed_types'] = 'jpg|jpeg';
            $config['max_size']	= '500';
            $config['file_name'] = "$crew->CREWIPN.jpg";
            $this->load->library('upload', $config);

//            var_dump($config);
//            exit();

            if (!$this->upload->do_upload()) {
                $this->session->set_flashdata('errors', $this->upload->display_errors());
            } else {
                $file = $this->upload->data()['full_path'];
                chmod($file,0777);
                $this->session->set_flashdata('success', "CREW PICTURE SUCCESSFULLY UPLOADED");

                $this->load->model("user");
                $this->load->model("historyLog");

                $userId = $this->user->findStaffByLogin()->staff_id;

                $description = "uploaded picture of crew $crew->FNAME, $crew->GNAME, $crew->MNAME";

                $logData = [
                    "account_id" => $userId,
                    "principal" => 0,
                    "manning_id" => $this->session->userdata('manning_id'),
                    "event_description" => $description,
                    "logged_at" => date("Y-m-d H:i:s")
                ];

                $this->historyLog->addLog($logData);
            }
        }
        redirect("crews/show/$crewId");
    }

    public function edit($crewId = null) {
        $data['crew'] = $this->crew->findById($crewId);
        $data['ranks'] = $this->crew->getRanks();

        $data['content'] = 'pages/crew/edit.php';
        $this->load->view('templates/template', $data);
    }

    public function updateCrew($crewId = null) {
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('errors', "INVALID ACCESS METHOD");
        } else {
            $data = array(
                'CREWIPN' => trim($this->input->post('crewipn')) ,
                'APPLICANTNO' => trim($this->input->post('crewipn')) ,
                'FNAME' => trim($this->input->post('last_name')) ,
                'GNAME' => trim($this->input->post('first_name')),
                'MNAME' => trim($this->input->post('middle_name')),
                'OTHER_FULLNAME' => $this->input->post('other_name'),
                'RANK' => $this->input->post('rank'),
                'BIRTHDATE' => $this->input->post('birthdate'),
                'ADDRESS' => $this->input->post('address'),
                'CONTACT_NO1' => $this->input->post('contactno1'),
                'CONTACT_NO2' => $this->input->post('contactno2'),
                'STATUS' => $this->input->post('status'),
                'POSTAL_CODE' => $this->input->post('postal_code'),
                'DATE_HIRED' => $this->input->post('date_hired'),
                'SCHOOL_NAME' => $this->input->post('school_name'),
                'SCHOOL_DATE_GRADUATED' => $this->input->post('school_date_graduated'),
                'EMAIL_ADDRESS' => $this->input->post('email_address')
            );

            $crew = $this->db->where('ID', $crewId)->get("crew")->row();

            $this->load->model("rank");
            $this->load->model("user");
            $this->load->model("historyLog");

            $oldValues = [
                'CREWIPN' => $crew->CREWIPN ,
                'APPLICANTNO' =>  $crew->APPLICANTNO ,
                'FNAME' =>  $crew->FNAME ,
                'GNAME' =>  $crew->GNAME,
                'MNAME' =>  $crew->MNAME,
                'OTHER_FULLNAME' =>  $crew->OTHER_FULLNAME,
                'RANK' =>  $this->rank->findByRankCode($crew->RANK)->rank_alias,
                'BIRTHDATE' =>  $crew->BIRTHDATE,
                'ADDRESS' =>  $crew->ADDRESS,
                'CONTACT_NO1' =>  $crew->CONTACT_NO1,
                'CONTACT_NO2' =>  $crew->CONTACT_NO2,
                'STATUS' =>  $crew->STATUS,
                'POSTAL_CODE' =>  $crew->POSTAL_CODE,
                'DATE_HIRED' =>  $crew->DATE_HIRED,
                'SCHOOL_NAME' =>  $crew->SCHOOL_NAME,
                'SCHOOL_DATE_GRADUATED' =>  $crew->SCHOOL_DATE_GRADUATED,
                'EMAIL_ADDRESS' =>  $crew->EMAIL_ADDRESS
            ];

            $newValues = [
                'CREWIPN' => $this->input->post('crewipn') ,
                'APPLICANTNO' => $this->input->post('crewipn') ,
                'FNAME' => $this->input->post('last_name') ,
                'GNAME' => $this->input->post('first_name'),
                'MNAME' => $this->input->post('middle_name'),
                'OTHER_FULLNAME' => $this->input->post('other_name'),
                'RANK' => $this->rank->findByRankCode($this->input->post('rank'))->rank_alias,
                'BIRTHDATE' => $this->input->post('birthdate'),
                'ADDRESS' => $this->input->post('address'),
                'CONTACT_NO1' => $this->input->post('contactno1'),
                'CONTACT_NO2' => $this->input->post('contactno2'),
                'STATUS' => $this->input->post('status'),
                'POSTAL_CODE' => $this->input->post('postal_code'),
                'DATE_HIRED' => $this->input->post('date_hired'),
                'SCHOOL_NAME' => $this->input->post('school_name'),
                'SCHOOL_DATE_GRADUATED' => $this->input->post('school_date_graduated'),
                'EMAIL_ADDRESS' => $this->input->post('email_address')
            ];

            $userId = $this->user->findStaffByLogin()->staff_id;
            $updatedColumn = "";
            foreach ($oldValues as $index => $value) {
                if($value != $newValues[$index]) $updatedColumn .= "$index, ";
            }
            $updatedColumn = substr($updatedColumn, 0, strripos($updatedColumn, ","))." of";

            $description = "updated $updatedColumn crew with CREWIPN OF $crew->CREWIPN";

            $logData = [
                "account_id" => $userId,
                "principal" => 0,
                "manning_id" => $this->session->userdata('manning_id'),
                "event_description" => $description,
                "old_value" => json_encode($oldValues),
                "new_value" => json_encode($newValues),
                "logged_at" => date("Y-m-d H:i:s")
            ];

            $this->historyLog->addLog($logData);

            $this->db->where('ID', $crewId);
            $this->db->update('crew', $data);
        }
        $this->session->set_flashdata('success', "CREW INFORMATION SUCCESSFULLY UPDATED!");
        redirect("crews/edit/$crewId");
    }

    public function addCrew() {
        $this->load->model("manning");
        $data['manning'] = $this->manning->findByStaffLogin();
        $data['ranks'] = $this->crew->getRanks();

        $data['content'] = 'pages/crew/addCrew.php';
        $this->load->view('templates/template', $data);
    }

    public function storeCrew() {
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('errors', "INVALID ACCESS METHOD");
        } elseif($this->crew->countCrew($this->input->post('crewipn')) > 0) {
            $this->session->set_flashdata('errors', "CREWIPN ALREADY EXISTS");
        } else {
            $crewIpn = trim($this->input->post('crewipn'));
            $fname = trim($this->input->post('last_name'));
            $gname = trim($this->input->post('first_name'));
            $mname = trim($this->input->post('middle_name'));
            $manningId = $this->input->post('manning_id');
            $rank = $this->input->post('rank');
            $bdate = $this->input->post('birthdate');
            $data = array(
                'CREWIPN' => $crewIpn ,
                'APPLICANTNO' => $crewIpn ,
                'FNAME' => $fname ,
                'GNAME' => $gname,
                'MNAME' => $mname,
                'MANNING_ID' => $manningId ,
                'RANK' => $rank,
                'BIRTHDATE' => $bdate
            );
            $this->db->insert('crew', $data);
            $this->session->set_flashdata('success', "CREW SUCCESSFULLY ADDED!");

            $this->load->model("rank");
            $this->load->model("manning");
            $this->load->model("user");
            $this->load->model("historyLog");

            $userId = $this->user->findStaffByLogin()->staff_id;

            $description = "added crew with Crew IPN of $crewIpn, $fname, $gname, $mname";

            $newValue = [
                'CREWIPN' => $crewIpn,
                'FNAME' => $fname,
                'GNAME' => $gname,
                'MNAME' => $mname,
                'BIRTHDATE' => $bdate,
                'RANK' => $this->rank->findByRankCode($rank)->rank_alias,
                'MANNING' => $this->manning->getManning($manningId)->manning_alias,
            ];

            $logData = [
                "account_id" => $userId,
                "principal" => 0,
                "manning_id" => $this->session->userdata('manning_id'),
                "event_description" => $description,
                'new_value' => json_encode($newValue),
                "logged_at" => date("Y-m-d H:i:s")
            ];

            $this->historyLog->addLog($logData);
        }
        redirect("crews/addCrew");
    }

    public function delete($crewId = null) {
        $crew = $this->db->where('ID', $crewId)->get("crew")->row();
        $this->db->where('ID', $crewId);
        $this->db->delete('crew');

        $this->load->model("user");
        $this->load->model("historyLog");

        $userId = $this->user->findStaffByLogin()->staff_id;

        $description = "deleted crew with CREWIPN OF $crew->CREWIPN full name: $crew->FNAME, $crew->GNAME, $crew->MNAME";

        $logData = [
            "account_id" => $userId,
            "principal" => 0,
            "manning_id" => $this->session->userdata('manning_id'),
            "event_description" => $description,
            "logged_at" => date("Y-m-d H:i:s")
        ];

        $this->historyLog->addLog($logData);

        $this->session->set_flashdata('success', "CREW SUCCESSFULLY DELETED!");
        redirect('mannings');
    }

    public function printFiles() {
        $files = $_POST['myKey'];
        $files = implode(", ", $files);
        $this->session->set_userdata('files', $files);
        $this->output->set_content_type('text/plain', 'UTF-8');
        return $this->output->set_output("success");
    }

    public function showPrintFiles() {
        include_once APPPATH.'vendor/autoload.php';
        $files = explode(", ", $this->session->userdata('files'));
        $this->session->unset_userdata('files');


        $newFiles = [];
        $pdf = new erc\pdfmerger\PDFMerger();
        try {
            foreach ($files as $index => $file) {
                $newFilePath = ROOTFOLDER.time()."$index.pdf";
                shell_exec("gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=$newFilePath $file");
                $pdf->addPDF($newFilePath);
                $newFiles[] = $newFilePath;
            }
            $pdf->merge('file', ROOTFOLDER.'test.pdf');
        } catch(Exception $e){
            exit($e->getMessage());
        }
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="'.basename("d.pdf").'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        readfile(ROOTFOLDER."test.pdf");
        foreach ($newFiles as $newFile)  unlink($newFile);
        unlink("test.pdf");
    }

    public function index($manningId = null, $offset = 0) {
        if($this->session->userdata('principal_no') != 'PRIN-0030') {
            $this->session->set_flashdata('errors', ["UNAUTHORIZED ACCESS!!"]);
            redirect("welcome/logout");
        }
        $this->load->model('user');
        $data['mannings'] = $this->user->findAllManning();

        if($manningId) {
            $data['currentManning'] = $manningId;
            $data['offset'] = $offset + 1;

            if($this->input->server('REQUEST_METHOD') == 'POST') {
                $keyword = $this->input->post("column");
                $value = $this->input->post("value");
                $data['crews'] = $this->crew->findByManning($manningId, $keyword, $value)->get()->result();
            } else {
                $this->load->library('pagination');
                $config['base_url'] = base_url("crews/index/$manningId");
                $config['total_rows'] = $this->crew->countCrewByManning($manningId)->count;
                $config['per_page'] = 20;
                $config['num_links'] = 5;
                $config['full_tag_open'] = "<div class='pagination'> <ul>";
                $config['full_tag_close'] = "</ul > </div>";
                $config['first_tag_open'] = "<li>";
                $config['last_tag_open'] = "<li >";
                $config['next_tag_open'] = "<li>";
                $config['prev_tag_open'] = "<li>";
                $config['num_tag_open'] = "<li>";
                $config['num_tag_close'] = "</li >";
                $config['first_tag_close'] = "</li>";
                $config['last_tag_close'] = "</li >";
                $config['next_tag_close'] = "</li>";
                $config['prev_tag_close'] = "</li >";
                $config['cur_tag_open'] = "<li class='active'> <span> <b>";
                $config['cur_tag_close'] = "</b> </span></li>";
                $data['crews'] = $this->crew->findByManning($manningId)
                    ->get(null, $config['per_page'], $offset)->result();
                $config['records'] = $data['crews'];
                $this->pagination->initialize($config);
            }

        } else {
            $data['currentManning'] = "";
        }

        $data['content'] = 'pages/crew/index.php';
        $this->load->view('templates/wide_template', $data);
    }

    public function editCrew($crewId = null) {
        if($this->session->userdata('principal_no') != 'PRIN-0030') {
            $this->session->set_flashdata('errors', ["UNAUTHORIZED ACCESS!!"]);
            redirect("welcome/logout");
        }

        $data['crew'] = $this->crew->findCrewById($crewId);

//        var_dump($data['crew']);
//        echo "<hr />";
//        exit($this->db->last_query());

//        var_dump($data['crew'], $crewId); exit();

        $this->load->model('user');
        $data['mannings'] = $this->user->findAllManning();
        $data['ranks'] = $this->crew->getRanks();

        $data['content'] = 'pages/crew/editCrew.php';
        $this->load->view('templates/wide_template', $data);
    }

    public function updateCrewInfo() {
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('errors', ["UNAUTHORIZED METHOD!"]);
            redirect("welcome/logout");
        }
        $crewId = $this->input->post("crewId");
        $crewipn = $this->input->post("crewipn");
        $fname = $this->input->post("fname");
        $gname = $this->input->post("gname");
        $mname = $this->input->post("mname");
        $birthdate = $this->input->post("birthdate");
        $otherFullname = $this->input->post("otherFullname");
        $postalCode = $this->input->post("postalCode");
        $insuranceNumber = $this->input->post("insuranceNumber");
        $address = $this->input->post("address");
        $manning = $this->input->post("manning");
        $rank = $this->input->post("rank");
        $dateHired = $this->input->post("dateHired");
        $emailAddress = $this->input->post("emailAddress");
        $contact1 = $this->input->post("contact1");
        $contact2 = $this->input->post("contact2");
        $schoolName = $this->input->post("schoolName");
        $schoolDateGraduated = $this->input->post("schoolDateGraduated");
        $status = $this->input->post("status")?"1" : "0";

        $updatedCrew = [
            "CREWIPN" => $crewipn,
            "FNAME" => $fname,
            "GNAME" => $gname,
            "MNAME" => $mname,
            "BIRTHDATE" => $birthdate,
            "OTHER_FULLNAME" =>$otherFullname,
            "POSTAL_CODE" => $postalCode,
            "INSURANCE_NUMBER" => $insuranceNumber,
            "ADDRESS" => $address,
            "MANNING_ID" => $manning,
            "RANK" => $rank,
            "DATE_HIRED" => $dateHired,
            "EMAIL_ADDRESS" => $emailAddress,
            "CONTACT_NO1" => $contact1,
            "CONTACT_NO2" => $contact2,
            "SCHOOL_NAME" => $schoolName,
            "SCHOOL_DATE_GRADUATED" => $schoolDateGraduated,
            "STATUS" => $status,
        ];

        $crew = $this->db->where('ID', $crewId)->get("crew")->row();

        $this->load->model("rank");
        $this->load->model("user");
        $this->load->model("historyLog");
        $this->load->model("manning");

        $oldValues = [
            'CREWIPN' => $crew->CREWIPN ,
            'FNAME' =>  $crew->FNAME ,
            'GNAME' =>  $crew->GNAME,
            'MNAME' =>  $crew->MNAME,
            'OTHER_FULLNAME' =>  $crew->OTHER_FULLNAME,
            'RANK' =>  $this->rank->findByRankCode($crew->RANK)->rank_alias,
            'BIRTHDATE' =>  $crew->BIRTHDATE,
            'ADDRESS' =>  $crew->ADDRESS,
            "MANNING_ID" => $this->manning->getManning($crew->MANNING_ID)->manning_alias,
            'CONTACT_NO1' =>  $crew->CONTACT_NO1,
            'CONTACT_NO2' =>  $crew->CONTACT_NO2,
            'STATUS' =>  $crew->STATUS,
            'POSTAL_CODE' =>  $crew->POSTAL_CODE,
            'DATE_HIRED' =>  $crew->DATE_HIRED,
            'SCHOOL_NAME' =>  $crew->SCHOOL_NAME,
            'SCHOOL_DATE_GRADUATED' =>  $crew->SCHOOL_DATE_GRADUATED,
            'EMAIL_ADDRESS' =>  $crew->EMAIL_ADDRESS
        ];

        $newValues = [
            "CREWIPN" => $crewipn,
            "FNAME" => $fname,
            "GNAME" => $gname,
            "MNAME" => $mname,
            "BIRTHDATE" => $birthdate,
            "OTHER_FULLNAME" =>$otherFullname,
            "POSTAL_CODE" => $postalCode,
            "INSURANCE_NUMBER" => $insuranceNumber,
            "ADDRESS" => $address,
            "MANNING_ID" => $this->manning->getManning($manning)->manning_alias,
            "RANK" => $this->rank->findByRankCode($rank)->rank_alias,
            "DATE_HIRED" => $dateHired,
            "EMAIL_ADDRESS" => $emailAddress,
            "CONTACT_NO1" => $contact1,
            "CONTACT_NO2" => $contact2,
            "SCHOOL_NAME" => $schoolName,
            "SCHOOL_DATE_GRADUATED" => $schoolDateGraduated,
            "STATUS" => $status,
        ];

        $userId = $this->user->findPrincipalByLogin()->principal_id;
        $updatedColumn = "";
        foreach ($oldValues as $index => $value) if($value != $newValues[$index]) $updatedColumn .= "$index, ";
        $updatedColumn = substr($updatedColumn, 0, strripos($updatedColumn, ","))." of";
        $description = "updated $updatedColumn crew with CREWIPN OF $crew->CREWIPN";

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


        if($this->db->where("ID", $crewId)->update("crew", $updatedCrew)) {
            $this->session->set_flashdata('success', "CREW INFORMATION SUCCESSFULLY UPDATED!");
        } else {
            $this->session->set_flashdata('errors', "UPDATE FAILED!!");
        }
        redirect("crews/editCrew/$crewId");

    }

    public function deleteCrew($crewId) {
        if($this->session->userdata('principal_no') == 'PRIN-0030') {
            $crew = $this->db->where('ID', $crewId)->get("crew")->row();
            if($this->db->delete('crew', array('ID' => $crewId))) {

                $this->load->model('user');
                $this->load->model('historyLog');
                $userId = $this->user->findPrincipalByLogin()->principal_id;
                $description = "DELETED CREW WITH CREWIPN OF $crew->CREWIPN and record ID of $crewId";
                $logData = [
                    "account_id" => $userId,
                    "principal" => 1,
                    "manning_id" => $this->session->userdata('manning_id'),
                    "event_description" => $description,
                    "logged_at" => date("Y-m-d H:i:s")
                ];
                $this->historyLog->addLog($logData);

                $this->session->set_flashdata('success', "CREW SUCCESSFULLY DELETED!");
            } else {
                $this->session->set_flashdata('errors', "FAILED TO DELETE CREW");
            }
            redirect("crews/index");
        } else {
            $this->session->set_flashdata('errors', ["UNAUTHORIZED ACCESS!"]);
            redirect("welcome");
        }
    }

    public function updateCerGrade() {
        if($this->input->server('REQUEST_METHOD') != 'POST') {
            $this->session->set_flashdata('errors', "INVALID ACCESS METHOD");
        }
        $crewId = $this->input->post("crewId");
        $cerId = $this->input->post("cerId");
        $cerNo = $this->input->post("cerNo");
        $grade = $this->input->post("grade");
        $count = $this->db->select("count(*) as count")->from("cer_grade")->where("cer_id", $cerId)->where("cer_number", $cerNo)->get()->row()->count;
//        exit($this->db->last_query());
        if($count) {
            $this->db->where("cer_id", $cerId)->where("cer_number", $cerNo)->update("cer_grade", ["grade" => $grade]);
        } else {
            $data = array(
                'cer_id' => $cerId,
                'cer_number' => $cerNo,
                'grade' => $grade
            );
            $this->db->insert('cer_grade', $data);
        }

        $cer = $this->db->select("cer.vessel_id")
            ->from("crew_cer cer")
            ->where("cer.id", $cerId)
            ->get()->row();
        $crew = $this->db->where('ID', $crewId)->get("crew")->row();


        $this->load->model("user");
        $this->load->model("historyLog");
        $vessel = $this->db->query("select vessel_name from cat_vessel v where v.vessel_id = $cer->vessel_id")->row();

        $userId = $this->user->findStaffByLogin()->staff_id;

        $description = "Updated $vessel->vessel_name CER #$cerNo grade to $grade for crew $crew->FNAME, $crew->GNAME, $crew->MNAME";

        $logData = [
            "account_id" => $userId,
            "principal" => 0,
            "manning_id" => $this->session->userdata('manning_id'),
            "event_description" => $description,
            "logged_at" => date("Y-m-d H:i:s")
        ];

        $this->historyLog->addLog($logData);

        $this->session->set_flashdata('success', "CER GRADE SUCCESSFULLY UPDATED!!");
        redirect("crews/show/$crewId");
    }

    public function viewDocumentReport($crewId, $documentHistoryId) {
        $this->load->model('manning');
        $this->load->model('crewDocument');
        $data['crewDocument'] = $this->crewDocument->findById($documentHistoryId);
        $data['document'] = $this->document->findById($data['crewDocument']->document_code);
        $data['crew'] = $this->crew->findById($crewId);
        $data['histories'] = $this->crewDocument->crewDocumentHistory($data['document']->id, $data['crew']->CREWIPN);
        $this->load->view("templates/wide_header");
        $this->load->view("templates/user_nav");
        $this->load->view("pages/crew/document/documentHistory.php", $data);
        $this->load->view("templates/wide_footer");
    }

    public function updateReportDocument() {
        $crewId = $this->input->post('crewId');
        $crewIpn = $this->input->post('crewIpn');
        $manning = $this->input->post('manning');
        $documentId = $this->input->post('documentId');
        $crewDocumentId = $this->input->post('crewDocumentId');
        $file = DOCFOLDER."$manning/$crewIpn/$documentId";
        $config['upload_path'] = $file;
        $config['allowed_types'] = 'pdf';
        $config['max_size']	= '1000';
        $config['max_width']  = '1024';
        $config['max_height']  = '768';
        $config['file_name'] = "$crewDocumentId.pdf";
        $this->load->library('upload', $config);
        if(file_exists("$file/$crewDocumentId.pdf")) {
            unlink("$file/$crewDocumentId.pdf");
        }
        if (!$this->upload->do_upload()) {
            $this->session->set_flashdata('errors', $this->upload->display_errors());
        } else {
            $this->session->set_flashdata('success', "FILE SUCCESSFULLY UPDATED!");
        }
        redirect("crews/viewDocumentReport/$crewId/$crewDocumentId");
    }

}
