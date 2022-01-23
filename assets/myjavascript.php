<!-- Javascripts -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>

<!--<script type="text/javascript" src="--><?php //echo base_url(); ?><!--assets/js/jquery.validate.js"></script>-->
<!--<script type="text/javascript" src="--><?php //echo base_url(); ?><!--assets/scripts/notification/js/bootstrap-notify.js"></script>-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/scripts/datepicker/js/bootstrap-datepicker.js"></script>

<!--<script type="text/javascript" src="assets/js/myjavascript.js"></script> -->

<script type="text/javascript">
    $(".collapse").collapse();
</script>

<script>
function goBack(){
  window.history.go(-1);
}
</script>
<!-- Make Enter Button became tab function -->
<script type="text/javascript">
    $(document).ready(function(){
$(".inputenter").not( $(":button") ).keypress(function (evt) {
if (evt.keyCode == 13) {
iname = $(this).val();
if (iname !== 'Submit'){
var fields = $(this).parents('form:eq(0),body').find('button, input, textarea, select');
var index = fields.index( this );
if ( index > -1 && ( index + 1 ) < fields.length ) {
fields.eq( index + 1 ).focus();
}
return false;
}
}
});
    });
</script>

<!-- Tooltip -->
<script type="text/javascript">
    $("[rel=tooltip]").tooltip();
</script>
<!-- End Tooltip -->


<!-- Fixed Bugs dropdown in mobile Bootsrap version 2.2.1 -->
<script type="text/javascript">
$('a.dropdown-toggle, .dropdown-menu a').on('touchstart', function(e) {
  e.stopPropagation();
});
</script>
<!-- End 2.2.1 -->

<!--Toggle-->
<script type="text/javascript">
$(document).ready(function(){
  $("#m2").click(function(){
    $(".m1").animate({opacity: 'toggle'}, 300); 
  });
});
</script>
<!--End Toggle-->
<!--Hide and Show Fade-->
<script>
$(document).ready(function(){
  $("#hide").click(function(){
    $(".p1_1").hide();
<!--Hide $(".p2").show(); i change the .show to .animate to fade-->
	$(".p2_1").animate({opacity: 'toggle'}, 1000); 
  });
  $("#show").click(function(){
    $(".p1_1").animate({opacity: 'toggle'}, 300); 
	$(".p2_1").hide();
  });
});
</script>
<!--End hide and show Fade-->

<!--Hide and Show Slide-->
<script>
$(document).ready(function(){
  $("#hide").click(function(){
    $(".p1").hide();
<!--Hide $(".p2").show(); i change the .show to .animate to fade-->
	$(".p2").animate({height: 'toggle'}, 1000); 
  });
  $("#show").click(function(){
    $(".p1").animate({height: 'toggle'}, 300); 
	$(".p2").hide();
  });
});
</script>
<!--End hide and show Slide-->


<!-- Date Picker -->
<script>
$(document).ready(function() {
   $("#datepicker").datepicker({
      dateFormat: "yy-mm-dd"
   });
   $("#datepicker_btn").click(function(event) {
      event.preventDefault();
      $("#datepicker").focus();
   });
});
</script>
<!-- End Date Picker -->

<!-- Notification -->
<script>
      // Random Messages
      var example = {
        messages: [
          'You Have An Email.'
        ],
        positions: [
          'bottom-right'
          //'bottom-left',
          //'top-right',
          //'top-left',
          //'center'
        ],
        styles: [
          'info'
          //'success',
          //'warning',
          //'danger',
          //'inverse',

          // custom
          //'bangTidy',
          //'blackgloss'
        ]
      };

      function select (arr) {
        return arr[Math.floor(Math.random() * arr.length)];
      }

      // Basic Features, style isn't even required.
      $('.show-notification').click(function (e) {
        $('.' + select(example.positions)).notify({ message: { text: select(example.messages) }, type: select(example.styles) }).show();
      });

      /* Custom Styles */
      var custom = [
        'bangTidy',
        'blackgloss'
      ];

      for(var i = 0; i < custom.length; i++) {
        var type = custom[i];

        (function(type) {
          $('.show-' + type).click(function (e) {
            $('.' + select(example.positions)).notify({ message: { text: select(example.messages) }, type: type }).show();
          });
        })(type);
      }
</script>




<!-- datepicker -->
        <script>
            $(document).ready(function() {
                $("#datepicker0").datepicker();
            
                $("#datepicker1").datepicker({
                    isRTL: false,
                    dateFormat: "yy-mm-dd"
                });
                $("#datepicker1btn").click(function(event) {
                    event.preventDefault();
                    $("#datepicker1").focus();
                })
                
                $("#datepicker2").datepicker({
                    isRTL: false,
                    dateFormat: "yy-mm-dd"
                });
                $("#datepicker2btn").click(function(event) {
                    event.preventDefault();
                    $("#datepicker2").focus();
                })
                
                
           });
        </script>
<!-- End datepicker -->





<!-- AJAX not working on IE-->

<script>    
    $('#ajax_submit').click(function() {      
    $(this).attr("disabled", "disabled");
               $.ajax({                                
                    url:"<?php echo base_url();?>indexController/test?menu=4",
                    type: 'POST',
                    dataType: 'html',
                    success: function(returnDataFromController) {
                          $('#ajaxcontainer').html(returnDataFromController);
                          $(this).removeAttr("disabled");
                    }
                  });
       return false;      
});
</script>

<!-- AJAX -->

<script>  
function loadPage(){
	var ajaxRequest;  // The variable that makes Ajax possible!
	
	try{
		// Opera 8.0+, Firefox, Safari, Chrome
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4  && ajaxRequest.status == 200){
			document.getElementById("myDiv").innerHTML = ajaxRequest.responseText;
		}
	},
	ajaxRequest.open("GET", "index.php", true);
	ajaxRequest.send(null); 
}
</script>