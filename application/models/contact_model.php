<?php
/**
 * Created by PhpStorm.
 * User: dbourgon
 * Date: 23/09/2015
 * Time: 12:56
 */

/**
 * Class Contacta
 */
class Contact_model extends CI_Model {



    private $id = NULL;
    private $client = NULL;
    private $type = NULL;
    private $name = NULL;
    private $type_via = NULL;
    private $address = NULL;
    private $zip = NULL;
    private $city = NULL;
    private $province = NULL;
    private $county = NULL;
    private $schedule = NULL;
    private $phone = NULL;
    private $mobile = NULL;
    private $email = NULL;
    private $email_cc = NULL;
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
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param null $client
     */
    public function setClient($client)
    {
        $this->client = $client;
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
    public function getTypeVia()
    {
        return $this->type_via;
    }

    /**
     * @param null $type_via
     */
    public function setTypeVia($type_via)
    {
        $this->type_via = $type_via;
    }

    /**
     * @return null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param null $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return null
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param null $zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * @return null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param null $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return null
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param null $province
     */
    public function setProvince($province)
    {
        $this->province = $province;
    }

    /**
     * @return null
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * @param null $county
     */
    public function setCounty($county)
    {
        $this->county = $county;
    }

    /**
     * @return null
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * @param null $schedule
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * @return null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param null $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return null
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param null $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * @return null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return null
     */
    public function getEmailCc()
    {
        return $this->email_cc;
    }

    /**
     * @param null $email_cc
     */
    public function setEmailCc($email_cc)
    {
        $this->email_cc = $email_cc;
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
            $query = $this->db->select("id_contact,client_contact,type_profile_contact,contact,type_via,address,zip,city,
                    province,county,schedule,phone,mobile,email,email_cc,status")
                    ->where("id_contact",$id)
                    ->limit(1)
                    ->get("contact");

            $row = $query->row();

            $this->id = $row->id_contact;
            $this->client = $row->client_contact;
            $this->type = $row->type_profile_contact;
            $this->name = $row->contact;
            $this->type_via = $row->type_via;
            $this->address = $row->address;
            $this->zip = $row->zip;
            $this->city = $row->city;
            $this->province = $row->province;
            $this->county = $row->county;
            $this->schedule = $row->schedule;
            $this->phone = $row->phone;
            $this->mobile = $row->mobile;
            $this->email = $row->email;
            $this->email_cc = $row->email_cc;
            $this->status = $row->status;

        }

        return $this;
    }
}