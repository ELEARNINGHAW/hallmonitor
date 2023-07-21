$(function(){
   $('#drag-and-drop-zone-0').dmUploader( dndini_0 );
   $('#drag-and-drop-zone-1').dmUploader( dndini_1 );
   $('#drag-and-drop-zone-2').dmUploader( dndini_2 );
});

 var dndini_0 = {
 url: 'upload-xls-0.php',
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
  onFileSizeError: function(file){ /* ui_add_log('File '' + file.name + '' cannot be added: size excess limit', 'danger');*/ }
};

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
    onFileSizeError: function(file){ /* ui_add_log('File '' + file.name + '' cannot be added: size excess limit', 'danger');*/ }
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
    onFileSizeError: function(file){ /* ui_add_log('File '' + file.name + '' cannot be added: size excess limit', 'danger');*/ }
};