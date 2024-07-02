{{-- Illuminate/Foundation/Exceptions/views --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>@yield('title')</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

  <!-- Styles -->
  <style>
  html, body {
    background-color: #fff;
    color: #636b6f;
    font-family: 'Raleway', sans-serif;
    font-weight: 100;
    height: 100vh;
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

  .content {
    text-align: center;
  }

  .title {
    font-size: 36px;
    padding: 20px;
    color: #ffffff
  }
</style>
</head>
<body>
  <div class="flex-center position-ref full-height" style="background-color: black;">
    <div class="content">
      <div class="page-status">
      <div class="image">
        <img src="{{ asset('img/page-status.png') }}" alt="status">
      </div>
      <div class="title">
        Estamos actualizando la web. <br> En breve podrás seguir comprando!
        <!-- <img src="" alt="out-of-service"> -->
      </div>
      </div>
     
    </div>
  </div>
</body>
</html>
