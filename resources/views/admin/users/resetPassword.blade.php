<!-- Modal -->
<div class="modal fade" id="modalResetPassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <form method="POST" id="ResetPasswordForm">
        {{--{{ method_field('put') }}--}}

      <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Cambiar Contraseña Usuario Actual</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="form-group col-sm-4 {{ $errors->has('password_anterior') ? 'has-error': '' }}">
                        <label for="password_anterior">Contraseña Anterior:</label>
                        <input name="password_anterior" class="form-control" type="password" placeholder="Ingresa contraseña anterior">
                    </div>

                    <div class="form-group col-sm-4 {{ $errors->has('password') ? 'has-error': '' }}">
                        <label for="password">Contraseña Nueva:</label>
                        <input name="password" class="form-control" type="password" placeholder="Ingresa contraseña">
                    </div>
        
                    <div class="form-group col-sm-4 {{ $errors->has('password') ? 'has-error': '' }}">
                        <label for="password_confirmation">Repite la contraseña:</label>
                        <input name="password_confirmation" class="form-control" type="password" placeholder="Repite contraseña">
                        {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>

              <input type="hidden" name="_token" id="tokenReset" value="{{ csrf_token() }}">
              {{--<input type="hidden" id="resetId" name="id">--}}
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary" id="btnResetPassword" >Actualizar</button>
            </div>
          </div>
        </div>
    </form>
</div>

@push('scripts')
   <script>

    if(window.location.hash === '#reset')
    {
        $('#modalResetPassword').modal('show');
    }

    $('#modalResetPassword').on('hide.bs.modal', function(){
        $("#ResetPasswordForm").validate().resetForm();
		document.getElementById("ResetPasswordForm").reset();
        window.location.hash = '#';

    });

    $('#modalResetPassword').on('shown.bs.modal', function(){
        window.location.hash = '#reset';

    });
       
       

//RESET password usuario actual
var validator = $("#ResetPasswordForm").validate({
	ignore: [],
    onkeyup:false,
    onclick: false,
    //onfocusout: false,
	rules: {
		password_anterior: {
			required: true
        },
		password: {
			required: true
        },
        password_confirmation: {
            required: true
        }

	},
	messages: {
		password_anterior: {
			required: "Por favor, ingrese contraseña anterior"
        },
		password: {
			required: "Por favor, ingrese contraseña"
        },
        password_confirmation: {
            required: "Por favor, confirme contraseña"
        }
	}
});

 
function BorrarFormularioReset2() {
    $("#ResetPasswordForm :input").each(function () {
        $(this).val('');
	});
};

$("#btnResetPassword").click(function(event) {
    event.preventDefault();
	if ($('#ResetPasswordForm').valid()) {
		resetModal();
	} else {
		validator.focusInvalid();
	}
});



function resetModal(button) {	
    var formData = $("#ResetPasswordForm").serialize();
	$.ajax({
		type: "POST",
		headers: {'X-CSRF-TOKEN': $('#tokenReset').val()},
		url: "{{route('users.reset')}}",
		data: formData,
		dataType: "json",
		success: function(data) {      
        if(data.success === 'Redirigir'){
            window.location = "{{url('/login')}}"
        }
			alertify.set('notifier','position', 'top-center');
			alertify.success('La contraseña se cambio Correctamente!!');
		},
		error: function(errors) {
            if(errors.responseText !=""){
                var errors = JSON.parse(errors.responseText);

                if (errors.password != null) {
                    for (i = 0; i < errors.password.length; i++) {
					    $("#ResetPasswordForm input[name='password_confirmation'] ").after("<label class='error' id='ErrorPassword'>"+errors.password[i]+"</br></label>");
				    }
                }
                else{
                    $("#ErrorPassword").remove();
                }
        
                if (errors.password_anterior != null) {
                    $("input[name='password_anterior'] ").after("<label class='error' id='ErrorPassword_anterior'>"+errors.password_anterior+"</label>");
                }
                else{
                    $("#ErrorPassword_anterior").remove();
                }
            }
            
		}
		
	});
}

    </script>
@endpush