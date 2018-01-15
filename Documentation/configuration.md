Configuration
========

En dehors des configuration requit pour un projet symfony 2.7, il faudra rajouter des paramétres pour le bon fonctionnement du projet.

Dans app/config/config.yml, dans la section parameters, vous trouverez des parametres pour l'envois des emails et des sms.

    *   configuration des SMS, (la configuration à été faite par rapport à OVH pour l'API "Email to SMS")
        -   SMSaccount: Compte SMS à utiliser
        -   SMSlogin:   Utilisateur SMS à utiliser sur le compte associé
        -   SMSpwd:     Mot de passe de l'utilisateur
        -   SMSfrom:    Votre numéro d'expéditeur à utiliser, parmi les numéros déclarés sur votre compte SMS (format international +33...)

    *   configuration du services d'envoi des Email gérer par swiftmailer.
        -   Mailtransport:  La méthode de transport exacte à utiliser pour envoyer des emails
        -   Mailencryption: Le mode d'encryption lors de l'utilisation de smtp en tant que mode de transport
        -   Mailauth_mode:  Le mode d'authentification lors de l'utilisation de smtp en tant que mode de transport
        -   Mailhost:       Le serveur auquel se connecter lors de l'utilisation de smtp en tant que mode de transport.
        -   Mailport:       Le numéro du port lors de l'utilisation de smtp en tant que mode de transpor
        -   MailFrom:       adresse mail
        -   MailFromPSWD:   mot de passe mail


Les paramétres sont déjàs définit mais penser à les changer si elles sont obselete (Donnée datant de 2015).

Ce services devra être associer à un planificateur de tache ou CRON, pour la relance des mails, ce script devra être.

    <?php
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://yyyyy/xxxxxx/revival");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
        curl_exec($ch);
        curl_close($ch);

Y sera le nom de l'hote et xx le routing définit dans app/config/routing.yml pour "db_service" au niveau du prefix.