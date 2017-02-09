<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Publication;
use AppBundle\Form\PublicationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

    public function publicationDetailAction($publicationId, $scienceId)
    {
        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository('AppBundle:Publication')
            ->find($publicationId);

        return $this->render('AppBundle:App:publication_detail.html.twig', ['publication' => $publication,
                                                                            'scienceId' => $scienceId]);
    }
}
