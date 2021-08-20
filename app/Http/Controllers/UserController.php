<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function deposito(Request $request)
    {
        $destino = $request->id;
        $monto = $request->monto;

        $usuario = $monto + $request;
        $usuario = Usuario::find($destino);
        $usuario->balance = $usuario->balance + $monto;
        $usuario->save();
    }
    public function retiro(Request $request) 
    {
        $origen = $request->id;
        $monto = $request->monto;

        $usuario = $monto + $request;
        $usuario = Usuario::find($origen);
        $usuario->balance = $usuario->balance + $monto;
        $usuario->save();
    }

    public function transferencia(Request $request)
    {
        
    }


    public function eventos(Request $request)
    {
        switch ($request->tipo) {
            case "deposito":
                deposito($request);
                break;
            case "retiro":
                retiro($request);
                break;
            case "transferencia":
                transferencia($request);
                break;
        }
    }

 //ingresar con email
    public function crearcuenta($mail)
    {
        try {
            $User->email = $mail->input('email');
            $User->save();
        } catch (Exception $e) {
            return $this->sendError("Error Conocido", "Email en base de datos", 404);
        }
     //random_int(100000, 999999)
    }

}