
/* Errors */
var app = angular.module('Timetracker.errors', ['ngResource'])

	.factory('$exceptionHandler', function () {
		return function (exception, cause) {
			console.error('HELLO THIS IS MINE ERROR::');
			exception.message += ' (caused by "' + cause + '")';
			throw exception;
		};
	});