<html data-ng-app="dns" data-ng-controller="dnsCtrl">
 <head>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/angular_material/1.0.0-rc5/angular-material.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=RobotoDraft:300,400,500,700,400italic">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="/css/md-data-table.min.css" type="text/css" />
    <link rel="stylesheet" href="/css/app.css" type="text/css" />
    <meta name="viewport" content="initial-scale=1" />
  </head>
  <body layout="column" ng-controller="dnsCtrl">

 <md-toolbar layout="row">
      <div class="md-toolbar-tools">
        <md-button ng-click="toggleSidenav('left')" hide-gt-sm class="md-icon-button">
          <!-- <md-icon aria-label="Menu" md-svg-icon="https://s3-us-west-2.amazonaws.com/s.cdpn.io/68133/menu.svg"></md-icon> -->
          <md-icon md-font-set="material-icons" aria-label="Menu" >menu</md-icon>

        </md-button>
        <h1>DNS management</h1>
      </div>
    </md-toolbar>
    <div layout="row" flex>
        <md-sidenav md-swipe-left="toggleSidenav('left')" layout="column" class="md-sidenav-left md-whiteframe-z2" md-component-id="left" md-is-locked-open="$mdMedia('gt-sm')">

          <div class="sidebar-inner c-overflow">
		    <div class="profile-menu">
		    	<div class="profile-info">
		               {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}

					    <i class="material-icons md-dark md-18" ng-click="disconnect()">power_settings_new</i>


		        </div>
		    </div>

		    <ul class="main-menu">


		        <li data-ui-sref-active="active">
		            <a data-ui-sref="home" data-ng-click="dnsCtrl.sidebarStat($event)"><i class="material-icons md-dark">home</i> Home</a>
		        </li>
		        <li ng-repeat="zone in zones | orderBy:['reverse','name']">
		            <a ui-sref="zone({ id: zone.id })" data-ng-click="dnsCtrl.sidebarStat($event)">
                  <i ng-show='zone.reverse == "0"' class="material-icons md-dark">language</i>
                  <i ng-hide='zone.reverse == "0"' class="material-icons md-dark">autorenew</i>
                  @{{zone.name}}
                </a>
		        </li>



		    </ul>
		    <?php if(\Auth::user()->role == "admin"): ?>
		    <div class="profile-menu">
		    	<div class="profile-info">
		               Administration
					<i class="zmdi zmdi-caret-down"></i>
		        </div>
		    </div>
		    <ul class="main-menu">
		    	<li>
		        	<a data-ui-sref="zones" data-ng-click="dnsCtrl.sidebarStat($event)"><i class="material-icons md-dark">device_hub</i> Zones</a>
		        </li>
		        <li>
		        	<a data-ui-sref="users" data-ng-click="dnsCtrl.sidebarStat($event)"><i class="material-icons md-dark">people</i> Users</a>
		        </li>
		    </ul>
		    <?php endif; ?>
		  </div>

        </md-sidenav>
        <div layout="column" flex id="content">
            <md-content layout="column" flex class="md-padding">
            <div ui-view></div>
            </md-content>
        </div>
    </div>
    <!-- Angular Material Dependencies -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-animate.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-aria.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-resource.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angular_material/1.0.0-rc5/angular-material.min.js"></script>
    <script src="/js/angular-ui-router.min.js"></script>
    <script src="/js/ocLazyLoad.min.js"></script>
    <script src="/js/md-data-table.min.js"></script>


   	<script src="/js/angular.js"></script>
  </body>
</html>
