<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{config('app_name')}}</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                
                
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .m-b-md {
                margin: 30px;
            }
            .form-group
            {
                margin-bottom:10px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="card">
                    <div class=" m-b-md">
                    @if(count($errors))
                        <div class="alert alert-danger" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                  <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="/search" method="get">
                        <div class="form-group">
                            <label for="meal_name">Meal Name</label>
                            <input type="text" id="meal_name" name="meal_name" value="{{old('meal_name')}}" class="form-control" placeholder="ex: meal, meal tst">
                        </div>
                    
                        <div class="form-group">
                            <label for="map_coordinates">Map Coordinates</label>
                            <input type="text" id="map_coordinates" name="map_coordinates" value="{{old('map_coordinates')}}" class="form-control" placeholder="ex: 30.012647,31.210113">
                        </div>
                        <input type="submit" class="btn btn-primary" value="search" />
                    </form>
                    <br/>
                    @if(isset($restaurants)&&count($restaurants)&&!count($errors))
                        <table style="width:100%">
                            <tr>
                                <th>Restaurant Name</th>
                            </tr>
                            @foreach($restaurants as $restaurant)
                                <tr>
                                    <td>{{$restaurant->restaurant_name}}</td>
                                </tr>
                            @endforeach
                        </table>
                    @endif 
                    </div>
                </div>
               
            </div>
        </div>
    </body>
</html>
