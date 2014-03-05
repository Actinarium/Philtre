<?php
/**
 * @author pdanyliuk
 * Date: 06.03.14
 * Time: 0:58
 */

namespace Actinarium\Philtre\Core\IO\Metadata;


class StreamDescriptor
{
    protected $id;
    protected $description;
    protected $types;

    function __construct($id, array $types, $description = null)
    {
        $this->id = $id;
        $this->types = $types;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

} 
