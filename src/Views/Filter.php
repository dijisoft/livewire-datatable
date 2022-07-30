<?php

namespace Dijisoft\LivewireDatatable\Views;

/**
 * Class Filter.
 */
class Filter
{
    public const TYPE_SELECT = 'select';
    public const TYPE_DATERANGE = 'daterange';
    public const TYPE_CHECKBOX = 'checkbox';
    public const TYPE_BTN = 'btn';
    public const TYPE_DROPDOWN = 'dropdown';

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $type;

     /**
     * @var string
     */
    public string $btnLayout = 'default';

    /**
     * @var array
     */
    public array $options = [];

    /**
     * @var callable
     */
    public $callback;

    /**
     * Filter constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     *
     * @return Filter
     */
    public static function make(string $name): Filter
    {
        return new static($name);
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function select(array $options = []): Filter
    {
        $this->type = self::TYPE_SELECT;

        $this->options = $options;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function daterange(array $options = []): Filter
    {
        $this->type = self::TYPE_DATERANGE;

        $this->options = $options;

        return $this;
    }

     /**
     * @param array $options
     *
     * @return $this
     */
    public function btn(array $options = [], $btnLayout = 'default'): Filter
    {
        $this->type = self::TYPE_BTN;
        $this->btnLayout = $btnLayout;

        $this->options = $options;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function dropdown(array $options = []): Filter
    {
        $this->type = self::TYPE_DROPDOWN;

        $this->options = $options;

        return $this;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * @return bool
     */
    public function isSelect(): bool
    {
        return $this->type === self::TYPE_SELECT;
    }

    /**
     * @return bool
     */
    public function isDaterange(): bool
    {
        return $this->type === self::TYPE_DATERANGE;
    }

    /**
     * @return bool
     */
    public function isDropdown(): bool
    {
        return $this->type === self::TYPE_DROPDOWN;
    }

     /**
     * @return bool
     */
    public function isBtn(): bool
    {
        return $this->type === self::TYPE_BTN;
    }

    /**
     * @return self
     */
    public function callback(callable $callback): ?self
    {
        $this->callback = $callback;

        return $this;
    }

     /**
     * @return bool
     */
    public function hasCallback(): bool
    {
        return $this->callback !== null;
    }

    /**
     * @return callable|null
     */
    public function getCallback(): ?callable
    {
        return $this->callback;
    }
}
