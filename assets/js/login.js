
$('#login_form').on('submit' , function(e){
    e.preventDefault();
    $('.error-msg').text('');
    let formData = new FormData(this);
    formData.append('action' , 'login');
    $.ajax({
        url : './api/auth.php' , 
        type : 'POST' ,
        dataType : 'json' ,
        processData :false ,
        contentType : false ,
        data : formData , 
        success:function(response){
            if(response.status == 200){
                window.location.href = './pages/dashboard.php';
            }
        }, 
        error:function(xhr){
            let response = xhr.responseJSON;
            if(response && response.errors){
                $('#email_error').text(response.errors.email || '');
                $('#password_error').text(response.errors.password || '');
            } else if(response && response.message){
                $('#login_message').text(response.message).addClass('text-red-600');
            }
        }
    });
})
