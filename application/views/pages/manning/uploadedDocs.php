<br />
<div class="well well-small">
    <b><?= $manning->manning_name;?></b>
    <span class="pull-right">
        <a href="<?= base_url("mannings/index");  ?>">Search Crew</a>
    </span>
</div>

<div class="row">
    <div class="span4 offset4">
        <?php echo form_open("mannings/uploadedDocs", array("style" => "text-align:left; border:1px solid; border-color:#DDDDDD; background-color:#FBFBFB;", "class" => "form-inline well")); ?>
            <label style="color:#666666; font-size:15px;"><strong>From:</strong></label> <br />
            <div class="input-append">
                <input id="datepicker1" class="input-small" name="from" type="date"  placeholder="From" style="width:200px;">
            </div> <br />
            <label style="color:#666666; font-size:15px;"><strong>To:</strong></label> <br />
            <div class="input-append">
                <input id="datepicker2" class="input-small" name="to" type="date"  placeholder="To" style="width:200px;">
            </div> <hr />
            <div style="text-align:right;">
                <button type="submit" name="add" class="btn btn-primary"/>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="row">

    <div class="span12">
        <table class="table table-striped table-condensed table-hover ">
            <thead>
                <tr style="font-weight:bold;font-size:14px;">
                    <td width="30">#</td>
                    <td width="50">Crewipn</td>
                    <td width="80">Rank</td>
                    <td width="100">Last Name</td>
                    <td width="100">First Name</td>
                    <td width="200" style="text-align:center;">Documents</td>
                    <td width="100" style="text-align:center;">Date Uploaded</td>
                    <td width="130" style="text-align:center;">Uploaded By</td>
                    <td width="50" style="text-align:center;">View</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($crew_list as $index => $list) {?>
                    <tr style="font-size:11px;">
                        <td><?= $index+1; ?></td>
                        <td><?= $list->CREWIPN; ?></td>
                        <td><?= $list->rank_alias; ?></td>
                        <td><?= $list->FNAME; ?></td>
                        <td><?= $list->GNAME; ?></td>
                        <td><?= $list->document_name; ?></td>
                        <td><?= $list->uploaded_date; ?></td>
                        <td><?= "$list->last_name $list->first_name"; ?></td>
                        <td>
                            <?php if(file_exists(DOCFOLDER."$manning->manning_folder_name/$list->CREWIPN/$list->CREWIPN$list->country_code$list->document_code_mk.pdf")) {?>
                                <a href="<?= base_url("welcome/viewDocument/$list->CREWIPN/$manning->manning_folder_name/$list->CREWIPN$list->country_code$list->document_code_mk"); ?>" class="btn btn-mini btn-warning" target="_blank" style="color: #000">
                                    <i class="icon-file"></i>
                                    View
                                </a>
                            <?php } elseif(file_exists(DOCFOLDER."$manning->manning_folder_name/company_license/$list->country_code$list->document_code_mk.pdf")) {?>
                                <a class="btn btn-mini btn-warning" target="_blanc" style="color:#000;"
                                   href="<?= base_url("welcome/viewDocument/null/$manning->manning_folder_name/$list->country_code$list->document_code_mk/cf"); ?>">
                                    <i class="icon-file"></i> View
                                </a>
                            <?php } else {?>
                                <strong style="color: red;">
                                    DELETED
                                </strong>
                            <?php }?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>