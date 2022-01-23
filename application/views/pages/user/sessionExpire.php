<div id="sessionExpire" class="modal fade" role="dialog" aria-labelledby="sessionExpireLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="row">
                    <div style="width: 550px;">
                        <h3>Expiring Session</h3>
                    </div>
                    <div style="width: 550px;">
                        Session will Expire in <span id="time" style="color: red;"></span> seconds.<br />
                        Application will be automatically logged out.<br /><small><u>For Security Purpose</u></small>
                    </div>
                </div> <br />
                <div class="row">
                    <div class="span1" style="width: 90px;"></div>
                    <div class="span2">
                        <a href="<?php echo base_url("welcome/logout");?>" type="button" class="btn btn-block" style="background: silver; color: white;">
                            SIGN OUT
                        </a>
                    </div>
                    <div class="span2">
                        <a type="button" class="btn btn-primary btn-block" id="refresh" onclick="refresh();" data-dismiss="modal" aria-label="Close">CONTINUE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="expiredSession" class="modal fade" role="dialog" aria-labelledby="expiredSessionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="row">
                    <div style="width: 550px;">
                        <h3>Your Session Expired</h3>
                    </div>
                    <div style="width: 550px;">
                        Please sign in to renew your session or choose to exit to close window.
                    </div>
                </div> <br />
                <div class="row">
                    <div class="span1" style="width: 90px;"></div>
                    <div class="span2">
                        <a href="/" class="btn btn-block" style="background: silver; color: white;">
                            EXIT
                        </a>
                    </div>
                    <div class="span2">
                        <a href="<?php echo base_url();?>" class="btn btn-primary btn-block">
                            SIGN IN
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let seconds = <?php echo SESSION_EXPIRED; ?>;
    function count() {
        document.getElementById('time').innerHTML = seconds;
        if(seconds <= 35 && seconds > 5) {
            if(!document.getElementById('sessionExpire').style.display || document.getElementById('sessionExpire').style.display === 'none')
                $("#sessionExpire").modal('show');
        } else if (seconds <= 5) {
            jQuery.get('<?php echo base_url('users/sessionLogout'); ?>',
                {},
                function (data) {
                    $("#sessionExpire").modal('hide');
                    $("#expiredSession").modal('show');
                }).fail(function () {
                alert('CANNOT LOG OUT SESSION!!');
            });
        }
        seconds--;
        setTimeout(count, 1000);
    }
    count();

    function refresh() {
        jQuery.post('<?php echo base_url('users/refreshSession');?>',
            {sessionDate: new Date(), <?= $this->security->get_csrf_token_name(); ?>: "<?= $this->security->get_csrf_hash(); ?>"},
            function (data) {
                console.log(data);
                $("#sessionExpire").modal('hide');
            }
        ).fail(function (err) {
            console.log(err);
            alert("Error occurred, refreshing the page.");
        });
        seconds = <?php echo SESSION_EXPIRED; ?>;
    }

</script>
