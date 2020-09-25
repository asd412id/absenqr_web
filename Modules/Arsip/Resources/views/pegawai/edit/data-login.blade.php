<div class="card">
  <div class="card-header">
    <h3>Data Login</h3>
  </div>
  <div class="card-body">
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" name="username" class="form-control" id="username" value="{{ @$data->user->username }}" placeholder="Username" required>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" name="password" class="form-control" id="password" value="" placeholder="Kosongkan jika tidak ingin diubah">
    </div>
    <div class="form-group">
      <label for="urutan">Urutan</label>
      <input type="number" name="urutan" class="form-control" id="urutan" value="{{ @$data->user->urutan??9999 }}" placeholder="Urutan" required>
    </div>
  </div>
</div>
