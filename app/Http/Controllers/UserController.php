<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Registro;

class UserController extends ApiController
{
     //DEFENSA
     public function editarEmail(Request $request){
        $usuario = Registro::find($request->id);
        if($usuario){
            $emailOld = $usuario->email;
            return $this->sendResponse('Emails:', ['emailOld' => $emailOld, 'email'=> $usuario->email]);
        }else{
            return $this->sendResponse('No existe la id');
        }
    }

    public function deposito(Request $request)
    {
        $monto = $request-> input ("monto");

        $usuario = Registro::find($request-> input ("id"));
        $usuario->balance = $usuario->balance + $monto;
        $usuario->save();
        return ($this->sendResponse($usuario->balance, $usuario->id));
    }

    public function retiro(Request $request) 
    {
        try{
            $origen = $request->id;
            $monto = $request->monto;
    
            $usuario = Registro::find($origen);
            $usuario->balance = $usuario->balance - $monto;
            $usuario->save();
            return ($this->sendResponse( $usuario->balance, $usuario->id));
        }catch(Exeption $e){
            return($this->senderror(404));
        }
    }

    public function transferencia(Request $request)
    {
        try{
            $destino = $request->destino;
            $monto = $request->monto;
            $origen = $request->origen;
            //se le quita cieto monto a usuario 
            $usuarioOrigen = Registro::find($origen);
            $usuarioOrigen->balance = $usuarioOrigen->balance - $monto;
            $usuarioOrigen->save();
            //se agrega monto desead a usuario destino
            $usuarioDestino = Registro::find($destino);
            $usuarioDestino->balance = $usuarioDestino->balance + $monto;
            $usuarioDestino->save();
            return ($this->sendResponse($usuarioOrigen->balance, $id)($usuarioDestino->balance, $id));
        }catch(Exeption $e){
            return($this->senderror(404));
        }
    }
//3 tipos
    public function eventos(Request $request)
    {
        switch ($request->input('tipo')) {
            case "deposito":
                $this->deposito($request);
                break;
            case "retiro":
                $this->retiro($request);
                break;
            case "transferencia":
                $this->transferencia($request);
                break;
        }
    }
// obtener el balance
    public function balance(Request $request){
        try{
            $usuario = Registro::find($request->id);
            return ($this->sendResponse($usuario->balance, $usuario->id));
        }catch(Exeption $e){
            return($this->senderror(404));
        }
    }
//limpiar las tablas del mysql
    public function reset(Request $request){
        DB::table('usuario')->delete();
    }


 //ingresar con email
    public function crearcuenta($mail)
    {
        try {
            $usuario->email = $mail->input('email');
            $usuario->save();
        } catch (Exception $e) {
            return $this->sendError("Error Conocido", "Email en base de datos", 404);
        }

     //random_int(100000, 999999)
    }

    public function test(){
        return $this->sendResponse('Retorno ok', 'todo ok');
    }


   

}

