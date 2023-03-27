---------------
# ddos_security
---------------

CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Recommended modules
 * Installation
 * Configuration
 * Troubleshooting
 * Maintainers

INTRODUCTION
------------
 
 This module provides a managed DDoS protection service.

	What is a DDoS attack.?
	Distributed denial of service (DDoS) attacks are a subclass of denial of service (DoS) attacks. A DDoS attack involves multiple connected online devices, collectively known as a botnet, which are used to overwhelm a target website with fake traffic.

	What is the purpose of DDoS Security module.?
	DDoS Security (ddos_security) provides an options to improve security of web application. DDoS attacks are typically measured in bits per seconds. This DDoS Security module prevent's that automatically with hits per second calculation.  However, these techniques requires server-side implementation. Thus, DDoS Security provides websites with easy and flexible way to implement them.

RECOMMENDED MODULES
-------------------

 * No extra module is required.

INSTALLATION
------------

 * Install as usual, see
   https://www.drupal.org/docs/8/extending-drupal-8/installing-contributed-modules-find-import-enable-configure-drupal-8 for further
   information.

CONFIGURATION
-------------

	To enable ddos_security:

	1. Enable the module
	2. Go to the admin interface (admin/config/ddos-security).
	3. Users can manage configuration using simple guidelines in the field's description.
	4. For Download DDoS Security Log Report in CSV (admin/config/ddos-security/export/csv)
	5. Nothing else :)

TROUBLESHOOTING
---------------

 * DDoS Security module doesn't provide any visible functions to the user on its own, it
   just provides security handling services.


MAINTAINERS
-----------

Current maintainers:

 * Manikandan Ramakrishnan (https://www.drupal.org/user/3508408/)
