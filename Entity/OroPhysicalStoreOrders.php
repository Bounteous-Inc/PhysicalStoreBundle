<?php

namespace DemacMedia\Bundle\PhysicalStoreBundle\Entity;

use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 * OroPhysicalStoreOrders
 *
 * @ORM\Table(name="oro_physicalstore_orders")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @Config(
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-ok-circle",
 *              "label"="PhysicalStore Order",
 *              "plural_label"="PhysicalStore Orders",
 *              "description"="PhysicalStore Order"
 *          },
 *      "security"={
 *          "type"="ACL"
 *      },
 *      "ownership"={
 *          "owner_type"="USER",
 *          "owner_field_name"="owner",
 *          "owner_column_name"="user_owner_id",
 *          "organization_field_name"="organization",
 *          "organization_column_name"="organization_id"
 *      }
 *  }
 * )
 */
class OroPhysicalStoreOrders
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="id",
     *              "plural_label"="ids",
     *              "description"="id"
     *          }
     *      }
     * )
     */
    protected $id;


    /**
     * @var string
     *
     * @ORM\Column(name="invno", type="string", length=32, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Inventory Number",
     *              "plural_label"="Inventories Number",
     *              "description"="Inventory Number"
     *          }
     *      }
     * )
     */
    protected $invno;

    /**
     * @var string
     *
     * @ORM\Column(name="custno", type="string", length=32, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Customer Number",
     *              "plural_label"="Customers Number",
     *              "description"="Customer Number"
     *          }
     *      }
     * )
     */
    protected $custno;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="invdate", type="datetime", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Inventory Date",
     *              "plural_label"="Inventories Date",
     *              "description"="Inventory Date"
     *          }
     *      }
     * )
     */
    protected $invdate;

    /**
     * @var string
     *
     * @ORM\Column(name="shipvia", type="string", length=32, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Shipvia",
     *              "plural_label"="Shipvia",
     *              "description"="Shipvia"
     *          }
     *      }
     * )
     */
    protected $shipvia;

    /**
     * @var string
     *
     * @ORM\Column(name="cshipno", type="string", length=32, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="cshipno",
     *              "plural_label"="cshipno",
     *              "description"="cshipno"
     *          }
     *      }
     * )
     */
    protected $cshipno;


    /**
     * @var float
     *
     * @ORM\Column(name="taxrate", type="percent", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Tax Rate",
     *              "plural_label"="Taxes Rate",
     *              "description"="Tax Rate"
     *          }
     *      }
     * )
     */
    protected $taxrate;

    /**
     * @var double
     *
     * @ORM\Column(name="tax", type="money", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Tax",
     *              "plural_label"="Taxes",
     *              "description"="Tax"
     *          }
     *      }
     * )
     */
    protected $tax;

    /**
     * @var float
     *
     * @ORM\Column(name="invamt", type="float", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Inventory Amount",
     *              "plural_label"="Inventories Amount",
     *              "description"="Inventory Amount"
     *          }
     *      }
     * )
     */
    protected $invamt;

    /**
     * @var string
     *
     * @ORM\Column(name="ponum", type="string", length=32, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Ponum",
     *              "plural_label"="Ponum",
     *              "description"="Ponum"
     *          }
     *      }
     * )
     */
    protected $ponum;

    /**
     * @var string
     *
     * @ORM\Column(name="refno", type="string", length=32, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Refno",
     *              "plural_label"="Refno",
     *              "description"="Refno"
     *          }
     *      }
     * )
     */
    protected $refno;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Created At",
     *              "plural_label"="Created At",
     *              "description"="Created At"
     *          }
     *      }
     * )
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Updated At",
     *              "plural_label"="Updated At",
     *              "description"="Updated At"
     *          }
     *      }
     * )
     */
    protected $updated;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_owner_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          },
     *          "entity"={
     *              "label"="Owner",
     *              "plural_label"="Owners",
     *              "description"="Owner"
     *          }
     *      }
     * )
     */
    protected $owner;

    /**
     * @var Organization
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          },
     *          "entity"={
     *              "label"="Organization",
     *              "plural_label"="Organizations",
     *              "description"="Organization"
     *          }
     *      }
     * )
     */
    protected $organization;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getInvno()
    {
        return $this->invno;
    }

    /**
     * @param string $invno
     */
    public function setInvno($invno)
    {
        $this->invno = $invno;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustno()
    {
        return $this->custno;
    }

    /**
     * @param string $custno
     */
    public function setCustno($custno)
    {
        $this->custno = $custno;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getInvdate()
    {
        return $this->invdate;
    }

    /**
     * @param \DateTime $invdate
     */
    public function setInvdate($invdate)
    {
        $this->invdate = $invdate;

        return $this;
    }

    /**
     * @return string
     */
    public function getShipvia()
    {
        return $this->shipvia;
    }

    /**
     * @param string $shipvia
     */
    public function setShipvia($shipvia)
    {
        $this->shipvia = $shipvia;

        return $this;
    }

    /**
     * @return string
     */
    public function getCshipno()
    {
        return $this->cshipno;
    }

    /**
     * @param string $cshipno
     */
    public function setCshipno($cshipno)
    {
        $this->cshipno = $cshipno;

        return $this;
    }

    /**
     * @return float
     */
    public function getTaxrate()
    {
        return $this->taxrate;
    }

    /**
     * @param float $taxrate
     */
    public function setTaxrate($taxrate)
    {
        $this->taxrate = $taxrate;

        return $this;
    }

    /**
     * @return float
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @param float $tax
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * @return float
     */
    public function getInvamt()
    {
        return $this->invamt;
    }

    /**
     * @param float $invamt
     */
    public function setInvamt($invamt)
    {
        $this->invamt = $invamt;

        return $this;
    }

    /**
     * @return string
     */
    public function getPonum()
    {
        return $this->ponum;
    }

    /**
     * @param string $ponum
     */
    public function setPonum($ponum)
    {
        $this->ponum = $ponum;

        return $this;
    }

    /**
     * @return string
     */
    public function getRefno()
    {
        return $this->refno;
    }

    /**
     * @param string $refno
     */
    public function setRefno($refno)
    {
        $this->refno = $refno;

        return $this;
    }

    /**
     * Get created date
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created date
     *
     * @param \DateTime $date
     * @return $this
     */
    public function setCreated(\DateTime $date = null)
    {
        if (!$date) {
            $this->created = new \DateTime('now', new \DateTimeZone('UTC'));
        } else {
            $this->created = $date;
        }

        return $this;
    }

    /**
     * Get updated date
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set updated date
     *
     * @param \DateTime $date
     * @return $this
     */
    public function setUpdated(\DateTime $date = null)
    {
        if (!$date) {
            $this->updated = new \DateTime('now', new \DateTimeZone('UTC'));
        } else {
            $this->updated = $date;
        }

        return $this;
    }

    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     * @return OroPhysicalStoreAccounts
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get organization
     *
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set organization
     *
     * @param Organization $organization
     * @return OroPhysicalStoreAccounts
     */
    public function setOrganization(Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * To string
     *
     * @return string
     */
    public function __toString()
    {
        return get_class();
    }

    /**
     * Pre persist event listener
     *
     * @ORM\PrePersist
     */
    public function beforeSave()
    {
        $this->created = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->updated = new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * Pre update event handler
     * @ORM\PreUpdate
     */
    public function doUpdate()
    {
        $this->updated = new \DateTime('now', new \DateTimeZone('UTC'));
    }
}
