# Magento 2 hints module
This module adds layout handles and template hints to generated page output as HTML comments.

It can be used by frontend developers to quickly spot the containers, blocks or UI elements that are used to compose
the current viewed page.
   
## Installation

### Install module
You can install Magento 2 hints module through [composer](http://getcomposer.org/download/).

First add the repository accessing through **HTTP** protocol:
 
    php composer.phar config repositories.foo vcs https://github.com/aleron75/mage2hints.git
    
or accessing through **SSH** protocol:
    
    php composer.phar config repositories.foo vcs git@github.com:aleron75/mage2hints.git

Then add the dependency: 
 
    php composer.phar require aleron75/mage2hints:~1.0

    
Alternatively you can manually add the dependency in your `composer.json`.

Accessing through **HTTPS** protocol:
 
    {
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/aleron75/mage2hints.git"
            }
        ],
        "require": {
            "aleron75/mage2hints": "~1.0"
        }
    } 

Accessing through **SSH** protocol:
 
    {
        "repositories": [
            {
                "type": "vcs",
                "url": "git@github.com:aleron75/mage2hints.git"
            }
        ],
        "require": {
            "aleron75/mage2hints": "~1.0"
        }
    } 
    
Once you have added the module dependency, run the following command from your project root:
    
    composer update
    
### Enable module
After the `composer update` finishes updating dependencies, run the following commands from your project root:
 
    bin/magento module:enable Aleron75_Mage2Hints
    bin/magento setup:upgrade
    
If you are in production mode, you also need to generate DI configuration and all non-existing interceptors and 
factories through the following command:
  
    bin/magento setup:di:compile

## Usage
To add layout handles and template hints to page as HTML comments simply add an `html` parameter to the URL like shown 
below:

    http://magento2.local/?hints=true
    
Then you can inspect your HTML source code and expect seeing something like this:
    
    <body ...>
    <!-- [LAYOUT_HANDLES] default - cms_index_index - cms_page_view - cms_index_index_id_home [/LAYOUT_HANDLES] -->
    <!-- [CONTAINER  name="root"] -->
    <!-- [CONTAINER  name="after.body.start" alias="after.body.start" parent_name="root"] -->
    <!-- [BLOCK  name="googleoptimizer.experiment.script" alias="googleoptimizer.experiment.script" parent_name="after.body.start" type="Magento\GoogleOptimizer\Block\Code\Page" template_path=""] -->
    ...    
    <!-- [/CONTAINER name="root"] -->
    </body>