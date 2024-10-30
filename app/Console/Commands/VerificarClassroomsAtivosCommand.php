<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Classroom;
use Google\Client;
use Google\Service\Classroom as GoogleClassroom;
use Illuminate\Support\Facades\Cookie;


class VerificarClassroomsAtivosCommand extends Command
{
    protected $signature = 'classrooms:verificar {token} {refreshToken}';
    protected $description = 'Verifica se os classrooms estão ativos na API Google Classroom.';
    protected $client;
    protected $service;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $token = $this->argument('token');
        $refreshToken = $this->argument('refreshToken');

        $this->client = new Client();
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        
        $this->client->setAccessToken($token);

        if ($this->client->isAccessTokenExpired()) {
            $newToken = $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
            if (isset($newToken['access_token'])) {
                $this->client->setAccessToken($newToken['access_token']);
            } else {
                $this->error('Não foi possível renovar o token.');
                return;
            }
        }

        $this->service = new GoogleClassroom($this->client);


        $classrooms = Classroom::where('ativo', true)->get();

        foreach ($classrooms as $classroom) {
            $this->client->setAccessToken(Cookie::get('google_login_token'));

            try {
                $this->service->courses->get($classroom->id_classroom);
            } catch (\Exception $e) {
                $classroom->ativo = false;
                $classroom->save();

                $this->warn("Classroom {$classroom->id_classroom} foi desativado.");
            }
        }

        $this->info('Verificação concluída.');
    }
}

