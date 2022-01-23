<br />

<?php if($this->session->flashdata('errors')) { ?>
    <div class="alert alert-error" style="" >
        <button type="button" class="close" data-dismiss="alert">×</button>
        <b><?= $this->session->flashdata('errors'); ?></b>
    </div>
<?php } else if($this->session->flashdata('success')) { ?>
    <div class="alert alert-success" style="" >
        <button type="button" class="close" data-dismiss="alert">×</button>
        <b><?= $this->session->flashdata('success'); ?></b>
    </div>
<?php } ?>

<div class="well well-small">
    <b>Welcome! <?= $this->session->userdata('type'); ?></b>
    <?php if($this->session->userdata('type') === 'Principal') { ?>
        <span class="pull-right">
            <a type="button" href="<?= base_url("principals"); ?>" class="btn btn-mini">
                <i class="icon-arrow-left"></i> Back
            </a>
        </span>
    <?php } ?>
</div>

<center>
    <span><b style="font-size: 18px;"><?= $manning->manning_name ?></b></span>
    <?php if($this->session->userdata('type') === 'Principal') { ?>
        <a href="<?= base_url("mannings/addVessel/$manning->manning_id");  ?>" class="btn btn-primary" name="addvessel" rel="tooltip" title="Add Vessel" style="margin-bottom: 10px;">
            <i class=" icon-user- icon-flag icon-white"></i> Add Vessel
        </a>
    <?php } ?>
    <?php if($this->session->userdata('type') === 'Staff') { ?>
        <div class="pull-right" style="margin-left: 10px;">
            <a href="<?= base_url("mannings/uploadedDocs"); ?>" class="btn btn-warning btn-mini" name="uploadeddocs" rel="tooltip" title="No. of uploaded documents for today!">
                <i class=" icon-file icon-white"> </i> Docs Uploaded <?= $uploadedDocs->count; ?>
            </a>
        </div>
    <?php } ?>
    <div class="pull-right">
        <a href="<?= base_url("mannings/companyLicense/$manning->manning_id");?>" target="_blank" style="color:#000"><i class="icon-file"></i> Company File</a>
    </div>
</center>

<div class="clearfix">
    <?php echo form_open_multipart("mannings/index/$manning->manning_id", array('name' => 'form1', 'class' => 'form-inline'));?>
        <fieldset>
            <legend>Search Crew</legend>
            <center>
                <label><b>Search By :</b></label>
                <select class="span2" rel="tooltip" title="Select Category" name="cat_keyword" onchange="changeInput(this.value);">
                    <option value="ALL">All</option>
                    <option value="FNAME">Last Name</option>
                    <option value="GNAME">First Name</option>
                    <option value="MNAME">Middle Name</option>
                    <option value="CREWIPN">Crewipn</option>
                </select>
                <input type="text" name="keyword" rel="tooltip" title="Enter : Last Name | First Name | Middle Name | Crewipn" placeholder="Enter : Last Name | First Name | Middle Name | Crewipn" class="span5" id="keyword">
                <button type="submit" class="btn" name="submit" rel="tooltip" title="Search"><i class="icon-search"></i> Search</button>
                <?php if($this->session->userdata('type') === 'Staff') { ?>
                    <a href="<?= base_url("crews/addCrew"); ?>" class="btn btn-info" name="addcrew" rel="tooltip" title="Add Crew">
                        <i class=" icon-user- icon-flag icon-white"></i> Add Crew
                    </a>
                <?php } ?>
            </center>
        </fieldset>
    </form>
</div> <br />

<?php if($this->input->server('REQUEST_METHOD') == 'POST') { ?>
    <table class="table table-striped table-condensed table-hover ">
        <thead>
            <tr style="font-weight:bold;font-size:14px;">
                <td width="100">Crewipn</td>
                <td width="100">Rank</td>
                <td width="150">Last Name</td>
                <td width="150">First Name</td>
                <td width="150">Middle Initial</td>
                <td width="100" style="text-align:center;">Documents</td>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($crews)){ ?>
                <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Result Not Found!</strong>
                </div>
            <?php } else { ?>
                <?php foreach ($crews as $crew) { ?>
                    <tr style="font-size:11px;">
                        <td><?php echo $crew->CREWIPN ; ?></td>
                        <td><?php echo $crew->rank_alias ; ?></td>
                        <td><?php echo $crew->FNAME ; ?></td>
                        <td><?php echo $crew->GNAME ; ?></td>
                        <td><?php echo $crew->MNAME ; ?></td>
                        <td style="text-align:center;">
                            <?php if($this->session->userdata('type') != 'Principal') { ?>
                                <a class="btn btn-mini btn-warning" href="<?php echo base_url("crews/show/$crew->ID"); ?>" style="color:#000"><i class="icon-file"></i> View</a>

                            <?php } else { ?>
                                <?php if(file_exists(DOCFOLDER.$crew->manning_folder_name."/".$crew->CREWIPN."/".$crew->CREWIPN.$foldercode[$crew->manning_nationality].'PP'.".pdf")
                                    || $this->session->userdata('principal_no') == 'PRIN-0030'
                                ) { ?>
                                    <a href="<?php echo base_url("crews/show/$crew->ID"); ?>" style="color:#000"><i class="icon-file"></i> View</a>
                                <?php } ?>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>

    </table>
<?php } ?>

<script type="text/javascript">
    function changeInput(value) {
        let placeHolder = "";
        switch (value) {
            case "ALL":
                placeHolder = "Enter : Last Name | First Name | Middle Name | Crewipn";
                break;
            case "FNAME":
                placeHolder = "Enter : Last Name";
                break;
            case "GNAME":
                placeHolder = "Enter : First Name";
                break;
            case "MNAME":
                placeHolder = "Enter : Middle Name";
                break;
            case "CREWIPN":
                placeHolder = "Enter : Crew IPN";
                break;
        }
        document.getElementById("keyword").placeholder = placeHolder;
        document.getElementById("keyword").title = placeHolder;
    }
</script>


