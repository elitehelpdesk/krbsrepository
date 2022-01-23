<h3>ADD PRINCIPAL ACCOUNT</h3>

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
    <?php echo form_open("users/storeStaffAccount"); ?>

    <div class="row">
        <div class="span8">
            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>ACCOUNT NUMBER:</b>
                </div>
                <div class="span6">
                    <input type="text" readonly class="form-control" placeholder="ACCOUNT NUMBER" style="width: 450px;" value="<?= $maxAccountNo ?>" name="accountNumber" required>
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>LAST NAME:</b>
                </div>
                <div class="span6">
                    <input type="text" class="form-control" placeholder="LAST NAME" style="width: 450px;" name="lastName" required onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>FIRST NAME:</b>
                </div>
                <div class="span6">
                    <input type="text" class="form-control" placeholder="FIRST NAME" style="width: 450px;" name="firstName" required onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>MIDDLE NAME:</b>
                </div>
                <div class="span6">
                    <input type="text" class="form-control" placeholder="MIDDLE NAME" style="width: 450px;" name="middleName" onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>POSITION:</b>
                </div>
                <div class="span6">
                    <input type="text" class="form-control" placeholder="POSITION NAME" style="width: 450px;" name="positionName" onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />
        </div>
        <div class="span8">
            <div class="row">
                <div class="span3" style="padding-top: 6px; text-align: right;">
                    <b>MANNING NAME:</b>
                </div>
                <div class="span5">
                    <select class="form-control" style="width: 415px;" name="manningId" required>
                        <option disabled>SELECT MANNING</option>
                        <?php foreach ($mannings as $manning) { ?>
                            <option value="<?= $manning->manning_id ?>">
                                <?= $manning->manning_name ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div> <br />

            <div class="row">
                <div class="span3" style="padding-top: 6px; text-align: right;">
                    <b>USERNAME:</b>
                </div>
                <div class="span5">
                    <input type="text" class="form-control" placeholder="USERNAME" style="width: 400px;" name="username" required onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <div class="row">
                <div class="span3" style="padding-top: 6px; text-align: right;">
                    <b>PASSWORD:</b>
                </div>
                <div class="span5">
                    <input type="password" class="form-control" placeholder="PASSWORD" style="width: 400px;" name="password" required>
                </div>
            </div> <br />

            <div class="row">
                <div class="span3" style="padding-top: 6px; text-align: right;">
                    <b>CONFIRM PASSWORD:</b>
                </div>
                <div class="span5">
                    <input type="password" class="form-control" placeholder="CONFIRM PASSWORD" style="width: 400px;" name="confirmPassword" required>
                </div>
            </div> <br />

            <div class="row">
                <div class="span3" style="padding-top: 6px; text-align: right;">
                    <b>ACTIVE STATUS:</b>
                </div>
                <div class="span5">
                    <input type="checkbox" name="status" class="form-control">
                </div>
            </div> <br />
        </div>
    </div>

    <div class="row">
        <div class="span5"></div>
        <div class="span4">
            <input type="submit" class="btn btn-primary btn-block" onclick="return confirm('Are you sure you want to update?')" value="ADD ACCOUNT">
        </div>
        <div class="span2">
            <a href="<?= base_url("users"); ?>" class="btn btn-danger btn-block" onclick="return confirm('Go Back?')">Back</a>
        </div>
        <div class="span5"></div>
    </div>

    </form>
</div>
