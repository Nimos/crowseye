Crow's Eye
==========

Crow's Eye is a tool designed to give a group of EVE Online players the ability to see a list of wormholes their friends scanned down and to add their own finds to said list.  
Unlike most existing tools of that sort, it is designed for daytripping groups, so its focus will be on listing holes in a small area of k-space, not to map chains in jspace. Furthermore, its UI is designed to be as simple as possible, so adding a wormhole with all the needed information can be done in just a few clicks.

Oh, and this code wasn't actually supposed to go to github. That means it's pretty horrible at a lot of places. *Especially* everything database related.


Installation
============

To run Crow's Eye, you will need a webserver that supports PHP, and configure it to relay all requests except those going to /static/* to be processed by index.php. For the two most popular (I think?) webservers, Apache and Nginx, here's a sample config:

Nginx
-----

In your server directive, add the following lines:

```
location /static/ {
}
location / {
   rewrite .* index.php last
}
```

Apache
------

In the project's root directory, create a file called .htaccess with the following contents:

```
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_URI} !^/static/*
RewriteRule ^.*$ index.php
```
