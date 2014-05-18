<?php

/**
 * Parses a file or sting an replaces placeholders.
 *
 * @author Krzysztof Sowa
 */
class simpletemplate {

    /**
     * Contains source and redered template
     *
     * @var array
     */
    protected $template = array(
        'source',
        'rendered'
    );
    /**
     * Container for parameters
     *
     * @var array
     */
    protected $params = array();


    public static function factory($position = null) {
        return new self($position);
    }

    /**
     * Loads template file if set
     *
     * @param string $position
     */
    public function __construct($position = null) {

        /* load template if requested */
        if (!is_null($position)) {
            $this->loadFromFile($position);
        }
    }

    /**
     * Loads template from file
     *
     * @param string $position
     * @return simpleTemplate
     */
    public function loadFromFile($position) {

        /* make sure file exists */
        if (!file_exists($position)) {
            return $this;
        }

        $this->template['source'] = file_get_contents($position);
        return $this;
    }
    
    public function saveToFile($position) {
        file_put_contents($position, $this->template['rendered']);
        return $this;
    }

    /**
     * Loads template form variable
     *
     * @param mixed $string
     * @return simpleTemplate
     */
    public function loadFromVariable($string) {
        $this->template['source'] = $string;
        return $this;
    }

    /**
     * Returns whether template file has been loaded
     *
     * @return  boolean
     */
    public function loaded() {
        return (bool) (isset($this->template['source']));
    }

    /**
     * Binds variables
     *
     * @param mixed $placeholder
     * @param mixed $value
     * @return simpleTemplate
     */
    public function param($placeholder, $value) {
        $this->params[strtoupper($placeholder)] = $value;
        return $this;
    }

    /**
     * Binds an array of variables
     *
     * @param array $params
     * @return simpleTemplate
     */
    public function params(array $params) {

        /* make sure $params is an array */
        if (!is_array($params) && count($params) < 1) {
            return $this;
        }

        foreach ($params as $placeholder => $value) {
            $this->param($placeholder, $value);
        }

        return $this;
    }

    /**
     * Renders the given input
     *
     * @return  simpleTemplate
     */
    public function render() {

        /* make sure template has been loaded */
        if (!$this->loaded()) {
            return $this;
        }

        /* replace placeholders if necessary */
        if (count($this->params)) {

            $this->template['rendered'] = $this->template['source'];

            foreach ($this->params as $placeholder => $value) {
                $this->template['rendered'] = preg_replace('|{' . $placeholder . '}|', $value, $this->template['rendered']);
            }
        } else {

            $this->template['rendered'] = $this->template['source'];
        }
        return $this;
    }

    /**
     * Checks whether input has been rendered
     *
     * @return  boolean
     */
    public function rendered() {
        return (bool) (isset($this->template['rendered']));
    }

    /**
     * Returns rendered template
     *
     * @return  string
     */
    public function get() {
        if (!empty($this->template['rendered'])) {
            return $this->template['rendered'];
        }
        return null;
    }

    public function __toString() {
        $this->get();
    }

    /**
     * Resets all fields
     *
     * @return simpleTemplate
     */
    public function destroy() {
        $this->params = null;
        $this->params = array();
        $this->template = null;
        $this->template = array(
            'source',
            'rendered'
        );
        return $this;
    }

}
