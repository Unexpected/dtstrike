<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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
 * AuthLDAP Class
 *
 * Simple LDAP Authentication library for Code Igniter.
 *
 * @package         AuthLDAP
 * @author          Greg Wojtak <gwojtak@techrockdo.com>
 * @version         0.7
 * @link            http://www.techrockdo.com/projects/auth_ldap
 * @license         GNU Lesser General Public License (LGPL)
 * @copyright       Copyright © 2010-2013 by Greg Wojtak <gwojtak@techrockdo.com>
 * @todo            Allow for privileges in groups of groups in AD
 * @todo            Rework roles system a little bit to a "auth level" paradigm
 */
class AuthLDAP {
    function __construct() {
        $this->ci =& get_instance();

        log_message('debug', 'AuthLDAP initialization commencing...');

        // Load the session library
        $this->ci->load->library('session');

        // Load the configuration
        $this->ci->load->config('authldap');

        // Load the language file
        // $this->ci->lang->load('authldap_lang', 'english');

        $this->_init();
    }

    
    /**
     * @access private
     * @return void
     */
    private function _init() {
        // Verify that the LDAP extension has been loaded/built-in
        // No sense continuing if we can't
        if (! function_exists('ldap_connect')) {
            show_error('LDAP functionality not present.  Either load the module ldap php module or use a php with ldap support compiled in.');
            log_message('error', 'LDAP functionality not present in php.');
        }

        $this->ldap_uri            = $this->ci->config->item('ldap_uri');
        $this->schema_type         = $this->ci->config->item('schema_type');
        $this->use_tls             = $this->ci->config->item('use_tls');
        $this->search_base         = $this->ci->config->item('search_base');
        $this->user_search_base    = $this->ci->config->item('user_search_base');
        if(empty($this->user_search_base)) {
            $this->user_search_base[0] = $this->search_base;
        }
        $this->group_search_base   = $this->ci->config->item('group_search_base');
        if(empty($this->group_search_base)) {
            $this->group_search_base[0] = $this->search_base;
        }
        $this->user_object_class   = $this->ci->config->item('user_object_class');
        $this->group_object_class  = $this->ci->config->item('group_object_class');
        $this->user_search_filter  = $this->ci->config->item('user_search_filter');
        $this->group_search_filter = $this->ci->config->item('group_search_filter');
        $this->login_attribute     = $this->ci->config->item('login_attribute');
        $this->login_attribute     = strtolower($this->login_attribute);
        $this->proxy_user          = $this->ci->config->item('proxy_user');
        $this->proxy_pass          = $this->ci->config->item('proxy_pass');
        $this->roles               = $this->ci->config->item('roles');
        $this->auditlog            = $this->ci->config->item('auditlog');
        if($this->schema_type == 'rfc2307') {
            $this->member_attribute = 'memberUid';
        }else if($this->schema_type == 'rfc2307bis' || $this->schema_type == 'ad') {
            $this->member_attribute = 'member';
            
        }
        
        $this->role_filter = '';
        foreach($this->roles as $role) {
            $this->role_filter .= '(cn='.$role.')';
        }
        $this->role_filter = '(|'.$this->role_filter.')';
    }

    /**
     * @access public
     * @param string $username
     * @param string $password
     * @return bool 
     */
    function login($username, $password) {
        /*
         * For now just pass this along to _authenticate.  We could do
         * something else here before hand in the future.
         */

        $user_info = $this->_authenticate($username,$password);
        if(empty($user_info['role_level'])) {
            log_message('info', $username." has no role to play.");
            show_error($username.' succssfully authenticated, but is not allowed because the username was not found in an allowed access group.');
        }
        // Record the login
        $this->_audit("Successful login: ".$user_info['cn']."(".$username.") from ".$this->ci->input->ip_address());

        // Set the session data
        $customdata = array('username' => $username,
                            'cn' => $user_info['cn'],
                            'role_name' => $user_info['role_name'],
                            'role_level' => $user_info['role_level'],
                            'logged_in' => TRUE);
    
        $this->ci->session->set_userdata($customdata);
        return TRUE;
    }

    /**
     * @access public
     * @return bool
     */
    function is_authenticated() {
        if($this->ci->session->userdata('logged_in')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * @access public
     */
    function logout() {
        // Just set logged_in to FALSE and then destroy everything for good measure
        $this->ci->session->set_userdata(array('logged_in' => FALSE));
        $this->ci->session->sess_destroy();
    }

    /**
     * @access private
     * @param string $msg
     * @return bool
     */
    private function _audit($msg){
        $date = date('Y/m/d H:i:s');
        if( ! file_put_contents($this->auditlog, $date.": ".$msg."\n",FILE_APPEND)) {
            log_message('info', 'Error opening audit log '.$this->auditlog);
            return FALSE;
        }
        return TRUE;
    }

    /**
     * @access private
     * @param string $username
     * @param string $password
     * @return array 
     */
    private function _authenticate($username, $password) {        
        foreach($this->ldap_uri as $uri) {
            $this->ldapconn = ldap_connect($uri);
            if($this->ldapconn) {
               break;
            }else {
                log_message('info', 'Error connecting to '.$uri);
            }
        }
        // At this point, $this->ldapconn should be set.  If not... DOOM!
        if(! $this->ldapconn) {
            log_message('error', "Couldn't connect to any LDAP servers.  Bailing...");
            show_error('Error connecting to your LDAP server(s).  Please check the connection and try again.'.ldap_error($this->ldapconn));
        }
        
        // Start TLS if requested
        if($this->use_tls) {
            if(! ldap_start_tls($this->ldapconn)) {
                log_message('error', "Couldn't properly initialize a TLS connection to your LDAP server.");
                log_message('error', 'Hopefully this helps: '.ldap_error($this->ldapconn).' (Errno: '.ldap_errno($this->ldapconn).')');
                show_error("<h3>Error starting TLS session.</h3>\n<p>LDAP Error: ".ldap_errno($this->ldapconn)."  ".ldap_error($this->ldapconn()));            
            }
        }

        // We've connected, now we can attempt the login...
        
        // These two ldap_set_options are needed for binding to AD properly
        // They should also work with any modern LDAP service.
        ldap_set_option($this->ldapconn, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($this->ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        
        // Find the DN of the user we are binding as
        // If proxy_user and proxy_pass are set, use those, else bind anonymously
        if($this->proxy_user) {
            $bind = ldap_bind($this->ldapconn, $this->proxy_user, $this->proxy_pass);
        }else {
            $bind = ldap_bind($this->ldapconn);
        }

        if(!$bind){
            log_message('error', 'Unable to perform anonymous/proxy bind');
            show_error('Unable to bind for user id lookup');
        }

        log_message('debug', 'Successfully bound to directory.  Performing dn lookup for '.$username);
        $filter = '(&(objectClass='.$this->user_object_class.')('.$this->login_attribute.'='.$username.'))';
        foreach($this->user_search_base as $usb) {
            $search = ldap_search($this->ldapconn, $usb, $filter, 
                array('dn', $this->login_attribute, 'cn'));
            $entries = ldap_get_entries($this->ldapconn, $search);
            if(isset($entries[0]['dn'])) {
                $binddn = $entries[0]['dn'];
                break;
            }
        }

        if(empty($binddn)) {
            show_error("Error looking up DN for ".$username.": ".ldap_error($this->ldapconn));
        }
        // Now actually try to bind as the user
        $bind = ldap_bind($this->ldapconn, $binddn, $password);
        if(! $bind) {
            $this->_audit("Failed login attempt: ".$username." from ".$_SERVER['REMOTE_ADDR']);
            show_error('Unable to bind to server: Invalid credentials for '.$username);
        }
        $cn = $entries[0]['cn'][0];
        $dn = stripslashes($entries[0]['dn']);
        $id = $entries[0][$this->login_attribute][0];
        
        if($this->schema_type == 'rfc2307') {
            $get_role_arg = $id;               
        }else if($this->schema_type == 'rfc2307bis' || $this->schema_type == 'ad') {
            $get_role_arg = $this->ldap_escape($dn, false);
        }
        $role_level = $this->_get_role($get_role_arg);
        return array('cn' => $cn, 'dn' => $entries[0]['dn'], 'id' => $id,
            'role_name' => $this->roles[$role_level],
            'role_level' => $role_level);
    }

    /**
     * @access private
     * @param string $str
     * @param bool $for_dn
     * @return string 
     */
    private function ldap_escape($str, $for_dn = false) {
        /**
         * This function courtesy of douglass_davis at earthlink dot net
         * Posted in comments at
         * http://php.net/manual/en/function.ldap-search.php on 2009/04/08
         */
        // see:
        // RFC2254
        // http://msdn.microsoft.com/en-us/library/ms675768(VS.85).aspx
        // http://www-03.ibm.com/systems/i/software/ldap/underdn.html  
        
        if  ($for_dn) {
            $metaChars = array(',','=', '+', '<','>',';', '\\', '"', '#');
        }else {
            $metaChars = array('*', '(', ')', '\\', chr(0));
        }

        $quotedMetaChars = array();
        foreach ($metaChars as $key => $value) $quotedMetaChars[$key] = '\\'.str_pad(dechex(ord($value)), 2, '0');
        $str=str_replace($metaChars,$quotedMetaChars,$str); //replace them
        return ($str);  
    }
    
    private function _get_role_level($role_name) {
        foreach($this->roles as $level => $name) {
            if(strtolower($name) == $role_name) {
                return $level;
            }
        }
    }
    
    /**
     * @access private
     * @param string $username
     * @return int
     */
    private function _get_role($username) {        
        $filter = '(&('.$this->member_attribute.'='.$username.')'.$this->role_filter.')';
        $role = '';
        
        foreach($this->group_search_base as $gsb) {
            $search = ldap_search($this->ldapconn, $gsb, $filter, array('cn'));
            if($search) {
                $results = ldap_get_entries($this->ldapconn, $search);
                if($results['count'] != 0) {                    
                    for($i = 0; $i < $results['count']; $i++) {
                        $role = $results[$i]['cn'][0];
                        if($role) {
                            return $this->_get_role_level(strtolower($role));
                        }
                    }
                }else {
                    log_message('error', "Error finding any groups");
                    show_error("Group search successful but no memberships were found");
                }
            }
        }     
        log_message('error', "Error searching for group:".ldap_error($this->ldapconn));
        show_error('Couldn\'t find groups: '.ldap_error($this->ldapconn));
        return false;
    }
}

?>
