@php
  $configs = \App\Configs::getAll();
  $percent = (int)str_replace('%','',request()->font_size??'100%');
@endphp
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>{!! $title !!}</title>
    <style>
      html,body{
        width: 100%;height: 100%;margin: 0;padding: 0;
        font-family: Arial !important;
        font-size: {{ (13*$percent/100).'px !important' }};
      }
      .status-color{
        width: {{ (30*$percent/100).'px !important' }};
        height: {{ (30*$percent/100).'px !important' }};
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
      .table{
        width: 100%;
        margin: 0 auto;
        border: solid 1px #000;
        border-collapse: collapse;
        break-inside: auto;
      }
      .table th{
        text-align: center;
        vertical-align: middle !important;
      }
      .table th, .table td{
        border: solid 1px #000 !important;
        border-bottom: solid 1px #000;
        border-collapse: collapse;
      }
      .table td{
        text-align: center;
        vertical-align: top;
      }
      .text-center{
        text-align: center !important;
      }
      .nowrap{
        white-space: nowrap !important;
      }
      tr, td, th, tbody, thead, tfoot {
        page-break-inside: avoid !important;
        break-inside: avoid-page !important;
        page-break-before: auto !important;
        page-break-after: auto !important;
        vertical-align: middle !important;
      }
      .table th{
        text-transform: uppercase;
      }
      .table th, .table td{
        padding: 3px 4px;
        vertical-align: middle !important;
      }
      .desc{
        max-width: 225px;
      }
      .table .badge-dark{
        margin-bottom: 3px;
      }
      span.badge{
        display: block !important;
        padding: 2px 4px !important;
        margin: 1px !important;
        border-radius: 5px !important;
      }
      .badge-dark{
        background-color: rgba(191,128,255,.3) !important;
      }
      .bg-primary,.badge-primary{
        background-color: rgba(0,153,255,.3) !important;
      }
      .bg-warning,.badge-warning{
        background-color: rgba(255,255,0,.3) !important;
      }
      .bg-success,.badge-success{
        background-color: rgba(0,255,0,.3) !important;
      }
      .bg-danger,.badge-danger{
        background-color: rgba(255,0,0,.3) !important;
      }
      @page{
        margin: 20px 10px;
      }
    </style>
  </head>
  <body>
    <div class="page">
      @if (count($data))
        @include('layouts.kop')
        @php
          $carbon = new \Carbon\Carbon;
          $tanggal = request()->start_date==request()->end_date?$carbon->createFromFormat('Y/m/d',request()->start_date)->locale('id')->translatedFormat('l, d F Y'):$carbon->createFromFormat('Y/m/d',request()->start_date)->locale('id')->translatedFormat('d F Y').' s.d. '.$carbon->createFromFormat('Y/m/d',request()->end_date)->locale('id')->translatedFormat('d F Y');
        @endphp
        <h3 class="text-center" style="margin: 0;padding: 0">{{ request()->title??'Rekap Absens' }}</h3>
        <p class="text-center" style="margin: 0;padding: 0">{{ $tanggal }}</p>
        @include('absensi::logs.layouts.rekap-table')
        @include('layouts.ttd')
      @else
        <h1 style="text-align: center">Data tidak tersedia!</h1>
      @endif
    </div>
  </body>
</html>
