<?php

namespace App\Http\Controllers;

use App\Models\Mens;
use Illuminate\Mail\Message;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Controller extends BaseController
{
    public function send(Request $request){
        $name       = $request->get('nome');
        $email      = $request->get('email');
        $celular    = $request->get('celular');
        $mensagem   = $request->get('mensagem');
        $error      =   "";
        if(!$name || strlen($name)<3){
            $error.="O nome deve conter no mínimo 3 caracteres<br/>";
        }
        if(!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error.="O emial deve conter um endereço eletrônico<br/>";
        }
        if(!$celular || strlen($celular)!=11){
            $error.="O celular deve conter o código de area mais o número<br/>";
        }
        if(!$mensagem || strlen($mensagem)<50){
            $error.="Escreva sua história.";
        }

        if($error!=""){
            return response()->json(['error'=>$error], 500);
        }
        $data = [
            'name'=>$name,
            'email'=>$email,
            'celular'=>$celular,
            'mensagem'=>$mensagem,
        ];
        if(Mens::createFromForm($data)){

            /** Envia um email */
            $message = 'Nome: '.$name.'<br/>';
            $message.= 'Email: '.$email.'<br/>';
            $message.= 'Celular: '.$celular.'<br/><hr/>';
            $message.= 'Mensagem: <br/>'.$mensagem.'<hr/>';

            try {
                Mail::send([], [], function (Message $mail) use ($message) {
                    $mail->to('diego-neumann@hotmail.com')
                        ->subject('PREMIO MUHERES POSITIVAS')
                        ->from('admin@oka6.com.br', 'Formulário landing page')
                        ->setBody($message, 'text/html');
                });
            }catch (\Exception $e){
                Log::error('Erro ao enviar email', );
            }
            return response()->json(['error'=>'Mensagem salva com sucesso'], 200);
        }
        return response()->json(['error'=>'Mensagem ja enviada para esse numero ou telefone'], 500);

    }
}
