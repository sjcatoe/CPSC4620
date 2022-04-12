<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />


    <link rel="stylesheet" href="home.css" />
    <title>MeTube</title>
  </head>



  
<head>
    <!-- import bootstrap cdn-->
    <link rel="stylesheet" href=
"https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity=
"sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z"
        crossorigin="anonymous" />
  
    <!-- import jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity=
"sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous">
    </script>
      
    <!-- import popper.js cdn -->
    <script src=
"https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity=
"sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous">
    </script>
      
    <!-- import javascript cdn -->
    <script src=
"https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity=
"sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
        crossorigin="anonymous">
    </script>

</head>
  
<body>
    <!-- top navbar -->
    <nav class="navbar navbar-expand-lg 
            navbar-light bg-primary">
        <a class="navbar-brand" href="#">Navbar</a>
        <!-- hamburger button that toggles the navbar-->
        <button class="navbar-toggler" type="button" 
            data-toggle="collapse" 
            data-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" 
            aria-expanded="false" 
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
            </span>
        </button>
        <!-- navbar links -->
        <div class="collapse navbar-collapse" 
                id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link 
                    active" href="#">Home</a>
                <a class="nav-item nav-link" 
                    href="#">Features</a>
                <a class="nav-item nav-link" 
                    href="#">Price</a>
                <a class="nav-item nav-link" 
                    href="#">About</a>
            </div>
        </div>
    </nav>
  
    <!-- This container contains the sidebar
             and main content of the page -->
    <!-- h-100 takes the full height of the body-->
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-2" id="green">
                <h4>Sidebar</h4>
  
                <!-- Navigation links in sidebar-->
                <a href="#">Link 1</a><br />
                <br />
                <a href="#">Link 2</a><br />
                <br />
                <a href="#">Link 3</a><br />
                <br />
                <a href="#">Link 4</a><br />
                <br />
            </div>
            <!--Contains the main content
                    of the webpage-->
            <div class="col-10" style="text-align: justify;">

            </div>
        </div>
    </div>
</body>
  
</html>



