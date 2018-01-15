<?php
 //mail subjets (define by default in french)
    # mail subjet for customer when the customer send a booking
    $container->setParameter('achat_clie', "Confirmation de votre commande - ");
    # mail subjet for customer when the customer send a booking
    $container->setParameter('revival_achat_clie', "Suivi de votre commande : relance de notre partenaire - ");
    # mail subjet for customer when the customer send a booking
    $container->setParameter('last_achat_clie', "Suivi de votre commande : dernière relance de notre partenaire - ");

    # mail subjet for recipent when the recipent send a booking
    $container->setParameter('achat_part', "Nouvelle demande de réservation - ");
    # revival mail subjet for recipent when the recipent send a booking
    $container->setParameter('revival_achat_part', "Relance : demande de réservation - ");
    # the last revival mail subjet for recipent when the recipent send a booking
    $container->setParameter('last_achat_part', "Dernière relance : demande de réservation - ");

    # mail subjet when the the recipient don"t answer at the customer booking
    # the subjet is same for Customer and recipient
    $container->setParameter('cancel_achat_auto', "Demande de réservation annulée - ");
    # mail subjet when the recipient want to cancel bookink
    # the subjet is same for Customer and recipient
    $container->setParameter('cancel_achat_manual', "Annulation de la réservation - ");

    # mail subjet for customer when the recipent confirm a booking
    # the subjet is same for Customer and recipient
    $container->setParameter('confirm_booking', "Confirmation de réservation - ");

    # mail subjet for customer when the recipent/customer want to contact customer/recipent
    $container->setParameter('contact_clie', "Prise de contact avec notre partenaire - ");
    # mail subjet for recipent when the recipent/customer want to contact customer/recipent
    $container->setParameter('revival_contact_clie', "Relance : prise de contact avec notre partenaire - ");
    # mail subjet for recipent when the recipent/customer want to contact customer/recipent
    $container->setParameter('last_contact_clie', "Dernière relance : prise de contact avec notre partenaire - ");
    # mail subjet for recipent when the recipent/customer want to contact customer/recipent
    $container->setParameter('contact_time_clie', "Prise de contact avec notre partenaire - ");

    # mail subjet for recipent when the recipent/customer want to contact customer/recipent
    $container->setParameter('contact_part', "Prise de contact avec notre client - ");
    # mail subjet for recipent when the recipent/customer want to contact customer/recipent
    $container->setParameter('revival_contact_part', "Relance : prise de contact avec notre client - ");
    # mail subjet for recipent when the recipent/customer want to contact customer/recipent
    $container->setParameter('last_contact_part', "Dernière relance : prise de contact avec notre client - ");
    # mail subjet for recipent when the recipent/customer want to contact customer/recipent
    $container->setParameter('contact_time_part', "Prise de contact avec notre client - ");

    # mail subjet for customer when the recipent send 2 date choose for customer
    $container->setParameter('contre', "Proposition de nouvelles dates de la part de notre partenaire - ");
    $container->setParameter('revival_contre', "Rappel : proposition de nouvelles dates de la part de notre partenaire - ");
    $container->setParameter('last_contre', "Dernier rappel : proposition de nouvelles dates de la part de notre partenaire - ");