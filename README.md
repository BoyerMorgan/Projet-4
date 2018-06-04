P4_OCR : Développement d'un backend pour un client.
======
Création de la billetterie en ligne du musée du louvre à l'aide du framework Symfony.

Installation de l'application

Le guide ci-dessous part du principe du serveur web déjà configuré.

Pré-requis : MySql et PHP 7.1.*
Installation des sources et de la base de données

Se positionner dans le répertoire choisi pour installer l'application.

    Dans une console, saisir : git clone https://github.com/BoyerMorgan/Projet-4.git

    Puis : cd projet-4

    Puis : composer install

    Saisir les paramètres demandés par la console

    Si la base de donnée n'a pas été créée en amont, saisir : bin/console doctrine:database:create

    Puis : bin/console doctrine:schema:update --force

    Faire pointer le "document root" du serveur web vers le dossier "web" (P4_OCR/web).

L'application est prête à être utilisée !
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/33fcbacd344f48cda2a0d8ee1596e370)](https://www.codacy.com/app/BoyerMorgan/Projet-4?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=BoyerMorgan/Projet-4&amp;utm_campaign=Badge_Grade)

A Symfony project created on April 17, 2018, 9:16 am.
