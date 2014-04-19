<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 2:41
 */

namespace Actinarium\Philtre\Core;

/**
 * Execution Manager interface, introduced to let users create their own implementations and pass them by interface to
 * their applications
 *
 * @package Actinarium\Philtre\Core
 */
interface ExecutionManager
{
    /**
     * @param mixed $configuration Execution Manager configuration
     */
    public function __construct($configuration);

    /**
     * The method that handles data processing (potentially including validation, initialization, invoking process()
     * methods on filters and optionally returning processing results). There are no rules on preconditions or output -
     * what this method must do is ensure that all filters operated as it was commanded to the ExecutionManager.
     *
     * @return void|mixed Calculation result, if configured so
     */
    public function process();
}
