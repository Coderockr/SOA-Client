<?php
namespace model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints;
use DMS\Filter\Rules as Filter;
use JMS\SerializerBundle\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="User")
 */
class User extends Entity
{

    /**
     * @ORM\Column(type="string", length=150)
     *
     * @Filter\StripTags()
     * @Filter\Trim()
     * @Filter\StripNewlines()
     * @JMS\Groups({"purchase","user"})
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     *
     * @Filter\StripTags()
     * @Filter\Trim()
     * @Filter\StripNewlines()
     * @JMS\Groups({"purchase","user"})
     * @var string
     */
    private $login;
    
    /**
     * @ORM\Column(type="string", length=250)
     *
     * @Filter\StripTags()
     * @Filter\Trim()
     * @Filter\StripNewlines()
     * @JMS\Groups({"purchase","user"})
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     *
     * @Filter\StripTags()
     * @Filter\Trim()
     * @Filter\StripNewlines()
     * @JMS\Groups({"purchase","user"})
     * @var string
     */
    private $email;
    
    /**
     * @ORM\Column(type="integer")
     * 
     * @Filter\Trim()
     * 
     * @JMS\Groups({"purchase","user"})
     * @var integer
     */
    protected $type;

    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        return $this->name = $name;
    }
    
    public function getLogin()
    {
        return $this->login;
    }
    
    public function setLogin($login)
    {
        return $this->login = $login;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function setPassword($password)
    {
        return $this->password = md5($password);
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
        return $this->email = $email;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setType($type)
    {
        return $this->type = $type;
    }

    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Constraints\NotNull());
        $metadata->addPropertyConstraint('name', new Constraints\NotBlank());

        $metadata->addPropertyConstraint('login', new Constraints\NotNull());
        $metadata->addPropertyConstraint('login', new Constraints\NotBlank());

        $metadata->addPropertyConstraint('password', new Constraints\NotNull());
        $metadata->addPropertyConstraint('password', new Constraints\NotBlank());

        $metadata->addPropertyConstraint('type', new Constraints\NotNull());
        $metadata->addPropertyConstraint('type', new Constraints\NotBlank());
        
    }
}
