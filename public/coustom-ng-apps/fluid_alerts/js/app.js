

var app = angular.module('Timetracker.apps.FluidAlerts', [
	'ngResource',

	'ui.router',
	'ui.sortable',

	'Timetracker.apps.FluidAlerts.services',
	'Timetracker.apps.FluidAlerts.controllers',
])


.config(function($stateProvider, $urlRouterProvider) {

	// For any unmatched url, redirect to /state1
	// $urlRouterProvider.otherwise("/home");
	// $urlRouterProvider.when("/", "/home");
	// $urlRouterProvider.when("/project/:projectId", "/project/:projectId/tasks");
	// $urlRouterProvider.when("/my-tasks", "/my-tasks/0");

	// Now set up the states
	$stateProvider
		.state('fa_home', {
			url: "/apps/fluidalerts",
			templateUrl: "/coustom-ng-apps/fluid_alerts/partials/index.html",
			controller: 'FA_IndexController'
		})

		// .state('home.tasks', {
		// 	url: "home",

		// 	views: {
		//       'tasks': {

		//         templateUrl: '/partials/my-tasks/tasks.my.html',
		//         controller: 'TasksMyController'
		//       },
		//       'clients': {
		//       	templateUrl: '/partials/clients/clients.html',
		//       	controller: 'ClientsController'
		//       }
		//     }
		// })
});

