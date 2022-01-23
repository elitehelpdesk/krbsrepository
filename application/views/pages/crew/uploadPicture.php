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
            <img src="<?php echo base_url("assets/img/others/photo.png"); ?>" class="thumbnail" style="width: 150px; height: 150px;"/> <br />
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
            <?php foreach($this->session->flashdata('errors') as $index => $error) { ?>
                <div class="alert alert-error" style="" >
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <?php echo ($index+1).". $error"; ?>
                </div>
            <?php } ?>
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
                <legend>Upload Crew Picture</legend>
            </fieldset>
        </form>

        <center>
            <?php echo form_open_multipart("crews/storePicture/$crew->ID"); ?>
            <input type="file" name="userfile" size="20" accept="image/jpeg"/>
            <button type="submit" name="upload" class="btn btn-info"/><i class="icon-upload icon-white"></i> Upload</button> <br />
            <span class="help-block">Size : <span style="color: #ff0000;">500kb</span> <br />File Format : <span style="color: #ff0000;">jpg|jpeg format only.</span></span>
            </form>
        </center>

    </div>

</div>
