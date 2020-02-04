@php
  $configs = \App\Configs::getAll();
@endphp
@include('layouts.kop-template.'.(@$configs->template??'atas'))
