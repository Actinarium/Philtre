<?php
/**
 * @author Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 2:41
 *
 * @version GIT: $Id$
 */

namespace Actinarium\Philtre\Core;


interface PipelineManager {
    /**
     * The only method every PipelineManager must implement. There are no rules on preconditions or output; what this
     * method must do is ensure that all filters operated as it was commanded to the PipelineManager.
     *
     * @return void|mixed
     */
    public function process();
}
