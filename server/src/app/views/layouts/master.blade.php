<html>
    <head>
      <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>

    </head>
    <body>
        @section('sidebar')
            
        @show

        <div class="container">

            @yield('content')
            <a href="{{ URL::to('school') }}">Atras</a>
        </div>
    </body>
</html>

<script type="text/javascript">
  @yield('scripts')
</script>
