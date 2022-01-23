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
        <?php echo form_open("crews/storeCrew", array("class"=>"form-inline well", "style"=>"text-align:left; border:1px solid; border-color:#DDDDDD; background-color:#FBFBFB;")); ?>
            <div style="text-align:center; font-weight:bold;">
                <i class="cus-user"></i> Add Crew
            </div>
            <label style="color:#666666; font-size:15px;">
                <strong>Crewipn</strong>
            </label> <br />
            <input type="text" name="crewipn" style="width:450px;" onkeyup="this.value=this.value.toUpperCase()" rel="tooltip" title="Enter Crewipn" placeholder="Enter Crewipn"/> <br /> <br />

            <label style="color:#666666; font-size:15px;"><strong>Last Name</strong></label> <br />
            <input type="hidden" name="manning_id" value="<?php echo $this->session->userdata('manning_id');?>">
            <input type="text" name="last_name" style="width:450px;" onkeyup="this.value=this.value.toUpperCase()" rel="tooltip" title="Enter Last Name" placeholder="Enter Last Name"/> <br /> <br />

            <label style="color:#666666; font-size:15px;"><strong>First Name</strong></label> <br />
            <input type="text" name="first_name" style="width:450px;" onkeyup="this.value=this.value.toUpperCase()" rel="tooltip" title="Enter First Name" placeholder="Enter First Name"/> <br /> <br />

            <label style="color:#666666; font-size:15px;"><strong>Middle Name</strong></label> <br />
            <input type="text" name="middle_name" style="width:450px;" onkeyup="this.value=this.value.toUpperCase()" rel="tooltip" title="Enter Middle Name" placeholder="Enter Middle Name"/> <br /> <br />

            <label style="color:#666666; font-size:15px;"><strong>Position</strong></label> <br />
            <select name="rank" style="width:465px;" rel="tooltip" title="Select Rank" placeholder="Select Rank">
                <option disabled>Select Rank</option>
                <?php foreach ($ranks as $rank) { ?>
                    <option value="<?php echo $rank->rank_code; ?>"><?php echo $rank->rank; ?></option>
                <?php } ?>
            </select> <br /> <br />
            <label style="color:#666666; font-size:15px;"><strong>Birthdate</strong></label> <br />
            <div class="input-append">
                <input id="datepicker1" class="input-small" name="birthdate" type="date"  placeholder="Birthdate" style="width:410px;">
                <button id="datepicker1btn" class="btn" type="button"><i class="icon-calendar"></i></button>
            </div> <br /> <br /> <hr />
            <div style="text-align:right;">
                <button type="submit" name="add" class="btn btn-primary"/>Add Crew</button>
                <button type="reset" name="reset"  class="btn"/>Clear</button>
            </div>
        </form>
    </div>
    <div class="span5">
        <form  name="form1" class="form-inline well" method="post"  style=" height:540px;text-align:left; border:1px solid; border-color:#DDDDDD; background-color:#FBFBFB;">
            <div style=" font-weight:bold;">Note</div>
            <div id="errorContainer" style=" display:none;padding:10px;position:relative;height:230px;width:280px; color:#F66;">
                <p><b>Please fill up the following field and try again:</b></p> <br>
                <ul />
            </div>
        </form>
    </div>
</div> <br />
