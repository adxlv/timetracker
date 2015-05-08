

/* Controllers */

var app = angular.module('Timetracker.controllers', [])
 
.controller('IndexController', function ($scope, $rootScope, $location, $state) {
	var locc = $location.$$absUrl
	
	//CodeKit ckcachecontrol remove
	var codeKit = new RegExp("\\?ckcachecontrol=\\d*");
	locc = locc.replace(codeKit,'')
	//thass all...

	var patt = new RegExp(".*"+$location.$$host+"(.*:.*?)?/");
	locc = locc.replace(patt,'')
	locc = locc.replace('#'+$location.$$path,'')
	console.log('IndexController',locc,$location);

	if (locc === 'desktopapp/dash') $state.go('tasks')

})


.controller('ClientsController', function (LogedInUser, $location, $state, $scope, $rootScope, $stateParams, $timeout,$interval, Clients) {
	LogedInUser.checkLoginStatuss()

	
	$scope.stateParams = $stateParams
	$scope.filters = {
		archived: false,
	}
	
	//Check if is in view //
		if (typeof $state.current.views === 'undefined') {
			$scope.weareinview = false
			$scope.clients = Clients.query(function(){
				$timeout( function() {
					Projects_BindOnhoverEffects()
				},100)
			})
		} else {
			$scope.weareinview = true
			$scope.clients = Clients.getMyProjects(function(){
				$timeout( function() {
					Projects_BindOnhoverEffects()
				},100)
			})
		}
	
	$scope.destroy = function($id) {
		Clients.delete({id:$id}, function() {
			for (var i = $scope.clients.length - 1; i >= 0; i--) {
				if ($scope.clients[i].id == $id) $scope.clients.splice(i,1)
			};
			if ($state.params.clientId == $id) $state.go('clients')
		})
    };
    $scope.archiveClient = function(c_id) {
    	for (var i = $scope.clients.length - 1; i >= 0; i--) {
			if ($scope.clients[i].id == c_id) {
				var index = i
				var client = $scope.clients[i]
			}
		};

		if (client.archived_by==null) {
			client.archive = true;
		} else {
			client.archive = false;
		}

		console.log('C::', client,c_id,index);
		Clients.save(client, function(client) {
			if ($scope.filters.archived) {
				$('.client-'+client.id).toggleClass('archived')
			} else {
				$scope.clients.splice(index,1)
			}
		})
	};

	$scope.gotoProjects = function($id) {
		$location.path('/clients/'+$id+'')
	}
	$scope.filterArchived = function(isActive) {
		if (!isActive) {
			$scope.filters.archived = true;
			$scope.clients = Clients.query({
				archived: $scope.filters.archived
				},function(){
				$timeout( function() {
					Projects_BindOnhoverEffects()
				},100)
			})
		} else {
			$scope.filters.archived = false;
			$scope.clients = Clients.query({
				archived: $scope.filters.archived
				},function(){
				$timeout( function() {
					Projects_BindOnhoverEffects()
				},100)
			})
		}
	}

	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
		All_FilterMenuOpen_Setup();

	})

	// $interval (function() {
	// 	console.log('Interval',$state.is('clients'));
	// 	// $state.reinit()
	// },2000)
})
.controller('ClientsEditController', function (LogedInUser, $scope, $rootScope, $stateParams, $timeout, Clients, $state) {
	LogedInUser.checkLoginStatuss()

	var link = $stateParams.clientId || ""
	$scope.link = (parseInt(link))?'':link

	$scope.client = Clients.get({id:link})

    $scope.save = function() {
    	$scope.client.$save(function(client){
    		if (parseInt(link)) {
    			for (var idx=0; idx<$scope.clients.length; idx++) {
    				if ($scope.clients[idx].id==client.id) {
    					$scope.clients[idx].title = client.title
    					$scope.clients[idx].color = client.color
    				}
    			}
				$state.go('clients')
    		} else {
    			client.new = true;
	    		$scope.clients.push(client)
				$state.go('clients')
    		}
    	});
  	};

	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
	})
})

.controller('ProjectsController', function (LogedInUser, $scope, $rootScope, $stateParams, $timeout, $interval, Clients, Projects, $location, $uiViewScroll) {
	LogedInUser.checkLoginStatuss()
	$scope.loggedinuser = LogedInUser.get()
	$scope.stateParams = $stateParams

	$scope.client = Clients.get({id:$stateParams.clientId},function(client){
		//$rootScope.$broadcast('setBreadcrumb',{client:client.title})
	})

	$scope.projects = Projects.query({
		archived : false,
		client_id : $stateParams.clientId
	}, function(data){
		$timeout( function() {
			Projects_BindOnhoverEffects()
		})
		console.log(data);
	})

	$scope.filters = {
		archived: false,
	}

    $scope.destroy = function($id) {
		Projects.delete({id:$id}, function() {
			for (var i = $scope.projects.length - 1; i >= 0; i--) {
				if ($scope.projects[i].id == $id) $scope.projects.splice(i,1)
			};
		})
    };
    $scope.archiveProject = function(p_id) {
    	for (var i = $scope.projects.length - 1; i >= 0; i--) {
			if ($scope.projects[i].id == p_id) {
				var index = i
				var project = $scope.projects[i]
			}
		};

		if (project.archived_by==null) {
			project.archive = true;
		} else {
			project.archive = false;
		}

		// console.log('P::', project,p_id,index);
		Projects.save(project, function(project) {
			if ($scope.filters.archived) {
				$('.project-'+project.id).toggleClass('archived')
			} else {
				$scope.projects.splice(index,1)
			}
		})
	};
    $scope.gotoProject = function($project) {
		$location.path('/clients/'+$scope.client.id+'/projects/'+$project)
	}

	$scope.filterArchived = function(isActive) {
		if (!isActive) {
			$scope.projects = Projects.query({
				client_id : $stateParams.clientId,
				archived : true
			}, function(data){
				$timeout( function() {
					Projects_BindOnhoverEffects()
				})
			})
			$scope.filters.archived = true;
		} else {
			$scope.projects = Projects.query({
				client_id : $stateParams.clientId,
				archived : false
			}, function(data){
				$timeout( function() {
					Projects_BindOnhoverEffects()
				})
			})
			$scope.filters.archived = false;
		}
	}
	


	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
		FoundationTabCallbackOverride();
		Projects_BindOnhoverEffects()
		All_FilterMenuOpen_Setup();

		console.log('Scrolling',$(document).scrollTop());
		$uiViewScroll($('.app-top'))

	},300)

	// $interval (function() {
	// 	console.log('Interval');
	// 	$scope.$apply()
	// },5000)
})
.controller('ProjectsEditController', function (LogedInUser, $scope, $rootScope, $stateParams, $timeout, Clients, Projects, $state) {
	LogedInUser.checkLoginStatuss()
	var link = $stateParams.projectId || ""
	$scope.link = (parseInt(link))?'':link


	$scope.project = Projects.get({id:link})

    $scope.save = function() {
    	$scope.project.client_id = $stateParams.clientId
    	Projects.save($scope.project, function(project) {
	    	if (parseInt(link)) {
				for (var idx=0; idx<$scope.projects.length; idx++) {
					if ($scope.projects[idx].id==project.id) {
						$scope.projects[idx].title = project.title
					}
				}
				$('#project-edit-modal').foundation('reveal', 'close');
			} else {
				console.log('Push project',project);
	    		$scope.projects.push(project)
    			$('#project-edit-modal').foundation('reveal', 'close');
			}
		});
  	};

  	$scope.back = function() {
  		$('#project-edit-modal').foundation('reveal', 'close');
  	}

	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
		FoundationTabCallbackOverride();

		$('#project-edit-modal').foundation('reveal', 'open');
		$(document).on('closed.fndtn.reveal', '[data-reveal]', function () {
			$state.go('clients.projects',{clientId:$stateParams.clientId})
		})

	},300)
})
.controller('ProjectsOpenController', function ($log, LogedInUser, $scope, $rootScope, $stateParams, $timeout, $location, Clients, Projects, Estimates, Users, Tasks, FireBase, EstimateTypes) {


	LogedInUser.checkLoginStatuss()
	var link = $stateParams.projectId || ""

	// $scope.client     = Clients.get({id:$stateParams.clientId}, function(client){
	// 	$rootScope.$broadcast('setBreadcrumb',{client:client.title})
	// })
	$scope.project    = Projects.get({id:$stateParams.projectId}, function(project){
		$rootScope.$broadcast('setBreadcrumb',{project:project.title})
	})

	// $scope.rougetasks = Projects.queryRougeTasks({id:$stateParams.projectId})



	$scope.syncobject = FireBase

  	

	$timeout( function() {
		// @from interaction.js
		AngularTabClickOverride();
	},100)	
})

.controller('TasksController', function ($log, $state, LogedInUser, $scope, $rootScope, $stateParams, $timeout, $location, Clients, Projects, Estimates, JobRoles, Users, Tasks, FireBase, EstimateTypes) {

	LogedInUser.checkLoginStatuss()
	$scope.user_filter = {
		search: ' '
	}

	$scope.tasks = Projects.queryTasks({id:$stateParams.projectId}, function(tasks) {
		tasks = _(tasks).each( function( task, key, list ) {
			task.users = [];
			_(task.taskrolebinds).each(function(rolebind){
				_(rolebind.users).each(function(user){
					user.taskrolebinds_id = user.pivot.taskrolebinds_id
					task.users.push(user)
				})
			})
		})

		$timeout( function() {
			LoadFoundation();
			ProjectsOpen_Display_Setup();
			ProjectsOpen_Click_Setup($scope);

			Projects_BindOnhoverEffects();
			Tasks_ShowHideDone_Click_Setup();
		},100)
	});
	$scope.task  = {}
	$scope.users = Users.query(function(users){
		users = _(users).each(function(user){
			user.search = user.name + " " + user.surname
		})
		console.log(users);
	})
	$scope.jobroles = JobRoles.query()

	$scope.project = Projects.get({id:$stateParams.projectId}, function(project){
		$rootScope.$broadcast('setBreadcrumb',{project:project.title})
	})


	// WATCHES
	$scope.$watch('local_user_filter', function(newValue, oldValue) {
		console.log('Changed');
	}, true);


	$scope.open_task = function(task,$index) {
		$state.go('project.tasks');

		Tasks.get({id:task.id}, function(task) {
			angular.copy(task,$scope.task)
			console.log('T::',$scope.task);
			
			$('#bind-user-new-modal').foundation('reveal', 'open');
			$timeout( function() {
				// ProjectsOpen_BindUserRole_Click_Setup($scope)
			},100)
			
		})
	};
	$scope.done_task = function(id,isDone) {
		// $scope.syncobject.pushrefresh = true;
		// $scope.syncobject.foruser = 0;
		// $scope.syncobject.refreshtime = moment().valueOf();
		// $scope.syncobject.$save();
		var $task = {
			id:id,
			done:isDone?1:0,
		}
		Tasks.save($task)
	};
	$scope.try_binding = function (event,taskroleid,userid) {

		var me = $(event.target)

		if ($(me).hasClass('secondary')) {
			$(me).removeClass('secondary')
			$scope.bind_role_user(taskroleid,userid)
		} else {
			$(me).addClass('secondary')
			$scope.unbind_role_user(taskroleid,userid)
		}

		console.log('DOING BINDING INSIDE!');		
	}
	$scope.bind_role_user = function (user,taskrole) {

		// $scope.syncobject.pushrefresh = true;
		// $scope.syncobject.foruser = user;
		// $scope.syncobject.refreshtime = moment().valueOf();
		// $scope.syncobject.$save();

		var rolebind = taskrole || $scope.open_rolebind
		if (typeof rolebind === 'undefined' || rolebind==null) {
			console.log('Wha?! No Current Rolebind');
			return;
		}

		// console.log("BINDING::",user,rolebind,$scope.tasks);

		if (typeof rolebind.users === 'undefined') rolebind.users = [];
		for (var i = rolebind.users.length - 1; i >= 0; i--) {
			if (rolebind.users[i].id == user.id) {
				console.log('Wha?! User already is there');
				return;
			}
		};

		Users.bindToTask({taskrole:rolebind.id,id:user.id},function(user) {
			rolebind.users.push(user)

			// Add user to user list in task list
			for (var i = $scope.tasks.length - 1; i >= 0; i--) {
				if ($scope.tasks[i].id == rolebind.task_id) {
					$scope.tasks[i].users.push(user)
				}
			};
			// ---
		})
	};
	$scope.unbind_role_user = function (taskrole,user) {
		// $scope.syncobject.pushrefresh = true;
		// $scope.syncobject.foruser = user;
		// $scope.syncobject.refreshtime = moment().valueOf();
		// $scope.syncobject.$save();
		
		var taskrole_id = taskrole.id
		var user_id = user.id

		console.log(taskrole);

		Users.unbindToTask({taskrole:taskrole_id,id:user_id},function(got_user) {
			
			// Remove user to user list in task list
			_($scope.tasks).each(function(task){
				if (task.id == $scope.task.id) {
					_(task.users).each(function(user,key){
						if (user.id == got_user.id && user.taskrolebinds_id == taskrole_id) task.users.splice(key,1)
					})
				}
			})
			// ---

			for (var i = taskrole.users.length - 1; i >= 0; i--) {
				if (taskrole.users[i].id == user_id) taskrole.users.splice(i,1)
			};

			$timeout( function() {
				ProjectsOpen_Display_Setup();
				// ProjectsOpen_Click_Setup($scope);
			},200)
		})
	};

	$scope.filter_focus = function(event) {
		var pos = $(event.target).offset()
		var elScope = $(event.target).scope()

		pos.top += $(event.target).parent().height() + 2

		$('#users-popup').finish().fadeIn(100,function() {
			// $(this).css('height',$(this).data('height'))
		}).offset(pos)

		$scope.open_rolebind = elScope.rolebind
		// console.log('elSCOPE::',elScope);
		// 

		$(event.target).off('blur').blur(function(){
			$('#users-popup').finish().fadeOut(100,function(){
				// $(this).css('height',0)
			})
			$(this).off('blur')
			$scope.user_filter = {}

			
				elScope.local_user_filter = {}
				elScope.$digest();

			// console.log('elSCOPE::',elScope);
		})
	}
	$scope.filter_change = function(event,local_user_filter) {
		// console.log(local_user_filter,$scope);
		$scope.user_filter = {
			search : local_user_filter.search
		}
	}

	$timeout( function() {
		// @from interaction.js
		// LoadFoundation();
		// ProjectsOpen_Display_Setup();
		// ProjectsOpen_Click_Setup($scope);

		// Projects_BindOnhoverEffects();
		// Tasks_ShowHideDone_Click_Setup();
		
		// $(document).on('opened.fndtn.reveal', '[data-reveal]', function () {
		//   var modal = $(this);
		//   // ProjectsOpen_BindUserRole_Click_Setup($scope)
		//   Tasks_BindUsersModal_Setup()
		// });
	},500)

	// $scope.$on('$viewContentLoaded', function() {
	// 	console.log('Calling::$viewContentLoaded');

	// 	LoadFoundation();
	// 	ProjectsOpen_Display_Setup();
	// 	ProjectsOpen_Click_Setup($scope);

	// 	Projects_BindOnhoverEffects();
	// 	Tasks_ShowHideDone_Click_Setup();
	// });
})
.controller('TasksInfoController', function ($log, $state, LogedInUser, $scope, $rootScope, $stateParams, $timeout, $location, Clients, Projects, Estimates, Users, Tasks, FireBase, EstimateTypes, JobRoles) {

	$scope.task = Tasks.get({id:$stateParams.taskId}, function(task) {
		task.original_title = task.title
		_(task.taskrolebinds).each(function(rolebind){
			rolebind.total_m = 0
			_(rolebind.users).each(function(user){
				user.total_m = 0
				_(user.timelogs).each(function(log){
					rolebind.total_m+=parseInt(log.minutes);
					user.total_m+=parseInt(log.minutes);
				})
			})
		})
		console.log(task);
	})
	
	$scope.estimates  = Projects.queryEstimates({id:$stateParams.projectId}, function(estimates) {
		_id = (typeof estimates[0] !== 'undefined')?estimates[0].id:null
		$scope.new_task = {
			estimate_id : _id
		}
	})

	$scope.save_task_info = function() {
		Tasks.save($scope.task,function(task){
			for (var i = $scope.tasks.length - 1; i >= 0; i--) {
				if ($scope.tasks[i].id == task.id) {
					$scope.tasks[i].title = task.title
				}
			};
			$('.info-edit-row').removeClass('show');
		}) 
	}
	$scope.cancel_edit = function() {
		$scope.task.title = $scope.task.original_title
		$('.info-edit-row').removeClass('show');
	}


	$timeout( function() {
		// @from interaction.js
		Tasks_Info_Show_Edit();

	},200)
})
.controller('TasksNewController', function ($log, $state, LogedInUser, $scope, $rootScope, $stateParams, $timeout, $location, Clients, Projects, Estimates, Users, Tasks, FireBase, EstimateTypes, JobRoles) {
	$scope.loggedinuser = LogedInUser.get()
	$scope.project    = Projects.get({id:$stateParams.projectId})
	$scope.estimates  = Projects.queryEstimates({id:$stateParams.projectId}, function(estimates) {
		_id = (typeof estimates[0] !== 'undefined')?estimates[0].id:null
		$scope.new_task = {
			estimate_id : _id
		}
	})
	$scope.jobroles = JobRoles.query({},function(jobroles){
		$scope.jobroles.ids = _.pluck(jobroles,'id');
	})

	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
		$('#new-task-modal').foundation('reveal', 'open');
		$(document).on('closed.fndtn.reveal', '[data-reveal]', function () {
			$state.go('project.tasks')
		})
	},200)

	$timeout( function() {
		console.log($scope.jobroles);
	},500)


	$scope.save_task = function() {
		$scope.new_task.project_id = $stateParams.projectId
		$scope.new_task.jobroles = $scope.jobroles.ids
		console.log($scope.new_task);
		Tasks.save($scope.new_task, function(newtask){
			// $state.go("^")
			$('#new-task-modal').foundation('reveal', 'close');
			$scope.task = newtask;
			$scope.task.users = []
			
			$scope.tasks.push(newtask)
			$scope.bind_role_user($scope.loggedinuser,newtask.taskrolebinds[0])

		});

		
		// estimate = Estimates.save($scope.estimate, function(){
		// 	// $location.path('clients/'+$stateParams.clientId+'/projects/'+$stateParams.projectId+"/estimates/"+estimate.id)
		// 	$state.go( 'project.estimates.edit', {estimateId:estimate.id} )
		// })
	}
})


.controller('EstimatesController', function ($log, $state, LogedInUser, $scope, $rootScope, $stateParams, $timeout, $location, Clients, Projects, Estimates, Users, Tasks, FireBase, EstimateTypes) {
	
	$scope.project    = Projects.get({id:$stateParams.projectId}, function(project){
		$rootScope.$broadcast('setBreadcrumb',{project:project.title})
	})

	$scope.estimates  = Projects.queryEstimates({id:$stateParams.projectId}, function(estimates) {
		
		$scope.estimates = _($scope.estimates).each( function( estimate, key, list ) {
			estimate.group = EstimateTypes(estimate.group)
			estimate.bound = _(estimate.bound).each(function(bound_estimate){
				bound_estimate.group = EstimateTypes(bound_estimate.group)
			})
			// estimate.bound = []
		})


		$timeout( function() {
			
			// console.log($scope.tasks);
		},500)
	})

	$scope.open_estimate = function(estimate,event) {
		$scope.opened_estimate = estimate
		if (estimate=='new') {
			$state.go('project.estimates.edit',{estimateId:'new'})
  			// $location.path('clients/'+$stateParams.clientId+'/projects/'+$stateParams.projectId+'/estimate/new') 
		} else {
			$state.go('project.estimates.info',{estimateId:estimate.id})
  			// $location.path('clients/'+$stateParams.clientId+'/projects/'+$stateParams.projectId+'/estimates/'+$id) 
		}
	};
	$scope.open_estimate_dbl = function(estimate,event) {
		console.log('DOUNBLE CLICK!!!');
		$scope.opened_estimate = estimate
		$state.go('project.estimates.edit',{estimateId:estimate.id})
	};

	$scope.close_estimate = function($id) {};

	$scope.expand_estimate = function(subestimate,estimate,event) {
		event.stopPropagation();
		for (var i = $scope.estimates.length - 1; i >= 0; i--) {
  			if ($scope.estimates[i].id == estimate.id) {
  				var main_est_i = i
  			}
  		};
		for (var i = estimate.bound.length - 1; i >= 0; i--) {
  			if (estimate.bound[i].id == subestimate.id) {
  				var sub_est_i = i
  			}
  		};
  		estimate.bound.splice(sub_est_i,1)
  		$scope.estimates.splice(main_est_i+1,0,subestimate)

  		$scope.unbind_estimate(subestimate.id)
	};


  	$scope.delete_estimate = function(estimate,event) {
  		event.stopPropagation();
  		for (var i = $scope.estimates.length - 1; i >= 0; i--) {
  			if ($scope.estimates[i].id == estimate.id) {
  				var index = i
  			}
  		};
  		Estimates.delete({id:estimate.id}, function(){
  			$scope.estimates.splice(index,1)
  		})
  	};

  	$scope.bind_estimate = function(which_est_id,toWitch_est_id) {
		console.log('bind_estimate');
		Estimates.save({
			id:which_est_id,
			bound_to_estimate_id:toWitch_est_id,
		})
	}
	$scope.unbind_estimate = function(estimate_id) {
		console.log('unbind_estimate');
		Estimates.save({
			id:estimate_id,
			bound_to_estimate_id:'null',
		})
	}

  	//Sortable Options
	$scope.fullListOptions = {
		handle: ".handle",
		appendTo: ".estimate-list",
		// tolerance: "pointer",
		connectWith: ".subestimates",
		// forceHelperSize: true,
		// forcePlaceholderSize: true,
		// placeholder: "drag-placeholder",
		helper: "clone",
		cursorAt: { 
			// top: 100,
			left: 150,
		},
		// containment: ".estimate-list",
		start: function(event, ui) {
			console.log('Got fullList');
			// $('.ui-sortable-helper').removeClass('estimate-page-group')
		},
		stop: function(event, ui) {
			//BUG Fix: by each change some top margins are calculated wrong by browser
			$('.sortable').toggleClass('fix-display')
		},
		receive: function(event, ui) {
			console.log('Receive fulllist');
		},
		over: function(event, ui) {
			console.log('Over fullList');
		}, 
	};
	$scope.shortListOptions = {
		handle: ".handle",
		appendTo: ".subestimates",
		tolerance: "pointer",
		forceHelperSize: true,
		forcePlaceholderSize: false,
		placeholder: "small-drag-placeholder",
		start: function(event, ui) {
			// console.log('Got shortList');
		},
		stop: function(event, ui) {
			//BUG Fix: by each change some top margins are calculated wrong by browser
			$('.sortable').toggleClass('fix-display')
		},
		receive: function(event, ui) {
			$item = $(ui.item).find('.estimate').addClass('subestimate')
			var which_est_id = $item.data('estimate-id')
			var toWitch_est_id = $(event.target).parent().find('.estimate').data('estimate-id')
			// console.log('Receive shortlist',which_est_id,toWitch_est_id);
			$scope.bind_estimate(which_est_id,toWitch_est_id)
		},
		over: function(event, ui) {
			// console.log('Over shortList');
			$(event.target).parents('.estimate-page-group').addClass('hover')
		}, 
		out: function(event, ui) {
			// console.log('Out shortList');
			$(event.target).parents('.estimate-page-group').removeClass('hover')
		}, 
	};

	//Events 
	$scope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams){
			Estimate_SetActive(toParams.projectId);
	})

  	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
	},100)


})
.controller('EstimatesInfoController', function ($log, $state, LogedInUser, $scope, $rootScope, $stateParams, $timeout, $location, Clients, Projects, Estimates, Users, Tasks, FireBase, EstimateTypes) {

	$scope.selecttypes = {
		list     : EstimateTypes(),
		selected : EstimateTypes(0)
	}

	// $state.is('contact.details.item')

	$scope.estimate = Estimates.get({id:$stateParams.estimateId},function(estimate){
		estimate.totals = {
			hours:0,
			summ:0,
		};

		estimate.entries.show = estimate.entries.length==0
		estimate.expences.show= estimate.expences.length==0
		estimate.group = EstimateTypes(estimate.group)

		estimate.headers = _(estimate.entries).where({is_header:1}) || {sortorder:9999}
			if (estimate.headers.length == 0) estimate.headers = [{
				sortorder:9999,
				title:'All Tasks'
			}]

		_(estimate.headers).each( function( header, key ) {
			header = summarizeHeader(header,estimate.entries,estimate.involved_roles.salary)
			
			estimate.totals.hours += header.totals.hours
			estimate.totals.summ += header.totals.summ

		})


		estimate.totals.tax_percent = (estimate.branch.type=='micro')?0.09:0.21
		estimate.totals.tax = estimate.totals.summ*estimate.totals.tax_percent


		$timeout( function() {
			Estimate_SetActive($scope.estimate.id)
		})
	},function(){
	})

	$timeout(function(){
	},500)

	function summarizeHeader(header,entrieslist,estimate_salary_list) {
		_summLooper = function(entry) {
			var total = 0
			for (var i = entry.hours.length - 1; i >= 0; i--) {
				total += entry.hours[i]*estimate_salary_list[i]
			};
			return total;
		}
		_hoursLooper = function(entry) {
			var total = 0
			for (var i = entry.hours.length - 1; i >= 0; i--) {
				total += entry.hours[i]
			};
			return total;
		}
		var stop_me = false;

		header.totals = {
			summ : 0,
			hours : 0,
		}

		console.log('New Loop::');

		_(entrieslist).each( function( entry, key, entrieslist ) {
			if (entry.sortorder >= header.sortorder) {
			
			} else if (entry.is_header == 1 ) {
				stop_me = true;
			} else if (!stop_me) {
				header.totals.summ += _summLooper(entry);
				header.totals.hours += _hoursLooper(entry);
			}
		})

		return header;		
	}
})
.controller('EstimatesNewController', function ($log, $state, LogedInUser, $scope, $rootScope, $stateParams, $timeout, $location, Clients, Projects, Estimates, Users, Tasks, FireBase, EstimateTypes) {

	$scope.selecttypes = {
		list     : EstimateTypes(),
		selected : EstimateTypes(0)
	}

	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
		$('#new-estimate-modal').foundation('reveal', 'open');
		$(document).on('closed.fndtn.reveal', '[data-reveal]', function () {
			console.log('IsSaved::',$scope.saved_open_estimate);
			if ($scope.saved_open_estimate>-1) {
				$state.go( 'project.estimates.edit', {estimateId:$scope.saved_open_estimate} )
				$scope.saved_open_estimate = -1
			} else {
				$state.go('project.estimates')
			}
		})
	},200)

	$scope.saved_open_estimate = -1
	$scope.save_open_estimate = function() {
		$scope.estimate.project_id = $stateParams.projectId
		$scope.estimate.discount = 0
		$scope.estimate.group = $scope.selecttypes.selected.value

		Estimates.save($scope.estimate, function(estimate){
			$scope.saved_open_estimate = estimate.id

			estimate.group = EstimateTypes(estimate.group)

			var ind = $scope.estimates.push(estimate)
			$scope.opened_estimate = $scope.estimates[ind-1]
			
			$('#new-estimate-modal').foundation('reveal', 'close');
		})
	}
})
.controller('EstimatesEditController', function (LogedInUser, $state, $scope, $rootScope, $stateParams, $timeout, $location, Clients, Projects, JobRoles, Estimates, EstimateEntries, Expences, EstimateTypes, FireBase) {
	LogedInUser.checkLoginStatuss()



	// $scope.client    = Clients.get({id:$stateParams.clientId}, function(client){
	// 	$rootScope.$broadcast('setBreadcrumb',{client:client.title})
	// })
	$scope.project   = Projects.get({id:$stateParams.projectId}, function(project){
		$rootScope.$broadcast('setBreadcrumb',{project:project.title})
	})

	$scope.selecttypes = {
		list     : EstimateTypes(),
		selected : EstimateTypes(0)
	}
	
	$scope.estimate = Estimates.get({id:$stateParams.estimateId},function(estimate){
		estimate.entries.show = estimate.entries.length==0
		estimate.expences.show= estimate.expences.length==0
		estimate.group = EstimateTypes(estimate.group)

		console.log(estimate);
		$timeout( function() {
			Projects_BindOnhoverEffects('.context-menu');
		})
	},function(){
	})
	$scope.jobroles = JobRoles.query()
	// $scope.tasks    = Tasks.query()

	$scope.syncobject = FireBase


	console.log('Estimate:',$scope.estimate);



	// WATCHES
	$scope.$watch('estimate.entries', calculateTotal, true);
	$scope.$watch('estimate.involved_roles.salary', calculateTotal, true);
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
	function calculateTotal (newValue, oldValue) {
		this.pushReorderTimeout = this.pushReorderTimeout | false
		var tasks_total = 0
		var push_reorder = false
		var eval = 0,
			var1 = 0,
			var2 = 0

		if ( typeof newValue !== 'undefined' && typeof oldValue !== 'undefined')
		if ( typeof newValue[0] === 'object') {
			for (var i = newValue.length - 1; i >= 0; i--) {
				if (typeof oldValue[i] !== 'undefined')
				if (newValue[i].id!=oldValue[i].id && !push_reorder) push_reorder = true
			};
		}

		for (e_idx in $scope.estimate.entries) {
			for (h_idx in $scope.estimate.entries[e_idx].hours) {
				var1 = $scope.estimate.entries[e_idx].hours[h_idx] || 0
				var2 = $scope.estimate.involved_roles.salary[h_idx] || 0
				eval = var1 * var2
				tasks_total += (isNaN(eval))?0:eval
			}
		}

		console.log('Total Changed:',tasks_total,'nv:',newValue,'ov:', oldValue,'pr:', push_reorder);

		// for (e_bind_idx in $scope.estimate.bound_estimates) {
		// 	tasks_total += $scope.estimate.bound_estimates[e_bind_idx].total.estimate;
		// }
		
		if (tasks_total==0 && typeof $scope.tasks_total !== 'undefined') {
			$scope.tasks_total = $scope.tasks_total
		} else {
			$scope.tasks_total = tasks_total
		}

		if (push_reorder) {
			if(this.pushReorderTimeout) clearTimeout(this.pushReorderTimeout);
			this.pushReorderTimeout = setTimeout(function() {
				EstimateEntries.saveOrder({objectarray:newValue})
			}, 500);
		}
	}
	function round5(x) {
    	return parseInt(x);
    	return (x % 5) >= 2.5 ? parseInt(x / 5) * 5 + 5 : parseInt(x / 5) * 5;
	}
	$scope.open_groups = function() {
		// $('#groups-modal').foundation('reveal', 'open');
		// EstimateEdit_JobRoles_Filter($scope)
		// EstimateEdit_JobRoles_Click_Setup($scope)
	}

	$scope.save = function(closeModal) {
		var closeModal = closeModal | true

		$scope.estimate.group = $scope.estimate.group.value
		$scope.estimate.total_summ = $scope.tasks_total

  		Estimates.save($scope.estimate, function(estimate) {
  			estimate.group = EstimateTypes(estimate.group)
  			$scope.estimate = estimate

  			if (typeof $scope.opened_estimate !== 'undefined') {
	  			$scope.opened_estimate.group = estimate.group
	  			$scope.opened_estimate.title = estimate.title
	  			$scope.opened_estimate.total_summ = estimate.total_summ  				
	  			// console.log("LOG::",$scope.opened_estimate,estimate);
  			}
  			
  			if (closeModal) $('#new-estimate-modal').foundation('reveal', 'close');
  		})
  	};
	$scope.back = function () {
		$location.path('clients/'+$stateParams.clientId+'/projects/'+$stateParams.projectId) 
	}


	$scope.add_task = function (entry) {
		if (typeof(entry)==='undefined') {
			console.error('Please be careful! There is no task to add!');
		} else {
			entry.estimate_id = $stateParams.estimateId
			entry.hours = []
			entry.is_header = true
			for (var idx=0; idx<$scope.estimate.involved_roles.id.length; idx++) {
				entry.hours.push(0)
			}
			EstimateEntries.save(entry, function(entry) {
				entry.is_header = false
				$scope.estimate.entries.push(entry)
				$scope.estimate.entries.show = false;
				$timeout( function() {
					EstimateEdit_JobRoles_Events_Setup($scope);
					Projects_BindOnhoverEffects('td.handle');
					$('input[data-entryid='+entry.id+'].title-input').focus()
				},100)
			});
			// $scope.estimate.tasks.show = false;

			// $task.estimate_id = $scope.estimate.id
			// $task.project_id = $stateParams.projectId
			// $task.jobroles = $scope.estimate.involved_roles.id
			// $scope.estimate.tasks.push(Tasks.save($task, function(task) {
			// 	$timeout( function() {
			// 		EstimateEdit_JobRoles_Events_Setup($scope);
			// 		$('input[data-taskid='+task.id+'].title-input').focus()
			// 	},100)
			// }))
			$scope.new_task=null

		}
	}
	$scope.remove_task = function (entry,$index) {
		EstimateEntries.delete({id:entry.id})
		$scope.estimate.entries.splice($index,1)
		$scope.estimate.entries.show = $scope.estimate.entries.length==0
	}
	$scope.changeHours = function (hour,index,entry) {
		var numb = parseFloat(hour.toString().replace(",",".")).toFixed(2)
		entry.hours[index] = round5(numb*100)/100
		console.log('Change Hour::',entry.hours,numb);
		EstimateEntries.save(entry);
	}
	$scope.changeTaskname = function (post) {
		EstimateEntries.save(post);
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
	$scope.update_estimates = function() {
		Estimates.get({id:$stateParams.estimateId},function(estimate){
			estimate.tasks.show = estimate.tasks.length==0
			estimate.expences.show= estimate.expences.length==0
			estimate.group = EstimateTypes(estimate.group)

			angular.copy(estimate,$scope.estimate)
		})
		Projects.get({id:$stateParams.projectId},function(project){

			angular.copy(project,$scope.project)
			$timeout( function() {
				EstimateEdit_BindEstimates_Events_Setup($scope);
			},400)
		})
		console.log($scope.estimate);
	}
	$scope.setHeader = function(entry,toWhat) {
		for (var i = entry.hours.length - 1; i >= 0; i--) { entry.hours[i] = 0 };
		entry.is_header = toWhat;
		EstimateEntries.save(entry)
	}


	$scope.estimateTypeHelper = function(indx) {
		var type = EstimateTypes(indx)
		return type.name
	}

	//Sortable Options
	$scope.sortableOptions = {
		handle: ".handle",
		delay: 150,
		forceHelperSize: true,
		forcePlaceholderSize: false,
		stop: function(event, ui) {
			//BUG Fix: by each change some top margins are calculated wrong by browser
			$('.sortable').toggleClass('fix-display')
			$('.button-group').removeClass('show')
		},
	};
	

	$timeout( function() {
		// @from interaction.js


		LoadFoundation();
		FoundationTabCallbackOverride();
		

		$('#new-estimate-modal').foundation('reveal', 'open');
		$(document).on('closed.fndtn.reveal', '[data-reveal]', function () {
			$scope.save(false)
			$state.go('project.estimates')
		})

		angular.element(document).ready(function () {
			console.log('IsReady::EstimatesEditController');
			EstimateEdit_JobRoles_Events_Setup($scope);
			EstimateEdit_Expences_Events_Setup($scope);
			EstimateEdit_BindEstimates_Events_Setup($scope);
	    });
	},500)
})






.controller('TasksMyController', function (LogedInUser, $state, $scope, $rootScope, $stateParams, $timeout, $location, Users, JobRoles, UserTimeLogs, Tasks, Projects, FireBase) {
	LogedInUser.checkLoginStatuss()

	lastrefresh = moment()

	Auth = LogedInUser.get(function(){
		$scope.user = Users.get({id:Auth.id}, function(data){
			_(data.taskroles).each(function(taskrole){
				taskrole.search = (taskrole.task.project.client.title+' '+taskrole.task.project.title+' '+taskrole.task.title)
			})
			console.log('Got User Data');
			$timeout( function() {
				LoadFoundation();
			},200);
		})
	})

	///*
	$scope.projects = Projects.query();
	$scope.jobroles = JobRoles.query();
	
	var now  = moment()
	var week = [
		now.startOf('isoWeek'),
	]
	week[0].weekend = false;
	week[0].today   = week[0].isSame(moment(), 'day');

	for (var i=1;i<7;i++) {
		week.push( week[i-1].clone().add('d', 1) )
		week[i].weekend = (week[i].day()==6 || week[i].day()==0)?true:false;
		week[i].today   = week[i].isSame(moment(), 'day');
	}
	$scope.week = week
	$scope.hours = 0;
	//*/

	// $rootScope.weekOffset = parseInt($stateParams.weekOffset);
	console.log('Made Week',$stateParams.weekOffset);

	
	$scope.open_log_select = function (passobject) {
		$rootScope.passobject = passobject;
		console.log('PO1',$rootScope.passobject);
		if(passobject.day.weekend) return;
		$('.app-time-select-modal').foundation('reveal', 'open');
	}
	$scope.close_log_select = function () {
		$('#time-select-modal').foundation('reveal', 'close');
		delete $rootScope.passobject
	}
	$scope.add_log = function () {
		var minutes = parseInt($scope.hours)*60 + parseInt($scope.minutes)
		var PO = $rootScope.passobject
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
	//*/
	$scope.resolve_task_offset = function(step) {
		if(!$rootScope.weekOffset) $rootScope.weekOffset=0
		if (step==0) {
			var go = 0
		} else {
			var go = $rootScope.weekOffset+step
		}
		$state.go('tasks.list',({weekOffset:go}))
	}
	
	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
		DashboardMy_Dragger_Setup($scope);

		console.log('Loaded Foundation');
	},500)
})

.controller('TasksMyListController', function (LogedInUser, $state, $scope, $rootScope, $stateParams, $timeout, $location, Users, JobRoles, UserTimeLogs, Tasks, Projects, FireBase) {
	LogedInUser.checkLoginStatuss()

	lastrefresh = moment()

	$scope.projects = Projects.query();
	$scope.jobroles = JobRoles.query();
	$rootScope.weekOffset = parseInt($stateParams.weekOffset);

	// $scope.syncobject = FireBase
	// $scope.syncobject.$on("change", function() {
	// 	console.log('Sync:',lastrefresh.diff($scope.syncobject.refreshtime));
	// 	if (lastrefresh.diff($scope.syncobject.refreshtime)<0 && ($scope.syncobject.foruser == $scope.user.id || $scope.syncobject.foruser==0)) {
	// 		LogedInUser.checkLoginStatuss()

	// 		Users.get({id:Auth.id}, function(user) {
	// 			angular.copy(user, $scope.user);
	// 		})
	// 	}
	// });
	
	console.log('Made NOW::',$rootScope.weekOffset);

	var now  = moment().add(parseInt($stateParams.weekOffset),"weeks")
	var week = [
		now.startOf('isoWeek'),
	]
	week[0].weekend = false;
	week[0].today   = week[0].isSame(moment(), 'day');

	for (var i=1;i<7;i++) {
		week.push( week[i-1].clone().add('d', 1) )
		week[i].weekend = (week[i].day()==6 || week[i].day()==0)?true:false;
		week[i].today   = week[i].isSame(moment(), 'day');
	}
	$scope.week = week
	$scope.hours = 0;
	

	$scope.open_log_select = function (passobject) {
		$rootScope.passobject = passobject;
		console.log('PO1',$rootScope.passobject);
		if(passobject.day.weekend) return;
		$('.app-time-select-modal').foundation('reveal', 'open');
	}
	$scope.close_log_select = function () {
		$('#time-select-modal').foundation('reveal', 'close');
		delete $rootScope.passobject
	}
	$scope.add_log = function () {
		var minutes = parseInt($scope.hours)*60 + parseInt($scope.minutes)
		var PO = $rootScope.passobject
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
		// LoadFoundation();
		DashboardMy_Dragger_Setup($scope);
	},500)
})




.controller('PlanerController', function (LogedInUser, $state, $scope, $rootScope, $stateParams, $timeout, $location, Users, JobRoles, UserTimeLogs, Tasks, Projects, FireBase) {
	LogedInUser.checkLoginStatuss()

	
	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
	},500)
})



// -- Reports -- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ //
.controller('ReportController', function (LogedInUser, $scope, $rootScope, $stateParams, $timeout, $location, Dashboard, Users, Tasks, FireBase) {
	LogedInUser.checkLoginStatuss()
	$scope.total = {
		done_m:0,
		sold_m:0
	}
	Dashboard.getProjects(function(projects){
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
	
	

	Dashboard.getAllEstimates(function(estimates){
		$scope.estimatesList = estimates
		DashboardCascade_OnClick_Setup();
	})

	$scope.users = Users.query(function(users) {
	})
	// console.log('NOW::',);
	var now  = moment().add(parseInt(0),"weeks")
	var week = [
		now.startOf('isoWeek'),
	]
	week[0].weekend = false;
	week[0].today   = week[0].isSame(moment(), 'day');

	for (var i=1;i<7;i++) {
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
		AngularTabClickOverride();
	},500)
})
.controller('ReportClentsController', function (LogedInUser, $scope, $rootScope, $stateParams, $timeout, $location, Dashboard, Users, Tasks, FireBase) {
	LogedInUser.checkLoginStatuss()

	Dashboard.query( {name:'clients'} , function(clients){
		$scope.clients = clients

	})
})
.controller('ReportDashboardController', function (LogedInUser, $scope, $rootScope, $stateParams, $timeout, $location, Dashboard, Users, Tasks, FireBase) {
	LogedInUser.checkLoginStatuss()

	Dashboard.getChartData(function(data){
		$scope.chartdata = data;
		Dashboard_D3($scope);
	})

	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
		AngularTabClickOverride();
	},500)
})
.controller('ReportUsersController', function (LogedInUser, $scope, $rootScope, $stateParams, $timeout, $location, Dashboard, Users, Tasks, FireBase) {
	LogedInUser.checkLoginStatuss()

	$scope.users = Users.query(function(){
		$timeout( function() {
			Reports_users_Click_Setup($scope);
		},100)
	});


	$scope.open_user = function(_id,event) {
		var userbuffer = Users.get({id:_id}, function(user) {});
		$timeout( function() {
			$scope.user = userbuffer;
			$scope.user.group = 1;

		},200)
		
	}
	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
		
	},500)
})
.controller('ReportBonusController', function (LogedInUser, $scope, $rootScope, $stateParams, $timeout, $location, Dashboard, Users, Tasks, JobRoles, Clients, Apps, FireBase) {
	var loadingstep = 1/5

	LogedInUser.checkLoginStatuss()
	if ( typeof $scope.loading === 'undefined') {
		$scope.loading = 0
	} else {

		// $scope.loading = 1
		// watcher_fnc(1)
	}

	$scope.clients = Clients.query()
	$scope.date_options = {
		// selectYears: true,
		// selectMonths: true,
	}
	

	

	

	// @Watchers
	var watcher_fnc = function(newValue, oldValue) {
		console.log('watcher_fnc::newValue==',newValue);
		if (newValue>=1) {

		} else if (newValue>=1-loadingstep) {
			console.log('watcher_fnc::do math');
			$timeout( function() {
				$scope.doTheMath()
				$scope.loading = Reports_bonus_Dragger_Setup(loadingstep);
				$timeout( function() { 
					console.log('watcher_fnc::show user-picture-row');
					Reports_bonus_D3()
					$('.user-picture-row').delay(1000).fadeIn(500)
				},100)
			},500)
		}
	}
	$scope.$watch('loading', watcher_fnc, true);


	$scope.doTheMath = function() {
		console.groupCollapsed('doTheMath');

		console.log('$scope.projectsWithHours',$scope.projectsWithHours);
		console.log('$scope.userhours',$scope.userhours);

		console.groupCollapsed('doTheMath::project details');
		for (prject_id in $scope.projectsWithHours) {
			console.groupCollapsed('Project::'+prject_id);
			
			if ( typeof $scope.userhours[prject_id] !== 'undefined') 
			if ( $scope.userhours[prject_id].length > 0) {
				
				var totalWork = {}
				for (var i1 = $scope.userhours[prject_id].length - 1; i1 >= 0; i1--) {
					var j_id = $scope.userhours[prject_id][i1].jobrole_id
					var min  = parseInt($scope.userhours[prject_id][i1].minutes)

					if (typeof totalWork[j_id] !== 'undefined') {
						totalWork[j_id] += min
					} else {
						totalWork[j_id] = min
					}
					
				}

				console.log(prject_id,'totalWork::',totalWork);			
				for (var i = $scope.userhours[prject_id].length - 1; i >= 0; i--) {
					var u_id = $scope.userhours[prject_id][i].user_id
					var j_id = $scope.userhours[prject_id][i].jobrole_id
					var min  = parseInt($scope.userhours[prject_id][i].minutes) 

					var sold_min = $scope.projectsWithHours[prject_id]['sold_m'][j_id] || 0
					var percent = min/totalWork[j_id] || 0 //Zero if divided by zero

					var sold_min_share = sold_min*percent

					console.log(' ','[j_id]::',j_id);
					console.log(' ','totalWork[j_id]::',totalWork[j_id]);
					console.log(' ','min::',min);
					console.log(' ','percent::',percent);
					console.log(' ','sold_min::',sold_min);
					console.log(' ','all_sold_min::',$scope.projectsWithHours[prject_id]['sold_m']);
					console.log(' ','sold_min_share::',sold_min_share);
					console.log(' -----------------------------');
					console.log('user_id::',u_id,'$scope.users::',$scope.users);

					// var title = $scope.jobroles[j_id].title
					var title = j_id

					//@ Check if user even exist and then add hours
					if ( typeof $scope.users[u_id] !== 'undefined' ) {
						if (typeof $scope.users[u_id].sold_m[title] !== 'undefined') {
							$scope.users[u_id].sold_m[title] += sold_min_share
						} else {
							$scope.users[u_id].sold_m[title] = sold_min_share
						}
						if (typeof $scope.users[u_id].done_m[title] !== 'undefined') {
							$scope.users[u_id].done_m[title] += min
						} else {
							$scope.users[u_id].done_m[title] = min
						}
					}
				};		
			} else {
				console.log('empty');
			}
			console.groupEnd();	
		}
		console.groupEnd();

		//Array manipulations before returning
		_($scope.users).each(function(user){
			var if_size = _.chain(user.sold_m)
				.reject(function(num,idx){return idx==1})
				.size()
				.value()

			var json = {}
			for (key in user.sold_m) {
				json[key+'a'] = user.sold_m[key]
				json[key+'b'] = user.done_m[key] - user.sold_m[key]
			}

			if (if_size>1) {
				json['9a'] = 0 //SOLD
				json['9b'] = 0 //DONE
				for (key in user.sold_m) {
					json['9a'] += user.sold_m[key]
					json['9b'] += user.done_m[key]
				}
				json['9b'] -= json['9a']
			} else {}
			user.json = json;

			_(user.sold_m).each(function(sold_m,role_id){
				if (typeof user.json['9a'] !== 'undefined' && typeof user.percent['9'] === 'undefined') {
					var part_for_total = user.json['9a']/(user.json['9b']+user.json['9a'])
					console.log('BLA::',part_for_total,user.json['9a'],user.json['9b']);
					user.percent['9'] = user.json['9a']/(user.json['9b']+user.json['9a'])  *  100;
					user.sold_m['9'] = user.json['9a']
				} 
				var part = (typeof user.done_m[role_id] !== 'undefined') ? sold_m/user.done_m[role_id] : 0
				user.percent[role_id] = part*100

			})
			user.jobrolecount  = _.chain(user.sold_m)
				.reject(function(num,idx){return idx==1})
				.size()
				.value()

			console.log('json',user);
		})
		console.groupEnd();
	}
	$scope.startQuery = function() {
		if (typeof $scope.e_date === 'undefined' || typeof $scope.s_date === 'undefined') {

			return
		}
		$('.dashboard-bonus .filter-date-display').text('')

		$('.app-date-filter-modal').foundation('reveal', 'close');

		$('.dashboard-bonus .button-group .secondary').removeClass('secondary')
		$('.loading-row div').show()
		$('.loading-row').fadeIn(300)

		var e_date = moment($scope.e_date)
		var s_date = moment($scope.s_date)
		if (e_date.diff(s_date,'days')>363) {
			$scope.date_period = 20*8*12*60
		} else {
			$scope.date_period = e_date.diff(s_date,'days')*8*60
		}

		$('.dashboard-bonus .filter-date-display').text(''+s_date.format('DD-MM-YYYY')+' -> '+e_date.format('DD-MM-YYYY'))

		Users.query(function(users){
			for (var i = users.length - 1; i >= 0; i--) {
				users[i].sold_m = {}
				users[i].done_m = {}
				users[i].percent = {}
			};
			$scope.users = _(users).indexBy('id')
			console.log('Users',$scope.users);
			$scope.loading = Reports_bonus_Dragger_Setup(loadingstep);
		});
		JobRoles.query(function(data){
			$scope.jobroles = _(data).indexBy('id')
			$scope.loading = Reports_bonus_Dragger_Setup(loadingstep);
		})
		Dashboard.query({
			name:'userhours',
			e_date:e_date.toString(),
			s_date:s_date.toString(),
		}, function(data) {
			$scope.userhours = _(data).groupBy('projects_id')
			$scope.loading = Reports_bonus_Dragger_Setup(loadingstep);
		})
		Dashboard.query({name:'getestimates'}, function(data) {
			$scope.pm_sold_hours = data[0]
		})
		Tasks.getEstimates({
			e_date:e_date.toString(),
			s_date:s_date.toString(),
		}, function(data) {
			$scope.projectsWithHours = _(data).indexBy('id')
			$scope.loading = Reports_bonus_Dragger_Setup(loadingstep);
		})
	}
	$scope.saveCompiledData = function() {
		var save = _($scope.users).map(function(user){
			return _(user).pick('sold_m','id');
		})
		console.log('F::',$scope.users,save);
		Apps.save({
			app_name:'BonusSystem',
			data: ''
		})
	}
	$scope.openModal = function() {
		$('.custom-date-row').fadeOut(0)
		$('.app-date-filter-modal').foundation('reveal', 'open');
	}

	$scope.thisYear = function() {
		$scope.e_date = moment()//.format('D dddd, YYYY')
		$scope.s_date = moment('2015-01-01 00:00:00')//.format('D dddd, YYYY')
		$scope.startQuery()
	}

	$scope.lastYear = function() {
		$scope.e_date = moment('2014-12-31 00:00:00')//.format('D dddd, YYYY')
		$scope.s_date = moment('2014-01-01 00:00:00')//.format('D dddd, YYYY')
		$scope.startQuery()
	}

	$scope.customDate = function() {
		$('.custom-date-row').fadeIn(300)

	}

	// $scope.startQuery()

	$timeout( function() {
		// @from interaction.js
		LoadFoundation();
		$('.custom-date-row').fadeOut(0)
		$('.app-date-filter-modal').foundation('reveal', 'open');


	},500)
})
.controller('ReportProjectsController', function (LogedInUser, $state, $scope, $rootScope, $stateParams, $timeout, $location, Dashboard, Users, JobRoles, UserTimeLogs, Tasks, Projects, FireBase) {
	var id = $stateParams.projectId
	

	$scope.chartdata = Dashboard.getProjectOverView({id:id}, function(data) {
		data.project_overview = []

		var loopObject = _.chain(data.project_overview_raw)
			.each(function(object){
				object.time = moment(object.time).format('YYYY-MM-DD')
			})
			.groupBy('time')
			.each(function(object,key,list){
				list[key] = _(object).indexBy('jobrole_id');
			})
			.value();
		
		var x = _.chain(data.project_overview_raw)
			.pluck('time')
			.uniq()
			.value();
		var roles = _.chain(data.project_overview_raw)
			.pluck('jobrole_id')
			.uniq()
			.value();


		data.project_overview_time.done = {};


		_(roles).each(function(role){
			var arrayToPush = [role]
			var totalDoneByRole = 0;
			_(x).each(function(date){

				if (typeof loopObject[date][role] === 'undefined') {
					arrayToPush.push(null)
				} else {
					var minutes = parseInt(loopObject[date][role].minutes)
					arrayToPush.push(minutes)
					totalDoneByRole += minutes;
				}
			})
			data.project_overview.push(arrayToPush)
			data.project_overview_time.done[role] = {
				id: role,
				minutes: totalDoneByRole
			}
		})

		data.project_overview_time_graph = []
		rolenames = {
			1:"PV",
			2:"Radošais direktors",
			3:"Tekstu autors",
			4:"Dizaineris",
			5:"Maketētājs",
			6:"Programmētājs",
	    }

		_(rolenames).each(function(name,key){
			var role = {
				Sold : (typeof data.project_overview_time.sold[key] === 'undefined')?0:data.project_overview_time.sold[key].minutes,
				Done : (typeof data.project_overview_time.done[key] === 'undefined')?0:data.project_overview_time.done[key].minutes,
				name : name
			}
			data.project_overview_time_graph.push(role)
		})

		x.unshift('x')
		data.project_overview.unshift(x)

		console.log('data',data);
		
		
	});
	
	$scope.project = Dashboard.getTaskroles({id:id}, function(project){
		$timeout( function() {
		// 	var $details = $('.real .project-details');
		// 	$details_clone.html( $details.html() ).removeClass('loading')
		// 	DashboardCascade_OnClick_Setup();
			Dashboard_D3_SingleProject($scope);
		},500)
	})
})



.controller('UsersController', function (LogedInUser, $location, $state, $scope, $rootScope, $stateParams, $timeout, Users) {
	LogedInUser.checkLoginStatuss()

	
	$scope.stateParams = $stateParams
	
	// console.log('State',$state.current);
	
	$scope.users = Users.query(function(){
		$timeout( function() {
			Projects_BindOnhoverEffects()
		},100)
	})
	
	$scope.destroy = function($ind) {
		var cli_id = $scope.clients[$ind].id
		Clients.delete({id:cli_id}, function() {
			$scope.clients.splice($ind,1)
			if ($state.params.clientId == cli_id) $state.go('clients')
		})
    };

	$scope.gotoProjects = function($id) {
		$location.path('/clients/'+$id+'')
	}

	$timeout( function() {
		// @from interaction.js
		LoadFoundation();

	})
})
.controller('UsersProfileController', function (LogedInUser, $location, $state, $scope, $rootScope, $stateParams, $timeout, Users) {
	
	$scope.password = {
		one: '',
		two: '',
		error_nomatch: false,
		error_noentered: false
	}

	$scope.groups = [
		{id:0, title:'User'},
		{id:1, title:'Project Manager'}
	]

	$scope.$watch('password', function(password, oldValue) {
		if ( (password.one != password.two) && password.one.length>0 && password.two.length>0 ) {
			$scope.password.error_nomatch = true
		} else {
			$scope.password.error_nomatch = false
		}
	},true)

	$scope.user = Users.get({id:$stateParams.userId},function(){
		$timeout( function() {
			Projects_BindOnhoverEffects()
		},100)
	})

	$scope.save = function() {
		if ($scope.password.error_nomatch || $scope.password.error_noentered ) {

		} else {
			if ($scope.password.one.length>0) { $scope.user.password = $scope.password.one }
			if ($scope.user.pict == '') { $scope.user.pict = 'junck' }

			Users.save($scope.user, function(user){
				if ( typeof $scope.user.id === 'undefined' ) {
					$scope.users.push(user)
				} else {
					for (var i = $scope.users.length - 1; i >= 0; i--) {
						if ($scope.users[i].id == user.id) {
							$scope.users[i].name = user.name
							$scope.users[i].surname = user.surname
						}
					};
					
				}
				$state.go('users')
			})
		}
	}
})



.controller('AppsController', function (LogedInUser, $location, $state, $scope, $rootScope, $stateParams, $timeout, Apps) {
	$scope.apps = Apps.getAppList();
})

.controller('SettingsController', function (LogedInUser, $location, $state, $scope, $rootScope, $stateParams, $timeout, Users) {
	LogedInUser.checkLoginStatuss()
})
.controller('BusinessInfoController', function (LogedInUser, Branches, $location, $state, $scope, $rootScope, $stateParams, $timeout, Users) {
	LogedInUser.checkLoginStatuss()
	$scope.branches = Branches.query(function(branches){
	});

})
.controller('BusinessInfoEditController', function (LogedInUser, Branches, $location, $state, $scope, $rootScope, $stateParams, $timeout, Users) {
	LogedInUser.checkLoginStatuss()
	$scope.branch = Branches.get({id:$state.params.id}, function() {


		$timeout( function() {
			// @from interaction.js
			LoadFoundation();
		},100)
	})


	// {

	// 	if (typeof $state.params.id !== 'undefined') {
	// 		for (var i = branches.length - 1; i >= 0; i--) {
	// 			if (branches[i].id == $state.params.id) $scope.branch = branches[i]
	// 		};
	// 	}
	// });


	$scope.save = function() {
		Branches.save($scope.branch, function(branch) {
			var added = false;
			for (var i = $scope.branches.length - 1; i >= 0; i--) {
				if ($scope.branches[i].id == branch.id) {
					$scope.branches[i].title = branch.title
					added = true
				}
			};
			if ( !added ) $scope.branches.push(branch);
			
			$state.go('settings.businessinfo');
		});
	}

	$timeout( function() {
		// @from interaction.js
		
	})
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

.controller('BreadCrumbs', function (LogedInUser, $scope, $rootScope, $location, $timeout) {
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
			href = '/#'

		$scope.url = []
		
		for (key in urls) {
			if (urls[key]!='') href += '/';
			href += urls[key]
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


.controller('profile_controller', function(LogedInUser, $scope, $rootScope, $location, $timeout ){
	$scope.me = LogedInUser.get()
})







