var app = angular.module('Timetracker.apps.FluidAlerts.controllers', [])

.controller('FA_IndexController', function ($scope, $rootScope, $location, $state, LayouterGrid) {
	var locc = $location.$$absUrl
	var dock = 10
		
	function saveAppData(new_val,old_val) {
		if (typeof old_val !== 'undefined')
		if (!angular.equals(new_val,old_val)) {
			LayouterGrid.save($scope.app)
		}
	}

	

	$scope.beep = function() {
		window.fluid.beep()
	}

	$scope.badge_add = function() {
		dock++;
		window.fluid.dockBadge = dock
	}
	$scope.badge_sub = function() {
		dock--;
		window.fluid.dockBadge = dock
	}
	$scope.notify = function() {
		window.fluid.showGrowlNotification({
		    title: "title", 
		    description: "description", 
		    priority: 1, 
		    sticky: false,
		    identifier: "foo",
		})
	}


})