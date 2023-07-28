<?php
    namespace Core;

    class Core {
        /**
         * Identifies Controller, Action and Params of the request and directs the person responsible
         *
         * @return void
         */
        public function run() {
            $url = '/';

            if (isset($_GET['url']) && !empty($_GET['url'])) {
                $url .= $_GET['url']; //Telling how it was sent
            }

            //Verify the URL of the request of the client with some broken data and return to the broken that hit
            $url = $this->checkRoutes($url); 

            $currentAction = 'index';
            $params = array();
            
            if (!empty($url) && $url != '/') {
                $url = explode('/',$url);
                
                //Pulling just the first part of the url that is part before / without anything
                array_shift($url);
                
                //Identifying or controller
                if (isset($url[0]) && $url[0] != '/') {
                    $currentController = $url[0].'Controller';
                    array_shift($url);
                }
               // var_dump($url);

                //Identifying action
                if(isset($url[0]) && !empty($url[0])) {
                    $currentAction = $url[0];
                    array_shift($url);
                }

                //Identifying the Parameters
                if (count($url) > 0) {
                    $params = $url;
                }

            }
            else {
                $currentController = 'HomeController';
                $currentAction = 'index';
            }

            $currentController = ucfirst($currentController); //Make the first letter capitalized
            $prefix = '\Controllers\\';

            if (!file_exists('../Controllers/'.$currentController.'.php') ||
                !method_exists($prefix.$currentController, $currentAction)) {
                $currentController = 'NotfoundController';
                $currentAction = 'index';
            }

            //Instantiating the identified controller
            $namespaceController = $prefix.$currentController;
            $c = new $namespaceController;
            //Executing the respective action
            call_user_func_array(
                array($c, $currentAction),
                $params
            );
        }

        /**
         * Checks if the URL of the client's request matches any of the routes
         *
         * @param [string] $url
         * @return void
         */
        private function checkRoutes($url)
        {
            global $routes;

            foreach($routes as $pt => $newurl){
                //Etapa 1
                //Identifies the arguments and replaces them with regex
                $pattern = preg_replace('(\{[a-z0-9-]{1,}\})', '([a-z0-9-]{1,})',$pt);
          
                
                //Checking which of the routes match the url typed by the client
                if(preg_match('#^('.$pattern.')*$#i', $url, $matches) === 1){
                    
                    array_shift($matches); //Removing first result
                    array_shift($matches); //Removing second result
                    // echo '<pre>';
                    // print_r($matches); //Result that interests us

                    //Gets all arguments between {} to associate
                    $items = array();
                    if(preg_match_all('(\{[a-z0-9]{1,}\})',$pt,$m)){
                        // echo '<pre>';
                        // print_r($m);
                        
                        //Replacing every open and closed key case with nothing
                        $items = preg_replace('(\{|\})','',$m[0]);
                        // print_r($items);
                    }

                    $arg = array();
                    foreach ($matches as $key => $match) {
                        $arg[$items[$key]] = $match;
                    }
                    // echo '<pre>';
                    // print_r($arg);

                    foreach ($arg as $argkey => $argvalue) {
                        $newurl =  str_replace(':' . $argkey, $argvalue, $newurl);
                        // echo $newurl.'<br>';
                    }
                    $url = $newurl;
                    break;
                }
            }
            return $url;
        }
    }
?>