**Rapport projet jobsimport**

- Lien vers le projet GitHub : [GitHub - choosemycompany/jobsimport](https://github.com/choosemycompany/jobsimport)
- Projet d'origine à retrouver : [GitHub - locfab/jobsimport](https://github.com/locfab/jobsimport)
- Liste les commits : [GitHub - locfab/jobsimport/commits/main](https://github.com/locfab/jobsimport/commits/main)

**Objectif du projet:**

Mise à jour du code pour importer le flux "jobteaser.json" du second partenaire.

Refactorisation pour anticiper l'ajout de futurs flux, la maintenance et l'évolutivité.

__Les "commandes utiles" n'ont pas changé, et la BDD non plus.__

---

- `./init.sh` pour initialiser et lancer le projet
- `./run-import.sh` pour lancer l'import
- `./clean.sh` pour arrêter et nettoyer le projet
- http://localhost:8000/ (`root` / `root`): interface phpMyAdmin pour visualiser le contenu de la base de donnée
  
__Nouvelles commandes.__

- `docker-compose run tests phpunit /var/app/tests` pour lancer les tests.


**Choix de développement:**

- Récupérer les fichiers du dossier "ressource" au lieu de se limiter au fichier "regionJob.xml", grâce à la fonction `getFilesFilterByExtensions` ajoutée dans `utils.php`.
- La gestion de la connexion se fait dans la classe `"DatabaseConnection"`, qui permet de centraliser la gestion de la connexion et est utilisée dans `"JobImporter"` et `"JobLister"`.
- Les fichiers `"config.php"`, `".sh"`, `".sql"`, `".yml"` n'ont pas été modifiés.
- Ajout de tests avec phpunit

l'arborescence du projet a été modifiée, surtout au niveau de src:

- resources
    - regionsjob2.xml
    - jobteaser.json
    - regionsjob.xml
    - indeed.json
    - jobteaser2.json
- tests
    - UtilsTest.php
    - JobRepositoryTest.php
- src
    - database
        - DatabaseConnection.php
        - JobRepository.php
    - lists
        - JobsLister.php
    - importers
        - ImportJobteaser
            - ImportJobteaser.php
        - ImportRegionsJob
            - ImportRegionsJob.php
        - FileImporter.php
    - config
        - config.php
    - utils
        - utils.php
    - JobsImporter.php
- .DS_Store
- phpunit.xml
- Dockerfile.test
- run-import.sh
- Dockerfile
- init.sh
- .git
- README.md
- init.sql
- clean.sh
- docker-compose.yml


**JobImporter:**

- Beaucoup de commentaires (dans le code) sont présents pour expliquer la démarche. Partant du principe que la gestion de l'importation pourrait dépendre des noms que nous attribuerions aux fichiers (XML, JSON), j'ai donc pris l'initiative d'ajouter des fichiers supplémentaires pour vérifier que tout fonctionne correctement.

**Règles de fichiers:**

- La propriété `"fileRules"` est un tableau associatif qui lie des motifs de noms de fichiers à des classes d'importation. Ces règles définissent comment les données doivent être importées à partir de fichiers spécifiques en se basant sur leurs noms et emplacements respectifs. Par exemple, une règle telle que `"*/jobteaser.jso*" => ImportJobteaser::class` indique que les fichiers correspondant à ce motif devraient être traités en utilisant la classe d'importation `"ImportJobteaser"`.

**Insertion de données d'emploi (factorisation):**

- La méthode `"insertJobData"` est utilisée pour insérer des données d'emploi dans la table de base de données 'job'. Elle prend divers paramètres liés à l'emploi (par exemple, référence, titre, description) et construit une requête SQL pour insérer les données dans la base de données.

**Importation des emplois:**

- La méthode `"importJobs"` est le point d'entrée principal pour l'importation de données d'emploi. Elle commence par supprimer les données d'emploi existantes dans la base de données, puis itère à travers la liste des fichiers. Pour chaque fichier, elle détermine la fonction d'importation appropriée en utilisant la méthode `"getImportClass"` et appelle la méthode correspondante. La méthode garde une trace du nombre total d'offres d'emploi importées et renvoie ce décompte.

**Méthodes d'importation:**

Ce code utilise une classe `"JobsImporter"` pour importer des données à partir de différents fichiers en utilisant des classes spécifiques pour chaque type de fichier, comme `"ImportJobteaser"` et `"ImportRegionsJob"`. La propriété `"fileRules"` associe les fichiers aux classes d'importation correspondantes. La méthode `"importJobs()"` itère sur les fichiers, les associe aux classes, importe les données, puis supprime les anciennes données si de nouvelles données ont été importées avec succès. Le code est modulaire, extensible et suit des principes de POO pour une gestion flexible des sources de données.

**Suppression des précédents "jobs"**

J'ai choisi d'abord importer les nouveaux emplois avant de supprimer les emplois existants. Cela me semblait être la meilleure approche pour éviter de perdre des données au cas où une opération d'importation pourrait échouer.

**Principales voies d'amélioration:**

- La principale innovation de ce code réside dans l'introduction de la fonction abstraite `"FileImporter"`, qui permet d'appeler des méthodes d'importation différentes en fonction du type de fichier. Cette abstraction offre une flexibilité considérable pour gérer divers formats de fichiers de manière modulaire.

- J'ai mis en place `"JobRepository"` et `"DataConnection"`, ce qui a permis de factoriser la gestion des données et d'appliquer le principe de l'injection de dépendance. En regroupant ces fonctionnalités dans des composants distincts, nous avons rendu le code plus modulaire et plus facile à maintenir. L'injection de dépendance nous permet de fournir des dépendances externes de manière contrôlée, renforçant la flexibilité et la testabilité du système.

- J'ai cherché à maximiser l'utilisation de `try catch` et d'exceptions pour la gestion des erreurs, que ce soit dans le code, pour la récupération de base de données, ou la récupération des fichiers, afin de garantir une meilleure robustesse du système. Cependant, il est possible qu'il subsiste quelques endroits où cette approche pourrait être améliorée. Cela permettrait de garantir une meilleure fiabilité du système en cas d'erreurs inattendues.

- J'ai pris des mesures pour prévenir les attaques par injection SQL en utilisant des requêtes préparées ou en liant les paramètres aux requêtes SQL au lieu d'interpoler directement les valeurs dans les requêtes. Cette approche renforce la sécurité de l'application en évitant l'exécution de code SQL malveillant via les entrées utilisateur et en assurant une manipulation sécurisée des données dans la base de données. Tout cela a été mis en place dans le repository `"JobRepository"`, garantissant que la gestion sécurisée des données est centralisée à cet endroit, simplifiant ainsi la maintenance et renforçant la sécurité globale du système.

- Dans mon fichier `"ImportJobteaser.php"`, j'ai opté pour la méthode la plus simple pour lire un fichier JSON : charger tout le contenu en mémoire avec `file_get_contents` et le décoder avec `json_decode`. Cela fonctionne bien pour les fichiers JSON de taille modérée et moyenne. Cependant, si nous prévoyons de traiter de gros fichiers JSON, il serait sage d'envisager une approche plus efficace. Une solution recommandée consiste à utiliser un décodeur JSON basé sur le modèle SAX (Simple API for XML). Avec cette approche, le fichier JSON est lu ligne par ligne, émettant des événements à mesure que le parseur parcourt le fichier, sans charger tout le JSON en mémoire. Cela réduit la consommation de mémoire, ce qui est essentiel pour les fichiers de grande taille.

- Pour améliorer, maintenir et garantir la fiabilité du code, l'intégration de __tests unitaires__ avec __PHPUnit__ est une une démarche judicieuse, c'est pourquoi j'ai mis en place quelques tests. Cependant il en faudrait __davantage__. Le travail déjà effectué, notamment en termes de factorisation, d'injection de dépendances, et de mise en place d'interfaces, simplifierait considérablement l'implémentation des tests, renforçant ainsi la qualité globale du système.
