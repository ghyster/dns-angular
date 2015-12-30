disconnecting...


<span id="signinButton" style="display:none;">
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
<!-- Placez ce script JavaScript asynchrone juste devant votre balise </body> -->
    <script type="text/javascript">
      (function() {
       var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
       po.src = 'https://apis.google.com/js/client:plusone.js';
       var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
     })();

      function signinCallback(authResult) {
    	  if (authResult['code']) {
    	    
    		  gapi.auth.signOut();
			
    	  } else if (authResult['error']) {
        	  //alert(authResult['error']);
    	    // Une erreur s'est produite.
    	    // Codes d'erreur possibles :
    	    //   "access_denied" - L'utilisateur a refusé l'accès à votre application
    	    //   "immediate_failed" - La connexion automatique de l'utilisateur a échoué
    	     //console.log("Une erreur s'est produite : " + authResult['error']);
    	  }
    	  document.location='/login/logout';
    	  
    	}
    </script>