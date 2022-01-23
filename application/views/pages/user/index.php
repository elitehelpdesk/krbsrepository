
<ul class="nav nav-pills">
    <li>
        <b style="font-size: 25px;"> User Account List </b>
    </li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="cus-user-suit"></i> Add Account
            <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a href="<?= base_url("users/addPrincipalAccount");  ?>" title="Add Principal Account">
                    <i class="cus-user-suit"></i> Principal
                </a>
            </li>
            <li>
                <a href="<?= base_url("users/addStaffAccount");  ?>" title="Add Staff Account">
                    <i class="cus-user-suit"></i> Staff
                </a>
            </li>
        </ul>
    </li>
</ul>

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

<ul class="nav nav-tabs">
    <li class="active"><a href="#principal" data-toggle="tab" >Principal</a></li>
    <li ><a href="#staff" data-toggle="tab" >Staff</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade in active" id="principal">
        <h5> Principal Accounts </h5>

        <table class="table table-striped table-condensed table-hover">
            <thead>
            <tr style="font-weight:bold;font-size:14px;" class="text-error">
                <td>#</td>
                <td>Prin. No</td>
                <td>Last Name</td>
                <td>First Name</td>
                <td>Middle Name</td>
                <td>Position</td>
                <td>Manning</td>
                <td>Account Name</td>
                <td>Status</td>
                <td>Action</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($principals as $index => $principal) {?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $principal->principal_no ?></td>
                    <td><?= $principal->last_name ?></td>
                    <td><?= $principal->first_name ?></td>
                    <td><?= $principal->middle_name ?></td>
                    <td><?= $principal->position ?></td>
                    <td title="<?= $principal->manning_name ?>"><?= $principal->manning_code ?></td>
                    <td><?= $principal->principal_account_name ?></td>
                    <td><?= ($principal->account_status) ? "ACTIVE" : "INACTIVE"; ?></td>
                    <td>
                        <a role="button" class="btn btn-mini btn-warning" href="<?= base_url("users/editPrincipal/$principal->principal_id"); ?>"> Edit </a>
                        <a role="button" class="btn btn-mini btn-danger" href="<?= base_url("users/deletePrincipal/$principal->principal_id"); ?>" onclick="return confirm('Are you sure you want to delete this Principal?');"> Delete </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="tab-pane fade in" id="staff">
        <h5> Staff Accounts </h5>

        <table class="table table-striped table-condensed table-hover">
            <thead>
            <tr style="font-weight:bold;font-size:14px;" class="text-error">
                <td>#</td>
                <td>Prin. No</td>
                <td>Last Name</td>
                <td>First Name</td>
                <td>Middle Name</td>
                <td>Position</td>
                <td>Manning</td>
                <td>Account Name</td>
                <td>Status</td>
                <td>Action</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($staffs as $index => $staff) {?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $staff->staff_no ?></td>
                    <td><?= $staff->last_name ?></td>
                    <td><?= $staff->first_name ?></td>
                    <td><?= $staff->middle_name ?></td>
                    <td><?= $staff->position ?></td>
                    <td title="<?= $staff->manning_name ?>"><?= $staff->manning_code ?></td>
                    <td><?= $staff->staff_account_name ?></td>
                    <td><?= ($staff->account_status) ? "ACTIVE" : "INACTIVE"; ?></td>
                    <td>
                        <a role="button" class="btn btn-mini btn-warning" href="<?= base_url("users/editStaff/$staff->staff_id"); ?>"> Edit </a>
                        <a role="button" class="btn btn-mini btn-danger" href="<?= base_url("users/deleteStaff/$staff->staff_id"); ?>" onclick="return confirm('Are you sure you want to delete this Staff?');"> Delete </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
