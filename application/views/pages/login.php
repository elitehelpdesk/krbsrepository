<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>"K" Line RoRo Bulk Ship Management Co., Ltd.</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="description" content="VERITAS WEBSITE">
    <meta name="author" content="">


    <link href="<?php echo base_url("assets/mystyle.css"); ?>" rel="stylesheet" integrity="sha384-byh1Rb+5iWjBNkCPHgeJnHrKM93P1kr7PZ8rYt0UN2bfzKLce0qE2VJW8Z02YEic" crossorigin="anonymous">
    <link rel="icon" href="<?php echo base_url("assets/img/ico/logo.png"); ?>">
    <script id="CookieDeclaration" src="https://consent.cookiebot.com/77725ff3-be00-4f00-b161-03e73e747950/cd.js" type="text/javascript" async></script>
</head>
<body>
<div class="container">

<div class="navbar">
    <div class="navbar-inner">
        <div class="container">
            <img src="<?php echo base_url("assets/img/ico/logo.png"); ?>" style="width:30px; height:30px;" class="pull-left">
<!--            <img src="./assets/img/ico/logo.png" style="width:30px; height:30px;" class="pull-left">-->
            <a class="brand times" href="">&nbsp;"K" Line RoRo Bulk Ship Management Co., Ltd.</a>
            <div class="nav-collapse">
                <div class="hidden-phone">
                    <ul class="nav pull-right" style="cursor: pointer;">
                        <li id="staff" class="active"><a type="button" onclick="updateSelection('staff');"><i class="cus-user"></i> Staff</a></li>
                        <li id="manager"><a type="button" onclick="updateSelection('manager');"><i class="cus-user"></i> Manager</a></li>
                        <li id="principal"><a type="button" onclick="updateSelection('principal');"><i class="cus-user-suit"></i> Principal</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div> <br />
<p align="center" ><img src="<?php echo base_url("assets/img/others/welcome.jpg"); ?>" style="width:450px;"></p>

<div class="hidden-phone">
    <div class="container" style="width:300px;">
        <?php if($this->session->flashdata('errors')) { ?>
            <?php foreach($this->session->flashdata('errors') as $index => $error) { ?>
                <div class="alert alert-error" style="" >
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <?php echo ($index+1).". $error"; ?>
                </div>
            <?php } ?>
        <?php } else if($this->session->flashdata('success')) { ?>
            <div class="alert alert-success" style="" >
                <button type="button" class="close" data-dismiss="alert">×</button>
                <b><?= $this->session->flashdata('success'); ?></b>
            </div>
        <?php } ?>

        <?php echo form_open("welcome/login", array('name' => 'form1', 'class' => 'form-inline well', 'style' => 'text-align:left; border:1px solid; border-color:#DDDDDD; background-color:#FBFBFB;'));?>
            <div style="text-align:center; font-weight:bold;"><i class="cus-user"></i> <span id="type">STAFF</span> LOGIN</div>
            <label style="color:#666666; font-size:16px;"><strong>Username</strong></label> <br />
            <input type="hidden" name="position" value="staff" id="pos">
            <div class="input-prepend">
                <span class="add-on"><i class="icon-user"></i></span>
                <input type="text" name="username" id="username" style="width:220px;" onkeyup="this.value=this.value.toUpperCase();" rel="tooltip" title="Enter Your Username" placeholder="Enter Username"/>
            </div> <br /> <br />
            <label style="color:#666666; font-size:16px;"><strong>Password</strong></label> <br />
            <div class="input-prepend">
                <span class="add-on"><i class="icon-lock"></i></span>
                <input type="password" name="password"  style="width:190px;" rel="tooltip" title="Enter Your Password" placeholder="Enter Password" id="userPass"/>
                <span class="add-on">
                    <img src="<?= base_url('assets/img/icons/passwordShow.png'); ?>" alt="Show Password" title="Reveal Password" style="width: 20px;" onclick="showPassword();" id="userPassImg">
                </span>
            </div><br />
            <br />
            <div style="text-align:center;">
                <button type="submit" name="login" class="btn btn-danger" id="login"/>Log In</button>
                <button type="reset" name="reset"  class="btn"/>Clear</button>
            </div>
        </form>
    </div>
</div>
<!-- END CREW LOGIN -->
<br>

<script type="text/javascript">
    function updateSelection(selection) {
        let lis = document.getElementsByTagName("li");
        for(let i=0; i<lis.length; i++) {
            let item = lis[i];
            item.className =(selection == item.id) ?"active" :""
        }
        document.getElementById("pos").value = selection;
        document.getElementById("type").innerHTML = selection.toUpperCase();
    }

    function showPassword() {
        let passwordField = document.getElementById('userPass');
        let imgPasswordField = document.getElementById('userPassImg');
        if(passwordField.type === 'password') {
            passwordField.type = 'text';
            imgPasswordField.src = '<?= base_url('assets/img/icons/passwordHide.png'); ?>';
            imgPasswordField.title = 'Hide Password';
        } else {
            passwordField.type = 'password';
            imgPasswordField.src = '<?= base_url('assets/img/icons/passwordShow.png'); ?>';
            imgPasswordField.title = 'Reveal Password';
        }
    }

    <?php if($this->input->cookie('attempt')) { ?>
    let attempt = <?= $this->input->cookie('attempt'); ?>;
    if(attempt > 2) {
        document.getElementById('username').readOnly = true;
        document.getElementById('userPass').readOnly = true;
        document.getElementById('login').disabled = true;
    }
    <?php } ?>
</script>




