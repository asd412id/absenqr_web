<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('assets/vendor') }}/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendor') }}/bootstrap/dist/css/bootstrap.min.css">
    <style media="screen">
      html,body{
        width: 100%;height: 100%;margin: 0;padding: 0;
        /* font-size: 9pt !important; */
        font-family: Arial !important;
      }
      .page{
        padding: 0 20px;
      }
      .page-break{
        page-break-before: always;
      }
      table tr{
        page-break-inside: avoid !important;
      }
    </style>
  </head>
  <body>
    <div class="page">
      @include('layouts.kop')
      <h1 class="text-center font-weight-bold">{{ $title }}</h1>
      <div class="text-center">
        {!! \QrCode::size('350')->generate($data->_token) !!}
      </div>
      @if ($data->desc)
        <h3 class="text-center" style="margin: 0">
          {{ $data->desc }}
        </h3>
      @endif
    </div>
  </body>
</html>
