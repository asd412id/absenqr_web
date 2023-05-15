@if (@$configs->logo)
  <div style="text-align: center;margin-bottom: 5px">
    <img class="img-logo" src="{{ asset('uploaded/' . @$configs->logo) }}" alt=""
      style="display: inline;width: 17cm">
  </div>
  <div
    style="border-top: solid 3px #000;border-bottom: solid 1px #000;margin-top: 3px;margin-bottom: 15px;padding: 1px 0;">
  </div>
@endif
