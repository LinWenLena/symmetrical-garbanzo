<?php
/**
 * Template Name: Dokumente
 *
 * @package WordPress
 * @subpackage VereinsApp
 * @since vereinsapp 1.0
 */

/**
 * @var array Contain fields of the page
 */
$fields = get_fields();


/**
 * Gets size of file
 * @param $file_path
 * @return float File size in KB
 */
function file_size($file_path) {
    $head = array_change_key_case(get_headers($file_path, TRUE));
    $filesize = $head['content-length'];
    return round($filesize / 1024, 0);
}

/**
 * Gets name of file
 * @param $file_path
 * @return mixed File name
 */
function file_name($file_path) {
    return pathinfo($file_path)['filename'];
}

function file_extension($file_path) {
    return pathinfo($file_path)['extension'];
}

get_header(); ?>

    <section class="main">
        <div class="content">

                <?php echo $fields['inhalt']; ?>

                <?php

                foreach ($fields['gruppen'] as $gruppe) {
                    echo '<h2>' . $gruppe['gruppe']['name'] . '</h2>';
                    echo '<ul class="files">';
                    foreach ($gruppe['gruppe']['dokumente'] as $dokument) {

                        $file_path = $dokument['dokument']['datei'];

                        echo '
                        <li class="file">
                            <span class="name">' . file_name($file_path) . '</span>
                            <span><b>Dateiname:</b> <a href="' . $file_path . '">' . file_name($file_path) . '.' . file_extension($file_path) . '</a></span>
                            <span><b>Dateigröße:</b> ' . file_size($file_path) . ' KB</span>
                            <span><b>Beschreibung:</b> ' . $dokument['dokument']['beschreibung'] . '</span>
                            <div class="clear"></div>
                        </li>';
                    }
                    echo '</ul>';
                }

                ?>

        </div>
    </section>

<?php get_footer(); ?>