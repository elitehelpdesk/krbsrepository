<h3>EDIT DOCUMENT</h3>

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
    <?php echo form_open("documents/update"); ?>

    <input type="hidden" value="<?= $document->id?>" name="documentId">

    <div class="row">
        <div class="span8">

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>DOCUMENT CODE:</b>
                </div>
                <div class="span6">
                    <input type="text" value="<?= $document->document_code ?>" name="documentCode" class="form-control" placeholder="DOCUMENT CODE" style="width: 450px;" required onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>MK CODE:</b>
                </div>
                <div class="span6">
                    <input type="text" value="<?= $document->document_code_mk ?>" name="mkCode" class="form-control" placeholder="DOCUMENT MK CODE" style="width: 450px;" required onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

            <div class="row">
                <div class="span2" style="padding-top: 6px; text-align: right;">
                    <b>DOCUMENT NAME:</b>
                </div>
                <div class="span6">
                    <input type="text" value="<?= $document->document_name ?>" name="documentName" class="form-control" placeholder="DOCUMENT NAME" style="width: 450px;" required onkeyup="this.value=this.value.toUpperCase()">
                </div>
            </div> <br />

        </div>
        <div class="span8">

            <div class="row">
                <div class="span3" style="padding-top: 6px; text-align: right;">
                    <b>DOCUMENT TYPE:</b>
                </div>
                <div class="span5">
                    <select class="form-control" style="width: 415px;" name="documentType" required>
                        <option disabled>--- SELECT DOCUMENT TYPE ---</option>
                        <?php foreach ($documentTypes as $documentType) { ?>
                            <option
                                <?= ($document->document_type == $documentType->document_type)?"selected":"";?>
                                value="<?= $documentType->document_type ?>">
                                <?= $documentType->document_type ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div> <br />


            <div class="row">
                <div class="span3" style="padding-top: 6px; text-align: right;">
                    <b>COUNTRY:</b>
                </div>
                <div class="span5">
                    <select class="form-control" style="width: 415px;" name="country" required>
                        <option disabled>--- SELECT COUNTRY ---</option>
                        <?php foreach ($countries as $country) { ?>
                            <option
                                <?= ($document->country_id == $country->id)?"selected":"";?>
                                value="<?= $country->id ?>">
                                <?= $country->name ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div> <br />

            <div class="row">
                <div class="span3" style="padding-top: 6px; text-align: right;">
                    <b>ACTIVE STATUS:</b>
                </div>
                <div class="span5">
                    <input type="checkbox" name="status" class="form-control" <?= ($document->status)? "checked": "" ?>>
                </div>
            </div> <br />

        </div>
    </div> <br />

    <div class="row container">
        <div class="span12">
            <table class="table table-striped table-condensed table-hover">
                <thead>
                <tr style="font-weight:bold;font-size:14px;">
                    <td>Nationality</td>
                    <?php for ($i=0; $i<5; $i++) { $country = $countries[$i]; ?>
                        <td> <?= $country->nationality_code ?> </td>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rankTypes as $rank_type) {?>
                    <tr>
                        <td>
                            <?= $rank_type->code ?>
                            <input type="checkbox" onclick="autoCheck(this.checked, <?= $rank_type->id ?>);">
                        </td>
                        <?php for ($i=0; $i<5; $i++) { $country = $countries[$i]; ?>
                            <td>
                                <input type="checkbox" name="matrix[<?= $rank_type->id ?>][<?= $country->id ?>]" id="matrix<?= "$rank_type->id$country->id" ?>"
                                    <?php
                                    $status = $this->db->select("status")
                                        ->from("document_rank_type")
                                        ->where("document_id", $document->id)
                                        ->where("rank_type_id", $rank_type->id)
                                        ->where("country_id", $country->id)
                                        ->get()->row()->status;
                                    if($status) {
                                        echo "checked";
                                    }
                                    ?>
                                >
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="span5"></div>
        <div class="span4">
            <input type="submit" class="btn btn-primary btn-block" onclick="return confirm('Are you sure you want to update?')" VALUE="Update">
        </div>
        <div class="span2">
            <a href="<?= base_url("documents"); ?>" class="btn btn-danger btn-block" onclick="return confirm('Go Back?')">Back</a>
        </div>
        <div class="span5"></div>
    </div>

    </form>
</div>

<script type="text/javascript">
    function autoCheck(state, row) {
        for (let column=1; column<6; column++) {
            document.getElementById("matrix" + row + column ).checked = state
        }
    }
</script>
