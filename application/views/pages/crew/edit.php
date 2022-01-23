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
                <?= $this->session->flashdata('errors'); ?>
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
                <legend>Update Crew Information</legend>
            </fieldset>
        </form> <br />

            <?php echo form_open("crews/updateCrew/$crew->ID", array("class"=>"form-horizontal")); ?>
            <div class="control-group">
                <label class="control-label">Status</label>
                <div class="controls">
                    <select name="status" class="span3" required="required"  rel="tooltip" title="Select Status" placeholder="Select Status">
                        <option value="1" <?= ($crew->STATUS) ?"selected" : "" ; ?> >ACTIVE</option>
                        <option value="0" <?= ($crew->STATUS) ?"" : "selected" ; ?> >INACTIVE</option>
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="inputEmail">Rank</label>
                <div class="controls">
                    <select name="rank" class="span3"  rel="tooltip" title="Select Rank" placeholder="Select Rank">
                        <option value="">---SELECT RANK---</option>
                        <?php foreach ($ranks as $rank) { ?>
                            <option value="<?= $rank->rank_code ?>" <?= ($rank->rank_code == $crew->RANK) ?"selected" :"" ; ?>><?= $rank->rank ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Last Name</label>
                <div class="controls">
                    <input type="hidden" name="crewipn"  value="<?= $crew->CREWIPN ?>">
                    <input type="text" name="last_name" placeholder="Last Name" rel="tooltip" title="Modify Last Name" value="<?= $crew->FNAME; ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">First Name</label>
                <div class="controls">
                    <input type="text" name="first_name" placeholder="First Name" rel="tooltip" title="Modify First Name" value="<?= $crew->GNAME; ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Middle Name</label>
                <div class="controls">
                    <input type="text" name="middle_name" placeholder="Middle Name" rel="tooltip" title="Modify Middle Name" value="<?= $crew->MNAME; ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Other Name</label>
                <div class="controls">
                    <input type="text" name="other_name" placeholder="Other Name" rel="tooltip" title="Modify Other Name" value="<?= $crew->OTHER_FULLNAME; ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Birthdate</label>
                <div class="controls">
                    <input type="text" name="birthdate" placeholder="Birthdate" rel="tooltip" title="Select Rank" value="<?= $crew->BIRTHDATE; ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Address</label>
                <div class="controls">
                    <input type="text" name="address" placeholder="Address" rel="tooltip" title="Modify Address" value="<?= $crew->ADDRESS; ?>">
                </div>
            </div>


            <div class="control-group">
                <label class="control-label">Contact No. 1</label>
                <div class="controls">
                    <input type="text" name="contactno1" placeholder="Contact # 1" rel="tooltip" title="Modify Contact # 1" value="<?= $crew->CONTACT_NO1; ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Contact No. 2</label>
                <div class="controls">
                    <input type="text" name="contactno2" placeholder="Contact No. 2" rel="tooltip" title="Modify Contact # 2" value="<?= $crew->CONTACT_NO2; ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">POSTAL CODE</label>
                <div class="controls">
                    <input type="text" name="postal_code" placeholder="POSTAL CODE" rel="tooltip" title="Modify POSTAL CODE" value="<?= $crew->POSTAL_CODE; ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">DATE HIRED</label>
                <div class="controls">
                    <input type="text" name="date_hired" placeholder="DATE HIRED" rel="tooltip" title="Modify DATE HIRED" value="<?= $crew->DATE_HIRED; ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">SCHOOL DATE GRADUATED</label>
                <div class="controls">
                    <input type="text" name="school_date_graduated" placeholder="SCHOOL DATE GRADUATED" rel="tooltip" title="Modify SCHOOL DATE GRADUATED" value="<?= $crew->SCHOOL_DATE_GRADUATED; ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Email Address</label>
                <div class="controls">
                    <input type="text" name="email_address" placeholder="Email Address" rel="tooltip" title="Modify Email Address" value="<?= $crew->EMAIL_ADDRESS; ?>">
                </div> <hr />
            </div>

            <div class="control-group">
                <div class="controls">
                    <button class="btn btn-primary" name="editcrew">Save Changes</button>
                    <input type="reset" class="btn" value="Reset">
                </div>
            </div>

        </form>

    </div>

</div>
