<!-- Modal -->
<div class="modal fade" id="modalUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    {!! Form::open( array( 'id' => 'UserForm' ) ) !!}

      <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Agregar Usuaio</h4>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="form-group col-sm-6 {{ $errors->has('name') ? 'has-error': '' }}">
                  <label for="name">Nombre:</label>
                  <input type="text" name="name" placeholder="Ingrese Nombre" class="form-control" value="{{old('name')}}">
                  {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                </div>

                <div class="form-group col-sm-6 {{ $errors->has('username') ? 'has-error': '' }}">
                  <label for="username">Usuario:</label>
                  <input type="text" name="username" placeholder="Ingrese Usuario" class="form-control" value="{{old('username')}}">
                  {!! $errors->first('username', '<span class="help-block">:message</span>') !!}
                </div>
              </div>
              <br>

              <div class="row">
                <div class="form-group col-sm-6 {{ $errors->has('email') ? 'has-error': '' }}">
                  <label for="email">Email:</label>
                  <input type="text" name="email" placeholder="Ingrese Correo" class="form-control" value="{{old('email')}}">
                  {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                </div>

                <div class="form-group col-sm-6 {{ $errors->has('roles') ? 'has-error': '' }}">
                  <label for="roles">Rol:</label>
                  <select class="form-control" id='roles' name="roles" title="Seleccione">
                    <option value="">Seleccione Rol</option>
                    @foreach ($roles as $rol)
                      <option value="{{$rol->name}}">{{$rol->name}}</option>
                    @endforeach
                  </select>
                  {!! $errors->first('roles', '<span class="help-block">:message</span>') !!}
                </div>
              </div>

              <br>
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

              <input type="hidden" name="_token" id="tokenUser" value="{{ csrf_token() }}">
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary" id="ButtonUserModal" >Agregar</button>
            </div>
          </div>
        </div>
    {!! Form::close() !!}
      </div>