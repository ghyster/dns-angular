var dns = angular.module('dns', [
'md.data.table',
'ngMaterial',
'ngMessages',
'ui.router',
'ngResource',
'oc.lazyLoad'
]);


dns.config(function ($stateProvider, $urlRouterProvider){
    $urlRouterProvider.otherwise("/home");

    $stateProvider
    //------------------------------
    // HOME
    //------------------------------
    .state ('home', {
    	url: '/home',
        templateUrl: 'views/home.html'
     })
     .state ('zone', {
    	 url: '/zone/:id',
         templateUrl: 'views/zone.html'
     })
     .state ('zones', {
    	 url: '/zones',
         templateUrl: 'views/zones.html'
     })
     .state ('users', {
    	 url: '/users',
         templateUrl: 'views/users.html'
     })
});




dns.controller('dnsCtrl', ['$scope', '$mdSidenav','zoneService', function($scope, $mdSidenav, zoneService){
	  $scope.profilemenu = true;
	  $scope.zones = null;

	  $scope.toggleSidenav = function(menuId) {
	    $mdSidenav(menuId).toggle();
	  };

	  $scope.$on('zones:updated', function(event,data) {
		     $scope.zones = data;
	  });

	  $scope.disconnect = function(){
		  document.location="/login/logout";
	  };

	  zoneService.getZones();

}])

dns.controller('zoneCtrl', ['$scope', '$stateParams', '$filter', '$mdDialog', 'zoneService', function($scope, $stateParams, $filter, $mdDialog, zoneService){
	  $scope.zone = zoneService.getZone($stateParams.id);
	  $scope.records=null;
	  $scope.filter = {
      options: {
        debounce: 500
      }
	  };

	  $scope.query = {
			  filter: '',
			  order: 'name'
	  };

    $scope.qLimit = 20;
    $scope.qPage = 1;

	  function DialogController($scope, $mdDialog, record,zone) {

      if(zone.reverse!=1){
		      $scope.recordtypes=['A', 'AAAA', 'CAA', 'CNAME', 'MX', 'SRV', 'TXT'];
      }else{
          $scope.recordtypes=['PTR'];
      }
		  $scope.cancel = function() {
			  $mdDialog.cancel();
		  };

		  $scope.hide = function() {
			  //$scope.recordForm.$setPristine();
			  $mdDialog.hide();
		  };
		  $scope.record=record;
		  $scope.orirecord = angular.copy(record);
      if(zone.reverse==1){
        $scope.record.type="PTR";
      }
		  $scope.zone=zone;
		  //$scope.form = {};
		  $scope.save = function() {
			  if($scope.recordForm.$valid==true){
				zoneService.saveRecord($scope.record,$scope.zone,$scope.orirecord);
				$scope.record={};
				$mdDialog.hide();
			  }
		  };
	  }

	  $scope.removeFilter = function () {
		    $scope.filter.show = false;
		    $scope.query.filter = '';

		    $scope.zone.records=$scope.records;
		    $scope.records=null;

		    if($scope.filter.form.$dirty) {
		      $scope.filter.form.$setPristine();
		    }
	  };

	  $scope.$watch('query.filter', function (newValue, oldValue) {
		   if($scope.records==null){
			   $scope.records=$scope.zone.records;
		   }
		  var lowercaseQuery = angular.lowercase(newValue);
		  $scope.zone.records=$filter('filter')($scope.records,lowercaseQuery);

	  });

	  $scope.update = function(record){
		  record.zid=$scope.zone.id;
		  $mdDialog.show({
		      controller: DialogController,
		      templateUrl: 'views/editrecord.html',
		      locals: {
		          record: record,
	              zone: $scope.zone
		      },
		      parent: angular.element(document.body),
		      //targetEvent: ev,
		      clickOutsideToClose:true
		    })
		    .then(function(/*answer*/) {
		      //$scope.status = 'You said the information was "' + answer + '".';
		    }, function() {
		      //closed
		  });
	  };

	  $scope.remove = function(record){
		  var confirm = $mdDialog.confirm()
	        .title('Are you sur you want to delete this record ?')
	        .textContent('All record trace will be lost.')
	        .ariaLabel('Delete')
	        //.targetEvent(ev)
	        .ok('Yes')
	        .cancel('No');

		  $mdDialog.show(confirm).then(function() {
			  zoneService.removeRecord(record, $scope.zone);
		  }, function() {

	      });
	  };

	  $scope.createRecord = function(ev){
		  $mdDialog.show({
		      controller: DialogController,
		      templateUrl: 'views/record.html',
		      locals: {
		          record: {
		        	  ttl: DNS_TTL,
		        	  zid: $scope.zone.id
		          },
	              zone: $scope.zone
		      },
		      parent: angular.element(document.body),
		      targetEvent: ev,
		      clickOutsideToClose:true
		    })
		    .then(function(/*answer*/) {
		      //$scope.status = 'You said the information was "' + answer + '".';
		    }, function() {
		      //closed
		    });
	  }

}])

dns.controller('zonesCtrl', ['$scope', '$stateParams', '$filter', '$mdDialog', '$timeout', 'zoneService', function($scope, $stateParams, $filter, $mdDialog, $timeout, zoneService){

	   //$timeout(function(){$scope.mzones = angular.copy($scope.zones);}, 700);
	  //$scope.mzones = angular.copy($scope.zones);

	  $scope.$on('zones:updated', function(event,data) {
		     //console.log(data);
		     $scope.mzones = data;
	  });

    zoneService.getZones();
	  $scope.records=null;
	  $scope.filter = {
	    options: {
			 debounce: 500
		}
	  };

	  $scope.query = {
			  filter: '',
			  order: 'name'
	  };

	  function DialogController($scope, $mdDialog, zone) {
		  $scope.cancel = function() {
			  //$scope.recordForm.$setPristine();
			  //$scope.recordForm.rdata = $scope.orirecord.rdata;
			  //console.log($scope.record,$scope.orirecord);
			  $mdDialog.cancel();
		  };

		  $scope.hide = function() {
			  //$scope.recordForm.$setPristine();
			  $mdDialog.hide();
		  };
		  $scope.zone=zone;
      $scope.zonealgo=['hmac-md5.sig-alg.reg.int','hmac-sha1','hmac-sha256', 'hmac-sha512'];
		  $scope.orizone = angular.copy(zone);

		  $scope.save = function() {
			  //console.log($scope.zoneForm);
			  if($scope.zoneForm.$valid==true){
				zoneService.saveZone($scope.zone, $scope);
				$scope.zone={};
				//$scope.zones = zoneService.getZones();
				$mdDialog.hide();
			  }
		  };
	  }

	  $scope.removeFilter = function () {
		    $scope.filter.show = false;
		    $scope.query.filter = '';

		    $scope.mzones=$scope.records;
		    $scope.records=null;

		    if($scope.filter.form.$dirty) {
		      $scope.filter.form.$setPristine();
		    }
	  };

	  $scope.$watch('query.filter', function (newValue, oldValue) {
		   if($scope.records==null){
			   $scope.records=$scope.mzones;
		   }
		  var lowercaseQuery = angular.lowercase(newValue);
		  $scope.mzones=$filter('filter')($scope.records,lowercaseQuery);

	  });

	  $scope.update = function(zone){

		  $mdDialog.show({
		      controller: DialogController,
		      templateUrl: 'views/createzone.html',
		      locals: {
		          zone: zone,
		          zones: $scope.zones,
		          mzones: $scope.mzones
		      },
		      parent: angular.element(document.body),
		      //targetEvent: ev,
		      clickOutsideToClose:true
		    })
		    .then(function(/*answer*/) {
		      //$scope.status = 'You said the information was "' + answer + '".';
		    }, function() {
		      //closed
		  });
	  };

	  $scope.remove = function(zone){
		  var confirm = $mdDialog.confirm()
	        .title('Are you sur you want to delete this zone ?')
	        .textContent('All zone trace will be lost.')
	        .ariaLabel('Delete')
	        //.targetEvent(ev)
	        .ok('Yes')
	        .cancel('No');

		  $mdDialog.show(confirm).then(function() {
			  zoneService.removeZone(zone);
		  }, function() {

	      });
	  };

	  $scope.createZone = function(ev){
		  //console.log($scope.mzones);
		  //console.log($scope.zones);

		  $mdDialog.show({
		      controller: DialogController,
		      locals: {
		          zone: {},
		          zones: $scope.zones,
		          mzones: $scope.mzones
		      },
		      templateUrl: 'views/createzone.html',
		      parent: angular.element(document.body),
		      targetEvent: ev,
		      clickOutsideToClose:true
		    })
		    .then(function() {

		    }, function() {

		    });
	  }

}])

dns.controller('usersCtrl', ['$scope', '$stateParams', '$filter', '$mdDialog', 'userService', function($scope, $stateParams, $filter, $mdDialog, userService){

	  $scope.users = null;
      userService.getUsers();

	  $scope.$on('users:updated', function(event,data) {
		     //console.log(data);
		     $scope.users = data;
	  });

	  $scope.records=null;
	  $scope.filter = {
	    options: {
			 debounce: 500
		}
	  };

	  $scope.query = {
			  filter: '',
			  order: 'username'
	  };

	  function DialogController($scope, $mdDialog, zoneService, user) {
		  $scope.cancel = function() {
			  $mdDialog.cancel();
		  };

		  $scope.hide = function() {
			  //$scope.recordForm.$setPristine();
			  $mdDialog.hide();
		  };
		  $scope.user=user;
		  //console.log(zoneService);

		  zoneService.getZones();
	      $scope.$on('zones:updated', function(event,data) {
	  		     $scope.uzones = data;
	  	  });

		  $scope.oriuser = angular.copy(user);

		  $scope.save = function() {
			  //console.log($scope.zoneForm);
			  if($scope.userForm.$valid==true){
				userService.saveUser($scope.user);
				$scope.user={};
				//$scope.zones = zoneService.getZones();
				$mdDialog.hide();
			  }
		  };

		  $scope.toggle = function (item, list) {
		        //console.log(list);
			    var idx = list.map(function(e) { return e.id; }).indexOf(item.id);
		        if (idx > -1) list.splice(idx, 1);
		        else list.push(item);
		  };
		  $scope.exists = function (item, list) {
			  return list && list.map(function(e) { return e.id; }).indexOf(item.id) > -1;
			  //console.log(list.map(function(e) { return e.id; }).indexOf(item.id));
		        //return list && list.indexOf(item) > -1;
		  };
	  }

	  $scope.removeFilter = function () {
		    $scope.filter.show = false;
		    $scope.query.filter = '';

		    $scope.users=$scope.records;
		    $scope.records=null;

		    if($scope.filter.form.$dirty) {
		      $scope.filter.form.$setPristine();
		    }
	  };

	  $scope.$watch('query.filter', function (newValue, oldValue) {
		   if($scope.records==null){
			   $scope.records=$scope.users;
		   }
		  var lowercaseQuery = angular.lowercase(newValue);
		  $scope.users=$filter('filter')($scope.records,lowercaseQuery);

	  });

	  $scope.update = function(user){

		  $mdDialog.show({
		      controller: DialogController,
		      templateUrl: 'views/user.html',
		      locals: {
		          user: user
		      },
		      parent: angular.element(document.body),
		      //targetEvent: ev,
		      clickOutsideToClose:true
		    })
		    .then(function(/*answer*/) {
		      //$scope.status = 'You said the information was "' + answer + '".';
		    }, function() {
		      //closed
		  });
	  };

	  $scope.remove = function(user){
		  var confirm = $mdDialog.confirm()
	        .title('Are you sur you want to delete this user ?')
	        .textContent('All user trace will be lost.')
	        .ariaLabel('Delete')
	        .ok('Yes')
	        .cancel('No');

		  $mdDialog.show(confirm).then(function() {
			  userService.removeUser(user);
		  }, function() {

	      });
	  };

	  $scope.createUser = function(ev){
		  $mdDialog.show({
		      controller: DialogController,
		      locals: {
		          user: {}
		      },
		      templateUrl: 'views/user.html',
		      parent: angular.element(document.body),
		      targetEvent: ev,
		      clickOutsideToClose:true
		    })
		    .then(function() {

		    }, function() {

		    });
	  }

}])

dns.service('zoneService', ['$resource', '$http', '$rootScope', function($resource, $http, $rootScope){
	this.getZones = function(){
		//var zoneList = $resource("zone");
		//return zoneList.query();
    $http.get('zone')
		  .then(function successCallback(response) {
			  $rootScope.$broadcast('zones:updated',response.data);
		  }, function errorCallback(response) {
			    // called asynchronously if an error occurs
			    // or server returns response with an error status.
		  });

	}

	this.getZone = function(id){
		var zone = $resource("zone/get?id=:id");
		return zone.get({id: id});
	}

	this.saveRecord = function(record, zone, orirecord){
		var rrecord = $resource("zone/record");
    //console.log(orirecord);
    if(orirecord.hasOwnProperty('name')){
      record.ori=orirecord;
    }
		$http.post('/zone/record',  record)
		  .then(function successCallback(response) {
			  zone.records=response.data;
		  }, function errorCallback(response) {
			    // called asynchronously if an error occurs
			    // or server returns response with an error status.
		  });
	}

	this.saveZone = function(zone,scope){

		$http.post('/zone/save', zone)
		  .then(function successCallback(response) {
			  $rootScope.$broadcast('zones:updated',response.data);
		  }, function errorCallback(response) {
			    // called asynchronously if an error occurs
			    // or server returns response with an error status.
		  });
	}

	this.removeRecord = function(record, zone){
    var rdata={name: record.name, type: record.type, ttl: record.ttl, rdata: record.rdata, zid: zone.id};
    if(record.type=='SRV'){
      rdata.priority=record.priority;
      rdata.weight=record.weight;
      rdata.port=record.port;
    }
    if(record.type=='MX'){
      rdata.priority=record.priority;
    }
    if(record.type=='CAA'){
      rdata.flag=record.flag;
      rdata.tag=record.tag;
    }

		$http.post('/zone/removerecord',  rdata)
		  .then(function successCallback(response) {
			    zone.records = response.data;
		  }, function errorCallback(response) {
			    // called asynchronously if an error occurs
			    // or server returns response with an error status.
		  });
	}

	this.removeZone = function(zone){
		$http.post('/zone/remove',  zone)
		  .then(function successCallback(response) {
			  $rootScope.$broadcast('zones:updated',response.data);
		  }, function errorCallback(response) {
			    // called asynchronously if an error occurs
			    // or server returns response with an error status.
		  });
	}
}])

dns.service('userService', ['$resource', '$http', '$rootScope', function($resource, $http, $rootScope){
	this.getUsers = function(){
		$http.get('/user/all')
		  .then(function successCallback(response) {
			  $rootScope.$broadcast('users:updated',response.data);
		  }, function errorCallback(response) {
			    // called asynchronously if an error occurs
			    // or server returns response with an error status.
		  });
	}

	this.getUser = function(id){
		var user = $resource("user/get?id=:id");
		return user.get({id: id});
	}

	this.saveUser = function(user){

		$http.post('/user/save', user)
		  .then(function successCallback(response) {
			  $rootScope.$broadcast('users:updated',response.data);
		  }, function errorCallback(response) {
			    // called asynchronously if an error occurs
			    // or server returns response with an error status.
		  });
	}

	this.removeUser = function(user){
		$http.post('/user/remove',  user)
		  .then(function successCallback(response) {
			  $rootScope.$broadcast('users:updated',response.data);
		  }, function errorCallback(response) {
			    // called asynchronously if an error occurs
			    // or server returns response with an error status.
		  });
	}
}])

dns.directive('dnsrecordvalue',function(){
            return {
                require: 'ngModel',
                link: function(scope,elem,attrs,ctrl){

                	ctrl.$validators.dnsrecordvalue = function (modelValue,viewValue){
                        //console.log(scope);
                    	if (ctrl.$isEmpty(viewValue)){
                            return false;
                        }
                    	if(scope.record.type=='A'){
	                    	//console.log(viewValue.match(/\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/));
	                        var m=viewValue.match(/\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/);
	                    	return m!=null;
                    	}else if(scope.record.type=='AAAA'){
                        var m=viewValue.match(/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$|^(([a-zA-Z]|[a-zA-Z][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z]|[A-Za-z][A-Za-z0-9\-]*[A-Za-z0-9])$|^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/);
                        return m!=null;
                      }else if(scope.record.type=='CNAME' || scope.record.type=='PTR' || scope.record.type=='SRV' || scope.record.type=='MX'){
                    		var m=viewValue.match(/(?=^.{1,254}$)(^(?:(?!\d+\.)[a-zA-Z0-9_\-]{1,63}\.?)+(?:[a-zA-Z\.]{2,})$)/);
                    		return m!=null;
                      }else if(scope.record.type=='CAA'){
                        if(scope.record.tag=='iodef'){
                          var m=viewValue.match(/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/);
                        }else{
                          var m=viewValue.match(/(?=^.{1,254}$)(^(?:(?!\d+\.)[a-zA-Z0-9_\-]{1,63}\.?)+(?:[a-zA-Z\.]{2,})$)/);
                        }
                        return m!=null;
                    	}else if(scope.record.type=='TXT'){
                        if (viewValue.indexOf('"')!=-1){
                          return false;
                        }
                        return viewValue.length < 255;
                      }else{
                    		return false;
                    	}
                    }
                }
            }
})
