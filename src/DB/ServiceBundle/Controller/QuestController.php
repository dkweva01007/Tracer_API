<?php

namespace DB\ServiceBundle\Controller;

use DB\ServiceBundle\Entity\Quest;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use DB\ServiceBundle\Controller\LogController;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

class QuestController extends LogController {

    public function getSecureResourceAction() {
        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Get a Quest instance",
     *  output = "DB\ServiceBundle\Entity\Quest",
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the User is not found"
     *   }
     * )
     * 
     * Get all Quest instance
     * @param int $id Id of the User
     * @return array User
     * @throws NotFoundHttpException when User not exist
     * 
     * @Rest\View()
     */
    public function getQuestAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DBServiceBundle:Quest')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quest entity');
        }
        return $entity;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Get all Quest instance",
     *  output = "DB\ServiceBundle\Entity\Quest",
     * filters={
     *      {"name"="order", "dataType"="string"},
     *      {"name"="sort_by", "dataType"="string"},
     *      {"name"="per_page", "dataType"="integer"},
     *      {"name"="page", "dataType"="integer"},
     *      {"name"="id[]", "dataType"="int", "description"=" Id"},
     *      {"name"="time[]", "dataType"="float", "description"=" Id"},
     *      {"name"="special[]", "dataType"="float" , "description"="distance maked"},
     *      {"name"="distance[]", "dataType"="float" , "description"="distance maked"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned  when User table is empty"
     *   }
     * )
     * 
     * Get all User instance 
     * @param int $id Id of the User
     * @return array User
     * @throws NotFoundHttpException when User table is empty
     * 
     * @return View()
     * 
     * @Rest\View()
     */
    public function getQuestsAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (sizeof($request->query->all()) == 0)
            $entities = $em->getRepository('DBServiceBundle:Quest')->findBy(array());
        else {
            $var = array();
            $limit = null;
            $offset = null;
            $orderby = null;
            $tmp = $request->query->all();
            foreach ($tmp as $key => $value) {
                switch ($key) {
                    case '_format':
                        break;
                    case 'order':
                    case 'sort_by':
                        $orderby = array($tmp['sort_by'] => $tmp['order']);
                        break;
                    case 'per_page':
                    case 'page':
                        $limit = (int) $tmp['per_page'];
                        $offset = $limit * ( (int) $tmp['page'] - 1);
                        break;
                    default:
                        if (is_array($value)) {
                            $var[$key] = array();
                            foreach ($value as $value2)
                                $var[$key][] = $value2;
                        } else
                            $var[$key] = $value;
                        break;
                }
            }
            $entities = $em->getRepository('DBServiceBundle:Quest')->my_findBy($var, $orderby, $limit, $offset);
        }
        if (!$entities) {
            throw $this->createNotFoundException('No Quest found');
        }
        return $entities;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="post a Quest instance",
     *  output = "DB\UserBundle\Entity\Quest",
     *  statusCodes = {
     *     200 = "Returned when the request success",
     *     404 = "Returned when the account not found",
     *   },
     *  parameters={
     *      {"name"="name", "dataType"="string", "required"=true, "description"="user ID"},
     *      {"name"="distance", "dataType"="float", "required"=true, "description"="user ID"},
     *      {"name"="special", "dataType"="int", "required"=true, "description"="user ID"},
     *      {"name"="time", "dataType"="float", "required"=true, "description"="user ID"}
     *  }
     * )
     * 
     * Post action
     * @param Request $request
     * @return View|array
     * 
     */
    public function postQuestAction(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();

            $mission = new Quest();
            $mission->setName($request->request->get('name'));
            $mission->setTime($request->request->get('time',0.00));
            $mission->setDistance($request->request->get('distance', 0.000));
            $mission->setSpecial($request->request->get('special', 0));
            $em->persist($mission);
            $em->flush();
            return $mission;
        } catch (\Doctrine\ORM\ORMException $e) {
            $loLogger = $this->get('logger');
            //$loLogger->critical($e->getMessage());
            $loLogger->critical("Échec : " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="put a Quest instance",
     *  output = "DB\DBServiceBundle\Entity\Quest",
     *  statusCodes = {
     *     200 = "Returned when the request success",
     *     404 = "Returned when the account not found",
     *   },
     *  parameters={
     *       {"name"="name", "dataType"="string", "required"=true, "description"="user ID"},
     *      {"name"="distance", "dataType"="float", "required"=true, "description"="user ID"},
     *      {"name"="special", "dataType"="int", "required"=true, "description"="user ID"},
     *      {"name"="time", "dataType"="float", "required"=true, "description"="user ID"}
     *  }
     * )
     * 
     * Post action
     * @param Request $request
     * @return View|array
     * 
     */
    public function putQuestAction(Request $request, $id) {
        try {
            $em = $this->getDoctrine()->getManager();
            $mission = $em->getRepository('DBServiceBundle:Quest')->find($id);
            if ($request->request->get('name'))
                $mission->setName($request->request->get('name'));
            if ($request->request->get('time') !== null)
                $mission->setTime($request->request->get('time'));
            if ($request->request->get('distance') !== null)
                $mission->setDistance($request->request->get('distance', 0.000));
            if ($request->request->get('special') !== null)
            $mission->setSpecial($request->request->get('special', 0) );

            $em->persist($mission);
            $em->flush();
            return $mission;
        } catch (\Doctrine\ORM\ORMException $e) {
            $loLogger = $this->get('logger');
            //$loLogger->critical($e->getMessage());
            $loLogger->critical("Échec : " . $e->getMessage());
            throw $e;
        }
    }

}
