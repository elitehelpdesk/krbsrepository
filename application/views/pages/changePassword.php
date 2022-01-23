<br />
<div class="well well-small">
    <b>Change Password</b>
</div> <br /> <br />
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
<?php if($change == '0'){ ?>
    <div class="row">
        <div class="span4 offset4">

                <?= form_open(base_url("welcome/changePassword"), ["class" => "form-inline", "role"=>"form"]); ?>
                <div class="form-group">
                    <label class="sr-only" for="exampleInputPassword1">Enter Old Password</label>
                    <input type="password" name="password1" class="form-control" id="exampleInputPassword1" placeholder="Enter Old Password" required="required">
                    <?php if($this->session->userdata("type") == "Principal") { ?>
                        <input type="hidden" name="password2" class="form-control" id="exampleInputPassword1" placeholder="Enter Old Password" value="<?php echo $principal_list->principal_account_password; ?>">
                    <?php } else { ?>
                        <input type="hidden" name="password2" class="form-control" id="exampleInputPassword1" placeholder="Enter Old Password" value="<?php echo $principal_list->staff_account_password; ?>">
                    <?php } ?>
                </div>
                <br />
                <button type="submit" name="confirmpass" class="btn btn-warning">Confirm Password</button>
            </form>
        </div>
    </div>
<?php } else { ?>
    <div class="row">
        <div class="span4 offset4">
            <form class="form-inline" role="form" method="post" action="changepassword">
                <div class="form-group">
                    <label class="sr-only" for="exampleInputPassword2">Enter New Password</label>
                    <input type="password" name="password3" class="form-control" id="exampleInputPassword2" placeholder="Enter New Password" required="required">
                </div>
                <br />
                <button type="submit" name="changepass" class="btn btn-primary">Change Password</button>
            </form>
        </div>
    </div>
<?php } ?>
