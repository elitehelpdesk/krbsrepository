<?php
    class Principals extends CI_Controller {

        public function __construct(){
            parent::__construct();
            if(! $this->session->userdata('validated')) {
                $errors = ["PLEASE LOGIN FIRST!"];
                $this->session->set_flashdata('errors', $errors);
                redirect('/');
            } elseif($this->session->userdata('type') != "Principal") {
                redirect("mannings/index/".$this->session->userdata('manning_id'));
            }
//            $this->verifyPassword();
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

        public function index() {

            /* bremen */
            if($this->session->userdata('manning_id') == '14') {
                $data['mannings'] = [
                    [
                        ["name" => "STARGATE - UKRAINE", "color"=>"ffdfc0", "index" =>6],
                        ["name" => "STARGATE - VARNA", "color"=>"c0fffe", "index" =>5],
                        ["name" => "VERITAS MARITIME CORPORATION", "color"=>"8db5e7", "index" =>1],
                    ],

                    [
                      ["name" => "VENTIS MARITIME CORPORATION", "color"=>"e78d8d", "index" =>2],
                      ["name"=>"SEA SUNSHINE INCORPORATION", "color"=>"b2fda1", "index" =>11],
                      ["name" => "NEW FILIPINO MARITIME AGENCIES, INC.", "color"=>"1abc9c", "index" =>3]
                    ],
                ];
            } else {
                $data['mannings'] = [
                    [
                        ["name" => "VERITAS MARITIME CORPORATION", "color"=>"8db5e7", "index" =>1],
                        ["name" => "KRBS KOBE", "color"=>"96a6a6", "index" =>7],
                        ["name" => "HUAYANG MARITIME CENTRE", "color"=>"bce78d", "index" =>4]
                    ],
                    [
                        ["name" => "VENTIS MARITIME CORPORATION", "color"=>"e78d8d", "index" =>2],
                        ["name" => "INTERMODAL SHIPPING, INC.", "color"=>"c2acda", "index" =>8],
                        ["name" => "NEW FILIPINO MARITIME AGENCIES, INC.", "color"=>"1abc9c", "index" =>3]
                    ],
                    [
                        ["name" => "STARGATE - UKRAINE", "color"=>"ffdfc0", "index" =>6],
                        ["name" => "STARGATE - VARNA", "color"=>"c0fffe", "index" =>5],
                        ["name" => "FILSTAR", "color"=>"f4f4f4", "index" =>12]
                    ],
                    [
                        ["name"=>"SEA SUNSHINE INCORPORATION", "color"=>"b2fda1", "index" =>11],
                        ["name"=>"OSM CHINA", "color"=>"fdeda1", "index" =>9],
                        ["name"=>"SINO CREW CHINA", "color"=>"ffcfcd", "index" =>10]
                    ],
                ];
            }

            if($this->input->server('REQUEST_METHOD') == 'POST') {
                $this->load->model('crew');
                $keyword = $this->input->post('cat_keyword');
                $key = $this->input->post('keyword');
                $data['crew_list'] = $this->crew->findByKeyword($keyword, $key);
                $data['foldercode'] = [
                    "FIL" => "PHL", "CHI" => "CHI", "BUL" => "BUL", "UKR" => "UKR", "JPN" => "JPN",
                ];
            }

            $data['content'] = 'pages/principal/index.php';
            $this->load->view('templates/template', $data);
        }

        public function view ($page = "home") {
            if(!file_exists(APPPATH."vies/pages/$page.php")) {
                show_404();
            }
            $data['title'] = ucfirst($page);

            $this->load->view("templates/header");
            $this->load->view("pages/$page", $data);
            $this->load->view("templates/footer");
        }

    }
