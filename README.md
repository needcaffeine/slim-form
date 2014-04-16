Slim Forms
===

This library brings together the Doctrine Annotation library to turn a Doctrine entity into an HTML form by way of Twig view helpers.

Setup
===
In your bootstrap file, configure the Doctrine entity manager and Twig View class provided by Slim, then invoke the SlimForm bootstrap's setup functions:

`\SlimForm\Bootstrap::getInstance()
     ->setAnnotationReader($annotationReader)
     ->setView($view);`


This will add the necessary template directory and twig extensions to the view object and set up the appropriate annotation readers for Doctrine.
