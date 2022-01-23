// JavaScript Document

//<!-- Tooltip -->
//<script type="text/javascript">
    $("[rel=tooltip]").tooltip();
//</script>
//<!-- End Tooltip -->


//<!-- Fixed Bugs dropdown in mobile Bootsrap version 2.2.1 -->
//<script type="text/javascript">
$('a.dropdown-toggle, .dropdown-menu a').on('touchstart', function(e) {
  e.stopPropagation();
});
//</script>
//<!-- End 2.2.1 -->

//<!--Toggle-->
//<script type="text/javascript">
$(document).ready(function(){
  $("#p1").click(function(){
    $(".k1").animate({opacity: 'toggle'}, 300); 
  });
});
//</script>
//<!--End Toggle-->
//<!--Hide and Show Fade-->
//<script>
$(document).ready(function(){
  $("#hide").click(function(){
    $(".p1_1").hide();
//<!--Hide $(".p2").show(); i change the .show to .animate to fade-->
	$(".p2_1").animate({opacity: 'toggle'}, 1000); 
  });
  $("#show").click(function(){
    $(".p1_1").animate({opacity: 'toggle'}, 300); 
	$(".p2_1").hide();
  });
});
//</script>
//<!--End hide and show Fade-->

//<!--Hide and Show Slide-->
//<script>
$(document).ready(function(){
  $("#hide").click(function(){
    $(".p1").hide();
//<!--Hide $(".p2").show(); i change the .show to .animate to fade-->
	$(".p2").animate({height: 'toggle'}, 1000); 
  });
  $("#show").click(function(){
    $(".p1").animate({height: 'toggle'}, 300); 
	$(".p2").hide();
  });
});
//</script>
//<!--End hide and show Slide-->


//<!-- Validation -->
//<script>
(function($,W,D)
{
    var JQUERY4U = {};

    JQUERY4U.UTIL =
    {
        setupFormValidation: function()
        {
            //form validation rules
            $("#theForm").validate({
                rules: {
                    crewipn: "required",
                    first_name: "required",
                    last_name: "required",
					nationality_code: "required",
					mm: "required",
					dd: "required",
					yy: "required",
					birth_date: "required",
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 5
                    },
                    agree: "required"
                },
                messages: {
					crewipn: {
                        required: "Crewipn is required",
                        minlength: "Your crewipn must be at least 8 characters long"
                    },
                    first_name: "First name  is required",
                    last_name: "Last name is required",
					mm: "Month  is required",
					dd: "Day is required",
					yy: "Year is required",
				    birth_date: "Birthday is required",
					nationality_code: "Nationality is required",
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 5 characters long"
                    },
                    email: "Please enter a valid email address",
                    agree: "Please accept our policy"
                },	
				errorContainer: $('#errorContainer'),
   				errorLabelContainer: $('#errorContainer ul'),
   				wrapper: 'li',
				
				
				// display right side of the text box
				//errorElement: "td",
				//errorPlacement: function (error, element) {
    		    //error.insertAfter(element.parent());
				//} ,
				
                submitHandler: function(form) {
                    form.submit();
                }
            });
        }
    },

    //when the dom has loaded setup form validation rules
    $(D).ready(function($) {
        JQUERY4U.UTIL.setupFormValidation();
    });

})(jQuery, window, document);
//</script>
//<!-- End Validation -->

//<!-- Date Picker -->
//<script>
$(document).ready(function() {
   $("#datepicker").datepicker({
      dateFormat: "yy-mm-dd"
   });
   $("#datepicker_btn").click(function(event) {
      event.preventDefault();
      $("#datepicker").focus();
   });
});
//</script>
//<!-- End Date Picker -->

//<!-- Notification -->
//    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
//<script src="http://twitter.github.com/bootstrap/assets/js/google-code-prettify/prettify.js"></script>
//<script>
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

      // Pretty print
      window.prettyPrint && prettyPrint();

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
//</script>