<br />
<div class="well well-small">
    <b><?= $manning->manning_name;?></b>
    <span class="pull-right">
        <a href="<?= base_url("mannings/index/$manning->manning_id");  ?>">Search Crew</a>
    </span>
</div>
<table class="table table-striped table-condensed table-hover">
    <thead>
    <tr style="font-weight:bold;font-size:14px;" class="text-error">
        <td width="500">Document Name</td>
        <td width="200">Document Code</td>
        <td width="200">Last Update</td>
        <td width="250" style="text-align: center;">Action</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($companyFiles as $companyFile) {?>
        <?php $filename =  DOCFOLDER."$manning->manning_folder_name/company_license/$companyFile->country_code$companyFile->document_code_mk.pdf"; ?>
        <tr <?= "style=\"font-size:11px;font-weight: bold;\"";?>>
            <td><?= $companyFile->document_name ; ?></td>
            <td><?= $companyFile->document_code_mk ; ?></td>
            <td><?= file_exists($filename)? $companyFile->uploaded_date: "" ; ?></td>
            <td style="text-align:center;">
                <?php if(file_exists($filename)) { ?>
                    <a class="btn btn-mini btn-warning" target="_blanc" style="color:#000;"
                       href="<?= base_url("welcome/viewDocument/null/$manning->manning_folder_name/$companyFile->country_code$companyFile->document_code_mk/cf"); ?>">
                        <i class="icon-file"></i> View
                    </a>
                    <?php if($this->session->userdata('type') === 'Staff') { ?>
                        <a class="btn btn-danger btn-mini" onclick="return confirm('Are you sure you want to delete?');"
                           href="<?php echo  base_url("mannings/deleteCompanyLicense/$manning->manning_folder_name/$companyFile->country_code$companyFile->document_code_mk/$companyFile->docid"); ?>">
                            <i class="icon-remove icon-white"></i> Delete
                        </a>
                    <?php } ?>
                <?php } else { ?>
                    <?php if($this->session->userdata('type') === 'Staff') { ?>
                        <a class="btn btn-info btn-mini" href="<?= base_url("mannings/uploadCompanyFile/$manning->manning_folder_name/$companyFile->docid"); ?>">
                            <i class="icon-upload icon-white"></i> Upload
                        </a>
                    <?php } else { ?>
                        <span style="color: #ff0000">NO DOCUMENT</span>
                    <?php } ?>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table> <br />
