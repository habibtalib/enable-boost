<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Boost Enabler for Billplz</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <section id="cover">
    <div id="cover-caption">
        <div id="container" class="container">
            <div class="row">
                <div class="col-sm-10 offset-sm-1 text-center">
                    <h1 class="display-3">Enable Boost for All Collection</h1>
                    <div class="info-form">
                        <form action="{{ route('boost') }}" class="form-inline justify-content-center" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="sr-only">API Key</label>
                                <input type="text" class="form-control" placeholder="Insert Your API Key Here" maxlength="50" size="50" name="api_key">
                            </div>
                            <button type="submit" class="btn btn-success ">Go!</button>
                        </form>
                    </div>
                    <br>

                    <a href="#nav-main" class="btn btn-secondary-outline btn-sm" role="button">↓</a>
                </div>
            </div>
        </div>
    </div>
</section>
    </body>
</html>
