<br />
<div class="well well-small">
    <b><?= $document->document_name ?> History for <?= "$crew->FNAME, $crew->GNAME, $crew->MNAME" ?></b>
    <span class="pull-right">
        <a href="<?= base_url(($this->session->userdata('type') == "Staff") ?"mannings" : "principals"); ?>"> Search Crew</a>
    </span>
    <span class="pull-right" style="margin-right: 15px;">
        <a href="<?= base_url("crews/show/$crew->ID"); ?>"> Go Back</a>
    </span>
</div>
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

<div class="row">
    <div class="span3">
        <ul class="nav nav-list">
            <?php foreach ($histories as $index => $history) { ?>
                <?php
                $crewDocumentPath = DOCFOLDER."$crew->manning_folder_name/$crew->CREWIPN/$document->id/$history->id.pdf";
                $count = count($histories);
                if(file_exists($crewDocumentPath)) { ?>
                    <?php
                    if($index < 2) {
                        echo $index ? "<li class='nav-header'>Previous</li>" : "<li class='nav-header'>Latest</li>";
                    }
                    ?>
                    <li <?= ($crewDocument->id == $history->id) ? "class='active'" : ''  ?>
                        id="list<?= $history->id ?>"
                        name="link"
                    >
                        <a href="#" onclick="changeDoc(<?= $history->id ?>);" id="<?= $history->id ?>">
                            <?= $history->uploaded_date ?>
                        </a>

                    </li>
                    <?php if($index >= 2 || $document->id == 210) break;  ?>
                <?php } ?>
            <?php } ?>
        </ul>
    </div>
    <div class="span12">
        <?php if($this->session->userdata('type') == "Principal") { ?>
            <a class="btn btn-mini btn-warning pull-right"
               style="!important; margin-bottom: 10px;"
               type="button"
               data-toggle="modal"
               data-target="#editCrewDocumentFile"
            >
                <i class="icon-file"></i> Edit
            </a>
        <?php }  ?>
        <iframe height="1500" width="1200" id="pdfFrame"></iframe>
    </div>
</div>

<div
    class="modal fade"
    id="editCrewDocumentFile"
    role="dialog"
    tabindex="-1"
    aria-labelledby="editCrewDocumentFileTitle"
    aria-hidden="true"
    style="display: none;"
>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <b id="editCrewDocumentFileTitle">Update File</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <?php echo form_open_multipart("crews/updateReportDocument"); ?>
                <input type="hidden" name="crewId" id="crewId" value="<?= $crew->ID ?>" required>
                <input type="hidden" name="crewIpn" id="crewIpn" value="<?= $crew->CREWIPN ?>" required>
                <input type="hidden" name="manning" id="manning" value="<?= $crew->manning_folder_name ?>" required>
                <input type="hidden" name="documentId" id="documentId" value="<?= $document->id ?>" required>
                <input type="hidden" name="crewDocumentId" id="crewDocumentId" required>
                <b>Upload File:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="file" name="userfile" accept="application/pdf" class="form-control" style="margin-bottom: 10px;"/>
                <br />
                <button type="submit" class="btn btn-warning btn-mini pull-right" id="saveButton" onclick="return confirm('Are you sure you want to update this fiile?');">Update</button>
                </form>
            </div>

        </div>
    </div>
</div>


<script>
    let url = "<?= base_url("welcome/viewDocumentReport/$crew->CREWIPN/$crew->manning_folder_name/$document->id/") ?>";
    let links = document.getElementsByName("link");
    //document.getElementById('pdfFrame').src = url + "<?//= $crewDocument->id ?>//";
    function changeDoc(historyId) {
        for(let i=0; i<links.length; i++) {
            let item = links[i];
            item.className =('list' + historyId === item.id) ?"active" :item.id + "list" + historyId;
        }
        document.getElementById('pdfFrame').src = url + historyId;
        document.getElementById('crewDocumentId').value = historyId
    }
    changeDoc(<?= $crewDocument->id ?>);
</script>

</div> <!-- END CONTAINER -->


<div class="modal fade bd-example-modal-lg" id="editCrewDocumentFile" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
     style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

            </div>

        </div>

    </div>
</div>
