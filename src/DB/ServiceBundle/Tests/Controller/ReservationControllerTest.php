<?php

namespace DB\ServiceBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

//require_once(__DIR__ . "/../../../../../app/AppKernel.php");

class ReservationControllerTest extends WebTestCase {

    private $container;
    private $em;

    public function setUp() {
        self::bootKernel();

        $this->container = self::$kernel->getContainer();
        $this->em = $this->container->get('doctrine')->getManager('service');
    }

    public function testSecurity() {
        $client = static::createClient();
        $client->request('GET', $client->getContainer()->get('router')->generate('get_reservations'), array('page' => 1, 'per_page' => 11));
        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testToken() {
        $client = static::createClient();

        $client->request('GET', $client->getContainer()->get('router')->generate('fos_oauth_server_token'), array('grant_type' => "client_credentials",
            'client_id' => $this->container->getParameter('oauth2_client_id'),
            'client_secret' => $this->container->getParameter('oauth2_client_secret')));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $tmp = json_decode($client->getResponse()->getContent(), true);

        return $tmp['access_token'];
    }

    /**
     * @depends testToken
     */
    public function testReservations($token) {
        $client = static::createClient();

        $crawler = $client->request('GET', $client->getContainer()->get('router')->generate('get_reservations'), array('page' => 1, 'per_page' => 11, 'access_token' => $token));
        if ($client->getResponse()->getStatusCode() == 200)
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
        else
            $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * @depends testToken
     */
    public function testPostReservation($token) {
        $client = static::createClient();
        $rand = rand();
        $reference = "test/" . strtoupper(substr(md5($rand), 1, 3)) . sprintf('%05X', $rand) . "/" . chr(date('y') + 60) . chr(date('n') + 71);
        $participant = array('civilite' => "Mr", 'type' => "adult", 'prenom' => "Johann", 'nom' => "MARIE-REINE",
            'email' => "informatique@cap-adrenaline.com", 'ddn' => "1988-04-01", 'taille' => 183, 'poids' => 115, 'telephone' => 0155289631);
        $data = array('web_id' => '0', 'eta_id' => '0', 'prenom_acheteur' => "johann", 'nom_acheteur' => "MARIE-REINE", 'id_offre' => '0', 'id_formule' => '0',
            'reference' => $reference, 'eta_name' => "test-presta", 'name' => "resa-test", "email_acheteur" => "informatique@cap-adrenaline.com",
            'a_paye' => '0', 'prix_formule' => 100, 'date_commande' => date("Y-m-d H:i"), 'date_debut_activite' => date("Y-m-d"),
            'creneau' => 'maintenant', 'participants' => array($participant), 'formule' => array('formule_nom' => "resa-test"));
        $crawler = $client->request('POST', $client->getContainer()->get('router')->generate('post_reservation', array('access_token' => $token)), $data);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $tmp = json_decode($client->getResponse()->getContent(), true);
        if (200 === $client->getResponse()->getStatusCode())
            return $tmp['reservation']['id'];
    }

    /**
     * @depends testToken
     * @depends testPostReservation
     */
    public function testReservation($token, $id) {
        $client = static::createClient();

        $crawler = $client->request('GET', $client->getContainer()->get('router')->generate('get_reservation', array('id' => $id)), array('access_token' => $token));
        if ($client->getResponse()->getStatusCode() == 200)
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
        else
            $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * @depends testToken
     * @depends testPostReservation
     */
    public function testPutReservation($token, $id) {
        $client = static::createClient();

        $crawler = $client->request('PUT', $client->getContainer()->get('router')->generate('put_reservation', array('id' => $id, 'access_token' => $token)), array('status' => 3));
        if ($client->getResponse()->getStatusCode() == 200)
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @depends testPostReservation
     */
    public function testDelete($id) {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->delete('DBServiceBundle:Reservation', 'r')
                ->where('r.id = :reservation')
                ->setParameter('reservation', $id)
                ->getQuery();
        $this->assertEquals(1, $query->execute());
    }

}
