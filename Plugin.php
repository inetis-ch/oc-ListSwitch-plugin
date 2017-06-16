<?php namespace Inetis\ListSwitch;

use Backend\Classes\Controller;
use Event;
use Flash;
use System\Classes\PluginBase;

/**
 * listSwitch Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'inetis.listswitch::lang.inetis.plugin.name',
            'description' => 'inetis.listswitch::lang.inetis.plugin.description',
            'author'      => 'inetis',
            'icon'        => 'icon-toggle-on',
            'homepage'    => 'https://github.com/inetis-ch/oc-ListSwitch'
        ];
    }

    /**
     * Register custom list type
     *
     * @return array
     */
    public function registerListColumnTypes()
    {
        return [
            'inetis-list-switch' => [ListSwitchField::class, 'render']
        ];
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        Event::listen('backend.list.extendColumns', function ($widget) {
            foreach ($widget->config->columns as $name => $config) {
                if (empty($config['type']) || $config['type'] !== 'inetis-list-switch') {
                    continue;
                }

                // Store field config here, before that unofficial fields was removed
                ListSwitchField::storeFieldConfig($name, $config);

                $column = [
                    'clickable' => false,
                    'type'      => 'inetis-list-switch'
                ];

                if (isset($config['label'])) {
                    $column['label'] = $config['label'];
                }

                // Set this column not clickable
                // if other column with same field name exists configs are merged
                $widget->addColumns([
                    $name => $column
                ]);
            }
        });

        /**
         * Switch a boolean value of a model field
         * @return void
         */
        Controller::extend(function ($controller) {

            $controller->addDynamicMethod('index_onSwitchInetisListField', function () use ($controller) {

                $field = post('field');
                $id = post('id');
                $modelClass = post('model');

                if (empty($field) || empty($id) || empty($modelClass)) {
                    Flash::error('Following parameters are required : id, field, model');

                    return;
                }

                $model = new $modelClass;
                $item = $model::find($id);
                $item->{$field} = !$item->{$field};

                $item->save();

                return $controller->listRefresh($controller->primaryDefinition);
            });
        });
    }
}
