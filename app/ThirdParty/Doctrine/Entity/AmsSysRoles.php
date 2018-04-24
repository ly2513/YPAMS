<?php



/**
 * AmsSysRoles
 */
class AmsSysRoles
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $menus;

    /**
     * @var string
     */
    private $permissions;

    /**
     * @var integer
     */
    private $pId = '0';

    /**
     * @var integer
     */
    private $status = '1';

    /**
     * @var integer
     */
    private $createBy = '0';

    /**
     * @var integer
     */
    private $updateBy = '0';

    /**
     * @var integer
     */
    private $createTime = '0';

    /**
     * @var integer
     */
    private $updateTime = '0';

    /**
     * @var boolean
     */
    private $isDelete = '0';


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return AmsSysRoles
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set menus
     *
     * @param string $menus
     *
     * @return AmsSysRoles
     */
    public function setMenus($menus)
    {
        $this->menus = $menus;

        return $this;
    }

    /**
     * Get menus
     *
     * @return string
     */
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * Set permissions
     *
     * @param string $permissions
     *
     * @return AmsSysRoles
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;

        return $this;
    }

    /**
     * Get permissions
     *
     * @return string
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Set pId
     *
     * @param integer $pId
     *
     * @return AmsSysRoles
     */
    public function setPId($pId)
    {
        $this->pId = $pId;

        return $this;
    }

    /**
     * Get pId
     *
     * @return integer
     */
    public function getPId()
    {
        return $this->pId;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return AmsSysRoles
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createBy
     *
     * @param integer $createBy
     *
     * @return AmsSysRoles
     */
    public function setCreateBy($createBy)
    {
        $this->createBy = $createBy;

        return $this;
    }

    /**
     * Get createBy
     *
     * @return integer
     */
    public function getCreateBy()
    {
        return $this->createBy;
    }

    /**
     * Set updateBy
     *
     * @param integer $updateBy
     *
     * @return AmsSysRoles
     */
    public function setUpdateBy($updateBy)
    {
        $this->updateBy = $updateBy;

        return $this;
    }

    /**
     * Get updateBy
     *
     * @return integer
     */
    public function getUpdateBy()
    {
        return $this->updateBy;
    }

    /**
     * Set createTime
     *
     * @param integer $createTime
     *
     * @return AmsSysRoles
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * Get createTime
     *
     * @return integer
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Set updateTime
     *
     * @param integer $updateTime
     *
     * @return AmsSysRoles
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    /**
     * Get updateTime
     *
     * @return integer
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * Set isDelete
     *
     * @param boolean $isDelete
     *
     * @return AmsSysRoles
     */
    public function setIsDelete($isDelete)
    {
        $this->isDelete = $isDelete;

        return $this;
    }

    /**
     * Get isDelete
     *
     * @return boolean
     */
    public function getIsDelete()
    {
        return $this->isDelete;
    }
}

