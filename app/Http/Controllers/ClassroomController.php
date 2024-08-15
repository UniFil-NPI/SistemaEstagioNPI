<?php
namespace App\Http\Controllers;

use App\Models\Classroom;
use Google\Client;
use Google\Service\Classroom as GoogleClassroom;
use Illuminate\Http\Request;

class GoogleClassroomController extends Controller
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Laravel Google Classroom API');
        $this->client->setScopes([
            GoogleClassroom::CLASSROOM_COURSES_READONLY, 
            GoogleClassroom::CLASSROOM_ROSTERS_READONLY, 
            GoogleClassroom::CLASSROOM_COURSEWORK_ME_READONLY
        ]);
        $this->client->setAuthConfig(storage_path('app/google/credentials.json'));
        $this->client->setAccessType('offline');

        $this->service = new GoogleClassroom($this->client);
    }

    public function adicionarCursoBanco($id_curso)
    {
        $course = $this->service->courses->get($id_curso);

        Classroom::create([
            'id_classroom' => $course->id,
            'id_dono' => $course->ownerId,
            'descricao' => $course->description,
            'secao' => $course->section,
            // Não precisa incluir 'email_aluno' aqui, pois isso será tratado separadamente
        ]);

        return response()->json(['message' => 'Curso adicionado com sucesso'], 200);
    }

    public function pegarAlunosCurso($id_curso)
    {
        $students = $this->service->courses_students->listCoursesStudents($id_curso);
        $studentData = [];

        foreach ($students->getStudents() as $student) {
            $studentData[] = [
                'name' => $student->profile->name->fullName,
                'email' => $student->profile->emailAddress,
            ];

            // Armazena cada aluno na tabela 'classroom'
            Classroom::updateOrCreate(
                ['id_classroom' => $id_curso, 'email_aluno' => $student->profile->emailAddress],
                ['secao' => $student->courseSection] // Exemplo de campo adicional, se necessário
            );
        }

        return response()->json($studentData, 200);
    }

    public function pegarAtividadesAluno($email_aluno)
    {
        $assignments = [];
        $courses = $this->service->courses->listCourses();

        foreach ($courses->getCourses() as $course) {
            $courseWork = $this->service->courses_courseWork->listCoursesCourseWork($course->id, [
                'userId' => $email_aluno
            ]);

            foreach ($courseWork->getCourseWork() as $work) {
                $assignments[] = [
                    'course' => $course->name,
                    'title' => $work->title,
                    'dueDate' => $work->dueDate,
                    'state' => $work->state,
                    'grade' => $work->assignedGrade,
                ];
            }
        }

        return response()->json($assignments, 200);
    }
}
