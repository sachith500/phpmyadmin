<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * URL redirector to avoid leaking Referer with some sensitive information.
 *
 * @package PhpMyAdmin
 */

/**
 * Gets core libraries and defines some variables
 */
define('PMA_MINIMUM_COMMON', true);
require_once './libraries/common.inc.php';

if (! PMA_isValid($_GET['url'])
    || ! preg_match('/^https?:\/\/[^\n\r]*$/', $_GET['url'])
) {
    header('Location: ' . $cfg['PmaAbsoluteUri']);
} else {
    // header('Location: ' . $_GET['url']);
    
    // domain whitelist check
    if (PMA_isAllowedDomain($_GET['url'])) {
        // JavaScript redirection is necessary. Because if header() is used
        //  then web browser sometimes does not change the HTTP_REFERER 
        //  field and so with old URL as Referer, token also goes to 
        //  external site.
        echo "<script type='text/javascript'>
		    	window.onload=function(){
		    		window.location='" . $_GET['url'] . "';
	    	}
	    	</script>";
        // Display redirecting msg on screen.
        echo __('Taking you to ') . ($_GET['url']);
    } else {
        header('Location: ' . $cfg['PmaAbsoluteUri']);
    }
}
die();
?>
