<?php

namespace Ikerib\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Ikerib\BackendBundle\Entity\Todo;
use Ikerib\BackendBundle\Form\TodoType;
use Ikerib\BackendBundle\Entity\Tag;
use Ikerib\BackendBundle\Form\TaggehituType;

/**
 * Todo controller.
 *
 */
class TodoController extends Controller
{

  public function indexAction()
  {
    $em = $this->getDoctrine()->getManager();
    $entities = $em->getRepository('BackendBundle:Todo')->findAll();
    $entity = new Todo();
    $form   = $this->createForm(new TodoType(), $entity);

    return $this->render('BackendBundle:Todo:index.html.twig', array(
      'entities' => $entities,
      'form'   => $form->createView(),
      ));
  }

  public function createAction(Request $request)
  {
      $entity  = new Todo();
      $form = $this->createForm(new TodoType(), $entity);
      $form->bind($request);

      if ($form->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $em->persist($entity);
          $em->flush();

          return $this->redirect($this->generateUrl('todo'));
      }

      return $this->render('BackendBundle:Todo:new.html.twig', array(
          'entity' => $entity,
          'form'   => $form->createView(),
      ));
  }

  public function updateAction(Request $request, $id)
  {
    if ($request->isXmlHttpRequest()) {
      $em = $this->getDoctrine()->getManager();

      $entity = $em->getRepository('BackendBundle:Todo')->find($id);

      if (!$entity) {

        throw $this->createNotFoundException('Unable to find Todo entity.');

      } else {
        $completado = $entity->getCompleted();
        if ( $completado == 0) {
          $entity->setCompleted(1);
        } else {
          $entity->setCompleted(0);
        }
        $em->persist($entity);
        $em->flush();

        $response = new Response(json_encode(array('completed' => $entity->getCompleted())));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
      }
    }
  }

  public function deleteAction($id, $token)
  {
    $em = $this->getDoctrine()->getManager();
    $entity = $em->getRepository('BackendBundle:Todo')->find($id);
    if (!$entity) {
      throw $this->createNotFoundException('Unable to find Todo entity.');
    }
    $csrf  = $this->container->get('form.csrf_provider');

    if ($csrf->isCsrfTokenValid($entity->getCsrfIntention('delete'), $token)) {
      $em->remove($entity);
      $em->flush();
    } else {
          throw $this->createNotFoundException('Token es incorrecto.');
    }

    return $this->redirect($this->generateUrl('todo'));

  }

  public function clearcompletedAction() {
    $em = $this->getDoctrine()->getManager();

    $nocompletados = $em->getRepository('BackendBundle:Todo')->findBy(array('completed' => 1));
    if ($nocompletados) {
      foreach ($nocompletados as $c) {
        $em->remove($c);
      }
      $em->flush();
    }
    return $this->redirect($this->generateUrl('todo'));
  }

  public function todotoggleAction() {
    $em = $this->getDoctrine()->getManager();

    $nocompletados = $em->getRepository('BackendBundle:Todo')->findBy(array('completed' => 0));
    if ($nocompletados) {
      foreach ($nocompletados as $c) {
        $c->setCompleted(1);
        $em->persist($c);
      }
      $em->flush();
    }
    return $this->redirect($this->generateUrl('todo'));
  }

}
