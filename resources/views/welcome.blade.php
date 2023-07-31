<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>
    @if (Route::has('login'))
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="navbar-nav">
                @auth
                    <a href="{{ url('/home') }}" class="nav-item nav-link active">Home</a>
                @else
                    <a href="{{ route('login') }}" class="nav-item nav-link">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="nav-item nav-link">Register</a>
                    @endif
                @endauth                    
            </div>
        </nav>
    @endif

    @if (Auth::check())
        <div class="jumbotron m-5">
            <h4>Welcome Back {{ auth()->user()->name }}</h4>
            <hr>
            <a href="{{ route('home') }}">
                <p>Dashboard</p>
            </a> 
        </div>
        
    @else
        <div class="jumbotron m-5">
            <h4>Please Register or Login First</h4>
            <h6>{{ config('app.name', 'Laravel') }}</h6>
            <hr>
            <a href="{{ route('login') }}">
                <p>Log in</p>
            </a>             
            <a href="{{ route('register') }}">
                <p>Register</p>
            </a>
        </div>        
    @endif

</body>

</html>
