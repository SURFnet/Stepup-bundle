<?php

/**
 * Copyright 2018 SURFnet bv
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Surfnet\StepupBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener as CoreExceptionListener;

/**
 * Prepare the generic error page with detailed information for the user.
 */
class ExceptionListener extends CoreExceptionListener
{
    /**
     * Clones the request for the exception.
     *
     * Stepup-specific override: pass the original exception instead of the
     * FlattenException so we can show very speficic user messages in the
     * ExceptionController.
     *
     * @param \Exception $exception The thrown exception
     * @param Request    $request   The original request
     *
     * @return Request $request The cloned request
     */
    protected function duplicateRequest(\Exception $exception, Request $request)
    {
        $attributes = array(
            '_controller' => $this->controller,
            'exception' => $exception,
            'logger' => $this->logger instanceof DebugLoggerInterface ? $this->logger : null,
            // keep for BC -- as $format can be an argument of the controller callable
            // see src/Symfony/Bundle/TwigBundle/Controller/ExceptionController.php
            // @deprecated since version 2.4, to be removed in 3.0
            'format' => $request->getRequestFormat(),
        );
        $request = $request->duplicate(null, null, $attributes);
        $request->setMethod('GET');

        return $request;
    }
}
