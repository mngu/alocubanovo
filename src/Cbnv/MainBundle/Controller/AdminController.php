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

    public function listAction($type)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CbnvMainBundle:' . ucfirst($type));
        $entities = $repository->findAll();
        $fields = $repository::getFields();
        $newLink = $this->generateUrl('admin_new', array('type' => $type));

        return $this->render('CbnvMainBundle:Admin:form_list.html.twig', array(
            'type'     => $type,
            'entities' => $entities,
            'fields'   => $fields,
            'new_link' => $newLink
        ));
    }

    /**
     * Displays a form to create a new entity.
     *
     */
    public function newAction($type)
    {
        $class = 'Cbnv\MainBundle\Entity\\' . ucfirst($type);
        $entity = new $class;
        $form   = $this->createCreateForm($type, $entity);

        return $this->render('CbnvMainBundle:Admin:form_new.html.twig', array(
            'entity'        => $entity,
            'title'         => $type,
            'return_path'   => $this->generateUrl('admin_list', array('type' => $type)),
            'form'          => $form->createView()
        ));
    }

    /**
    * Creates a form to create an entity.
    *
    * @param $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm($type, $entity)
    {
        $formType = $entity->getForm();
        $form = $this->createForm($formType, $entity, array(
            'action' => $this->generateUrl('admin_create', array('type' => $type)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Creates a new Article entity.
     *
     */
    public function createAction($type, Request $request)
    {
        $class = 'Cbnv\MainBundle\Entity\\' . ucfirst($type);
        $entity = new $class;
        $form = $this->createCreateForm($type, $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_list', array('type' => $type)));
        }

        return $this->render('CbnvMainBundle:Admin:form_new.html.twig', array(
            'entity'        => $entity,
            'title'         => $type,
            'return_path'   => $this->generateUrl('admin_list', array('type' => $type)),
            'form'          => $form->createView()
        ));
    }

    public function editAction($type, $id)
    {
        $class = ucfirst($type);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CbnvMainBundle:' . $class)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException("Unable to find $class entity.");
        }

        $editForm = $this->createEditForm($type, $entity);
        return $this->render('CbnvMainBundle:Admin:form_edit.html.twig', array(
            'title'       => "$class edit",
            'return_path' => $this->generateUrl('admin_list', array('type' => $type)),
            'edit_form'   => $editForm->createView()
        ));
    }

    /**
    * Creates a form to edit an entity.
    *
    * @param $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm($type, $entity)
    {
        $formType = $entity->getForm();
        $form = $this->createForm($formType, $entity, array(
            'action' => $this->generateUrl('admin_update', array('type' => $type, 'id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing entity.
     *
     */
    public function updateAction(Request $request, $type, $id)
    {
        $class = ucfirst($type);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CbnvMainBundle:' . $class)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException("Unable to find $class entity.");
        }

        $editForm = $this->createEditForm($type, $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('admin_edit', array('type' => $type, 'id' => $id)));
        }

        return $this->render('CbnvMainBundle:Admin:form_edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }

    /**
     * Deletes an entity.
     *
     */
    public function deleteAction($type, $id)
    {
        $class = ucfirst($type);
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CbnvMainBundle:' . $class)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException("Unable to find $class entity.");
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_list', array('type' => $type)));
    }
}
