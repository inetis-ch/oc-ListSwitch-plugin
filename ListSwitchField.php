<?php namespace Inetis\ListSwitch;

use Backend\Classes\ListColumn;
use Lang;
use October\Rain\Database\Model;

class ListSwitchField
{
    /**
     * Default field configuration
     * all these params can be overrided by column config
     * @var array
     */
    private static $defaultFieldConfig = [
        'icon'       => true,
        'titleTrue'  => 'inetis.listswitch::lang.inetis.listswitch.title_true',
        'titleFalse' => 'inetis.listswitch::lang.inetis.listswitch.title_false',
        'textTrue'   => 'inetis.listswitch::lang.inetis.listswitch.text_true',
        'textFalse'  => 'inetis.listswitch::lang.inetis.listswitch.text_false',
        'request'    => 'onSwitchInetisListField'
    ];

    private $name;
    private $value;
    private $column;
    private $record;
    private $config;

    /**
     * @param            $value
     * @param ListColumn $column
     * @param Model      $record
     *
     * @return string HTML
     */
    public static function render($value, ListColumn $column, $record)
    {
        $field = new self($value, $column, $record);
        $config = $field->getConfig();

        return '
<a href="javascript:;"
    data-request="' . $config['request'] . '"
    data-request-data="' . $field->getRequestData() . '"
    data-stripe-load-indicator
    title="' . $field->getButtonTitle() . '">
    ' . $field->getButtonValue() . '
</a>
';
    }

    /**
     * ListSwitchField constructor.
     *
     * @param            $value
     * @param ListColumn $column
     * @param Model      $record
     */
    public function __construct($value, ListColumn $column, $record)
    {
        $this->name = $column->columnName;
        $this->value = $value;
        $this->column = $column;
        $this->record = $record;

        $this->config = array_merge(self::$defaultFieldConfig, $column->config);
    }

    /**
     * @param $config
     *
     * @return mixed
     */
    private function getConfig($config = null)
    {
        return data_get($this->config, $config);
    }

    /**
     * Return data-request-data params for the switch button
     *
     * @return string
     */
    public function getRequestData()
    {
        $modelClass = str_replace('\\', '\\\\', get_class($this->record));

        $data = [
            "id: {$this->record->{$this->record->getKeyName()}}",
            "field: '$this->name'",
            "model: '$modelClass'"
        ];

        if (post('page')) {
            $data[] = "page: " . post('page');
        }

        return implode(', ', $data);
    }

    /**
     * Return button text or icon
     *
     * @return string
     */
    public function getButtonValue()
    {
        if (!$this->getConfig('icon')) {
            return Lang::get($this->getConfig($this->value ? 'textTrue' : 'textFalse'));
        }

        if ($this->value) {
            return '<i class="oc-icon-check"></i>';
        }

        return '<i class="oc-icon-times"></i>';
    }

    /**
     * Return button hover title
     *
     * @return string
     */
    public function getButtonTitle()
    {
        return Lang::get($this->getConfig($this->value ? 'titleTrue' : 'titleFalse'));
    }
}
