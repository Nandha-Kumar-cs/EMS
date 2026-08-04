
$(document).ready(function(){

    let formData = new FormData();
    formData.append('action', 'summary');

    $.ajax({
        url : '../api/dashboard.php' , 
        type : 'POST' ,
        dataType : 'json' ,
        processData : false ,
        contentType : false ,
        data : formData , 
        success : function(response){
            if(response.status == 200){
                $('#total_employees').text(response.total_employees);
                $('#total_departments').text(response.total_departments);
                $('#total_designations').text(response.total_designations);
                $('#total_payroll').text(response.total_payroll);
                renderRecentEmployees(response.recent_employees);
            }
        }
    });
});

function esc(value){
    return $('<div>').text(value == null ? '' : value).html();
}

function renderRecentEmployees(employees){
    let rows = '';
    if(employees.length == 0){
        rows = '<tr><td colspan="4" class="px-4 py-4 text-center text-slate-500">No employees yet !</td></tr>';
    } else {
        employees.forEach(function(emp){
            rows += '<tr class="border-t border-slate-200">'
                + '<td class="px-4 py-3">' + esc(emp.employee_name) + '</td>'
                + '<td class="px-4 py-3">' + esc(emp.department) + '</td>'
                + '<td class="px-4 py-3">' + esc(emp.designation) + '</td>'
                + '<td class="px-4 py-3">' + esc(emp.date_of_joining) + '</td>'
                + '</tr>';
        });
    }
    $('#recent_table_body').html(rows);
}
