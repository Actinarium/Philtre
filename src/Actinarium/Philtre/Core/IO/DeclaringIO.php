<?php
/**
 * @author Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 2:20
 *
 * @version GIT: $Id$
 */

namespace Actinarium\Philtre\Core\IO;
use Actinarium\Philtre\Core\IO\Metadata\IODescriptor;

/**
 * Declares {@link getIODescriptor()} method, which should be implemented by Filters and Contexts that want to notify
 * their superiors (Contexts and PipelineManagers respectively) about IO consumption.
 *
 * @package Actinarium\Philtre\Core
 */
interface DeclaringIO {
    /**
     * Every filter must implement this method in a valid fashion so that filter context can be aware of used streams.
     *
     * @return IODescriptor valid descriptor with streams metadata
     */
    public function getIODescriptor();
} 
