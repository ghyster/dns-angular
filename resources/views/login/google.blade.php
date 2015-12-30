<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div style="with: 640px;margin:auto;margin-top:200px;">

	  <div style="text-align: center;">
      <p>Use your google account to login by clicking the button</p>
	  	<span id="signinButton">
				  <span
				    class="g-signin"
				    data-callback="signinCallback"
				    data-clientid="{{\Config::get('app.google_client_id')}}"
				    data-cookiepolicy="single_host_origin"
				    data-redirecturi="postmessage"
				    data-accesstype="offline"
				    data-scope="https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email">
	  			</span>
			</span>
		</div>
    </div>


    <form style="display:none;" name="googlelogin" method="post" action="/login/google"><input type="hidden" name="access_token" value=""/></form>

    <!-- Placez ce script JavaScript asynchrone juste devant votre balise </body> -->
    <script type="text/javascript">
      (function() {
       var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
       po.src = 'https://apis.google.com/js/client:plusone.js';
       var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
     })();

      function signinCallback(authResult) {
    	  if (authResult['code']) {
    	    // Autorisation réussie
    	    // Masquer le bouton de connexion maintenant que l'utilisateur est autorisé, par exemple :
    	    document.getElementById('signinButton').setAttribute('style', 'display: none');
			//console.log(authResult);

			document.googlelogin.access_token.value=authResult['code'];
			document.googlelogin.submit();


    	  } else if (authResult['error']) {
        	  //alert(authResult['error']);
    	    // Une erreur s'est produite.
    	    // Codes d'erreur possibles :
    	    //   "access_denied" - L'utilisateur a refusé l'accès à votre application
    	    //   "immediate_failed" - La connexion automatique de l'utilisateur a échoué
    	     //console.log("Une erreur s'est produite : " + authResult['error']);
    	  }
    	}
    </script>
</body>
</html>
