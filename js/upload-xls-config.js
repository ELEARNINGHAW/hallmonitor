$(function(){
   $('#drag-and-drop-zone-0').dmUploader( dndini_0 );
   $('#drag-and-drop-zone-1').dmUploader( dndini_1 );
   $('#drag-and-drop-zone-2').dmUploader( dndini_2 );
});

var dndini_00 = {  url: 'upload-xls-0.php',  }
var dndini_01 = {  url: 'upload-xls-1.php',  }
var dndini_02 = {  url: 'upload-xls-2.php',  }

 var dndini = {
 url: 'upload-xls-0.php',
 extFilter: [ 'csv' ],
 maxFileSize: 5000000,
 onDragEnter:     function() { this.addClass('active');  $('#log').html("dare drop it")    },
 onDragLeave:     function() { this.removeClass('active');  $('#log').html("ready")  },
 onInit:          function() { $('#log').html("ready")  },
 onFileTypeError: function() { $('#log').html("wrong file type") },
 onFileExtError:  function() { $('#log').html("wrong file type") },
 onComplete :     function() { $('#log').html("complete OK")  },
 onNewFile:       function( id, file){ ui_add_log('New file added #' + id);
                                       ui_multi_add_file(id, file);
     $('#log').html("file added")},

 onBeforeUpload: function( id )
 {
   ui_add_log('Starting the upload of #' + id );
   ui_multi_update_file_status( id, 'uploading', 'uploading...' );
   ui_multi_update_file_progress( id, 0, '', true );
  },
  onUploadCanceled: function( id )
 {  ui_multi_update_file_progress( id, 0, 'warning', false );
  },
  onUploadProgress: function( id, percent ){ ui_multi_update_file_progress(id, percent); $('#log').html("loading...")   },
  onUploadSuccess: function( id, data )
  { $('#log').html("OK" )
    ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));
    ui_add_log('Upload of file #' + id + ' COMPLETED', 'success');
    ui_multi_update_file_status(id, 'success', 'Upload Complete');
    ui_multi_update_file_progress(id, 100, 'success', false);

  },
  onUploadError: function(id, xhr, status, message)
  { ui_multi_update_file_status(id, 'danger', message);
    ui_multi_update_file_progress(id, 0, 'danger', false);
      $('#log').html("ERROR")
  },
  onFallbackMode: function(){ ui_add_log('Plugin cant be used here, running Fallback callback', 'danger'); },
  onFileSizeError: function(file){ $('#log').html("file size ERROR")  }
};
var dndini_0 = Object.assign(dndini_00, dndini);
var dndini_1 = Object.assign(dndini_01, dndini);
var dndini_2 = Object.assign(dndini_02, dndini);

/*

var dndini_1 = {
    url: 'upload-xls-1.php',
    extFilter: [   'csv'  ],
    maxFileSize: 5000000,
    onDragEnter: function(){ this.addClass('active');    },
    onDragLeave: function(){ this.removeClass('active'); },
    onInit:      function(){ ui_add_log('Penguin initialized :)', 'info');  },
    onComplete : function(){ alert("IMPORT: OK"); },
    onNewFile:   function(id, file){ ui_add_log('New file added #' + id);
        ui_multi_add_file(id, file);  },
    onBeforeUpload: function(id)
    { ui_add_log('Starting the upload of #' + id);
        ui_multi_update_file_status(id, 'uploading', 'Uploading...');
        ui_multi_update_file_progress(id, 0, '', true);
    },
    onUploadCanceled: function(id)
    {  ui_multi_update_file_progress(id, 0, 'warning', false);
    },
    onUploadProgress: function(id, percent){ ui_multi_update_file_progress(id, percent); },
    onUploadSuccess: function(id, data)
    { ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));
        ui_add_log('Upload of file #' + id + ' COMPLETED', 'success');
        ui_multi_update_file_status(id, 'success', 'Upload Complete');
        ui_multi_update_file_progress(id, 100, 'success', false);
    },
    onUploadError: function(id, xhr, status, message)
    { ui_multi_update_file_status(id, 'danger', message);
        ui_multi_update_file_progress(id, 0, 'danger', false);
    },
    onFallbackMode: function(){ ui_add_log('Plugin cant be used here, running Fallback callback', 'danger'); },
    onFileSizeError: function(file){ }
};


var dndini_2 = {
    url: 'upload-xls-2.php',
    extFilter: [   'csv'  ],
    maxFileSize: 5000000,
    onDragEnter: function(){ this.addClass('active');    },
    onDragLeave: function(){ this.removeClass('active'); },
    onInit:      function(){ ui_add_log('Penguin initialized :)', 'info');  },
    onComplete : function(){ alert("IMPORT: OK"); },
    onNewFile:   function(id, file){ ui_add_log('New file added #' + id);
        ui_multi_add_file(id, file);  },
    onBeforeUpload: function(id)
    { ui_add_log('Starting the upload of #' + id);
        ui_multi_update_file_status(id, 'uploading', 'Uploading...');
        ui_multi_update_file_progress(id, 0, '', true);
    },
    onUploadCanceled: function(id)
    {  ui_multi_update_file_progress(id, 0, 'warning', false);
    },
    onUploadProgress: function(id, percent){ ui_multi_update_file_progress(id, percent); },
    onUploadSuccess: function(id, data)
    { ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));
        ui_add_log('Upload of file #' + id + ' COMPLETED', 'success');
        ui_multi_update_file_status(id, 'success', 'Upload Complete');
        ui_multi_update_file_progress(id, 100, 'success', false);
    },
    onUploadError: function(id, xhr, status, message)
    { ui_multi_update_file_status(id, 'danger', message);
        ui_multi_update_file_progress(id, 0, 'danger', false);
    },
    onFallbackMode: function(){ ui_add_log('Plugin cant be used here, running Fallback callback', 'danger'); },
    onFileSizeError: function(file){  }
};
 */