<?php
$this->load->view("templates/header");
$this->load->view("templates/user_nav");
$this->load->view($content);
$this->load->view("pages/user/sessionExpire.php");
$this->load->view("templates/footer");

?>
