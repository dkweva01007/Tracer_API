<?php

namespace DB\ServiceBundle\Controller;

use DB\ServiceBundle\Entity\Profile;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use DB\ServiceBundle\Controller\LogController;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

class ProfileController extends LogController {

    public function getSecureResourceAction() {
        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Get a Profile instance",
     *  output = "DB\ServiceBundle\Entity\Profile",
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
    public function getProfileAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DBServiceBundle:Profile')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity');
        }
        return $entity;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Get all Profile instance",
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
     * Get all User instance 
     * @param int $id Id of the User
     * @return array User
     * @throws NotFoundHttpException when User table is empty
     * 
     * @return View()
     * 
     * @Rest\View()
     */
    public function getProfilesAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (sizeof($request->query->all()) == 0)
            $entities = $em->getRepository('DBServiceBundle:Profile')->findBy(array(), array('updated' => 'DESC'));
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
            $entities = $em->getRepository('DBServiceBundle:Profile')->my_findBy($var, $orderby, $limit, $offset);
        }
        if (!$entities) {
            throw $this->createNotFoundException('No Profile found');
        }
        return $entities;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="post a Profile instance",
     *  output = "DB\UserBundle\Entity\User",
     *  statusCodes = {
     *     200 = "Returned when the request success",
     *     404 = "Returned when the account not found",
     *   },
     *  parameters={
     *      {"name"="idUser", "dataType"="int", "required"=true, "description"="user ID"},
     *      {"name"="distance", "dataType"="float", "required"=true, "description"="distance make"},
     *      {"name"="countMissionComplete", "dataType"="int", "required"=true, "description"="count Mission Complete"},
     *      {"name"="countTime", "dataType"="float", "required"=true, "description"="count Mission Complete"},
     *      {"name"="average", "dataType"="float", "required"=true, "description"="count Mission Complete"},
     *      {"name"="xp", "dataType"="int", "required"=true, "description"="player xp"},
     *      {"name"="level", "dataType"="int", "required"=true, "description"="player level"}
     *  }
     * )
     * 
     * Post action
     * @param Request $request
     * @return View|array
     * 
     */
    public function postProfileAction(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();

            $profile = new Profile();
            $user = $em->getRepository('DBUserBundle:User')->find(
                    $request->request->get('idUser')
            );
            if (!$user) {
                throw $this->createNotFoundException('no\'t found User');
            }
            $profile->setDistance($request->request->get('distance', 0.000));
            $profile->setCountMissionComplete($request->request->get('countMissionComplete', 0));
            $profile->setCountTime($request->request->get('countTime', 0.00));
            $profile->setAverage($request->request->get('average', 0));
            $profile->setXp($request->request->get('xp', 1));
            $profile->setLevel($request->request->get('level', 1));
            $profile->setIdUser($user);
            $em->persist($profile);
            $em->flush();
            return $profile;
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
     *  description="put a User instance",
     *  output = "DB\DBServiceBundle\Entity\Profile",
     *  statusCodes = {
     *     200 = "Returned when the request success",
     *     404 = "Returned when the account not found",
     *   },
     *  parameters={
     *      {"name"="idUser", "dataType"="int", "required"=true, "description"="user ID"},
     *      {"name"="distance", "dataType"="float", "required"=true, "description"="distance make"},
     *      {"name"="countMissionComplete", "dataType"="int", "required"=true, "description"="count Mission Complete"},
     *      {"name"="countTime", "dataType"="float", "required"=true, "description"="count Mission Complete"},
     *      {"name"="average", "dataType"="float", "required"=true, "description"="count Mission Complete"},
     *      {"name"="xp", "dataType"="float", "required"=true, "description"="player xp"},
     *      {"name"="level", "dataType"="float", "required"=true, "description"="player level"}
     *  }
     * )
     * 
     * Post action
     * @param Request $request
     * @return View|array
     * 
     */
    public function putProfileAction(Request $request, $id) {
        try {
            $em = $this->getDoctrine()->getManager();
            $profile = $em->getRepository('DBServiceBundle:Profile')->find($id);
            if (!$profile) {
                throw $this->createNotFoundException('no\'t found Profile');
            }
            if ($request->request->get('idUser')) {
                $user = $em->getRepository('DBUserBundle:User')->find(
                        $request->request->get('idUser')
                );
                if (!$user)
                    throw $this->createNotFoundException('no\'t found User');
                $profile->setIdUser($user);
            }
            if ($request->request->get('distance') !== null)
                $profile->setDistance($request->request->get('distance', 0.000));
            if ($request->request->get('countMissionComplete') !== null)
                $profile->setCountMissionComplete($request->request->get('countMissionComplete', 0));
            if ($request->request->get('countTime') !== null)
                $profile->setCountTime($request->request->get('countTime', 0.0));
            if ($request->request->get('average') !== null)
                $profile->setAverage($request->request->get('average', 0));
            if ($request->request->get('xp') !== null)
                $profile->setXp($request->request->get('xp', 1));
            if ($request->request->get('level') !== null)
                $profile->setLevel($request->request->get('level', 1));

            $em->persist($profile);
            $em->flush();
            return $profile;
        } catch (\Doctrine\ORM\ORMException $e) {
            $loLogger = $this->get('logger');
            //$loLogger->critical($e->getMessage());
            $loLogger->critical("Échec : " . $e->getMessage());
            throw $e;
        }
    }

}
