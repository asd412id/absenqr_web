@php
  $configs = \App\Configs::getAll();
  $percent = (int)str_replace('%','',request()->font_size??'100%');
@endphp
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
      html,body{
        width: 100%;height: 100%;margin: 0;padding: 0;
        /* font-size: 9pt !important; */
        font-family: Arial !important;
      }
      @if (@$configs->template!='none')
        @if (@$configs->template=='atas')
        img.img-logo{
          width: {{ (45*$percent/100)."px !important" }}
        }
        @elseif (@$configs->template=='samping')
        img.img-logo{
          height: {{ (75*$percent/100)."px !important" }}
        }
        @endif
      @endif
      .page{
        padding: 0 20px;
      }
      .page-break{
        page-break-before: always;
      }
      table tr{
        page-break-inside: avoid !important;
      }
      .text-center{
        text-align: center !important;
      }
      .font-weight-bold{
        font-weight: bold !important;
      }
      @page{
        margin: 40px 30px;
      }
    </style>
  </head>
  <body>
    <div class="page">
      @include('layouts.kop')
      <h2 class="text-center font-weight-bold" style="margin: 0">{{ $title }}</h2>
      <div class="text-center">
        {!! str_replace('<?xml version="1.0" encoding="UTF-8"?>','',\QrCode::size('350')->generate($data->_token)) !!}
      </div>
      @if ($data->desc)
        <h3 class="text-center" style="margin: 0">
          {{ $data->desc }}
        </h3>
      @endif
    </div>
  </body>
</html>
