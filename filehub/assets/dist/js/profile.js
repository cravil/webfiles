function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      
      reader.onload = function(e) {
        $('#ppic').attr('src', e.target.result);
        document.getElementById('ppic-save').style.display = 'block';
      }
      
      reader.readAsDataURL(input.files[0]);
    }
  }
  $("#imgInp").change(function() {
    readURL(this);
  });
  $('#upload_form').on('submit', function(e){
  e.preventDefault();
  $.ajax({
      url:"../user/logo_update",
      method:"POST",
      data:new FormData(this),
      contentType: false,
      cache: false,
      
      processData: false,
      success:function(data)
      {
          var content = JSON.parse(data);
          swal(
              content.success == true ? 'Success!' : 'Error!',
              content.msg,
              content.success == true ? 'success' : 'error'
          );
      },
          error: function(data) {
              swal(
                  'Error!',
                  'There is an issue in saving your email template. Please try again later',
                  'error'
              );
          }
      })
  })