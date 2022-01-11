@extends('layout')
<link rel="stylesheet" href="{{asset('css/login/login.css') }}">

@section('contenido')
    <div class="login-page">
        {{--@if(session()->has('MensajeEstado'))
    
            <div class="alert alert-danger"><strong>Advertencia!  </strong>{{ session('MensajeEstado') }}</div>
            
        @endif--}}
        <div class="form">
          <form class="login-form" method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}" id="LoginForm">
            @csrf

            <div class="form-group row {{ $errors->has('login') ? 'has-danger' : '' }}">
                <input id="login" type="login" class="form-control" name="login" value="{{ old('login') }}" required autofocus placeholder="Introduce tu Nombre de Usuario">
            </div>
                     
            <div class="form-group row {{ $errors->has('login') ? 'has-danger' : '' }}">

                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Introduce Contraseña">

                    @if ($errors->has('password'))
                        <span class="help-block" role="alert" style="color:red">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif

                    @if ($errors->has('login'))
                        <span class="help-block" role="alert" style="color:red" id="MensajeError">
                            <strong>{{ $errors->first('login') }}</strong>
                            {{--{!!$errors->first('login', '<label class="error">:message</label>')!!}--}}
                        </span>
                        <input type="hidden" value="{{$errors->first('login')}}" name="error">
                    @endif

                    @if(session()->has('MensajeEstado'))
    
                    <span class="help-block" role="alert" style="color:red" id="MensajeError">
                        <strong>{{ session('MensajeEstado') }}</strong>
                    </span>
                    
                    @endif
            </div>

            <div class="form-group">
                <div class="col-md-8 offset-md-5">
                    <label for="remember">{{ __('Recuerdame') }}</label>
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="position:absolute; left:-50px; top:3px">                        
 
                </div>
            </div>

            <button type="submit" class="btn-login" id="btnLogin">
                {{ __('Login') }}
            </button>

            <input type="hidden" name="contador">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

            <div class="loader loader-bar is-active"></div>

          </form>

            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button id="btnRecuperar" class="btn-login2 tip-top btn-title" title="Puede utilizar su correo o nombre de usuario">Recuperar Contraseña</button>
                </div>
            </div>
        </div>
    </div>

    
@endsection

@push('scripts')

<script>
    /*$('#btnLogin').click(function(){
        $('.loader').addClass('is-active');
    });*/
    $(document).ready(function() {
        $('.loader').removeClass('is-active');
    });

var error = $("input[name='error']").val();


if(error != undefined)
{
    var username = $("input[name='login'] ").val();
	//var url = "/user/get/?data=" + username;
	var url = "{{route('user.get', ['data='])}}"+ username
    $.getJSON( url , function ( result ) {
        if (result == 0 ) {

        }
        else {

            var contador = result[0].contador_intentos;
			var aumentacontador = parseFloat(contador) + 1;
			
			if(result[0].estado == 0){
				$("#MensajeError").empty();
				$("#MensajeError").html('<strong> Usuario Bloqueado, Contacte al Administrador </strong>');
			}

			else{
				$("input[name='contador'] ").val(aumentacontador);

				var user = result[0].id;
				var formData = {user : user, contador_intentos: aumentacontador}
				$.ajax({
					type: "POST",
					headers: {'X-CSRF-TOKEN': $('#token').val()},
					url: "{{route('user.contador')}}",
					data: formData,
					dataType: "json",
					success: function(data) {
						//window.location = "/login"
						if(aumentacontador == 10){
							$("#MensajeError").empty();
							$("#MensajeError").html('<strong> Usuario Bloqueado, Contacte al Administrador </strong>');
						}
						
					},
					always: function() {
						l.stop();
					},
					error: function() {
						alert("Ha ocurrido un problema, contacte a su administrador!!");
					}
					
				});
			}
            
        }
    });
}

//RECUPERAR PASSWORD

$.validator.addMethod("userExiste", function(value, element) {
	var valid = false;
	$.ajax({
		type: "GET",
		async: false,
		url: "{{route('user.existe')}}",
		data: "login=" + value,
		dataType: "json",
		success: function(msg) {
			valid = !msg;
		}
	});
	return valid;
}, "El usuario ingresado no existe en la base de datos");


var validator = $("#LoginForm").validate({
	ignore: [],
	onkeyup:false,
    onclick: false,
    //onfocusout: false,
	rules: {
		login: {
            required : true,
            userExiste: true
		}
	},
	messages: {
		login: {
			required: "Por favor, ingrese Usuario"
		}
	}
});

$("#btnRecuperar").click(function(event) {
	$("#MensajeError").empty();
	$('.help-block').empty();
	if ($('#LoginForm').valid()) {
		Recuperar();
		
	} else {
		validator.focusInvalid();
	}
});

function Recuperar(button) {
	$('.loader').addClass('is-active');
	$("#btnRecuperar").attr('disabled', 'disabled');
	var formData = $("#LoginForm").serialize();
	$.ajax({
		type: "POST",
		headers: {'X-CSRF-TOKEN': $('#token').val()},
		url: "{{route('password.reset2')}}",
		data: formData,
		dataType: "json",
		success: function(data) {
			$('.loader').removeClass('is-active');
			alertify.set('notifier','position', 'top-center');
			alertify.success('Se envio nueva contraseña a su correo');
			
            $('#btnRecuperar').attr("disabled", false);
		},
		always: function() {
			l.stop();
		},
		error: function() {
			$('.loader').removeClass('is-active');
			alert("Ha ocurrido un problema, contacte a su administrador!!");
		}
		
	});
}

</script>

<script src="{{asset('js/login/login.js') }}"></script>
@endpush
