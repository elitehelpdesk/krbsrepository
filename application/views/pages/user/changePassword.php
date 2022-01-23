<div class="container-fluid">
    <h3>CHANGE PASSWORD</h3>

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


    <div class="form-inline well">
        <?php echo form_open("users/updatePassword"); ?>
        <div class="row">
            <div class="span4" style="padding-top: 6px; text-align: right;">
                <b>OLD PASSWORD:</b>
            </div>
            <div class="span5">
                <input type="password" class="form-control" placeholder="OLD PASSWORD" style="width: 342px;" name="oldPassword" required id="oldPassword">
                <img src="<?php echo base_url('assets/img/icons/passwordShow.png'); ?>" alt="Show Password" title="Reveal Password" style="width: 20px;" onclick="showPassword('oldPassword');" id="oldPasswordImg">
            </div>
        </div> <br />

        <div class="row">
            <div class="span4" style="padding-top: 6px; text-align: right;">
                <b>NEW PASSWORD:</b>
            </div>
            <div class="span5">
                <input type="password" class="form-control" placeholder="YOUR NEW PASSWORD" style="width: 342px;" name="newPassword" required id="newPassword">
                <img src="<?= base_url('assets/img/icons/passwordShow.png'); ?>" alt="Show Password" title="Reveal Password" style="width: 20px;" onclick="showPassword('newPassword');" id="newPasswordImg">
            </div>
        </div> <br />

        <div class="row">
            <div class="span4" style="padding-top: 6px; text-align: right;">
                <b>CONFIRM NEW PASSWORD:</b>
            </div>
            <div class="span5">
                <input type="password" class="form-control" placeholder="CONFIRM YOUR NEW PASSWORD" style="width: 342px;" name="confirmNewPassword" required id="confirmNewPassword">
                <img src="<?= base_url('assets/img/icons/passwordShow.png'); ?>" alt="Show Password" title="Reveal Password" style="width: 20px;" onclick="showPassword('confirmNewPassword');" id="confirmNewPasswordImg">
            </div>
        </div> <br />

        <div class="row">
            <div class="span2"></div>
            <div class="span4">
                <input type="submit" class="btn btn-primary btn-block" onclick="return confirm('Are you sure you want to update?')" value="UPDATE">
            </div>
            <div class="span2">
                <a href="<?= base_url("principals"); ?>" class="btn btn-danger btn-block" onclick="return confirm('Go Back?')">Back</a>
            </div>
            <div class="span5"></div>
        </div>

        </form>
    </div>

    <div class="alert alert-error" style="" >
        <b>SECURITY: Users are required to change password at least once every 6 months.</b>
    </div>

    <script>
        function showPassword(id) {
            let passwordField = document.getElementById(id);
            let imgPasswordField = document.getElementById(id + 'Img');
            if(passwordField.type === 'password') {
                passwordField.type = 'text';
                imgPasswordField.src = '<?= base_url('assets/img/icons/passwordHide.png'); ?>';
                imgPasswordField.title = 'Hide Password';
            } else {
                passwordField.type = 'password';
                imgPasswordField.src = '<?= base_url('assets/img/icons/passwordShow.png'); ?>';
                imgPasswordField.title = 'Reveal Password';
            }
        }
    </script>

</div>
