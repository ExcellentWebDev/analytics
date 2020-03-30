<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Analytics event based on ticketmaster.quiktix.co" />
        <title>Analytics ticketmaster event</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="shortcut icon" href="{{'images/favicon.png'}}" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <body>
        <div id="app"></div>
        <script src="{{asset('js/app.js')}}" type="text/javascript"></script>
    </body>
</html>
