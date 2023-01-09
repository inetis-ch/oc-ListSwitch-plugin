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
            'homepage'    => 'https://github.com/inetis-ch/oc-ListSwitch',
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
            'inetis-list-switch' => [ListSwitchField::class, 'render'],
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
            /** @var \Backend\Widgets\Lists $widget */
            /** @var \Backend\Classes\ListColumn $listColumn */
            foreach ($widget->getColumns() as $name => $listColumn) {
                if (data_get($listColumn, 'config.type') !== 'inetis-list-switch') {
                    continue;
                }

                $widget->addColumns([
                    $name => array_merge($listColumn->config, [
                        'clickable' => false,
                    ]),
                ]);
            }
        });

        /**
         * Switch a boolean value of a model field
         * @return void
         */
        Controller::extend(function ($controller) {
            /** @var Controller $controller */
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
