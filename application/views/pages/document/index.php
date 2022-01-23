
<ul class="nav nav-pills">
    <li>
        <b style="font-size: 25px;"> Document List </b>
    </li>
    <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>

    <a href="<?= base_url("documents/create") ?>" class="btn btn-primary">
        <i class="cus-user-suit"></i> Add Document
    </a>

    <fieldset>
        <center>
            <select class="span3" rel="tooltip" title="Select Category" name="cat_keyword" onchange="changeInput(this.value);">
                <option value="1">DOCUMENT CODE</option>
                <option value="2" selected>NAME</option>
                <option value="3">COUNTRY CODE</option>
                <option value="5">DOCUMENT TYPE</option>
                <option value="7">STATUS</option>
            </select>
            <input type="text" name="keyword" placeholder="Enter Name" title="Enter : Name" class="span5" id="keyword" onkeyup="searchDoc(this.value);">
        </center>
    </fieldset>

</ul>

<script>
    // let rows = document.getElementById("docRows").rows;
    // for(let i=0; i<rows.length; i++) {
    //     alert(i + ". " +rows[i].cells[1].innerHTML.toLowerCase() + "--" + rows[i].cells[2].innerHTML.toLowerCase());
    // }
    // alert(document.getElementById("docRows").rows[1].cells[1].innerHTML);
    let category = 2;
    function changeInput(value) {
        let placeHolder = "";
        switch (value) {
            case 2:
                placeHolder = "Enter : DOCUMENT NAME";
                break;
            case 1:
                placeHolder = "Enter : DOCUMENT CODE";
                break;
            case 3:
                placeHolder = "Enter : COUNTRY CODE";
                break;
            case 5:
                placeHolder = "Enter : DOCUMENT TYPE";
                break;
            case 7:
                placeHolder = "Enter : DOCUMENT STATUS";
                break;
        }
        // document.getElementById("keyword").placeholder = placeHolder;
        document.getElementById("keyword").setAttribute('placeholder', placeHolder);
        document.getElementById("keyword").setAttribute('title', placeHolder);
        // document.getElementById("keyword").title = placeHolder;
        category = value;
        searchDoc(document.getElementById("keyword").value);
    }

    function searchDoc(text) {
        let rows = document.getElementById("docRows").rows;
        if(text !== "") {
            for(let i=0; i<rows.length; i++) {
                if(rows[i].cells[category].innerHTML.toLowerCase().includes(text.toLowerCase())) {
                    // alert("YES: " + rows[i].cells[category].innerHTML);
                    let cells = rows[i].cells;
                    for(let j=0; j<cells.length; j++) {
                        cells[j].style.display = "";
                    }
                } else {
                    // alert("NO: " + rows[i].cells[category].innerHTML);
                    let cells = rows[i].cells;
                    for(let j=0; j<cells.length; j++) {
                        cells[j].style.display = "none";
                    }
                }
            }
        } else {
            for(let i=0; i<rows.length; i++) {
                let cells = rows[i].cells;
                for(let j=0; j<cells.length; j++) {
                    cells[j].style.display = "";
                }
            }
        }
    }
</script>

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

<table class="table table-striped table-condensed table-hover">
    <thead>
    <tr style="font-weight:bold;font-size:14px;" class="text-error">
        <td>#</td>
        <td>Document Code</td>
        <td>Document Name</td>
        <td>Country Code</td>
        <td>Nationality</td>
        <td>Document Type</td>
        <td>Country</td>
        <td>Status</td>
        <td>Action</td>
    </tr>
    </thead>
    <tbody id="docRows">
    <?php foreach ($documents as $index => $document) {?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td><?= $document->document_code_mk ?></td>
            <td><?= $document->document_name ?></td>
            <td><?= $document->code ?></td>
            <td><?= $document->nationality_code?></td>
            <td><?= $document->document_type ?></td>
            <td><?= $document->code ?></td>
            <td><?= $document->status ?"ACTIVE" : "DISABLED" ?></td>
            <td>
                <a role="button" class="btn btn-mini btn-warning" href="<?= base_url("documents/edit/$document->id"); ?>"> Edit </a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
