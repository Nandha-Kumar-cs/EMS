
$('#register_form').on('submit' , function(e){
    e.preventDefault();
    $('.error-msg').text('');
    let formData = new FormData(this);
    formData.append('action' , 'register');
    $.ajax({
        url : './api/auth.php' , 
        type : 'POST' ,
        dataType : 'json' ,
        processData :false ,
        contentType : false ,
        data : formData , 
        success:function(response){
            if(response.status == 201){
                $('#register_message').text(response.message).removeClass('text-red-600').addClass('text-green-600');
                $('#register_form')[0].reset();
            }
        }, 
        error:function(xhr){
            let response = xhr.responseJSON;
            if(response && response.errors){
                $('#username_error').text(response.errors.username || '');
                $('#email_error').text(response.errors.email || '');
                $('#password_error').text(response.errors.password || '');
            } else if(response && response.message){
                $('#register_message').text(response.message).addClass('text-red-600');
            }
        }
    });
})
