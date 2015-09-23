<?php
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 23/09/2015
 * Time: 12:56
 */

/**
 * Class Client
 */
class Client_model extends CI_Model {



    private $id = NULL;
    private $type = NULL;
    private $name = NULL;
    private $picture_url = NULL;
    private $description = NULL;
    private $facturable = NULL;
    private $status = NULL;

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param null $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return null
     */
    public function getPictureUrl()
    {
        return $this->picture_url;
    }

    /**
     * @param null $picture_url
     */
    public function setPictureUrl($picture_url)
    {
        $this->picture_url = $picture_url;
    }

    /**
     * @return null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return null
     */
    public function getFacturable()
    {
        return $this->facturable;
    }

    /**
     * @param null $facturable
     */
    public function setFacturable($facturable)
    {
        $this->facturable = $facturable;
    }

    /**
     * @return null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param null $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }




    /**
     * Constructor Contact
     */
    public function __construct()
    {
        $this->load->database();
    }

    /**
     * GetById
     */
    public function getById($id = NULL)
    {
        if(!is_null($id) && $id > 0)
        {
            $query = $this->db->select("id_client,type_profile_client,client,picture_url,description,facturable,status")
                ->where("id_client",$id)
                ->limit(1)
                ->get("client");

            $row = $query->row();

            $this->id = $row->id_client;
            $this->type = $row->type_profile_client;
            $this->name = $row->client;
            $this->picture_url = $row->picture_url;
            $this->description = $row->description;
            $this->facturable = $row->facturable;
            $this->status = $row->status;

        }

        return $this;
    }
}