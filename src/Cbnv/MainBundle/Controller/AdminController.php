<?php

namespace Cbnv\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

use Cbnv\MainBundle\Entity\Admin;
use Cbnv\MainBundle\Form\AdminType;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('CbnvMainBundle:Admin:index.html.twig');
    }

    public function menuAction()
    {
        return $this->render('CbnvMainBundle:Admin:menu.html.twig', array(
            'admin_link'    => $this->generateUrl('admin_index'),
            'article_link'  => $this->generateUrl('article_list'),
            'album_link'    => $this->generateUrl('album_list'),
            'member_link'   => $this->generateUrl('member_list'),
            'photo_link'    => $this->generateUrl('photo_list'),
            'logout'        => $this->generateUrl('logout')
        ));
    }

    public function loginAction()
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirect($this->generateUrl('admin_index'));
        }

        $request = $this->getRequest();
        $session = $request->getSession();

        // On vérifie s'il y a des erreurs d'une précédente soumission du formulaire
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('CbnvMainBundle:Admin:login.html.twig', array(
            // Valeur du précédent nom d'utilisateur entré par l'internaute
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }
}
