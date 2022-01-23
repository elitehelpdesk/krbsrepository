<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>"K" Line RoRo Bulk Ship Management Co., Ltd.</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="description" content="VERITAS WEBSITE">
    <meta name="author" content="">
    <link href="<?php echo base_url("assets/mystyle.css"); ?>" rel="stylesheet">
    <link rel="icon" href="<?php echo base_url("assets/img/ico/logo.png"); ?>">
</head>

<body >
<div class="container">

    <?php echo form_open_multipart("welcome/veritasReport"); ?>
    <input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml" name="userfile"/> <br />














    <input type="submit" name="submitFile"/>
    </form>

</div>
</body>
</html>
