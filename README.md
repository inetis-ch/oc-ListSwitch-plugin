# ListSwitch
This OctoberCMS plugin add the possibility to create buttons in backend lists that toggle a model property.
That allow you to create a publish / unpublish button on the lists backend (see bellow for demo).

## Setup
For add this feature to your list you just need to edit the `columns.yaml` field of your model.
```yaml
your_field:
    label: 'Your Label'
    type: inetis-list-switch # define the type "inetis-list-switch" for create a button

    icon: true # If true replace Yes/No text by ✓/✗ icons (default: true)

    # With the two following params, you can override 
    # the default title displayed on the button hover 
    # You can use translation strings
    titleTrue: 'Unpublish item' # by default : Click to switch to No
    titleFalse: 'Publish item' # by default : Click to switch to Yes
    
    # For override the text of the button
    # the default title displayed on the button hover
    # You can use translation strings
    textTrue: 'Published' # by default : Yes
    textFalse: 'Unpublished' # by default : No
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
