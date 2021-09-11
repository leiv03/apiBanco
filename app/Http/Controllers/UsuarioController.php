<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;


class UsuarioController extends ApiController
{
    // Deposito de dinero
    public function deposito(Request $request)
    {
        $destino = $request->input('destino');
        $monto = $request->input('monto');

        $usuario = User::find($destino);
        if ($usuario) {
            $usuario->balance = $usuario->balance + $monto;
            $usuario->save();
            return $this->sendResponse(
                ['id' => $usuario->id, 'balance' => $usuario->balance],
                'Se a hecho el deposito correctamente',
                200
            );
        } else {
            return $this->sendError(
                'error conocido',
                'Usuario no encontrado',
                404
            );
        }
    }
    //Retiro de dinero
    public function retiro(Request $request)
    {
        $origen = $request->input('origen');
        $monto = $request->input('monto');
        $usuario = User::find($origen);
        if ($usuario) {
            if ($usuario->balance > $monto) {
                $usuario->balance = $usuario->balance - $monto;
                $usuario->save();
                return $this->sendResponse(
                    ['id' => $usuario->id, 'balance' => $usuario->balance],
                    'Se ha realizado el retiro correctamente'
                );
            } else {
                return $this->sendError(
                    'error conocido',
                    'Saldo insuficiente',
                    404
                );
            }
        } else {
            return $this->sendError(
                'error conocido',
                'Usuario no encontrado',
                404
            );
        }
        /*
            if ($monto >= 1000) {
                $this->crearToken($usuario);
            }*/
    }

    //Transferir dinero de una cuenta a otra
    public function transferencia(Request $request)
    {
        $usuarioDestino = User::find($request->input('destino'));
        $usuarioOrigen = User::find($request->input('origen'));
        if (!$usuarioOrigen or !$usuarioDestino) {
            return $this->sendError(
                'error conocido',
                'Usuario no encontrado',
                404
            );
        } else {
            if ($usuarioOrigen->balance < $request->input('monto')) {
                return $this->sendError(
                    'No se logro hacer la transferencia',
                    'saldo insuficiente',
                    404
                );
            } else {
                $usuarioOrigen->balance =
                    $usuarioOrigen->balance - $request->input('monto');
                $usuarioOrigen->save();
                $usuarioDestino->balance =
                    $usuarioDestino->balance + $request->input('monto');
                $usuarioDestino->save();
                return $this->sendResponse(
                    [
                        [
                            'id' => $usuarioOrigen->id,
                            'balance' => $usuarioOrigen->balance,
                        ],
                        [
                            'id' => $usuarioDestino->id,
                            'balance' => $usuarioDestino->balance,
                        ],
                    ],
                    'realizada correctamente la transferencia'
                );
            }
        }
    }

    //Opcion de deposaitar, retirar y transferencia
    public function eventos(Request $request)
    {
        switch ($request->input('tipo')) {
            case 'deposito':
                return $this->deposito($request);
                break;
            case 'retiro':
                return $this->retiro($request);
                break;
            case 'transferencia':
                return $this->transferencia($request);
                break;
        }
    }

    // Visualizar balance de la cuenta
    public function balance(Request $request)
    {
        $usuario = User::find($request->input('id'));
        if($usuario) {
            return $this->sendResponse(
                ['id' => $usuario->id,'balance' => $usuario->balance],'Balance de usuario');
        } else{
            return $this->sendError('error conocido','usuario no existe', 404);
        }
    }

   //Creación de cuenta con email
   public function crearUsuario(Request $request){
       $usuario = User::where('email', '=', $request->input('email'))->get();
       if(strlen($usuario) > 2) {
        return $this->sendError('error conocido','Email ya existente', 404);
       }else{
        $usuario = new User();
        $usuario->email = $request->input('email');
        $usuario->balance = 0;
        $usuario->save();
        return $this->sendResponse([
            'id' => $usuario->id,
            'email' => $usuario->email,
        ],'usuario creado satisfactoriamente');
       }
   }

   //reset
    public function reset(Request $request)
    {
        User::truncate();
        return $this->sendResponse('La tabla usuario','se borro correctamente');
    }
   
}