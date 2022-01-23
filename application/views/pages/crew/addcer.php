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
            <?php if(file_exists(DOCFOLDER.$crew->manning_folder_name."/picture/$crew->CREWIPN.jpg")) { ?>
                <img src="<?=  VIEWPIC."$crew->CREWIPN/".$crew->manning_folder_name ;?>" class="thumbnail" style="width: 120px; height: 120px;"/> <br />
                <a class="btn btn-success btn-mini" href="<?=  base_url("welcome/download_picture/$crew->manning_folder_name/$crew->CREWIPN"); ?>">
                    <i class="icon-download icon-white"></i> Download Picture
                </a>
            <?php } else { ?>
                <img src="<?=  base_url("assets/img/others/photo.png"); ?>" class="thumbnail" style="width: 120px; height: 120px;"/>
            <?php } ?>
        </center> <br /> <br />
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
                <b><?= $this->session->flashdata('errors'); ?></b>
            </div>
        <?php } else if($this->session->flashdata('success')) { ?>
            <div class="alert alert-success" style="" >
                <button type="button" class="close" data-dismiss="alert">×</button>
                <b><?= $this->session->flashdata('success'); ?></b>
            </div>
        <?php } ?>
        <ul class="breadcrumb">
            <li class="text-error"><b>NAME : <?= "$crew->FNAME, $crew->GNAME $crew->MNAME"; ?></b>
            </li>
            <li class="pull-right">
                <a class="btn btn-mini" href="<?=  base_url("crews/show/$crew->ID"); ?>" style="color:#000">
                    <i class="icon-arrow-left"></i> Back
                </a>
            </li>
        </ul>
        <div class="form-inline" >
            <fieldset>
                <legend>Add Crew Evaluation Report</legend>
            </fieldset>
        </div>
        <?php if(isset($_POST['addcervessel'])){ ?>
            <div class="alert alert-info" style="height:20px;">Successfully Added!</div>
        <?php } ?>
        <br />

        <?php echo form_open("crews/storecer/$crew->ID", array("class"=>"form-horizontal")); ?>
            <div class="control-group">
                <label class="control-label" for="inputEmail">Vessel</label>
                <div class="controls">
                    <select name="vessel_id" class="span3" required="required"  rel="tooltip" title="Select Vessel" placeholder="Select Vessel">
                        <option value=""></option>
                        <?php foreach ($vessels as $vessel) { ?>
                            <option value="<?= $vessel->vessel_id; ?>"><?php echo $vessel->vessel_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input type="hidden" name="crewipn"  value="<?= $crew->CREWIPN; ?>">
                    <input type="hidden" name="manning_id"  value="<?= $crew->MANNING_ID; ?>">
                    <input type="hidden" name="cercounter"  value="<?= (empty($crew_cer_counter_list->max_cer_id)) ?'1' :$crew_cer_counter_list->max_cer_id + 1 ; ?>">
                </div>
            </div>
            <div class="control-group">
                <hr />
                <div class="control-group">
                    <div class="controls">
                        <button class="btn btn-primary" name="addcervessel">Add CER Vessel</button>
                        <input type="reset" class="btn" value="Clear">
                    </div>
                </div>
            </div>
        </form>

    </div>

</div> <br /><br />