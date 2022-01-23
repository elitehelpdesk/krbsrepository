<h3>EDIT CREW INFORMATION</h3>

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

    <?php echo form_open("crews/updateCrewInfo"); ?>
    <input type="hidden" value="<?= $crew->ID ?>" name="crewId">

    <div class="row">
        <div class="span8" style="border-right: 1px solid gray">

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>CREWIPN:</b>
                </div>
                <div class="span6">
                    <input type="text" name="crewipn" class="form-control" placeholder="CREWIPN" style="width: 400px;" value="<?= $crew->CREWIPN; ?>" required onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>LAST NAME:</b>
                </div>
                <div class="span6">
                    <input type="text" name="fname" class="form-control" placeholder="LAST NAME" style="width: 400px;" value="<?= $crew->FNAME; ?>" required onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>FIRST NAME:</b>
                </div>
                <div class="span6">
                    <input type="text" name="gname" class="form-control" placeholder="FIRST NAME" style="width: 400px;" value="<?= $crew->GNAME; ?>" required onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>MIDDLE NAME:</b>
                </div>
                <div class="span6">
                    <input type="text" name="mname" class="form-control" placeholder="MIDDLE NAME" style="width: 400px;" value="<?= $crew->MNAME; ?>" onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>BIRTHDAY:</b>
                </div>
                <div class="span6">
                    <input type="date" name="birthdate" class="form-control" style="width: 400px;" value="<?= $crew->BIRTHDATE; ?>">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>OTHER FULLNAME:</b>
                </div>
                <div class="span6">
                    <input type="text" name="otherFullname" class="form-control" placeholder="OTHER FULLNAME" style="width: 400px;" value="<?= $crew->OTHER_FULLNAME; ?>" onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>POSTAL CODE:</b>
                </div>
                <div class="span6">
                    <input type="text" name="postalCode" class="form-control" placeholder="POSTAL CODE" style="width: 400px;" value="<?= $crew->POSTAL_CODE; ?>" onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>INSURANCE NUMBER:</b>
                </div>
                <div class="span6">
                    <input type="text" name="insuranceNumber" class="form-control" placeholder="INSURANCE NUMBER" style="width: 400px;" value="<?= $crew->INSURANCE_NUMBER; ?>" onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>ADDRESS:</b>
                </div>
                <div class="span6">
                    <textarea class="form-control" name="address" placeholder="ADDRESS" style="width: 400px;" onkeyup="this.value=this.value.toUpperCase()"><?= $crew->ADDRESS ?></textarea>
                </div>
            </div> <br />
        </div>

        <div class="span8">

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>MANNING:</b>
                </div>
                <div class="span6">
                    <select name="manning" style="width: 415px;" class="form-control" required>
                        <?php foreach ($mannings as $manning) { ?>
                            <option value="<?= $manning->manning_id ?>"
                                <?= ($crew->MANNING_ID == $manning->manning_id) ?"selected" :"" ; ?>>
                                <?= $manning->manning_name ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>RANK:</b>
                </div>
                <div class="span6">
                    <select name="rank" style="width: 415px;" class="form-control" required>
                        <?php foreach ($ranks as $rank) { ?>
                            <option value="<?= $rank->rank_code ?>"
                                <?= ($crew->RANK == $rank->rank_code) ?"selected" :"" ; ?>>
                                <?= $rank->rank ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>DATE HIRED:</b>
                </div>
                <div class="span6">
                    <input type="date" name="dateHired" class="form-control" placeholder="DATE HIRED" style="width: 400px;" value="<?= $crew->DATE_HIRED; ?>">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>EMAIL ADDRESS:</b>
                </div>
                <div class="span6">
                    <input type="text" name="emailAddress" class="form-control" placeholder="EMAIL ADDRESS" style="width: 400px;" value="<?= $crew->EMAIL_ADDRESS; ?>">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>CONTACT #1:</b>
                </div>
                <div class="span6">
                    <input type="text" name="contact1" class="form-control" placeholder="CONTACT 1" style="width: 400px;" value="<?= $crew->CONTACT_NO1; ?>">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>CONTACT #2:</b>
                </div>
                <div class="span6">
                    <input type="text" name="contact2" class="form-control" placeholder="CONTACT 2" style="width: 400px;" value="<?= $crew->CONTACT_NO2; ?>">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>SCHOOL NAME:</b>
                </div>
                <div class="span6">
                    <input type="text" name="schoolName" class="form-control" placeholder="SCHOOL NAME" style="width: 400px;" value="<?= $crew->SCHOOL_NAME; ?>" onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>DATE GRADUATED:</b>
                </div>
                <div class="span6">
                    <input type="date" name="schoolDateGraduated" class="form-control" placeholder="SCHOOL DATE GRADUATED" style="width: 400px;" value="<?= $crew->SCHOOL_DATE_GRADUATED; ?>">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>STATUS:</b>
                </div>
                <div class="span6">
                    <input type="checkbox" name="status" class="form-control" <?= ($crew->STATUS != "1") ?: "checked"; ?>>
                </div>
            </div> <br />
        </div>
    </div> <br />

    <div class="row">
        <div class="span5"></div>
        <div class="span4">
            <input type="submit" class="btn btn-primary btn-block" onclick="return confirm('Are you sure you want to update?')" VALUE="Update">
        </div>
        <div class="span2">
            <a href="<?= base_url("crews"); ?>" class="btn btn-danger btn-block" onclick="return confirm('Go Back?')">Back</a>
        </div>
        <div class="span5"></div>
    </div>


    </form>

</div>
