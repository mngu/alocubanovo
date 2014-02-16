<?php

namespace Cbnv\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function displayLogoAction()
    {
    	return $this->render('CbnvMainBundle:Default:logo.html.twig', array(
            'index_link'    => $this->generateUrl('homepage')
        ));
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
}
