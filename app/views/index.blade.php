@extends('layouts.master')

@section('body_contents')

    <div class="app-container">
        <nav class="app-menu">
            @include('partials.menu')
        </nav>

        <div class="app-pusher">
            <div class="app-top">
                <div class="container"> @include('partials.top') </div>
            </div>

            <div class="container">
                <ul class="breadcrumbs" ng-controller="BreadCrumbs">
                    <li ng-repeat="page in url" class="@{{($last)?'current':''}} @{{(page.disabled)?'unavailable':''}}">
                        <a ng-href="@{{(page.disabled || $last)?'':page.href}}">@{{ ($first)?'Home':page.name; }}</a>
                    </li>
                </ul>
                <div class="app-content" ui-view ></div>
            </div>
        </div>

    </div>
@stop

