<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class SupplierController extends Controller
{
    public function index(){
        $users = User::where('is_admin', '0')->get();

        return $users;
    }

    public function store(Request $request) {
        $size = count($request->all());

        for ($i=0; $i < $size; $i++) { 
            $u = User::where('email', $request[$i]['email'])->get();

            if(count($u) == 0){
                $user = new User();
                $user->name = $request[$i]['name'];
                $user->email = $request[$i]['email'];
                $user->rfc = $request[$i]['rfc'];
                $user->is_admin = '0';
                $user->is_active = 0;
                $user->password = Hash::make('12345678');
                $user->save();
            }
        }

        return json_encode(['status' => true, 'message' => "El usuario se actualizo correctamente!", 'r' => $request->all()]);
    }

    public function update(Request $request){
        $user = User::find($request['id']);

        if($request['is_active'] == '0'){
            $active = 1;
        }else{
            $active = 0;
        }

        try {
            $user->is_active = $active;
            $user->save();

            return json_encode(['status' => true, 'message' => "El usuario se actualizo correctamente!"]);
        } catch (\Throwable $th) {
            return json_encode(['status' => false, 'message' => "Hubo un error al actualizar"]);
        }
    }
}
