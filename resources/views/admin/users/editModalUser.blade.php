<!-- Modal -->
<div class="modal fade" id="modalUpdateUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <form method="POST" id="UserUpdateForm">
            {{--{{ method_field('put') }}--}}
    
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="myModalLabel">Editar Usuaio</h4>
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
                      <select class="form-control" id='roles2' name="roles" title="Seleccione">
                        <option value="">Seleccione Rol</option>
                        @foreach ($roles as $rol)
                          <option value="{{$rol->name}}">{{$rol->name}}</option>
                        @endforeach
                      </select>
                      {!! $errors->first('roles', '<span class="help-block">:message</span>') !!}
                    </div>
                  </div>
    
                  <input type="hidden" name="_token" id="tokenUserEdit" value="{{ csrf_token() }}">
                  <input type="hidden" name="id">
                  
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-primary" id="ButtonUserModalUpdate" >Actualizar</button>
                </div>
              </div>
            </div>
        </form>
          </div>