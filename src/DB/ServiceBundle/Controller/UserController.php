<?php

namespace DB\ServiceBundle\Controller;

use DB\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use DB\ServiceBundle\Controller\LogController;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserController extends LogController {

    public function getSecureResourceAction() {
        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Get a User instance",
     *  output = "DB\UserBundle\Entity\User",
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the User is not found"
     *   }
     * )
     * 
     * Get all User instance by website
     * @param int $id Id of the User
     * @return array User
     * @throws NotFoundHttpException when User not exist
     * 
     * @Rest\View()
     */
    public function getUserAction($id) {
        $em = $this->getDoctrine()->getManager('service');
        $entity = $em->getRepository('DBUserBundle:User')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity');
        }
        return $entity;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Get all User instance",
     *  output = "DB\UserBundle\Entity\User",
     * filters={
     *      {"name"="order", "dataType"="string"},
     *      {"name"="sort_by", "dataType"="string"},
     *      {"name"="per_page", "dataType"="integer"},
     *      {"name"="page", "dataType"="integer"},
     *      {"name"="createdDate[]", "dataType"="datetime", "description"="Y-m-d h:m:i"},
     *      {"name"="updatedDate[]", "dataType"="datetime" , "description"="Y-m-d h:m:i"},
     *      {"name"="email[]", "dataType"="string"},
     *      {"name"="username[]", "dataType"="string"}
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
    public function getUsersAction(Request $request) {
        $em = $this->getDoctrine()->getManager('service');
        if (sizeof($request->query->all()) == 0)
            $entities = $em->getRepository('DBUserBundle:User')->findBy(array(), array('createdDate' => 'DESC'));
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
            $entities = $em->getRepository('DBUserBundle:User')->my_findBy($var, $orderby, $limit, $offset);
        }
        if (!$entities) {
            throw $this->createNotFoundException('No User found');
        }
        return $entities;
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Put a User instance",
     *  output = "DB\ServiceBundle\Entity\User",
     *  statusCodes = {
     *     200 = "Returned when the request success",
     *     404 = "Returned when the User not exist",
     *     404 = "Returned when the request fail"
     *   },
     *  parameters={
     *      {"name"="email", "dataType"="string(160)", "required"=true, "description"="Customer's mail"},
     *      {"name"="current_pswd", "dataType"="string", "required"=true, "description"="current password"},
     *      {"name"="password", "dataType"="string", "required"=true, "description"="new password"},
     *      {"name"="firstName", "dataType"="string", "required"=true, "description"="new first name"},
     *      {"name"="LastName", "dataType"="string", "required"=true, "description"="new last name"}
     *  }
     * )
     * 
     * Put action
     * @param Request $request
     * @param integer $id Id of the entity
     * @return View|array
     * 
     */
    //il faudra un message du prestaire lors de la confirmation du prestataire
    public function putUserAction(Request $request, $id) {
        try {

            $userManager = $this->get('fos_user.user_manager');
            if (!$user = $userManager->findUserBy(array('id' => $id)))
                throw new \Exception('not found ID');

            if (!verification($user, $request->request->get('current_pswd')))
                throw new \Exception('Username or Password not valid.');

            if ($request->request->get('email'))
                $user->setEmail($request->request->get('email'));
            if ($request->request->get('firstName'))
                $user->setFirstName($request->request->get('firstName'));
            if ($request->request->get('lastName'))
                $user->setLastName($request->request->get('lastName'));
            if ($request->request->get('password'))
                $user->setPlainPassword($request->request->get('password'));
            if ($request->request->get('locke'))
                $user->setLocked(0); // don't lock the user
            if ($request->request->get('enable'))
                $user->setEnabled(1);
            $userManager->updateUser($user);
            return $user;
        } catch (\Doctrine\ORM\ORMException $e) {
            $loLogger = $this->get('logger');
            //$loLogger->critical($e->getMessage());
            $loLogger->critical("Parameter(s) : " . $this->writelog($request->request->all())
            );
            throw $e;
        }
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="Login",
     *  output = "DB\UserBundle\Entity\User",
     *  statusCodes = {
     *     200 = "Returned when the request success",
     *     404 = "Returned when the User not exist",
     *     404 = "Returned when the request fail"
     *   },
     *  parameters={
     *      {"name"="email", "dataType"="string(160)", "required"=true, "description"="Customer's mail"},
     *      {"name"="current_pswd", "dataType"="string", "required"=true, "description"="current password"},
     *      {"name"="password", "dataType"="string", "required"=true, "description"="new password"},
     *      {"name"="firstName", "dataType"="string", "required"=true, "description"="new first name"},
     *      {"name"="LastName", "dataType"="string", "required"=true, "description"="new last name"}
     *  }
     * )
     * 
     * Put action
     * @param Request $request
     * @param integer $id Id of the entity
     * @return View|array
     * 
     */
    public function verification(User $user, $current_pswd) {
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);

        return $encoder->isPasswordValid($user->getPassword(), $current_pswd, $user->getSalt());
    }

    /**
     * @ApiDoc(
     * resource=true,
     *  description="post a User instance",
     *  output = "DB\UserBundle\Entity\User",
     *  statusCodes = {
     *     200 = "Returned when the request success",
     *     404 = "Returned when the account not found",
     *   },
     *  parameters={
     *      {"name"="email", "dataType"="array", "required"=true, "description"="customer's email"},
     *      {"name"="password", "dataType"="array", "required"=true, "description"="login password"},
     *      {"name"="firstName", "dataType"="array", "required"=true, "description"="Customer's first name"},
     *      {"name"="lastName", "dataType"="int", "required"=true, "description"="Customers's last name"},
     *      {"name"="username", "dataType"="int", "required"=true, "description"="Login"}
     *  }
     * )
     * 
     * Post action
     * @param Request $request
     * @return View|array
     * 
     */
    public function postUserAction(Request $request) {
        try {
            $userManager = $this->get('fos_user.user_manager');

            if ($userManager->findUserByEmail($request->request->get('email')))
                throw new \Exception('Email exist');
            if ($userManager->findUserByUsername($request->request->get('username')))
                throw new \Exception('username exist');

            $user = $userManager->createUser();
            $user->setUsername($request->request->get('username'));
            $user->setEmail($request->request->get('email'));
            $user->setFirstName($request->request->get('firstName'));
            $user->setLastName($request->request->get('lastName'));
            $user->setPlainPassword($request->request->get('password'));
            $user->setLocked(0); // don't lock the user
            $user->setEnabled(1);
            $userManager->updateUser($user);
            return $user;
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
     *  description="post a User instance",
     *  output = "DB\UserBundle\Entity\User",
     *  statusCodes = {
     *     200 = "Returned when the request success",
     *     404 = "Returned when the account not found",
     *   },
     *  parameters={
     *      {"name"="username", "dataType"="array", "required"=true, "description"="customer's email"},
     *      {"name"="password", "dataType"="array", "required"=true, "description"="login password"}
     *  }
     * )
     * 
     * Post action
     * @param Request $request
     * @return View|array
     * 
     */
    public function postLoginAction(Request $request) {
        try {
            $userManager = $this->get('fos_user.user_manager');

            if (!$user = $userManager->findUserByUsernameOrEmail($request->request->get('username')))
                throw new \Exception('username/email not found');

            if ($this->verification($user, $request->request->get('password')))
                return $user;
            else
                throw new \Exception('login infos incorrect');
        } catch (\Doctrine\ORM\ORMException $e) {
            $loLogger = $this->get('logger');
            //$loLogger->critical($e->getMessage());
            $loLogger->critical("Échec : " . $e->getMessage());
            throw $e;
        }
    }

}
