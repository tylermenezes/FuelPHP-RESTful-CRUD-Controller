<?php
 namespace Fuel\Core;

abstract class Controller_Rest_Crud extends \Controller_Rest
{
    public function router($resource, array $arguments){
        array_unshift($arguments, $resource);
        \Config::load('rest', true);

        $pattern = '/\.(' . implode('|', array_keys($this->_supported_formats)) . ')$/';

        // Check if a file extension is used
        if (preg_match($pattern, $resource, $matches))
        {
            // Remove the extension from arguments too
            $resource = preg_replace($pattern, '', $resource);
            $arguments[count($arguments) - 1] = preg_replace($pattern, '', $arguments[count($arguments) - 1]);

            if($arguments[count($arguments) - 1] == ""){
                array_pop($arguments);
            }

            $this->format = $matches[1];
        }
        else
        {
            // Which format should the data be returned in?
            $this->format = $this->_detect_format();
        }

        //Check method is authorized if required
        if (\Config::get('rest.auth') == 'basic')
        {
            $valid_login = $this->_prepare_basic_auth();
        }
        elseif (\Config::get('rest.auth') == 'digest')
        {
            $valid_login = $this->_prepare_digest_auth();
        }

        //If the request passes auth then execute as normal
        if(\Config::get('rest.auth') == '' or $valid_login)
        {
            // Go to $this->method(), unless there are no args and it's a GET, in which case, go to $this->get_list();
            $controller_method = strtolower(\Input::method());
            if(\Input::method() == "GET" && $resource == ""){

                // Add the page variable to the method
                $page = Input::get("page", 0);
                $search = Input::get("search", FALSE);

                if($search !== FALSE && method_exists($this, "get_search")){
                    $controller_method = "get_search";

                    array_push($arguments, $search);
                    array_push($arguments, $page);
                }else if(method_exists($this, "get_list")){
                    $controller_method = "get_list";

                    array_push($arguments, $page);
                }
            }

            // If method is not available, set status code to 404
            if (method_exists($this, $controller_method))
            {
                call_user_func_array(array($this, $controller_method), $arguments);
            }
            else
            {
                $this->response->status = 404;
                return;
            }
        }
        else
        {
            $this->response(array('status'=>0, 'error'=> 'Not Authorized'), 401);
        }
    }
}
?>