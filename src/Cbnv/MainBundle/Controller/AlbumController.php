<?php

namespace Cbnv\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Cbnv\MainBundle\Entity\Album;
use Cbnv\MainBundle\Form\AlbumType;
use Cbnv\MainBundle\Entity\Photo;

/**
 * Album controller.
 *
 */
class AlbumController extends Controller
{
    /**
     * Lists all Album entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CbnvMainBundle:Album');
        $entities = $repository->findAll();
        $fields = $repository::getFields();
        $newLink = $this->generateUrl('album_new');

        return $this->render('CbnvMainBundle:Admin:form_list.html.twig', array(
            'type'          => 'album',
            'edit_path'     => 'album_edit',
            'delete_path'   => 'album_delete',
            'entities'      => $entities,
            'fields'        => $fields,
            'new_link'      => $newLink
        ));
    }

    /**
     * Creates a new Album entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Album();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $photos = $request->files->all();
            foreach($photos['photos'] as $photo) {
                $newPhoto = new Photo();
                $newPhoto->setFile($photo);
                $newPhoto->setAlbum($entity);
                $em->persist($newPhoto);
                unset($newPhoto);
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('album_list'));
        }

        return $this->render('CbnvMainBundle:Admin:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Album entity.
    *
    * @param Album $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Album $entity)
    {
        $form = $this->createForm(new AlbumType(), $entity, array(
            'action' => $this->generateUrl('album_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Album entity.
     *
     */
    public function newAction()
    {
        $entity = new Album();
        $form   = $this->createCreateForm($entity);

        return $this->render('CbnvMainBundle:Admin:form_album.html.twig', array(
            'entity'        => $entity,
            'title'         => 'album',
            'return_path'   => $this->generateUrl('album_list'),
            'form'          => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Album entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CbnvMainBundle:Album')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Album entity.');
        }
        $photos = $em->getRepository('CbnvMainBundle:Photo')->findBy(array('album' => $entity));
        $editForm = $this->createEditForm($entity);
        return $this->render('CbnvMainBundle:Admin:form_album.html.twig', array(
            'title'         => 'Album edit',
            'return_path'   => $this->generateUrl('album_list'),
            'photos'        => $photos,
            'form'          => $editForm->createView()
        ));
    }

    /**
    * Creates a form to edit a Album entity.
    *
    * @param Album $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Album $entity)
    {
        $form = $this->createForm(new AlbumType(), $entity, array(
            'action' => $this->generateUrl('album_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Album entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CbnvMainBundle:Album')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Album entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $photos = $request->files->all();
            foreach($photos['photos'] as $photo) {
                $newPhoto = new Photo();
                $newPhoto->setFile($photo);
                $newPhoto->setAlbum($entity);
                $em->persist($newPhoto);
                unset($newPhoto);
            }
            $em->flush();
            return $this->redirect($this->generateUrl('album_edit', array('id' => $id)));
        }

        return $this->render('CbnvMainBundle:Admin:form_album.html.twig', array(
            'entity' => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Album entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CbnvMainBundle:Album')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Album entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('album_list'));
    }
}
