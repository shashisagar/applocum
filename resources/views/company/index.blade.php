@extends('layouts.app')

@section('content')
    <link href="/assets/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>

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

                    <div id="show_loader"></div>
                    <form method="post" action="" id="create_company" >

                        <input type="hidden" id="company_id_update" name="company_id_update" value="">

                        <div class="form-group">
                            <label for="company_name">Name: *</label>
                            <input type="text" class="form-control" id="company_name" name="company_name">
                            <span id="company_name_error" class="error_class"></span>
                        </div>

                        <div class="form-group">
                            <label for="company_email">Email: *</label>
                            <input type="email" class="form-control" id="company_email" name="email">
                            <span id="company_email_error" class="error_class"></span>
                        </div>

                        <div class="form-group">
                            <label for="company_website">Website: *</label>
                            <input type="text" class="form-control" id="company_website" name="company_website">
                            <span id="company_website_error" class="error_class"></span>
                        </div>
                        <div class="form-group">
                            <label for="company_password" id="company_password_level">Password: *</label>
                            <input type="password" class="form-control" id="company_password" name="company_password">
                            <span id="company_password_error" class="error_class"></span>
                        </div>

{{--                        <div class="form-group company_update_hide">--}}
{{--                            <label for="fileToUpload">Company Logo:</label>--}}
{{--                            <input type="file" class="form-control" name="fileToUpload" id="fileToUpload">--}}
{{--                         --}}
{{--                        </div>--}}



                        <div class="form-group">
                            <label class="col-sm-12 control-label">Company Logo</label>
                            <div class="text-center col-md-4">
                                <div class="fileinput fileinput-new" data-provides="fileinput">

                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                        <img id="storage_path_id" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image+100*100" alt=""/>
                                    </div>

                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
                                    </div>
                                    <div>
                                            <span class="btn default btn-primary btn-file">
                                            <span class="fileinput-new"> Select image </span>
                                            <span class="fileinput-exists"> Change </span>
                                            <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*" >
                                        </span>
{{--                                        <a href="#" class="btn default btn-danger fileinput-exists" data-dismiss="fileinput" style="margin-right: -35px;"> Remove </a>--}}
                                    </div>
                                    <span id="fileToUpload_error" class="error_class"></span>
                                </div>
                            </div>
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
    <script src="/assets/bootstrap-fileinput/bootstrap-fileinput.js"></script>

    <script>

        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
           $("#create_new_company").click(function(){
               $(".error_class").text('');
               $("#company_name").val('');
               $("#company_email").val('');
               $("#company_website").val('');
               $("#company_id_update").val('');
               $('.company_update_hide').show();
               $("#update_company_click").hide();
               $("#create_company_click").show();
               $("#create_update_company").text('Create Company');
               $(".fileinput").removeClass('fileinput-exists');
               $(".fileinput").addClass('fileinput-new');
               $("#createComapny").modal('show');
               $("#company_password_level").text('Password: *')

           });

           $("#create_company_click").click(function(e){
               e.preventDefault();
               $("#create_company_click").text('Creating...');
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
                       before:function(){

                       },
                       success: function (html) {
                          // $("#show_loader").html('mail sent successfully');
                           $("#create_company_click").text('Create');
                           alert("Created successfully & mail sent to admin!!");
                           $("#createComapny").modal('hide');
                           var table = $('#company_list').DataTable();
                           table.ajax.reload();
                       },
                       error:function(errors){

                            var Obj = JSON.parse(errors.responseText)
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
                           if (Obj.errors.company_website) {
                               $("#company_website_error").html(Obj.errors.company_website[0]);
                           }
                           else {
                               $("#company_website_error").html('');
                           }
                           if (Obj.errors.company_password) {
                               $("#company_password_error").html(Obj.errors.company_password[0]);
                           }
                           else {
                               $("#company_password_error").html('');
                           }

                           if (Obj.errors.fileToUpload) {
                               $("#fileToUpload_error").html(Obj.errors.fileToUpload[0]);
                           }
                           else {
                               $("#fileToUpload_error").html('');
                           }

                       },

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
                        },
                        error:function(errors){

                        var Obj = JSON.parse(errors.responseText)
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
                        if (Obj.errors.company_website) {
                            $("#company_website_error").html(Obj.errors.company_website[0]);
                        }
                        else {
                            $("#company_website_error").html('');
                        }
                        if (Obj.errors.company_password) {
                            $("#company_password_error").html(Obj.errors.company_password[0]);
                        }
                        else {
                            $("#company_password_error").html('');
                        }

                        if (Obj.errors.fileToUpload) {
                            $("#fileToUpload_error").html(Obj.errors.fileToUpload[0]);
                        }
                        else {
                            $("#fileToUpload_error").html('');
                        }

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
                $(".error_class").text('');
                var path= '<?php echo storage_path() ?>';
                var company_id=$(this).attr('company_id');
                var company_name=$(this).attr('company_name');
                var company_website=$(this).attr('company_website');
                var company_email=$(this).attr('company_email');
                var company_logo=$(this).attr('company_logo');
               // alert(company_logo);
                var filePath = path+company_logo;
                $('.company_update_hide').hide();
                $("#update_company_click").show();
                $("#create_company_click").hide();
                $("#create_update_company").text('Update Company');
                $("#company_email").val(company_email);
                $("#company_name").val(company_name);
                $("#company_website").val(company_website);
                $("#company_id_update").val(company_id);
                $("#company_password").val('');
                $("#image_for_edit").attr('src',filePath);
                $("#createComapny").modal('show');
                $("#company_password_level").text('Password:')

                // $(".fileinput").addClass('fileinput-exists');
                // $(".fileinput").removeClass('fileinput-new');
                if(company_logo){
                   // alert("pp");
                    var url_1 = company_logo;
                    var html='<img src="'+url_1+'">';
                    $(".fileinput-preview").html(html);
                    $(".fileinput").addClass('fileinput-exists');
                    $(".fileinput").removeClass('fileinput-new');

                }
                else{
                    $(".fileinput").removeClass('fileinput-exists');
                    $(".fileinput").addClass('fileinput-new');
                   // $(".fileinput-preview").children().attr('src','http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image+100*100');
                    //$("#storage_path_id").attr('src','http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image+100*100');
                }
               // var html='<img src='+url_1+' alt=""/>';
                //
                // $.ajax({
                //         url: 'image',
                //         type: "POST",
                //         data: { url: url_1 },
                //         dataType:"Json",
                //         success: function (html) {
                //            console.log(html);
                //         }
                //     }
                // );
                //
                //
                // $("#insertedImages").html(html);







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
