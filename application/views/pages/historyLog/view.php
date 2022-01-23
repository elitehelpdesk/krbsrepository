<h3>HISTORY LOG INFO</h3>

<div class="form-inline well">
    <div class="row">
        <div class="span9">
            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>Last Name:</b>
                </div>
                <div class="span6">
                    <input type="text" readonly class="form-control" style="width: 450px;" value="<?= $user->last_name ?>" name="accountNumber" required>
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>First Name:</b>
                </div>
                <div class="span6">
                    <input type="text" readonly class="form-control" style="width: 450px;" value="<?= $user->first_name ?>" name="accountNumber" required>
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>Middle Name:</b>
                </div>
                <div class="span6">
                    <input type="text" readonly class="form-control" style="width: 450px;" value="<?= $user->middle_name ?>" name="accountNumber" required>
                </div>
            </div>
        </div>
        <div class="span9">
            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>Username:</b>
                </div>
                <div class="span7">
                    <input type="text" readonly class="form-control" style="width: 450px;" value="<?= ($historyLog->principal) ?$user->principal_account_name :$user->staff_account_name ?>" name="accountNumber" required>
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>Account Type:</b>
                </div>
                <div class="span7">
                    <input type="text" readonly class="form-control" style="width: 450px;" value="<?= ($historyLog->principal) ?"Principal" :"Staff" ?>" name="accountNumber" required>
                </div>
            </div><br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>Manning:</b>
                </div>
                <div class="span7">
                    <input type="text" readonly class="form-control" style="width: 450px;" value="<?= $manning->manning_name ?>" name="accountNumber" required>
                </div>
            </div>
        </div>
    </div>
    <hr style="border: 1px solid black" />

    <div class="row">
        <div class="span9">
            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>Event Description:</b>
                </div>
                <div class="span7">
                    <input type="text" readonly class="form-control" style="width: 450px;" value="<?= $historyLog->event_description ?>" name="accountNumber" required>
                </div>
            </div> <br />
            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>Log Date:</b>
                </div>
                <div class="span7">
                    <input type="text" readonly class="form-control" style="width: 450px;" value="<?= $historyLog->logged_at ?>" name="accountNumber" required>
                </div>
            </div>
        </div>
        <div class="span9">
            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>Browser:</b>
                </div>
                <div class="span7">
                    <input type="text" readonly class="form-control" style="width: 450px;" value="<?= $historyLog->browser ?>" name="accountNumber" required>
                </div>
            </div> <br />
            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>IP Address:</b>
                </div>
                <div class="span7">
                    <input type="text" readonly class="form-control" style="width: 450px;" value="<?= $historyLog->ip ?>" name="accountNumber" required>
                </div>
            </div>
        </div>
    </div>
    <?php if($historyLog->old_value || $historyLog->new_value) { ?>
        <div class="row">
            <?php if($historyLog->old_value ) { ?>
                <div class="span9">
                    <h3>OLD VALUE</h3>
                    <?php $oldValue = json_decode($historyLog->old_value) ?>
                    <?php foreach ($oldValue as $index => $value) { ?>
                        <?php if(is_object($value)) { ?>
                            <div class="row">
                                <h3><center><?= ucwords($index) ?></center></h3>
                                <?php foreach ($value as $row => $item) {?>
                                    <div class="row">
                                        <div class="span3" style="padding-top: 6px; text-align: right;">
                                            <b><?= $row ?></b>
                                        </div>
                                        <div class="span2">
                                            <input type="text" readonly class="form-control" style="width: 390px;" value="<?= $item ?>" name="accountNumber" required>
                                        </div>
                                    </div> <br />
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <div class="row">
                                <div class="span2" style="padding-top: 6px; text-align: right;">
                                    <b><?= ucwords($index) ?>:</b>
                                </div>
                                <div class="span6">
                                    <input type="text" readonly class="form-control" style="width: 450px;" value="<?= $value ?>" name="accountNumber" required>
                                </div>
                            </div> <br />
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php if($historyLog->new_value ) { ?>
                <div class="span9">
                    <h3>NEW VALUE</h3>
                    <?php $newValue = json_decode($historyLog->new_value) ?>
                    <?php foreach ($newValue as $index => $value) { ?>
                        <?php if(is_object($value)) { ?>
                            <div class="row">
                                <h3><center><?= ucwords($index) ?></center></h3>
                                <?php foreach ($value as $row => $item) {?>
                                    <div class="row">
                                        <div class="span3" style="padding-top: 6px; text-align: right;">
                                            <b><?= $row ?></b>
                                        </div>
                                        <div class="span2">
                                            <input type="text" readonly class="form-control" style="width: 390px;" value="<?= $item ?>" name="accountNumber" required>
                                        </div>
                                    </div> <br />
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <div class="row">
                                <div class="span2" style="padding-top: 6px; text-align: right;">
                                    <b><?= ucwords($index) ?>:</b>
                                </div>
                                <div class="span6">
                                    <input type="text" readonly class="form-control" style="width: 450px;" value="<?= $value ?>" name="accountNumber" required>
                                </div>
                            </div> <br />
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>

