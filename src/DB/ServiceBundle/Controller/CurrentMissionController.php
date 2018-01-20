<?php

namespace DB\ServiceBundle\Controller;

use DB\ServiceBundle\Entity\CurrentMission;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use DB\ServiceBundle\Controller\LogController;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

class CurrentMissionController extends LogController {

    public function getSecureResourceAction() {
        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Get a CurrentMission instance",
     *  output = "DB\ServiceBundle\Entity\CurrentMission",
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
    public function getCurrentMissionAction($id) {
        $em = $this->getDoctrine()->getManager('service');
        $entity = $em->getRepository('DBServiceBundle:CurrentMission')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity');
        }
        return $entity;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Get all CurrentMission instance",
     *  output = "DB\ServiceBundle\Entity\Profile",
     * filters={
     *      {"name"="order", "dataType"="string"},
     *      {"name"="sort_by", "dataType"="string"},
     *      {"name"="per_page", "dataType"="integer"},
     *      {"name"="page", "dataType"="integer"},
     *      {"name"="idUser[]", "dataType"="int", "description"="User Id"},
     *      {"name"="distance[]", "dataType"="float" , "description"="distance maked"},
     *      {"name"="countMissionCount[]", "dataType"="int"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned  when User table is empty"
     *   }
     * )
     * 
     * Get all CurrentMission instance 
     * @param int $id Id of the User
     * @return array User
     * @throws NotFoundHttpException when User table is empty
     * 
     * @return View()
     * 
     * @Rest\View()
     */
    public function getCurrentMissionsAction(Request $request) {
        $em = $this->getDoctrine()->getManager('service');
        if (sizeof($request->query->all()) == 0)
            $entities = $em->getRepository('DBServiceBundle:CurrentMission')->findBy(array());
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
            $entities = $em->getRepository('DBServiceBundle:CurrentMission')->my_findBy($var, $orderby, $limit, $offset);
        }
        if (!$entities) {
            throw $this->createNotFoundException('No User found');
        }
        return $entities;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="post a CurrentMission instance",
     *  output = "DB\UserBundle\Entity\CurrentMission",
     *  statusCodes = {
     *     200 = "Returned when the request success",
     *     404 = "Returned when the account not found",
     *   },
     *  parameters={
     *      {"name"="status", "dataType"="int", "required"=true, "description"="user ID"},
     *      {"name"="distanceMake", "dataType"="float", "required"=true, "description"="user ID"},
     *      {"name"="idUser", "dataType"="int", "required"=false, "description"="user ID"},
     *      {"name"="idMission", "dataType"="int", "required"=false, "description"="user ID"},
     *      {"name"="special", "dataType"="int", "required"=false, "description"="user ID"},
     *      {"name"="type", "dataType"="int", "required"=true, "description"="user ID"}
     *  }
     * )
     * 
     * Post action
     * @param Request $request
     * @return View|array
     * 
     */
    public function postCurrentMissionAction(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager('service');

            $cuurentmission = new CurrentMission();
            $user = $em->getRepository('DBUserBundle:User')->find(
                    $request->request->get('idUser')
            );
            if (!$user) {
                throw $this->createNotFoundException('no\'t found User');
            }
            $cuurentmission->setIdUser($user);
            $mission = $em->getRepository('DBUserBundle:Mission')->find(
                    $request->request->get('idMission')
            );
            if (!$mission) {
                throw $this->createNotFoundException('no\'t found Mission');
            }
            $cuurentmission->setIdMission($idMission);
            $cuurentmission->setStatus($request->request->get('status'));
            $cuurentmission->setDistanceMake($request->request->get('distanceMake'));
            $cuurentmission->set($request->request->get('distance'));
            $cuurentmission->setDistance($request->request->get('distance'));

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
     *  description="put a CurrentMission instance",
     *  output = "DB\DBServiceBundle\Entity\Profile",
     *  statusCodes = {
     *     200 = "Returned when the request success",
     *     404 = "Returned when the account not found",
     *   },
     *  parameters={
     *       {"name"="name", "dataType"="string", "required"=true, "description"="user ID"},
     *      {"name"="distance", "dataType"="string", "required"=true, "description"="user ID"},
     *      {"name"="special", "dataType"="int", "required"=false, "description"="user ID"},
     *      {"name"="type", "dataType"="int", "required"=true, "description"="user ID"}
     *  }
     * )
     * 
     * Post action
     * @param Request $request
     * @return View|array
     * 
     */
    public function putCurrentMissionAction(Request $request, $id) {
        try {
            $em = $this->getDoctrine()->getManager('service');
            $cuurentmission = $em->getRepository('DBServiceBundle:CurrentMission')->find($id);
            if ($request->request->get('idUser')) {
                $user = $em->getRepository('DBUserBundle:User')->find(
                        $request->request->get('idUser')
                );
                if (!$user)
                    throw $this->createNotFoundException('no\'t found User');
                $cuurentmission->setIdUser($user);
            }
            if ($request->request->get('idMission')) {
                $mission = $em->getRepository('DBUserBundle:Mission')->find(
                        $request->request->get('idUser')
                );
                if (!$mission)
                    throw $this->createNotFoundException('no\'t found Mission');
                $cuurentmission->setIdMission($mission);
            }
            if ($request->request->get('name'))
                $cuurentmission->setName($request->request->get('name'));
            if ($request->request->get('special'))
                $cuurentmission->setSpecial($request->request->get('special'));
            if ($request->request->get('type'))
                $cuurentmission->setType($request->request->get('type'));
            if ($request->request->get('distance'))
                $cuurentmission->setDistance($request->request->get('distance'));

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
