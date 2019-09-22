(function($) {
  showSuccessToast = function(text) {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Berhasil!',
      text: text,
      showHideTransition: 'slide',
      icon: 'success',
      loaderBg: '#f96868',
      position: 'top-right'
    })
  };
  showInfoToast = function() {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Info',
      text: 'And these were just the basic demos! Scroll down to check further details on how to customize the output.',
      showHideTransition: 'slide',
      icon: 'info',
      loaderBg: '#46c35f',
      position: 'top-right'
    })
  };
  showWarningToast = function() {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Warning',
      text: 'And these were just the basic demos! Scroll down to check further details on how to customize the output.',
      showHideTransition: 'slide',
      icon: 'warning',
      loaderBg: '#57c7d4',
      position: 'top-right'
    })
  };
  showDangerToast = function(text) {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Kesalahan!',
      text: text,
      showHideTransition: 'slide',
      icon: 'error',
      loaderBg: '#f2a654',
      position: 'top-right'
    })
  };
  showToastPosition = function(position) {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Positioning',
      text: 'Specify the custom position object or use one of the predefined ones',
      position: String(position),
      icon: 'success',
      stack: false,
      loaderBg: '#f96868'
    })
  }
  showToastInCustomPosition = function() {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Custom positioning',
      text: 'Specify the custom position object or use one of the predefined ones',
      icon: 'success',
      position: {
        left: 120,
        top: 120
      },
      stack: false,
      loaderBg: '#f96868'
    })
  }
  resetToastPosition = function() {
    $('.jq-toast-wrap').removeClass('bottom-left bottom-right top-left top-right mid-center'); // to remove previous position class
    $(".jq-toast-wrap").css({
      "top": "",
      "left": "",
      "bottom": "",
      "right": ""
    }); //to remove previous position style
  }
})(jQuery);

var language = {
  "decimal":        "",
  "emptyTable":     "Data tidak tersedia",
  "info":           "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
  "infoEmpty":      "Menampilkan 0 hingga 0 dari 0 data",
  "infoFiltered":   "(Difilter dari _MAX_ total data)",
  "infoPostFix":    "",
  "thousands":      ",",
  "lengthMenu":     "Menampilkan _MENU_ data",
  "loadingRecords": "Memuat...",
  "processing":     "Memproses...",
  "search":         "Cari:",
  "zeroRecords":    "Pencarian tidak ditemukan",
  "paginate": {
    "first":      "Pertama",
    "last":       "Terakhir",
    "next":       "Selanjutnya",
    "previous":   "Sebelumnya"
  }
};

$(".hapus").on('click',function(){
  if (!confirm('Hapus data ini?')) {
    return false;
  }
});

$('.pendidikan').repeater({
  // (Optional)
  // "defaultValues" sets the values of added items.  The keys of
  // defaultValues refer to the value of the input's name attribute.
  // If a default value is not specified for an input, then it will
  // have its value cleared.
  defaultValues: {
    'status_pendidikan': 'negeri',
  },
  // (Optional)
  // "show" is called just after an item is added.  The item is hidden
  // at this point.  If a show callback is not given the item will
  // have $(this).show() called on it.
  show: function() {
    $(this).slideDown();
  },
  // (Optional)
  // "hide" is called when a user clicks on a data-repeater-delete
  // element.  The item is still visible.  "hide" is passed a function
  // as its first argument which will properly remove the item.
  // "hide" allows for a confirmation step, to send a delete request
  // to the server, etc.  If a hide callback is not given the item
  // will be deleted.
  hide: function(deleteElement) {
    if (confirm('Hapus data pada baris ini?')) {
      $(this).slideUp(deleteElement);
    }
  },
  // (Optional)
  // Removes the delete button from the first list item,
  // defaults to false.
  isFirstItemUndeletable: true
});

$('.file-upload-browse').on('click', function(e) {
  e.stopPropagation();
  e.stopImmediatePropagation();
  var file = $(this).parent().parent().parent().find('.file-upload-default');
  file.trigger('click');
});
$('.file-upload-default').on('change', function(e) {
  e.stopPropagation();
  e.stopImmediatePropagation();
  $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
});

$("#form-import").submit(function(){
  $(this).find("button[type='submit']").prop('disabled',true);
  $(this).find("button[type='submit']").html('Sedang mengimport ...');
})

if ($("#table-siswa").length>0) {
  var table = $("#table-siswa").DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: location.href,
    columns: [
      {data: 'nisn', name: 'nisn'},
      {data: 'nis', name: 'nis'},
      {data: 'nama_lengkap', name: 'nama_lengkap'},
      {data: 'jk', name: 'jk'},
      {data: 'ttl', name: 'ttl'},
      {data: 'asal_sekolah', name: 'asal_sekolah'},
      {data: 'action', name: 'action', orderable: false, searchable: false}
    ],
    "language": language,
    'drawCallback': function(settings){
      var start = (settings.json.input.start/settings.json.input.length)+1;
      var rows = settings.json.input.length;
      var dta = settings.json.input.search.value;
      var uri = 'all';
      if (dta!=null) {
        uri = encodeURIComponent(dta.trim());
      }
      $(".btn-print").prop('href',location.href+'/ekspor-pdf?q='+uri+'&rows='+rows+'&page='+start);
      $(".hapus").on('click',function(){
        if (!confirm('Hapus data ini?')) {
          return false;
        }
      });
    }
  });
}
if ($("#table-pegawai").length>0) {
  var table = $("#table-pegawai").DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: location.href,
    columns: [
      {data: 'nip', name: 'nip'},
      {data: 'nama', name: 'nama'},
      {data: 'jk', name: 'jk'},
      {data: 'skep', name: 'skep'},
      {data: 'jabatan', name: 'jabatan'},
      {data: 'action', name: 'action', orderable: false, searchable: false},
      {data: 'pangkat_golongan', name: 'pangkat_golongan', orderable: false, visible: false}
    ],
    "language": language,
    'drawCallback': function(settings){
      var start = (settings.json.input.start/settings.json.input.length)+1;
      var rows = settings.json.input.length;
      var dta = settings.json.input.search.value;
      var uri = 'all';
      if (dta!=null) {
        uri = encodeURIComponent(dta.trim());
      }
      $(".btn-print").prop('href',location.href+'/ekspor-pdf?q='+uri+'&rows='+rows+'&page='+start);
      $(".hapus").on('click',function(){
        if (!confirm('Hapus data ini?')) {
          return false;
        }
      });
    }
  });
}
