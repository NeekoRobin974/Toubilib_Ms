# Toubilib Micro Services

Cadet Mattéo  
Dieudonné Quentin  
DWM1

## Présentation
L'application Toubilib mais divisé en micro services.

## Fonctionnalités réalisées
- 1)Affichage de la liste des praticiens
- 2)Affichage du détail d'un praticien donné
- 3)Affichage de la liste des créneaux disponibles pour une période donnée
- 4)Affichage d'un RDV donné
- 5)Créer un RDV
- 6)Annuler un RDV
- 7)Affichage de l'agenda d'un praticien
- 8)S'authentifier en tant que patient ou praticien
- 9)Lister/rechercher un praticien en fonction d’une spécialité et/ou d’une ville d’exercice
- 10)Gestion du cycle de vie des RDV, on peut changer le status du RDV (0 = à venir, 1 = honoré, 2 = non honoré)
- 12)S'inscrire en tant que patient ou praticien

## Liens utiles pour les tests

- **Accès aux bases de données** : [http://localhost:8080](http://localhost:8080)
- **Liste des praticiens** : [http://localhost:6082/praticiens](http://localhost:6080/praticiens)
- **Détail d'un praticien** : [http://localhost:6082/praticiens/4305f5e9-be5a-4ccf-8792-7e07d7017363](http://localhost:6080/praticiens/4305f5e9-be5a-4ccf-8792-7e07d7017363)
- **Liste créneaux sur une période** : [http://localhost:6082/praticiens/4305f5e9-be5a-4ccf-8792-7e07d7017363/creneaux?date_debut=2025-12-01&date_fin=2025-12-07](http://localhost:6080/praticiens/4305f5e9-be5a-4ccf-8792-7e07d7017363/creneaux?date_debut=2025-12-01&date_fin=2025-12-07)
- **Consulter un RDV** : [http://localhost:6083/rdvs/2e1a7275-2593-3c04-9a4c-4e7cbada9541](http://localhost:6080/rdvs/2e1a7275-2593-3c04-9a4c-4e7cbada9541)
- **Consulter l'agenda d'un praticien** : [http://localhost:6082/praticien/4305f5e9-be5a-4ccf-8792-7e07d7017363/agenda](http://localhost:6080/praticien/4305f5e9-be5a-4ccf-8792-7e07d7017363/agenda)
- **Consulter l'agenda d'un praticien après une date précise** : [http://localhost:6082/praticien/4305f5e9-be5a-4ccf-8792-7e07d7017363/2025-12-03/agenda](http://localhost:6080/praticien/4305f5e9-be5a-4ccf-8792-7e07d7017363/2025-12-03/agenda)
- **Consulter l'agenda d'un praticien entre deux dates** : [http://localhost:6082/praticien/4305f5e9-be5a-4ccf-8792-7e07d7017363/2025-12-01/2025-12-03/agenda](http://localhost:6080/praticien/4305f5e9-be5a-4ccf-8792-7e07d7017363/2025-12-01/2025-12-03/agenda)
- **Annuler un RDV (à faire sur Postman)** : [http://localhost:6083/rdvs/2e1a7275-2593-3c04-9a4c-4e7cbada9541/annuler](http://localhost:6080/rdvs/2e1a7275-2593-3c04-9a4c-4e7cbada9541/annuler)
- **Liste des praticiens qui ont la spécialité "pédiatrie"** : [http://localhost:6082/praticiens?specialite=pédiatrie](http://localhost:6080/praticiens?specialite=pédiatrie)
- **Liste des praticiens qui exercent à Nancy** : [http://localhost:6082/praticiens?ville=Nancy](http://localhost:6080/praticiens?ville=Nancy)

### Authentification et inscription

- **Inscription** : POST [http://localhost:6084/auth/register](http://localhost:6080/auth/signup)
    - Body (JSON) :
      ```json
      {
          "email": "test@exemple.com",
          "password": "test",
          "role": 1
      }
      ```
    - Si les infos sont correctes, on obtient un 201 Created avec le body retourné.

- **Connexion** : POST [http://localhost:6084/auth/signin](http://localhost:6080/auth/signin)
    - Body (JSON) :
      ```json
      {
          "email": "test@exemple.com",
          "password": "test",
          "role": 1
      }
      ```
    - Si les infos sont correctes, on obtient un 200 OK avec un token JWT dans le body retourné.

- **Refresh** : POST [http://localhost:6084/auth/refresh](http://localhost:6080/auth/signin)
    - Il faut d'abord récupérer le refresh token renvoyé lors de la connexion.
    - Body (JSON) : le vider.
    - Sur Postman, aller dans Authorization, choisir Bearer Token et coller le refresh token.
    - Si les infos sont correctes, on obtient un 200 OK avec le nouveau token.

### Gestion des cycles de vie des RDVs
- **Changer le statut d'un RDV (à faire sur Postman)** : POST [http://localhost:6083/rdvs/4a51b40a-4deb-36c1-86e9-0406ffdfa49d/status](http://localhost:6080/rdvs/4a51b40a-4deb-36c1-86e9-0406ffdfa49d/status)
    - Body (JSON) :
      ```json
      {
          "status": 2
      }
      ```
    - Si les infos sont correctes, on obtient un 200 OK avec un message.

## Remarques
- Les identifiants de test sont à créer via la route d'inscription.
- Les rôles : 1 = patient, 10 = praticien.
- Les identifiants UUID présents dans les liens sont des exemples présents dans la BD.