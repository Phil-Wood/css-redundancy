<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Project;
use AppBundle\Entity\Selector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;


class ProjectController extends Controller
{
    /**
     * Lists all project entities.
     *
     * @Route("/", name="project_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        // List Projects

        $em = $this->getDoctrine()->getManager();

        $projects = $em->getRepository('AppBundle:Project')->findAll();

        $deleteForms = array();

        foreach ($projects as $project) {
            $deleteForms[$project->getId()] = $this->createDeleteForm($project)->createView();
        }

        // Create New Project

        $project = new Project();
        $newForm = $this->createForm('AppBundle\Form\ProjectType', $project);
        $newForm->handleRequest($request);

        if ($newForm->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush($project);

            return $this->redirectToRoute('project_index');
        }

        return $this->render('project/index.html.twig', array(
            'projects' => $projects,
            'deleteForms' => $deleteForms,
            'newForm' => $newForm->createView(),
        ));
    }

    /**
     * Finds and displays a project entity.
     *
     * @Route("/{id}", name="project_show")
     * @Method({"GET", "POST"})
     */
    public function showAction(Project $project, Request $request)
    {
        $cssFileShort = $project->getCssFile();
        if($project->getCssFile()){
            $project->setCssFile(
                new File($this->getParameter('css_directory').'/'.$project->getId().'/'.$project->getCssFile())
            );
        }
        
        $cssForm = $this->createForm('AppBundle\Form\CssFileType', $project);
        $cssForm->handleRequest($request);
        $deleteForm = $this->createDeleteForm($project);

        if ($cssForm->isSubmitted() && $cssForm->isValid()) {

            # Wrong MIME type detection
            # Need Work, but ok for self project

            $file = $project->getCssFile();

            $fileName = $file->getClientOriginalName();

            // Delete Other Css Files and Directory
            
            $AppBundle = new AppBundle();
            $AppBundle->removeDirectory($this->getParameter('css_directory').'/'.$project->getId());

            $file->move(
                $this->getParameter('css_directory').'/'.$project->getId(),
                $fileName
            );
            $project->setCssFile($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush($project);

            return $this->redirectToRoute('project_show', array('id' => $project->getId()));
        }

        $errors = array();
        foreach ($cssForm as $fieldName => $formField) {
            // each field has an array of errors
            $errors[$fieldName] = $formField->getErrors();
        }

        return $this->render('project/show.html.twig', array(
            'project' => $project,
            'css_form' => $cssForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'cssFileShort' => $cssFileShort,
        ));
    }

    /**
     * Displays a form to edit an existing project entity.
     *
     * @Route("/{id}/edit", name="project_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Project $project)
    {
        $deleteForm = $this->createDeleteForm($project);
        $editForm = $this->createForm('AppBundle\Form\ProjectType', $project);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('project_index');
        }

        return $this->render('project/edit.html.twig', array(
            'project' => $project,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a project entity.
     *
     * @Route("/{id}", name="project_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Project $project)
    {
        $form = $this->createDeleteForm($project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush($project);
        }

        return $this->redirectToRoute('project_index');
    }

    /**
     * Deletes the uploaded css file.
     *
     * @Route("/{id}/delete_css", name="delete_css")
     * @Method("GET")
     */
    public function deleteCssAction(Project $project)
    {
        $AppBundle = new AppBundle();
        $AppBundle->removeDirectory($this->getParameter('css_directory').'/'.$project->getId());
        $project->setCssFile('');
        $project->setSelectors('0');
        $em = $this->getDoctrine()->getManager();
        $em->persist($project);

        $selectors = $em->getRepository('AppBundle:Selector')
        ->findByProjectId($project->getId());

        foreach ($selectors as $selector) {
            $em->remove($selector);
        }

        $em->flush($project);

        return $this->redirectToRoute('project_show', array('id' => $project->getId()));
    }

    /**
     * Crunches the uploaded css file.
     *
     * @Route("/{id}/crunch_css", name="crunch_css")
     * @Method("GET")
     */
    public function crunchCssAction(Project $project)
    {
        $AppBundle = new AppBundle();
        $selectorArray = $AppBundle->crunchCss($this->getParameter('css_directory').'/'.$project->getId().'/'.$project->getCssFile());

        $project->setSelectors(count($selectorArray));
        $em = $this->getDoctrine()->getManager();
        $em->persist($project);

        foreach($selectorArray as $value){
            $selector = new Selector();
            $selector->setProjectId($project->getId());
            $selector->setName($value);
            $em->persist($selector);
        }

        $em->flush($project);

        return $this->redirectToRoute('project_show', array('id' => $project->getId()));
    }

    /**
     * Creates a form to delete a project entity.
     *
     * @param Project $project The project entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Project $project)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('project_delete', array('id' => $project->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
