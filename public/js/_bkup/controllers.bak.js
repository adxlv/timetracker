

/* Controllers */

var app = angular.module('Timetracker.controllers', [])
 
.controller('IndexController', function ($scope, $location, $routeParams) {
	var locc = $location.$$absUrl
	var patt = new RegExp(".*"+$location.$$host);
	locc = locc.replace(patt,'')

	if (locc === '/desktopapp/dash#/') $location.path('/my-tasks')
})


.controller('ClientsController', function (LogedInUser, $scope, $rootScope, $routeParams, $timeout, Clients, $location) {
	LogedInUser.checkLoginStatuss()

	$scope.clients = Clients.query()
	
	
	
	$scope.destroy = function($ind) {
		$scope.clients[$ind].$remove()
		$scope.clients.splice($ind,1)
		// https://docs.angularjs.org/api/ngResource/service/$resource
    };

	$scope.gotoProjects = function($id) {
		$location.path('/clients/'+$id+'')
	}

	$timeout( function() {
		// @from interaction.js
		LoadFoundation();

	})
})
.controller('ClientsEditController', function (LogedInUser, $scope, $rootScope, $routeParams, $timeout, Clients, $location) {
	LogedInUser.checkLoginStatuss()
	var link = $routeParams.clientId || ""

	// console.log()
	$scope.client = Clients.get({id:link})

	// $scope.client = fbClients.$child(link)

    $scope.save = function() {
    	$scope.client.$save();
		$location.path('clients')
  	};

	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
	})
})

.controller('ProjectsController', function (LogedInUser, $scope, $rootScope, $routeParams, $timeout, Clients, Projects, $location) {
	LogedInUser.checkLoginStatuss()

	$scope.client = Clients.get({id:$routeParams.clientId},function(client){
		$rootScope.$broadcast('setBreadcrumb',{client:client.title})
	})
	$scope.projects = Clients.queryProjects({id:$routeParams.clientId})
	// console.log($scope.client)

    $scope.destroy = function(Project,$ind) {
    	// console.log($ind); return;
		$scope.projects[$ind].$remove()
		$scope.projects.splice($ind,1)
		Projects.delete({id:Project.id})
		// https://docs.angularjs.org/api/ngResource/service/$resource
    };

    $scope.gotoProject = function($project) {
		$location.path('/clients/'+$scope.client.id+'/projects/'+$project)
	}

	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
		FoundationTabCallbackOverride();
	})
})
.controller('ProjectsEditController', function (LogedInUser, $scope, $rootScope, $routeParams, $timeout, $location, Clients, Projects) {
	LogedInUser.checkLoginStatuss()
	var link = $routeParams.projectId || ""

	$scope.client = Clients.get({id:$routeParams.clientId},function(client){
		$rootScope.$broadcast('setBreadcrumb',{client:client.title})
	})
	$scope.project = Projects.get({id:$routeParams.projectId})

    $scope.save = function() {
    	$scope.project.client_id = $routeParams.clientId
    	$scope.project.$save()
    	$location.path('clients/'+$routeParams.clientId+'/projects')
  	};

  	$scope.back = function() {
  		$location.path('clients/'+$routeParams.clientId+'/projects') 
  	}

	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
		FoundationTabCallbackOverride();
	})
})
.controller('ProjectsOpenController', function ($log, LogedInUser, $scope, $rootScope, $routeParams, $timeout, $location, Clients, Projects, Estimates, Users, Tasks, FireBase, EstimateTypes) {
	LogedInUser.checkLoginStatuss()
	var link = $routeParams.projectId || ""

	$scope.client     = Clients.get({id:$routeParams.clientId}, function(client){
		$rootScope.$broadcast('setBreadcrumb',{client:client.title})
	})
	$scope.project    = Projects.get({id:$routeParams.projectId}, function(project){
		$rootScope.$broadcast('setBreadcrumb',{project:project.title})
	})
	$scope.tasks      = Projects.queryTasks({id:$routeParams.projectId})
	$scope.rougetasks = Projects.queryRougeTasks({id:$routeParams.projectId})
	$scope.task       = {}
	$scope.estimates  = Projects.queryEstimates({id:$routeParams.projectId}, function(estimates) {
		$scope.estimates_grouped = _(estimates).groupBy('group')

		$scope.estimates_grouped = _($scope.estimates_grouped).each( function( group, key, list ) {
			_(group).each( function( estimate, key, list ) {
				_(estimate.tasks).each(function(task){
					task.users = []
					_(task.taskrolebinds).each(function(rolebind){
						_(rolebind.users).each(function(user){
							user.taskrolebinds_id = user.pivot.taskrolebinds_id
							// console.log(user);
							task.users.push(user)
						})
					})
				})
			})
			console.log('V::',group);
			group.options = EstimateTypes(key)
		})


		$timeout( function() {
			ProjectsOpen_Click_Setup($scope);
			ProjectsOpen_Display_Setup();
		},100)
	})
	$scope.users     = Users.query()

	// console.log($scope.tasks);

	$scope.syncobject = FireBase
	// 
	// console.log($scope.project);

    $scope.save = function() {
    	// $scope.project.$save()
    	// $location.path('client/'+$routeParams.clientId+'/projects')
  	};

  	$scope.back = function() {
  	}
  	

	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
		FoundationTabCallbackOverride();
	},400)

	$scope.open_task = function(task,$index) {
		Tasks.get({id:task.id}, function(task) {
			angular.copy(task,$scope.task)
			console.log('T::',$scope.task);
			
			$('#bind-user-modal').foundation('reveal', 'open');
			$timeout( function() {
				ProjectsOpen_BindUserRole_Click_Setup($scope)
			},100)
			
		})
	}
	$scope.done_task = function(id,isDone) {
		$scope.syncobject.pushrefresh = true;
		$scope.syncobject.foruser = 0;
		$scope.syncobject.refreshtime = moment().valueOf();
		$scope.syncobject.$save();
		var $task = {
			id:id,
			done:isDone?1:0,
		}
		Tasks.save($task)
	}
	$scope.bind_role_user = function (taskrole,user) {
		$scope.syncobject.pushrefresh = true;
		$scope.syncobject.foruser = user;
		$scope.syncobject.refreshtime = moment().valueOf();
		$scope.syncobject.$save();
		Users.bindToTask({taskrole:taskrole,id:user},function(user) {
			_($scope.estimates_grouped).each( function( group, key, list ) {
				_(group).each( function( estimate, key, list ) {
					if (estimate.id == $scope.task.estimate_id) {
						_(estimate.tasks).each(function(task){
							if (task.id == $scope.task.id) {
								user.taskrolebinds_id = taskrole
								task.users.push(user)
							}
						})
					}
				})
			})
			$timeout( function() {
				ProjectsOpen_Display_Setup();
			},100)
		})

		
	}
	$scope.unbind_role_user = function (taskrole,user) {
		$scope.syncobject.pushrefresh = true;
		$scope.syncobject.foruser = user;
		$scope.syncobject.refreshtime = moment().valueOf();
		$scope.syncobject.$save();
		Users.unbindToTask({taskrole:taskrole,id:user},function(got_user) {
			_($scope.estimates_grouped).each( function( group, key, list ) {
				_(group).each( function( estimate, key, list ) {
					if (estimate.id == $scope.task.estimate_id) {
						_(estimate.tasks).each(function(task){
							if (task.id == $scope.task.id) {
								_(task.users).each(function(user,key){
									if (user.id == got_user.id && user.taskrolebinds_id == taskrole) task.users.splice(key,1)
								})
							}
						})
					}
				})
			})
			$timeout( function() {
				ProjectsOpen_Display_Setup();
			},100)
		})
	}


	$scope.open_estimate = function($id) {
		if ($id=='new') {
  			$location.path('clients/'+$routeParams.clientId+'/projects/'+$routeParams.projectId+'/estimate/new') 
		} else {
  			$location.path('clients/'+$routeParams.clientId+'/projects/'+$routeParams.projectId+'/estimates/'+$id) 
		}
	}
	$scope.close_estimate = function($id) {
	}
  	$scope.save_estimate = function($id) {
  	};
  	$scope.delete_estimate = function(id, group$index, $index) {
  		Estimates.delete({id:id}, function(){
  			$scope.estimates_grouped[group$index].splice($index,1)
  			if ($scope.estimates_grouped[group$index].length == 0) delete $scope.estimates_grouped[group$index]
  		})
  	};

})

.controller('EstimatesEditController', function (LogedInUser, $scope, $rootScope, $routeParams, $timeout, $location, Clients, Projects, JobRoles, Estimates, Tasks, Expences, EstimateTypes, FireBase) {
	LogedInUser.checkLoginStatuss()

	$scope.client    = Clients.get({id:$routeParams.clientId}, function(client){
		$rootScope.$broadcast('setBreadcrumb',{client:client.title})
	})
	$scope.project   = Projects.get({id:$routeParams.projectId}, function(project){
		$rootScope.$broadcast('setBreadcrumb',{project:project.title})
	})

	$scope.selecttypes = {
		list     : EstimateTypes(),
		selected : EstimateTypes(0)
	}
	
	$scope.estimate = Estimates.get({id:$routeParams.estimateId},function(estimate){
		estimate.tasks.show = estimate.tasks.length==0
		estimate.expences.show= estimate.expences.length==0
		estimate.group = EstimateTypes(estimate.group)
	},function(){
	})
	$scope.jobroles = JobRoles.query()
	$scope.tasks    = Tasks.query()

	$scope.syncobject = FireBase


	console.log('Estimate:',$scope.estimate);



	// WATCHES
	$scope.$watch('estimate.tasks', function(newValue, oldValue) {
		var tasks_total = 0
		var eval = 0,
			var1 = 0,
			var2 = 0

		for (t_idx in $scope.estimate.tasks) {
			for (j_idx in $scope.estimate.tasks[t_idx].jobroles) {
				var1 = $scope.estimate.tasks[t_idx].jobroles[j_idx].pivot.hours || 0
				var2 = $scope.estimate.tasks[t_idx].jobroles[j_idx].salary_neto || 0
				eval = var1 * var2
				tasks_total += (isNaN(eval))?0:eval
			}
		}

		for (e_bind_idx in $scope.estimate.bound_estimates) {
			tasks_total += $scope.estimate.bound_estimates[e_bind_idx].total.estimate;
		}

		$scope.tasks_total = tasks_total
	}, true);

	$scope.$watch('estimate.expences', function(newValue, oldValue) {
		var exp_total = 0
		var eval = 0

		for (e_idx in $scope.estimate.expences) {
			eval = $scope.estimate.expences[e_idx].qty * $scope.estimate.expences[e_idx].price
			exp_total += (isNaN(eval))?0:eval
		}

		for (ex_bind_idx in $scope.estimate.bound_estimates) {
			exp_total += $scope.estimate.bound_estimates[ex_bind_idx].total.expences;
		}

		$scope.exp_total = exp_total
	}, true);

	// FUNCTIONS
	$scope.open_groups = function() {
		// $('#groups-modal').foundation('reveal', 'open');
		// EstimateEdit_JobRoles_Filter($scope)
		// EstimateEdit_JobRoles_Click_Setup($scope)
	}

	$scope.save = function () {
		$scope.estimate.group = $scope.estimate.group.value

		$scope.estimate.$save()
		$location.path('clients/'+$routeParams.clientId+'/projects/'+$routeParams.projectId) 
		
		$scope.syncobject.pushrefresh = true;
		$scope.syncobject.$save();
	}
	$scope.back = function () {
		$location.path('clients/'+$routeParams.clientId+'/projects/'+$routeParams.projectId) 
	}


	$scope.add_task = function ($task) {
		if (typeof($task)==='undefined') {
			console.error('Please be careful! There is no task to add!');
		} else {
			$scope.estimate.tasks.show = false;

			$task.estimate_id = $scope.estimate.id
			$task.jobroles = $scope.estimate.involved_roles.id
			$scope.estimate.tasks.push(Tasks.save($task, function(task) {
				$timeout( function() {
					EstimateEdit_JobRoles_Events_Setup($scope);
					$('input[data-taskid='+task.id+'].title-input').focus()
				},100)
			}))
			$scope.new_task=null

		}
	}
	$scope.remove_task = function (task,$index) {
		Tasks.delete({id:task.id})
		$scope.estimate.tasks.splice($index,1)
		$scope.estimate.tasks.show = $scope.estimate.tasks.length==0
	}
	$scope.changeHours = function (post) {
		Tasks.changeHours(post);
		$scope.$digest()
	}
	$scope.changeTaskname = function (post) {
		Tasks.save(post);
	}


	$scope.add_exp = function ($exp) {
		if (typeof($exp)==='undefined') {
			console.error('Please be careful! There is no expence to add!');
		} else {
			$scope.estimate.expences.show = false;

			$exp.estimate_id = $scope.estimate.id
			$scope.estimate.expences.push(Expences.save($exp, function(exp) {
				$timeout( function() {
					EstimateEdit_Expences_Events_Setup($scope);
					$('input[data-expid='+exp.id+'].title-input').focus()
				},100)
			}))
			$scope.new_exp=null
		}
	}
	$scope.remove_exp = function (exp,$index) {
		Expences.delete({id:exp.id})
		$scope.estimate.expences.splice($index,1)
		$scope.estimate.expences.show = $scope.estimate.expences.length==0
	}
	$scope.changeExp = function (post) {
		Expences.save(post);
	}

	$scope.open_bind_estimates = function($estimate) {
		$('#bound-other-estimates-modal').foundation('reveal', 'open');
	}
	$scope.close_bind_estimates = function() {
		$('#bound-other-estimates-modal').foundation('reveal', 'close');
	}
	$scope.bind_estimate = function(estimate_id) {
		console.log('bind_estimate');
		Estimates.save({
			id:estimate_id,
			bound_to_estimate_id:$scope.estimate.id,
		})
	}
	$scope.unbind_estimate = function(estimate_id) {
		console.log('unbind_estimate');
		Estimates.save({
			id:estimate_id,
			bound_to_estimate_id:' ',
		})
	}
	$scope.update_estimates = function() {
		Estimates.get({id:$routeParams.estimateId},function(estimate){
			estimate.tasks.show = estimate.tasks.length==0
			estimate.expences.show= estimate.expences.length==0
			estimate.group = EstimateTypes(estimate.group)

			angular.copy(estimate,$scope.estimate)
		})
		Projects.get({id:$routeParams.projectId},function(project){

			angular.copy(project,$scope.project)
			$timeout( function() {
				EstimateEdit_BindEstimates_Events_Setup($scope);
			},400)
		})
		console.log($scope.estimate);
	}


	$scope.estimateTypeHelper = function(indx) {
		var type = EstimateTypes(indx)
		return type.name
	}
	$scope.save_open_estimate = function() {
		$scope.estimate.project_id = $routeParams.projectId
		$scope.estimate.discount = 0
		$scope.estimate.group = $scope.selecttypes.selected.value

		estimate = Estimates.save($scope.estimate, function(){
			$location.path('clients/'+$routeParams.clientId+'/projects/'+$routeParams.projectId+"/estimates/"+estimate.id)
		})
	}

	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
		FoundationTabCallbackOverride();

		angular.element(document).ready(function () {
			console.log('IsReady::EstimatesEditController');
			EstimateEdit_JobRoles_Events_Setup($scope);
			EstimateEdit_Expences_Events_Setup($scope);
			EstimateEdit_BindEstimates_Events_Setup($scope);
	    });
	},400)
})



.controller('TasksMyController', function (LogedInUser, $scope, $rootScope, $routeParams, $timeout, $location, Users, JobRoles, UserTimeLogs, Tasks, Projects, FireBase) {
	LogedInUser.checkLoginStatuss()

	lastrefresh = moment()

	Auth = LogedInUser.get(function(){
		$scope.user = Users.get({id:Auth.id}, function(){
			$timeout( function() {
				LoadFoundation();
			},200);
		})
	})

	$scope.projects = Projects.query();
	$scope.jobroles = JobRoles.query();


	$scope.syncobject = FireBase
	$scope.syncobject.$on("change", function() {
		console.log('Sync:',lastrefresh.diff($scope.syncobject.refreshtime));
		if (lastrefresh.diff($scope.syncobject.refreshtime)<0 && ($scope.syncobject.foruser == $scope.user.id || $scope.syncobject.foruser==0)) {
			LogedInUser.checkLoginStatuss()

			Users.get({id:Auth.id}, function(user) {
				angular.copy(user, $scope.user);
			})
		}
	});
	
	var now  = moment()
	var week = [
		now.startOf('isoWeek'),
	]
	week[0].weekend = false;
	week[0].today   = week[0].isSame(moment(), 'day');

	for (var i=1;i<14;i++) {
		week.push( week[i-1].clone().add('d', 1) )
		week[i].weekend = (week[i].day()==6 || week[i].day()==0)?true:false;
		week[i].today   = week[i].isSame(moment(), 'day');
	}
	$scope.week = week
	$scope.hours = 0;
	
	$scope.taskinfo = function (event,task) {
		var id = task.id
		$timeout( function() {
			if (task.estimate_id!=null) {
				var tooltip = $(event.currentTarget).data('selector');
				var info = Tasks.getFullInfo({id:id}, function(){
					var html  = '<table>'
						html += '<tr><td class="col1"> Client: </td><td class="col2">'
						html += info.estimate.project.client.title
						html += '</td></tr>'
						html += '<tr><td class="col1"> Project: </td><td class="col2">'
						html += info.estimate.project.title
						html += '</td></tr>'
						html += '</table>'
						html += '<span class="nub"></span>'
					$('.tooltip[data-selector="'+tooltip+'"]').html(html)
				});
			} else {
				var tooltip = $(event.currentTarget).data('selector');
				var html  = '<div>You Added this task..</div>'
					html += '<span class="nub"></span>'
				$('.tooltip[data-selector="'+tooltip+'"]').html(html)
			}
		},400)
	}
	$scope.open_log_select = function (passobject) {
		$scope.passobject = passobject;
		console.log('PO1',$scope.passobject);
		if(passobject.day.weekend) return;
		$('.app-time-select-modal').foundation('reveal', 'open');
	}
	$scope.close_log_select = function () {
		$('#time-select-modal').foundation('reveal', 'close');
		delete $scope.passobject
	}
	$scope.add_log = function () {
		var minutes = parseInt($scope.hours)*60 + parseInt($scope.minutes)
		var PO = $scope.passobject
		console.log('PO2',PO);
		// console.log(PO); return;
		if(PO.day.weekend) return;
		if (Auth.loggedin) {
			timelog = UserTimeLogs.save({
				id:PO.timelog.id,
				user_id:Auth.id,
				taskrole_id:PO.taskrole.id,
				time:PO.day,
				minutes:minutes,
				comment:PO.comment,
			})
			if(PO.timelog.id) {
				PO.taskrole.usertimelogs[PO.timelog.index].minutes = minutes
				PO.taskrole.usertimelogs[PO.timelog.index].comment = PO.comment
			} else {
				PO.taskrole.usertimelogs.push(timelog)
			}
			$scope.close_log_select()
		} else {
			console.error('This is weard.. how did you get this far without logging in?');
		}
	}
	$scope.add_rouge_task = function () {
		try {
			$scope.newTask.jobroles = [$scope.newTask.jobroles_o.id]
			$scope.newTask.project_id = $scope.fuzzy.selected

			Tasks.save($scope.newTask, function(task){
				$('#user-add-new-task-modal').foundation('reveal', 'close');
				Users.bindToTask({
					id:Auth.id,
					taskrole:task.jobroles[0].pivot.id
				}, function() {
					Users.get({id:Auth.id}, function(user) {
						angular.copy(user, $scope.user);
					})
				})
			}, function() {
				throw "Something went wrong"
			})
		} catch(err) {
			$('.app-user-add-new-task-modal .modal-error-area').fadeOut(0).html("<h5>There was an Error!</h5><p>Please Check if all the fields are filled...</p>").fadeIn(400)
		} 
	}
	$scope.open_task_add = function() {
		$('#user-add-new-task-modal').foundation('reveal', 'open');
	}
	$scope.refresh_fuzzysearch = function() {
		$('.app-user-add-new-task-modal .fuzzy-searchresults table').fadeIn(200)
		$scope.fuzzy.result = $scope.Fuse.search($scope.fuzzy.searchstring);
	}
	$scope.set_fuzzysearch = function(project) {
		$('.app-user-add-new-task-modal .fuzzy-searchresults table').fadeOut(200)
		$('.app-user-add-new-task-modal .postfix').addClass('success').find('i').fadeIn(200)
		$scope.fuzzy.searchstring = project.client.title+' '+project.title
		$scope.fuzzy.selected = project.id
	}
	
	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
		DashboardMy_Dragger_Setup($scope);
	},500)
})

.controller('DashboardController', function (LogedInUser, $scope, $rootScope, $routeParams, $timeout, $location, Dashboard, Users, Tasks, FireBase) {
	LogedInUser.checkLoginStatuss()
	$scope.total = {
		done_m:0,
		sold_m:0
	}
	Dashboard.getProjects(function(projects){
		console.log(projects);
		for (key in projects) {
			if ( !isNaN( parseInt(key) )) {
				$scope.total.done_m += projects[key].done_m
				$scope.total.sold_m += projects[key].sold_m
			}
		}
		$scope.projectList = projects;

		$timeout( function() {
			Dashboard_OnClick_Setup();
		},100)
	})
	
	Dashboard.getChartData(function(data){
		$scope.chartdata = data;
		Dashboard_D3($scope);
	})

	Dashboard.getAllEstimates(function(estimates){
		$scope.estimatesList = estimates
		DashboardCascade_OnClick_Setup();
	})

	$scope.users = Users.query(function(users) {
	})
	var now  = moment()
	var week = [
		now.startOf('isoWeek'),
	]
	week[0].weekend = false;
	week[0].today   = week[0].isSame(moment(), 'day');

	for (var i=1;i<14;i++) {
		week.push( week[i-1].clone().add('d', 1) )
		week[i].weekend = (week[i].day()==6 || week[i].day()==0)?true:false;
		week[i].today   = week[i].isSame(moment(), 'day');
	}
	$scope.week = week
	$scope.hours = 0;

	// $scope.project = Dashboard.getTaskroles({id:1})

	$scope.refreshProject_Details = function(event,id) {
		$('.project-details.clone').remove()

		if ($(event.currentTarget).parent('tr').hasClass('project-open')) {
			var $details = $('.project-details');

			// $details.height($details.height())
			$details.addClass('closed')
			$(event.currentTarget).parent('tr').removeClass('project-open')
		} else {
			$(event.currentTarget).parents('table').find('.project-open').removeClass('project-open')
			$(event.currentTarget).parent('tr').addClass('project-open')

			$details_clone = $('.real .project-details.loading').clone();
			$details_clone.removeClass('open').addClass('closed clone')
			$(event.currentTarget).parent('tr').after($details_clone)
			$details_clone.removeClass('closed').addClass('open')
		}

		$scope.project = Dashboard.getTaskroles({id:id}, function(project){
			$timeout( function() {
				var $details = $('.real .project-details');
				$details_clone.html( $details.html() ).removeClass('loading')
				DashboardCascade_OnClick_Setup();
			},200)
		})
	}
	$scope.taskinfo = function (event,task) {
		var id = task.id
		$timeout( function() {
			if (task.estimate_id!=null) {
				var tooltip = $(event.currentTarget).data('selector');
				var info = Tasks.getFullInfo({id:id}, function(){
					var html  = '<table>'
						html += '<tr><td class="col1"> Client: </td><td class="col2">'
						html += info.estimate.project.client.title
						html += '</td></tr>'
						html += '<tr><td class="col1"> Project: </td><td class="col2">'
						html += info.estimate.project.title
						html += '</td></tr>'
						html += '</table>'
						html += '<span class="nub"></span>'
					$('.tooltip[data-selector="'+tooltip+'"]').html(html)
				});
			} else {
				var tooltip = $(event.currentTarget).data('selector');
				var html  = '<div>You Added this task..</div>'
					html += '<span class="nub"></span>'
				$('.tooltip[data-selector="'+tooltip+'"]').html(html)
			}
		},400)
	}

	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
		FoundationTabCallbackOverride();
	},500)
})






.controller('ExitController', function (LogedInUser, $scope, $rootScope, $location, $timeout, FireBase) {
	LogedInUser.checkLoginStatuss()
	$timeout( function() {

		// @from interaction.js
		e404Loaded();
	})
})

.controller('ManualController', function (LogedInUser, $scope, $rootScope, $location, $timeout, FireBase) {
	LogedInUser.checkLoginStatuss()
	$timeout( function() {

		// @from interaction.js
		ManualsLoaded();
	})
})

.controller('e404Controller', function (LogedInUser, $scope, $rootScope, $location, $timeout, FireBase) {
	LogedInUser.checkLoginStatuss()
	$timeout( function() {

		// @from interaction.js
		e404Loaded();
	})
})

.controller('BreadCrumbs', function (LogedInUser, $scope, $rootScope, $routeParams, $location, $timeout) {
	LogedInUser.checkLoginStatuss()

	$scope.$watch('translatedIds', function(newValue,oldValue) {
		for (idx in $scope.url) {
			for (new_idx in newValue) {
				if ($scope.url[idx].context.search(new_idx)!=-1) $scope.url[idx].name = newValue[new_idx]
			}
		}
	},true)

	$scope.$on('$locationChangeStart', function(){
		$scope.translatedIds = {}
		var urls = $location.path().split('/'),
			href = '#'

		$scope.url = []
		
		for (key in urls) {
			href += urls[key]+'/'
			name  = urls[key]
			context  = ""

			if (!isNaN(parseInt(urls[key])) ) {
				context = urls[key-1]
			}

			$scope.url.push({
				name:name,
				context:context,
				href:href,
				disabled:(urls[key]=='edit' || urls[key]=='new' )?true:false
			})
		}
	})
	$scope.$on('setBreadcrumb', function(event,object) {
		for (idx in object) {
			$scope.translatedIds[idx] = object[idx]
		}
    });

	$timeout( function() {
		// @from interaction.js
		// 
	},0)
})










