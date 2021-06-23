<?php


namespace App\Helper\View;


class Ajax extends Field
{
    /**
     * @var string $formId
     */
    public string $formId;

    /**
     * @var array $additional
     */
    public array $additional = [];

    /**
     * @var string|null $done
     * @example 'function(response){do something..}' or 'response => {do something}'
     * null for auto response
     */
    public ?string $done;

    /**
     * @var string $view
     */
    public static string $view = 'assets.field.ajax';

    /**
     * Ajax constructor.
     * @param string $formId
     * @param array $additional
     * @param string|null $done
     */
    public function __construct(string $formId, array $additional = [], ?string $done = null)
    {
        parent::__construct([]);
        $this->formId = $formId;
        $this->additional = $additional;
        $this->done = $done;
    }

    /**
     * @return string
     */
    public function getFormId(): string
    {
        return $this->formId;
    }

    /**
     * @param string $formId
     */
    public function setFormId(string $formId): void
    {
        $this->formId = $formId;
    }

    /**
     * @return array
     */
    public function getAdditional(): array
    {
        return $this->additional;
    }

    /**
     * @param array $additional
     */
    public function setAdditional(array $additional): void
    {
        $this->additional = $additional;
    }

    /**
     * @return string|null
     */
    public function getDone(): ?string
    {
        return $this->done;
    }

    /**
     * @param string|null $done
     */
    public function setDone(?string $done): void
    {
        $this->done = $done;
    }

    public function render(): string
    {
        return view(static::$view, ['ajax' => $this]);
    }
}