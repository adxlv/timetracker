<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" ng-app="Timetracker">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>TimeTracker</title>
    

    <script src="/js/vendor/modernizr.js"></script>
    <script src="/js/vendor/jquery.min.js"></script>
    <script src="/js/vendor/fastclick.js"></script>

    <script src="/js/foundation/foundation.min.js"></script>
    <!-- // <script src="/js/foundation/foundation.equalizer.js"></script> -->
    
    <!-- <link href='css/onepage-scroll.css' rel='stylesheet' type='text/css'> -->
    <link href='/css/app.css' rel='stylesheet' type='text/css'>
    <link href='/css/screen.css' rel='stylesheet' type='text/css'>

 
 
  </head>
 
  <body class="preload-animations-disabled">
  <div class="app-content"><div class="page-login">
    
    <div class="row login">
      <div class="small-4 small-centered columns">
        
        <h1>Welcome</h1>
        <h4 class="subheader">please log in</h4>
        
        <form name="login_form" method="post">
          <div class="row">
            <div class="columns">
                  <input class="error" id="username" name="username" type="text" placeholder="Username" value="{% $user %}"/>
                  <input class="" id="password" name="password" type="password" placeholder="Password" />
              
              <div class="row collapse">
                <div class="columns">
                  <input type="submit" class="button small expand" value="Login">
                </div>
              </div>

            @if ($error)
              <div data-alert class="alert-box {% $type %}">
                <p>Sorry, but {% $msg %}</p>
                <p>Don't be shy, Try again!</p>
                <a href="#" class="close">&times;</a>
              </div>
            @endif
              


            </div>
          </div>
        </form>

      </div>
    </div>
  
  </div></div>

    <script type="text/javascript">
      $(document).foundation();
      $('#username').focus()
    </script>


  </body>
</html>
<!-- - See more at: http://laravelsnippets.com/snippets/angularjs-with-laravel#sthash.aqwCk1yz.dpuf -->