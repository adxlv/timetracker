@extends('layouts.master')

@section('body_contents')

	<div class="app-container desktopapp">
	    <div class="app-pusher">
	        <div class="app-top">
	            <div class="container">@include('partials.top')
	                <div class="logout">
	                    <a id="test-notification" ng-show="true" style="display:none">Test Notification</a>
	                    <a href="/logout">LogOut</a>
	                </div>
	            </div>
	        </div>

	        <div class="container">
	            <div class="app-content" ui-view ngAnimate ngSwitch></div>
	        </div>

	    </div>
	</div>

@stop