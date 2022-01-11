<!-- Modal -->
<div class="modal fade" id="modalResetPasswordTercero" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <form method="POST" id="ResetPasswordTerceroForm">
            {{--{{ method_field('put') }}--}}
    
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Cambiar Contraseña</h4>
                </div>
                <div class="modal-body">
    
                    <div class="row">
                        <div class="form-group col-sm-6 {{ $errors->has('password') ? 'has-error': '' }}">
                            <label for="password">Contraseña:</label>
                            <input name="password" class="form-control" type="password" placeholder="Ingresa contraseña">
                        </div>
            
                        <div class="form-group col-sm-6 {{ $errors->has('password') ? 'has-error': '' }}">
                            <label for="password_confirmation">Repite la contraseña:</label>
                            <input name="password_confirmation" class="form-control" type="password" placeholder="Repite contraseña">
                            {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
    
                  <input type="hidden" name="_token" id="tokenResetTercero" value="{{ csrf_token() }}">
                  <input type="hidden" id="resetId" name="id">
                  
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-primary" id="btnResetPasswordTercero" >Actualizar</button>
                </div>
              </div>
            </div>
        </form>
          </div>