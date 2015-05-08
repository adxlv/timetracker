var app = angular.module('Timetracker.apps.LayouterGrid.controllers', [])

.controller('LG_IndexController', function ($scope, $rootScope, $location, $state, LayouterGrid) {
	var locc = $location.$$absUrl
	
	$scope.app = LayouterGrid.get(function(data){
		$scope.$watch('app', saveAppData, true);
	});

	
	function saveAppData(new_val,old_val) {
		if (typeof old_val !== 'undefined')
		if (!angular.equals(new_val,old_val)) {
			LayouterGrid.save($scope.app)
		}


	}

	$scope.add_new = function() {
		var new_arr = {
			active:false,
			lang:'',
			texts:['','','','','','','','']
		}
		$scope.app.data.languages.push(new_arr)
	}

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
})