var app = angular.module('Timetracker.apps.FluidAlerts.services', [])
	.factory('FluidAlerts', function($resource, CSRF_TOKEN) {
		return $resource('/api/v1/apps/fluidalerts', {
			id: '@id', projects: '@projects', 'csrf_token' :CSRF_TOKEN
		},{
			// save: { method: 'PUT' }
		});
	})