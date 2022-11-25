# Deploiement en ligne sur Heroku de votre application Symfony 4/5/6

Installer le [client Heroku](https://devcenter.heroku.com/articles/heroku-cli#install-the-heroku-cli) sur votre machine.

Créer une application Heroku à partir de votre [client Heroku](https://devcenter.heroku.com/articles/heroku-cli#install-the-heroku-cli).

Ouvrez un Terminal et tapez la commande create
```
heroku create
Creating app... done, stack is heroku-18
https://floating-badlands-41656.herokuapp.com/ | https://git.heroku.com/floating-badlands-41656.git
```

Creez un fichier **Procfile** qui permettra à Heroku de savoir quelle commande utiliser pour lancer le server web (ici Apache par défaut)

```
echo 'web: heroku-php-apache2 public/' > Procfile

git add Procfile

git commit -m "Heroku Procfile"
```
*Note : Attention à bien écrire Procfile avec un P majuscule.*

A présent avant de deployer votre application, il faut basculer l'environnement de devellopement de Symfony en **prod** (production), par défaut il est en **dev** (développement).
Pour cela il faut changer la variable d'environnement APP_ENV située dans le .env en **prod**, ou utiliser la commande Heroku suivante : 

```
heroku config:set APP_ENV=prod
Setting config vars and restarting floating-badlands-41656... done
APP_ENV: prod

```
Vous pouvez maintenant deployer votre application

```
git push heroku master
…
remote: Compressing source files... done.
remote: Building source:
remote:
remote: -----> PHP app detected
remote: -----> Bootstrapping...
remote: -----> Installing system packages...
…
remote: -----> Installing dependencies...
remote:        Composer version 1.8.6
remote:        Loading composer repositories with package information
remote:        Installing dependencies from lock file
…
remote: -----> Preparing runtime environment...
remote: -----> Checking for additional extensions to install...
remote: -----> Discovering process types
remote:        Procfile declares types -> web
remote:
remote: -----> Compressing... done, 87.3MB
remote: -----> Launching...
remote:        Released v4
remote:        https://floating-badlands-41656.herokuapp.com/ deployed to Herok
remote:
remote: Verifying deploy... done.
To https://git.heroku.com/floating-badlands-41656.git
 * [new branch]      master -> master
```

Voilà, c'est terminé ! Vous pouvez maintenant l'ouvrir sur votre navigateur avec la commande suivante 

```
heroku open
Opening floating-badlands-41656... done
```
# Integrer une base de donnée à votre application graçe à ClearDB MySQL

Maintenant que votre application est en ligne, vous pouvez si vous en avez la nécessité lui integrer une base de donnée.
Vous pouvez utiliser l'addon [ClearDB MySQL](https://devcenter.heroku.com/articles/cleardb)

Créez une base de donnée :

```
heroku addons:create cleardb:ignite
-----> Adding cleardb to sharp-mountain-4005... done, v18 (free)
```
Pour ajouter cette base de donnée que vous venez de créer à notre application suivez les étapes suivante : <br>

Recuperez l'URL `CLEARDB_DATABASE_URL` de votre base de donnée avec la commande suivante

```
heroku config
GITHUB_USERNAME: joesmith
OTHER_VAR:    production
CLEARDB_DATABASE_URL : mysql://adffdadf2341:adf4234@us-cdbr-east.cleardb.com/heroku_db?reconnect=true

```

Copiez la valeur de `CLEARDB_DATABASE_URL` et ajoutez la à une variable `DATABASE_URL` de votre application 

```
heroku config:set DATABASE_URL='mysql://adffdadf2341:adf4234@us-cdbr-east.cleardb.com/heroku_db?reconnect=true'
Adding config vars:
DATABASE_URL => mysql2://adffd...b?reconnect=true
Restarting app... done, v61.`
```

C'est terminé ! 
