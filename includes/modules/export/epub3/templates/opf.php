<?php

function refineField($fieldID, $metaKey, &$meta) {
	if ( ! empty( $meta[$metaKey] ) ) {
		$fieldValue=$meta[$metaKey];
		echo "<meta refines='#$fieldID' property='alternate-script' xml:lang='en'>$fieldValue</meta>";
		echo "\n";
		unset( $meta[$metaKey] );
	}
}
function refineFieldWithValue($fieldID, $fieldValue) {
	echo "<meta refines='#$fieldID' property='alternate-script' xml:lang='en'>$fieldValue</meta>";
	echo "\n";
}

// @see: \PressBooks\Export\Export loadTemplate()

if ( ! defined( 'ABSPATH' ) ) exit;

echo '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
?>
<package version="3.0" xmlns="http://www.idpf.org/2007/opf" unique-identifier="PrimaryID">
	<metadata xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:opf="http://www.idpf.org/2007/opf">
		<?php
		// Required, Title
		echo '<dc:title id="bookTitle">' . ( ! empty( $meta['pb_title'] ) ? $meta['pb_title'] : get_bloginfo( 'name' ) ) . '</dc:title>';
		unset( $meta['pb_title'] );
		echo "\n";
		refineField('bookTitle','pb_title_english',$meta);

		//EDITION - added by ODDI
		if ( ! empty( $meta['pb_edition'] ) ) {
			echo '<dc:title id="bookEdition">' . $meta['pb_edition'] . '</dc:title>';
    		echo '<meta refines="#bookEdition" property="title-type">edition</meta>';
    		unset( $meta['pb_edition'] );
		}
		//SUBTITLE - added by ODDI
		if ( ! empty( $meta['pb_subtitle'] ) ) {
			echo '<dc:title id="bookSubtitle">' . $meta['pb_subtitle'] . '</dc:title>';
    		echo '<meta refines="#bookSubtitle" property="title-type">subtitle</meta>';
    		unset( $meta['pb_subtitle'] );
		}
		//SERIES TITLE - added by ODDI
		if ( ! empty( $meta['pb_series_title'] ) ) {
			echo '<meta property="belongs-to-collection" id="bookSeriesTitle">' .$meta['pb_series_title'] . '</meta>';
			echo '<meta refines="#bookSeriesTitle" property="collection-type">series</meta>';
			unset( $meta['pb_series_title'] );
		} 

		$languageCode = 'en';
		if ( ! empty( $meta['pb_language'] ) ) {
			$languageCode = $meta['pb_language'];
		}

		$directionStr="";
		if (\PressBooks\Utility\isRTL($languageCode)) {
			$directionStr = ' page-progression-direction="rtl" ';
		} 

		// Required, Language
		echo '<dc:language>' . ( ! empty( $meta['pb_language'] ) ? $meta['pb_language'] : 'en' ) . '</dc:language>';
		unset( $meta['pb_language'] );
		echo "\n";

		// Required, Modification date
		echo '<meta property="dcterms:modified">' . ( date( 'Y-m-d\TH:i:s\Z' ) ) . '</meta>';
		echo "\n";

		// Required, Primary ID
		if ( ! empty( $meta['pb_ebook_isbn'] ) ) {
			echo '<dc:identifier id="PrimaryID">' . $meta['pb_ebook_isbn'] . '</dc:identifier>';
		} else {
			echo '<dc:identifier id="PrimaryID">' . get_bloginfo( 'url' ) . '</dc:identifier>';
		}
		unset( $meta['pb_ebook_isbn'] );
		echo "\n";

		// UUID - extra field
		if ( ! empty( $meta['pb_uuid'] ) ) {
			echo '<dc:identifier id="uuid_id">' . $meta['pb_uuid'] . '</dc:identifier>';
		} 
		unset( $meta['pb_uuid'] );
		echo "\n";

		// Pick best non-html description
		if ( ! empty( $meta['pb_about_50'] ) ) {
			echo "<dc:description id='bookDescription'>{$meta['pb_about_50']}</dc:description>\n";
			unset( $meta['pb_about_50'] );
			refineField('bookDescription','pb_about_50_english',$meta);
		} elseif ( ! empty( $meta['pb_about_140'] ) ) {
			/* ODDI - this field is hidden on the front end so this should never get used */
			echo "<dc:description>{$meta['pb_about_140']}</dc:description>\n";
			unset( $meta['pb_about_140'] );
		}
		

		// Author
		echo '<dc:creator id="author">';
		if ( ! empty( $meta['pb_author'] ) ) {
			echo $meta['pb_author'];
		} else {
			echo 'Authored by: ' . get_bloginfo( 'url' );
		}
		echo '</dc:creator>' . "\n";
		refineField('author','pb_author_english',$meta);
		
		// Contributing authors
		if ( ! empty( $meta['pb_contributing_authors'] ) ){
			$contributors = explode( ',', $meta['pb_contributing_authors'] );
			foreach ( $contributors as $contributor ){
				echo '<dc:contributor>' . trim( $contributor ) . '</dc:contributor>' . "\n";
			}
			unset( $meta['pb_contributing_authors'] );
		}

		echo '<meta refines="#author" property="file-as">';
		if ( ! empty( $meta['pb_author_file_as'] ) ) {
			echo $meta['pb_author_file_as'];
		} else if ( ! empty( $meta['pb_author'] ) ) {
			echo $meta['pb_author'];
		} else {
			echo 'Authored by: ' . get_bloginfo( 'url' );
		}
		echo '</meta>';
		unset( $meta['pb_author_file_as'], $meta['pb_author'] );

		// Copyright
		if ( ! empty( $meta['pb_copyright_year'] ) || ! empty( $meta['pb_copyright_holder'] ) ) {
			echo '<dc:rights>';
			echo _( 'Copyright' ) . ' &#169; ';
			if ( ! empty( $meta['pb_copyright_year'] ) )
					echo $meta['pb_copyright_year'] . ' ';
			if ( ! empty( $meta['pb_copyright_holder'] ) )
					echo ' ' . __( 'by', 'pressbooks' ) . ' ' . $meta['pb_copyright_holder'];
			if ( ! empty( $do_copyright_license ) ) echo '. ' . $do_copyright_license;

			echo "</dc:rights>\n";
		}
		unset( $meta['pb_copyright_year'], $meta['pb_copyright_holder'] );
		unset( $do_copyright_license );

		// Rest of metadata
		foreach ( $meta as $key => $val ) {
			switch ( $key ) {

				case 'pb_publisher' :
					echo "<dc:publisher id='bookPublisher'>$val</dc:publisher>\n";
					refineFieldWithValue('bookPublisher',$val);
					break;

				case 'pb_publication_date' :
					echo '<dc:date>';
					echo date( 'Y-m-d', ( int ) $val );
					echo "</dc:date>\n";
					break;

				case 'pb_bisac_subject' :
					$subjects = explode( ',', $val );
					foreach ( $subjects as $subject ) {
						echo '<dc:subject>' . trim( $subject ) . "</dc:subject>\n";
					}
					break;

				default:
					// TODO: echo "<!-- $key, $val -->\n";
					break;
			}
		}

		// Required (for Kindle), Cover
		echo '<meta name="cover" content="cover-image" />' . "\n";
		?>
	</metadata>


	<manifest>
		<?php
		echo $manifest_filelist;
		echo $manifest_assets;
		?>
		<item id="toc" properties="nav" href="toc.xhtml" media-type="application/xhtml+xml"/>
		<item id="ncx" href="toc.ncx" media-type="application/x-dtbncx+xml" />
		<?php if ( ! empty( $stylesheet ) ): ?>
		<item id="stylesheet" href="OEBPS/<?php echo $stylesheet; ?>"  media-type="text/css" />
		<?php endif; ?>
	</manifest>


	<spine <?php echo $directionStr; ?> toc="ncx">
		<?php
		foreach ( $manifest as $k => $v ) {

			$linear = 'yes';

			printf( '<itemref idref="%s" linear="%s" />', $k, $linear );
			echo "\n";
		}
		?>
	</spine>

	<guide>
		<reference type="toc" title="Table of Contents" href="OEBPS/table-of-contents.xhtml" />
		<reference type="cover" title="cover" href="OEBPS/front-cover.xhtml" />
		<?php
		/* Set the EPUB's start-point */

		// First, look if the user has set this themselves.
		$start_key = false;
		foreach ( $manifest as $key => $val ) {
			if ( $val['ID'] > 0 && get_post_meta( $val['ID'], 'pb_ebook_start', true ) ) {
				$start_key = $key;
				break;
			}
		}

		// If nothing was found, set � the first page after the table of contents � as start point
		if ( $start_key === false ) {
			$keys = array_keys( $manifest );
			$position = array_search( 'table-of-contents', $keys );
			if ( isset( $keys[$position + 1] ) ) {
				$start_key = $keys[$position + 1];
			}
		}

		if ( $start_key !== false ) {
			printf( '<reference type="text" title="start" href="OEBPS/%s" />', $manifest[$start_key]['filename'] );
			echo "\n";
		}
		?>
	</guide>

</package>