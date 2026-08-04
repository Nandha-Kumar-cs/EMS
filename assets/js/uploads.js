
$(document).ready(function(){
    loadEmployees();
    loadUploads();
});

function esc(value){
    return $('<div>').text(value == null ? '' : value).html();
}

function loadEmployees(){
    let formData = new FormData();
    formData.append('action', 'options');

    $.ajax({
        url : '../api/employee.php' , 
        type : 'POST' ,
        dataType : 'json' ,
        processData : false ,
        contentType : false ,
        data : formData , 
        success : function(response){
            if(response.status == 200){
                let options = '<option value="">Select Employee</option>';
                response.employees.forEach(function(emp){
                    options += '<option value="' + emp.id + '">' + esc(emp.employee_name) + '</option>';
                });
                $('#employee_id').html(options);
            }
        }
    });
}

function formatSize(bytes){
    if(bytes >= 1024 * 1024){
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }
    return (bytes / 1024).toFixed(1) + ' KB';
}

function loadUploads(){
    let formData = new FormData();
    formData.append('action', 'list');

    $.ajax({
        url : '../api/upload.php' , 
        type : 'POST' ,
        dataType : 'json' ,
        processData : false ,
        contentType : false ,
        data : formData , 
        success : function(response){
            if(response.status == 200){
                renderUploads(response.files);
            }
        }
    });
}

function renderUploads(files){
    let rows = '';
    if(files.length == 0){
        rows = '<tr><td colspan="7" class="px-4 py-4 text-center text-slate-500">No files uploaded yet !</td></tr>';
    } else {
        files.forEach(function(file, index){
            rows += '<tr class="border-t border-slate-200">'
                + '<td class="px-4 py-3">' + (index + 1) + '</td>'
                + '<td class="px-4 py-3">' + esc(file.original_name) + '</td>'
                + '<td class="px-4 py-3">' + esc(file.employee_name) + '</td>'
                + '<td class="px-4 py-3">' + esc(file.file_type) + '</td>'
                + '<td class="px-4 py-3">' + formatSize(file.file_size) + '</td>'
                + '<td class="px-4 py-3">' + esc(file.created_at) + '</td>'
                + '<td class="px-4 py-3 flex gap-2">'
                + '<a href="../uploads/' + esc(file.stored_name) + '" download class="bg-blue-900 hover:bg-blue-800 text-white text-xs px-3 py-1.5 rounded-lg font-semibold transition">Download</a>'
                + '<button onclick="deleteUpload(' + file.id + ')" class="bg-red-700 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded-lg font-semibold transition">Delete</button>'
                + '</td>'
                + '</tr>';
        });
    }
    $('#upload_table_body').html(rows);
}

function deleteUpload(id){
    if(!confirm('Are you sure you want to delete this file ?')){
        return;
    }
    let formData = new FormData();
    formData.append('action', 'delete');
    formData.append('upload_id', id);

    $.ajax({
        url : '../api/upload.php' , 
        type : 'POST' ,
        dataType : 'json' ,
        processData : false ,
        contentType : false ,
        data : formData , 
        success : function(){
            loadUploads();
        },
        error : function(xhr){
            let response = xhr.responseJSON;
            alert(response && response.message ? response.message : 'Something went wrong !');
        }
    });
}

$('#upload_form').on('submit' , function(e){
    e.preventDefault();
    $('.error-msg').text('');

    let formData = new FormData(this);
    formData.append('action', 'upload');

    $.ajax({
        url : '../api/upload.php' , 
        type : 'POST' ,
        dataType : 'json' ,
        processData : false ,
        contentType : false ,
        data : formData , 
        success : function(response){
            if(response.status == 201){
                $('#upload_message').text(response.message).removeClass('text-red-600').addClass('text-green-600');
                $('#upload_form')[0].reset();
                loadUploads();
            }
        },
        error : function(xhr){
            let response = xhr.responseJSON;
            if(response && response.errors){
                $('#file_error').text(response.errors.file || '');
                $('#employee_id_error').text(response.errors.employee_id || '');
            } else if(response && response.message){
                $('#upload_message').text(response.message).addClass('text-red-600');
            }
        }
    });
});
