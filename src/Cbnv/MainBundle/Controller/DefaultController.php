<?php

namespace Cbnv\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function displayHeaderAction($uri)
    {
    	return $this->render('CbnvMainBundle:Default:header.html.twig', $this->generateMenu($uri));
    }

    public function displayFooterAction($uri) 
    {
        return $this->render('CbnvMainBundle:Default:footer.html.twig', $this->generateMenu($uri));
    }

    private function generateMenu($uri) 
    {
        return array(
            'index_link'    => $this->generateUrl('homepage'),
            'menus'         => array(
                'cours'     => array(
                    'link'      => $this->generateUrl('cours'),
                    'name'      => 'Cours',
                    'active'    => ($uri == '/cours') ? 1 : 0,
                    'menus'     => array(
                        'salsa' => array(
                            'link'      => $this->generateUrl('cours_salsa'),
                            'name'      => 'Salsa',
                            'active'    => ($uri == '/cours/salsa') ? 1 : 0
                        ),
                        'zumba' => array(
                            'link'      => $this->generateUrl('cours_zumba'),
                            'name'      => 'Zumba',
                            'active'    => ($uri == '/cours/zumba') ? 1 : 0
                        ),
                        'stage' => array(
                            'link'      => $this->generateUrl('cours_stage'),
                            'name'      => 'Stage',
                            'active'    => ($uri == '/cours/stage') ? 1 : 0
                        ),
                    ) 
                ),
                'profs'     => array(
                    'link'      => $this->generateUrl('profs'),
                    'name'      => 'Professeurs',
                    'active'    => ($uri == '/professeurs') ? 1 : 0
                ),
                'galerie'   => array(
                    'link'      => $this->generateUrl('galeries'),
                    'name'      => 'Galeries',
                    'active'    => ($uri == '/galeries') ? 1 : 0
                ),
                'infos'     => array(
                    'link'      => $this->generateUrl('infos'),
                    'name'      => 'Infos pratiques',
                    'active'    => ($uri == '/infos') ? 1 : 0
                ),
            )    
        );
    }

    public function displayArticleAction($slug)
    {
    	$repository = $this->getDoctrine()
		                 ->getManager()
		                 ->getRepository('CbnvMainBundle:Article');
		$article = $repository->findOneBy(array('slug' => $slug));

		if($article === null)
		{
			throw $this->createNotFoundException('Article[slug='.$slug.'] inexistant.');
		}

		return $this->render('CbnvMainBundle:Default:displayArticle.html.twig', array(
			'article' => $article
		));
    }

    public function displayAllArticleAction($page = 1)
    {
    	$repository = $this->getDoctrine()
		                 ->getManager()
		                 ->getRepository('CbnvMainBundle:Article');

        $count = count($repository->findAll());
        $pagination = array(
            'page'          => $page,
            'route'         => 'homepage_page',
            'pages_count'   => ceil($count / 5),
            'route_params'  => array()
        );

        $listArticles = $repository->getPaginator($page);

		return $this->render('CbnvMainBundle:Default:displayAllArticle.html.twig', array(
			'listArticles'  => $listArticles,
            'pagination'    => $pagination
		));
    }

    public function coursAction() {
        return $this->render('CbnvMainBundle:Default:cours.html.twig');
    }
    public function coursSalsaAction() {
        return $this->render('CbnvMainBundle:Default:coursSalsa.html.twig');
    }
    public function coursZumbaAction() {
        return $this->render('CbnvMainBundle:Default:coursZumba.html.twig');
    }
    public function coursStageAction() {
        return $this->render('CbnvMainBundle:Default:coursStage.html.twig');
    }
    public function professeursAction() {
        return $this->render('CbnvMainBundle:Default:professeurs.html.twig');
    }
    public function infosAction() {
        return $this->render('CbnvMainBundle:Default:infos.html.twig');
    }

    public function galeriesAction() 
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('CbnvMainBundle:Album');
        $albums = $repository->findAll();

        if($albums === null)
        {
            throw $this->createNotFoundException('Aucun album à afficher');
        }

        return $this->render('CbnvMainBundle:Default:galeries.html.twig', array(
            'albums' => $albums
        ));
    }

    public function galerieDisplayAction($slug) 
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('CbnvMainBundle:Album');
        $album = $repository->findOneBy(array('slug' => $slug));

        if($album === null)
        {
            throw $this->createNotFoundException('Album[slug='.$slug.'] inexistant.');
        }

        return $this->render('CbnvMainBundle:Default:galerie.html.twig', array(
            'album' => $album
        ));
    }   

}
