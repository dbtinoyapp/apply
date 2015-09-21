<?php

namespace Auth\Entity;

use Core\Entity\AbstractEntity;
use Core\Entity\EntityInterface;
use Core\Entity\FileEntity;
use Core\Entity\FileEntityInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * peronal informations of a user.
 * 
 * @ODM\EmbeddedDocument
 */
class Info extends AbstractEntity implements InfoInterface {

    /** @var string 
     * @ODM\String */
    protected $profession;

    /** @var string 
     * @ODM\String */
    protected $birthDay;

    /** @var string 
     * @ODM\String */
    protected $birthMonth;

    /** @var string 
     * @ODM\String */
    protected $birthYear;

    /** @var string 
     * @ODM\String */
    protected $email;

    /** @var string 
     * @ODM\String */
    protected $firstName;
    
    /** @var string 
     * @ODM\String */
    protected $middleName;

    /** @var string 
     * @ODM\String */
    protected $gender;

    /** @var string 
     * @ODM\String */
    protected $houseNumber;

    /** @var string
     * @ODM\String */
    protected $lastName;

    /** @var string 
     * @ODM\String */
    protected $phone;

    /** @var string 
     * @ODM\String */
    protected $postalcode;

    /** @var string 
     * @ODM\String */
    protected $city;

    /**
     * 
     * @var FileInterface
     * @ODM\ReferenceOne(targetDocument="UserImage", cascade={"persist"}, simple=true, nullable=true) 
     */
    protected $image;

    /** @var string 
     * @ODM\String */
    protected $street;

    /** @var string 
     * @ODM\String */
    protected $dob;

    /** @var string 
     * @ODM\String */
    protected $age;

    /** @var string 
     * @ODM\String */
    protected $civilStatus;

    /** @var string 
     * @ODM\String */
    protected $citizenship;

    /** @var string 
     * @ODM\String */
    protected $tin;

    /** @var string 
     * @ODM\String */
    protected $sss;

    /**
     * {@inheritdoc}
     * @return \Auth\Model\User
     */
    public function setProfession($profession) {
        $this->profession = $profession;
        return $this;
    }
    /**
     * {@inheritdoc}
     * @return \Auth\Model\User
     */
    public function getFullName() {
        return $this->firstName. ' ' .$this->middleName. ' ' . $this->lastName;
    }

    /** {@inheritdoc} */
    public function getProfession() {
        return $this->profession;
    }

    /**
     * {@inheritdoc}
     * @return \Auth\Model\User
     */
    public function setBirthDay($birthDay) {
        $this->birthDay = $birthDay;
        return $this;
    }

    /** {@inheritdoc} */
    public function getBirthDay() {
        return $this->street;
    }

    /**
     * {@inheritdoc}
     * @return \Auth\Model\User
     */
    public function setBirthMonth($birthMonth) {
        $this->birthDay = $birthMonth;
        return $this;
    }

    /** {@inheritdoc} */
    public function getBirthMonth() {
        return $this->birthMonth;
    }

    /**
     * {@inheritdoc}
     * @return \Auth\Model\User
     */
    public function setBirthYear($birthYear) {
        $this->birthYear = $birthYear;
        return $this;
    }

    /** {@inheritdoc} */
    public function getBirthYear() {
        return $this->birthYear;
    }

    /**
     * {@inheritdoc}
     * @return \Auth\Model\User
     */
    public function setEmail($email) {
        $this->email = trim((String) $email);
        return $this;
    }

    /** {@inheritdoc} */
    public function getEmail() {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     * @return \Auth\Model\User
     */
    public function setFirstName($firstName) {
        $this->firstName = trim((String) $firstName);
        return $this;
    }

    /** {@inheritdoc} */
    public function getGender() {
        return $this->gender;
    }

    /**
     * {@inheritdoc}
     * @return \Auth\Model\User
     */
    public function setGender($gender) {
        $this->gender = trim((String) $gender);
        return $this;
    }

    /** {@inheritdoc} */
    public function getFirstName() {
        return $this->firstName;
    }
    public function getMiddleName() {
        return $this->middleName;
    }

    public function setMiddleName($middleName) {
        $this->middleName = $middleName;
        return $this;
    }

        /**
     * {@inheritdoc}
     * @return \Auth\Model\User
     */
    public function setHouseNumber($houseNumber) {
        $this->houseNumber = $houseNumber;
        return $this;
    }

    /** {@inheritdoc} */
    public function getHouseNumber() {
        return $this->houseNumber;
    }

    /**
     * {@inheritdoc}
     * @return \Auth\Model\User
     */
    public function setLastName($name) {
        $this->lastName = trim((String) $name);
        return $this;
    }

    /** {@inheritdoc} */
    public function getLastName() {
        return $this->lastName;
    }

    public function getDisplayName() {
        if (!$this->lastName) {
            return $this->email;
        }
        return ($this->firstName ? $this->firstName . ' ' : '') . $this->lastName;
    }

    public function getAddress($extended = false) {
        $address = array();
        if ($this->lastName) {
            $address[] = ("male" == $this->gender ? 'Mr' : 'Ms') . ' '
                    . ($this->firstName ? $this->firstName . ' ' : '')
                    . $this->lastName;
        }
        if ($this->street) {
            $address[] = $this->street . ($this->houseNumber ? ' ' . $this->houseNumber : '');
        }
        if ($this->city) {
            $address[] = ($this->postalCode ? $this->postalCode . ' ' : '') . $this->city;
        }

        if ($extended) {
            $address[] = ''; // empty line

            if ($this->phone) {
                $address[] = $this->phone;
            }
            if ($this->email) {
                $address[] = $this->email;
            }
        }

        return implode(PHP_EOL, $address);
    }

    /**
     * {@inheritdoc}
     * @return \Auth\Model\User
     */
    public function setPhone($phone) {
        $this->phone = (String) $phone;
        return $this;
    }

    /** {@inheritdoc} */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * {@inheritdoc}
     * @return \Auth\Model\User
     */
    public function setPostalcode($postalcode) {
        $this->postalcode = (String) $postalcode;
        return $this;
    }

    /** {@inheritdoc} */
    public function getPostalcode() {
        return $this->postalcode;
    }

    /**
     * {@inheritdoc}
     * @return \Auth\Model\User
     */
    public function setCity($city) {
        $this->city = (String) $city;
        return $this;
    }

    /** {@inheritdoc} */
    public function getCity() {
        return $this->city;
    }

    public function setImage(EntityInterface $image = null) {
        $this->image = $image;
        return $this;
    }

    public function getImage() {
        return $this->image;
    }

    /**
     * {@inheritdoc}
     * @return \Auth\Model\User
     */
    public function setStreet($street) {
        $this->street = $street;
        return $this;
    }

    /** {@inheritdoc} */
    public function getStreet() {
        return $this->street;
    }

    public function getDob() {
        return $this->dob;
    }

    public function getAge() {
        return $this->age;
    }

    public function getCivilStatus() {
        return $this->civilStatus;
    }

    public function getCitizenship() {
        return $this->citizenship;
    }

    public function getTin() {
        return $this->tin;
    }

    public function getSss() {
        return $this->sss;
    }

    public function setDob($dob) {
        $this->dob = $dob;
        return $this;
    }

    public function setAge($age) {
        $this->age = $age;
        return $this;
    }

    public function setCivilStatus($civilStatus) {
        $this->civilStatus = $civilStatus;
        return $this;
    }

    public function setCitizenship($citizenship) {
        $this->citizenship = $citizenship;
        return $this;
    }

    public function setTin($tin) {
        $this->tin = $tin;
        return $this;
    }

    public function setSss($sss) {
        $this->sss = $sss;
        return $this;
    }

    /**
     * convert an array into an Info Object
     * @param Array $array
     * @return \Auth\Entity\Info
     */
    public function fromArray($array) {
        $this->profession = $array['profession'];
        $this->birthDay = $array['birthDay'];
        $this->birthMonth = $array['birthMonth'];
        $this->birthYear = $array['birthYear'];
        $this->firstName = $array['firstName'];
        $this->middleName = $array['middleName'];
        $this->lastName = $array['lastName'];
        $this->email = $array['email'];
        $this->gender = $array['gender'];
        $this->street = $array['street'];
        $this->houseNumber = $array['houseNumber'];
        $this->phone = $array['phone'];
        $this->postalcode = $array['postalcode'];
        $this->city = $array['city'];
        $this->dob = $array['dob'];
        $this->age = $array['age'];
        $this->civilStatus = $array['civilStatus'];
        $this->citizenship = $array['citizenship'];
        $this->image = $array['image'];
        $this->tin = $array['tin'];
        $this->sss = $array['sss'];
        return($this);
    }

    /**
     * convert an Info object into an Array
     * @param Info $info
     * @return Array
     */
    static function toArray(Info $info) {
        $array['profession'] = $info->profession;
        $array['birthDay'] = $info->birthDay;
        $array['birthMonth'] = $info->birthMonth;
        $array['birthYear'] = $info->birthYear;
        $array['firstName'] = $info->firstName;
        $array['middleName'] = $info->middleName;
        $array['lastName'] = $info->lastName;
        $array['email'] = $info->email;
        $array['gender'] = $info->gender;
        $array['street'] = $info->street;
        $array['houseNumber'] = $info->houseNumber;
        $array['phone'] = $info->phone;
        $array['postalcode'] = $info->postalcode;
        $array['city'] = $info->city;
        $array['dob'] = $info->dob;
        $array['age'] = $info->age;
        $array['civilStatus'] = $info->civilStatus;
        $array['citizenship'] = $info->citizenship;
        $array['image'] = $info->image;
        $array['tin'] = $info->tin;
        $array['sss'] = $info->sss;
        return $array;
    }

}
