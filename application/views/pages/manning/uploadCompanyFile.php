<br />
<div class="well well-small">
    <b><?= $manning->manning_name;?></b>
    <span class="pull-right">
        <a href="<?= base_url("mannings/index");  ?>">Search Crew</a>
    </span>
</div>

<div class="span12">
    <?php if($this->session->flashdata('errors')) { ?>
        <div class="alert alert-error" style="" >
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?php echo $this->session->flashdata('errors'); ?>
        </div>
    <?php } else if($this->session->flashdata('success')) { ?>
        <div class="alert alert-success" style="" >
            <button type="button" class="close" data-dismiss="alert">×</button>
            <b><?= $this->session->flashdata('success'); ?></b>
        </div>
    <?php } ?>
    <form class="form-inline" method="post" action="staff">
        <fieldset>
            <legend>Upload Documents</legend>
        </fieldset>
    </form>
    <?php if(isset($_POST['upload'])){ ?>
        <div class="alert alert-danger" style="height:20px;"><?php echo $error; ?></div>
    <?php } ?>
    <br />
    <center>
        <?php echo form_open_multipart("mannings/addCompanyLicense/$manning->manning_folder_name/$docno/$document->country_code/$document->document_code_mk"); ?>
            <label><b><?= strtoupper($document->document_name); ?></b></label><br />

            <input type="file" name="userfile" size="20"  accept="application/pdf"/>
            <button type="submit" name="upload" class="btn btn-info"/><i class="icon-upload icon-white"></i> Upload</button>
            <br />
            <span class="help-block">Size : <span style="color: #ff0000;">500kb</span> <br />File Format : <span style="color: #ff0000;">PDF format only.</span></span>
        </form>  
    </center>
    
</div>