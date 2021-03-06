<?php

namespace Core\View\Helper;

use Zend\View\Helper\AbstractHelper;

namespace Core\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * This helper generates HTML link markup from a URI or email address.
 *
 * <code>
 *
 * $this->link('http://test.com');
 * // Outputs: <a href="http://test.com">http://test.com</a>
 *
 * $this->link('http://test.com', 'Test.Com');
 * // Outputs: <a href="http://test.com">Test.Com</a>
 *
 * $this->link('test@host.tld');
 * // Ouptpus: <a href="mailto:test@host.tld">test@host.tld</a>
 *
 * $this->link('test@host.tld', 'send mail');
 * // Outputs: <a href="mailto:test@host.tld">send mail</a>
 * </code>
 *
 */
class Link extends AbstractHelper {

    /**
     * generates a link from a text snippet
     *
     * @param string $urlOrEmail
     * @param string $label
     * @return string
     */
    public function __invoke($urlOrEmail, $label = null) {
        if (null === $label) {
            $label = $urlOrEmail;
        }

        if (false !== strpos($urlOrEmail, '@')) {
            $urlOrEmail = 'mailto:' . $urlOrEmail;
        }

        return sprintf('<a href="%s">%s</a>', $urlOrEmail, $label);
    }

}
