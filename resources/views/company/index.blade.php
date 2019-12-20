@extends('layouts.app')

@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">


    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Company</div>
                <button type="button" class="btn btn-primary" id="create_new_company">Create New Company</button>


            </div>
        </div>
    </div>



        <div class="container">
            <table id="company_list" class="table table-hover table-condensed" style="width:100%">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Website</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>

    <div class="modal fade" id="createComapny" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="create_update_company">Create Company</h4>
                </div>
                <div class="modal-body">

                    <form method="post" action="" id="create_company" >

                        <input type="hidden" id="company_id_update" name="company_id_update" value="">

                        <div class="form-group">
                            <label for="company_name">Name:</label>
                            <input type="text" class="form-control" id="company_name" name="company_name">
                            <span id="company_name_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="company_email">Email:</label>
                            <input type="email" class="form-control" id="company_email" name="email">
                            <span id="company_email_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="company_website">Website:</label>
                            <input type="text" class="form-control" id="company_website" name="company_website">
                        </div>
                        <div class="form-group">
                            <label for="company_password">Password:</label>
                            <input type="password" class="form-control" id="company_password" name="company_password">
                        </div>

                        <div class="form-group company_update_hide">
                            <label for="fileToUpload">Uplaod Image:</label>
                            <input type="file" class="form-control" name="fileToUpload" id="fileToUpload">
                        </div>

                        <button type="button" class="btn btn-default" id="create_company_click">Create</button>
                        <button type="button" class="btn btn-default" id="update_company_click">Update</button>

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

        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
           $("#create_new_company").click(function(){
               $("#company_name").val('');
               $("#company_website").val('');
               $("#company_id_update").val('');
               $('.company_update_hide').show();
               $("#update_company_click").hide();
               $("#create_company_click").show();
               $("#create_update_company").text('Create Company');
               $("#createComapny").modal('show');
           });

           $("#create_company_click").click(function(e){
               e.preventDefault();

               var form = $('#create_company')[0];
               var formdata = new FormData(form);
               $.ajax({
                       cache: false,
                       url: '{{url('company')}}',
                       type: "POST",
                       data: formdata,
                       contentType: false,
                       processData: false,
                       dataType:"JSON",
                       success: function (html) {
                           alert("Created successfully!!");
                           $("#createComapny").modal('hide');
                           var table = $('#company_list').DataTable();
                           table.ajax.reload();
                       },
                       error:function(errors){
                            var Obj = JSON.parse(errors.responseText)
                           // console.log();


                          // var response = JSON.parse(errors);

                           if (Obj.errors.company_name) {
                               $("#company_name_error").html(Obj.errors.company_name[0]);
                           }
                           else {
                               $("#company_name_error").html('');
                           }
                           if (Obj.errors.email) {
                               $("#company_email_error").html(Obj.errors.email[0]);
                           }
                           else {
                               $("#company_email_error").html('');
                           }
                           // if (Obj.errors.email) {
                           //     $("#company_email_error").html(Obj.errors.email[0]);
                           // }
                           // else {
                           //     $("#company_email_error").html('');
                           // }

                       }
                   }
               );
           });



            $("#update_company_click").click(function(e){
                e.preventDefault();

                var form = $('#create_company')[0];
                var formdata = new FormData(form);
                $.ajax({
                        cache: false,
                        url: '{{url('company/update')}}',
                        type: "POST",
                        data: formdata,
                        contentType: false,
                        processData: false,
                        success: function (html) {
                            alert("Updated successfully!!");
                            $("#createComapny").modal('hide');
                            var table = $('#company_list').DataTable();
                            table.ajax.reload();
                        }
                    }
                );
            });

            $('#company_list').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    url: "{{url('company/company_list')}}",
                    type: 'POST',
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'website', name: 'website'},
                    {data: 'action', name: 'action'},
                    // {data: 'status', name: 'users.status', searchable: false},
                    // {data: 'deleteAction', name: 'deleteAction', orderable: false, searchable: false}
                ]
            });


            $(document.body).on('click','.edit_company',function(){
                var company_id=$(this).attr('company_id');
                var company_name=$(this).attr('company_name');
                var company_website=$(this).attr('company_website');
                var company_email=$(this).attr('company_email');
                $('.company_update_hide').hide();
                $("#update_company_click").show();
                $("#create_company_click").hide();
                $("#create_update_company").text('Update Company');
                $("#company_email").val(company_email);
                $("#company_name").val(company_name);
                $("#company_website").val(company_website);
                $("#company_id_update").val(company_id);
                $("#company_password").val('');
                $("#createComapny").modal('show');
            })


            $(document.body).on('click','.delete_company',function(){
                var company_id=$(this).attr('company_id');
                alert(company_id);
                if (confirm("Are you sure?")) {
                    $.ajax({
                            url: '{{url('company/delete')}}',
                            type: "POST",
                            data: {company_id: company_id},
                            dataType:"Json",
                            success: function (html) {
                                alert("Deleted successfully!!");
                                var table = $('#company_list').DataTable();
                                table.ajax.reload();
                            }
                        }
                    );
                }
            })

        });
    </script>

@stop
