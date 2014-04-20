<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 19.04.14
 * Time: 17:57
 */

namespace Actinarium\Philtre\Core\IO\Metadata;

/**
 * Utility class to build {@link IODescriptor} easier
 *
 * @package Actinarium\Philtre\Core\IO\Metadata
 */
class IODescriptorBuilder
{

    /** @var StreamDescriptor[] */
    protected $requiredStreams = array();
    /** @var StreamDescriptor[] */
    protected $exportedStreams = array();
    /** @var StreamDescriptor[] */
    protected $usedStreams = array();

    private $temp;

    /**
     * @param string $streamId
     * @param string $description
     */
    public function requires($streamId = null, $types = array(), $description = null)
    {
        $this->pushToTemp('require', $streamId, $types, $description);
        return $this;
    }

    /**
     * @param string $streamId
     * @param string $description
     */
    public function exports($streamId = null, $types = array(), $description = null)
    {
        $this->pushToTemp('export', $streamId, $types, $description);
        return $this;
    }

    /**
     * @param string $streamId
     * @param string $description
     */
    public function uses($streamId = null, $types = array(), $description = null)
    {
        $this->pushToTemp('use', $streamId, $types, $description);
        return $this;
    }

    /**
     * @param string $streamId
     */
    public function streamId($streamId)
    {
        $this->pushToTemp(null, $streamId);
        return $this;
    }

    public function type($type)
    {
        $this->pushToTemp(null, null, $type);
        return $this;
    }

    /**
     * @param string|null $description
     */
    public function description($description)
    {
        $this->pushToTemp(null, null, null, $description);
        return $this;
    }

    public function get()
    {
        if ($this->temp !== null) {
            $this->pushTempToAppropriateList();
        }
        return new IODescriptor(
            $this->requiredStreams,
            $this->exportedStreams,
            $this->usedStreams
        );
    }

    /**
     * @param string $type
     */
    private function pushToTemp($type = null, $streamId = null, $types = null, $description = null)
    {
        if ($this->temp === null) {
            // very first call. Must be one of the "starting" methods to define where the descriptor should be stored
            if ($type === null) {
                throw new \LogicException(
                    'You should use requires(), exports() or uses() prior to supplying descriptor parameters'
                );
            } else {
                $this->temp = array('type'        => $type, 'streamId' => $streamId, 'types' => $types,
                                    'description' => $description);
                return;
            }
        } elseif ($type === null) {
            // this means, we are pushing data to current temp object
            if ($streamId !== null) {
                $this->temp['streamId'] = $streamId;
            }
            if ($types !== null) {
                if (is_string($types)) {
                    $this->temp['types'][] = $types;
                } elseif (is_array($types)) {
                    foreach ($types as $t) {
                        $this->temp['types'][] = $t;
                    }
                }
            }
            if ($description !== null) {
                $this->temp['description'] = $description;
            }
        } else {
            // we started creating a new object - then save this one and fill the new one with provided data
            $this->pushTempToAppropriateList();
            $this->temp = null;
            $this->pushToTemp($type, $streamId, $types, $description);
        }
    }

    private function pushTempToAppropriateList()
    {
        $streamDesc = new StreamDescriptor($this->temp['streamId'], $this->temp['types'], $this->temp['description']);
        switch ($this->temp['type']) {
            case 'require':
                $this->requiredStreams[] = $streamDesc;
                break;
            case 'export':
                $this->exportedStreams[] = $streamDesc;
                break;
            case 'use':
                $this->usedStreams[] = $streamDesc;
                break;
        }
    }
}
