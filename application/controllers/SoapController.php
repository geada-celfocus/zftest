<?php

/**
 * Still doesn't work and is currently not being used or implemented in any way
 *
 */
class SoapController extends Zend_Controller_Action
{
    /**
     * This function is still not in use
     * It will, however, remain in the file to allow me to study different
     * implementations of the soap api
     *
     * @return void
     */
    // public function wsdlAction()
    // {
    //     $request = $this->getRequest();

    //     if (!$request->isGet()) {
    //         return $this->prepareClientErrorResponse('GET');
    //     }

    //     $wsdl = new Zend_Soap_AutoDiscover();
    //     $this->populateServer($wsdl);

    //     $response = $this->getResponse();

    //     $response->getHeaders()->addHeaderLine("Content-Type", "application/wsdl+xml");
    //     $response->setContent($wsdl->toXml());

    //     return $response;
    //}

    /**
     * This function is still not in use
     * It will, however, remain in the file to allow me to study different
     * implementations of the soap api
     *
     * @return void
     */
    // public function serverAction()
    // {
    //     $request = $this->getRequest();

    //     if (!$request->isPost()) {
    //         return $this->prepareClientErrorResponse("Post");
    //     }

    //     $server = new Zend_Soap_Server(
    //         $this->url()
    //             ->fromRoute("soap/wsdl", [], ["force_canonical" => true]),
    //         [
    //                 "actor" => $this->url()
    //                     ->fromRoute("soap/server", [], ["force_canonical" => true]),
    //             ]
    //     );
    //     $server->setReturnResponse(true);
    //     $this->populateServer($server);

    //     $soapResponse = $server->handle($request->getContent());

    //     $response->getHeaders()->addHeaderLine("Content-Type", "application/soap+xml");
    //     $response->setContent($soapResponse);
    //     return $response;
    // }

    /**
     * Serves the data for users related requests
     *
     * @return void
     */
    public function usersAction()
    {
        $config = Zend_Registry::get("config");
        $this->getHelper("viewRenderer")->setNoRender(true);

        $server = new Zend_Soap_Server($config->soap->wsdl->directory_path . "users.wsdl", null);

        //custom service class, under library/soap/service
        $server->setClass(Soap_Service_User::class);
        $server->handle();
    }

    /**
     * This function is used by the commented methods above
     * It might be of use to those functions, so, in the mean time I will keep
     * it. It is not being used
     *
     * @param [type] $server
     * @param [type] $env
     * @return void
     */
    // public function populateServer($server, $env)
    // {
        // $server->setClass(Users::class);
    // }
}
