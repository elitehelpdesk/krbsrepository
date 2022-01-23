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
    <div class="span7">
        <?php echo form_open("mannings/storeVessel", array("class"=>"form-inline well", "style"=>"text-align:left; border:1px solid; border-color:#DDDDDD; background-color:#FBFBFB;", "name"=>"form1", "id"=>"theform" )); ?>
            <div style="text-align:center; font-weight:bold;" class="breadcrumb">ADD NEW VESSEL</div> <br />
            <label style="color:#666666; font-size:15px;"><strong>Vessel Name</strong></label> <br />
            <input type="hidden" name="manning_id" value="<?= $manning->manning_id?>">
            <input type="text" name="vessel_name" required="required" style="width:450px;" onkeyup="this.value=this.value.toUpperCase()" rel="tooltip" title="Enter Vessel Name" placeholder="Enter Vessel Name"/>
            <br /> <hr />
            <div style="text-align:right;">
                <button type="submit" name="add" class="btn btn-primary"/>Add Vessel</button>
                <button type="reset" name="reset"  class="btn"/>Clear</button>
            </div>
        </form>
    </div>
    <div class="span5">
        <form  name="form1" class="form-inline well" method="post"  style="text-align:left; border:1px solid; border-color:#DDDDDD; background-color:#FBFBFB;">
            <div style="text-align:center; font-weight:bold;" class="breadcrumb">VESSEL LIST</div>
            <table class="table table-striped table-condensed table-hover">
                <thead>
                <tr>
                    <th>Vessel Name</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($vessels as $vessel) { ?>
                    <tr style="font-size:11px;">
                        <td><?= $vessel->vessel_name ; ?></td>
                        <td><?= ($vessel->vessel_status ?'ACTIVE' :'INACTIVE') ; ?></td>
                        <td class="pull-right">
                            <a class="btn btn-warning btn-mini" href="<?= base_url("mannings/editVessel/$vessel->vessel_id"); ?>" style="color:#fff" rel="tooltip" title="Edit">
                                <i class="icon-edit icon-white"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

            <div id="errorContainer" style=" display:none;padding:10px;position:relative;height:230px;width:280px; color:#F66;">
                <p><b>Please fill up the following field and try again:</b></p> <br />
                <ul />
            </div>
        </form>
    </div>
</div>
