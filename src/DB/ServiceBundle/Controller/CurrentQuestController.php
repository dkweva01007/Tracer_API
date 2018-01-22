<?php

namespace DB\ServiceBundle\Controller;

use DB\ServiceBundle\Entity\CurrentQuest;
use DB\ServiceBundle\Entity\Quest;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use DB\ServiceBundle\Controller\LogController;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

class CurrentQuestController extends LogController {

    public function getSecureResourceAction() {
        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Get a CurrentQuest instance",
     *  output = "DB\ServiceBundle\Entity\CurrentQuest",
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the User is not found"
     *   }
     * )
     * 
     * Get all Profile instance
     * @param int $id Id of the User
     * @return array User
     * @throws NotFoundHttpException when User not exist
     * 
     * @Rest\View()
     */
    public function getCurrent_questAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DBServiceBundle:CurrentQuest')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find CurrentQuest entity');
        }
        return $entity;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Get all CurrentQuest instance",
     *  output = "DB\ServiceBundle\Entity\CurrentQuest",
     * filters={
     *      {"name"="order", "dataType"="string"},
     *      {"name"="sort_by", "dataType"="string"},
     *      {"name"="per_page", "dataType"="integer"},
     *      {"name"="page", "dataType"="integer"},
     *      {"name"="idUser[]", "dataType"="int", "description"="User Id"},
     *      {"name"="idQuest[]", "dataType"="int" , "description"="distance maked"},
     *      {"name"="status[]", "dataType"="int"},
     *      {"name"="special[]", "dataType"="float"},
     *      {"name"="distanceMake[]", "dataType"="float"},
     *      {"name"="timeMake[]", "dataType"="float"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned  when User table is empty"
     *   }
     * )
     * 
     * Get all CurrentQuest instance 
     * @param int $id Id of the User
     * @return array User
     * @throws NotFoundHttpException when User table is empty
     * 
     * @return View()
     * 
     * @Rest\View()
     */
    public function getCurrent_questsAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (sizeof($request->query->all()) == 0)
            $entities = $em->getRepository('DBServiceBundle:CurrentQuest')->findBy(array());
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
            $entities = $em->getRepository('DBServiceBundle:CurrentQuest')->my_findBy($var, $orderby, $limit, $offset);
        }
        if (!$entities) {
            throw $this->createNotFoundException('No CurrentQuest found');
        }
        return $entities;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="post a CurrentQuest instance",
     *  output = "DB\UserBundle\Entity\CurrentQuest",
     *  statusCodes = {
     *     200 = "Returned when the request success",
     *     404 = "Returned when the account not found",
     *   },
     *  parameters={
     *      {"name"="status", "dataType"="int", "required"=true, "description"="user ID"},
     *      {"name"="special", "dataType"="int", "required"=true, "description"="user ID"},
     *      {"name"="distanceMake", "dataType"="float", "required"=true, "description"="user ID"},
     *      {"name"="idUser", "dataType"="int", "required"=false, "description"="user ID"},
     *      {"name"="idQuest", "dataType"="int", "required"=false, "description"="user ID"},
     *      {"name"="timeMake", "dataType"="float", "required"=false, "description"="user ID"}
     *  }
     * )
     * 
     * Post action
     * @param Request $request
     * @return View|array
     * 
     */
    public function postCurrent_questAction(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();

            $cuurentmission = new CurrentQuest();
            $user = $em->getRepository('DBUserBundle:User')->find(
                    $request->request->get('idUser')
            );
            if (!$user) {
                throw $this->createNotFoundException('no\'t found User');
            }
            $cuurentmission->setIdUser($user);
            $mission = $em->getRepository('DBServiceBundle:Quest')->find(
                    $request->request->get('idQuest')
            );
            if (!$mission) {
                throw $this->createNotFoundException('no\'t found Quest');
            }
            $cuurentmission->setIdQuest($mission);
            if ($request->request->get('status'))
                $cuurentmission->setStatus($request->request->get('status'));
            if ($request->request->get('special'))
                $cuurentmission->setSpecial($request->request->get('special'));
            if ($request->request->get('distanceMake'))
                $cuurentmission->setDistanceMake($request->request->get('distanceMake'));
            if ($request->request->get('timeMake'))
                $cuurentmission->setTimeMake($request->request->get('timeMake'));
            $em->persist($cuurentmission);
            $em->flush();
            return $cuurentmission;
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
     *  description="put a CurrentQuest instance",
     *  output = "DB\DBServiceBundle\Entity\CurrentQuest",
     *  statusCodes = {
     *     200 = "Returned when the request success",
     *     404 = "Returned when the account not found",
     *   },
     *  parameters={
     *      {"name"="status", "dataType"="int", "required"=true, "description"="user ID"},
     *      {"name"="special", "dataType"="int", "required"=true, "description"="user ID"},
     *      {"name"="distanceMake", "dataType"="float", "required"=true, "description"="user ID"},
     *      {"name"="idUser", "dataType"="int", "required"=false, "description"="user ID"},
     *      {"name"="idQuest", "dataType"="int", "required"=false, "description"="user ID"},
     *      {"name"="timeMake", "dataType"="float", "required"=false, "description"="user ID"}
     *  }
     * )
     * 
     * Post action
     * @param Request $request
     * @return View|array
     * 
     */
    public function putCurrent_questAction(Request $request, $id) {
        try {
            $em = $this->getDoctrine()->getManager();
            $cuurentmission = $em->getRepository('DBServiceBundle:CurrentQuest')->find($id);
            if ($request->request->get('idUser')) {
                $user = $em->getRepository('DBUserBundle:User')->find(
                        $request->request->get('idUser')
                );
                if (!$user)
                    throw $this->createNotFoundException('no\'t found User');
                $cuurentmission->setIdUser($user);
            }
            if ($request->request->get('idQuest')) {
                $mission = $em->getRepository('DBUserBundle:Quest')->find(
                        $request->request->get('idQuest')
                );
                if (!$mission)
                    throw $this->createNotFoundException('no\'t found Quest');
                $cuurentmission->setIdMission($mission);
            }
            if ($request->request->get('status'))
                $cuurentmission->setStatus($request->request->get('status'));
            if ($request->request->get('special'))
                $cuurentmission->setSpecial($request->request->get('special'));
            if ($request->request->get('distanceMake'))
                $cuurentmission->setDistanceMake($request->request->get('distanceMake'));
            if ($request->request->get('timeMake'))
                $cuurentmission->setTimeMake($request->request->get('timeMake'));
            $em->persist($cuurentmission);
            $em->flush();
            return $cuurentmission;
        } catch (\Doctrine\ORM\ORMException $e) {
            $loLogger = $this->get('logger');
            //$loLogger->critical($e->getMessage());
            $loLogger->critical("Échec : " . $e->getMessage());
            throw $e;
        }
    }

}
