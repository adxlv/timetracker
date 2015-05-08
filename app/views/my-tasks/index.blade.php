<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" ng-app="Timetracker">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">


		<title>TimeTracker</title>


 	 	<script src="/js/vendor/modernizr.js"></script>
 	 	<script src="/js/vendor/jquery.min.js"></script>
 	 	<script src="/js/vendor/underscore-min.js"></script>
		<script src="/js/vendor/fastclick.js"></script>
		
		<script src="/bower_components/angular/angular.min.js"></script>

		<script src="/bower_components/angular-resource/angular-resource.min.js"></script>
		<!-- // <script src="/bower_components/angular-route/angular-route.min.js"></script> -->
		<script src="/bower_components/angular-ui-router/release/angular-ui-router.min.js"></script>
		<script src="/bower_components/angular-animate/angular-animate.min.js"></script>
 		
 		
 	 	<script src='//cdn.firebase.com/js/client/1.0.15/firebase.js'></script>
 	 	<script src="//cdn.firebase.com/libs/angularfire/0.7.1/angularfire.min.js"></script>


		<script src="/bower_components/d3/d3.min.js"></script>
		<script src="/bower_components/c3/c3.min.js"></script>




 		<script src="/js/foundation/foundation.min.js"></script>
 		<!-- // <script src="/js/foundation/foundation.equalizer.js"></script> -->
 		

 		<!-- <link href='css/onepage-scroll.css' rel='stylesheet' type='text/css'> -->
		<link href='/css/app.css' rel='stylesheet' type='text/css'>
		<link href='/css/screen.css' rel='stylesheet' type='text/css'>
		<link href='/bower_components/c3/c3.css' rel='stylesheet' type='text/css'>

		

 
 
	</head>
 
	<body>

		<div class="app-container desktopapp">
			
	 
			<div class="app-pusher">
				<div class="app-top">
					<div class="container"> @include('partials.top') 
						<div class="logout">
							<a href="/logoutfromdesktopapp">LogOut</a>
						</div>
					</div>
				</div>
				
				<div class="container">
					<div class="app-content" ui-view ngAnimate ngSwitch> </div>
				</div>

			</div>
			
		</div>
 


	<link href='/css/dragdealer.css' rel='stylesheet' type='text/css'>

	<script src="/js/min/dragdealer-ck.js"></script>
	<script src="/js/vendor/moment.min.js"></script>
	<script src="/js/vendor/fuse-min.js"></script>

	<!-- https://github.com/angular-ui/ui-utils -->
	<!-- // <script type="text/javascript" src="bower_components/angular-ui-utils/ui-utils.js"></script> -->

	<!-- // <script src="/js/errors.js"></script> -->
	<script src="/js/services.js"></script>
	<script src="/js/controllers.js"></script>
	<script src="/js/app.js"></script>
	
	<script src="/js/interaction.js"></script>
	
	<!-- // <script src="app/js/filters.js"></script> -->
	<!-- // <script src="app/js/directives.js"></script> -->
	<!-- // <script src="app/js/date.js"></script> -->


	 <script>
		angular.module("Timetracker").constant("CSRF_TOKEN", '<?php echo csrf_token(); ?>');
		// 
		// var dataRef = new Firebase('/');
    	// dataRef.set("I am now writing data into Firebase!");
	 </script>
	</body>
</html>
<!-- - See more at: http://laravelsnippets.com/snippets/angularjs-with-laravel#sthash.aqwCk1yz.dpuf -->