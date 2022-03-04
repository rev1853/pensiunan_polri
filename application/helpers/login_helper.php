<?php
// TYPE : user,personil
function logged_in($type = 'user'): bool
{
    $ci = get_instance();
    return $ci->session->has_userdata($type);
}

function get_ID($type = 'user')
{
    $ci = get_instance();
    return $ci->session->userdata($type)['id'];
}

function get_name($type)
{
    $ci = get_instance();
    return $ci->session->userdata($type)['nama'];
}
