<table class="table table-condensed" style="font-size:12px;padding:0px;margin:0px; border: 1px solid black;">
    <thead>
    <tr>
        <th style="border: 1px solid black">#</th>
        <th style="border: 1px solid black">CREWIPN</th>
        <th style="border: 1px solid black">RANK</th>
        <th style="border: 1px solid black">FULL NAME</th>
        <?php foreach ($documents as $document) { ?>
            <th style="border: 1px solid black"><?= $document->document_name ?></th>
        <?php  } ?>
        <th style="border: 1px solid black">UNUPLOADED</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($crewWithDocs as $index => $crewWithDoc) { ?>
        <?php
            $crew = $crewWithDoc['crew'];
            $crewDocuments = $crewWithDoc['documents'];
            $count = 0;
            $unuploaded = 0;
        ?>
        <tr>
            <td style="border: 1px solid black"><?= $index+1 ?></td>
            <td style="border: 1px solid black"><?= $crew->rank_alias ?></td>
            <td style="border: 1px solid black"><?= $crew->CREWIPN ?></td>
            <td style="border: 1px solid black"><?= "$crew->FNAME, $crew->GNAME, $crew->MNAME" ?></td>
            <?php
                if(count($crewDocuments)) {
                    foreach ($documents as $document)  {
                        switch ($crewDocuments[$document->id]['bg']) {
                            case 'red':
                                $unuploaded++;
                            case 'green':
                                $count++;
                                break;
                        }
            ?>
                    <td style="border: 1px solid black; background: <?= $crewDocuments[$document->id]['bg']  ?>;">
                        <?php if(isset($crewDocuments[$document->id]['dateUpload'])) { ?>
                            <a href="<?= "https://krbsgroup.com/krbsrepository/welcome/viewDocument/$crew->CREWIPN/veritas/" . $crewDocuments[$document->id]['docName']; ?>" target="_blank">
                                <?= $crewDocuments[$document->id]['dateUpload'] ?>
                            </a>
                        <?php } else if($crewDocuments[$document->id]['bg'] == 'pink') { ?>
                            NO VERIPRO UPLOAD
                        <?php } ?>
                    </td>
                <?php } ?>
                    <td style="border: 1px solid black"><?= "$unuploaded / $count" ?></td>
            <?php } else {?>
                <td style="border: 1px solid black;" colspan="49">
                    <b>NO MATCHING RECORD</b>
                </td>
            <?php } ?>
        </tr>
    <?php } ?>
    </tbody>
</table>
