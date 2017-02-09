<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Publication;
use AppBundle\Entity\Comment;
use AppBundle\Form\PublicationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Class AppController
 * @package AppBundle\Controller
 */
class AppController extends Controller
{
    /**
     * Home page action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Publication');
        $publications = $repository->findThreeLastPublications();

        return $this->render('AppBundle:App:home.html.twig', ['publications' => $publications]);
    }

    public function publicationDetailAction(Request $request, $publicationId, $scienceId)
    {
        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository('AppBundle:Publication')
            ->find($publicationId);

        $comment = new Comment();
        $comment->setPublication($publication);
        $form = $this->createForm('AppBundle\Form\CommentType', $comment,
                                    ['publication_field' => false])
            ->add('save', Type\SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-sm btn-primary',
                ]
            ]);;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush($comment);

            return $this->redirectToRoute('app_publication_detail', [
                'publicationId' => $publicationId,
                'scienceId' => $scienceId
            ]);
        }

        $repository = $em->getRepository('AppBundle:Comment');
        $comments = $repository->getCommentByPublicationId($publicationId);

        return $this->render('AppBundle:App:publication_detail.html.twig', ['publication' => $publication,
                                                                            'scienceId' => $scienceId,
                                                                            'comment' => $comment,
                                                                            'comments' => $comments,
                                                                            'form' => $form->createView()]);
    }

    public function sciencesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Science');
        $sciences = $repository->findAllSciencesByAlpha();

        return $this->render('AppBundle:App:sciences.html.twig', ['sciences' => $sciences]);
    }

    public function scienceDetailAction($scienceId)
    {
        $em = $this->getDoctrine()->getManager();
        $science = $em->getRepository('AppBundle:Science')
            ->find($scienceId);

        $repository = $em->getRepository('AppBundle:Publication');
        $publications = $repository->findPublicationsByScience($science);

        return $this->render('AppBundle:App:science_detail.html.twig', ['science' => $science,
                                                                        'scienceId' => $scienceId,
                                                                        'publications' => $publications]);
    }

    public function publishAction(Request $request)
    {
        $publication = new Publication();
        $form = $this->createForm('AppBundle\Form\PublicationType', $publication, ['validated_field' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($publication);
            $em->flush($publication);

            return $this->redirectToRoute('app_home', array('id' => $publication->getId()));
        }

        return $this->render('AppBundle:App:publish.html.twig', array(
            'publication' => $publication,
            'form' => $form->createView(),
        ));
    }

    public function sideAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sciences = $em->getRepository('AppBundle:Science')
            ->findAll();

        return $this->render('AppBundle:Layout:sidebar.html.twig', ['sciences' => $sciences]);
    }
}
