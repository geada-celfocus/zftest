<?php

/**
 * This service provides the soap api the logic to be executed in each request
 */
class Soap_Service_User
{
    
    /**
     * Get all users present in database, according to restrictions
     * passed in the parameters
     *
     * @param [array] $params
     * @return array
     */
    public function getUsers($params = null)
    {
        $users = new Users();

        $columns = array(
            "id",
            "name",
            "access_level",
            "active"
        );
        $result = (null === $params) ? $users->fetchAll($users->select($columns)) : $users->select($columns)->where($params);

        return $result;
    }
}
