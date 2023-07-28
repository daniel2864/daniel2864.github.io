<?php
    namespace Core;

    class Model {
        protected $pdo;

        /**
         * Sets the member $pdo of the Model class to the global instance $db defined in config.php
         */
        public function __construct() {
            global $db;
            $this->pdo = $db;
        }
    }