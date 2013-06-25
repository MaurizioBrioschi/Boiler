Boiler
======

Boiler is MVC php framework, useful to simply develop frontend and backend web application.

Installation
=======
Just download this .zip file anche unzip it in your root directory web site

MVC
=======
This is a simple mvc framework:
    * Model is inside "model" directory and contain all classes useful for your web site. You can find some classes documented inside.
    * "wiew" directory contains all templates pages called from controllers, present inside "controller" directory.
    * Directory "application" contains the core of the framework with all classes useful to make the framework works. (N.B. change this classes only if you are sure of what you are doing)
    * includes directory contains files for configuring your application

Using Boiler
=======
After have unziped the package in your root directory you have a mvc framework ready to create some pages.
Create a new page is pretty easy:
    * Copy and paste indexController.php in controller directory and name like the page you want call.
      es. if you want to have a page about create the controller aboutController.php and change the class name from to indexController to aboutController
      Now the url http://yoursite.com/about will call your aboutController class and the method index
    * If you want to call another method from your aboutController just insert a new method inside like for example "rock" 
      If you do this the url http://yoursite.com/about/rock will call this method.
    * Everythings came after the second slash (in this case rock) will be considered like argument of the method
      N.B. index method of every controller is the only method that can't have arguments
    * The code will be rendered in its template by the method show:
      ```php

      $this->registry->template->show 

      ```
      
      That call the template php with that name in views directory.

This is the complete code exaple:

```php
Class aboutController Extends baseController {

public function index() {
            // this is to render the index.php template inside views directory
            $this->registry->template->show('index');

}

public function rock($args=null)    {
    //$this->registry->template->args is how declare a variable inside the template
    $this->registry->template->args=$args;
    // this is to render the index.php template inside views directory
    // in the template you can use all variables in common php
    $this->registry->template->show('index'); 
}

}
```
    
Note
=======

This is my personal framework, used for years to develop web application and now is time to make it useful form everybody.
Probably is to fix, explain and to make more easy... 

License
=======

This code is under MIT license present in the root directory

