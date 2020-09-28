@if (@$configs->template!='none')
  @include('layouts.kop-template.'.(@$configs->template??'atas'))
@endif
