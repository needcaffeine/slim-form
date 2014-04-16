Slim Forms
===

This library brings together the Doctrine Annotation library to turn a Doctrine entity into an HTML form by way of Twig view helpers.

Setup
===

In your bootstrap file (for Slim Framework it is index.php) set the SlimForm\Reader class with an annotation reader the same as your entity manager uses. (implements \Doctrine\Common\Annotations\Reader interface)

`\SlimForm\Reader::setAnnotationReader($entityManagerAnnotationReader);`

Additionally, you will need to register filters with your existing application.

These filters are found in the following class

`\SlimForm\Twig\Extension\Filters::getInstance()->getFilters()`

Add the array of simple twig filters to an existing view extension as needed.

Finally, you will need to use \SlimForm\View as your application's view class in order to include the proper twig templates for rendering the slim form elements. This file is an extension of \Slim\View amd may be extended the same way. The only difference is that it allows for multiple directories to be used when rendering views.
