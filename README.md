# About
OctoberCMS plugin to add a toggleable switch as a column type to the backend.

## Usage

To add a switch column to a list; set the `type` of the column to `inetis-list-switch`. 

Example:
```yaml
your_column:
    label: 'Your Label'
    # Define the type as "inetis-list-switch" to enable this functionality
    type: inetis-list-switch
    
    # Whether to use ✓/✗ icons to replace the Yes/No text (default: true)
    icon: true
   
    # Overrides the title displayed on hover over the column
    titleTrue: 'Unpublish item' # (default: 'inetis.listswitch::lang.inetis.listswitch.title_true')
    titleFalse: 'Publish item' # (default: 'inetis.listswitch::lang.inetis.listswitch.title_false')
    
    # Overrides the text displayed on the button
    textTrue: 'Published' # (default: 'inetis.listswitch::lang.inetis.listswitch.text_true')
    textFalse: 'Unpublished' #(default: 'inetis.listswitch::lang.inetis.listswitch.text_false')
```


## Demo
*Default behavior*  
![switch-icon](https://cloud.githubusercontent.com/assets/12028540/23846134/2e0245c8-07cc-11e7-82a6-c5c0c940b453.gif)

*Behavior with ` icon: false`*  
![switch-text](https://cloud.githubusercontent.com/assets/12028540/23846200/a88ac8c4-07cc-11e7-89fd-ccb61a701b82.gif)

*With custom titles*  
![switch-icon-custom-titles](https://cloud.githubusercontent.com/assets/12028540/23846367/69e1f89e-07cd-11e7-9f8b-943aa9301464.gif)

## Author
inetis is a webdesign agency in Vufflens-la-Ville, Switzerland. We love coding and creating powerful apps and sites  [see our website](https://inetis.ch).
