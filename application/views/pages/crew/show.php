<?php
include_once APPPATH.'vendor/autoload.php';
function getGrade($file = null) {
    $finalGrade = "";
    $grade = "";
    if($file) {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($file);
        $text = $pdf->getText();

        for($j=0; $j<strlen($text); $j++) {
            $letter = substr($text, $j, 1);
            if(is_numeric($letter)) {
                $grade .= $letter;
            } else {
                if($grade > 50 && $grade < 100 && strlen($grade) == 2) {
                    $finalGrade = $grade;
                }
                $grade = "";
            }
        }
    }
    return $finalGrade;
}
?>

<br />

<div class="well well-small">
    <b>
        <?php
        $root = base_url();
        $rank = $crew->RANK;
        echo $crew->manning_name;
        ?>
    </b>
    <span class="pull-right">
        <?php if($this->session->userdata('manning_id') == '7') { ?>
            <a type="button" href=" <?= base_url("mannings/index/$crew->MANNING_ID"); ?>" class="btn btn-mini"><i class="icon-arrow-left"></i> Back </a>
        <?php } else { ?>
            <a type="button" href="<?= base_url("mannings/index/".$this->session->userdata('manning_id')); ?>" class="btn btn-mini"><i class="icon-arrow-left"></i> Back</a>
        <?php } ?>
    </span>
</div>

<div class="row">
    <div class="span9 pull-right">
        <?php if($this->session->flashdata('errors')) { ?>
            <div class="alert alert-error" style="" >
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?= $this->session->flashdata('errors'); ?>
            </div>
        <?php } else if($this->session->flashdata('success')) { ?>
            <div class="alert alert-success" style="" >
                <button type="button" class="close" data-dismiss="alert">×</button>
                <b><?= $this->session->flashdata('success'); ?></b>
            </div>
        <?php } ?>
    </div>
</div>

<div class="row">
    <div class="span3">
        <center>
            <?php if(file_exists(DOCFOLDER.$crew->manning_folder_name."/picture/$crew->CREWIPN.jpg")) { ?>
                <img src="<?=  VIEWPIC."$crew->CREWIPN/".$crew->manning_folder_name ;?>" class="thumbnail" style="width: 120px; height: 120px;"/> <br />
                <?php if($this->session->userdata("type") == "Staff") { ?>
                    <a href="<?= base_url("crews/deletePicture/$crew->ID"); ?>" class="btn btn-mini" onclick="return confirm('Are you sure you want to delete?');" style="font-weight: bold">Del Pic</a>
                <?php } else { ?>
                    <a class="btn btn-success btn-mini" href="<?=  base_url("welcome/download_picture/$crew->manning_folder_name/$crew->CREWIPN"); ?>">
                        <i class="icon-download icon-white"></i> Download Picture
                    </a>
                <?php } ?>
            <?php } else { ?>
                <img src="<?=  base_url("assets/img/others/photo.png"); ?>" class="thumbnail" style="width: 120px; height: 120px;"/>
                <?php if($this->session->userdata("type") == "Staff") { ?>
                    <a href="<?= base_url("crews/uploadPicture/$crew->ID"); ?>" class="btn btn-info btn-mini"> Upload </a>
                <?php }  ?>
            <?php } ?>
            <?php if($this->session->userdata("type") == "Staff") { ?>
                <a class="btn btn-mini" style="font-weight: bold;" href="<?= base_url("crews/edit/$crew->ID"); ?>">
                    <i class="icon-edit"></i>  Edit Info
                </a>
            <?php } ?>
        </center>
    </div>

    <div class="span9">
        <table class="table table-condensed" style="font-size:12px;padding:0px;margin:0px;">
            <tbody>
            <tr>
                <td>NAME :</td>
                <td><?= "$crew->FNAME, $crew->GNAME $crew->MNAME ($crew->OTHER_FULLNAME)"; ?></td>
                <td>CONTACT #1 :</td>
                <td><?= $crew->CONTACT_NO1; ?></td>
            </tr>
            <tr>
                <td>STATUS :</td>
                <td> <?= ($crew->STATUS == 1)?'ACTIVE':'INACTIVE'; ?> </td>
                <td>CONTACT #2 :</td>
                <td><?= $crew->CONTACT_NO2; ?></td>
            </tr>
            <tr>
                <td>CREWIPN :</td>
                <td><?= $crew->CREWIPN; ?></td>
                <td>EMAIL ADDRESS :</td>
                <td><?= $crew->EMAIL_ADDRESS; ?></td>
            </tr>
            <tr>
                <td>RANK :</td>
                <td><?= $crew->rank ?>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>BIRTHDAY :</td>
                <td><?= $crew->BIRTHDATE; ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>ADDRESS :</td>
                <td  colspan="3"><?= $crew->ADDRESS; ?></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="span12">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#consent" data-toggle="tab">Consent</a></li>
            <li><a href="#201" data-toggle="tab">Biodata</a></li>
            <li><a href="#documents" data-toggle="tab">Documents</a></li>
            <?php for($flagCounter = 0; $flagCounter<7; $flagCounter++) { ?>
                <li id="tab<?= $flags[$flagCounter]['id']; ?>">
                    <a href="#<?= $flags[$flagCounter]['id']; ?>" data-toggle="tab" id="<?=  $flags[$flagCounter]['code']; ?>">
                        <?= ucwords(strtolower($flags[$flagCounter]['name']), " "); ?>
                    </a>
                </li>
            <?php } ?>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Others
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <?php for($flagCounter = 7; $flagCounter<count($flags); $flagCounter++) { ?>
                        <li id="tab<?= $flags[$flagCounter]['id']; ?>">
                            <a href="#<?= $flags[$flagCounter]['id']; ?>" data-toggle="tab" id="<?=  $flags[$flagCounter]['code']; ?>">
                                <?= ucwords(strtolower($flags[$flagCounter]['name']), " "); ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
            <li><a href="#cer" data-toggle="tab">CER</a></li>
            <li class="pull-right">
                <button id="printAll" onclick="printAll(this);" class="btn btn-primary">Print/View Multiple</button>
                <button id="cancelPrint" onclick="cancelPrintAll(this);" class="btn btn-danger" title="Cancel Print Multiple" style="display: none;"><i class="icon-remove icon-white"></i></button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="consent">
                <table class="table table-striped table-condensed table-hover">
                    <thead>
                    <tr style="font-weight:bold;font-size:14px;" class="text-error">
                        <td width="500">Document Name</td>
                        <td width="200">Document Code</td>
                        <td width="200">Last Update</td>
                        <td width="250" style="text-align: center;">Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($documents as $document) { ?>
                        <?php if($document->document_type == 'A'){ ?>
                            <tr style="font-size: 11px; font-weight: bold;">
                                <td><?= $document->document_name ; ?></td>
                                <td><?= $document->document_code_mk ; ?></td>
                                <td>
                                    <?php
                                    $fileName = "";
                                    if(file_exists(DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.pdf")) {
                                        $fileName = DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.pdf";
                                    } else if(file_exists(DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.PDF")) {
                                        $fileName = DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.PDF";
                                    }
                                    if(file_exists($fileName)) {
                                        $query = mysqli_query($con, "SELECT uploaded_date 
                                                                                          FROM document_upload_history 
                                                                                          WHERE document_code='$document->id'
                                                                                           AND applicantno='$crew->CREWIPN'
                                                                                           ORDER BY uploaded_date DESC");
                                        $docHistory = mysqli_fetch_array($query);
                                        echo $docHistory['uploaded_date'];
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if(file_exists($fileName)) { ?>
                                        <a class="btn btn-mini btn-warning" target="_blank" href="<?= base_url("welcome/viewDocument/$crew->CREWIPN/$crew->manning_folder_name/$crew->CREWIPN$document->country_code$document->document_code_mk"); ?>">
                                            <i class="icon-file"></i> View
                                        </a> &nbsp;
                                        <?php if($this->session->userdata('type') == "Staff") { ?>
                                            <a href="<?= base_url("crews/deleteDoc/$crew->CREWIPN/$crew->manning_folder_name/$crew->CREWIPN$document->country_code$document->document_code_mk/$crew->ID"); ?>"
                                               class="btn btn-danger btn-mini" style="color:#000;"
                                               onclick="return confirm('Are you sure you want to delete?');">
                                                <i class="icon-remove"></i> Delete
                                            </a>
                                        <?php } else { ?>
                                            <a href="<?= base_url("welcome/download_document/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk/$crew->manning_folder_name"); ?>" class="btn btn-success btn-mini">
                                                <i class="icon-download icon-white"></i> Download
                                            </a>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <?php if($this->session->userdata('type') == "Staff") { ?>
                                            <a href="<?= base_url("crews/uploadDoc/$crew->manning_folder_name/$document->id/$crew->ID");  ?>" class="btn btn-info btn-mini">
                                                <i class="icon-upload icon-white"></i> Upload
                                            </a>
                                        <?php } else { ?>
                                            <span style="color: #ff0000">NO DOCUMENT</span>
                                        <?php } ?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if(file_exists($fileName)) { ?>
                                        <input type="checkbox" name="cbxFile" value="<?= $fileName ?>" onclick="addFile(this);">
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php break; } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade in" id="201">
                <table class="table table-striped table-condensed table-hover">
                    <thead>
                    <tr style="font-weight:bold;font-size:14px;" class="text-error">
                        <td width="500">Document Name</td>
                        <td width="200">Document Code</td>
                        <td width="200">Last Update</td>
                        <td width="250" style="text-align: center;">Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($documents as $document) { ?>
                        <?php if($document->document_type == 'B'){ ?>
                            <tr style="font-size: 11px; font-weight: bold;">
                                <td><?= $document->document_name ; ?></td>
                                <td><?= $document->document_code_mk ; ?></td>
                                <td>
                                    <?php
                                    $fileName = "";
                                    if(file_exists(DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.pdf")) {
                                        $fileName = DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.pdf";
                                    } else if(file_exists(DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.PDF")) {
                                        $fileName = DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.PDF";
                                    }
                                    if(file_exists($fileName)) {
                                        $query = mysqli_query($con, "SELECT uploaded_date 
                                                                                          FROM document_upload_history 
                                                                                          WHERE document_code='$document->id'
                                                                                           AND applicantno='$crew->CREWIPN'
                                                                                           ORDER BY uploaded_date DESC");
                                        $docHistory = mysqli_fetch_array($query);
                                        echo $docHistory['uploaded_date'];
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if(file_exists($fileName)) { ?>
                                        <a class="btn btn-mini btn-warning" target="_blank" href="<?= base_url("welcome/viewDocument/$crew->CREWIPN/$crew->manning_folder_name/$crew->CREWIPN$document->country_code$document->document_code_mk"); ?>">
                                            <i class="icon-file"></i> View
                                        </a> &nbsp;
                                        <?php if($this->session->userdata('type') == "Staff") { ?>
                                            <a href="<?= base_url("crews/deleteDoc/$crew->CREWIPN/$crew->manning_folder_name/$crew->CREWIPN$document->country_code$document->document_code_mk/$crew->ID"); ?>"
                                               class="btn btn-danger btn-mini" style="color:#000;"
                                               onclick="return confirm('Are you sure you want to delete?');">
                                                <i class="icon-remove"></i> Delete
                                            </a>
                                        <?php } else { ?>
                                            <a href="<?= base_url("welcome/download_document/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk/$crew->manning_folder_name"); ?>" class="btn btn-success btn-mini">
                                                <i class="icon-download icon-white"></i> Download
                                            </a>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <?php if($this->session->userdata('type') == "Staff") { ?>
                                            <a href="<?= base_url("crews/uploadDoc/$crew->manning_folder_name/$document->id/$crew->ID");  ?>" class="btn btn-info btn-mini">
                                                <i class="icon-upload icon-white"></i> Upload
                                            </a>
                                        <?php } else { ?>
                                            <span style="color: #ff0000">NO DOCUMENT</span>
                                        <?php } ?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if(file_exists($fileName)) { ?>
                                        <input type="checkbox" name="cbxFile" value="<?= $fileName ?>" onclick="addFile(this);">
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php break; } ?>
                    <?php } ?>
                    </tbody>
                </table>

                <table class="table table-condensed" style="font-size:12px;padding:0px;margin:0px;">
                    <tbody>
                    <tr>
                        <td>NAME :</td>
                        <td><?= "$crew->FNAME, $crew->GNAME $crew->MNAME ($crew->OTHER_FULLNAME)"; ?></td>
                        <td>CONTACT #1 :</td>
                        <td><?= $crew->CONTACT_NO1; ?></td>
                    </tr>
                    <tr>
                        <td>STATUS :</td>
                        <td> <?= ($crew->STATUS == 1)?'ACTIVE':'INACTIVE'; ?> </td>
                        <td>CONTACT #2 :</td>
                        <td><?= $crew->CONTACT_NO2; ?></td>
                    </tr>
                    <tr>
                        <td>CREWIPN :</td>
                        <td><?= $crew->CREWIPN; ?></td>
                        <td>EMAIL ADDRESS :</td>
                        <td><?= $crew->EMAIL_ADDRESS; ?></td>
                    </tr>
                    <tr>
                        <td>RANK :</td>
                        <td><?= $crew->rank ?>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>BIRTHDAY :</td>
                        <td><?= $crew->BIRTHDATE; ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ADDRESS :</td>
                        <td  colspan="3"><?= $crew->ADDRESS; ?></td>
                    </tr>
                    <tr>
                        <td>POSTAL CODE :</td>
                        <td><?= $crew->POSTAL_CODE; ?></td>
                        <td>DATE OF EMPLOYMENT :</td>
                        <td><?= $crew->DATE_HIRED; ?></td>
                    </tr>
                    <tr>
                        <td>SCHOOL :</td>
                        <td><?= $crew->SCHOOL_NAME; ?></td>
                        <td>DATE GRADUATED :</td>
                        <td><?= $crew->SCHOOL_DATE_GRADUATED; ?></td>
                    </tr>
                    <tr>
                        <td>EMPLOYMENT NUMBER :</td>
                        <td><?= $crew->CREWCODE; ?></td>
                        <td>INSURANCE NUMBER :</td>
                        <td><?= $crew->INSURANCE_NUMBER; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="documents">
                <table class="table table-striped table-condensed table-hover">
                    <thead>
                    <tr style="font-weight:bold;font-size:14px;" class="text-error">
                        <td width="500">Document Name</td>
                        <td width="200">Document Code</td>
                        <td width="200">Last Update</td>
                        <td width="250" style="text-align: center;">Action</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($documents as $document) { ?>
                        <?php if($document->document_type == 'D'){ ?>
                            <tr
                                <?php if((($document->document_code == 'N6')) && (($rank != 'D11') && ($rank != 'D21') && ($rank != 'D22') && ($rank != 'D23'))){
                                    echo "style=\"display:none;font-size:11px;font-weight: bold;\"";
                                } else if((($document->document_code == 'N536') || ($document->document_code == 'N239')) && (($rank != 'D11') &&
                                        ($rank != 'D21') && ($rank != 'D22') && ($rank != 'D23')&& ($rank != 'E11') && ($rank != 'E21') && ($rank != 'E22') && ($rank != 'E23'))) {
                                    echo "style=\"display:none;font-size:11px;font-weight: bold;\"";
                                } else if(($document->document_code == 'N337') && (($rank != 'D11') && ($rank != 'D21') && ($rank != 'D22') && ($rank != 'E11') && ($rank != 'E21'))) {
                                    echo "style=\"display:none;font-size:11px;font-weight: bold;\"";
                                } else if(($document->document_code == 'CTCSC') && !in_array($rank, array("S31", "S32")) ) {
                                    echo "style=\"display:none;font-size:11px;font-weight: bold;\"";
                                } else if(($document->document_code == 'VACTYHEP') && !in_array($rank, array("S31", "S32", "S33")) ) {
                                    echo "style=\"display:none;font-size:11px;font-weight: bold;\"";
                                } else { echo "style=\"font-size:11px;font-weight: bold;\"";  }?>>
                                <td><?php echo $document->document_name ; ?></td>
                                <td><?php echo $document->document_code_mk ; ?></td>
                                <td>
                                    <?php
                                    $fileName = "";
                                    if(file_exists(DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.pdf")) {
                                        $fileName = DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.pdf";
                                    } else if(file_exists(DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.PDF")) {
                                        $fileName = DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.PDF";
                                    }
                                    if(file_exists($fileName)) {
                                        $query = mysqli_query($con, "SELECT uploaded_date 
                                                                                          FROM document_upload_history 
                                                                                          WHERE document_code='$document->id'
                                                                                           AND applicantno='$crew->CREWIPN'
                                                                                           ORDER BY uploaded_date DESC");
                                        $docHistory = mysqli_fetch_array($query);
                                        echo $docHistory['uploaded_date'];
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if(file_exists($fileName)) { ?>
                                        <a class="btn btn-mini btn-warning" target="_blank" href="<?= base_url("welcome/viewDocument/$crew->CREWIPN/$crew->manning_folder_name/$crew->CREWIPN$document->country_code$document->document_code_mk"); ?>">
                                            <i class="icon-file"></i> View
                                        </a> &nbsp;
                                        <?php if($this->session->userdata('type') == "Staff") { ?>
                                            <a href="<?= base_url("crews/deleteDoc/$crew->CREWIPN/$crew->manning_folder_name/$crew->CREWIPN$document->country_code$document->document_code_mk/$crew->ID"); ?>"
                                               class="btn btn-danger btn-mini" style="color:#000;"
                                               onclick="return confirm('Are you sure you want to delete?');">
                                                <i class="icon-remove"></i> Delete
                                            </a>
                                        <?php } else { ?>
                                            <a href="<?= base_url("welcome/download_document/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk/$crew->manning_folder_name"); ?>" class="btn btn-success btn-mini">
                                                <i class="icon-download icon-white"></i> Download
                                            </a>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <?php if($this->session->userdata('type') == "Staff") { ?>
                                            <a href="<?= base_url("crews/uploadDoc/$crew->manning_folder_name/$document->id/$crew->ID");  ?>" class="btn btn-info btn-mini">
                                                <i class="icon-upload icon-white"></i> Upload
                                            </a>
                                        <?php } else { ?>
                                            <span style="color: #ff0000">NO DOCUMENT</span>
                                        <?php } ?>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if(file_exists($fileName)) { ?>
                                        <input type="checkbox" name="cbxFile" value="<?= $fileName ?>" onclick="addFile(this);">
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>

                </table>
            </div>

            <?php foreach ($flags as $flag) { ?>
                <?php
                $code = $flag['code'];
                $id = $flag['id'];
                $count = 0;
                ?>
                <div class="tab-pane fade" id="<?= $id ?>">
                    <h3> <?= $flag['name']; ?> </h3>
                    <table class="table table-striped table-condensed table-hover">
                        <thead>
                        <tr style="font-weight:bold;font-size:14px;" class="text-error">
                            <td width="500" style="text-align: center;" colspan="4"> <h3>LICENSES</h3> </td>
                        </tr>
                        <tr style="font-weight:bold;font-size:14px;" class="text-error">
                            <td width="500">License Name</td>
                            <td width="200">License Code</td>
                            <td width="200">Last Update</td>
                            <td width="250" style="text-align: center;">Action</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($documents as $document) { ?>
                            <?php if($document->document_type == 'L'  && $document->country_code == $code){ ?>
                                <tr
                                    <?php if( (($document->document_code == 'PS') || ($document->document_code == 'N6') || ($document->document_code == 'PB')
                                            || ($document->document_code == 'H2') || ($document->document_code == 'J7') || ($document->document_code == 'J6')
                                            || ($document->document_code == 'J3') || ($document->document_code == '27') || ($document->document_code == 'CH')
                                            || ($document->document_code == 'HG') || ($document->document_code == 'CG') || ($document->document_code == 'J9'))
                                        && (($rank != 'D11') && ($rank != 'D21') && ($rank != 'D22') && ($rank != 'D23') && ($rank != 'D32'))){
                                        echo "style=\"display:none;font-size:11px;font-weight: bold;\"";
                                    } else if((($document->document_code == 'F1') || ($document->document_code == '16') || ($document->document_code == '18')
                                            || ($document->document_code == 'P1') || ($document->document_code == 'J1') || ($document->document_code == '44')
                                            || ($document->document_code == 'S1') || ($document->document_code == 'S2')
                                            //                                        || ($document->document_code == 'LS')
                                            || ($document->document_code == 'LL') || ($document->document_code == 'CL') || ($document->document_code == 'HL')
                                            || ($document->document_code == '28')) && (($rank != 'D11') && ($rank != 'D21') && ($rank != 'D22')
                                            && ($rank != 'D23') && ($rank != 'D32') && ($rank != 'D41') && ($rank != 'E11') && ($rank != 'E21') && ($rank != 'E22')
                                            && ($rank != 'E23') && ($rank != 'E32') && ($rank != 'E41'))) {
                                        echo "style=\"display:none;font-size:11px;font-weight: bold;\"";
                                    } else if((($document->document_code == 'JA') || ($document->document_code == 'JB')) && (($rank != 'D11') &&
                                            ($rank != 'D21') && ($rank != 'D22') && ($rank != 'D23') && ($rank != 'E11') && ($rank != 'E21'))) {
                                        echo "style=\"display:none;font-size:11px;font-weight: bold;\"";
                                    } else if(($document->document_code == 'C0') && (($rank == 'D11') || ($rank == 'D21') || ($rank == 'D22') || ($rank == 'D23')
                                            || ($rank == 'E11') || ($rank == 'E21') || ($rank == 'E22') || ($rank == 'E23'))) {
                                        echo "style=\"display:none;font-size:11px;font-weight: bold;\"";
                                    } else if((($document->document_code == '6340') || ($document->document_code == 'GT')
                                            || ($document->document_code == 'CC')) && (($rank != 'S31') && ($rank != 'S32') && ($rank != 'S33')) ) {
                                        echo "style=\"display:none;font-size:11px;font-weight: bold;\"";
                                    }  else if((($document->document_code == 'J5') || ($document->document_code == 'NT')
                                            || ($document->document_code == 'CC')) && (($rank != 'S31') && ($rank != 'S32')) ) {
                                        echo "style=\"display:none;font-size:11px;font-weight: bold;\"";
                                    } else if(($document->document_code == '6342') && ($rank != 'S33')) {
                                        echo "style=\"display:none;font-size:11px;font-weight: bold;\"";
                                    } else {
                                        echo "style=\"font-size:11px;font-weight: bold;\"";
                                        $count = $count + 1;
                                    }?>>
                                    <td><?= $document->document_name ; ?></td>
                                    <td><?= $document->document_code_mk ; ?></td>
                                    <td>
                                        <?php
                                        $fileName = "";
                                        if(file_exists(DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.pdf")) {
                                            $fileName = DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.pdf";
                                        } else if(file_exists(DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.PDF")) {
                                            $fileName = DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.PDF";
                                        }
                                        if(file_exists($fileName)) {
                                            $query = mysqli_query($con, "SELECT uploaded_date 
                                                                                          FROM document_upload_history 
                                                                                          WHERE document_code='$document->id'
                                                                                           AND applicantno='$crew->CREWIPN'
                                                                                           ORDER BY uploaded_date DESC");
                                            $docHistory = mysqli_fetch_array($query);
                                            echo $docHistory['uploaded_date'];
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if(file_exists($fileName)) { ?>
                                            <a class="btn btn-mini btn-warning" target="_blank" href="<?= base_url("welcome/viewDocument/$crew->CREWIPN/$crew->manning_folder_name/$crew->CREWIPN$document->country_code$document->document_code_mk"); ?>">
                                                <i class="icon-file"></i> View
                                            </a> &nbsp;
                                            <?php if($this->session->userdata('type') == "Staff") { ?>
                                                <a href="<?= base_url("crews/deleteDoc/$crew->CREWIPN/$crew->manning_folder_name/$crew->CREWIPN$document->country_code$document->document_code_mk/$crew->ID"); ?>"
                                                   class="btn btn-danger btn-mini" style="color:#000;"
                                                   onclick="return confirm('Are you sure you want to delete?');">
                                                    <i class="icon-remove"></i> Delete
                                                </a>
                                            <?php } else { ?>
                                                <a href="<?= base_url("welcome/download_document/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk/$crew->manning_folder_name"); ?>" class="btn btn-success btn-mini">
                                                    <i class="icon-download icon-white"></i> Download
                                                </a>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <?php if($this->session->userdata('type') == "Staff") { ?>
                                                <a href="<?= base_url("crews/uploadDoc/$crew->manning_folder_name/$document->id/$crew->ID");  ?>" class="btn btn-info btn-mini">
                                                    <i class="icon-upload icon-white"></i> Upload
                                                </a>
                                            <?php } else { ?>
                                                <span style="color: #ff0000">NO DOCUMENT</span>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if(file_exists($fileName)) { ?>
                                            <input type="checkbox" name="cbxFile" value="<?= $fileName ?>" onclick="addFile(this);">
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>

                    <table class="table table-striped table-condensed table-hover">
                        <thead>
                        <tr style="font-weight:bold;font-size:14px;" class="text-error">
                            <td width="500" style="text-align: center;" colspan="4"> <h3>CERTIFICATES</h3> </td>
                        </tr>
                        <tr style="font-weight:bold;font-size:14px;" class="text-error">
                            <td width="500">License Name</td>
                            <td width="200">License Code</td>
                            <td width="200">Last Update</td>
                            <td width="250" style="text-align: center;">Action</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($documents as $document) { ?>
                            <?php if($document->document_type == 'C'  && $document->country_code == $code){ ?>
                                <tr  <?php
                                if(($document->document_code == 'ER') && (($rank != 'E11') && ($rank != 'E21') && ($rank != 'E22') && ($rank != 'E23') && ($rank != 'E32') )) {
                                    echo "style=\"display:none;font-size:11px;font-weight: bold;\"";
                                } else if((($document->document_code == 'SOCCERT') && !in_array($rank, array('D11', 'E11', 'D21', 'E21')))) {
                                    echo "style=\"display:none;font-size:11px;font-weight: bold;\"";
                                } else {
                                    echo "style=\"font-size:11px;font-weight: bold;\"";
                                    $count = $count + 1;
                                }?>>
                                    <td><?= $document->document_name ; ?></td>
                                    <td><?= $document->document_code_mk ; ?></td>
                                    <td>
                                        <?php
                                        $fileName = "";
                                        if(file_exists(DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.pdf")) {
                                            $fileName = DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.pdf";
                                        } else if(file_exists(DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.PDF")) {
                                            $fileName = DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk.PDF";
                                        }
                                        if(file_exists($fileName)) {
                                            $query = mysqli_query($con, "SELECT uploaded_date 
                                                                                          FROM document_upload_history 
                                                                                          WHERE document_code='$document->id'
                                                                                           AND applicantno='$crew->CREWIPN'
                                                                                           ORDER BY uploaded_date DESC");
                                            $docHistory = mysqli_fetch_array($query);
                                            echo $docHistory['uploaded_date'];
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if(file_exists($fileName)) { ?>
                                            <a class="btn btn-mini btn-warning" target="_blank" href="<?= base_url("welcome/viewDocument/$crew->CREWIPN/$crew->manning_folder_name/$crew->CREWIPN$document->country_code$document->document_code_mk"); ?>">
                                                <i class="icon-file"></i> View
                                            </a> &nbsp;
                                            <?php if($this->session->userdata('type') == "Staff") { ?>
                                                <a href="<?= base_url("crews/deleteDoc/$crew->CREWIPN/$crew->manning_folder_name/$crew->CREWIPN$document->country_code$document->document_code_mk/$crew->ID"); ?>"
                                                   class="btn btn-danger btn-mini" style="color:#000;"
                                                   onclick="return confirm('Are you sure you want to delete?');">
                                                    <i class="icon-remove"></i> Delete
                                                </a>
                                            <?php } else { ?>
                                                <a href="<?= base_url("welcome/download_document/$crew->CREWIPN/$crew->CREWIPN$document->country_code$document->document_code_mk/$crew->manning_folder_name"); ?>" class="btn btn-success btn-mini">
                                                    <i class="icon-download icon-white"></i> Download
                                                </a>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <?php if($this->session->userdata('type') == "Staff") { ?>
                                                <a href="<?= base_url("crews/uploadDoc/$crew->manning_folder_name/$document->id/$crew->ID");  ?>" class="btn btn-info btn-mini">
                                                    <i class="icon-upload icon-white"></i> Upload
                                                </a>
                                            <?php } else { ?>
                                                <span style="color: #ff0000">NO DOCUMENT</span>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if(file_exists($fileName)) { ?>
                                            <input type="checkbox" name="cbxFile" value="<?= $fileName ?>" onclick="addFile(this);">
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>

                </div>
            <?php if($count < 1) { ?>
                <script type="text/javascript">
                    var tab = document.getElementById("<?= $flag['code'] ?>");
                    tab.setAttribute('style', "display: none;");
                </script>
            <?php }?>
            <?php } ?>

            <div class="tab-pane fade" id="cer">
                <table class="table table-striped table-condensed table-hover">
                    <thead>
                    <tr style="font-weight:bold;font-size:11px;">
                        <td colspan="7">
                            <div class="pull-left">
                                <h5>CREW EVALUATION REPORT</h5>
                            </div>
                            <div class="pull-right">
                                <a href="<?= base_url("crews/addcer/$crew->ID"); ?>" class="btn btn-success btn-mini">
                                    <i class="icon-plus-sign icon-white"></i> Add CER
                                </a>
                            </div> <br /> <br />
                        </td>
                    </tr>
                    <tr style="font-weight:bold;font-size:14px;" class="text-error">
                        <td width="200">Vessel Name</td>
                        <td width="750" colspan="7" style="text-align: center;">Action</td>
                    </tr>
                    </thead>

                    <tbody>
                    <?php $x = 1; foreach ($crew_cer_list as $row_crew_cer_list) { ?>
                        <?php if($x <= '4') { ?>
                            <tr style="font-weight:bold;font-size:11px;">
                            <td>
                                <?php
                                $vesselid = $row_crew_cer_list->id;
                                echo $vesselname = $row_crew_cer_list->vessel_name;
                                ?>
                            </td>
                            <?php for($counter=1; $counter<=3; $counter++) { ?>
                                <?php
                                if($counter == 3 && $crew->RANKTYPECODE == 'E') $i = $counter + 1;
                                else $i = $counter;
                                ?>
                                <td style="text-align: center;" width="115">
                                    <?php
                                    $file = CERDOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN"."_V$row_crew_cer_list->vessel_id"."_N$i"."_C$row_crew_cer_list->cer_initial_id.pdf";
                                    if(file_exists($file)) {
                                        $count = $this->db->select("count(*) as count")->from("cer_grade")->where("cer_id", $row_crew_cer_list->id)->where("cer_number", $i)->get()->row()->count;
                                        if($count) {
                                            $crewGrade = $this->db->select("grade")->from("cer_grade")->where("cer_id", $row_crew_cer_list->id)->where("cer_number", $i)->get()->row()->grade;
                                        } else {
                                            $crewGrade = getGrade($file);
                                        }
                                        $promptGrade = "";
                                        if($crewGrade == "") {
                                            $promptGrade = "No grades detected!";
                                            $color = "warning";
                                        } elseif($crewGrade<70) {
                                            $promptGrade = "Grade: $crewGrade";
                                            $color = "danger";
                                        } else {
                                            $promptGrade = "Grade: $crewGrade";
                                            $color = "success";
                                        }
                                        $crewGrade = "";
                                    }
                                    ?>
                                    <?php  if($this->session->userdata('type') == "Staff") { ?>
                                        <?php if (file_exists(CERDOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN"."_V$row_crew_cer_list->vessel_id"."_N$i"."_C$row_crew_cer_list->cer_initial_id.pdf")) { ?>
                                            <a class="btn btn-mini btn-<?= $color ?>" target="_blank" href="<?= base_url("welcome/viewDocument/$crew->CREWIPN/$crew->manning_folder_name/$crew->CREWIPN"."_V$row_crew_cer_list->vessel_id"."_N$i"."_C$row_crew_cer_list->cer_initial_id/cer"); ?>"
                                               title="<?= $promptGrade ?>"
                                            >
                                                <i class="icon-file"></i> CER #<?php echo $i; ?>
                                            </a>

                                            <div class="dropdown btn btn-primary btn-mini">
                                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                    <span class="caret"></span>
                                                </a>
                                                <ul class="dropdown-menu" style="cursor: pointer;">
                                                    <li class="text-center">
                                                        <a type="button" data-toggle="modal" data-target="#cerGradeModal" title="Input CER <?= " #$i Grade for $vesselname" ?>" rel="tooltip"
                                                           onclick="updateGradeModal(<?= "'$row_crew_cer_list->id', '$vesselname', '$i'" ?>);"
                                                        >
                                                            <strong> Enter Grade </strong>
                                                        </a>
                                                    </li>
                                                    <li class="text-left">
                                                        <a rel="tooltip" title="Delete Cer #<?= $i; ?>" class="btn btn-danger btn-mini" onclick="return confirm('Are you sure you want to delete?');" href="<?= base_url("crews/deleteCrewCer/$i/$row_crew_cer_list->id"); ?>">
                                                            <i class="icon-remove icon-white"></i> <b style="font-weight: bolder;">Delete</b>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <input type="checkbox" name="cbxFile" value="<?= CERDOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN"."_V$row_crew_cer_list->vessel_id"."_N$i"."_C$row_crew_cer_list->cer_initial_id.pdf" ?>" onclick="addFile(this);">

                                        <?php } else { ?>
                                            <a class="btn btn-mini btn-info" href="<?= base_url("crews/uploadCrewCer/$i/$row_crew_cer_list->id"); ?>">
                                                <i class="icon-upload icon-white"> </i> Upload #<?php echo $i; ?>
                                            </a>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <?php if (file_exists(CERDOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN"."_V$row_crew_cer_list->vessel_id"."_N$i"."_C$row_crew_cer_list->cer_initial_id.pdf")) { ?>
                                            <a class="btn btn-mini btn-<?= $color ?>" target="_blank" href="<?= base_url("welcome/viewDocument/$crew->CREWIPN/$crew->manning_folder_name/$crew->CREWIPN"."_V$row_crew_cer_list->vessel_id"."_N$i"."_C$row_crew_cer_list->cer_initial_id/cer"); ?>"
                                               title="<?= $promptGrade ?>"
                                            >
                                                <i class="icon-file"></i> CER #<?php echo $i; ?>
                                            </a>
                                            <input type="checkbox" name="cbxFile" value="<?= CERDOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$crew->CREWIPN"."_V$row_crew_cer_list->vessel_id"."_N$i"."_C$row_crew_cer_list->cer_initial_id.pdf" ?>" onclick="addFile(this);">
                                        <?php } else echo 'None'; ?>
                                    <?php } ?>
                                </td>
                            <?php } ?>

                            <td style="text-align: center;">
                            <?php  if($this->session->userdata('type') == "Staff") { ?>
                                <a onclick="return confirm('Are you sure you want to delete?');" class="btn btn-mini btn-danger" href="<?php echo base_url("crews/cerDelete/$row_crew_cer_list->id/$crew->ID"); ?>">
                                    <i class="icon-remove icon-white"></i> Remove
                                </a>
                            <?php } else { ?>
                                <?php $commentCount = count($vesselCrewComments[$vesselid]);if($commentCount > 0) {?>
                                    <div class="dropdown">
                                        <a class="dropdown-toggle btn btn-primary btn-mini" data-toggle="dropdown" href="#"
                                           title=" <?php echo "$commentCount CREW COMMENTS FOR $vesselname"; ?>" data-placement="bottom">
                                            Comments
                                            <span style="color: #FF0000"> (<?php echo $commentCount; ?>) </span>
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" style="cursor: pointer;">
                                            <li class="text-left">
                                                <a type="button" data-toggle="modal" data-target="#<?php echo $vesselid; ?>"
                                                   title="View Crew Comments for <?php echo $vesselname; ?>"
                                                   onclick="arrangeModal('<?php echo $vesselid; ?>');">
                                                    <strong> View Comments </strong>
                                                </a>
                                            </li>
                                            <li class="text-left">
                                                <a type="button" data-toggle="modal" data-target="#addcomment"
                                                   title="Add New Comment for <?php echo $vesselname; ?>"
                                                   onclick="showAddComment(
                                                           '<?php echo $vesselid; ?>',
                                                           '<?php echo $vesselname; ?>',
                                                           '<?php echo $row_crew_cer_list->manning_name; ?>'
                                                           );">
                                                    <strong> Add New Comment </strong>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                <?php } else { ?>
                                    <a type="button" data-toggle="modal" data-target="#addcomment"
                                       class="dropdown-toggle btn btn-success btn-mini"
                                       title="Add New Comment for <?php echo $vesselname; ?>"
                                       onclick="showAddComment(
                                               '<?php echo $vesselid; ?>',
                                               '<?php echo $vesselname; ?>',
                                               '<?php echo $row_crew_cer_list->manning_name; ?>'
                                               );">
                                        <strong> Add Comment </strong>
                                    </a>
                                <?php } ?>
                                </td>

                                </tr>
                            <?php } ?>
                        <?php } ?>
                        <?php  if($this->session->userdata('type') != "Staff") $counter++;  ?>
                    <?php } ?>
                    </tbody>
                    <thead>
                    <tr style="font-weight: bold;font-size:12px;" class="text-info">
                        <td width="650" colspan="5">
                            <br/>Legend for CER<br/>
                            #1) 4 MONTHS AFTER EMBARKATION<br/>
                            #2) 1 MONTH BEFORE DISEMBARKING<br/>
                            #3) SIGN OFF MASTER / CHIEF ENGINEER<br/>
                            #4) CREW COMMENT<br/>
                        </td>
                    </tr>
                    </thead>

                </table>
            </div>


        </div>
    </div>

</div>
<?php  if($this->session->userdata('type') != "Staff") { ?>
    <script type="text/javascript">
        var previousComment = "";
        var previousCommentator = 0;

        function editcomment(id, vesselid) {
            var editbutton = document.getElementById("edit" + id);
            var savebutton = document.getElementById("save" + id);
            var commenttext= document.getElementById("comment" + id);
            var commentator= document.getElementById("commentator" + id);

            document.getElementsByName("commentext" + vesselid).forEach(textarea => {
                textarea.readOnly = textarea !== commenttext;
            });

            document.getElementsByName("commentedit" + vesselid).forEach(edit => {
                edit.disabled = edit.id !== editbutton.id;
            })

            document.getElementsByName("commentsave" + vesselid).forEach(save => {
                save.disabled = save.id !== savebutton.id;
            })

            document.getElementsByName("commentator" + vesselid).forEach(dropdown => {
                dropdown.disabled = dropdown !== commentator;
            });

            if(editbutton.innerText !== "Edit") {
                savebutton.disabled = true;
                commentator.disabled = true;
                commenttext.readOnly = true;
                editbutton.setAttribute("class", "btn btn-secondary");
                editbutton.innerText = "Edit";
                commenttext.value = previousComment;
                commentator.value = previousCommentator;
                document.getElementsByName("commentedit" + vesselid).forEach(edit => {
                    edit.disabled = false;
                });
                document.getElementById('file' + id).style.display = 'none';
                document.getElementById('show' + id).style.display = 'block';
            } else {
                savebutton.disabled = false;
                commentator.disabled = false;
                commenttext.readOnly = false;
                editbutton.innerText = "Cancel";
                editbutton.setAttribute("class", "btn btn-warning");
                previousComment = commenttext.value;
                previousCommentator = commentator.options[commentator.selectedIndex].value;
                document.getElementById('file' + id).style.display = 'block';
                document.getElementById('show' + id).style.display = 'none';

            }
        }

        function updateComment(ccid) {
            if(confirm("Are you sure you want to update this comment?")) {
                var textarea = document.getElementById('comment' + ccid);
                var comentator = document.getElementById('commentator' + ccid);
                var form = document.updateCrewComment;
                var inputFile = document.getElementById("file" + ccid);
                form.appendChild(inputFile);
                form.crewcommentid.value = ccid;
                form.comentator.value = comentator.value;
                form.crewcommenttext.value = textarea.value;
                form.submit();
            }
        }

        function arrangeModal(id) {
            document.getElementById(id).style = "display: none;";
        }

        function showAddComment(vesselid, vesselname, manningname) {
            document.getElementById('vesselId').value = vesselid;
            document.getElementById('vesseltitle').innerHTML = vesselname;
            document.getElementById('manningtitle').innerHTML = manningname;
        }

        function saveComment() {
            let saveButton = document.getElementById("saveButton");
            saveButton.innerHTML = "Saving....";
            saveButton.disabled = true;
        }

    </script>

    <?php $timetoday = new DateTime('now'); ?>

    <?php foreach ($crew_cer_list as $cer) {
        $vesselname = $cer->vessel_name;
        $vesselid = $cer->id; ?>
        <div class="modal fade bd-example-modal-lg" id="<?php echo $vesselid;?>" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
             style="display: none;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="well" style="margin-bottom: 0px; padding-top: 10px; background: none; border-color: white;">
                            <span class="pull-left"><strong class="modal-title"> <?php echo $vesselname; ?></strong></span>
                            <span class="pull-right">
                                <u><?php echo "$crew->FNAME, $crew->GNAME ".substr($crew->GNAME, 0, 1)."."; ?></u> - <?php echo $crew->ALIAS2;?> <br />
                                <i>
                                    <small style="font-size: 10px;">
                                        <?php echo $cer->manning_name; ?>
                                    </small>
                                </i>
                            </span>
                        </div>

                    </div>

                    <div class="modal-body" style="height: auto;">
                        <div class="container-fluid">
                            <?php foreach ($vesselCrewComments[$vesselid] as $crewcomment) {
                                $commented_at = new DateTime($crewcomment->commented_at);
                                $file = DOCFOLDER.$crew->manning_folder_name."/$crew->CREWIPN/comments/$crewcomment->id.pdf";
                                ?>

                                <span>
                                        <strong><?php echo $crewcomment->commented_by; ?></strong> <br/>
                                        <small><i style="font-size: 10px;"><?php echo $crewcomment->commented_at; ?></i></small>
                                    </span>
                                <br />
                                <textarea class="form-control" style="margin: 0px 0px 10px; width: 484px; height: 100px;"
                                          title="Type you comment here for this crew. . . . ." readonly
                                          name="commentext<?php echo $vesselid; ?>"
                                          id="<?php echo "comment$crewcomment->id" ;?>"><?php echo $crewcomment->comment ;?></textarea> <br />

                                <b>Comment by:</b>&nbsp;&nbsp;&nbsp;
                                <select class="form-control" style="width: 200px;" name="commentator<?php echo $vesselid; ?>" id="<?php echo "commentator$crewcomment->id" ;?>" disabled>
                                    <option selected> -------------- </option>
                                    <?php foreach ($commentators as $commentator) { ?>
                                        <option value="<?= $commentator->id ?>" <?= ($crewcomment->commentator_id == $commentator->id)?"selected":"" ?>><?= $commentator->name ?></option>
                                    <?php } ?>
                                </select>

                                <?php if(file_exists($file)) { ?>
                                    <a type="button" target="_blank"
                                       class="btn btn-warning pull-right"
                                       id="show<?php echo $crewcomment->id; ?>"
                                       href="<?= base_url("welcome/viewDocument/$crew->CREWIPN/$crew->manning_folder_name/$crewcomment->id/cc"); ?>" >
                                        View Attachment
                                    </a>
                                <?php } ?>
                                <br />

                                <?php if($crewcomment->principal_id == $this->session->userdata('principal_id') && $commented_at->diff($timetoday)->days < 1) { ?>
                                    <button class="btn btn-secondary"
                                            name="commentedit<?php echo $vesselid; ?>"
                                            id="<?php echo "edit$crewcomment->id";?>"
                                            onclick="editcomment('<?php echo $crewcomment->id;?>', '<?php echo $vesselid; ?>');"> Edit
                                    </button>

                                    <button class="btn btn-primary"
                                            name="commentsave<?php echo $vesselid; ?>" disabled
                                            id="<?php echo "save$crewcomment->id" ;?>"
                                            onclick="updateComment('<?php echo $crewcomment->id; ?>'); this.disabled=true"> Save
                                    </button>
                                    <input type="file" id="file<?php echo $crewcomment->id; ?>" name="userfile" size="20"  accept="application/pdf" class="pull-right" style="display: none;">
                                <?php } ?>

                                <br /> <hr class="hr" style="border: 1px solid gray"/>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-mini" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="modal fade bd-example-modal-lg" id="addcomment" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
         style="display: none;">

        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <div class="well" style="margin-bottom: 0px; padding-top: 10px; background: none; border-color: white;">
                        <span class="pull-left"><strong class="modal-title" id="vesseltitle"></strong></span>
                        <span class="pull-right">
                            <u><?= "$crew->FNAME, $crew->GNAME ".substr($crew->GNAME, 0, 1)."."; ?></u> - <?= $crew->ALIAS2;?> <br />
                            <i>
                                <small style="font-size: 10px;" id="manningtitle">
                                </small>
                            </i>
                        </span>
                    </div>
                </div>
                <?php echo form_open_multipart("crews/saveCrewComment/$crew->MANNING_ID", array('onSubmit' => 'saveComment();')); ?>
                <div>
                    <input type="hidden" name="vesselId" id="vesselId" required>
                    <input type="hidden" name="crewid" id="crewid" value="<?php echo $crew->ID; ?>" required>
                    <div class="container-fluid">
                        <strong>Add New Comment</strong> <br />
                        <textarea id="comment" name="comment" class="form-control" style="margin: 0px 0px 10px; width: 484px; height: 100px;" required
                                  placeholder="Type you comment here for this crew. . . . ."
                                  title="Type your new comment here for this crew. . . . ."></textarea> <br />
                        <b>Comment by:</b>&nbsp;&nbsp;&nbsp;
                        <select class="form-control" style="width: 200px;" name="commentator">
                            <?php foreach ($commentators as $commentator) { ?>
                                <option value="<?= $commentator->id ?>"><?= $commentator->name ?></option>
                            <?php } ?>
                        </select> <br />

                        <b>Upload File:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="file" name="userfile" size="20"  accept="application/pdf" class="form-control" style="margin-bottom: 10px;" />
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-mini" id="saveButton" onclick="return confirm('Are you sure you want to save this comment?');">Save Comment</button>
                    <button type="button" class="btn btn-danger btn-mini" data-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>

    </div>

    <?php echo form_open_multipart("crews/updateCrewComment/$crew->ID", array('name' => 'updateCrewComment'));?>
    <input type="hidden" name="crewcommentid" id="crewcommentid" required>
    <input type="hidden" name="crewcommenttext" id="crewcommenttext" required>
    <input type="hidden" name="comentator" id="comentator" required>
    </form>
<?php } else { ?>

    <script type="text/javascript">

        function updateGradeModal(cerId, vesselName, cerNo) {
            document.getElementById("cerGradeId").value = cerId;
            document.getElementById("cerNoId").value = cerNo;
            document.getElementById("cerGradeHeader").innerHTML = cerNo + " GRADE FOR " + vesselName + " VESSEL";
        }

    </script>

    <div class="modal fade bd-example-modal-lg" id="cerGradeModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
         style="display: none;">

        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <b>CER # <span id="cerGradeHeader"></span></b>
                </div>

                <?php echo form_open("crews/updateCerGrade/", array('name' => 'updateCerGrade'));?>
                <div class="modal-body">
                    <input type="hidden" name="cerId" id="cerGradeId" required>
                    <input type="hidden" name="cerNo" id="cerNoId" required>
                    <input type="hidden" name="crewId" id="crewId" value="<?= $crew->ID ?>" required>
                    <b>Grade:</b> <input type="number" name="grade" min="50.00" max="100.00" step="0.01" placeholder="Enter CER Grade" required>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-mini" id="saveButton" onclick="return confirm('Are you sure you want to save this comment?');">Save</button>
                    <button type="button" class="btn btn-danger btn-mini" data-dismiss="modal">Close</button>
                </div>
                </form>

            </div>
        </div>
    </div>

<?php } ?>
<script type="text/javascript">

    showCheckBoxes(false);
    let files = [];
    function addFile(checkBox) {
        if(checkBox.checked) {
            files.push(checkBox.value);
        } else {
            files = files.filter(function (value) {
                return value !== checkBox.value;
            });
        }
    }
    function printAll(button) {
        if(button.innerHTML === "Print/View Multiple") {
            button.innerHTML = "Submit";
            document.getElementById("cancelPrint").style.display = "inline";
            showCheckBoxes(true);
        } else {

            if(files.length) {
                if(confirm("Are you sure you want to print selected files?")) {
                    jQuery.post('<?= base_url("crews/printFiles"); ?>', {myKey: files, <?= $this->security->get_csrf_token_name(); ?>: "<?= $this->security->get_csrf_hash(); ?>"}, function(data)
                    {
                        window.open("<?= base_url("crews/showPrintFiles"); ?>");
                        location.reload();
                    }).fail(function()
                    {
                        alert("Error occurred, refreshing the page.");
                        location.reload();
                    });
                }
            } else {
                alert('No file/s selected!!');
            }
            cancelPrintAll(document.getElementById("cancelPrint"));
        }
    }

    function cancelPrintAll(button) {
        let printAllButton = document.getElementById("printAll");
        printAllButton.innerHTML = "Print/View Multiple";
        button.style.display = "none";
        showCheckBoxes(false);
    }

    function showCheckBoxes(show) {
        let checkBoxes = document.getElementsByName("cbxFile");
        let i;
        for(i = 0; i<checkBoxes.length; i++) {
            checkBoxes[i].style.display = show? "inline": "none";
            checkBoxes[i].checked = false;
        }
    }
</script>
