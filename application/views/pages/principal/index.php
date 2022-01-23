<br />
<div class="well well-small">
    <b>Welcome! Principal</b>
</div>
<a  type="button" data-toggle="collapse" data-target="#crewSearch" aria-expanded="false" aria-controls="crewSearch" onclick="expandCollapse()">
    <h3><span id="plus">-</span> Search Crew</h3>
</a> <hr />

<div class="collapse" id="crewSearch">
    <div class="card card-body">
        <?php echo form_open_multipart("principals", array('class' => 'form-inline'));?>
            <fieldset>
                <center>
                    <label><b>Search By :</b></label>
                    <select class="span2" rel="tooltip" title="Select Category" name="cat_keyword" onchange="changeInput(this.value);">
                        <option value="ALL">All</option>
                        <option value="FNAME">Last Name</option>
                        <option value="GNAME">First Name</option>
                        <option value="MNAME">Middle Name</option>
                        <option value="CREWIPN">Crewipn</option>
                    </select>
                    <input type="text" name="keyword" placeholder="Enter : Last Name | First Name | Middle Name | Crewipn" title="Enter : Last Name | First Name | Middle Name | Crewipn" class="span5" id="keyword">
                    <button type="submit" class="btn" name="submit" rel="tooltip" title="Search"><i class="icon-search"></i> Search</button>
                </center>
            </fieldset>
        </form>
    </div> <br />

    <?php if(isset($_POST['submit']) || isset($_POST['keyword'])){ ?>
        <hr />
        <table class="table table-striped table-condensed table-hover ">
            <thead>
                <tr style="font-weight:bold;font-size:14px;">
                    <td width="100">Crewipn</td>
                    <td width="100">Rank</td>
                    <td width="150">Last Name</td>
                    <td width="150">First Name</td>
                    <td width="150">Middle Initial</td>
                    <td width="100">Mannning</td>
                    <td width="100" style="text-align:center;">Documents</td>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($crew_list)){ ?>
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Result Not Found!</strong>
                    </div>
                <?php } ?>
                <?php foreach ($crew_list as $row_crew_list) { ?>
                <tr style="font-size:11px;" >
                    <td><?php echo $row_crew_list->CREWIPN ; ?></td>
                    <td><?php echo $row_crew_list->rank_alias ; ?></td>
                    <td><?php echo $row_crew_list->FNAME ; ?></td>
                    <td><?php echo $row_crew_list->GNAME ; ?></td>
                    <td><?php echo $row_crew_list->MNAME ; ?></td>
                    <td title="<?php echo $row_crew_list->manning_name; ?>" > <?php echo $row_crew_list->manning_code; ?> </td>
                    <td style="text-align:center;">
                        <?php if(
                                file_exists(DOCFOLDER.$row_crew_list->manning_folder_name."/".$row_crew_list->CREWIPN."/".$row_crew_list->CREWIPN.$foldercode[$row_crew_list->manning_nationality].'PP'.".pdf")
                        || $this->session->userdata('principal_no') == 'PRIN-0030'
                        ) { ?>
                            <a href="<?php echo base_url("crews/show/$row_crew_list->ID");?>" style="color:#000">
                                <i class="icon-file"></i> View
                            </a>
                        <?php } ?>
                    </td>

                </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>
<script type="text/javascript">
    var plus = document.getElementById('plus');
    function expandCollapse() {
        if(plus.innerText === "-") plus.innerText = "+";
        else plus.innerText = "-";
    }

    function changeInput(value) {
        let placeHolder = "";
        switch (value) {
            case "ALL":
                placeHolder = "Enter : Last Name | First Name | Middle Name | Crewipn";
                break;
            case "FNAME":
                placeHolder = "Enter : Last Name";
                break;
            case "GNAME":
                placeHolder = "Enter : First Name";
                break;
            case "MNAME":
                placeHolder = "Enter : Middle Name";
                break;
            case "CREWIPN":
                placeHolder = "Enter : Crew IPN";
                break;
        }
        document.getElementById("keyword").placeholder = placeHolder;
        document.getElementById("keyword").title = placeHolder;
    }
</script>

<form class="form-inline" method="post" action="tnkc_index">
    <fieldset>
        <legend>Choose Manning</legend>
        <center></center>
    </fieldset>
</form>

<?php foreach ($mannings as $rowManning) { ?>
    <ul class="thumbnails">
        <?php foreach ($rowManning as $manning) { ?>

            <li class="span4">
                <a href="mannings/index/<?php echo $manning['index'];?>"
                   class="thumbnail well"
                   style="background-color: #<?php echo $manning['color']; ?>;text-decoration:none;color:#333;">
                    <div style="padding:10px;font-weight: bold;">
                        <center><h4><?php echo $manning['name'];?></h4></center>
                    </div>
                </a>
            </li>

        <?php } ?>
    </ul>
<?php } ?>
