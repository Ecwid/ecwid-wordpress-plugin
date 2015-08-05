/* global tinymce */
tinymce.PluginManager.add( 'ecwid', function( editor ) {
	var toolbarActive = false;

//	function parseShortcode( content ) {
//		return content.replace( /(?:<p>)?\[(?:wp_)?caption([^\]]+)\]([\s\S]+?)\[\/(?:wp_)?caption\](?:<\/p>)?/g, function( a, b, c ) {
//			var id, cls, w, cap, img, width,
//					trim = tinymce.trim;
//
//			id = b.match( /id=['"]([^'"]*)['"] ?/ );
//			if ( id ) {
//				b = b.replace( id[0], '' );
//			}
//
//			cls = b.match( /align=['"]([^'"]*)['"] ?/ );
//			if ( cls ) {
//				b = b.replace( cls[0], '' );
//			}
//
//			w = b.match( /width=['"]([0-9]*)['"] ?/ );
//			if ( w ) {
//				b = b.replace( w[0], '' );
//			}
//
//			c = trim( c );
//			img = c.match( /((?:<a [^>]+>)?<img [^>]+>(?:<\/a>)?)([\s\S]*)/i );
//
//			if ( img && img[2] ) {
//				cap = trim( img[2] );
//				img = trim( img[1] );
//			} else {
//				// old captions shortcode style
//				cap = trim( b ).replace( /caption=['"]/, '' ).replace( /['"]$/, '' );
//				img = c;
//			}
//
//			id = ( id && id[1] ) ? id[1] : '';
//			cls = ( cls && cls[1] ) ? cls[1] : 'alignnone';
//
//			if ( ! w && img ) {
//				w = img.match( /width=['"]([0-9]*)['"]/ );
//			}
//
//			if ( w && w[1] ) {
//				w = w[1];
//			}
//
//			if ( ! w || ! cap ) {
//				return c;
//			}
//
//			width = parseInt( w, 10 );
//			if ( ! editor.getParam( 'wpeditimage_html5_captions' ) ) {
//				width += 10;
//			}
//
//			return '<div class="mceTemp"><dl id="'+ id +'" class="wp-caption '+ cls +'" style="width: '+ width +'px">' +
//					'<dt class="wp-caption-dt">'+ img +'</dt><dd class="wp-caption-dd">'+ cap +'</dd></dl></div>';
//		});
//	}
//
//	function getShortcode( content ) {
//		return content.replace( /<div (?:id="attachment_|class="mceTemp)[^>]*>([\s\S]+?)<\/div>/g, function( a, b ) {
//			var out = '';
//
//			if ( b.indexOf('<img ') === -1 ) {
//				// Broken caption. The user managed to drag the image out?
//				// Try to return the caption text as a paragraph.
//				out = b.match( /<dd [^>]+>([\s\S]+?)<\/dd>/i );
//
//				if ( out && out[1] ) {
//					return '<p>' + out[1] + '</p>';
//				}
//
//				return '';
//			}
//
//			out = b.replace( /<dl ([^>]+)>\s*<dt [^>]+>([\s\S]+?)<\/dt>\s*<dd [^>]+>([\s\S]*?)<\/dd>\s*<\/dl>/gi, function( a, b, c, cap ) {
//				var id, cls, w;
//
//				w = c.match( /width="([0-9]*)"/ );
//				w = ( w && w[1] ) ? w[1] : '';
//
//				if ( ! w || ! cap ) {
//					return c;
//				}
//
//				id = b.match( /id="([^"]*)"/ );
//				id = ( id && id[1] ) ? id[1] : '';
//
//				cls = b.match( /class="([^"]*)"/ );
//				cls = ( cls && cls[1] ) ? cls[1] : '';
//				cls = cls.match( /align[a-z]+/ ) || 'alignnone';
//
//				cap = cap.replace( /\r\n|\r/g, '\n' ).replace( /<[a-zA-Z0-9]+( [^<>]+)?>/g, function( a ) {
//					// no line breaks inside HTML tags
//					return a.replace( /[\r\n\t]+/, ' ' );
//				});
//
//				// convert remaining line breaks to <br>
//				cap = cap.replace( /\s*\n\s*/g, '<br />' );
//
//				return '[caption id="'+ id +'" align="'+ cls +'" width="'+ w +'"]'+ c +' '+ cap +'[/caption]';
//			});
//
//			if ( out.indexOf('[caption') !== 0 ) {
//				// the caption html seems broken, try to find the image that may be wrapped in a link
//				// and may be followed by <p> with the caption text.
//				out = b.replace( /[\s\S]*?((?:<a [^>]+>)?<img [^>]+>(?:<\/a>)?)(<p>[\s\S]*<\/p>)?[\s\S]*/gi, '<p>$1</p>$2' );
//			}
//
//			return out;
//		});
//	}
//
//	function extractImageData( imageNode ) {
//		var classes, extraClasses, metadata, captionBlock, caption, link, width, height,
//				dom = editor.dom,
//				isIntRegExp = /^\d+$/;
//
//		// default attributes
//		metadata = {
//			attachment_id: false,
//			size: 'custom',
//			caption: '',
//			align: 'none',
//			extraClasses: '',
//			link: false,
//			linkUrl: '',
//			linkClassName: '',
//			linkTargetBlank: false,
//			linkRel: '',
//			title: ''
//		};
//
//		metadata.url = dom.getAttrib( imageNode, 'src' );
//		metadata.alt = dom.getAttrib( imageNode, 'alt' );
//		metadata.title = dom.getAttrib( imageNode, 'title' );
//
//		width = dom.getAttrib( imageNode, 'width' );
//		height = dom.getAttrib( imageNode, 'height' );
//
//		if ( ! isIntRegExp.test( width ) || parseInt( width, 10 ) < 1 ) {
//			width = imageNode.naturalWidth || imageNode.width;
//		}
//
//		if ( ! isIntRegExp.test( height ) || parseInt( height, 10 ) < 1 ) {
//			height = imageNode.naturalHeight || imageNode.height;
//		}
//
//		metadata.customWidth = metadata.width = width;
//		metadata.customHeight = metadata.height = height;
//
//		classes = tinymce.explode( imageNode.className, ' ' );
//		extraClasses = [];
//
//		tinymce.each( classes, function( name ) {
//
//			if ( /^wp-image/.test( name ) ) {
//				metadata.attachment_id = parseInt( name.replace( 'wp-image-', '' ), 10 );
//			} else if ( /^align/.test( name ) ) {
//				metadata.align = name.replace( 'align', '' );
//			} else if ( /^size/.test( name ) ) {
//				metadata.size = name.replace( 'size-', '' );
//			} else {
//				extraClasses.push( name );
//			}
//
//		} );
//
//		metadata.extraClasses = extraClasses.join( ' ' );
//
//		// Extract caption
//		captionBlock = dom.getParents( imageNode, '.wp-caption' );
//
//		if ( captionBlock.length ) {
//			captionBlock = captionBlock[0];
//
//			classes = captionBlock.className.split( ' ' );
//			tinymce.each( classes, function( name ) {
//				if ( /^align/.test( name ) ) {
//					metadata.align = name.replace( 'align', '' );
//				}
//			} );
//
//			caption = dom.select( 'dd.wp-caption-dd', captionBlock );
//			if ( caption.length ) {
//				caption = caption[0];
//
//				metadata.caption = editor.serializer.serialize( caption )
//						.replace( /<br[^>]*>/g, '$&\n' ).replace( /^<p>/, '' ).replace( /<\/p>$/, '' );
//			}
//		}
//
//		// Extract linkTo
//		if ( imageNode.parentNode && imageNode.parentNode.nodeName === 'A' ) {
//			link = imageNode.parentNode;
//			metadata.linkUrl = dom.getAttrib( link, 'href' );
//			metadata.linkTargetBlank = dom.getAttrib( link, 'target' ) === '_blank' ? true : false;
//			metadata.linkRel = dom.getAttrib( link, 'rel' );
//			metadata.linkClassName = link.className;
//		}
//
//		return metadata;
//	}
//
//	function hasTextContent( node ) {
//		return node && !! ( node.textContent || node.innerText );
//	}
//
//	function updateImage( imageNode, imageData ) {
//		var classes, className, node, html, parent, wrap, linkNode,
//				captionNode, dd, dl, id, attrs, linkAttrs, width, height,
//				dom = editor.dom;
//
//		classes = tinymce.explode( imageData.extraClasses, ' ' );
//
//		if ( ! classes ) {
//			classes = [];
//		}
//
//		if ( ! imageData.caption ) {
//			classes.push( 'align' + imageData.align );
//		}
//
//		if ( imageData.attachment_id ) {
//			classes.push( 'wp-image-' + imageData.attachment_id );
//			if ( imageData.size && imageData.size !== 'custom' ) {
//				classes.push( 'size-' + imageData.size );
//			}
//		}
//
//		width = imageData.width;
//		height = imageData.height;
//
//		if ( imageData.size === 'custom' ) {
//			width = imageData.customWidth;
//			height = imageData.customHeight;
//		}
//
//		attrs = {
//			src: imageData.url,
//			width: width || null,
//			height: height || null,
//			alt: imageData.alt,
//			title: imageData.title || null,
//			'class': classes.join( ' ' ) || null
//		};
//
//		dom.setAttribs( imageNode, attrs );
//
//		linkAttrs = {
//			href: imageData.linkUrl,
//			rel: imageData.linkRel || null,
//			target: imageData.linkTargetBlank ? '_blank': null,
//			'class': imageData.linkClassName || null
//		};
//
//		if ( imageNode.parentNode && imageNode.parentNode.nodeName === 'A' && ! hasTextContent( imageNode.parentNode ) ) {
//			// Update or remove an existing link wrapped around the image
//			if ( imageData.linkUrl ) {
//				dom.setAttribs( imageNode.parentNode, linkAttrs );
//			} else {
//				dom.remove( imageNode.parentNode, true );
//			}
//		} else if ( imageData.linkUrl ) {
//			if ( linkNode = dom.getParent( imageNode, 'a' ) ) {
//				// The image is inside a link together with other nodes,
//				// or is nested in another node, move it out
//				dom.insertAfter( imageNode, linkNode );
//			}
//
//			// Add link wrapped around the image
//			linkNode = dom.create( 'a', linkAttrs );
//			imageNode.parentNode.insertBefore( linkNode, imageNode );
//			linkNode.appendChild( imageNode );
//		}
//
//		captionNode = editor.dom.getParent( imageNode, '.mceTemp' );
//
//		if ( imageNode.parentNode && imageNode.parentNode.nodeName === 'A' && ! hasTextContent( imageNode.parentNode ) ) {
//			node = imageNode.parentNode;
//		} else {
//			node = imageNode;
//		}
//
//		if ( imageData.caption ) {
//
//			id = imageData.attachment_id ? 'attachment_' + imageData.attachment_id : null;
//			className = 'wp-caption align' + ( imageData.align || 'none' );
//
//			if ( ! editor.getParam( 'wpeditimage_html5_captions' ) ) {
//				width = parseInt( width, 10 );
//				width += 10;
//			}
//
//			if ( captionNode ) {
//				dl = dom.select( 'dl.wp-caption', captionNode );
//
//				if ( dl.length ) {
//					dom.setAttribs( dl, {
//						id: id,
//						'class': className,
//						style: 'width: ' + width + 'px'
//					} );
//				}
//
//				dd = dom.select( '.wp-caption-dd', captionNode );
//
//				if ( dd.length ) {
//					dom.setHTML( dd[0], imageData.caption );
//				}
//
//			} else {
//				id = id ? 'id="'+ id +'" ' : '';
//
//				// should create a new function for generating the caption markup
//				html =  '<dl ' + id + 'class="' + className +'" style="width: '+ width +'px">' +
//						'<dt class="wp-caption-dt">' + dom.getOuterHTML( node ) + '</dt><dd class="wp-caption-dd">'+ imageData.caption +'</dd></dl>';
//
//				if ( parent = dom.getParent( node, 'p' ) ) {
//					wrap = dom.create( 'div', { 'class': 'mceTemp' }, html );
//					parent.parentNode.insertBefore( wrap, parent );
//					dom.remove( node );
//
//					if ( dom.isEmpty( parent ) ) {
//						dom.remove( parent );
//					}
//				} else {
//					dom.setOuterHTML( node, '<div class="mceTemp">' + html + '</div>' );
//				}
//			}
//		} else if ( captionNode ) {
//			// Remove the caption wrapper and place the image in new paragraph
//			parent = dom.create( 'p' );
//			captionNode.parentNode.insertBefore( parent, captionNode );
//			parent.appendChild( node );
//			dom.remove( captionNode );
//		}
//
//		if ( wp.media.events ) {
//			wp.media.events.trigger( 'editor:image-update', {
//				editor: editor,
//				metadata: imageData,
//				image: imageNode
//			} );
//		}
//
//		editor.nodeChanged();
//		// Refresh the toolbar
//		addToolbar( imageNode );
//	}

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

			found = ecwid_get_store_shortcode(e.content);

			if (!found) return;

			var content = e.content;

			var store = '<img height="200" width="100%" data-ecwid-shortcode="' + window.encodeURIComponent(found.content) + '" src="' + ecwid_store_svg + '" data-mce-placeholder="true" data-mce-resize="false" class="ecwid-store-editor mceItem">';

			e.content = e.content.substr(0, found.index) + store + e.content.substr(found.index + found.content.length);
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