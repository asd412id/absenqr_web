@php
  $configs = \App\Configs::getAll();
@endphp
@if (@$configs->template!='none')
  @include('layouts.kop-template.'.(@$configs->template??'atas'))
@endif
