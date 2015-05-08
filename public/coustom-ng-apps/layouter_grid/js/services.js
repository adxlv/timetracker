var app = angular.module('Timetracker.apps.LayouterGrid.services', [])
	.factory('LayouterGrid', function($resource, CSRF_TOKEN) {
		return $resource('/api/v1/apps/layoutergrid', {
			id: '@id', projects: '@projects', 'csrf_token' :CSRF_TOKEN
		},{
			// save: { method: 'PUT' }
		});
	})