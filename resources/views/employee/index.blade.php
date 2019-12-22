@extends('layouts.app')

@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">employee</div>
                    <button type="button" class="btn btn-primary" id="create_new_employee">Create New Employee</button>


                </div>
            </div>
        </div>


        <div class="container">
            <table id="employee_list" class="table table-hover table-condensed" style="width:100%">
                <thead>
                <tr>
                    <th>Company Name</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
        <div class="modal fade" id="createEmployee" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="create_update_employee">Create Employee</h4>
                    </div>
                    <div class="modal-body">

                        <form method="post" action="" id="create_employee">

                            <div class="form-group employee_update_hide">
                                <label for="select_company">Company Name:</label>

                                <select id="select_company" class="form-control" name="select_company">
                                    @foreach($companies as $company)
                                        <option class="form-group" value="{{$company->id}}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="hidden" id="employee_id_update" name="employee_id_update" value="">

                            <div class="form-group ">
                                <label for="employee_name">Name: *</label>
                                <input type="text" class="form-control" id="employee_name" name="employee_name">
                                <span id="employee_name_error" class="error_class"></span>
                            </div>

                            <div class="form-group">
                                <label for="employee_email">Email: *</label>
                                <input type="email" class="form-control" id="email" name="email">
                                <span id="employee_email_error" class="error_class"></span>
                            </div>

                            <div class="form-group">
                                <label for="employee_phone">Phone: *</label>
                                <input type="text" class="form-control" id="employee_phone" name="employee_phone">
                                <span id="employee_phone_error" class="error_class"></span>

                            </div>

                            <button type="button" class="btn btn-default" id="create_employee_click">Create</button>
                            <button type="button" class="btn btn-default" id="update_employee_click">Update</button>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>


    </div>
@endsection

@section('myjsfile')

    <script>

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#create_new_employee").click(function () {
                $(".error_class").text('');
                $("#employee_name").val('');
                $("#email").val('');
                $("#employee_phone").val('');
                $("#employee_id_update").val('');
                $('.employee_update_hide').show();
                $("#update_employee_click").hide();
                $("#create_employee_click").show();
                $("#create_update_employee").text('Create employee');
                $("#createEmployee").modal('show');
            });

            $("#create_employee_click").click(function (e) {
                e.preventDefault();
                var form = $('#create_employee')[0];
                var formdata = new FormData(form);
                $.ajax({
                        cache: false,
                        url: '{{url('employee')}}',
                        type: "POST",
                        data: formdata,
                        contentType: false,
                        processData: false,
                        success: function (html) {
                            notification('Created successfully!!','error');
                            $("#createEmployee").modal('hide');
                            var table = $('#employee_list').DataTable();
                            table.ajax.reload();
                        },
                        error: function (errors) {
                            notification('Please check error!!','error');
                            var Obj = JSON.parse(errors.responseText);
                            if (Obj.errors.employee_name) {
                                $("#employee_name_error").html(Obj.errors.employee_name[0]);
                            } else {
                                $("#employee_name_error").html('');
                            }
                            if (Obj.errors.email) {
                                $("#employee_email_error").html(Obj.errors.email[0]);
                            } else {
                                $("#employee_email_error").html('');
                            }

                            if (Obj.errors.employee_phone) {
                                $("#employee_phone_error").html(Obj.errors.employee_phone[0]);
                            } else {
                                $("#employee_phone_error").html('');
                            }
                        }
                    }
                );
            });


            $("#update_employee_click").click(function (e) {
                e.preventDefault();

                var form = $('#create_employee')[0];
                var formdata = new FormData(form);
                $.ajax({
                        cache: false,
                        url: '{{url('employee/update')}}',
                        type: "POST",
                        data: formdata,
                        contentType: false,
                        processData: false,
                        success: function (html) {
                            notification('Updated successfully!!','success');
                            $("#createEmployee").modal('hide');
                            var table = $('#employee_list').DataTable();
                            table.ajax.reload();
                        },
                        error: function (errors) {
                            notification('Please check error!!','error');
                            var Obj = JSON.parse(errors.responseText)
                            if (Obj.errors.company_name) {
                                $("#employee_name_error").html(Obj.errors.employee_name[0]);
                            } else {
                                $("#employee_name_error").html('');
                            }
                            if (Obj.errors.email) {
                                $("#employee_email_error").html(Obj.errors.email[0]);
                            } else {
                                $("#employee_email_error").html('');
                            }

                            if (Obj.errors.employee_phone) {
                                $("#employee_phone_error").html(Obj.errors.employee_phone[0]);
                            } else {
                                $("#employee_phone_error").html('');
                            }
                        }
                    }
                );
            });

            $('#employee_list').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    url: "{{url('employee/employee_list')}}",
                    type: 'POST',
                },
                columns: [
                    {data: 'cname', name: 'cname'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'action', name: 'action'},
                ]
            });


            $(document.body).on('click', '.edit_employee', function () {
                $(".error_class").text('');
                var employee_id = $(this).attr('employee_id');
                var employee_name = $(this).attr('employee_name');
                var employee_phone = $(this).attr('employee_phone');
                var employee_email = $(this).attr('employee_email');
                $('.employee_update_hide').hide();
                $("#update_employee_click").show();
                $("#create_employee_click").hide();
                $("#create_update_employee").text('Update employee');
                $("#email").val(employee_email);
                $("#employee_name").val(employee_name);
                $("#employee_phone").val(employee_phone);
                $("#employee_id_update").val(employee_id);
                $("#employee_password").val('');
                //$("#select_company").val(company_id);

                $("#createEmployee").modal('show');
            })


            $(document.body).on('click', '.delete_employee', function () {
                var employee_id = $(this).attr('employee_id');
                if (confirm("Are you sure?")) {
                    $.ajax({
                            url: '{{url('employee/delete')}}',
                            type: "POST",
                            data: {employee_id: employee_id},
                            dataType: "Json",
                            success: function (html) {
                                notification('Deleted successfully!!','success');
                                var table = $('#employee_list').DataTable();
                                table.ajax.reload();
                            }
                        }
                    );
                }
            })

        });
    </script>

@stop
