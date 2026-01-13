<?php
// guard_mitra.php

function profilMitraLengkap($koneksi, $id_user)
{
    $u = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT nama, username, no_hp 
        FROM users 
        WHERE id_user='$id_user'
    "));

    if (
        empty($u['nama']) ||
        empty($u['username']) ||
        empty($u['no_hp'])
    ) return false;

    $m = mysqli_fetch_assoc(mysqli_query($koneksi, "
        SELECT alamat_tinggal, lokasi_stand, link_gmaps_stand
        FROM mitra
        WHERE id_user='$id_user'
    "));

    if (!$m) return false;

    if (
        empty($m['alamat_tinggal']) ||
        empty($m['lokasi_stand']) ||
        empty($m['link_gmaps_stand'])
    ) return false;

    return true;
}
