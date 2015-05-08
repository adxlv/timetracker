

var app = angular.module('Timetracker.apps.LayouterGrid', [
	'ngResource',

	'ui.router',
	'ui.sortable',

	'Timetracker.apps.LayouterGrid.services',
	'Timetracker.apps.LayouterGrid.controllers',
])


.config(function($stateProvider, $urlRouterProvider) {

	// For any unmatched url, redirect to /state1
	// $urlRouterProvider.otherwise("/home");
	// $urlRouterProvider.when("/", "/home");
	// $urlRouterProvider.when("/project/:projectId", "/project/:projectId/tasks");
	// $urlRouterProvider.when("/my-tasks", "/my-tasks/0");

	// Now set up the states
	$stateProvider
		.state('lg_home', {
			url: "/apps/layoutergrid",
			templateUrl: "/coustom-ng-apps/layouter_grid/partials/index.html",
			controller: 'LG_IndexController'
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

