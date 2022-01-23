<ul class="nav nav-pills">
    <li>
        <b style="font-size: 25px;">History Logs</b>
    </li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>

    <fieldset class="pull-right">
        <?= form_open("historyLogs", ["class" => "form-inline", "id" => "logForm"]) ?>

        <?php if($this->session->userdata('type') == "Principal") { ?>
            <select class="span3" rel="tooltip" title="Select Manning to Search" name="manning" onchange="changeInput(this.value);">
                <option value="">--- SELECT MANNING --- </option>
                <?php foreach ($mannings as $manning) { ?>
                    <option value="<?= $manning->manning_id ?>" <?= ((isset($man) && $man == $manning->manning_id) ?"selected" :"") ?>><?= $manning->manning_alias ?></option>
                <?php } ?>
            </select>
        <?php } ?>
        <input type="text" name="dateFrom" rel="tooltip" title="Select Date From" id="dateFrom" <?= ((isset($dateFrom)) ?"value='$dateFrom'" :"") ?> placeholder="Date From"/>
        <input type="text" name="dateTo" rel="tooltip" title="Select Date To" id="dateTo" onchange="requireDateFrom();" <?= ((isset($dateTo)) ?"value='$dateTo'" :"") ?> placeholder="Date To"/>
        <input type="hidden" name="page" value="0" id="page">
        <button type="submit" class="btn" name="submitForm" rel="tooltip" title="Search"><i class="icon-search"></i> Search</button>
        </form>
    </fieldset>

</ul>

<script>
    $('#dateFrom').dateTimePicker();
    $('#dateTo').dateTimePicker();
    function requireDateFrom() {
        document.getElementById("dateFrom").required = true;
    }

    function submitForm(page) {
        document.getElementById("page").value = page;
        form = document.getElementById("logForm");
        form.submit();
    }

    function openView(historyId) {
        window.open("historyLogs/view/" + historyId);
    }
</script>

<?php
$pages = [];
$a = -1;
while ($a++*100 < $count) $pages[] = $a;
if($page - 5 < 0) {
    $start = 0;
} elseif($page + 6 > count($pages)-1) {
    $start = count($pages) - 13;
} else {
    $start = $page - 5;
}
$pages = range($start, $start+10, 1);
$lastPage = ((int)($count/100)-1);
?>

<div class="pagination">
    <ul>
        <li <?= ($page) ?"onclick=\"submitForm(".(0).");\"" : "class=\"disabled\""; ?>><a href="#">First</a></li>
        <?php foreach ($pages as $indexPage) { ?>
            <li onclick="submitForm(<?= $indexPage ?>)" style="cursor: pointer" <?= ($indexPage == $page) ?"class='active'" : ""?>>
                <a> <?= ($indexPage+1) ?> </a>
            </li>
        <?php } ?>
        <li <?= ($lastPage != $page) ?"onclick=\"submitForm($lastPage);\"" : "class=\"disabled\""; ?> style="cursor: pointer"><a>Last</a></li>
    </ul>
</div>

<table class="table table-striped table-condensed table-hover">
    <thead>
    <tr style="font-weight:bold;font-size:14px;" class="text-error">
        <td>#</td>
        <td>ACCOUNT NAME</td>
        <td>MANNING</td>
        <td>DESCRIPTION</td>
        <td>LOG DATE</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($historyLogs as $index => $historyLog) {
        $user = ($historyLog->principal) ? $principals[$historyLog->account_id] : $staffs[$historyLog->account_id];
        $pageIndex = ($page*  100) + 1;
        ?>
        <tr ondblclick="openView(<?= $historyLog->id ?>);">
            <td><?= ($index + $pageIndex) ?></td>
            <td>
                <?php
                    $fullname = ucwords(strtolower("$user->last_name, $user->first_name"));
                    if($user->middle_name) $fullname .= ', '.substr($user->middle_name, 0, 1).'.';
                    echo $fullname;
                ?>
            </td>
            <td><?= $mannings[$historyLog->manning_id]->manning_alias ?></td>
            <td><?= $historyLog->event_description ?></td>
            <td><?= $historyLog->logged_at ?></td>
        </tr>
    <?php  } ?>
    </tbody>
</table>

<div class="pagination">
    <ul>
        <li <?= ($page) ?"onclick=\"submitForm(".($page-1).");\"" : "class=\"disabled\""; ?>><a href="#">Prev</a></li>
        <?php for($indexPage = 0; $indexPage*100 < $count; $indexPage++ ) { ?>
            <li onclick="submitForm(<?= $indexPage ?>)" style="cursor: pointer" <?= ($indexPage == $page) ?"class='active'" : ""?>>
                <a> <?= ($indexPage+1) ?> </a>
            </li>
        <?php } ?>
        <li <?= (($page+1) * 100 < $count) ?"onclick=\"submitForm(".($page+1).");\"" : "class=\"disabled\""; ?>><a href="#">Next</a></li>
    </ul>
</div>
