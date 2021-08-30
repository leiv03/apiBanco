<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Token;
use Carbon\Carbon;

class UsuarioController extends ApiController
{
    //deposito
    public function deposito(Request $request)
    {
        $destino = $request->input('id');
        $monto = $request->input('monto');

        $usuario = User::find($destino);
        $usuario->balance = $usuario->balance + $monto;
        $usuario->save();
        return $this->sendResponse(
            ['id' => $usuario->id, 'balance' => $usuario->balance],
            'Se a hecho el deposito correctamente',
            200
        );
    }
    //retiro
    public function retiro(Request $request)
    {
        try {
            
            $origen = $request->input('origen');
            $monto = $request->input('monto');
            $usuario = User::find($origen);
            echo $monto;
            if ($monto >= 1000) {
                
                $this->crearToken($usuario);
            }
            $usuario->balance = $usuario->balance - $monto;
            $usuario->save();
            return $this->sendResponse($usuario->origen,$usuario->balance,200);
        } catch (Exception $e) {
            return $this->sendError('error conosido','Saldo insuficiente',404);
        }
    }
    //transferencia
    public function transferencia(Request $request)
    {
        $usuarioDestino = User::find($request->input('destino'));
        $usuarioOrigen = User::find($request->input('origen'));

        if ($monto >= 1000) {
            $date = Carbon::now();
            $date = $date->addMinute(5);
            $token = new Token();
            $token->idUsuario = $usuario->id;
            $token->token = random_int(100000, 500000);
            $token->fecha = $date;
            $token->save();
        } elseif ($usuarioOrigen->balance <= $request->input('monto')) {
            return $this->sendError(404, 'saldo insuficiente');
        } else {
            $usuarioOrigen->balance = $usuarioOrigen->balance - $request->input('monto');
            $usuarioOrigen->save();
            $usuarioDestino->balance = $usuarioDestino->balance + $request->input('monto');
            $usuarioDestino->save();
            return $this->sendResponse([[$usuarioOrigen,"usuario origen"],[$usuarioDestino,"usuario destino"]], 'realizada correctamente la transferencia');
        }
    }

    //tres tipos de enevntos
    public function eventos(Request $request)
    {
        switch ($request->input('tipo')) {
            case 'deposito':
                $this->deposito($request);
                break;
            case 'retiro':
                $this->retiro($request);
                break;
            case 'transferencia':
                $this->transferencia($request);
                break;
        }
    }

    public function balance(Request $request)
    {
        try {
            $usuario = User::find($request->id);
            return ($this->sendResponse([$usuario->id, $usuario->balance], 'balance de usuario'));
        } catch (Exception $e) {
            return ($this->sendError('no existe'));
        }
    }
    public function reset(Request $request)
    {
        token::truncate();
        User::truncate();
        return ($this->sendResponse(200, "tablas borradas"));
    }
    public function crearUsuario(Request $request)
    {
        if (User::find($request->input('email'))) {
            return ($this->sendError(404, "mail ya existente en base de datos"));
        } else {
            $usuario = new User();
            $usuario->email = $request->input('email');
            $usuario->balance = 0;
            $usuario->save();
            return ($this->sendResponse($usuario, "usuario creado satisfactoriamente"));
        }
    }
    public function crearToken($usuario){
    //creacion de la fecha 
    $date = Carbon::now();
    $date = $date->addMinute(5);

    //Guardar/Crea el token con una fecha asociado a una id de usuario
    $token = new Token();
    $token->idUsuario = $usuario->id;
    $token->token = random_int(100000, 500000);
    $token->fecha = $date;
    $token->save();

    //envio del mail
    $data = array();
    $data['token'] =  $token->token;
    $data['email'] = $usuario->email;
    Mail::send('mail.email', $data, function ($msj) use ($data) {
        $msj->subject('Envio de TOKEN');
        $msj->to($data['email']);
    });
    return $this->sendResponse("Token enviado", "revise su mail :)", 200);
}
   
}