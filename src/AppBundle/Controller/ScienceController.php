<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Science;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Science controller.
 *
 */
class ScienceController extends Controller
{
    /**
     * Lists all science entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $sciences = $em->getRepository('AppBundle:Science')->findAll();

        return $this->render('science/index.html.twig', array(
            'sciences' => $sciences,
        ));
    }

    /**
     * Creates a new science entity.
     *
     */
    public function newAction(Request $request)
    {
        $science = new Science();
        $form = $this->createForm('AppBundle\Form\ScienceType', $science);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($science);
            $em->flush($science);

            return $this->redirectToRoute('science_show', array('id' => $science->getId()));
        }

        return $this->render('science/new.html.twig', array(
            'science' => $science,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a science entity.
     *
     */
    public function showAction(Science $science)
    {
        $deleteForm = $this->createDeleteForm($science);

        return $this->render('science/show.html.twig', array(
            'science' => $science,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing science entity.
     *
     */
    public function editAction(Request $request, Science $science)
    {
        $deleteForm = $this->createDeleteForm($science);
        $editForm = $this->createForm('AppBundle\Form\ScienceType', $science);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('science_edit', array('id' => $science->getId()));
        }

        return $this->render('science/edit.html.twig', array(
            'science' => $science,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a science entity.
     *
     */
    public function deleteAction(Request $request, Science $science)
    {
        $form = $this->createDeleteForm($science);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($science);
            $em->flush($science);
        }

        return $this->redirectToRoute('science_index');
    }

    /**
     * Creates a form to delete a science entity.
     *
     * @param Science $science The science entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Science $science)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('science_delete', array('id' => $science->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
