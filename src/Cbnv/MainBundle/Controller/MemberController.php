<?php

namespace Cbnv\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cbnv\MainBundle\Entity\Member;
use Cbnv\MainBundle\Form\MemberType;

/**
 * Member controller.
 *
 */
class MemberController extends Controller
{

    /**
     * Lists all Member entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CbnvMainBundle:Member');
        $entities = $repository->findAll();
        $fields = $repository::getFields();
        $newLink = $this->generateUrl('member_new');

        return $this->render('CbnvMainBundle:Admin:form_list.html.twig', array(
            'type'          => 'member',
            'edit_path'     => 'member_edit',
            'delete_path'   => 'member_delete',
            'entities'      => $entities,
            'fields'        => $fields,
            'new_link'      => $newLink
        ));
    }

    /**
     * Creates a new Member entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Member();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('member_list'));
        }

        return $this->render('CbnvMainBundle:Admin:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Member entity.
    *
    * @param Member $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Member $entity)
    {
        $form = $this->createForm(new MemberType(), $entity, array(
            'action' => $this->generateUrl('member_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Member entity.
     *
     */
    public function newAction()
    {
        $entity = new Member();
        $form   = $this->createCreateForm($entity);

        return $this->render('CbnvMainBundle:Admin:form_new.html.twig', array(
            'entity'        => $entity,
            'title'         => 'member',
            'return_path'   => $this->generateUrl('member_list'),
            'form'          => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Member entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CbnvMainBundle:Member')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Member entity.');
        }

        $editForm = $this->createEditForm($entity);
        return $this->render('CbnvMainBundle:Admin:form_edit.html.twig', array(
            'title'       => 'Member edit',
            'return_path' => $this->generateUrl('member_list'),
            'edit_form'   => $editForm->createView()
        ));
    }

    /**
    * Creates a form to edit a Member entity.
    *
    * @param Member $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Member $entity)
    {
        $form = $this->createForm(new MemberType(), $entity, array(
            'action' => $this->generateUrl('member_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Member entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CbnvMainBundle:Member')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Member entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('member_edit', array('id' => $id)));
        }

        return $this->render('CbnvMainBundle:Admin:form_edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Member entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CbnvMainBundle:Member')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Member entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('member_list'));
    }
}
