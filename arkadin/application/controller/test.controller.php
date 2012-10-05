<?php

class test extends controller {

    function ldap() {

        $user = 'alequoy';
        $password = 'zeb33tln1$';
        $host = 'FRPARW02';
        $domain = '';
        $basedn = 'dc=mydomain,dc=ex';
        $group = 'SomeGroup';

        $ad = ldap_connect("ldap://{$host}.{$domain}") or die('Could not connect to LDAP server.');
        ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
        @ldap_bind($ad, "{$user}@{$domain}", $password) or die('Could not bind to AD.');
        $userdn = getDN($ad, $user, $basedn);
        if (checkGroupEx($ad, $userdn, getDN($ad, $group, $basedn))) {
//if (checkGroup($ad, $userdn, getDN($ad, $group, $basedn))) {
            echo "You're authorized as " . getCN($userdn);
        } else {
            echo 'Authorization failed';
        }
        ldap_unbind($ad);
    }

}