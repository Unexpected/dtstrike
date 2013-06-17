<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * This file is part of AuthLDAP.

    AuthLDAP is free software: you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    AuthLDAP is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with AuthLDAP.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

/**
 * @author      Greg Wojtak <gwojtak@techrockdo.com>
 * @copyright   Copyright © 2010-2013 by Greg Wojtak <gwojtak@techrockdo.com>
 * @package     AuthLDAP
 * @subpackage  configuration
 * @license     GNU Lesser General Public License
 */

/**
 * Array Index        - Usage
 * ldap_uri           - Array of ldap URI's to try to connect to (see RFC2732)
 * use_tls            - Attempt to use transport layer security (TLS)
 * search_base        - Default search base (usually your top level DN)
 * user_search_base   - Array of DN's to search for users.
 * group_search_base  - Array of DN's to search for groups.
 * user_object_class  - objectClass to use for user searches
 * group_object_class - objectClass to use for group searches
 * login_attribute    - LDAP attribute used to check usernames against
 * schema_type        - The schema type your Directory Server uses.  Currently supported are rfc2307, rfc2307bis, and ad
 * proxy_user         - Distinguised name of a proxy user if your LDAP server does not allow anonymous binds
 * proxy pass         - Password to use with above
 * roles              - An array of role names to use within your app.  The values are arbitrary.  
 *                      The keys themselves represent the
 *			"security level," ie
 *			if( $security_level >= 3 ) {
 *				// Is a power user
 *				echo display_info_for_power_users_or_admins();
 *			}
 * auditlog         - Location to log auditable events.  Needs to be writeable
 *                      by the web server
 */

/*
 * The following may be used as a guide for setting up to authenticate
 * against OpenLDAP server
 */
$config['ldap_uri'] = array('ldap://fr-dc019.groupinfra.com:389/');
$config['use_tls'] = false; // Encrypted without using SSL
$config['search_base'] = 'OU=FR,OU=Landlord FR,OU=Corporate,DC=groupinfra,DC=com';
$config['user_search_base'] = array('OU=Users,OU=FR,OU=Landlord FR,OU=Corporate,DC=groupinfra,DC=com');  // Leave empty to use $config['search_base']
$config['group_search_base'] = array('OU=Groups,OU=FR,OU=Landlord FR,OU=Corporate,DC=groupinfra,DC=com');  // Leave empty to use $config['search_base']
$config['user_object_class'] = 'user';
$config['group_object_class'] = 'group';
$config['user_search_filter'] = '';  // Additional search filters to use for user lookups
$config['group_search_filter'] = ''; // Additional search filters to use for group lookups
$config['login_attribute'] = 'cn';
$config['schema_type'] = 'ad'; // Use rfc2307, rfc2307bis, or ad
$config['proxy_user'] = getenv('LDAP_USER');
$config['proxy_pass'] = getenv('LDAP_PASS');
$config['roles'] = array(1 => '_KLS_Utilisateurs', 
                         3 => 'FR_R2A_LYO_DirectionTechnique'); // FIXME Ajouter les bons rôles
$config['auditlog'] = 'application/logs/audit.log';  // Some place to log attempted logins (separate from message log)

/*
 * The following is a sample configuration for Active Directory servers
 * 
 * $config['ldap_uri'] = array('ldaps://dc1.mycompany.com/', 'ldaps://dc2.mycompany.com/');
 * $config['use_tls'] = false;  // Using SSL above, don't use TLS with it
 * $config['search_base'] = 'DC=mycompany,DC=com';
 * $config['user_search_base'] = 'CN=Users,DC=mycompany,DC=com';
 * $config['group_search_base'] = 'CN=Users,DC=mycompany,DC=com';
 * $config['user_object_class'] = 'user';
 * $config['group_object_class'] = 'group';
 * $config['user_search_filter'] = '';
 * $config['group_search_filter'] = '';
 * $config['login_attribute'] = 'sAMAccountName';
 * $config['schema_type'] = 'ad'; // Could also use rfc2307bis
 * $config['proxy_user'] = 'ProxyUser@mycompany.com';  // Or CN=ProxyUser,CN=Users,DC=mycompany,DC=com
 * $config['proxy_pass'] = 'mysecretpasswordofawesomeness';
 * $config['roles'] = array(1 => 'User', 
 *                        3 => 'Power User',
 *                        5 => 'Administrator');
 * $config['auditlog'] = 'application/logs/audit.log';
 */

?>
