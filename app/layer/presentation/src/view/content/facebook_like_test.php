<head>
</head>

<body>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '209332149157812', // App ID
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });

    // Additional initialization code here
    FB.Event.subscribe('edge.create',
    function(response) {
        url = "<?php echo HOST_PREFIX; ?>" + url;
    }
	);
	
	FB.Event.subscribe('edge.remove',
    function(response) {
        alert('You unliked the URL: ' + response);
    }
	);

  };

  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     d.getElementsByTagName('head')[0].appendChild(js);
   }(document));
</script>

<div class="fb-like" data-href="http://www.skyweaver.com/123345521" data-send="true" data-width="450" data-show-faces="true"></div>

</body>
