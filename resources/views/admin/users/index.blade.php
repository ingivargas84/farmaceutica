@extends('admin.layoutadmin')

@section('header')
<section class="content-header">
    <h1>
      Listado de Usuarios
      <small>Todos los usuarios</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{route('dashboard')}}"><i class="fa fa-home"></i> Inicio</a></li>
      <li class="active">Usuarios</li>
    </ol>
  </section>

  @endsection

@section('content')

@include('admin.users.createModalUser')
@include('admin.users.editModalUser')
@include('admin.users.resetPasswordTercero')


<div class="box">
    <div class="box-header">
      @can('create', new App\User)
      <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalUser">
        <i class="fa fa-plus"></i>Agregar Usuario</button>
      @endcan
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <input type="hidden" name="rol_user" value="{{$user->roles[0]->name}}">
        <table id="users-table" class="table table-striped table-bordered no-margin-bottom dt-responsive nowrap"  width="100%">            
        </table>
        <input type="hidden" name="urlActual" value="{{url()->current()}}">
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box --> 

@endsection


@push('styles')
 
 
@endpush

@push('scripts')
  <script>
    $(document).ready(function() {
      users_table.ajax.url("{{route('users.getJson')}}").load();
    });

      function resetModalT(button) {	
        var formData = $("#ResetPasswordTerceroForm").serialize();
        //var id = $("#resetId").val();
      $.ajax({
        type: "POST",
        headers: {'X-CSRF-TOKEN': $('#tokenResetTercero').val()},
        url: "{{route('users.reset.tercero')}}",
        data: formData,
        dataType: "json",
        success: function(data) {
          BorrarFormularioReset();
          $('#modalResetPasswordTercero').modal("hide");
          users_table.ajax.reload();
          alertify.set('notifier','position', 'top-center');
          alertify.success('La contraseña se cambio Correctamente!!');
          
        },
        error: function(errors) {
                var errors = JSON.parse(errors.responseText);

          if (errors.password != null) {
            for (i = 0; i < errors.password.length; i++) {
              $("#ResetPasswordTerceroForm input[name='password'] ").after("<label class='error' id='ErrorPassword'>"+errors.password[i]+"<br></label>");
              }
          }
          else{
            $("#ErrorPassword").remove();
          }
        }
        
      });
    }
  </script>
  <script src="{{asset('js/users/index.js')}}"></script>
  <script src="{{asset('js/users/create.js')}}"></script>
  <script src="{{asset('js/users/edit.js')}}"></script>
  <script src="{{asset('js/users/resetPassword.js')}}"></script>

@endpush