<?php
function gcklr_upload_attachment($file)
{
    $upload_dir = wp_upload_dir();
    $name = $file['name'];
    $tmp_name = $file['tmp_name'];
    $filepath = wp_mkdir_p($upload_dir['path']) ?
        $upload_dir['path'] . '/' . $name :
        $upload_dir['basedir'] . '/' . $name;

    move_uploaded_file($tmp_name, $filepath);

    $filetype = wp_check_filetype($name, null);

    $wp_file_attachment = array(
        'post_mime_type' => $filetype['type'],
        'post_title' => sanitize_file_name($name),
        'post_content' => '',
        'post_status' => 'inherit'
    );

    $file_attachment_id = wp_insert_attachment($wp_file_attachment, $filepath);

    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $file_attachment_data = wp_generate_attachment_metadata($file_attachment_id, $filepath);

    wp_update_attachment_metadata($file_attachment_id, $file_attachment_data);

    return $file_attachment_id;
}
