<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user');
    }

    //<editor-fold defaultstate="collapsed" desc="INDEX">
    public function index() {
        if ($this->session->userdata('validated')) {
            redirect('principals');
        }
//        $this->load->view("templates/header");
        $this->load->view("pages/login.php");
        $this->load->view("templates/footer");
	}
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="LOGIN">
	public function login() {
	    $username = $this->input->post('username');
	    $password = $this->input->post('password');
	    $position = $this->input->post('position');
	    $errors = [];
	    if(!$username) $errors[] = "EMPTY USERNAME";
	    if(!$password) $errors[] = "EMPTY PASSWORD";
	    if(!$position) $errors[] = "EMPTY POSITION";

        if(!count($errors)) {
            $this->load->model('user');
            $user = null;

            switch ($position) {
                case "staff":
                    $user = $this->user->loginAsStaff($username, $password);
                    if($user) {
                        $this->session->set_userdata('attempt', 0);
                        redirect("mannings/index/$user");
                    }
                    else $errors[] = "STAFF NOT EXISTS";
                    break;
                case "manager":
                    $user = $this->user->loginAsManager($username, $password);
                    if($user) {
                        $this->session->set_userdata('attempt', 0);
                        redirect('principals');
                    }
                    else $errors[] = "MANAGER NOT EXISTS";
                    break;
                case "principal":
                    $user = $this->user->loginAsPrincipal($username, $password);
                    if($user) {
                        $this->session->set_userdata('attempt', 0);
                        redirect('principals');
                    }
                    else $errors[] = "PRINCIPAL NOT EXISTS";
                    break;

                default:
                    $errors[] = "INVALID USER TYPE";
            }
            if(!$user) {
                $loginAttempt = $this->session->userdata('attempt') ?: 0;
                $loginAttempt++;
                $this->session->set_userdata('attempt', $loginAttempt);
                $loginAttempt = $this->session->userdata('attempt');
                if($loginAttempt > 2) {
                    $this->user->deactivateAccount($position, $username);
                    $errors = [
                        "TOO MANY LOGIN ATTEMPTS, YOUR ACCOUNT HAS BEEN DEACTIVATED",
                        "PLEASE CONTACT SYSTEM ADMINISTRATOR FOR ASSISTANCE",
                    ];
                    $this->session->set_userdata('attempt', 0);
                } else {
                    $existingUser = $this->user->exists($position, $username);
                    if($existingUser && !$existingUser->account_status) {
                        $errors = [
                            'YOUR ACCOUNT HAS BEEN DEACTIVATED',
                            'PLEASE CONTACT SYSTEM ADMINISTRATOR FOR ASSISTANCE',
                        ];
                    }
                    $errors[] = "ATTEMPTS REMAINING (" . (3 - $loginAttempt) . ")";
                }
                $cookie= array(
                    'name'   => 'attempt',
                    'value'  => $loginAttempt . '',
                    'expire' => '30',
                    'secure' => TRUE
                );
                $this->input->set_cookie($cookie);
            }
        }
        $this->session->set_flashdata('errors', $errors);
        redirect('/');

    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="LOGOUT">
    public function logout() {

	    if($this->session->userdata('type')) {
            $this->load->model("historyLog");
            if($this->session->userdata('type') == "Staff") {
                $user = $this->user->findStaffByLogin();
                $userId = $user->staff_id;
                $username = $user->staff_account_name;
                $principal = 0;
            } else {
                $user = $this->user->findPrincipalByLogin();
                $userId = $user->principal_id;
                $username = $user->principal_account_name;
                $principal = 1;
            }

            $description = "username $username Logged Out";
            $logData = [
                "account_id" => $userId,
                "principal" => $principal,
                "manning_id" => $this->session->userdata('manning_id'),
                "event_description" => $description,
                "logged_at" => date("Y-m-d H:i:s")
            ];
            $this->historyLog->addLog($logData);
        }

        $this->session->sess_destroy();
        redirect('/');
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="VIEWDOCUMENT">
    public function viewDocument($crewipn=null, $manning=null, $docname=null, $cer=null) {
        if(! ($this->session->userdata('staff_no') || $this->session->userdata('principal_no'))) {
            return "";
        } else if(!($crewipn === null || $manning === null || $docname === null)){
            if ($cer === "cer") $file = CERDOCFOLDER."$manning/$crewipn/$docname.pdf";
            elseif(($cer === "cc")) $file = DOCFOLDER."$manning/$crewipn/comments/$docname.pdf";
            elseif(($cer === "cf")) $file = DOCFOLDER."$manning/company_license/$docname.pdf";
            else {
                if(file_exists(DOCFOLDER."$manning/$crewipn/$docname.pdf")) {
                    $file = DOCFOLDER."$manning/$crewipn/$docname.pdf";
                } else if(file_exists(DOCFOLDER."$manning/$crewipn/$docname.PDF")) {
                    $file = DOCFOLDER."$manning/$crewipn/$docname.PDF";
                } else {
                    $file = "";
                }
            }
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                readfile($file);
                exit();
            } else {
                return "";
            }
        } else {
            return "";
        }
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="TEMPVIEWDOCUMENT">
    public function tempViewDocument($crewipn=null, $manning=null, $docname=null, $cer=null) {

        if(!($crewipn === null || $manning === null || $docname === null)){
            if ($cer === "cer") $file = CERDOCFOLDER."$manning/$crewipn/$docname.pdf";
            elseif(($cer === "cc")) $file = DOCFOLDER."$manning/$crewipn/comments/$docname.pdf";
            elseif(($cer === "cf")) $file = DOCFOLDER."$manning/company_license/$docname.pdf";
            else {
                if(file_exists(DOCFOLDER."$manning/$crewipn/$docname.pdf")) {
                    $file = DOCFOLDER."$manning/$crewipn/$docname.pdf";
                } else if(file_exists(DOCFOLDER."$manning/$crewipn/$docname.PDF")) {
                    $file = DOCFOLDER."$manning/$crewipn/$docname.PDF";
                } else {
                    $file = "";
                }
            }
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                readfile($file);
                exit();
            } else {
                return "";
            }
        } else {
            return "";
        }
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="VIEWPIC">
    public function viewPic($crewipn=null, $manning=null) {
        if(! ($this->session->userdata('staff_no') || $this->session->userdata('principal_no'))) {
            return "";
        } else if(!($crewipn === null || $manning === null)){
            $filesmall = DOCFOLDER."$manning/picture/$crewipn.jpg";
            $fileBig = DOCFOLDER."$manning/picture/$crewipn.JPG";
            if(file_exists($filesmall)) {
                $file = $filesmall;
            } else {
                $file = $fileBig;
            }
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: image/jpeg');
                header('Content-Disposition: inline; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                readfile($file);
                exit();
            } else {
                return "";
            }
        } else {
            return "";
        }
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="TEMPVIEWPIC">
    public function tempViewPic($crewipn=null, $manning=null) {
        if(!($crewipn === null || $manning === null)){
            $filesmall = DOCFOLDER."$manning/picture/$crewipn.jpg";
            $fileBig = DOCFOLDER."$manning/picture/$crewipn.JPG";
            if(file_exists($filesmall)) {
                $file = $filesmall;
            } else {
                $file = $fileBig;
            }
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: image/jpeg');
                header('Content-Disposition: inline; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                readfile($file);
                exit();
            } else {
                return "";
            }
        } else {
            return "";
        }
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="VIEWCOMPANYLICENSE">
    public function viewCompanyLicense($manning=null, $filename=null) {
        if(! ($this->session->userdata('staff_no') || $this->session->userdata('principal_no'))) {
            return "";
        } else if(!($manning === null || $filename === null)){
            $file = DOCFOLDER."$manning/company_license/$filename.pdf";
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                readfile($file);
                exit();
            } else {
                return "";
            }
        } else {
            return "";
        }
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="DOWNLOADPICTURE">
    public function download_picture($path, $applicantno=null) {
        $name = "$applicantno.jpg";
        $data = file_get_contents(DOCFOLDER."$path/picture/$name"); // Read the file's contents
        force_download($name, $data);
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="DOWNLOADDOCUMENT">
    public function download_document($applicantno, $docno, $path) {
        $name = "$docno.pdf";
        $data = file_get_contents(DOCFOLDER."$path/$applicantno/$name");
        force_download($name, $data);
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="CHANGEPASSWORD">
    public function changePassword() {
        $data['pass1'] = $this->security->xss_clean(md5($this->input->post('password1')));
        $data['pass2'] = $this->security->xss_clean($this->input->post('password2'));
        if(isset($_POST['confirmpass']) && ($data['pass1'] == $data['pass2'])){
            $data['change'] = '1';
            $data['status'] = '1';
        } else {
            $data['change'] = '0';
            $data['status'] = '0';
        }
        if($this->session->userdata("type") == "Principal") {
            if(isset($_POST['changepass'])){

                $data = array(
                    'principal_account_password' => $this->security->xss_clean(md5($this->input->post('password3'))),
                    'password_updated_at' => date('Y-m-d')
                );
                $this->db->where('principal_no', $this->session->userdata('principal_no'));
                $this->db->update('account_principal', $data);
                $this->session->set_userdata('password_updated_at', date('Y-m-d'));
                $this->session->set_flashdata('success', "PASSWORD SUCCESSFULLY UPDATED");
                return redirect('welcome/changePassword');
            }
            $data['principal_list'] = $this->user->findPrincipalByLogin();
        } else {
            if(isset($_POST['changepass'])){

                $data = array(
                    'staff_account_password' => $this->security->xss_clean(md5($this->input->post('password3'))),
                );
                $this->db->where('staff_no', $this->session->userdata('principal_no'));
                $this->db->update('account_staff', $data);
                $this->session->set_userdata('password_updated_at', date('Y-m-d'));
                $this->session->set_flashdata('success', "PASSWORD SUCCESSFULLY UPDATED");
                return redirect('welcome/changePassword');
            }
            $data['principal_list'] = $this->user->findStaffByLogin();
        }

//        var_dump($data['principal_list']); exit();

        $this->load->view("templates/header");
        $this->load->view("templates/user_nav");
        $this->load->view("pages/changePassword", $data);
        $this->load->view("templates/footer");
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="PRIVACY">
    public function privacy() {
        $eula = array();
        $eula[] = "Your privacy is important to us. It is Elite Software and Data Security Inc.'s policy to respect your privacy regarding any information we may collect from you across the website, http://www.krbsgroup.com, and other sites we operate.";
        $eula[] = "We collect the following personal information from you when you manually or electronically submit to us your user account details such as name, position, email and/or birth date.";
        $eula[] = "We only ask for personal information when we truly need it to provide a service to you. We collect it by fair and lawful means, with your knowledge and consent. We also let you know why we’re collecting it and how it will be used.";
        $eula[] = "The collected personal information is utilized solely for documentation, processing and/or verification purposes for the KRBS website and other sites we operate.";
        $eula[] = "We only retain collected information for as long as necessary to provide you with your requested service. What data we store, we’ll protect within commercially acceptable means to prevent loss and theft, as well as unauthorized access, disclosure, copying, use or modification.";
        $eula[] = "We don’t share any personally identifying information publicly or with third-parties, except when required to by law or by you.";
        $eula[] = "Our website may link to external sites that are not operated by us. Please be aware that we have no control over the content and practices of these sites, and cannot accept responsibility or liability for their respective privacy policies.";
        $eula[] = "You are free to refuse our request for your personal information, with the understanding that we may be unable to provide you with some of your desired services.";
        $eula[] = "Your continued use of our website will be regarded as acceptance of our practices around privacy and personal information. If you have any questions about how we handle user data and personal information, feel free to contact us.";
        $eula[] = "We may update this privacy policy from time to time in order to reflect, for example, changes to our practices or for other operational, legal or regulatory reasons.";
        $eula[] = "For more information about our privacy practices, if you have questions, or if you would like to make a complaint, please contact us by e-mail at legal@elitesdsi.com";

        $data['eulas'] = $eula;
        $this->load->view("pages/privacy", $data);
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="READEXCELFILE">
    public function readExcelFile() {
        $this->load->helper(array('form', 'url'));
        $this->load->view('pages/readExcelFile');
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="VERITASREPORT">
    public function veritasReport() {

        $includedDocumentIds = [
            'W1' => 201,	    // ADVANCE WELDING COURSE
            'R3' => 164,	    // AFFIDAVIT OF DRUG AND ALCOHOL POLICY
            'A4' => 77,	        // AUSTRALIAN MARITIME CREW VISA
            'A0' => 67,	        // AUTOMATIC IDENTIFICATION SYSTEMS
            'B4' => 186,	    // BALLAST WATER MANAGEMENT COURSE
            'I0' => 98,	        // BASSNET
            '#BIO' => 87,	        // BIODATA
            '#b6' => 188,	    // BOILER COMBUSTION CONTROL COURSE
            '78' => 99,	        // BRM / BTM
            '#K4' => 51,	        // CAYMAN GMDSS
            '18' => 10,	        // CERTIFICATE OF COMPETENCY for OFFICERS
            '#CGMDSST' => 153,	    // CERTIFICATE OF GMDSS TRAINING
            'E3' => 44,	        // CONTRACT
            'N5' => 6,	        // COP_ADVANCED TRAINING IN FIREFIGHTING
            '37' => 8,	        // COP_MEDICAL CARE
            '39' => 7,	        // COP_MEDICAL EMERGENCY - FIRST AID
            '36' => 4,	        // COP_PROF. IN SURVIVAL CRAFT & RESCUE BOAT
            'N8' => 29,	        // COP_SDSD
            'N6' => 33,	        // COP_SHIP SECURTY OFFICER
            '40' => 5,	        // COP_STCW '95 - BASIC SAFETY COURSE
            'CO' => 160,	    // CRANE OPERATOR
            'DC' => 173,	    // CREW CONSENT DOCUMENT
            'CY' => 178,	    // CYBER SECURITY CERTIFICATE
            'DG' => 210,	    // DIESEL GENERATOR ENGINE OVERHAULING TRAINING
            'EF' => 86,	        // ECDIS FURUNO CBT- 3100/3200/3300
            'FE' => 85,	        // ECDIS FURUNO FEA - 2807/2107
            'FM' => 36,	        // ECDIS FURUNO FMD-3100/3200/3300
            '54' => 28,	        // ECDIS GENERIC
            'JE' => 35,	        // ECDIS JRC 7201/9201
            'JR' => 79,	        // ECDIS JRC JAN-701/901/901M/701B/901B/2000
            'ET' => 37,	        // ECDIS TOKYO KEIKI
            'E9' => 182,	    // ELECTRIC WELDING COURSE
            '16' => 11,	        // ENDORSEMENT OF CERTIFICATE
            'EL' => 165,	    // ENERGY MANAGEMENT SYSTEM ONLINE COURSE
            'ER' => 69,	        // ENGINE ROOM SIMULATOR COURSE (ERS/ERM)
            'E4' => 109,	    // EQ IN THE WORKPLACE
            'FT' => 68,	        // FAMILIARIZATION TRAINING IN MOORING EQUIPMENT (FTME)
            'L5' => 71,	        // FREE FALL LIFEBOAT FAMILIARIZATION
            'F6' => 189,	    // FUEL AND LUBRICANTS
            'GX' => 171,	    // GAS DETECTOR CALIBRATION
            '#92' => 166,	    // HANDLING DANGEROUS AND OTHER SUBSTANCES
            'HG' => 106,	    // HEALTH & GALLEY MANAGEMENT COURSE
            'M6' => 192,	    // INDUSTRIAL MOTOR CONTROL
            'T0' => 30,	        // ISM/SMS ONLINE ASSESSMENT
            '3S' => 74,	        // ISM/SMS V 3.0 (3 DAYS COURSE)
            '57' => 158,	    // ISPS CODE
            'KI' => 31,	        // ISPS ONLINE ASSESSMENT
            'H2' => 23,	        // JAPANESE 3RD GRADE RADIO LICENSE
            'J1' => 18,	        // JAPANESE COC LICENSE
            'J5' => 25,	        // JAPANESE COOK LICENSE
            'JT' => 57,	        // JAPANESE COOK TRAINING CERTIFICATE
            'J6' => 21,	        // JAPANESE GOC
            'J7' => 34,	        // JAPANESE GOC BOOKLET
            'J9' => 24,	        // JAPANESE HEALTH SUPERVISOR
            '#J9' => 60,	        // JAPANESE MEDICAL CERTIFICATE
            'J3' => 22,	        // JAPANESE ROC
            'J2' => 19,	        // JAPANESE SEAMAN BOOK
            'JA' => 20,	        // JAPANESE SSO -  CERT. OF COMPLETION(TRAINING)
            'JB' => 80,	        // JAPANESE SSO -  CERT. OF QUALIFICATION
            'L2' => 194,	    // LATHE MACHINE OPERATOR COURSE
            'LC' => 139,	    // LIBERIAN CRA
            '#LIBPASS' => 141,	    // LIBERIAN PASSPORT
            'LB' => 140,	    // LIBERIAN SEAMAN BOOK
            'M5' => 196,	    // MAIN DIESEL ENGINE OVERHAUL TRAINING 1
            'M7' => 197,	    // MAIN DIESEL ENGINE OVERHAUL TRAINING 2
            'M8' => 198,	    // MAIN DIESEL ENGINE OVERHAUL TRAINING 3
            'M2' => 187,	    // MAIN ENGINE MANUEVERING SYSTEM SIMULATOR (NABCO)
            'LL' => 64,	        // MALAYSIAN (COR)
            'LS' => 63,	        // MALAYSIAN (SID)
            'MN' => 112,	    // MALAYSIAN CREW CONTRACT AGREEMENT (SEC)
            'MX' => 113,	    // MALAYSIAN MEDICAL CERTIFICATE (PEME)
            '#MALPASS' => 138,	    // MALAYSIAN PASSPORT
            'MD' => 137,	    // MALAYSIAN SEAMANBOOK
            'M9' => 190,	    // MARINE AND AUXILLARY MACHINERIES
            '97' => 191,	    // MARINE ELECTRO-TECHNOLOGY
            'MH' => 200,	    // MARINE HIGH VOLTAGE
            'C4' => 199,	    // MARINE REFRIGERATION
            'MI' => 62,	        // MARSHALL ISLAND LICENSE
            'MB' => 61,	        // MARSHALL ISLAND SEAMAN BOOK
            'ME' => 111,	    // ME / ENGINE FAMILARIZATION COURSE
            '#P7' => 27,	        // MEDICAL CERTIFICATE (PEME)
            '#P8' => 66,	        // MEDICAL CERTIFICATE (POST MEDICAL)
            'MF' => 167,	    // MLC 2006
            'OS' => 183,	    // MOSHI
            'N2' => 207,	    // NAVIGATIONAL WATCH SIMULATOR LEVEL 1
            'CF' => 161,	    // NICKLE ORE CARGO FAMILIARIZATION
            'O1' => 193,	    // OXYGEN_ACETYLENE WELDING COURSE
            'T7' => 168,	    // PAINTING TECHNIQUE
            'SD' => 75,	        // PANAMA ENDORSEMENT - SDSD
            'CC' => 136,	    // PANAMA ENDORSEMENT - SHIPS COOK COURSE
            'PE' => 97,	        // PANAMA ENDORSEMENT - SSO
            '28' => 14,	        // PANAMA GMDSS LICENSE
            '44' => 15,	        // PANAMA GMDSS SEAMAN'S BOOK
            'P1' => 16,	        // PANAMA LICENSE
            'P2' => 17,	        // PANAMA SEAMAN BOOK
            'PB' => 47,	        // PANAMA SSO BOOK
            'PS' => 32,	        // PANAMA SSO LICENSE
            'TF' => 172,	    // PCC SPECIAL BRIEFING
            'H4' => 185,	    // PEOPLE HANDLING SKILLS
            'Z4' => 84,	        // PHILIPPINE COP (II-4 / III-4)
            'Z5' => 56,	        // PHILIPPINE COP (II-5 / III-5)
            '27' => 13,	        // PHILIPPINE GOC
            'NC' => 54,	        // PHILIPPINE NC1 FOR (MESSMAN)
            'NR' => 53,	        // PHILIPPINE NC2 FOR (CHIEF COOK & 2ND COOK)
            'NT' => 65,	        // PHILIPPINE NC3 FOR (CHIEF COOK & 2ND COOK)
            '41' => 2,	        // PHILIPPINE PASSPORT
            'F2' => 1,	        // PHILIPPINE SEAMAN BOOK
            'PO' => 211,	    // PURIFIER OVERHAULING MAINTENANCE COURSE
            '11' => 181,	    // RATING FORMING PART OF A WATCH IN MANNED ENGINE ROOM
            'R9' => 102,	    // REAL DANGER SENSING COURSE
            'I6' => 169,	    // ROPE SLICING
            'SP' => 72,	        // SAFE PRACTICES IN HANDLING FUMIGATED CARGO
            'FO' => 174,	    // SAFETY OFFICER COURSE CERTIFICATE
            'V3' => 170,	    // SAFIR
            'SR' => 101,	    // SENIOR RATING UPGRADING COURSE
            '#SR' => 202,	    // SENIOR RATING UPGRDATING COURSE - OILER #1 (SRUC-E)
            'SI' => 90,	        // SHIP HANDLING TRAINING FOR CAPTAIN
            '49' => 96,	        // SHIP SECURITY OFFICER
            '79' => 70,	        // SHIP SIMULATOR & BRIGDE TEAMWORK w/ BRM
            'S2' => 45,	        // SINGAPORE COE
            'S1' => 46,	        // SINGAPORE GOC
            'O2' => 103,	    // SKILL ENHANCEMENT COURSE
            'I8' => 159,	    // SPAS -  DIGITRACE
            'I2' => 107,	    // SPECIAL CUISINE COURSE (INTERNATIONAL COOKING)
            'sc' => 104,	    // STEERING COURSE
            'S7' => 110,	    // STRESS MANAGEMENT SEMINAR
            'U2' => 108,	    // TEAMWORK IN CULTURAL DIVERSITY
            'KT' => 212,	    // TRADE TEST
            'D9' => 184,	    // TRIM AND STABILITY
            'BN' => 94,	        // TTOS - BASIC NAVIGATION
            'I3' => 162,	    // TTOS - COMPETENCY PROMOTIONAL INHOUSE TRAINING
            'TB' => 82,	        // TTOS - CORONA
            'AD' => 163,	    // TTOS - E-NAVIGATOR
            'KS' => 91,	        // TTOS - KLINE EMS (ENVIRONMENTAL MANAGEMENT SYSTEM)
            'T8' => 95,	        // TTOS - PCC
            'LF' => 100,	    // TTOS NISHI-F LRRS
            'I9' => 92,	        // TTOS SPECIAL OR TTOS MARITIME LEGAL MATTERS
            '42' => 78,	        // US VISA
            '51' => 3,	        // VACCINATION (YELLOW FEVER)
            '#AI' => 175,	    // VACCINATION: TYPHOID FEVER AND HEPA A & B
            'AI' => 179,	    // VARICELLA (CHICKEN POX) VACCINE
        ];

        $master = 'D11';
        $chiefMate = 'D21';
        $secondMate = 'D22';
        $thirdMate = 'D23';
        $envObserver = 'D27';
        $bosun = 'D31';
        $ableSeaman = 'D32';
        $ordinarySeaman = 'D33';
        $deckCadet = 'D49';
        $deckBoy = 'D34';
        $chiefEngineer = 'E11';
        $firstAe = 'E21';
        $secondAe = 'E22';
        $thirdAe = 'E23';
        $oiler1 = 'E31';
        $oiler = 'E32';
        $wiper = 'E33';
        $engineBoy = 'E34';
        $engineCadet = 'E49';
        $chiefCook = 'S31';
        $secondCook = 'S32';
        $messman = 'S33';

        $deckOfficers = [$master, $chiefMate, $secondMate, $thirdMate];
        $engineOfficers = [$chiefEngineer, $firstAe, $secondAe, $thirdAe];

        $deckRatings = [$bosun, $ableSeaman, $ordinarySeaman, $deckCadet, $deckBoy];
        $engineRatings = [$oiler1, $oiler, $wiper, $engineBoy, $engineCadet];

        $deckRanks = array_merge($deckOfficers, $deckRatings);
        $engineRanks = array_merge($engineOfficers, $engineRatings);
        $galleyRanks = ['S31', 'S32', 'S33'];

        $allRanks = array_merge($deckRanks, $engineRanks, $galleyRanks);

        $stringRequiredDocumentIds = implode(',', $includedDocumentIds);

        $requiredDocumentPerRank = [];

        foreach ($allRanks as $rankCode) {
            $sqlRequiredDocuments = "select d.id
                                        from cat_documents d
                                        where exists(
                                            select *
                                            from document_ranks dr
                                            where dr.country_id = 1
                                              and dr.rank_code = '$rankCode'
                                            and dr.document_id = d.id
                                            and dr.status = 1
                                                  )
                                        and d.id in ($stringRequiredDocumentIds);";
            $queryRequiredDocumentIds = $this->db->query($sqlRequiredDocuments)->result();

            $requiredDocumentIds = [];

            foreach ($queryRequiredDocumentIds as $queryRequiredDocumentId) {
                $requiredDocumentIds[] = $queryRequiredDocumentId->id;
            }
            $requiredDocumentPerRank[$rankCode] = $requiredDocumentIds;

        }

        $this->load->library('excel');
        $excelObject = PHPExcel_IOFactory::load($_FILES['userfile']['tmp_name']);
        $sheet = $excelObject->getActiveSheet()->toArray(null);
        $documents = $this->db->select()
            ->from('cat_documents')
            ->where_in('id', $includedDocumentIds)
            ->order_by('document_type')
            ->order_by('document_name')
            ->get()->result();
        $rankList = $this->db->select(['rank_type_id', 'rank_alias', 'rank_code'])->from('cat_rank')
//            ->where('STATUS', 1)
            ->get()->result();
        $ranks = [];
        foreach ($rankList as $rank) {
            $ranks[$rank->rank_code] = $rank;
        }

        $crewWithDocs = [];
        foreach ($sheet as $rowNumber => $row) {
            if($rowNumber == 0) continue;
            $date = date("Y-m-d", strtotime(date($row[5])));
            $conditions = [
                "c.FNAME" => $row[2],
                "c.GNAME" => $row[3],
                "c.BIRTHDATE" => $date,
                "c.MANNING_ID" => 1,
                "c.STATUS" => 1,
            ];
            $crew = $this->db
                ->select(['c.CREWIPN', 'c.FNAME', 'c.GNAME', 'c.MNAME', 'c.BIRTHDATE', 'c.RANK'])
                ->from('crew c')
                ->where($conditions)
                ->get()->row();
//            var_dump($this->db->last_query());
//            var_dump($crew);
//            exit();
            if($crew) {
                switch ($crew->RANK) {
                    case 'D41': // IF DECK CADET A, CHANGE TO DECK DECK CADET B
                    case 'D43': // IF ORDINARY SEAMAN MAINTENANCE, CHANGE TO DECK DECK CADET B
                    case 'D49': // IF DECK CADET A, CHANGE TO DECK DECK CADET B
                        $rank = $ranks[$deckCadet];
                        break;

                    case 'E37': // IF FITTER, CHANGE TO WIPER
                        $rank = $ranks[$wiper];
                        break;

                    case 'E55': // IF ENGINE CADET ME+, CHANGE TO ENGINE CADET
                        $rank = $ranks[$engineCadet];
                        break;

                    default:
                        $rank = $ranks[$crew->RANK];
                        break;
                }
                $crew->rank_alias = $rank->rank_alias;
                $abc = [];
                $abc['crew'] = $crew;
                $crewDocs = [];
                foreach ($documents as $document) {
//                    $crewDocStatus = $this->db->select('status')
//                        ->from('document_rank_type')
//                        ->where('document_id', $document->id)
//                        ->where('rank_type_id', $rank->rank_type_id)
//                        ->where('country_id', 1)
//                        ->get()->row();
                    if(isset($requiredDocumentPerRank[$crew->RANK])) {
//                        if(!$crewDocStatus->status) {
                        if(!in_array($document->id, $requiredDocumentPerRank[$crew->RANK])) {
                            $crewDocs[$document->id]['bg'] = 'gray';
                        } elseif (!file_exists(DOCFOLDER."veritas/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.pdf")) {
                            $background = 'red';

                            $doccode = "";

                            foreach ($includedDocumentIds as $code => $crewDocumentId) {
                                if($crewDocumentId == $document->id) {
                                    $doccode = $code;
                                    break;
                                }
                            }

                            if(substr($doccode, 0, 1) != '#') {
                                $fileUrl = "https://veripro.com.ph/document/getCrewDocument/$doccode/" . $row[1];
//                                $fileUrl = "https://veripro.com.ph/site/viewDocs/104281/D/A4";
                                $filePath = DOCFOLDER . time() . ".pdf";
                                try {
//                                    file_put_contents($filePath, file_get_contents($fileUrl));

//                                    file_put_contents($filePath, file_get_contents("https://veripro.com.ph/document/getCrewDocument/$doccode/$crew->FNAME/$crew->GNAME/$crew->BIRTHDATE"));
                                    file_put_contents($filePath, file_get_contents($fileUrl));

                                    if(filesize($filePath) < 1024) {
                                        $background = 'pink';
//                                    } else {
//                                        var_dump($filePath, filesize($filePath), $fileUrl);
//                                        exit();
                                    }

                                    unlink($filePath);
                                } catch (Exception $exception) {
                                    $crewDocs[$document->id]['bg'] = $background;
                                }

                            }
                            $crewDocs[$document->id]['bg'] = $background;
                        } elseif (file_exists(DOCFOLDER."veritas/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.pdf")) {
                            $crewDocs[$document->id]['bg'] = 'green';
                            $crewDocument = $this->db->select('uploaded_date')
                                ->from('document_upload_history')
                                ->where('crewipn', $crew->CREWIPN)
                                ->where('manning_id', 1)
                                ->order_by('uploaded_date', 'DESC')
                                ->get()->row();
                            $crewDocs[$document->id]['dateUpload'] = $crewDocument->uploaded_date;
                            $crewDocs[$document->id]['docName'] = "$crew->CREWIPN$document->country_code$document->document_code_mk";
                        } else {
                            $crewDocs[$document->id]['bg'] = 'black';
                        }
                    } else {
                        $crewDocs[$document->id]['bg'] = 'yellow';
                    }
                    $abc['documents'] = $crewDocs;
                }
                $crewWithDocs[] = $abc;
            } else  {
                $rank = $ranks[$row[0]];
                $crew = new ArrayObject();
                $crew->CREWIPN = $row[1];
                $crew->FNAME = $row[2];
                $crew->GNAME = $row[3];
                $crew->MNAME = $row[4];
                $crew->rank_alias = $rank->rank_alias;
                $abc['crew'] = $crew;
                $abc['documents'] = [];
                $crewWithDocs[] = $abc;
            }
        }
        $data = [
            'crewWithDocs' => $crewWithDocs,
            'documents' => $documents
        ];
        $this->load->view("templates/header");
        $this->load->view("veritasReport.php", $data);
        $this->load->view("templates/footer");
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="CREWDOCUMENTHISTORY">
    public function crewDocumentHistory($crewipn=null, $manning=null, $docname=null, $historyId=null) {
        if(! ($this->session->userdata('staff_no') || $this->session->userdata('principal_no'))) {
            return "";
        } else if(!($crewipn === null || $manning === null || $docname === null)) {

            if(file_exists(DOCFOLDER."$manning/$crewipn/$docname.pdf")) {
                $file = DOCFOLDER."$manning/$crewipn/$docname/$docname"."_$historyId.pdf";
            } else {
                $file = "";
            }

//            exit($file);

//            if ($cer === "cer") $file = CERDOCFOLDER."$manning/$crewipn/$docname.pdf";
//            elseif(($cer === "cc")) $file = DOCFOLDER."$manning/$crewipn/comments/$docname.pdf";
//            elseif(($cer === "cf")) $file = DOCFOLDER."$manning/company_license/$docname.pdf";
//            else {
//                if(file_exists(DOCFOLDER."$manning/$crewipn/$docname.pdf")) {
//                    $file = DOCFOLDER."$manning/$crewipn/$docname.pdf";
//                } else if(file_exists(DOCFOLDER."$manning/$crewipn/$docname.PDF")) {
//                    $file = DOCFOLDER."$manning/$crewipn/$docname.PDF";
//                } else {
//                    $file = "";
//                }
//            }
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                readfile($file);
                exit();
            } else {
                return "";
            }
        } else {
            return "";
        }
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="GETPDF">
    public function getPdf($applicantno=null, $doctype=null, $docnum=null) {
        $file = "https://veripro.com.ph/site/viewDocs/$applicantno/$doctype/$docnum";

        $newFilePath = ROOTFOLDER.time()."_1.pdf";
        $convertFilePath = ROOTFOLDER.time()."_2.pdf";
        $reconveredtFilePath = ROOTFOLDER.time()."_3.pdf";

        file_put_contents($newFilePath, file_get_contents($file));


        $coloredDocs = [ '41', '44', 'B2', 'F2', 'J2', 'JP', 'LB', 'MD', 'P2', 'MB'];

        $included = false;
        foreach ($coloredDocs as $coloredDoc) {
            $position = stripos($docnum, $coloredDoc);
            if($position !== false) {
                $included = true;
                break;
            }
        }


        if($included) {

//            shell_exec("mogrify -resize 120% $newFilePath");
////            shell_exec("gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=$reconveredtFilePath $newFilePath");
//            shell_exec("gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4  -dPDFSETTINGS=/default -dNOPAUSE -dQUIET -dDetectDuplicateImages -dCompressFonts=true -dBATCH -r10 -sPAPERSIZE=letter -dFIXEDMEDIA -dPDFFitPage -f -sOutputFile=$reconveredtFilePath $newFilePath");

            // checked before lunch
            shell_exec("convert -compress Zip -density 100x100 $newFilePath $convertFilePath");
            shell_exec("gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4  -dPDFSETTINGS=/default -dNOPAUSE -dQUIET -dDetectDuplicateImages -dCompressFonts=true -dBATCH -r10 -sPAPERSIZE=letter -dFIXEDMEDIA -dPDFFitPage -f -sOutputFile=$reconveredtFilePath $convertFilePath");

//            shell_exec("gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/default -dNOPAUSE -dQUIET -dBATCH -sPAPERSIZE=letter -dFIXEDMEDIA -dPDFFitPage -sOutputFile=$reconveredtFilePath $convertFilePath");
//            shell_exec("gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/default -dDetectDuplicateImages -dCompressFonts=true -r10 -dNOPAUSE -dQUIET -dBATCH -dDEVICEWIDTHPOINTS=50 -dDEVICEHEIGHTPOINTS=100 -sOutputFile=$reconveredtFilePath $convertFilePath");
//            shell_exec("gs -sDEVICE=pdfwrite -dPDFSETTINGS=/default -dCompatibilityLevel=1 -dDetectDuplicateImages -dCompressFonts=true -r10 -dNOPAUSE -dQUIET -dBATCH -dDEVICEWIDTHPOINTS=50 -dDEVICEHEIGHTPOINTS=100 -sOutputFile=$newFilePath $file");
//            shell_exec("gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/default -dDetectDuplicateImages -dCompressFonts=true -r75 -dNOPAUSE -dQUIET -dBATCH -dDEVICEWIDTHPOINTS=100 -dDEVICEHEIGHTPOINTS=200 -sOutputFile=$newFilePath $file");
//            $reducedFile = (filesize($file) > filesize($newFilePath)) ? $newFilePath : $file;
        } else {
            shell_exec("convert -compress Zip -density 100x100 $newFilePath $convertFilePath");
            shell_exec("gs -sDEVICE=pdfwrite -sColorConversionStrategy=Gray -dProcessColorModel=/DeviceGray -dCompatibilityLevel=1.4 -dPDFSETTINGS=/default -dDetectDuplicateImages -dCompressFonts=true -r75 -dNOPAUSE -dQUIET -dBATCH -dDEVICEWIDTHPOINTS=100 -dDEVICEHEIGHTPOINTS=200 -sOutputFile=$reconveredtFilePath $convertFilePath");

        }
            $reducedFile = (filesize($reconveredtFilePath) > filesize($newFilePath)) ? $newFilePath : $reconveredtFilePath;
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="'.basename("$docnum.pdf").'"');
        header('Content-Type: application/pdf');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        readfile($reducedFile);

        exit();
    }
    //</editor-fold>

//    public function getPdf($applicantno=null, $doctype=null, $docnum=null) {
//        $file = "http://veripro.com.ph/site/viewDocs/$applicantno/$doctype/$docnum";
//        header('Content-Description: File Transfer');
//        header('Content-Disposition: inline; filename="'.basename($file).'"');
//        header('Content-Type: application/pdf');
//        header('Expires: 0');
//        header('Cache-Control: must-revalidate');
//        header('Pragma: public');
//        readfile($file);
//        exit();
//    }

    //<editor-fold defaultstate="collapsed" desc="UPDATE DOCUMENT PER RANK MATRIX">
    public function updateDocumentRankMatrix() {
        exit('UNAUTHORIZED ACCESS!!');
        $master = 'D11';
        $chiefMate = 'D21';
        $secondMate = 'D22';
        $thirdMate = 'D23';
        $bosun = 'D31';
        $ableSeaman = 'D32';
        $ordinarySeaman = 'D33';
        $deckCadet = 'D49';
        $deckBoy = 'D34';
        $chiefEngineer = 'E11';
        $firstAe = 'E21';
        $secondAe = 'E22';
        $thirdAe = 'E23';
        $oiler1 = 'E31';
        $oiler = 'E32';
        $wiper = 'E33';
        $engineBoy = 'E34';
        $engineCadet = 'E49';
        $chiefCook = 'S31';
        $secondCook = 'S32';
        $messman = 'S33';

        $deckOfficers = [$master, $chiefMate, $secondMate, $thirdMate];
        $engineOfficers = [$chiefEngineer, $firstAe, $secondAe, $thirdAe];
        $crewOfficers = array_merge($deckOfficers, $engineOfficers);

        $deckRatings = [$bosun, $ableSeaman, $ordinarySeaman, $deckCadet, $deckBoy];
        $engineRatings = [$oiler1, $oiler, $wiper, $engineBoy, $engineCadet];
        $crewRatings = array_merge($deckRatings, $engineRatings);

        $deckRanks = array_merge($deckOfficers, $deckRatings);

        $engineRanks = array_merge($engineOfficers, $engineRatings);
        $galleyRanks = ['S31', 'S32', 'S33'];
        $allRanks = array_merge($deckRanks, $engineRanks, $galleyRanks);

        $crewDocumentMatrix = [
            201 => [ $oiler1 ],         // ADVANCE WELDING COURSE

            164 => $allRanks,           // AFFIDAVIT OF DRUG AND ALCOHOL POLICY

            77 => $allRanks,            // AUSTRALIAN MARITIME CREW VISA

            67 => $deckOfficers,        // AUTOMATIC IDENTIFICATION SYSTEMS

            186                         // BALLAST WATER MANAGEMENT COURSE
            => [ $chiefMate, $secondMate, $thirdMate ],

            98 => $crewOfficers,        // BASSNET

            87 => $allRanks,            // BIODATA

            188 => [ $secondAe ],    // BOILER COMBUSTION CONTROL COURSE

            99 => $deckOfficers,     // BRM / BTM

            10 => $crewOfficers,      // CERTIFICATE OF COMPETENCY for OFFICERS

            44 => $allRanks,     // CONTRACT

            6 => $crewOfficers,      // COP_ADVANCED TRAINING IN FIREFIGHTING

            8      // COP_MEDICAL CARE
            => [ $master, $chiefMate, $secondMate ],

            7 => $crewOfficers,      // COP_MEDICAL EMERGENCY - FIRST AID

            4 => $allRanks,      // COP_PROF. IN SURVIVAL CRAFT & RESCUE BOAT

            29 => $allRanks,     // COP_SDSD

            33     // COP_SHIP SECURTY OFFICER
            => [ $master, $chiefMate ],

            5 => $allRanks,      // COP_STCW '95 - BASIC SAFETY COURSE

            160 => $deckRanks,    // CRANE OPERATOR

            173 => $allRanks, // CREW CONSENT DOCUMENT

            178 => $allRanks,    // CYBER SECURITY CERTIFICATE

            210 => [ $secondAe ],    // DIESEL GENERATOR ENGINE OVERHAULING TRAINING

            86 => $deckOfficers,      // ECDIS FURUNO CBT- 3100/3200/3300

            85 => $deckOfficers,      // ECDIS FURUNO FEA - 2807/2107

            36 => $deckOfficers,      // ECDIS FURUNO FMD-3100/3200/3300

            28 => $deckOfficers,      // ECDIS GENERIC

            35 => $deckOfficers,      // ECDIS JRC 7201/9201

            79 => $deckOfficers,      // ECDIS JRC JAN-701/901/901M/701B/901B/2000

            81 => $deckOfficers,      // ECDIS KELVIN HUGNES - KLHG

            37 => $deckOfficers,      // ECDIS TOKYO KEIKI

            182    // ELECTRIC WELDING COURSE
            => [ $secondAe, $thirdAe, $oiler1, $oiler, $wiper ],

            11 => $crewOfficers,     // ENDORSEMENT OF CERTIFICATE

            165 => [],    // ENERGY MANAGEMENT SYSTEM ONLINE COURSE

            69 => $engineOfficers,     // ENGINE ROOM SIMULATOR COURSE (ERS/ERM)

            109 => $allRanks,    // EQ IN THE WORKPLACE

            68 => $deckRatings,     // FAMILIARIZATION TRAINING IN MOORING EQUIPMENT (FTME)

            71 => $allRanks,     // FREE FALL LIFEBOAT FAMILIARIZATION

            189    // FUEL AND LUBRICANTS
            => [ $chiefEngineer, $firstAe, $secondAe ],

            171 => $allRanks,    // GAS DETECTOR CALIBRATION

            166 => [],    // HANDLING DANGEROUS AND OTHER SUBSTANCES

            106    // HEALTH & GALLEY MANAGEMENT COURSE
            => [ $chiefCook, $secondCook ],

            206    // IMSBC CODE TRAINING
            => array_merge($deckOfficers,  [$bosun] ),

            192    // INDUSTRIAL MOTOR CONTROL
            => [ $thirdAe ],

            30 => $allRanks,    // ISM/SMS ONLINE ASSESSMENT

            74 => $allRanks,     // ISM/SMS V 3.0 (3 DAYS COURSE)

            158 => $allRanks,    // ISPS CODE

            31 => $allRanks,     // ISPS ONLINE ASSESSMENT

            23 => $deckOfficers,     // JAPANESE 3RD GRADE RADIO LICENSE

            18 => $crewOfficers,     // JAPANESE COC LICENSE

            25     // JAPANESE COOK LICENSE
            => [ $chiefCook, $secondCook ],

            57     // JAPANESE COOK TRAINING CERTIFICATE
            => [ $secondCook],

            58 => $allRanks,     // JAPANESE FECALYSIS

            21 => $crewOfficers,     // JAPANESE GOC

            34 => $crewOfficers,     // JAPANESE GOC BOOKLET

            24     // JAPANESE HEALTH SUPERVISOR
            => [ $master, $chiefMate, $secondMate ],

            60 => $allRanks,     // JAPANESE MEDICAL CERTIFICATE

            22 => $deckOfficers,    // JAPANESE ROC

            19 => $allRanks,     // JAPANESE SEAMAN BOOK

            20     // JAPANESE SSO - CERT. OF COMPLETION(TRAINING)
            => [ $master, $chiefMate ],

            80     // JAPANESE SSO - CERT. OF QUALIFICATION
            => [ $master, $chiefMate ],

            194     // LATHE MACHINE OPERATOR COURSE
            => [ $secondAe, $thirdAe, $oiler1 ],

            139 => $allRanks,     // LIBERIAN CRA

            141 => $allRanks,     // LIBERIAN PASSPORT

            140 => $allRanks,     // LIBERIAN SEAMAN BOOK

            196     // MAIN DIESEL ENGINE OVERHAUL TRAINING 1
            => [ $secondAe, $thirdAe, $oiler1, $oiler ],

            197     // MAIN DIESEL ENGINE OVERHAUL TRAINING 2
            => [ $chiefEngineer, $firstAe ],

            198     // MAIN DIESEL ENGINE OVERHAUL TRAINING 3
            => [ $chiefEngineer, $firstAe ],

            187     // MAIN ENGINE MANUEVERING SYSTEM SIMULATOR (NABCO)
            => [ $chiefEngineer, $firstAe ],

            64 => $crewOfficers,     // MALAYSIAN (COR)

            63 => $allRanks,     // MALAYSIAN (SID)

            112 => $allRanks,     // MALAYSIAN CREW CONTRACT AGREEMENT (SEC)

            113 => $allRanks,     // MALAYSIAN MEDICAL CERTIFICATE (PEME)

            138 => [],     // MALAYSIAN PASSPORT

            137 => [],     // MALAYSIAN SEAMANBOOK

            190 => [ $thirdAe ],     // MARINE AND AUXILLARY MACHINERIES

            191 => [ $thirdAe ],     // MARINE ELECTRO-TECHNOLOGY

            200 => $engineOfficers,     // MARINE HIGH VOLTAGE

            199 => [ $thirdAe ],    // MARINE REFRIGERATION

            88 => $deckOfficers,     // MARSHALL ISLAND GMDSS

            62 => $deckOfficers,     // MARSHALL ISLAND LICENSE

            61 => $allRanks,     // MARSHALL ISLAND SEAMAN BOOK

            111 => $engineOfficers,     // ME / ENGINE FAMILARIZATION COURSE

            27 => $allRanks,     // MEDICAL CERTIFICATE (PEME)

            66 => $allRanks,     // MEDICAL CERTIFICATE (POST MEDICAL)

            167 => $allRanks,     // MLC 2006

            183     // MOSHI
            => [ $secondAe, $thirdAe, $oiler1, $oiler ],

            207 => [$secondMate ],     // NAVIGATIONAL WATCH SIMULATOR LEVEL 1

            161     // NICKLE ORE CARGO FAMILIARIZATION
            => array_merge($deckOfficers, [ $bosun ] ),

            193 => $engineOfficers,     // OXYGEN_ACETYLENE WELDING COURSE

            168     // PAINTING TECHNIQUE
            => [ $chiefMate, $bosun, $ableSeaman, $ordinarySeaman ],

            75     // PANAMA ENDORSEMENT - SDSD
            => array_merge([ $secondMate, $thirdMate], $engineRanks, $galleyRanks ),

            136 => [],     // PANAMA ENDORSEMENT - SHIPS COOK COURSE

            97     // PANAMA ENDORSEMENT - SSO
            => [ $master, $chiefMate ],

            14 => $deckOfficers,     // PANAMA GMDSS LICENSE

            15 => $deckOfficers,     // PANAMA GMDSS SEAMAN'S BOOK

            16 => $crewOfficers,     // PANAMA LICENSE

            157 => $allRanks,     // PANAMA MEDICAL CERTIFICATE

            17 => $allRanks,     // PANAMA SEAMAN BOOK

            47 => [],     // PANAMA SSO BOOK

            32 => [],     // PANAMA SSO LICENSE

            172 => $deckOfficers,     // PCC SPECIAL BRIEFING

            185     // PEOPLE HANDLING SKILLS
            => [ $master, $chiefMate ],

            84     // PHILIPPINE COP (II-4 / III-4)
            => [ $bosun, $ableSeaman, $ordinarySeaman ],

            56     // PHILIPPINE COP (II-5 / III-5)
            => [ $bosun, $ableSeaman, $ordinarySeaman ],

            13 => $deckOfficers,     // PHILIPPINE GOC

            9 => $crewOfficers,     // PHILIPPINE LICENSE

            54     // PHILIPPINE NC1 FOR (MESSMAN)
            => [ $messman ],

            53 => [],     // PHILIPPINE NC2 FOR (CHIEF COOK & 2ND COOK)

            65     // PHILIPPINE NC3 FOR (CHIEF COOK & 2ND COOK)
            => [ $chiefCook, $secondCook ],

            2 => $allRanks,     // PHILIPPINE PASSPORT

            1 => $allRanks,     // PHILIPPINE SEAMAN BOOK

            211 => [ $thirdAe ],     // PURIFIER OVERHAULING MAINTENANCE COURSE

            181     // RATING FORMING PART OF A WATCH IN MANNED ENGINE ROOM
            => [ $thirdAe, $oiler1 ],

            102     // REAL DANGER SENSING COURSE
            => array_merge([ $chiefMate, $secondMate, $thirdMate, $firstAe, $secondAe, $thirdAe ], $deckRatings, $engineRatings, $galleyRanks),

            169     // ROPE SLICING
            => [ $bosun, $ableSeaman, $ordinarySeaman ],

            72 => $allRanks,     // SAFE PRACTICES IN HANDLING FUMIGATED CARGO

            174 => $crewOfficers,     // SAFETY OFFICER COURSE CERTIFICATE

            170     // SAFIR
            => [ $master, $chiefMate, $chiefEngineer, $firstAe ],

            101 => [ $bosun ],     // SENIOR RATING UPGRADING COURSE

            202 => [ $oiler1 ],     // SENIOR RATING UPGRDATING COURSE - OILER #1 (SRUC-E)

            90 => [ $master, $bosun ],     // SHIP HANDLING TRAINING FOR CAPTAIN

            96     // SHIP SECURITY OFFICER
            => [ $master, $chiefMate, $chiefEngineer ],

            70 => $deckOfficers,     // SHIP SIMULATOR & BRIGDE TEAMWORK w/ BRM

            45 => $crewOfficers,     // SINGAPORE COE

            46 => $crewOfficers,     // SINGAPORE GOC

            103     // SKILL ENHANCEMENT COURSE
            => [ $bosun, $oiler ],

            159 => $crewOfficers,     // SPAS - DIGITRACE

            107     // SPECIAL CUISINE COURSE (INTERNATIONAL COOKING)
            => [ $chiefCook, $secondCook ],

            104     // STEERING COURSE
            => [ $bosun, $ordinarySeaman ],

            110 => $allRanks,     // STRESS MANAGEMENT SEMINAR

            108 => $galleyRanks,     // TEAMWORK IN CULTURAL DIVERSITY

            212     // TRADE TEST
            => [ $oiler1, $oiler, $chiefCook ],

            184 => [ $chiefMate ],     // TRIM AND STABILITY

            94     // TTOS - BASIC NAVIGATION
            => [ $secondMate, $thirdMate, $ableSeaman, $ordinarySeaman ],

            162 => $crewOfficers,     // TTOS - COMPETENCY PROMOTIONAL INHOUSE TRAINING

            82 => $allRanks,     // TTOS - CORONA

            163 => $deckOfficers,     // TTOS - E-NAVIGATOR

            91 => $allRanks,     // TTOS - KLINE EMS (ENVIRONMENTAL MANAGEMENT SYSTEM)

            209 => $engineRanks,     // TTOS - MARINE ELEVATOR SAFETY TRAINING

            95 => $allRanks,     // TTOS - PCC

            100 => $allRanks,     // TTOS NISHI-F LRRS

            92     // TTOS SPECIAL OR TTOS MARITIME LEGAL MATTERS
            => [ $master, $chiefMate ],

            78 => $allRanks,     // US VISA

            3 => $allRanks,     // VACCINATION (YELLOW FEVER)

            175 => $galleyRanks,     // VACCINATION: TYPHOID FEVER AND HEPA A & B

            179 => $allRanks,     // VARICELLA (CHICKEN POX) VACCINE

            208     // WOODCHIP CARRIER FAMILIARIZATION COURSE
            => array_merge($deckRanks, [ $chiefEngineer, $firstAe ])

        ];

        $queryIncludedRanks = $this->db->select(['rank', 'rank_code', 'ALIAS2'])
            ->from('cat_rank')
            ->where_in('rank_code', $allRanks)
            ->get()->result();

        $includedRanks = [];

        foreach ($queryIncludedRanks as $includedRank) {
            $includedRanks[$includedRank->rank_code] = $includedRank;
        }


        echo "<style>
                table.table-hover tr:hover {
                    background: #ccc;
                }
            </style>
            <table border='1' class='table-hover'>
                <thead>
                <tr>
                    <th>DOCCODE</th>
                    <th>DOCUMENT NAME</th>";

        foreach ($allRanks as $rank) {
            $queriedRank = $includedRanks[$rank];
            echo "<th>$queriedRank->ALIAS2</th>";
        }
        echo "</tr>
                </thead>
                <tbody>";

        foreach ($crewDocumentMatrix as $documentId => $rankCodes) {
            $crewDocument = $this->db->select(['document_code', 'document_name'])
                ->from('cat_documents')
                ->where('id', $documentId)
                ->where_not_in('document_type', [ 'CF', 'V' ])
                ->get()->row();
            if($crewDocument) {
                echo "<tr>
                        <td>$crewDocument->document_code</td>
                        <td>$crewDocument->document_name</td>";

                foreach ($allRanks as $rank) {
                    $color = in_array($rank, $rankCodes) ? '' : 'background: red;';
                    $required = in_array($rank, $rankCodes) ? 'REQ' : '';
                    $titleRank = $includedRanks[$rank]->rank;
                    echo "<td style='$color border-bottom: 1px solid black;' title='$titleRank'>$required</td>";
                }

                echo "</tr>";
            }
        }

        echo "</tbody>
            </table>";

        $this->db->update('document_ranks', ['status' => 0]);
        foreach ($crewDocumentMatrix as $documentId => $rankCodes) {
            foreach ($rankCodes as $rankCode) {
                $existingMatrix = $this->db->select()
                    ->from('document_ranks')
                    ->where('document_id', $documentId)
                    ->where('rank_code', $rankCode)
                    ->where('country_id', 1)
                    ->get()->row();

                if($existingMatrix) {
                    $this->db->where('id', $existingMatrix->id)->update(['status', 1]);
                } else {
                    $newMatrixData = [
                        'document_id' => $documentId,
                        'rank_code' => $rankCode,
                        'country_id' => 1,
                        'status' => 1,
                    ];
                    $this->db->insert('document_ranks', $newMatrixData);
                }
            }
        }

    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="VIEW DOCUMENT REPORT">
    public function viewDocumentReport($crewIpn, $manning, $documentId, $crewDocumentId) {
        if(! ($this->session->userdata('staff_no') || $this->session->userdata('principal_no'))) {
            return "";
        } else if(!($crewIpn === null || $manning === null || $documentId === null || $crewDocumentId == null)) {

            $file = DOCFOLDER."$manning/$crewIpn/$documentId/$crewDocumentId.pdf";
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                readfile($file);
                exit();
            } else {
                return "";
            }
        } else {
            return "";
        }
    }
    //</editor-fold>


    //<editor-fold defaultstate="collapsed" desc="DOWNLOAD DOCUMENT REPORT">
    public function download_document_report($crewIpn, $manning, $documentId, $crewDocumentId) {
        $crewDocumentPath = DOCFOLDER."$manning/$crewIpn/$documentId/$crewDocumentId.pdf";
        $selections = [
            'dh.crewipn',
            'd.document_code'
        ];
        $crewDocument = $this->db->select($selections)
            ->from('document_upload_history dh')
            ->join('cat_documents d', 'd.id = dh.document_code', 'left')
            ->where('dh.id', $crewDocumentId)
            ->get()->row();
        $name = "$crewDocument->crewipn" . '_' ."$crewDocument->document_code.pdf";
        $data = file_get_contents($crewDocumentPath);
        force_download($name, $data);
    }
    //</editor-fold>

}
