<h3>EDIT PRINCIPAL ACCOUNT</h3>

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
    <?php echo form_open("users/updatePrincipalAccount"); ?>

    <input type="hidden" value="<?= $principal->principal_id ?>" name="principalId">

    <div class="row">
        <div class="span8">
            <?php if($this->session->userdata('principal_no') == 'PRIN-0030') { ?>
                <div class="row">
                    <div class="span2" style="padding-top: 6px; text-align: right;">
                        <b>ACCOUNT NUMBER:</b>
                    </div>
                    <div class="span6">
                        <input type="text" readonly class="form-control" placeholder="ACCOUNT NUMBER" style="width: 450px;" value="<?= $principal->principal_no; ?>">
                    </div>
                </div> <br />
            <?php } ?>

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>LAST NAME:</b>
                </div>
                <div class="span6">
                    <input type="text" class="form-control" placeholder="LAST NAME" style="width: 450px;" value="<?= $principal->last_name; ?>" name="lastName" required onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>FIRST NAME:</b>
                </div>
                <div class="span6">
                    <input type="text" class="form-control" placeholder="FIRST NAME" style="width: 450px;" value="<?= $principal->first_name; ?>" name="firstName" required onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>MIDDLE NAME:</b>
                </div>
                <div class="span6">
                    <input type="text" class="form-control" placeholder="MIDDLE NAME" style="width: 450px;" value="<?= $principal->middle_name; ?>" name="middleName" onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <?php if($this->session->userdata('principal_no') == 'PRIN-0030') { ?>
                <div class="row">
                    <div class="span2" style="padding-top: 6px; text-align: right;">
                        <b>POSITION:</b>
                    </div>
                    <div class="span6">
                        <input type="text" class="form-control" placeholder="POSITION NAME" style="width: 450px;" value="<?= $principal->position; ?>" name="positionName" onkeyup="this.value=this.value.toUpperCase()">
                    </div>
                </div> <br />
            <?php } ?>
        </div>
        <div class="span8">
            <?php if($this->session->userdata('principal_no') == 'PRIN-0030') { ?>
                <div class="row">
                    <div class="span3" style="padding-top: 6px; text-align: right;">
                        <b>MANNING NAME:</b>
                    </div>
                    <div class="span5">
                        <select class="form-control" style="width: 415px;" name="manningId" required>
                            <option disabled>SELECT MANNING</option>
                            <?php foreach ($mannings as $manning) { ?>
                                <option
                                    <?= ($principal->manning_id == $manning->manning_id)?"selected":"";?>
                                    value="<?= $manning->manning_id ?>">
                                    <?= $manning->manning_name ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div> <br />
            <?php } ?>

            <div class="row">
                <div class="span3" style="padding-top: 6px; text-align: right;">
                    <b>USERNAME:</b>
                </div>
                <div class="span5">
                    <input type="text" class="form-control" placeholder="USERNAME" style="width: 400px;" value="<?= $principal->principal_account_name; ?>" name="username" required onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <div class="row">
                <div class="span3" style="padding-top: 6px; text-align: right;">
                    <b>PASSWORD:</b>
                </div>
                <div class="span5">
                    <input type="password" class="form-control" placeholder="PASSWORD" style="width: 400px;" name="password">
                </div>
            </div> <br />

            <div class="row">
                <div class="span3" style="padding-top: 6px; text-align: right;">
                    <b>CONFIRM PASSWORD:</b>
                </div>
                <div class="span5">
                    <input type="password" class="form-control" placeholder="CONFIRM PASSWORD" style="width: 400px;" name="confirmPassword">
                </div>
            </div> <br />

            <?php if($this->session->userdata('principal_no') == 'PRIN-0030') { ?>
                <div class="row">
                    <div class="span3" style="padding-top: 6px; text-align: right;">
                        <b>ACTIVE STATUS:</b>
                    </div>
                    <div class="span5">
                        <input type="checkbox" name="status" class="form-control" <?= ($principal->account_status != "1") ?: "checked"; ?>>
                    </div>
                </div> <br />
            <?php } ?>
        </div>
    </div>

    <div class="row">
        <div class="span5"></div>
        <div class="span4">
            <input type="submit" class="btn btn-primary btn-block" onclick="return confirm('Are you sure you want to update?')" VALUE="Update">
        </div>
        <div class="span2">
            <?php if($this->session->userdata('principal_no') == 'PRIN-0030') { ?>
                <a href="<?= base_url("users"); ?>" class="btn btn-danger btn-block" onclick="return confirm('Go Back?')">Back</a>
            <?php } else { ?>
                <a href="<?= base_url("principals"); ?>" class="btn btn-danger btn-block" onclick="return confirm('Go Back?')">Back</a>
            <?php } ?>
        </div>
        <div class="span5"></div>
    </div>

    </form>
</div>
