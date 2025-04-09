<?php

namespace Nosyos\Hivephp\Request;

class Context {
    public $pathParams;
    public $queryParams;
    public $formData;


    public function __construct() {
        $this->pathParams = [];
        $this->queryParams = [];
        $this->formData = [];
    }

    public function setPathParams(array $pathParams) {
        $this->pathParams = $pathParams;
        return; 
    }

    public function setQueryParams() {
        if (!empty($_GET)) {
            $this->queryParams = filter_var_array($_GET, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        return;
    }

    public function setFormData() {
        // TODO: implement logic
        return ;
    }
}
