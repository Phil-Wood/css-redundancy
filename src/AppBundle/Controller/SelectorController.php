<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Selector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Selector controller.
 *
 * @Route("selector")
 */
class SelectorController extends Controller
{
    /**
     * Lists all selector entities.
     *
     * @Route("/", name="selector_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $selectors = $em->getRepository('AppBundle:Selector')->findAll();

        return $this->render('selector/index.html.twig', array(
            'selectors' => $selectors,
        ));
    }

    /**
     * Creates a new selector entity.
     *
     * @Route("/new", name="selector_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $selector = new Selector();
        $form = $this->createForm('AppBundle\Form\SelectorType', $selector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($selector);
            $em->flush($selector);

            return $this->redirectToRoute('selector_show', array('id' => $selector->getId()));
        }

        return $this->render('selector/new.html.twig', array(
            'selector' => $selector,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a selector entity.
     *
     * @Route("/{id}", name="selector_show")
     * @Method("GET")
     */
    public function showAction(Selector $selector)
    {
        $deleteForm = $this->createDeleteForm($selector);

        return $this->render('selector/show.html.twig', array(
            'selector' => $selector,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing selector entity.
     *
     * @Route("/{id}/edit", name="selector_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Selector $selector)
    {
        $deleteForm = $this->createDeleteForm($selector);
        $editForm = $this->createForm('AppBundle\Form\SelectorType', $selector);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('selector_edit', array('id' => $selector->getId()));
        }

        return $this->render('selector/edit.html.twig', array(
            'selector' => $selector,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a selector entity.
     *
     * @Route("/{id}", name="selector_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Selector $selector)
    {
        $form = $this->createDeleteForm($selector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($selector);
            $em->flush($selector);
        }

        return $this->redirectToRoute('selector_index');
    }

    /**
     * Creates a form to delete a selector entity.
     *
     * @param Selector $selector The selector entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Selector $selector)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('selector_delete', array('id' => $selector->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function parseCss()
    {

    }
}
