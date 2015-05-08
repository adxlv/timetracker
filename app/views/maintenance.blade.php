@extends('layouts.master')

@section('body_contents')

    <div class="app-container desktopapp">
        <div class="app-pusher">
            <div class="app-top">
                <div class="container">@include('partials.top')
                    <div class="logout">
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="app-content">

                    <div class="row"> <br><br><br><br><br><br><br>
                    <h1>App Maintenance</h1>
                    <br>
                      <div class="small-12 medium-12 large-12 columns">
                          <h3><small>Guntis is making some awesome updates so please try to log in later...</small> </h3>
                      </div>
                    </div>
                    
                </div>
            </div>

        </div>
    </div>

@stop