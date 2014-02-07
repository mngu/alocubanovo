<?php

namespace Cbnv\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cbnv\MainBundle\Entity\Photo;
use Cbnv\MainBundle\Form\PhotoType;

/**
 * Photo controller.
 *
 */
class PhotoController extends Controller
{

    /**
     * Lists all Photo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CbnvMainBundle:Photo');
        $entities = $repository->findAll();
        $fields = $repository::getFields();
        $newLink = $this->generateUrl('photo_new');

        return $this->render('CbnvMainBundle:Admin:form_list.html.twig', array(
            'type'          => 'photo',
            'edit_path'     => 'photo_edit',
            'delete_path'   => 'photo_delete',
            'entities'      => $entities,
            'fields'        => $fields,
            'new_link'      => $newLink
        ));
    }

    /**
     * Creates a new Photo entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Photo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('photo_list'));
        }

        return $this->render('CbnvMainBundle:Admin:form_photo.html.twig', array(
            'entity'        => $entity,
            'title'         => 'photo',
            'return_path'   => $this->generateUrl('photo_list'),
            'form'          => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Photo entity.
    *
    * @param Photo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Photo $entity)
    {
        $form = $this->createForm(new PhotoType(), $entity, array(
            'action' => $this->generateUrl('photo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Photo entity.
     *
     */
    public function newAction()
    {
        $entity = new Photo();
        $form   = $this->createCreateForm($entity);

        return $this->render('CbnvMainBundle:Admin:form_photo.html.twig', array(
            'entity'        => $entity,
            'title'         => 'photo',
            'return_path'   => $this->generateUrl('photo_list'),
            'form'          => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Photo entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CbnvMainBundle:Photo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }

        $editForm = $this->createEditForm($entity);
        return $this->render('CbnvMainBundle:Admin:form_photo.html.twig', array(
            'entity'        => $entity,
            'title'         => 'Photo edit',
            'return_path'   => $this->generateUrl('photo_list'),
            'form'          => $editForm->createView()
        ));
    }

    /**
    * Creates a form to edit a Photo entity.
    *
    * @param Photo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Photo $entity)
    {
        $form = $this->createForm(new PhotoType(), $entity, array(
            'action' => $this->generateUrl('photo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Photo entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CbnvMainBundle:Photo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('photo_edit', array('id' => $id)));
        }

        return $this->render('CbnvMainBundle:Admin:form_edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Photo entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CbnvMainBundle:Photo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('photo_list'));
    }
}
