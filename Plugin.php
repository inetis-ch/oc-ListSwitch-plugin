<?php namespace Inetis\ListSwitch;

use ApplicationException;
use Backend\Behaviors\RelationController;
use Backend\Classes\Controller;
use Event;
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
         */
        Controller::extend(function ($controller) {
            /** @var Controller $controller */
            $controller->addDynamicMethod('index_onSwitchInetisListField', function () use ($controller) {
                $this->processInvertField(post('model'), post('id'), post('field'));

                return $controller->listRefresh($controller->primaryDefinition);
            });

            $controller->addDynamicMethod('onSwitchInetisListField', function ($recordId) use ($controller) {
                $this->processInvertField(post('model'), post('id'), post('field'));

                if ($controller->isClassExtendedWith(RelationController::class)) {
                    $controller->initForm($controller->formFindModelObject($recordId));

                    return $controller->relationRefresh(post('_relation_field'));
                }
            });
        });
    }

    /**
     * Invert a model field's value
     *
     * @param string $modelClass
     * @param int $id
     * @param string $fieldName
     */
    private function processInvertField($modelClass, $id, $fieldName)
    {
        if (empty($fieldName) || empty($id) || empty($modelClass)) {
            throw new ApplicationException("Following parameters are required : id, field, model");
        }

        $item = $modelClass::findOrFail($id);
        $item->{$fieldName} = !$item->{$fieldName};
        $item->save();
    }
}
