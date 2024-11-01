<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Classroom;
use App\Models\Atividade;
use Google\Client;
use Google\Service\Classroom as GoogleClassroom;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;


class SincronizarAtividadesCommand extends Command
{
    protected $signature = 'atividades:sincronizar {token} {refreshToken}';
    protected $description = 'Sincroniza as atividades dos classrooms com a API Google Classroom';
    protected $client;
    protected $service;

    public function __construct()
    {
        echo "<script>console.log('constructor')</script>";
        parent::__construct();
    }

    public function handle()
    {
        $token = $this->argument('token');
        $refreshToken = $this->argument('refreshToken');

        $this->client = new Client();
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->addScope(GoogleClassroom::CLASSROOM_COURSEWORK_ME_READONLY);
        $this->client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));  // Configure seu URI no .env


        $this->client->setAccessToken($token);

        if ($this->client->isAccessTokenExpired()) {
            $newToken = $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
            if (isset($newToken['access_token'])) {
                $this->client->setAccessToken($newToken['access_token']);
            } else {
                $this->error('Não foi possível renovar o token.');
            }
        }

        $this->service = new GoogleClassroom($this->client);

        $classrooms = Classroom::where('ativo', true)->get();
        
        foreach ($classrooms as $classroom) {
            $courseWork = $this->service->courses_courseWork->listCoursesCourseWork($classroom->id_classroom);
            
            foreach ($courseWork->getCourseWork() as $work) {
                $workIDs[] = $work->id;
                $dataCriacao = Carbon::parse($work->creationTime)->format('Y-m-d');
                $dataEntrega = $work->dueDate ? Carbon::parse($work->dueDate)->format('Y-m-d') : null;

                // Obtenha os alunos associados à turma
                $alunos = $classroom->alunos; // Presumindo que você tenha a relação definida em Classroom
                
                foreach ($alunos as $aluno) {
                    $atividadeExistente = Atividade::where('id_classroom', $classroom->id_classroom)
                    ->where('id_api', $work->id)
                    ->where('email_aluno', $aluno->email_aluno)
                    ->first();

                    // Se a atividade já existir, não faz o update
                    if ($atividadeExistente) {
                        continue; // Se a atividade existe, pula para o próximo aluno
                    }
                    
                    Atividade::updateOrCreate(
                        [
                            'id_classroom' => $classroom->id_classroom,
                            'id_api' => $work->id,
                            'email_aluno' => $aluno->email_aluno, // Vinculando a atividade ao aluno
                            'titulo' => $work->title,
                        ],
                        [
                            'data_criacao' => $dataCriacao,
                            'data_entrega' => $dataEntrega,
                            'nota' => 0,
                            'entregue' => false,
                        ]
                    );
                }
            }

            Atividade::where('id_classroom', $classroom->id_classroom)
            ->whereNotIn('id_api', $workIDs)
            ->delete();
    
        }

        $this->info('Sincronização de atividades concluída.');
    }
}