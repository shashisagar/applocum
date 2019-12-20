@extends('companyAccessDir/layouts.app')

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

                                <select id="select_company"  class="form-group" name="select_company">
                                @foreach($companies as $company)
                                   <option class="form-group" value="{{$company->id}}">{{$company->name}}</option>
                                 @endforeach
                                </select>
                            </div>

                            <input type="hidden" id="employee_id_update" name="employee_id_update" value="">

                            <div class="form-group ">
                                <label for="employee_name">Name:</label>
                                <input type="text" class="form-control" id="employee_name" name="employee_name">
                            </div>

                            <div class="form-group">
                                <label for="employee_email">Email:</label>
                                <input type="email" class="form-control" id="employee_email" name="employee_email">
                            </div>

                            <div class="form-group">
                                <label for="employee_phone">Phone:</label>
                                <input type="text" class="form-control" id="employee_phone" name="employee_phone">
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
                $("#employee_name").val('');
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
                        url: '{{url('comp/employee/store')}}',
                        type: "POST",
                        data: formdata,
                        contentType: false,
                        processData: false,
                        success: function (html) {
                            alert("Created successfully!!");
                            $("#createEmployee").modal('hide');
                            var table = $('#employee_list').DataTable();
                            table.ajax.reload();
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
                        url: '{{url('comp/employee/update')}}',
                        type: "POST",
                        data: formdata,
                        contentType: false,
                        processData: false,
                        success: function (html) {
                            alert("Updated successfully!!");
                            $("#createEmployee").modal('hide');
                            var table = $('#employee_list').DataTable();
                            table.ajax.reload();
                        }
                    }
                );
            });

            $('#employee_list').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    url: "{{url('comp/employee_list')}}",
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
                var employee_id = $(this).attr('employee_id');
                var employee_name = $(this).attr('employee_name');
                var employee_phone = $(this).attr('employee_phone');
                var employee_email = $(this).attr('employee_email');
                $('.employee_update_hide').hide();
                $("#update_employee_click").show();
                $("#create_employee_click").hide();
                $("#create_update_employee").text('Update employee');
                $("#employee_email").val(employee_email);
                $("#employee_name").val(employee_name);
                $("#employee_phone").val(employee_phone);
                $("#employee_id_update").val(employee_id);
                $("#employee_password").val('');
                $("#createEmployee").modal('show');
            })


            $(document.body).on('click', '.delete_employee', function () {
                var employee_id = $(this).attr('employee_id');
                //alert(employee_id);
                if (confirm("Are you sure?")) {
                    $.ajax({
                            url: '{{url('comp/employee/delete')}}',
                            type: "POST",
                            data: {employee_id: employee_id},
                            dataType: "Json",
                            success: function (html) {
                                alert("Deleted successfully!!");
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
