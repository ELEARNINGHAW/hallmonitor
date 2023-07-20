$(function(){
  /*
   * For the sake keeping the code clean and the examples simple this file
   * contains only the plugin configuration & callbacks.
   * 
   * UI functions ui_* can be located in: demo-ui.js
   */
  
  $('#drag-and-drop-zone').dmUploader( 
      dndini
  );
});



 var dndini = {
 
 url: 'backend/upload.php',
 extFilter: [ 'jpg', 'pdf', 'png', 'gif' ],
 maxFileSize: 3000000, 
 onDragEnter: function(){ this.addClass('active');    },    
 onDragLeave: function(){ this.removeClass('active'); },   
 onInit:      function(){     ui_add_log('Penguin initialized :)', 'info'); 
                       },                            
 onComplete : function(){    
                       },    
 onNewFile:   function(id, file){ ui_add_log('New file added #' + id);
                                  ui_multi_add_file(id, file); 
 			                   }, 
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





const dndini2 = " url: 'backend/upload.php'," + 
   "\n extFilter: [ 'jpg', 'pdf', 'png', 'gif' ]," + 
   "\n maxFileSize: 3000000, " + 
   "\n onDragEnter: function(){ this.addClass('active');    },    " + 
   "\n onDragLeave: function(){ this.removeClass('active'); },   " + 
   "\n onInit:      function(){     ui_add_log('Penguin initialized :)', 'info'); " + 
   "\n                       },                            " + 
   "\n onComplete : function(){    " + 
   "\n                       },    " + 
   "\n onNewFile:   function(id, file){ ui_add_log('New file added #' + id);" + 
   "\n                                  ui_multi_add_file(id, file); " + 
   "\n 			                   }, " + 
   "\n  onBeforeUpload: function(id)" + 
   "\n { ui_add_log('Starting the upload of #' + id);" + 
   "\n   ui_multi_update_file_status(id, 'uploading', 'Uploading...');" + 
   "\n   ui_multi_update_file_progress(id, 0, '', true);" + 
   "\n  },  " + 
   "\n  onUploadCanceled: function(id) " + 
   "\n {  ui_multi_update_file_progress(id, 0, 'warning', false);   " + 
   "\n  }, " + 
   "\n  onUploadProgress: function(id, percent){ ui_multi_update_file_progress(id, percent); }," + 
   "\n  onUploadSuccess: function(id, data)" + 
   "\n  { ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));" + 
   "\n     ui_add_log('Upload of file #' + id + ' COMPLETED', 'success');" + 
   "\n    ui_multi_update_file_status(id, 'success', 'Upload Complete');" + 
   "\n    ui_multi_update_file_progress(id, 100, 'success', false);" + 
   "\n  }, " + 
   "\n  onUploadError: function(id, xhr, status, message)" + 
   "\n  { ui_multi_update_file_status(id, 'danger', message);" + 
   "\n    ui_multi_update_file_progress(id, 0, 'danger', false);  " + 
   "\n  }," +
   "\n  onFallbackMode: function(){ ui_add_log('Plugin cant be used here, running Fallback callback', 'danger'); }," + 
   "\n  onFileSizeError: function(file){ // ui_add_log('File \'' + file.name + '\' cannot be added: size excess limit', 'danger'); " + 
   "\n                                }   ";

