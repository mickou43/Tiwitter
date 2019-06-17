<?php
/**
 * Created by PhpStorm.
 * User: remcr
 * Date: 14/06/2019
 * Time: 17:18
 */

namespace Model\Finder;


use Model\Gateway\TiwitGateway;
use app\src\App;

class TiwitFinder implements FinderInterface
{
    public function __construct(App $app) {
        $this->app = $app;
        $this->conn = $this->app->getService('database')->getConnection();
    }
    public function findAll(){}
    public function findOneById($id){}

    public function getPost($id){
        $query = $this->conn->prepare('SELECT id, contenu, utilisateurID FROM post WHERE contenu like :id'); // Création de la requête + utilisation order by pour ne pas utiliser sort
        $query->execute([':id' => $id]); // Exécution de la requête
        $element = $query->fetchAll(\PDO::FETCH_ASSOC);

        if($element == null) return null;

        $post = new PostGateway($this->app);
        $post->hydrate($element);

        return $post;
    }

    public function post($contenu)
    {
        $post = new PostGateway($this->app);
        $post->setcontenu($contenu);
        $post->setutilisateurID($_SESSION['id']);
        $post->insert();
    }
    public function CreateTiwit(Array $tiwits) : Bool
    {
        try
        {
            $tiwit = new TiwitGateway($this->app);
            $tiwit->setContenu($tiwits['contenu']);
            $tiwit->setUtilisateurID($tiwits['utilisateurID']);


            $tiwit->insert();

            return true;
        }
        catch (\Error $e)
        {
            return false;
        }
    }
}