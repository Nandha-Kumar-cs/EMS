
let employeeTable;

$(document).ready(function(){

    employeeTable = $('#employee_table').DataTable({
        serverSide : true,
        processing : true,
        ajax : {
            url : '../api/employee.php' , 
            type : 'POST' ,
            data : function(d){
                d.action = 'datatables';
            }
        },
        columns : [
            { data : 'id' },
            { data : 'employee_name' },
            { data : 'email' },
            { data : 'mobile' },
            { data : 'department' },
            { data : 'designation' },
            { data : 'salary' },
            { data : 'date_of_joining' },
            { data : null, orderable : false, searchable : false, render : function(data, type, row){
                return '<button onclick="openEditModal(' + row.id + ')" class="bg-blue-900 hover:bg-blue-800 text-white text-xs px-3 py-1.5 rounded-lg font-semibold transition">Edit</button>'
                    + ' <button onclick="deleteEmployee(' + row.id + ')" class="bg-red-700 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded-lg font-semibold transition">Delete</button>';
            } }
        ],
        order : [[0, 'desc']],
        dom : 'rtip',
        pageLength : 10,
        scrollY : '50vh',
        scrollCollapse : true,
        language : {
            info : 'Showing _START_ to _END_ of _TOTAL_ records'
        }
    });

    employeeTable.columns().every(function(){
        let column = this;
        let input = $(column.footer()).find('input');
        if(input.length){
            input.on('keyup change', function(){
                column.search(this.value).draw();
            });
        }
    });
});

function openAddModal(){
    $('#employee_form')[0].reset();
    $('#employee_id').val('');
    $('#employee_modal_title').text('Add Employee');
    $('.error-msg').text('');
    $('#employee_modal').removeClass('hidden');
}

function openEditModal(id){
    let formData = new FormData();
    formData.append('action', 'get');
    formData.append('employee_id', id);

    $.ajax({
        url : '../api/employee.php' , 
        type : 'POST' ,
        dataType : 'json' ,
        processData : false ,
        contentType : false ,
        data : formData , 
        success : function(response){
            if(response.status == 200){
                let emp = response.employee;
                $('#employee_id').val(emp.id);
                $('#employee_name').val(emp.employee_name);
                $('#email').val(emp.email);
                $('#mobile').val(emp.mobile);
                $('#department').val(emp.department);
                $('#designation').val(emp.designation);
                $('#salary').val(emp.salary);
                $('#date_of_joining').val(emp.date_of_joining);
                $('#employee_modal_title').text('Edit Employee');
                $('.error-msg').text('');
                $('#employee_modal').removeClass('hidden');
            } else {
                alert(response.message);
            }
        }
    });
}

function closeEmployeeModal(){
    $('#employee_modal').addClass('hidden');
}

function deleteEmployee(id){
    if(!confirm('Are you sure you want to delete this employee ?')){
        return;
    }
    let formData = new FormData();
    formData.append('action', 'delete');
    formData.append('employee_id', id);

    $.ajax({
        url : '../api/employee.php' , 
        type : 'POST' ,
        dataType : 'json' ,
        processData : false ,
        contentType : false ,
        data : formData , 
        success : function(){
            employeeTable.ajax.reload();
        },
        error : function(xhr){
            let response = xhr.responseJSON;
            alert(response && response.message ? response.message : 'Something went wrong !');
        }
    });
}

$('#employee_form').on('submit' , function(e){
    e.preventDefault();
    $('.error-msg').text('');
    $('#employee_message').text('');

    let formData = new FormData(this);
    let employee_id = $('#employee_id').val();
    formData.append('action', employee_id ? 'update' : 'add');

    $.ajax({
        url : '../api/employee.php' , 
        type : 'POST' ,
        dataType : 'json' ,
        processData : false ,
        contentType : false ,
        data : formData , 
        success : function(){
            closeEmployeeModal();
            employeeTable.ajax.reload();
        },
        error : function(xhr){
            let response = xhr.responseJSON;
            if(response && response.errors){
                Object.keys(response.errors).forEach(function(key){
                    $('#' + key + '_error').text(response.errors[key]);
                });
            } else if(response && response.message){
                $('#employee_message').text(response.message).addClass('text-red-600');
            }
        }
    });
});
