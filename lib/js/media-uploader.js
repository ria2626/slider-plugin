jQuery(function($){
  var delete_icon_url =  $('#plugin_url').val();
    // Set all variables to be used in scope
    var frame,
        metaBox = $('#image_uploader_metabox.postbox'); // Your meta box id here
        addImgLink = metaBox.find('.upload-custom-img');
        imgContainer = metaBox.find( '.custom-img-container');
        imgIdInput = metaBox.find( '.custom-img-id' );
        customImgDiv = metaBox.find( '#custom-images' );


    
    // ADD IMAGE LINK
    addImgLink.on( 'click', function( event ){
      
      event.preventDefault();
     
      // If the media frame already exists, reopen it.
      if ( frame ) {
        frame.open();
        return;
      }
      
      // Create a new media frame
      frame = wp.media({
        title: 'Select or Upload Image for Slideshow',
        button: {
          text: 'Use this Image'
        },
        multiple: false  // Set to true to allow multiple files to be selected
      });

      
      // When an image is selected in the media frame...
      frame.on( 'select', function() {
        
        // Get media attachment details from the frame state
        var attachment = frame.state().get('selection').first().toJSON();
        
        // Send the attachment URL to our custom image input field.
     
        imgContainer.append( '<div class="image-wrapper"><img src="'+attachment.url+'" width="200"><input type="hidden" name="image_src[]" value="'+attachment.url+'"> 	<div class="delete-custom-img-main" ><a href="#" class="delete-custom-img"><img src="'+delete_icon_url+'"></a></div></div>' );
        
      });
      // Finally, open the modal on click
      frame.open();
    });

    
      customImgDiv.on ( 'click', '.delete-custom-img', function (event){		
          event.preventDefault();
          jQuery(event.target).parents('.image-wrapper').remove();		

      });

    
        $( "#sortable1" ).sortable({
      
        }).disableSelection();
     
  });