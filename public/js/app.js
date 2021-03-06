// var app = angular.module('Timetracker', ['ngResource','Timetracker.filters','Timetracker.services','Timetracker.directives','Timetracker.controllers']);
// var app = angular.module('Timetracker', ['firebase', 'ngRoute', 'Timetracker.services', 'Timetracker.controllers'])
var app = angular.module('Timetracker', [
	'ngResource',
	'ngAnimate',

	'ui.router',
	'ui.sortable',

	'firebase',
	'angular-datepicker',

	'Timetracker.services',
	'Timetracker.controllers',

	'Timetracker.apps.LayouterGrid',
	'Timetracker.apps.FluidAlerts',
])

.config(function($stateProvider, $urlRouterProvider, $compileProvider) {

	// For any unmatched url, redirect to /state1
	$urlRouterProvider.otherwise("/home");
	$urlRouterProvider.when("", "/home");
	$urlRouterProvider.when("/", "/home");
	$urlRouterProvider.when("/project/:projectId", "/project/:projectId/tasks");
	$urlRouterProvider.when("/my-tasks", "/my-tasks/0");
	$urlRouterProvider.when("/settings", "/settings/menu");

	// Now set up the states
	$stateProvider
		.state('home', {
			url: "/",
			templateUrl: "/partials/home/home.html",
			controller: 'IndexController'
		})

		.state('home.tasks', {
			url: "home",

			views: {
		      'tasks': {

		        templateUrl: '/partials/my-tasks/tasks.my.html',
		        controller: 'TasksMyController'
		      },
		      'clients': {
		      	templateUrl: '/partials/clients/clients.html',
		      	controller: 'ClientsController'
		      }
		    }
		})
	

		.state('tasks', {
			url: "/my-tasks",
			templateUrl: "/partials/my-tasks/tasks.my.html",
			controller: 'TasksMyController'
		})
		.state('tasks.list', {
			url: "/:weekOffset",
			// url: "/{weekOffset:[0-9]{1,8}}",
			templateUrl: "/partials/my-tasks/tasks.list.html",
			controller: 'TasksMyListController'
		})
		

		.state('planer', {
			url: "/planer",
			templateUrl: "/partials/planer/planer.html",
			controller: 'PlanerController'
		})
		// .state('tasks.planer.views', {
		// 	url: "/planer-test/:weekOffset",
		// 	// templateUrl: "/partials/my-tasks/tasks.list.html",
		// 	// controller: 'TasksMyListController'

		// 	views: {
		//       'task-list': {
		//         templateUrl: "/partials/my-tasks/tasks.list.html",
		// 		controller: 'TasksMyListController'
		//       },
		//       'planer': {
		//       	templateUrl: '/partials/my-tasks/tasks.planer.html',
		//       	controller: 'TasksMyPlanerController'
		//       }
		//     }
		// })


		.state('clients', {
			url: "/clients",
			templateUrl: "partials/clients/clients.html",
			controller: 'ClientsController'
		})
		.state('clients.edit', {
			url: "/:clientId/edit",
			templateUrl: "partials/clients/clients.edit.html",
			controller: 'ClientsEditController'
		})

		.state('clients.projects', {
			url: "/:clientId",
			templateUrl: "partials/projects/projects.html",
			controller: 'ProjectsController'
		})

		.state('clients.projects.new', {
			url: "/project/:projectId",
			templateUrl: "partials/projects/projects.edit.html",
			controller: 'ProjectsEditController'
		})

		.state('project', {
			url: "/project/:projectId",
			templateUrl: "partials/projects/projects.open.html",
			controller: 'ProjectsOpenController',
		})
		
		.state('project.tasks', {
			url: "/tasks",
			templateUrl: "partials/tasks/tasks.html",
			controller: 'TasksController'
		})

		.state('project.tasks.info', {
			url: "/info/:taskId",
			views: {
		      'tasksinfo': {
					templateUrl: "partials/tasks/tasks.info.html",
					controller: 'TasksInfoController'
		      }
		    }
		})

		.state('project.tasks.new', {
			url: "/new",
			views: {
		      'tasksnew': {
					templateUrl: "partials/tasks/tasks.new.html",
					controller: 'TasksNewController'
		      }
		    }
		})

		.state('project.estimates', {
			url: "/estimates",
			templateUrl: "partials/estimates/estimates.html",
			controller: 'EstimatesController'
		})
		.state('project.estimates.new', {
			url: "/new",
			templateUrl: "partials/estimates/estimates.new.html",
			controller: 'EstimatesNewController'
		})
		.state('project.estimates.info', {
			url: "/:estimateId",
			views: {
		      'info': {
					templateUrl: "partials/estimates/estimates.info.html",
					controller: 'EstimatesInfoController'
		      }
		    }
		})
		.state('project.estimates.edit', {
			url: "/:estimateId/edit",
			views: {
		      'edit': {
					templateUrl: "partials/estimates/estimates.edit.html",
					controller: 'EstimatesEditController'
		      }
		    }
		})
		


		.state('project.reports', {
			url: "/reports",
			templateUrl: "partials/reports/reports.project.html",
			controller: 'ReportProjectsController'
		})

		
		.state('reports', {
			url: "/reports",
			templateUrl: "/partials/reports/reports.html",
			controller: 'ReportController'
		})
		.state('reports.dashboard', {
			url: "/dashboard",
			templateUrl: "/partials/reports/reports.dashboard.html",
			controller: 'ReportDashboardController'
		})
		.state('reports.clients', {
			url: "/clients",
			templateUrl: "/partials/reports/reports.clients.html",
			controller: 'ReportClentsController'
		})
		.state('reports.users', {
			url: "/users",
			
			views: {
			  '': {
			  	templateUrl: "/partials/reports/reports.users.html",
				controller: 'ReportUsersController',
			  },
		      'tasks@reports.users': {
		        templateUrl: '/partials/my-tasks/tasks.list.html',
		        controller: 'ReportUsersController'
		      }
		    }
		})
		.state('reports.bonus', {
			url: "/bonus",
			templateUrl: "/partials/reports/reports.bonus.html",
			controller: 'ReportBonusController'
		})

		.state('settings', {
			url: "/settings",
			templateUrl: "/partials/settings/settings.html",			
			// abstract: true,

		})

		.state('settings.menu', {
			url: "/menu",
			templateUrl: "/partials/settings/settings.menu.html",
			controller: 'SettingsController'
		})

		.state('settings.users', {
			url: "/users",
			templateUrl: "/partials/users/users.html",
			controller: 'UsersController'
		})
		.state('settings.users.profile', {
			url: "/:userId",
			templateUrl: "/partials/users/users.profile.html",
			controller: 'UsersProfileController'
		})

		.state('settings.businessinfo', {
			url: "/businessinfo",
			templateUrl: "/partials/settings/business/list.html",
			controller: 'BusinessInfoController'
		})
		.state('settings.businessinfo.edit', {
			url: "/:id",
			templateUrl: "/partials/settings/business/edit.html",
			controller: 'BusinessInfoEditController'
		})

		// .state('settings.users.me', {
		// 	url: "/my-profile",
		// 	templateUrl: "/partials/users/users.profile.html",
		// 	controller: 'UsersProfileController'
		// })



		.state('apps', {
			url: "/apps",
			templateUrl: "/partials/apps/apps.html",
			controller: 'AppsController'
		})

		// .state('users.profile.edit', {
		// 	url: "/edit",
		// 	templateUrl: "/partials/reports/reports.html",
		// 	controller: 'DashboardController'
		// })		

	
	//Debug
})

app.config(['$compileProvider', function ($compileProvider) {
	// $compileProvider.debugInfoEnabled(false);
}]);




//Workaround for state is
app.run(function ($rootScope, $state, $stateParams) {
  $rootScope.$state = $state;
  $rootScope.$stateParams = $stateParams;
});