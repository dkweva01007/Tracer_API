<?php

namespace DB\ServiceBundle\Controller;

use DB\ServiceBundle\Entity\Friend;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use DB\ServiceBundle\Controller\LogController;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

class FriendController extends LogController {

    public function getSecureResourceAction() {
        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Get a Friend instance",
     *  output = "DB\ServiceBundle\Entity\Friend",
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the User is not found"
     *   }
     * )
     * 
     * Get all FriendC instance
     * @param int $id Id of the User
     * @return array User
     * @throws NotFoundHttpException when User not exist
     * 
     * @Rest\View()
     */
    public function getFriendAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DBServiceBundle:Friend')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity');
        }
        return $entity;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Get all Friend instance",
     *  output = "DB\ServiceBundle\Entity\Friend",
     * filters={
     *      {"name"="order", "dataType"="string"},
     *      {"name"="sort_by", "dataType"="string"},
     *      {"name"="per_page", "dataType"="integer"},
     *      {"name"="page", "dataType"="integer"},
     *      {"name"="idUser1[]", "dataType"="int", "description"="User Id"},
     *      {"name"="idUser2[]", "dataType"="int" , "description"="distance maked"},
     *      {"name"="status[]", "dataType"="int"}
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
    public function getFriendsAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (sizeof($request->query->all()) == 0)
            $entities = $em->getRepository('DBServiceBundle:Friend')->findBy(array(), array('updated' => 'DESC'));
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
            $entities = $em->getRepository('DBServiceBundle:Friend')->my_findBy($var, $orderby, $limit, $offset);
        }
        if (!$entities) {
            throw $this->createNotFoundException('No Friend found');
        }
        return $entities;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="post a Friend instance",
     *  output = "DB\UserBundle\Entity\Friend",
     *  statusCodes = {
     *     200 = "Returned when the request success",
     *     404 = "Returned when the account not found",
     *   },
     *  parameters={
     *      {"name"="idUser1", "dataType"="int", "required"=true, "description"="user ID"},
     *      {"name"="idUser2", "dataType"="int", "required"=true, "description"="user ID"},
     *      {"name"="status", "dataType"="int", "required"=true, "description"="user ID"}
     *  }
     * )
     * 
     * Post action
     * @param Request $request
     * @return View|array
     * 
     */
    public function postFriendAction(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();

            $friend = new Friend();
            $user1 = $em->getRepository('DBUserBundle:User')->find(
                    $request->request->get('idUser1')
            );
            if (!$user1) {
                throw $this->createNotFoundException('no\'t found User1');
            }
            $user2 = $em->getRepository('DBUserBundle:User')->find(
                    $request->request->get('idUser2')
            );
            if (!$user2) {
                throw $this->createNotFoundException('no\'t found User2');
            }
            $friend->setIdUser1($user1);
            $friend->setIdUser2($user2);
            $friend->setStatus($request->request->get('status', 0));
            $em->persist($friend);
            $em->flush();
            return $friend;
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
     *  description="put a Friend instance",
     *  output = "DB\DBServiceBundle\Entity\Friend",
     *  statusCodes = {
     *     200 = "Returned when the request success",
     *     404 = "Returned when the account not found",
     *   },
     *  parameters={
     *      {"name"="idUser1", "dataType"="int", "required"=true, "description"="user ID"},
     *      {"name"="idUser2", "dataType"="int", "required"=true, "description"="user ID"},
     *      {"name"="status", "dataType"="int", "required"=true, "description"="user ID"}
     *  }
     * )
     * 
     * Post action
     * @param Request $request
     * @return View|array
     * 
     */
    public function putFriendAction(Request $request, $id) {
        try {
            $em = $this->getDoctrine()->getManager();
            $friend = $em->getRepository('DBServiceBundle:Friend')->find($id);
            if (!$friend) {
                throw $this->createNotFoundException('no\'t found Friend');
            }
            if ($request->request->get('idUser1')) {
                $user1 = $em->getRepository('DBUserBundle:User')->find(
                        $request->request->get('idUser1')
                );
                if (!$user1) {
                    throw $this->createNotFoundException('no\'t found User1');
                }
                $friend->setIdUser1($user1);
            }
            if ($request->request->get('idUser2')) {
                $user2 = $em->getRepository('DBUserBundle:User')->find(
                        $request->request->get('idUser2')
                );
                if (!$user2) {
                    throw $this->createNotFoundException('no\'t found User2');
                }
                $friend->setIdUser2($user2);
            }
            if ($request->request->get('status', 0))
                $friend->setStatus($request->request->get('status', 0));

            $em->persist($friend);
            $em->flush();
            return $friend;
        } catch (\Doctrine\ORM\ORMException $e) {
            $loLogger = $this->get('logger');
            //$loLogger->critical($e->getMessage());
            $loLogger->critical("Échec : " . $e->getMessage());
            throw $e;
        }
    }

}
