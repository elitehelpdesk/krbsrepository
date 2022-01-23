<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>"K" Line RoRo Bulk Ship Management Co., Ltd.</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <?php header("Content-Type: text/html; charset=UTF-8");  ?>
    <meta name="description" content="VERITAS WEBSITE">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body class="container">

<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e9ecef">
    <a class="navbar-brand" href="">
        <img src="<?php echo base_url("assets/img/ico/logo.png"); ?>" style="width:60px; height:60px;" class="img-fluid" >
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a href="#" style="font-family: 'Times New Roman'; font-weight: bolder" class="nav-link h3">&nbsp;"K" Line RoRo Bulk Ship Management Co., Ltd.<span class="sr-only">(current)</span></a>
            </li>
        </ul>
    </div>

    <?php if($this->session->userdata('validated')) { ?>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a role="button" href="<?= base_url("welcome/logout"); ?>" style="font-family: 'Times New Roman'; font-weight: bolder" class="btn btn-danger">Logout<span class="sr-only">(current)</span></a>
                </li>
            </ul>
        </div>
    <?php } ?>
</nav>
<hr class="hr"/>

<div class="card">
    <div class="card-header text-center">
        <span class="h4">Elite Software and Data Security Inc.'s Privacy Policy</span>
    </div>
    <div class="card-body">
        <div class="card-body">
            <?php foreach ($eulas as $index=>$eula) { ?>
                <p class="card-text"><?php echo $eula; ?></p>
            <?php } ?>
        </div><hr />
    </div>
    <div class="card-footer">

    </div>
</div>

</body>
</html>
