RedDigg
======

How to start:

1. clone repository
2. Setting up Permissions

*Using ACL on a system that supports chmod +a*
`
rm -rf var/cache/* var/logs/* var/sessions/*

HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo chmod -R +a "$HTTPDUSER allow delete,write,append,file_inherit,directory_inherit" var
sudo chmod -R +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" var
`

*Using ACL on a system that does not support chmod +a*

`HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX var`

3. Install components
`composer.phar install`

4. Set up database:
`
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
`

5. Start the server:
`php bin/console server:star 0.0.0.0:80`

6. Now visit:
`http://localhost:80`
