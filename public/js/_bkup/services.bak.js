
/* Services */
var app = angular.module('Timetracker.services', ['ngResource'])

	.value ('fbURL', 'https://incandescent-fire-4784.firebaseio.com')
	.factory('FireBase', function($firebase, fbURL) {
		// console.log('get');
		return $firebase(new Firebase(fbURL));
	})
	
	.factory('EstimateTypes', function() {
		var Types = [
			{value: 0, name:'BrandBox HQ'},
			{value: 1, name:'BrandBox Digital'},
			{value: 2, name:'BrandBox xPress'},
		]

		return function(value) {
			if (typeof value === 'undefined') {
				return Types
			} else {
				for (v_idx in Types) {
					if (Types[v_idx].value == value) return Types[v_idx]
				}
			}
		}
	})
	
	.factory('LogedInUser', function($resource, CSRF_TOKEN) {
		return $resource('/api/v1/loggedin', {
			'csrf_token' :CSRF_TOKEN
		},{
			checkLoginStatuss: { method: 'GET', isArray:false, transformResponse: function(data){
				var data = $.parseJSON(data)
				// console.log('checkLoginStatuss',data);
				if (data.loggedout==true) {
					location.reload();
				}
			}}
		});
	})
	

	.factory('Clients', function($resource, CSRF_TOKEN) {
		return $resource('/api/v1/clients/:id/:projects', {
			id: '@id', projects: '@projects', 'csrf_token' :CSRF_TOKEN
		},{
			queryProjects: { method: 'GET', params: {projects:'projects'}, isArray:true },
			// save: { method: 'PUT' }
		});
	})
	.factory('Projects', function($resource, CSRF_TOKEN) {
		return $resource('/api/v1/projects/:id/:relatilon', {
			id: '@id', relatilon: '@relatilon', 'csrf_token' :CSRF_TOKEN
		},{
			queryTasks: { method: 'GET', params: {relatilon:'tasks'}, isArray:true },
			queryRougeTasks: { method: 'GET' , params: {relatilon:'rougetasks'}, isArray:true },
			queryEstimates: { method: 'GET', params: {relatilon:'estimates'}, isArray:true },
			//create: { method: 'POST' }
		});
	})
	.factory('Estimates', function($resource, CSRF_TOKEN) {
		return $resource('/api/v1/estimates/:id/:relatilon', {
			id: '@id', relatilon: '@relatilon', 'csrf_token' :CSRF_TOKEN
		},{
			queryTasks: { method: 'GET', params: {relatilon:'tasks'}, isArray:true },
			saveTask: { method: 'POST', params: {relatilon:'tasks'}, isArray:true },
		});
	})
	.factory('JobRoles', function($resource, CSRF_TOKEN) {
		return $resource('/api/v1/jobroles/:id/:relatilon', {
			id: '@id', relatilon: '@relatilon', 'csrf_token' :CSRF_TOKEN
		},{
			//create: { method: 'POST' }
		});
	})
	.factory('Tasks', function($resource, CSRF_TOKEN) {
		return $resource('/api/v1/tasks/:id/:relatilon', {
			id: '@id', relatilon: '@relatilon', 'csrf_token' :CSRF_TOKEN
		},{
			changeHours:   { method: 'POST', params: {relatilon:'changehours'}, isArray:true },
			getFullInfo:   { method: 'GET' , params: {relatilon:'info'}, isArray:false },
		});
	})
	.factory('Expences', function($resource, CSRF_TOKEN) {
		return $resource('/api/v1/expences/:id/:relatilon', {
			id: '@id', relatilon: '@relatilon', 'csrf_token' :CSRF_TOKEN
		},{
			// change:   { method: 'POST', params: {relatilon:'changehours'}, isArray:true },
		});
	})
	.factory('Users', function($resource, CSRF_TOKEN) {
		return $resource('/api/v1/users/:id/:relatilon', {
			id: '@id', relatilon: '@relatilon', 'csrf_token' :CSRF_TOKEN
		},{
			bindToTask:   { method: 'POST', params: {relatilon:'bindtotask'  }, isArray:false },
			unbindToTask: { method: 'POST', params: {relatilon:'unbindtotask'}, isArray:false },
		});
	})
	.factory('UserTimeLogs', function($resource, CSRF_TOKEN) {
		return $resource('/api/v1/usertimelogs/:id/:relatilon', {
			id: '@id', relatilon: '@relatilon', 'csrf_token' :CSRF_TOKEN
		},{
			// changeHours:   { method: 'POST', params: {relatilon:'changehours'}, isArray:true },
		});
	})
	.factory('Dashboard', function($resource, CSRF_TOKEN) {
		return $resource('/api/v1/dashboard/:name/:id', {
			id:'@id' ,name: '@name', 'csrf_token' :CSRF_TOKEN
		},{
			getProjects     : { method : 'GET', params : {name : 'projects'  }, isArray : true },
			getTaskroles    : { method : 'GET', params : {name : 'taskroles' }, isArray : false },
			getAllEstimates : { method : 'GET', params : {name : 'estimates' }, isArray : true },


			getChartData    : { method : 'GET', params : {name : 'chartdata' }, isArray : false },
		});
	})


	.factory('updateObject', function() {
		
	})


	.filter('arrayfilter', function() {
		return function(array, expression) {
	        return array.filter(function(comparator) {
	        	
	        	var ret = false

	        	for (key in expression) {
	        		var array = expression[key]
	        		for (arrkey in array) {
	        			try {
	        				ret += comparator[key]==array[arrkey]
	        			} catch(err) {
	        			}
	        		}
	        	}
	            return (ret>0)?true:false

	        });
	    };
	})
	.filter('groupBy', function() {
		return function(object,groupBy) {
			return _(object).groupBy(groupBy)
		}
	})
	.filter('compareArrayToObject', function() {
	    return function(array, expression, comparator) {
        	for (key in array) {
        		if (array[key][comparator]==expression[comparator])	return true
        	}
	        return false;
	    };
	})
	.filter('compareTimesGetObject', function() {
	    return function(array, expression, comparator) {
        	for (key in array) {
        		if (expression.diff(moment(array[key].time))==0) {
        			array[key].exist=true
        			array[key].index=key
        			return array[key]
        		}
        	}
	        return {exist:false};
	    };
	})
	.filter('parseTimeMins' , function() {
		return function(minutes) {
			// var minutes = minutes || 0
			var m = parseInt(minutes%60),
				h = parseInt(minutes/60)
			if (m<10) m = '0'+m
			if (isNaN(m) || isNaN(h)) return '?'
			return h+":"+m
		}
	})
	.filter('htmlEscape', function() {
	    return function(input) {
	        if (!input) {
	            return '';
	        }
	        return input.
	            replace(/&/g, '&amp;').
	            replace(/</g, '&lt;').
	            replace(/>/g, '&gt;').
	            replace(/'/g, '&#39;').
	            replace(/"/g, '&quot;')
	        ;
	    };
	})
	.filter('textToHtml', ['$sce', 'htmlEscapeFilter', function($sce, htmlEscapeFilter) {
	    return function(input) {
	        if (!input) {
	            return '';
	        }
	        input = htmlEscapeFilter(input);

	        var output = '';
	        $.each(input.split("\n\n"), function(key, paragraph) {
	            output += '<p>' + paragraph + '</p>';
	        });

	        return $sce.trustAsHtml(output);
	    };
	}])


