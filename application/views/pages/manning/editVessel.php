<br />
<div class="well well-small">
    <b><?= $manning->manning_name;  ?></b>
    <span class="pull-right">
        <a href="<?= base_url(($this->session->userdata('type') == "Staff") ?"mannings" : "principals"); ?>"> Search Crew</a>
    </span>
</div>
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
<div class="row">
    <div class="span8 offset2">
        <?php echo form_open_multipart("mannings/updateVessel", array("class"=>"form-inline well", "style"=>"text-align:left; border:1px solid; border-color:#DDDDDD; background-color:#FBFBFB;", "name"=>"form1", "id"=>"theform" )); ?>
            <div style="text-align:center; font-weight:bold;" class="breadcrumb">UPDATE VESSEL</div>
            <br />
            <label style="color:#666666; font-size:15px;"><strong>Vessel Name</strong></label>
            <br />
            <input type="hidden" name="manning_id" value="<?= $this->session->userdata('manning_id');?>">
            <input type="hidden" name="vessel_id" value="<?= $vessel->vessel_id;?>">
            <input type="text" name="vessel_name" value="<?= $vessel->vessel_name; ?>" required="required" style="width:550px;" onkeyup="this.value=this.value.toUpperCase()" rel="tooltip" title="Enter Vessel Name" placeholder="Enter Vessel Name"/>
            <br />
            <hr />
            <div style="text-align:right;">
                <button type="submit" name="edit" class="btn btn-warning"/>Edit Vessel Name</button>
                <button type="reset" name="reset"  class="btn"/>Clear</button>
            </div>
        </form>
    </div>
</div>
