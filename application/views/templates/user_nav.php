<div class="navbar">
    <div class="navbar-inner">
        <div class="container">
            <img src="<?= base_url("assets/img/ico/logo.png") ?>" style="width:30px; height:30px;" class="pull-left">
            <?php if($this->session->userdata('manning_id') == '7') { ?>
                <a class="brand times" href="<?= base_url("principals"); ?>">&nbsp;"K" Line RoRo Bulk Ship Management Co., Ltd.</a>
            <?php } else { ?>
                <a class="brand times" href="<?= base_url("mannings/index/".$this->session->userdata('manning_id')); ?>">&nbsp;"K" Line RoRo Bulk Ship Management Co., Ltd.</a>
            <?php } ?>
            <div class="btn-group pull-right visible-phone hidden-desktop">
                <a class="btn btn-navbar dropdown-toggle" data-toggle="dropdown" href="#">
                    <span class="icon-bar"></span> <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <ul class="dropdown-menu times">
                    <li><a href="<?= base_url("welcome/logout"); ?>">Log out</a></li>
                </ul>
            </div>
            <div class="nav-collapse">
                <div class="hidden-phone">
                    <ul class="nav pull-right" >
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="cus-user-suit"></i>
                                <?= $this->session->userdata('name'); ?>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="disabled"><a tabindex="-1" href="#">Logged as <?= $this->session->userdata('type') ?></a></li>
                                <li class="divider"></li>

                                <?php if(in_array($this->session->userdata('principal_no'), ['PRIN-0030', 'PRIN-0016'])) { ?>
                                    <li><a href="<?= base_url("historyLogs");?>">History Logs</a></li>
                                <?php } ?>

                                <?php if($this->session->userdata('principal_no') == 'PRIN-0030') { ?>
                                    <li><a href="<?= base_url("users");?>">Accounts</a></li>
                                    <li><a href="<?= base_url("crews");?>">Crews</a></li>
                                    <li><a href="<?= base_url("documents");?>">Documents</a></li>
                                <?php } ?>
                                <li><a href="<?= base_url('users/changePassword');?>">Change Password</a></li>
                                <li><a href="<?= base_url("welcome/logout"); ?>">Log out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
