<?php

namespace Cbnv\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cbnv\MainBundle\Entity\Article;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CbnvMainBundle:Default:index.html.twig', array('name' => ''));
    }

    public function displayArticleAction($id)
    {
    	$repository = $this->getDoctrine()
		                 ->getManager()
		                 ->getRepository('CbnvMainBundle:Article');
		// On récupère l'entité correspondant à l'id $id
		$article = $repository->find($id);
		// $article est donc une instance de Sdz\BlogBundle\Entity\Article
		// Ou null si aucun article n'a été trouvé avec l'id $id
		if($article === null)
		{
			throw $this->createNotFoundException('Article[id='.$id.'] inexistant.');
		}

		return $this->render('CbnvMainBundle:Default:displayArticle.html.twig', array(
			'article' => $article
		));
    }

    public function displayAllArticleAction($page = 0)
    {
    	$start = ($page - 1) * 5;
    	$start = ($start < 0) ? 0 : $start;
    	$repository = $this->getDoctrine()
		                 ->getManager()
		                 ->getRepository('CbnvMainBundle:Article');
		// On récupère l'entité correspondant à l'id $id
		$listArticles = $repository->findBy(array(), array(), 5, $start);

		return $this->render('CbnvMainBundle:Default:displayAllArticle.html.twig', array(
			'listArticles' => $listArticles
		));
    }

    public function addArticleAction() {
    	$article = new Article;
    	$formBuilder = $this->createFormBuilder($article);
    	$formBuilder
		    ->add('title',       'text')
		    ->add('content',     'textarea');
		// Pour l'instant, pas de commentaires, catégories, etc., on les gérera plus tard
		// À partir du formBuilder, on génère le formulaire
		$form = $formBuilder->getForm();
		// On passe la méthode createView() du formulaire à la vue afin qu'elle puisse afficher le formulaire toute seule
		$request = $this->get('request');

		// On vérifie qu'elle est de type POST
		if ($request->getMethod() == 'POST') {
			// On fait le lien Requête <-> Formulaire
			// À partir de maintenant, la variable $article contient les valeurs entrées dans le formulaire par le visiteur
			$form->bind($request);

			// On vérifie que les valeurs entrées sont correctes
			// (Nous verrons la validation des objets en détail dans le prochain chapitre)
			if ($form->isValid()) {
				// On l'enregistre notre objet $article dans la base de données
				$em = $this->getDoctrine()->getManager();
				$em->persist($article);
				$em->flush();

				// On redirige vers la page de visualisation de l'article nouvellement créé
				return $this->redirect($this->generateUrl('cbnv_article', array('id' => $article->getId())));
			}
		}
		return $this->render('CbnvMainBundle:Default:addArticle.html.twig', array(
			'form' => $form->createView(),
		));
	}
}
