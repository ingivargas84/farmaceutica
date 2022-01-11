<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use DB;
use Validator;
use Illuminate\Support\Facades\Auth;
class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
     {
        $roles = DB::table('roles')
        ->select('roles.id','roles.name')
        ->where('roles.name', '!=', 'Super-Administrador')
        ->get();
        
         $user = Auth::User();
         return view('admin.users.index', compact('roles', 'user'));
     }
 
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
         //
     }
 
     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function store(Request $request)
     {
        $this->authorize('create', new User);

        $messages = [
            'regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula, un número y un carácter especial: @#%'
        ];

        $data = $request->all();
        $errors = Validator::make($data,[
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:users',
            'username' => 'required|max:255|unique:users',
            'password' => ['regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^0-9a-zA-Z])([A-Za-z]|[^ ]){8,15}$/', 'confirmed', 'min:8', 'required'],
        ],$messages);

         if($errors->fails())
         {
            return  Response::json($errors->errors(), 422);
         }

        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->username = $data['username'];
        $user->password = bcrypt($data['password']);
        //$user = User::create($data);
        $user->save();

        $user->assignRole($request->roles);                       

        return Response::json(['success' => 'Éxito']);
        //return redirect()->route('users.index')->withFlash('El usuario ha sido creado');
     }
 
     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show(User $user)
     {

     }
 
     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function edit(User $user)
     {   
     }
 
     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function update(Request $request, User $user)
     {
         //$this->authorize('update',$user);
 
         /*$data = $request->validate([
             'name' => 'required',
             'email' => ['required', Rule::unique('users')->ignore($user->id)],
             'username' => ['required',Rule::unique('users')->ignore($user->id)],
         ]);*/

         $data = $request->all();

         $errors = Validator::make($data,[
            'name' => 'required',
            'email' => ['required', Rule::unique('users')->ignore($user->id)],
            'username' => ['required',Rule::unique('users')->ignore($user->id)],
         ]);

         //dd($data);
         
         if($errors->fails())
         {
            return  Response::json($errors->errors(), 422);
         }

         /*if(auth()->user()->hasRole('Admin'))
         {
             $this->validate($request, [
                 'estado' => 'required'
             ]);
 
             $user->estado = $request['estado'];
             $user->save();
         }*/
 
         $user->update($data);

         $user->roles()->detach();
         $user->assignRole($data['roles']);
 
         return Response::json(['success' => 'Éxito']);
     }
 
     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy(User $user)
     { 
         $this->authorize('delete', $user);

         $user->estado = 0;
         $user->save();
         //$user->delete();
         return Response::json(['success' => 'Éxito']);
         
     }

     public function getJson(Request $params)
    {
        $user_id = Auth::id();
       
        if(auth()->user()->hasRole('Administrador|Super-Administrador')){
            $query = "SELECT U.id, U.name, U.email, IF(U.estado = 1,'Activo', 'Inactivo') as estado, R.name as rol, U.username, U.estado as estado_numero 
            from users U 
            LEFT JOIN model_has_roles M on M.model_id = U.id
            LEFT JOIN roles R on R.id = M.role_id WHERE R.name !='Super-Administrador' order by U.id desc ";            
        } 
        
        else{
            $query = "SELECT U.id, U.name, U.email, IF(U.estado = 1,'Activo', 'Inactivo') as estado, R.name as rol, U.username, U.estado as estado_numero 
            from users U 
            LEFT JOIN model_has_roles M on M.model_id = U.id
            LEFT JOIN roles R on R.id = M.role_id WHERE R.name !='Super-Administrador' AND U.id =" .$user_id. " order by U.id desc ";
        }
       
        $result = DB::select($query);
        $api_Result['data'] = $result;

        return Response::json( $api_Result );
    }

    public function resetPasswordTercero(Request $request)
    {
        $data = $request->all();

        $messages = [
            'regex' => 'La contraseña debe contener al menos una mayuscula, una minuscula, un número y un caracter especial: @#%'
        ];

        $errors = Validator::make($data,[
            'password' => ['regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^0-9a-zA-Z])([A-Za-z]|[^ ]){8,15}$/', 'confirmed', 'min:8', 'required'],
        ], $messages);

         if($errors->fails())
         {
            return  Response::json($errors->errors(), 422);
         }

         $user = User::where('id', $request->id)->first();
         $user->password = bcrypt($data['password']);
         $user->estado = 1;
         $user->save();

        return Response::json(['success' => 'Éxito']);
    }

    public function resetPassword(Request $request)
    {
        $password_actual = Auth::user()->password;
        $user = User::where('id', auth()->id())->first();

        $data = $request->all();

        $messages = [
            'regex' => 'La contraseña debe contener al menos una mayuscula, una minuscula, un número y un caracter especial: @#%'
        ];

        $errors = Validator::make($data,[
            'password' => ['regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^0-9a-zA-Z])([A-Za-z]|[^ ]){8,15}$/', 'confirmed', 'min:8', 'required'],
        ], $messages);

         if($errors->fails())
         {
            return  Response::json($errors->errors(), 422);
         }

         if(password_verify($data['password_anterior'],$password_actual))
         {
            $user->password = bcrypt($data['password']);
            Auth::logout();
            $user->save();
            return Response::json(['success' => 'Redirigir']);

            //return redirect()->route('login')->withFlash('La contraseña se cambio correctamente!');            
         }

         else{
            return  Response::json(['password_anterior' => 'La contraseña no coincide'], 422);
         }

    }

    public function cargarSelect()
	{
        $dato = Input::get("user_id");

        if ($dato == ""){
            $dato = 0;
        }

        $result = DB::table('users')
        ->select('users.id','users.name')
        ->leftJoin('empleados','users.id','=','empleados.user_id')
        ->wherenull('empleados.user_id')
        ->orWhere('users.id', '=', $dato)
        ->get();

		return Response::json( $result );		
    }

    
    public function cargarSelectApertura()
	{
        /*$dato = Input::get("user_id");

        if ($dato == ""){
            $dato = 0;
        }*/

        $result = User::role('Cobrador')->where('estado', 1)->where('caja_abierta', 0)->get();

		return Response::json( $result);		
    }
}
