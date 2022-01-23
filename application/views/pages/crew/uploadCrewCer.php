<br />
<div class="well well-small">
    <b><?= $crew->manning_name;  ?></b>
    <span class="pull-right">
        <a href="<?= base_url(($this->session->userdata('type') == "Staff") ?"mannings" : "principals"); ?>"> Search Crew</a>
    </span>
</div>

<div class="row">
    <div class="span3">
        <center>
            <?php if (file_exists(DOCFOLDER."$crew->manning_folder_name/picture/$crew->CREWIPN.jpg")) { ?>
                <img src="<?= base_url("welcome/viewPic/$crew->CREWIPN/$crew->manning_folder_name"); ?>" class="thumbnail" style="width: 150px; height: 150px;"/> <br />
                <a href="<?= base_url("crews/deletePic/$crew->CREWIPN/$crew->ID"); ?>" class="btn btn-mini" style="font-weight:bold;" onclick="return confirm('Are you sure you want to delete?');">
                    <i class="icon-picture"></i> Del Pic
                </a>
            <?php } else { ?>
                <img src="<?php echo base_url("assets/img/others/photo.png"); ?>" class="thumbnail" style="width: 150px; height: 150px;"/> <br />
                <a href="<?= base_url("crews/uploadPic/$crew->CREWIPN/$crew->ID"); ?>" class="btn btn-info btn-mini">
                    <i class="icon-upload icon-white"></i> Upload
                </a>
            <?php } ?>
            <a href="<?= base_url("crews/edit/$crew->ID"); ?>" class="btn btn-mini" style="font-weight:bold;">
                <i class="icon-edit"></i> Edit Info
            </a>
        </center> <br />
        <span style="font-weight:bold;font-size:12px;">
            STATUS :
            <p class="text-error">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= ($crew->STATUS == 1)?'ACTIVE':'INACTIVE'; ?></p>
            CREWIPN :
            <p class="text-error">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $crew->CREWIPN; ?></p>
            RANK :
            <p class="text-error">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?= $crew->rank ?> </p>
            LAST NAME :
            <p class="text-error">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $crew->FNAME; ?></p>
            FIRST NAME :
            <p class="text-error">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $crew->GNAME; ?></p>
            MIDDLE NAME :
            <p class="text-error">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $crew->MNAME; ?></p>
            BIRTHDAY :
            <p class="text-error">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $crew->BIRTHDATE; ?></p>
        </span>
    </div>

    <div class="span9">
        <?php if($this->session->flashdata('errors')) { ?>
            <div class="alert alert-error" style="" >
                <button type="button" class="close" data-dismiss="alert">×</button>
                <? $this->session->flashdata('errors'); ?>
            </div>
        <?php } else if($this->session->flashdata('success')) { ?>
            <div class="alert alert-success" style="" >
                <button type="button" class="close" data-dismiss="alert">×</button>
                <b><?= $this->session->flashdata('success'); ?></b>
            </div>
        <?php } ?>
        <ul class="breadcrumb">
            <li class="text-error"><b>NAME : <?php echo "$crew->FNAME, $crew->GNAME $crew->MNAME"; ?></b>
            </li>
            <li class="pull-right"><button type="button" onclick="goBack()" class="btn btn-mini"><i class="icon-arrow-left"></i> Back</button></li>
        </ul>
        <form class="form-inline" method="post" action="staff">
            <fieldset>
                <legend>Upload CER Documents</legend>
            </fieldset>
        </form>

        <center>
            <?php echo form_open_multipart("crews/storeCrewCer/$number/$cerId", ["onsubmit" => "hideSubmit();"]); ?>
            <input type="file" name="userfile" size="20" accept="application/pdf"/>
            <button type="submit" name="upload" class="btn btn-info" id="upload"><i class="icon-upload icon-white"></i> Upload</button> <br />
            <span class="help-block">Size : <span style="color: #ff0000;">500kb</span> <br />File Format : <span style="color: #ff0000;">PDF format only.</span></span>


            </form>

        </center>
            <?php if($this->session->userdata('staff_no') == 'STA-00044') { ?>
                <b style="margin-left: 150px;">Grade:</b> <input type="number" name="grade" min="50.00" max="100.00" step="0.01" placeholder="Enter CER Grade" required>
            <?php  } ?>

    </div>
    
</div>

<script>
    function hideSubmit() {
        let saveButton = document.getElementById("upload");
        saveButton.innerHTML = "Please Wait....";
        saveButton.disabled = true;
    }
</script>