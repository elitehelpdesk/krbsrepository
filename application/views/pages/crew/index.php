<b style="font-size: 25px;" class="pull-left">
    [<?php foreach ($mannings as $manning) {
        if($currentManning == $manning->manning_id) {
            echo $manning->manning_name;
            break;
        }
    } ?>]
    Crew List
</b>

<div class="pull-right">
    <?php echo form_open("crews/index/$currentManning");?>
    <div class="span6">
        <select style="width: 120px;" rel="tooltip" title="Select Category" name="column" class="form-control">
            <option value="FNAME">Last Name</option>
            <option value="GNAME">First Name</option>
            <option value="MNAME">Middle Name</option>
            <option value="CREWIPN">Crewipn</option>
        </select>
        <input type="text" name="value" rel="tooltip" title="Enter : Last Name | First Name | Middle Name | Crewipn" placeholder="Type something..." required>
        <button type="submit" class="btn" name="submit" rel="tooltip" title="Search" style="margin-bottom: 10px;"><i class="icon-search"></i> Search</button>
    </div>
    </form>
</div>

<br /> <br />

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
    <?php foreach ($mannings as $manning) { ?>
        <li title="<?= $manning->manning_name ?>" <?= ($currentManning == $manning->manning_id) ?"class='active'" :"" ; ?>>
            <a href="<?= base_url("crews/index/$manning->manning_id") ?>" ><?= $manning->manning_code ?></a>
        </li>
    <?php } ?>
</ul>

<?php if(isset($crews)) { ?>
    <?php if(count($crews) > 0) { ?>
        <table class="table table-striped table-condensed table-hover">
            <thead>
            <tr style="font-weight:bold;font-size:14px;" class="text-error">
                <td>#</td>
                <td>CREWIPN</td>
                <td>Last Name</td>
                <td>First Name</td>
                <td>Middle Name</td>
                <td>Birth date</td>
                <td>Rank</td>
                <td>Status</td>
                <td>Action</td>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($crews as $index => $crew) {?>
                <tr>
                    <td><?= $index + $offset ?></td>
                    <td><?= $crew->CREWIPN ?></td>
                    <td><?= $crew->FNAME ?></td>
                    <td><?= $crew->GNAME ?></td>
                    <td><?= $crew->MNAME ?></td>
                    <td><?= $crew->BIRTHDATE ?></td>
                    <td><?= $crew->rank_alias ?></td>
                    <td><?= ($crew->STATUS) ?"ACTIVE" : "INACTIVE"; ?></td>
                    <td>
                        <a role="button" class="btn btn-mini btn-warning" href="<?= base_url("crews/editCrew/$crew->ID"); ?>"> Edit </a>
                        <?php if(in_array($currentManning, [5, 6])) { ?>
                            <a role="button" class="btn btn-mini btn-danger" href="<?= base_url("crews/deleteCrew/$crew->ID"); ?>" onclick="return confirm('Are you sure you want to delete this Principal?');"> Delete </a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>

        </table>
        <div class="pull-right" style="margin-left: auto; ">
            <?php if($this->input->server('REQUEST_METHOD') != 'POST') echo $this->pagination->create_links(); ?>
        </div>
    <?php } else { ?>
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Result Not Found!</strong>
        </div>
    <?php } ?>
<?php } ?>
