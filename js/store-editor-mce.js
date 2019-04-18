/* global tinymce */
tinymce.PluginManager.add( 'ecwid', function( editor ) {
	var toolbarActive = false;

	function editStore( img ) {
		ecwid_open_store_popup();
	}

	function removeImage( node ) {
		var wrap;

		if ( node.nodeName === 'DIV' && editor.dom.hasClass( node, 'ecwid-store-wrap' ) ) {
			wrap = node;
		} else if ( node.nodeName === 'IMG' || node.nodeName === 'DT' || node.nodeName === 'A' ) {
			wrap = editor.dom.getParent( node, 'div.ecwid-store-wrap' );
		}

		if ( wrap ) {
			if ( wrap.nextSibling ) {
				editor.selection.select( wrap.nextSibling );
			} else if ( wrap.previousSibling ) {
				editor.selection.select( wrap.previousSibling );
			} else {
				editor.selection.select( wrap.parentNode );
			}

			editor.selection.collapse( true );
			editor.nodeChanged();
			editor.dom.remove( wrap );
		} else {
			editor.dom.remove( node );
		}
		removeToolbar();

		editor.dom.remove(editor.dom.select('#ecwid-edit-store-button'));
	}

	function addToolbar( node ) {
		var rectangle, toolbarHtml, toolbar, left,
				dom = editor.dom;

		removeToolbar(node);

		// Don't add to other images
		if ( ! node || node.nodeName !== 'IMG' || node.className.indexOf('ecwid-store-editor') == -1 ) {
			return;
		}

		dom.setAttrib( node, 'data-ecwid-store-select', 1 );
		rectangle = dom.getRect( node );

		toolbarHtml = '<div class="dashicons dashicons-no-alt remove" data-mce-bogus="1"></div>';

		toolbar = dom.create( 'div', {
			'id': 'ecwid-store-toolbar',
			'data-mce-bogus': '1',
			'contenteditable': false
		}, toolbarHtml );

		if ( editor.rtl ) {
			left = rectangle.x + rectangle.w - 82;
		} else {
			left = rectangle.x;
		}

		editor.getBody().appendChild( toolbar );
		dom.setStyles( toolbar, {
			top: rectangle.y,
			left: left
		});

		toolbarActive = true;
	}

	this.addToolbar = function() {
		addToolbar(
			jQuery(editor.dom.doc.body).find('.ecwid-store-editor').get(0)
		);
	}

	function removeToolbar(parentNode) {

		if (parentNode && editor.dom.getAttrib( parentNode, 'class') == 'ecwid-store-editor' ) {
			var toolbar = editor.dom.get( 'wp-image-toolbar' );
			if ( toolbar ) {
				editor.dom.remove( toolbar );
			}
		}

		var toolbar = editor.dom.get( 'ecwid-store-toolbar' );
		if ( toolbar ) {
			editor.dom.remove( toolbar );
		}

		// also remove image toolbar

		editor.dom.setAttrib( editor.dom.select( 'img[data-ecwid-store-select]' ), 'data-ecwid-store-select', null );

		toolbarActive = false;
	}

	editor.onInit.add(function(editor) {

		dom = editor.dom;
		dom.bind( editor.getDoc(), 'dragstart', function( event ) {
			var node = editor.selection.getNode();

			// Prevent dragging images out of the caption elements
			if ( node.nodeName === 'IMG' && dom.getParent( node, '.wp-caption' ) ) {
				event.preventDefault();
			}

			// Remove toolbar to avoid an orphaned toolbar when dragging an image to a new location
			removeToolbar();
		});
	});

	editor.onKeyUp.add( function( editor, event ) {
		var node, wrap, P, spacer,
				selection = editor.selection,
				keyCode = event.keyCode,
				dom = editor.dom;

		if ( keyCode === 46 || keyCode === 8 ) {
			checkEcwid();
		}
	});

	editor.onKeyDown.add( function( editor, event ) {
		var node, wrap, P, spacer,
				selection = editor.selection,
				keyCode = event.keyCode,
				dom = editor.dom;

		if ( keyCode == 27 ) {
			jQuery('#ecwid-store-popup-content').removeClass('open');
			return false;
		}

		if ( keyCode === 46 || keyCode === 8 ) {
			node = selection.getNode();

			if ( node.nodeName === 'DIV' && dom.hasClass( node, 'ecwid-store-wrap' ) ) {
				wrap = node;
			} else if ( node.nodeName === 'IMG' ) {
				wrap = dom.getParent( node, 'div.ecwid-store-wrap' );
			}

			if ( wrap ) {
				dom.events.cancel( event );
				removeImage( node );
				editor.dom.remove(editor.dom.select('#ecwid-edit-store-button'));

				return false;
			}

			removeToolbar();
		}

		// Key presses will replace the image so we need to remove the toolbar
		if ( toolbarActive ) {
			if ( event.ctrlKey || event.metaKey || event.altKey ||
					( keyCode < 48 && keyCode > 90 ) || keyCode > 186 ) {
				return;
			}

			removeToolbar();
			editor.dom.remove(editor.dom.select('#ecwid-edit-store-button'));

		}

	});

	editor.onMouseDown.add( function( editor, event ) {
		if ( editor.dom.getParent( event.target, '#ecwid-store-toolbar' ) ) {
			if ( tinymce.Env.ie ) {
				// Stop IE > 8 from making the wrapper resizable on mousedown
				event.preventDefault();
			}
		} else if ( event.target.nodeName !== 'IMG' ) {
			removeToolbar();
			
			if (event.target.nodeName == 'INPUT' && event.target.id == 'ecwid-edit-store-button') {
				ecwid_open_store_popup();
			}
		}
	});

	editor.onMouseUp.add( function( editor, event ) {
		var image,
				node = event.target,
				dom = editor.dom;

		// Don't trigger on right-click
		if ( event.button && event.button > 1 ) {
			return;
		}

		if ( node.nodeName === 'DIV' && dom.getParent( node, '#ecwid-store-toolbar' ) ) {
			image = dom.select( 'img[data-ecwid-store-select]' )[0];

			if ( image ) {
				editor.selection.select( image );

				if ( dom.hasClass( node, 'remove' ) ) {
					removeImage( image );
				} else if ( dom.hasClass( node, 'edit' ) ) {
					editStore( image );
				}
			}
		} else if ( node.nodeName === 'IMG' && ! editor.dom.getAttrib( node, 'data-ecwid-store-select' ) ) {
			addToolbar( node );
		} else if ( node.nodeName !== 'IMG' ) {
			removeToolbar();
		}
	});

		// Replace Read More/Next Page tags with images
	editor.onBeforeSetContent.add( function( editor, e ) {
		if ( e.content ) {
			
			var found = ecwid_get_store_shortcode(e.content);

			if (!found) return;

			var start = found.index;
			var end = found.index + found.content.length;
			
			var content = e.content;
            
			var gutenStart = content.indexOf('<!-- wp:ecwid/store-block');
			var gutenEnd = content.indexOf('<!-- /wp:ecwid/store-block -->') + '<!-- /wp:ecwid/store-block -->'.length;
			
			
            if (gutenStart != -1 && gutenEnd != -1) {
            	var gutenberged = content.substr(gutenStart, gutenEnd);
            	if (gutenberged.indexOf(found.content) != -1) {
            		start = gutenStart;
            		end = gutenEnd;
				}
			}

			var store = '<img height="200" width="100%" data-ecwid-shortcode="' + window.encodeURIComponent(found.content) + '" src="' + ecwid_store_svg + '" data-mce-placeholder="true" data-mce-resize="false" class="ecwid-store-editor mceItem">';

			e.content = e.content.substr(0, start) + store + e.content.substr(end);
		}
	});

	// Replace images with tags
	editor.onPostProcess.add( function( editor, e ) {

        if ( e.get ) {

			return e.content = e.content.replace( /(<img [^>]*data-ecwid-shortcode=[^>]+>)/g, function( match, image ) {

				var data = window.decodeURIComponent(jQuery(image).attr('data-ecwid-shortcode'));

				if ( data ) {
					return data;
				}

				return match;
			});
		}
	});
});