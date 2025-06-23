/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./js/gutenberg/src/buynow/block.jsx":
/*!*******************************************!*\
  !*** ./js/gutenberg/src/buynow/block.jsx ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./style.scss */ "./js/gutenberg/src/buynow/style.scss");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./editor.scss */ "./js/gutenberg/src/buynow/editor.scss");
/* harmony import */ var _includes_icons_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../includes/icons.js */ "./js/gutenberg/src/includes/icons.js");
/* harmony import */ var _includes_controls_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../includes/controls.js */ "./js/gutenberg/src/includes/controls.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__);









(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)('ec-store/buynow', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Buy Now Button', 'ecwid-shopping-cart'),
  icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_6__.EcwidIcons.button,
  category: 'ec-store',
  // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  attributes: {
    id: {
      type: 'integer'
    },
    show_price_on_button: {
      type: 'boolean',
      default: true
    },
    center_align: {
      type: 'boolean',
      default: true
    }
  },
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Display a buy button', 'ecwid-shopping-cart'),
  supports: {
    customClassName: false,
    className: false,
    html: false,
    align: true,
    alignWide: false,
    inserter: EcwidGutenbergParams.isApiAvailable,
    isPrivate: !EcwidGutenbergParams.isApiAvailable
  },
  example: {},
  /**
   * The edit function describes the structure of your block in the context of the editor.
   * This represents what the editor will render when the block is used.
   *
   * The "edit" property must be a valid function.
   *
   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
   */
  edit: function (props) {
    const {
      attributes
    } = props;
    const saveCallback = function (params) {
      const attributes = {
        'id': params.newProps.id
      };
      EcwidGutenbergParams.products[params.newProps.id] = {
        name: params.newProps.product.name,
        imageUrl: params.newProps.product.thumb
      };
      params.originalProps.setAttributes(attributes);
    };
    const editor = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsxs)(_includes_controls_js__WEBPACK_IMPORTED_MODULE_7__.EcwidProductBrowserBlock, {
      props: props,
      attributes: attributes,
      icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_6__.EcwidIcons.button,
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Buy Now Button', 'ecwid-shopping-cart'),
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("div", {
        className: "ec-store-block-cart-page",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("div", {
          className: "ec-store-block-buynow-preview"
        })
      }), !attributes.id && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("div", {
        className: "button-container",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("button", {
          className: "button ec-store-block-button",
          onClick: () => {
            var params = {
              'saveCallback': saveCallback,
              'props': props
            };
            ecwid_open_product_popup(params);
          },
          children: EcwidGutenbergParams.chooseProduct
        })
      })]
    });
    function buildToggle(props, name, label) {
      return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
        label: label,
        checked: props.attributes[name],
        onChange: () => props.setAttributes({
          [name]: !props.attributes[name]
        }),
        __nextHasNoMarginBottom: true
      });
    }
    function openEcwidProductPopup(props) {
      ecwid_open_product_popup({
        'saveCallback': saveCallback,
        'props': props
      });
    }
    return [editor, /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsxs)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, {
      children: [attributes.id && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("div", {
          className: "ec-store-inspector-row",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("label", {
            className: "ec-store-inspector-subheader",
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Linked product', 'ecwid-shopping-cart')
          })
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsxs)("div", {
          className: "ec-store-inspector-row",
          children: [EcwidGutenbergParams.products && EcwidGutenbergParams.products[attributes.id] && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("label", {
            children: EcwidGutenbergParams.products[attributes.id].name
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("button", {
            className: "button",
            onClick: () => openEcwidProductPopup(props),
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Change', 'ecwid-shopping-cart')
          })]
        })]
      }), !attributes.id && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("button", {
          className: "button",
          onClick: () => openEcwidProductPopup(props),
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Choose product', 'ecwid-shopping-cart')
        })
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Appearance', 'ecwid-shopping-cart'),
        initialOpen: false,
        children: [buildToggle(props, 'show_price_on_button', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Show price inside the «Buy now» button', 'ecwid-shopping-cart')), buildToggle(props, 'center_align', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Center align on a page', 'ecwid-shopping-cart'))]
      })]
    })];
  },
  save: function (props) {
    return false;
  }
});

/***/ }),

/***/ "./js/gutenberg/src/buynow/editor.scss":
/*!*********************************************!*\
  !*** ./js/gutenberg/src/buynow/editor.scss ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/buynow/style.scss":
/*!********************************************!*\
  !*** ./js/gutenberg/src/buynow/style.scss ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/cart-page/block.jsx":
/*!**********************************************!*\
  !*** ./js/gutenberg/src/cart-page/block.jsx ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./style.scss */ "./js/gutenberg/src/cart-page/style.scss");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./editor.scss */ "./js/gutenberg/src/cart-page/editor.scss");
/* harmony import */ var _includes_icons_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../includes/icons.js */ "./js/gutenberg/src/includes/icons.js");
/* harmony import */ var _includes_controls_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../includes/controls.js */ "./js/gutenberg/src/includes/controls.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_7__);








const blockName = 'ec-store/cart-page';
const blockParams = EcwidGutenbergParams.blockParams[blockName];

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)('ec-store/cart-page', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Cart and Checkout', 'ecwid-shopping-cart'),
  // Block title.
  icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_5__.EcwidIcons.cartPage,
  category: 'ec-store',
  // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  attributes: blockParams.attributes,
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Display shopping cart and checkout page', 'ecwid-shopping-cart'),
  supports: {
    customClassName: false,
    className: false,
    html: false,
    multiple: false,
    inserter: EcwidGutenbergParams.isWidgetsScreen ? false : true
  },
  example: {},
  /**
   * The edit function describes the structure of your block in the context of the editor.
   * This represents what the editor will render when the block is used.
   *
   * The "edit" property must be a valid function.
   *
   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
   */
  edit: function (props) {
    const {
      attributes
    } = props;
    const editor = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_7__.jsx)(_includes_controls_js__WEBPACK_IMPORTED_MODULE_6__.EcwidProductBrowserBlock, {
      props: props,
      attributes: attributes,
      icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_5__.EcwidIcons.cartPage,
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Cart and Checkout', 'ecwid-shopping-cart'),
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_7__.jsx)("div", {
        className: "ec-store-block-cart-page",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_7__.jsx)("div", {
          className: "ec-store-block-cart-page-preview"
        })
      })
    });
    return [editor];
  },
  save: function (props) {
    return null;
  }
});

/***/ }),

/***/ "./js/gutenberg/src/cart-page/editor.scss":
/*!************************************************!*\
  !*** ./js/gutenberg/src/cart-page/editor.scss ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/cart-page/style.scss":
/*!***********************************************!*\
  !*** ./js/gutenberg/src/cart-page/style.scss ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/categories/block.jsx":
/*!***********************************************!*\
  !*** ./js/gutenberg/src/categories/block.jsx ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./style.scss */ "./js/gutenberg/src/categories/style.scss");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./editor.scss */ "./js/gutenberg/src/categories/editor.scss");
/* harmony import */ var _includes_icons_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../includes/icons.js */ "./js/gutenberg/src/includes/icons.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__);






if (!EcwidGutenbergParams.isDemoStore) {
  const {
    InspectorControls
  } = wp.blockEditor;
  const {
    PanelBody
  } = wp.components;
  const blockName = 'ec-store/categories';
  const blockParams = EcwidGutenbergParams.blockParams[blockName];

  /**
   * Register: aa Gutenberg Block.
   *
   * Registers a new block provided a unique name and an object defining its
   * behavior. Once registered, the block is made editor as an option to any
   * editor interface where blocks are implemented.
   *
   * @link https://wordpress.org/gutenberg/handbook/block-api/
   * @param  {string}   name     Block name.
   * @param  {Object}   settings Block settings.
   * @return {?WPBlock}          The block, if it has been successfully
   *                             registered; otherwise `undefined`.
   */
  (0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)('ec-store/categories', {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Store Categories Menu', 'ecwid-shopping-cart'),
    icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_4__.EcwidIcons.categories,
    category: 'ec-store',
    // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
    description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Display categories navigation bar', 'ecwid-shopping-cart'),
    supports: {
      customClassName: false,
      className: false,
      html: false,
      multiple: false,
      inserter: EcwidGutenbergParams.isApiAvailable,
      isPrivate: !EcwidGutenbergParams.isApiAvailable
    },
    example: {},
    /**
     * The edit function describes the structure of your block in the context of the editor.
     * This represents what the editor will render when the block is used.
     *
     * The "edit" property must be a valid function.
     *
     * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
     */
    edit: function (props) {
      const {
        attributes
      } = props;
      const editor = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
        className: "ec-store-block ec-store-block-categories",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-block-header",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Categories', 'ecwid-shopping-cart')
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-categories-menu"
          })]
        })
      });
      const message = (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('The block is hidden because you don\'t have categories in your store. <a target="_blank" href="admin.php?page=ec-store-admin-category-id-0-mode-edit">Add categories.</a>', 'ecwid-shopping-cart');
      return [editor, /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(InspectorControls, {
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)(PanelBody, {
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            style: {
              height: '10px'
            }
          }), !blockParams.has_categories && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            dangerouslySetInnerHTML: {
              __html: message
            }
          })]
        })
      })];
    },
    save: function (props) {
      return false;
    }
  });
}

/***/ }),

/***/ "./js/gutenberg/src/categories/editor.scss":
/*!*************************************************!*\
  !*** ./js/gutenberg/src/categories/editor.scss ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/categories/style.scss":
/*!************************************************!*\
  !*** ./js/gutenberg/src/categories/style.scss ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/category-page/block.jsx":
/*!**************************************************!*\
  !*** ./js/gutenberg/src/category-page/block.jsx ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./style.scss */ "./js/gutenberg/src/category-page/style.scss");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./editor.scss */ "./js/gutenberg/src/category-page/editor.scss");
/* harmony import */ var _includes_icons_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../includes/icons.js */ "./js/gutenberg/src/includes/icons.js");
/* harmony import */ var _includes_controls_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../includes/controls.js */ "./js/gutenberg/src/includes/controls.js");
/* harmony import */ var _includes_utils_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../includes/utils.js */ "./js/gutenberg/src/includes/utils.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__);










const blockName = 'ec-store/category-page';
const blockParams = EcwidGutenbergParams.blockParams[blockName];

/**
 * Register: Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */

(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)('ec-store/category-page', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Store Category Page', 'ecwid-shopping-cart'),
  // Block title.
  icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_6__.EcwidIcons.category,
  category: 'ec-store',
  // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  attributes: EcwidGutenbergStoreBlockParams.attributes,
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Display category page', 'ecwid-shopping-cart'),
  supports: {
    customClassName: false,
    className: false,
    html: false,
    multiple: false,
    inserter: EcwidGutenbergParams.isWidgetsScreen ? false : true
  },
  example: {},
  /**
   * The edit function describes the structure of your block in the context of the editor.
   * This represents what the editor will render when the block is used.
   *
   * The "edit" property must be a valid function.
   *
   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
   */
  edit: function (props) {
    const {
      attributes
    } = props;

    // legacy reset 
    props.setAttributes({
      widgets: ''
    });
    const editor = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_includes_controls_js__WEBPACK_IMPORTED_MODULE_7__.EcwidProductBrowserBlock, {
      props: props,
      attributes: attributes,
      icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_6__.EcwidIcons.category,
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Store Category Page', 'ecwid-shopping-cart'),
      showDemoButton: blockParams.isDemoStore,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)("div", {
        className: "ec-store-products",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
          className: "ec-store-product-block",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
            className: "ec-store-product ec-store-category-sneaker"
          })
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
          className: "ec-store-product-block",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
            className: "ec-store-product ec-store-category-bag"
          })
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
          className: "ec-store-product-block",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
            className: "ec-store-product ec-store-category-shirt"
          })
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)("div", {
        className: "ec-store-products",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
          className: "ec-store-product-block",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
            className: "ec-store-product ec-store-category-hat"
          })
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
          className: "ec-store-product-block",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
            className: "ec-store-product ec-store-category-watch"
          })
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
          className: "ec-store-product-block",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
            className: "ec-store-product ec-store-category-glasses"
          })
        })]
      })]
    });
    const productMigrationWarning = (0,_includes_utils_js__WEBPACK_IMPORTED_MODULE_8__.buildDangerousHTMLMessageWithTitle)('', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('To improve the look and feel of your store and manage your storefront appearance here, please enable the “Next-gen look and feel of the product list on the storefront” option in your store dashboard (“<a href="admin.php?page=ec-store&ec-store-page=whatsnew">Settings → What’s New</a>”).', 'ecwid-shopping-cart'));
    const cartIconMessage = (0,_includes_utils_js__WEBPACK_IMPORTED_MODULE_8__.buildDangerousHTMLMessageWithTitle)((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Display cart icon', 'ecwid-shopping-cart'), blockParams.customizeMinicartText);
    const isNewProductList = blockParams.isNewProductList;
    const isNewDetailsPage = blockParams.isNewDetailsPage;
    const controls = (0,_includes_controls_js__WEBPACK_IMPORTED_MODULE_7__.EcwidControls)(blockParams.attributes, props);
    return [editor, /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, {
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        children: [!EcwidGutenbergParams.hasCategories && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
          style: {
            margin: '10px'
          },
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("a", {
            href: "admin.php?page=ec-store-admin-category-id-0-mode-edit",
            target: "_blank",
            class: "button button-primary",
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Add categories', 'ecwid-shopping-cart')
          })
        }), EcwidGutenbergParams.hasCategories && [!props.attributes.default_category_id && controls.select('default_category_id', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Select category', 'ecwid-shopping-cart')), props.attributes.default_category_id && controls.select('default_category_id', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Selected category', 'ecwid-shopping-cart'))]]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Category List Appearance', 'ecwid-shopping-cart'),
        initialOpen: false,
        children: [isNewProductList && [controls.select('product_list_category_title_behavior'), attributes.product_list_category_title_behavior !== 'SHOW_TEXT_ONLY' && [controls.buttonGroup('product_list_category_image_size'), controls.toolbar('product_list_category_image_aspect_ratio')]], !isNewProductList && productMigrationWarning]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Product List Appearance', 'ecwid-shopping-cart'),
        initialOpen: false,
        children: [isNewProductList && [controls.toggle('product_list_show_product_images'), attributes.product_list_show_product_images && [controls.buttonGroup('product_list_image_size'), controls.toolbar('product_list_image_aspect_ratio')], controls.toolbar('product_list_product_info_layout'), controls.select('product_list_title_behavior'), controls.select('product_list_price_behavior'), controls.select('product_list_sku_behavior'), controls.select('product_list_buybutton_behavior'), controls.toggle('product_list_show_additional_image_on_hover'), controls.toggle('product_list_show_frame')], !isNewProductList && productMigrationWarning]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Product Page Appearance', 'ecwid-shopping-cart'),
        initialOpen: false,
        children: [isNewDetailsPage && [controls.select('product_details_layout'), (attributes.product_details_layout === 'TWO_COLUMNS_SIDEBAR_ON_THE_RIGHT' || attributes.product_details_layout === 'TWO_COLUMNS_SIDEBAR_ON_THE_LEFT') && controls.toggle('show_description_under_image'), controls.toolbar('product_details_gallery_layout'), (0,_includes_controls_js__WEBPACK_IMPORTED_MODULE_7__.EcwidInspectorSubheader)((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Product sidebar content', 'ecwid-shopping-cart')), controls.toggle('product_details_show_product_name'), controls.toggle('product_details_show_breadcrumbs'), controls.toggle('product_details_show_product_sku'), controls.toggle('product_details_show_product_price'), controls.toggle('product_details_show_qty'), controls.toggle('product_details_show_weight'), controls.toggle('product_details_show_number_of_items_in_stock'), controls.toggle('product_details_show_in_stock_label'), controls.toggle('product_details_show_wholesale_prices'), controls.toggle('product_details_show_share_buttons'), controls.toggle('product_details_show_navigation_arrows'), controls.toggle('product_details_show_product_photo_zoom')], !isNewDetailsPage && productMigrationWarning]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Store Navigation', 'ecwid-shopping-cart'),
        initialOpen: false,
        children: [controls.toggle('show_categories'), controls.toggle('show_search'), controls.toggle('show_breadcrumbs'), isNewProductList && controls.toggle('show_footer_menu'), controls.toggle('show_signin_link'), controls.toggle('product_list_show_sort_viewas_options'), cartIconMessage]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Color settings', 'ecwid-shopping-cart'),
        initialOpen: false,
        children: [controls.color('chameleon_color_button'), controls.color('chameleon_color_foreground'), controls.color('chameleon_color_price'), controls.color('chameleon_color_link'), controls.color('chameleon_color_background')]
      })]
    })];
  },
  save: function (props) {
    return null;
  }
});

/***/ }),

/***/ "./js/gutenberg/src/category-page/editor.scss":
/*!****************************************************!*\
  !*** ./js/gutenberg/src/category-page/editor.scss ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/category-page/style.scss":
/*!***************************************************!*\
  !*** ./js/gutenberg/src/category-page/style.scss ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/filters-page/block.jsx":
/*!*************************************************!*\
  !*** ./js/gutenberg/src/filters-page/block.jsx ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./style.scss */ "./js/gutenberg/src/filters-page/style.scss");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./editor.scss */ "./js/gutenberg/src/filters-page/editor.scss");
/* harmony import */ var _includes_icons_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../includes/icons.js */ "./js/gutenberg/src/includes/icons.js");
/* harmony import */ var _includes_controls_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../includes/controls.js */ "./js/gutenberg/src/includes/controls.js");
/* harmony import */ var _includes_utils_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../includes/utils.js */ "./js/gutenberg/src/includes/utils.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__);










const blockName = 'ec-store/filters-page';
const blockParams = EcwidGutenbergParams.blockParams[blockName];

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)('ec-store/filters-page', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Product Search and filters', 'ecwid-shopping-cart'),
  // Block title.
  icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_6__.EcwidIcons.filters,
  category: 'ec-store',
  // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  attributes: blockParams.attributes,
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Display search page with filters on a side', 'ecwid-shopping-cart'),
  supports: {
    customClassName: false,
    className: false,
    html: false,
    multiple: false,
    inserter: EcwidGutenbergParams.isWidgetsScreen ? false : true
  },
  example: {},
  /**
   * The edit function describes the structure of your block in the context of the editor.
   * This represents what the editor will render when the block is used.
   *
   * The "edit" property must be a valid function.
   *
   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
   */
  edit: function (props) {
    const {
      attributes
    } = props;
    const editor = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_includes_controls_js__WEBPACK_IMPORTED_MODULE_7__.EcwidProductBrowserBlock, {
      props: props,
      attributes: attributes,
      icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_6__.EcwidIcons.filters,
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Search and Filters', 'ecwid-shopping-cart'),
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_includes_controls_js__WEBPACK_IMPORTED_MODULE_7__.EcwidStoreBlockInner, {
        state: "SEARCH_FILTERS_PAGE"
      })
    });
    /*       const editor =
               <div className="ec-store-block ec-store-block-filters-page">
                   <div className="ec-store-block-header">
                       { EcwidIcons.filters }
                       { __( 'Filters Page', 'ecwid-shopping-cart' ) }
                   </div>
                   <div className="image">
                   </div>
                   { blockParams.isDemoStore &&
                   <div>
                       <a className="button button-primary" href="admin.php?page=ec-store">{ __( 'Set up your store', 'ecwid-shopping-cart') }</a>
                   </div>
                   }
               </div>
           ;*/

    const filtersDisabledMessage = (0,_includes_utils_js__WEBPACK_IMPORTED_MODULE_8__.buildDangerousHTMLMessageWithTitle)('', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('You can enable filters in the store settings: (“<a target="_blank" href="admin.php?page=ec-store-admin-product-filters-mode-main">Settings → Product Filters</a>”).', 'ecwid-shopping-cart'));
    const productMigrationWarning = (0,_includes_utils_js__WEBPACK_IMPORTED_MODULE_8__.buildDangerousHTMLMessageWithTitle)('', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('To improve the look and feel of your store and manage your storefront appearance here, please enable the “Next-gen look and feel of the product list on the storefront” option in your store dashboard (“<a href="admin.php?page=ec-store&ec-store-page=whatsnew">Settings → What’s New</a>”).', 'ecwid-shopping-cart'));
    const isNewProductList = blockParams.isNewProductList;
    const controls = (0,_includes_controls_js__WEBPACK_IMPORTED_MODULE_7__.EcwidControls)(blockParams.attributes, props);
    return [editor, /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, {
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Filters', 'ecwid-shopping-cart'),
        initialOpen: false,
        children: [!blockParams.filtersEnabled && filtersDisabledMessage, blockParams.filtersEnabled && [controls.select('product_filters_position_search_page')]]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Product List Appearance', 'ecwid-shopping-cart'),
        initialOpen: false,
        children: [isNewProductList && [controls.toggle('product_list_show_product_images'), attributes.product_list_show_product_images && [controls.buttonGroup('product_list_image_size'), controls.toolbar('product_list_image_aspect_ratio')], controls.toolbar('product_list_product_info_layout'), controls.select('product_list_title_behavior'), controls.select('product_list_price_behavior'), controls.select('product_list_sku_behavior'), controls.select('product_list_buybutton_behavior'), controls.toggle('product_list_show_additional_image_on_hover'), controls.toggle('product_list_show_frame')], !isNewProductList && productMigrationWarning]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Store Navigation', 'ecwid-shopping-cart'),
        initialOpen: false,
        children: [controls.toggle('show_categories'), controls.toggle('show_breadcrumbs'), isNewProductList && controls.toggle('show_footer_menu'), controls.toggle('show_signin_link'), controls.toggle('product_list_show_sort_viewas_options')]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Color settings', 'ecwid-shopping-cart'),
        initialOpen: false,
        children: [controls.color('chameleon_color_button'), controls.color('chameleon_color_foreground'), controls.color('chameleon_color_price'), controls.color('chameleon_color_link'), controls.color('chameleon_color_background')]
      })]
    })];
  },
  save: function (props) {
    return null;
  }
});

/***/ }),

/***/ "./js/gutenberg/src/filters-page/editor.scss":
/*!***************************************************!*\
  !*** ./js/gutenberg/src/filters-page/editor.scss ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/filters-page/style.scss":
/*!**************************************************!*\
  !*** ./js/gutenberg/src/filters-page/style.scss ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/includes/color.js":
/*!********************************************!*\
  !*** ./js/gutenberg/src/includes/color.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ColorControl: () => (/* binding */ ColorControl)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__);




const colors = [{
  name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Pale pink"),
  slug: "pale-pink",
  color: "#f78da7"
}, {
  name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Vivid red"),
  slug: "vivid-red",
  color: "#cf2e2e"
}, {
  name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Luminous vivid orange"),
  slug: "luminous-vivid-orange",
  color: "#ff6900"
}, {
  name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Luminous vivid amber"),
  slug: "luminous-vivid-amber",
  color: "#fcb900"
}, {
  name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Light green cyan"),
  slug: "light-green-cyan",
  color: "#7bdcb5"
}, {
  name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Vivid green cyan"),
  slug: "vivid-green-cyan",
  color: "#00d084"
}, {
  name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Pale cyan blue"),
  slug: "pale-cyan-blue",
  color: "#8ed1fc"
}, {
  name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Vivid cyan blue"),
  slug: "vivid-cyan-blue",
  color: "#0693e3"
}, {
  name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Very light gray"),
  slug: "very-light-gray",
  color: "#eeeeee"
}, {
  name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Cyan bluish gray"),
  slug: "cyan-bluish-gray",
  color: "#abb8c3"
}, {
  name: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Very dark gray"),
  slug: "very-dark-gray",
  color: "#313131"
}];
const ColorControl = ({
  name,
  title,
  props
}) => {
  const [manual, setManual] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useState)(null);
  const [color, setColor] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_2__.useState)(null);

  // Setting default value
  if (typeof props.attributes[name] === 'undefined') {
    props.attributes[name] = false;
  }
  const isManual = manual === null && props.attributes[name] !== false && props.attributes[name] !== null && props.attributes[name] !== '' || manual === 'manual';
  if (!isManual) {
    props.setAttributes({
      [name]: false
    });
  } else if (color !== null) {
    props.setAttributes({
      [name]: color === undefined ? false : color
    });
  }
  const currentValue = props.attributes[name] || '';
  const titleElement = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("span", {
    children: [title, currentValue !== null && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.ColorIndicator, {
      colorValue: props.attributes[name]
    })]
  });
  function handleColorChange(newColor) {
    setColor(newColor === undefined ? false : newColor);
    props.setAttributes({
      [name]: newColor === undefined ? false : newColor
    });
  }
  function handleSelectChange(event) {
    const newValue = event.target.value;
    setManual(newValue);
    if (newValue === 'auto') {
      setColor(false);
      props.setAttributes({
        [name]: false
      });
    }
  }
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.BaseControl, {
    label: titleElement,
    className: "ec-store-color-picker",
    __nextHasNoMarginBottom: true,
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)("select", {
      onChange: handleSelectChange,
      value: isManual ? 'manual' : 'auto',
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("option", {
        value: "auto",
        children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Detect automatically', 'ecwid-shopping-cart')
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)("option", {
        value: "manual",
        children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Set manually', 'ecwid-shopping-cart')
      })]
    }), isManual && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__.ColorPalette, {
      value: currentValue,
      colors: colors,
      onChange: handleColorChange
    })]
  });
};

/***/ }),

/***/ "./js/gutenberg/src/includes/controls.js":
/*!***********************************************!*\
  !*** ./js/gutenberg/src/includes/controls.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   EcwidControls: () => (/* binding */ EcwidControls),
/* harmony export */   EcwidInspectorSubheader: () => (/* binding */ EcwidInspectorSubheader),
/* harmony export */   EcwidProductBrowserBlock: () => (/* binding */ EcwidProductBrowserBlock),
/* harmony export */   EcwidStoreBlockInner: () => (/* binding */ EcwidStoreBlockInner)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _icons_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./icons.js */ "./js/gutenberg/src/includes/icons.js");
/* harmony import */ var _color_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./color.js */ "./js/gutenberg/src/includes/color.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__);






function EcwidControls(declaration, properties) {
  const attributes = properties.attributes;
  let buildButtonGroup = function (props, name, label, items) {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.BaseControl, {
      label: label,
      __nextHasNoMarginBottom: true,
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ButtonGroup, {
        className: "ec-store-inspector-button-group",
        children: items.map(function (item) {
          return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
            isPrimary: attributes[name] === item.value,
            onClick: () => props.setAttributes({
              [name]: item.value
            }),
            children: item.title
          });
        })
      })
    });
  };
  let buildToggle = function (props, name, label) {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
      label: label,
      checked: props.attributes[name],
      onChange: () => props.setAttributes({
        [name]: !props.attributes[name]
      }),
      __nextHasNoMarginBottom: true
    });
  };
  let buildSelect = function (props, name, label, items, callback = () => {}) {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.BaseControl, {
      label: label,
      __nextHasNoMarginBottom: true,
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("select", {
        className: "ec-store-control-select",
        onChange: event => {
          props.setAttributes({
            [name]: event.target.value
          });
          callback();
        },
        children: items.map(function (item) {
          return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("option", {
            value: item.value,
            selected: props.attributes[name] == item.value,
            children: item.title
          });
        })
      })
    });
  };
  let buildTextbox = function (props, name, label) {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.BaseControl, {
      label: label,
      __nextHasNoMarginBottom: true,
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("input", {
        type: "text",
        value: props.attributes[name],
        onChange: event => {
          props.setAttributes({
            [name]: event.target.value
          });
        }
      })
    });
  };
  let buildToolbar = function (props, name, label, items) {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.BaseControl, {
      label: label,
      __nextHasNoMarginBottom: true,
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToolbarGroup, {
        controls: items.map(function (item) {
          return {
            icon: _icons_js__WEBPACK_IMPORTED_MODULE_3__.EcwidIcons[item.icon],
            title: item.title,
            isActive: props.attributes[name] === item.value,
            className: 'ecwid-toolbar-icon',
            onClick: () => props.setAttributes({
              [name]: item.value
            })
          };
        })
      })
    });
  };
  let buildRadioButtonWithDescription = function (props, name, label, items) {
    const needShowCategories = props.attributes[name] == 'DEFAULT_CATEGORY_ID';
    const item = declaration['default_category_id'];
    let isPreviewInFrame = document.querySelector('[name=editor-canvas]') != null ? true : false;
    let w = window;
    if (isPreviewInFrame) w = document.querySelector('[name=editor-canvas]').contentWindow;
    const bodyDone = value => {
      if (typeof w.Ecwid != 'undefined' && value != 'FILTERS_PAGE') {
        if (w.document.getElementById('ec-store-preview') != null) w.document.getElementById('ec-store-preview').innerHTML = '';
        setTimeout(function () {
          // w.ecwid_onBodyDone();
          window.Ecwid.init();
        }, 300);
      }
    };
    let select = '';
    if (item.values && item.values.length > 1) {
      select = buildSelect(props, item.name, item.title, item.values, bodyDone);
    }
    let options = items.map(function (item) {
      return {
        value: item.value,
        label: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("span", {
            className: "ec-store-inspector-radio__title",
            children: item.title
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("p", {
            children: item.description
          }), item.value == 'DEFAULT_CATEGORY_ID' && needShowCategories && [select]]
        })
      };
    });
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.BaseControl, {
      __nextHasNoMarginBottom: true,
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.RadioControl, {
        label: label,
        className: "ec-store-inspector-radio",
        options: options,
        selected: props.attributes[name],
        onChange: value => {
          props.setAttributes({
            [name]: value
          });
          bodyDone(value);
        }
      })
    });
  };
  return {
    buttonGroup: function (name) {
      const item = declaration[name];
      if (typeof properties.attributes[name] == 'undefined') {
        properties.attributes[name] = item.default;
      }
      return buildButtonGroup(properties, item.name, item.title, item.values);
    },
    toggle: function (name) {
      const item = declaration[name];
      if (typeof properties.attributes[name] == 'undefined') {
        properties.attributes[name] = item.default;
      }
      return buildToggle(properties, item.name, item.title);
    },
    select: function (name, title = null) {
      const item = declaration[name];
      if (typeof properties.attributes[name] == 'undefined') {
        properties.attributes[name] = item.default;
      }
      return buildSelect(properties, item.name, title ? title : item.title, item.values);
    },
    textbox: function (name) {
      const item = declaration[name];
      return builtTextbox(properties, item.name, item.title);
    },
    toolbar: function (name) {
      const item = declaration[name];
      if (typeof properties.attributes[name] == 'undefined') {
        properties.attributes[name] = item.default;
      }
      return buildToolbar(properties, item.name, item.title, item.values);
    },
    color: function (name) {
      return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_color_js__WEBPACK_IMPORTED_MODULE_4__.ColorControl, {
        props: properties,
        name: name,
        title: declaration[name].title
      });
    },
    defaultCategoryId: function (name) {
      const item = declaration[name];
      if (item.values && item.values.length > 1) {
        if (typeof properties.attributes[name] == 'undefined') {
          properties.attributes[name] = item.default;
        }
        return buildSelect(properties, item.name, item.title, item.values);
      } else {
        return buildTextbox(properties, item.name, item.title);
      }
    },
    radioButtonWithDescription: function (name) {
      const item = declaration[name];
      if (typeof properties.attributes[name] == 'undefined') {
        properties.attributes[name] = item.default;
      }
      return buildRadioButtonWithDescription(properties, item.name, item.title, item.values);
    }
  };
}
function EcwidInspectorSubheader(title) {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
    className: "ec-store-inspector-subheader-row",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("label", {
      className: "ec-store-inspector-subheader",
      children: title
    })
  });
}
;
function trackDynamicProperties(currentDocument, props, dynamicProps) {
  const blockProps = props.props;
  const dynamicProperties = dynamicProps.split(' ');
  const blockId = blockProps.clientId;
  const wrapperId = '#ec-store-block-' + blockId;
  const storedData = jQuery(currentDocument).find(wrapperId).data('ec-store-block-stored-properties');
  let changed = false;
  let propValues = {};
  for (let i = 0; i < dynamicProperties.length; i++) {
    let name = dynamicProperties[i];
    if (!storedData || blockProps.attributes[name] != storedData[name]) {
      changed = true;
    }
    propValues[name] = blockProps.attributes[name];
  }
  jQuery(currentDocument).find(wrapperId).data('ec-store-block-stored-properties', propValues);
  return changed;
}
function EcwidProductBrowserBlock(props) {
  const blockProps = props.props;
  const attributes = props.attributes;
  const blockId = blockProps.clientId;
  const showCats = blockProps.attributes.show_categories;
  const showSearch = blockProps.attributes.show_search;
  const render = typeof props.render === 'undefined' ? true : props.render;
  const wrapperId = 'ec-store-block-' + blockId;
  const isPreviewInFrame = () => {
    return document.querySelector('[name=editor-canvas]') != null ? true : false;
  };
  const getPreviewFrameContent = () => {
    return document.querySelector('[name=editor-canvas]').contentWindow;
  };
  let w = window;
  if (isPreviewInFrame()) w = getPreviewFrameContent();
  let widget = "productbrowser";
  let args = '';
  if (blockProps.attributes.default_category_id) {
    args = "defaultCategoryId=" + blockProps.attributes.default_category_id;
  } else if (blockProps.attributes.default_product_id) {
    args = "defaultProductId=" + blockProps.attributes.default_product_id;
  }
  let className = '';
  if (!props.isLivePreviewEnabled) {
    className = "ec-store-generic-block ec-store-dynamic-block";
    if (!render || !w.document.getElementById(wrapperId) || !w.document.getElementById(wrapperId).getAttribute('data-ec-store-rendered')) {
      className += " ec-store-block";
    }
    if (showCats) {
      className += " ec-store-with-categories";
    }
    if (showSearch) {
      className += " ec-store-with-search";
    }
    className += " ec-store-with-stub";
  }
  let changed = trackDynamicProperties(w.document, props, "default_product_id default_category_id show_search show_categories");
  if (render && changed) {
    w.document.getElementById(wrapperId) && w.document.getElementById(wrapperId).removeAttribute('data-ec-store-rendered');
    if ("undefined" != typeof EcwidGutenberg) {
      setTimeout(function () {
        EcwidGutenberg.refresh();
      });
    }
  }
  w.ec = w.ec || {};
  w.ec.storefront = w.ec.storefront || {};
  w.ec.config = w.ec.config || {};
  w.ec.config.chameleon = w.ec.config.chameleon || {};
  w.ec.config.chameleon.colors = {};
  w.ec.config.disable_all_cookies = true;
  Object.keys(attributes).map(i => {
    let value = typeof blockProps.attributes[i] !== 'undefined' ? blockProps.attributes[i] : attributes.default;
    if (i.indexOf('chameleon') !== -1) {
      if (value) {
        w.ec.config.chameleon.colors['color-' + i.substring(16)] = value;
      }
    } else {
      if (typeof value != 'undefined') {
        w.ec.storefront[i] = value;
      }
    }
  });
  delete w.ec.storefront.enable_catalog_on_one_page;
  delete w.ec.storefront.show_root_categories;
  if (!!props.isLivePreviewEnabled) {
    const [lastBlockId, setLastBlockId] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)('');
    const openPage = (page, params = {}) => {
      if (props.isProductPage) return;
      if ("undefined" != typeof w.Ecwid && w.Ecwid.openPage) {
        w.Ecwid.openPage(page, params);
      }
    };
    const clearUrlHash = () => {
      history.replaceState(null, null, ' ');
    };
    switch (attributes.storefront_view) {
      case 'EXPAND_CATEGORIES':
        w.ec.storefront.enable_catalog_on_one_page = true;
        clearUrlHash();
        break;
      case 'SHOW_ROOT_CATEGORIES':
        w.ec.storefront.show_root_categories = false;
        clearUrlHash();
        break;
      case 'FILTERS_PAGE':
        openPage("search");
        break;
      case 'DEFAULT_CATEGORY_ID':
      case 'COLLAPSE_CATEGORIES':
      default:
        w.ec.storefront.enable_catalog_on_one_page = false;
        clearUrlHash();
    }
    const loadScriptJS = el => {
      if (el == null) return;
      if (typeof w.Ecwid != 'undefined') {
        w.Ecwid.OnAPILoaded.add(() => {
          w.Ecwid.refreshConfig();
        });
      }
      w.ecwid_script_defer = true;
      w.ecwid_dynamic_widgets = true;
      w._xnext_initialization_scripts = [];
      localStorage.setItem('ec_disabled_apps', "all");
      let was_opened_once = false;
      let searchNode = w.document.getElementById('ec-store-search-preview');
      let hasSearchBoxAdded = searchNode && searchNode.childNodes && searchNode.childNodes.length > 0;
      if (!!props.attributes.show_search && !hasSearchBoxAdded) {
        w._xnext_initialization_scripts.push({
          widgetType: 'SearchWidget',
          id: 'ec-store-search-preview',
          arg: ["id=ec-store-search-preview"]
        });
      }
      if (!!props.attributes.show_categories) {
        w._xnext_initialization_scripts.push({
          widgetType: 'CategoriesV2',
          id: 'ec-store-categories-preview',
          arg: ["id=ec-store-categories-preview"]
        });
      }
      w._xnext_initialization_scripts.push({
        widgetType: 'ProductBrowser',
        id: 'ec-store-preview',
        arg: [args]
      });
      if (!w.document.getElementById('ec-store-script')) {
        var script = w.document.createElement('script');
        script.type = 'text/javascript';
        script.id = 'ec-store-script';
        script.src = EcwidGutenbergParams.scriptJsUrl;
        el.innerHTML = '';
        el.appendChild(script);
        script.addEventListener('load', function () {
          if (typeof w.Ecwid == 'undefined') {
            return;
          }
          var nodes = w.document.getElementsByClassName('ec-cart-widget');
          if (nodes.length > 0) {
            w.Ecwid.init();
          }
          w.Ecwid.OnAPILoaded.add(function () {
            if (attributes.storefront_view == 'FILTERS_PAGE' && !was_opened_once) {
              openPage("search");
              was_opened_once = true;
            }
          });
          setLastBlockId(blockId);
        });
      } else {
        if (typeof w.Ecwid != 'undefined') {
          if (lastBlockId != blockId) {
            setLastBlockId(blockId);
            w.Ecwid.init();
          }
          if (changed) {
            w.document.getElementById('ec-store-preview').innerHTML = '';
            w.Ecwid.init();
          }
        }
      }
    };
    const noticeActions = [{
      'label': (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Set up your store', 'ecwid-shopping-cart'),
      'url': 'admin.php?page=ec-store',
      'variant': 'primary'
    }];
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
      className: className,
      "data-ec-store-widget": widget,
      "data-ec-store-id": blockId,
      "data-ec-store-args": args,
      "data-ec-store-with-search": showSearch,
      "data-ec-store-with-categories": showCats,
      id: wrapperId,
      children: [props.attributes.show_search && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
        id: "ec-store-search-preview"
      }), props.attributes.show_categories && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
        id: "ec-store-categories-preview"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
        id: "ec-store-preview"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
        ref: loadScriptJS
      }), props.showDemoButton && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Notice, {
        status: "info",
        isDismissible: false,
        actions: noticeActions,
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
          style: {
            margin: '0 0 12px 12px'
          },
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('This is a demo store. Create your store to see your store products here.', 'ecwid-shopping-cart')
        })
      })]
    });
  } else {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
      className: className,
      "data-ec-store-widget": widget,
      "data-ec-store-id": blockId,
      "data-ec-store-args": args,
      "data-ec-store-with-search": showSearch,
      "data-ec-store-with-categories": showCats,
      id: wrapperId,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
        className: "ec-store-block-header",
        children: [props.icon, props.title]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
        className: "ec-store-block-content",
        children: props.children
      }), props.showDemoButton && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("a", {
          className: "button button-primary",
          href: "admin.php?page=ec-store",
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Set up your store', 'ecwid-shopping-cart')
        })
      })]
    });
  }
}
;
function EcwidStoreBlockInner(props) {
  let products = function (title = '') {
    if (title != '') {
      title = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("h5", {
        children: title
      });
    }
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
        className: "ec-store-block-subheader",
        children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Categories', 'ecwid-shopping-cart')
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
        className: "ec-store-products",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
          className: "ec-store-product-block",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-category-sneaker"
          })
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
          className: "ec-store-product-block",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-category-bag"
          })
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
          className: "ec-store-product-block",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-category-shirt"
          })
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
        className: "ec-store-block-subheader",
        children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Featured Products', 'ecwid-shopping-cart')
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
        className: "ec-store-products",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-g_sneaker"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-p_shirt"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-b_hat"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        })]
      })]
    });
  };
  let only_featured_products = function () {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
        className: "ec-store-block-subheader",
        children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Featured Products', 'ecwid-shopping-cart')
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
        className: "ec-store-products",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-m_sneaker"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-p_shirt"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-g_hat"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
        className: "ec-store-products",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-b_watch"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-y_bag"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-p_sneaker"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        })]
      })]
    });
  };
  let category = function () {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
        className: "ec-store-block-subheader",
        children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Category #1', 'ecwid-shopping-cart')
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
        className: "ec-store-products",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-y_sneaker"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-y_shirt"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-y_watch"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
        className: "ec-store-products",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-y_bag"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-y_hat"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-y_sneaker"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        })]
      })]
    });
  };
  let menu_mode = function () {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
        className: "ec-store-block-subheader",
        children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Category #1', 'ecwid-shopping-cart')
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
        className: "ec-store-products",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-y_sneaker"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-y_shirt"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-y_watch"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
        className: "ec-store-block-subheader",
        children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Category #2', 'ecwid-shopping-cart')
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
        className: "ec-store-products",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-g_sneaker"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-g_shirt"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-g_watch"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        })]
      })]
    });
  };
  let filter = function (class_name = '') {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
      className: class_name,
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
        className: "ec-store-products",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
          className: "ec-store-product-block ec-store-product-filter",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-y_shirt"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-b_watch"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        })]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
        className: "ec-store-products",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
          className: "ec-store-product-block"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-g_sneaker"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsxs)("div", {
          className: "ec-store-product-block",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-product ec-store-product-g_hat"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
            className: "ec-store-stub-sample"
          })]
        })]
      })]
    });
  };
  switch (props.state) {
    case 'EXPAND_CATEGORIES':
      return menu_mode();
    case 'SHOW_ROOT_CATEGORIES':
      return only_featured_products();
    case 'DEFAULT_CATEGORY_ID':
      return category();
    case 'FILTERS_PAGE':
      return filter();
    case 'SEARCH_FILTERS_PAGE':
      return filter('ec-store-block-filters-page');
    default:
      return products();
  }
}


/***/ }),

/***/ "./js/gutenberg/src/includes/icons.js":
/*!********************************************!*\
  !*** ./js/gutenberg/src/includes/icons.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   EcwidIcons: () => (/* binding */ EcwidIcons)
/* harmony export */ });
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__);

const EcwidIcons = {
  ecwid: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    class: "ec-store-icon",
    version: "1.1",
    id: "Layer_1",
    x: "0px",
    y: "0px",
    viewBox: "0 0 215 215",
    "enable-background": "new 0 0 215 215",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      fill: "#0087cd",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        "fill-rule": "evenodd",
        "clip-rule": "evenodd",
        d: "M160.68,163.34c-3.67,0-6.65,2.98-6.65,6.66c0,3.68,2.98,6.66,6.65,6.66 c3.68,0,6.66-2.98,6.66-6.66C167.34,166.32,164.36,163.34,160.68,163.34z"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        "fill-rule": "evenodd",
        "clip-rule": "evenodd",
        d: "M53.46,162.51c-3.67,0-6.65,2.98-6.65,6.66c0,3.68,2.98,6.66,6.65,6.66 c3.68,0,6.66-2.98,6.66-6.66C60.12,165.49,57.14,162.51,53.46,162.51z"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        "fill-rule": "evenodd",
        "clip-rule": "evenodd",
        d: "M166.12,0H48.88C21.89,0,0,21.89,0,48.89v117.23c0,27,21.89,48.88,48.88,48.88 h117.24c27,0,48.88-21.88,48.88-48.88V48.88C215,21.89,193.11,0,166.12,0z M134.43,57.85c5.36,0,9.7,4.34,9.7,9.7 c0,5.36-4.34,9.7-9.7,9.7c-5.36,0-9.7-4.34-9.7-9.7C124.73,62.19,129.07,57.85,134.43,57.85z M134.43,85.25 c5.36,0,9.7,4.34,9.7,9.7s-4.34,9.7-9.7,9.7c-5.36,0-9.7-4.34-9.7-9.7S129.07,85.25,134.43,85.25z M107.09,57.85 c5.36,0,9.7,4.34,9.7,9.7c0,5.36-4.34,9.7-9.7,9.7c-5.36,0-9.7-4.34-9.7-9.7C97.39,62.19,101.73,57.85,107.09,57.85z M107.09,85.25 c5.36,0,9.7,4.34,9.7,9.7s-4.34,9.7-9.7,9.7c-5.36,0-9.7-4.34-9.7-9.7S101.73,85.25,107.09,85.25z M79.75,57.85 c5.36,0,9.7,4.34,9.7,9.7c0,5.36-4.34,9.7-9.7,9.7c-5.36,0-9.7-4.34-9.7-9.7C70.05,62.19,74.39,57.85,79.75,57.85z M79.75,85.25 c5.36,0,9.7,4.34,9.7,9.7s-4.34,9.7-9.7,9.7c-5.36,0-9.7-4.34-9.7-9.7S74.39,85.25,79.75,85.25z M53.46,187.72 c-10.24,0-18.55-8.31-18.55-18.55c0-10.25,8.31-18.56,18.55-18.56c10.25,0,18.56,8.31,18.56,18.56 C72.03,179.41,63.71,187.72,53.46,187.72z M160.68,188.55c-10.24,0-18.55-8.31-18.55-18.55c0-10.25,8.31-18.56,18.55-18.56 c10.25,0,18.56,8.31,18.56,18.56C179.24,180.24,170.93,188.55,160.68,188.55z M193.27,37.66l-19.18,71.44 c-5.12,19.07-21.28,31.04-41.03,31.04h-12.65c-4.18,0-10.23-2.26-12.74-4.62c-0.42-0.39-1.08-0.39-1.5,0 c-2.51,2.36-8.56,4.62-12.74,4.62h-13.9c-19.12,0-33.61-10.9-39.41-29.12L23.81,59.86c-0.32-1.02-0.15-2.1,0.49-2.97 c0.63-0.86,1.6-1.36,2.69-1.36l3.12,0.01c7.52,0.03,14.11,4.86,16.38,12.02l11.98,37.62c3.24,10.19,13.61,17.04,24.3,17.04 l4.65-0.01c4.8,0,8.18-2.46,10.22-4.66c1.06-1.15,2.54-1.82,4.11-1.82l10.44,0.01c1.48,0,2.92,0.59,3.91,1.68 c1.98,2.17,5.49,4.79,10.33,4.79l4.43,0.01c11.04,0,21.75-7.45,24.62-18.11l15.53-57.84c2.03-7.53,8.88-12.78,16.67-12.78l2.74,0 c0.26,0,0.52,0.04,0.76,0.14C192.93,34.37,193.7,36.08,193.27,37.66z"
      })]
    })
  }),
  store: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    class: "ec-store-icon-color",
    xmlns: "http://www.w3.org/2000/svg",
    width: "24",
    height: "24",
    viewBox: "0 0 24 24",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      fill: "none",
      "fill-rule": "evenodd",
      stroke: "currentColor",
      "stroke-linejoin": "round",
      "stroke-width": "2",
      transform: "translate(0 3)",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        d: "M20 7L20 17C20 18.1045695 19.1045695 19 18 19L4 19C2.8954305 19 2 18.1045695 2 17L2 7 2 7M1 0L21 0 21.5808632 3.48517907C21.8145004 4.88700236 20.8935617 6.22128765 19.5 6.5L18.9764235 6.60471529C17.7961226 6.84077548 16.5971903 6.29508301 16 5.25L16 5.25 16 5.25 15.7442084 5.69763529C15.2840087 6.50298484 14.4275622 7 13.5 7 12.5724378 7 11.7159913 6.50298484 11.2557916 5.69763529L11 5.25 11 5.25 10.7442084 5.69763529C10.2840087 6.50298484 9.42756224 7 8.5 7 7.57243776 7 6.71599134 6.50298484 6.25579159 5.69763529L6 5.25 6 5.25C5.40280971 6.29508301 4.20387741 6.84077548 3.02357646 6.60471529L2.5 6.5C1.10643827 6.22128765.185499607 4.88700236.419136822 3.48517907L1 0 1 0z"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("polygon", {
        points: "7 11 15 11 15 19 7 19"
      })]
    })
  }),
  product: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    class: "ec-store-icon-color",
    xmlns: "http://www.w3.org/2000/svg",
    width: "24",
    height: "24",
    viewBox: "0 0 24 24",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      fill: "none",
      "fill-rule": "evenodd",
      "stroke-linecap": "round",
      "stroke-linejoin": "round",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        stroke: "currentColor",
        "stroke-width": "2",
        d: "M5.5638852,7 L18.4361148,7 C19.3276335,7 19.6509198,7.09282561 19.9768457,7.2671327 C20.3027716,7.4414398 20.5585602,7.69722837 20.7328673,8.0231543 C20.9071744,8.34908022 21,8.67236646 21,9.5638852 L21,20.4361148 C21,21.3276335 20.9071744,21.6509198 20.7328673,21.9768457 C20.5585602,22.3027716 20.3027716,22.5585602 19.9768457,22.7328673 C19.6509198,22.9071744 19.3276335,23 18.4361148,23 L5.5638852,23 C4.67236646,23 4.34908022,22.9071744 4.0231543,22.7328673 C3.69722837,22.5585602 3.4414398,22.3027716 3.2671327,21.9768457 C3.09282561,21.6509198 3,21.3276335 3,20.4361148 L3,9.5638852 C3,8.67236646 3.09282561,8.34908022 3.2671327,8.0231543 C3.4414398,7.69722837 3.69722837,7.4414398 4.0231543,7.2671327 C4.34908022,7.09282561 4.67236646,7 5.5638852,7 Z"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        stroke: "currentColor",
        "stroke-width": "2",
        d: "M8,10 L8,6 C8,3.790861 9.790861,2 12,2 C14.209139,2 16,3.790861 16,6 L16,10 L16,10"
      })]
    })
  }),
  aspect169: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    width: "40px",
    height: "40px",
    viewBox: "0 0 40 40",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
      stroke: "none",
      "stroke-width": "1",
      fill: "none",
      "fill-rule": "evenodd",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "9",
        y: "14",
        width: "22",
        height: "12",
        rx: "2"
      })
    })
  }),
  aspect916: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
    width: "40px",
    height: "40px",
    viewBox: "0 0 40 40",
    version: "1.1",
    children: ["    ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
      stroke: "none",
      "stroke-width": "1",
      fill: "none",
      "fill-rule": "evenodd",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "14",
        y: "9",
        width: "12",
        height: "22",
        rx: "2"
      })
    })]
  }),
  aspect11: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    width: "40px",
    height: "40px",
    viewBox: "0 0 40 40",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
      stroke: "none",
      "stroke-width": "1",
      fill: "none",
      "fill-rule": "evenodd",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "12",
        y: "12",
        width: "16",
        height: "16",
        rx: "2"
      })
    })
  }),
  aspect34: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    width: "40px",
    height: "40px",
    viewBox: "0 0 40 40",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
      stroke: "none",
      "stroke-width": "1",
      fill: "none",
      "fill-rule": "evenodd",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "12",
        y: "10",
        width: "16",
        height: "20",
        rx: "2"
      })
    })
  }),
  aspect43: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    width: "40px",
    height: "40px",
    viewBox: "0 0 40 40",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
      stroke: "none",
      "stroke-width": "1",
      fill: "none",
      "fill-rule": "evenodd",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "10",
        y: "12",
        width: "20",
        height: "16",
        rx: "2"
      })
    })
  }),
  textalignleft: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    width: "40px",
    height: "40px",
    viewBox: "0 0 40 40",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      stroke: "none",
      "stroke-width": "1",
      fill: "none",
      "fill-rule": "evenodd",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "13",
        y: "13",
        width: "14",
        height: "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "13",
        y: "16",
        width: "9",
        height: "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "13",
        y: "19",
        width: "13",
        height: "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "13",
        y: "22",
        width: "9",
        height: "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "13",
        y: "25",
        width: "14",
        height: "2"
      })]
    })
  }),
  textaligncenter: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    width: "40px",
    height: "40px",
    viewBox: "0 0 40 40",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      stroke: "none",
      "stroke-width": "1",
      fill: "none",
      "fill-rule": "evenodd",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "13",
        y: "13",
        width: "14",
        height: "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "16",
        y: "16",
        width: "8",
        height: "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "14",
        y: "19",
        width: "12",
        height: "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "16",
        y: "22",
        width: "8",
        height: "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "13",
        y: "25",
        width: "14",
        height: "2"
      })]
    })
  }),
  textalignright: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    width: "40px",
    height: "40px",
    viewBox: "0 0 40 40",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      stroke: "none",
      "stroke-width": "1",
      fill: "none",
      "fill-rule": "evenodd",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "13",
        y: "13",
        width: "14",
        height: "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "18",
        y: "16",
        width: "9",
        height: "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "14",
        y: "19",
        width: "13",
        height: "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "18",
        y: "22",
        width: "9",
        height: "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "13",
        y: "25",
        width: "14",
        height: "2"
      })]
    })
  }),
  textalignjustify: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    width: "40px",
    height: "40px",
    viewBox: "0 0 40 40",
    zoomAndPan: "1.5",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      stroke: "none",
      "stroke-width": "1",
      fill: "none",
      "fill-rule": "evenodd",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "13",
        y: "13",
        width: "14",
        height: "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "13",
        y: "16",
        width: "14",
        height: "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "13",
        y: "19",
        width: "14",
        height: "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "13",
        y: "22",
        width: "14",
        height: "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "13",
        y: "25",
        width: "14",
        height: "2"
      })]
    })
  }),
  productLayout3Columns: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    width: "40px",
    height: "40px",
    viewBox: "0 0 40 40",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      stroke: "none",
      "stroke-width": "1",
      fill: "none",
      "fill-rule": "evenodd",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        transform: "translate(13.000000, 19.500000) rotate(-270.000000) translate(-13.000000, -19.500000) ",
        x: "3.5",
        y: "16.5",
        width: "19",
        height: "6",
        rx: "1"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "18",
        y: "10",
        width: "5",
        height: "19"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "25",
        y: "10",
        width: "5",
        height: "8"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "25",
        y: "19",
        width: "5",
        height: "10"
      })]
    })
  }),
  productLayout2ColumnsLeft: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    width: "40px",
    height: "40px",
    viewBox: "0 0 40 40",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      stroke: "none",
      "stroke-width": "1",
      fill: "none",
      "fill-rule": "evenodd",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "17",
        y: "10",
        width: "13",
        height: "19",
        rx: "1"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "10",
        y: "10",
        width: "5",
        height: "5"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        fill: "#000000",
        x: "10",
        y: "17",
        width: "5",
        height: "12"
      })]
    })
  }),
  productLayout2ColumnsRight: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    width: "40px",
    height: "40px",
    viewBox: "0 0 40 40",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
      stroke: "none",
      "stroke-width": "1",
      fill: "none",
      "fill-rule": "evenodd",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
        transform: "translate(10.000000, 10.000000)",
        fill: "#000000",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
          x: "0",
          y: "0",
          width: "13",
          height: "19",
          rx: "1"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
          x: "15",
          y: "0",
          width: "5",
          height: "5"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
          x: "15",
          y: "7",
          width: "5",
          height: "12"
        })]
      })
    })
  }),
  productLayout2ColumnsBottom: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    width: "40px",
    height: "40px",
    viewBox: "0 0 40 40",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
      stroke: "none",
      "stroke-width": "1",
      fill: "none",
      "fill-rule": "evenodd",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
        transform: "translate(10.000000, 10.000000)",
        fill: "#000000",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
          x: "0",
          y: "0",
          width: "13",
          height: "12",
          rx: "1"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
          x: "15",
          y: "0",
          width: "5",
          height: "12"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
          x: "0",
          y: "14",
          width: "20",
          height: "5"
        })]
      })
    })
  }),
  galleryLayoutHorizontal: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    width: "40px",
    height: "40px",
    viewBox: "0 0 40 40",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
      stroke: "none",
      "stroke-width": "1",
      fill: "none",
      "fill-rule": "evenodd",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
        transform: "translate(20.000000, 20.500000) rotate(-180.000000) translate(-20.000000, -20.500000) translate(10.000000, 11.000000)",
        fill: "#000000",
        "fill-rule": "nonzero",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
          x: "0",
          y: "0",
          width: "13",
          height: "19",
          rx: "1"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
          x: "15",
          y: "0",
          width: "5",
          height: "6"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
          x: "15",
          y: "14",
          width: "5",
          height: "5"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
          x: "15",
          y: "7",
          width: "5",
          height: "6"
        })]
      })
    })
  }),
  galleryLayoutVertical: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    width: "40px",
    height: "40px",
    viewBox: "0 0 40 40",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
      stroke: "none",
      "stroke-width": "1",
      fill: "none",
      "fill-rule": "evenodd",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
        transform: "translate(19.500000, 20.000000) rotate(-270.000000) translate(-19.500000, -20.000000) translate(9.500000, 10.500000)",
        fill: "#000000",
        "fill-rule": "nonzero",
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
          x: "0",
          y: "-1.13686838e-13",
          width: "13",
          height: "19",
          rx: "1"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
          x: "15",
          y: "-1.13686838e-13",
          width: "5",
          height: "6"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
          x: "15",
          y: "7",
          width: "5",
          height: "5"
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
          x: "15",
          y: "13",
          width: "5",
          height: "6"
        })]
      })
    })
  }),
  galleryLayoutFeed: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    width: "40px",
    height: "40px",
    viewBox: "0 0 40 40",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      stroke: "none",
      "stroke-width": "1",
      fill: "none",
      "fill-rule": "evenodd",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
        transform: "translate(20.500000, 12.500000) rotate(-270.000000) translate(-20.500000, -12.500000) translate(14.000000, 3.000000)",
        fill: "#000000",
        "fill-rule": "nonzero",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
          x: "0",
          y: "0",
          width: "13",
          height: "19",
          rx: "1"
        })
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
        transform: "translate(20.500000, 27.500000) rotate(-270.000000) translate(-20.500000, -27.500000) translate(14.000000, 18.000000)",
        fill: "#000000",
        "fill-rule": "nonzero",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
          x: "0",
          y: "0",
          width: "13",
          height: "19",
          rx: "1"
        })
      })]
    })
  }),
  cart: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    class: "ec-store-icon-color",
    class: "ec-store-icon-color",
    width: "24px",
    height: "24px",
    viewBox: "0 0 24 24",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
      id: "Typography",
      stroke: "none",
      "stroke-width": "1",
      "fill-rule": "evenodd",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
        id: "gutenberg-widgets-icons",
        transform: "translate(-352.000000, -415.000000)",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
          id: "cart-icon",
          transform: "translate(352.000000, 415.000000)",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
            d: "M4.5269723,4 L2,4 C1.44771525,4 1,3.55228475 1,3 C1,2.44771525 1.44771525,2 2,2 L5.33333333,2 C5.80393835,2 6.21086155,2.32812702 6.31061146,2.788039 L7.22413999,7 L21,7 C21.6640252,7 22.143636,7.63527258 21.9617572,8.27390353 L19.968471,15.272927 C19.8460922,15.7026358 19.4535094,15.9990234 19.0067139,15.9990234 L7.93579102,15.9990234 C7.465186,15.9990234 7.0582628,15.6708964 6.95851289,15.2109844 L4.5269723,4 Z M7.65791824,9 L8.74215205,13.9990234 L18.2517453,13.9990234 L19.6754416,9 L7.65791824,9 Z",
            id: "Path-3",
            "fill-rule": "nonzero"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("circle", {
            id: "Oval-2",
            cx: "9",
            cy: "20",
            r: "2"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("circle", {
            id: "Oval-2",
            cx: "18",
            cy: "20",
            r: "2"
          })]
        })
      })
    })
  }),
  search: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    class: "ec-store-icon-color",
    xmlns: "http://www.w3.org/2000/svg",
    width: "18",
    height: "18",
    viewBox: "0 0 18 18",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      fill: "none",
      "fill-rule": "evenodd",
      stroke: "currentColor",
      "stroke-linecap": "round",
      "stroke-width": "2",
      transform: "translate(1.667 1.667)",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("line", {
        x1: "10.667",
        x2: "14.667",
        y1: "10.667",
        y2: "14.667"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("circle", {
        cx: "6",
        cy: "6",
        r: "6",
        "stroke-linejoin": "round"
      })]
    })
  }),
  categories: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    class: "ec-store-icon-color",
    class: "ec-store-icon-color",
    width: "24px",
    height: "24px",
    viewBox: "0 0 24 24",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
      id: "Typography",
      stroke: "none",
      "stroke-width": "1",
      "fill-rule": "evenodd",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
        id: "gutenberg-widgets-icons",
        transform: "translate(-234.000000, -416.000000)",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
          id: "categories-icon",
          transform: "translate(234.000000, 416.000000)",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("polygon", {
            id: "Triangle",
            points: "3 2 5.5 7 0.5 7"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("polygon", {
            id: "Line",
            "fill-rule": "nonzero",
            points: "8 6 8 4 23 4 23 6"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("polygon", {
            id: "Line",
            "fill-rule": "nonzero",
            points: "8 13 8 11 23 11 23 13"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("polygon", {
            id: "Line",
            "fill-rule": "nonzero",
            points: "8 20 8 18 23 18 23 20"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
            id: "Rectangle",
            stroke: "currentColor",
            "stroke-width": "2",
            fill: "#FFFFFF",
            x: "2",
            y: "11",
            width: "2",
            height: "2"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
            id: "Rectangle",
            stroke: "currentColor",
            "stroke-width": "2",
            fill: "#FFFFFF",
            x: "2",
            y: "18",
            width: "2",
            height: "2",
            rx: "1"
          })]
        })
      })
    })
  }),
  category: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    class: "ec-store-icon-color",
    xmlns: "http://www.w3.org/2000/svg",
    width: "24",
    height: "24",
    viewBox: "0 0 24 24",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      fill: "none",
      stroke: "currentColor",
      "stroke-linecap": "round",
      "stroke-linejoin": "round",
      "stroke-width": "2",
      d: "M4.5638852 2L7.4361148 2C8.32763354 2 8.65091978 2.09282561 8.9768457 2.2671327 9.30277163 2.4414398 9.5585602 2.69722837 9.7328673 3.0231543 9.90717439 3.34908022 10 3.67236646 10 4.5638852L10 7.4361148C10 8.32763354 9.90717439 8.65091978 9.7328673 8.9768457 9.5585602 9.30277163 9.30277163 9.5585602 8.9768457 9.7328673 8.65091978 9.90717439 8.32763354 10 7.4361148 10L4.5638852 10C3.67236646 10 3.34908022 9.90717439 3.0231543 9.7328673 2.69722837 9.5585602 2.4414398 9.30277163 2.2671327 8.9768457 2.09282561 8.65091978 2 8.32763354 2 7.4361148L2 4.5638852C2 3.67236646 2.09282561 3.34908022 2.2671327 3.0231543 2.4414398 2.69722837 2.69722837 2.4414398 3.0231543 2.2671327 3.34908022 2.09282561 3.67236646 2 4.5638852 2zM4.5638852 14L7.4361148 14C8.32763354 14 8.65091978 14.0928256 8.9768457 14.2671327 9.30277163 14.4414398 9.5585602 14.6972284 9.7328673 15.0231543 9.90717439 15.3490802 10 15.6723665 10 16.5638852L10 19.4361148C10 20.3276335 9.90717439 20.6509198 9.7328673 20.9768457 9.5585602 21.3027716 9.30277163 21.5585602 8.9768457 21.7328673 8.65091978 21.9071744 8.32763354 22 7.4361148 22L4.5638852 22C3.67236646 22 3.34908022 21.9071744 3.0231543 21.7328673 2.69722837 21.5585602 2.4414398 21.3027716 2.2671327 20.9768457 2.09282561 20.6509198 2 20.3276335 2 19.4361148L2 16.5638852C2 15.6723665 2.09282561 15.3490802 2.2671327 15.0231543 2.4414398 14.6972284 2.69722837 14.4414398 3.0231543 14.2671327 3.34908022 14.0928256 3.67236646 14 4.5638852 14zM16.5638852 2L19.4361148 2C20.3276335 2 20.6509198 2.09282561 20.9768457 2.2671327 21.3027716 2.4414398 21.5585602 2.69722837 21.7328673 3.0231543 21.9071744 3.34908022 22 3.67236646 22 4.5638852L22 7.4361148C22 8.32763354 21.9071744 8.65091978 21.7328673 8.9768457 21.5585602 9.30277163 21.3027716 9.5585602 20.9768457 9.7328673 20.6509198 9.90717439 20.3276335 10 19.4361148 10L16.5638852 10C15.6723665 10 15.3490802 9.90717439 15.0231543 9.7328673 14.6972284 9.5585602 14.4414398 9.30277163 14.2671327 8.9768457 14.0928256 8.65091978 14 8.32763354 14 7.4361148L14 4.5638852C14 3.67236646 14.0928256 3.34908022 14.2671327 3.0231543 14.4414398 2.69722837 14.6972284 2.4414398 15.0231543 2.2671327 15.3490802 2.09282561 15.6723665 2 16.5638852 2zM16.5638852 14L19.4361148 14C20.3276335 14 20.6509198 14.0928256 20.9768457 14.2671327 21.3027716 14.4414398 21.5585602 14.6972284 21.7328673 15.0231543 21.9071744 15.3490802 22 15.6723665 22 16.5638852L22 19.4361148C22 20.3276335 21.9071744 20.6509198 21.7328673 20.9768457 21.5585602 21.3027716 21.3027716 21.5585602 20.9768457 21.7328673 20.6509198 21.9071744 20.3276335 22 19.4361148 22L16.5638852 22C15.6723665 22 15.3490802 21.9071744 15.0231543 21.7328673 14.6972284 21.5585602 14.4414398 21.3027716 14.2671327 20.9768457 14.0928256 20.6509198 14 20.3276335 14 19.4361148L14 16.5638852C14 15.6723665 14.0928256 15.3490802 14.2671327 15.0231543 14.4414398 14.6972284 14.6972284 14.4414398 15.0231543 14.2671327 15.3490802 14.0928256 15.6723665 14 16.5638852 14z"
    })
  }),
  button: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    class: "ec-store-icon-color",
    width: "24px",
    height: "24px",
    viewBox: "0 0 24 24",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
      id: "Typography",
      stroke: "none",
      "stroke-width": "1",
      "fill-rule": "evenodd",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
        id: "gutenberg-widgets-icons",
        transform: "translate(-345.000000, -280.000000)",
        "fill-rule": "nonzero",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
          id: "button-icon",
          transform: "translate(345.000000, 280.000000)",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
            d: "M4,8 L4,16 L20,16 L20,8 L4,8 Z M4,6 L20,6 C21.1045695,6 22,6.8954305 22,8 L22,16 C22,17.1045695 21.1045695,18 20,18 L4,18 C2.8954305,18 2,17.1045695 2,16 L2,8 C2,6.8954305 2.8954305,6 4,6 Z",
            id: "Rectangle-5"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
            d: "M13.8320367,9.8101295 C14.2137832,9.41102047 14.8467917,9.3969454 15.2459008,9.77869195 C15.6450098,10.1604385 15.6590849,10.793447 15.2773383,11.192556 L12.2122748,14.3970238 C11.8300377,14.7966458 11.1960253,14.8101668 10.7970986,14.427204 L9.5128579,13.1943549 C9.11444327,12.8118837 9.10151859,12.1788506 9.48398981,11.780436 C9.86646103,11.3820214 10.4994941,11.3690967 10.8979087,11.7515679 L11.4594438,12.290632 L13.8320367,9.8101295 Z",
            id: "Line-6"
          })]
        })
      })
    })
  }),
  productPreview: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    width: "72px",
    height: "72px",
    viewBox: "0 0 72 72",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
      id: "Typography",
      stroke: "none",
      "stroke-width": "1",
      fill: "none",
      "fill-rule": "evenodd",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
        id: "gutenberg-widgets",
        transform: "translate(-625.000000, -811.000000)",
        fill: "#AAAAAA",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
          id: "Group-2",
          transform: "translate(571.000000, 756.000000)",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
            id: "product-preview",
            transform: "translate(54.000000, 55.000000)",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
              d: "M6,25 L6,69 L66,69 L66,25 L6,25 Z M4,23 L68,23 L68,71 L4,71 L4,23 Z",
              id: "Rectangle-2-Copy-2",
              "fill-rule": "nonzero"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
              d: "M36.5,23.5 L65.836706,23.5 L67.2237665,22.8226349 L55.0328393,7.34740904 L39.8812213,0.895706316 L40.7501329,7.5 L17.0403124,7.5 L5.04031242,22.5 L6.32093727,22.5 L17.5209373,8.5 L36.5,8.5 L36.5,23.5 Z M42.9573255,16.6099474 L41.1011835,2.50206036 L54.4056315,8.16722056 L66.5284549,23.5566573 L42.9573255,16.6099474 Z",
              id: "Combined-Shape",
              stroke: "#AAAAAA"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
              d: "M29.8056641,41.53125 C29.9375,38.2060547 32.2080078,35.6865234 36.4560547,35.6865234 C40.3232422,35.6865234 42.9306641,37.9863281 42.9306641,41.1210938 C42.9306641,43.3916016 41.7880859,44.9882812 39.8544922,46.1455078 C37.9648438,47.2587891 37.4228516,48.0351562 37.4228516,49.5439453 L37.4228516,50.4375 L34.390625,50.4375 L34.3759766,49.265625 C34.3027344,47.2001953 35.1962891,45.8818359 37.203125,44.6806641 C38.9755859,43.6113281 39.6054688,42.7617188 39.6054688,41.2529297 C39.6054688,39.5976562 38.3017578,38.3818359 36.2949219,38.3818359 C34.2734375,38.3818359 32.9697266,39.5976562 32.8378906,41.53125 L29.8056641,41.53125 Z M35.9287109,57.2197266 C34.859375,57.2197266 34.0097656,56.3994141 34.0097656,55.3300781 C34.0097656,54.2607422 34.859375,53.4404297 35.9287109,53.4404297 C37.0273438,53.4404297 37.8623047,54.2607422 37.8623047,55.3300781 C37.8623047,56.3994141 37.0273438,57.2197266 35.9287109,57.2197266 Z",
              id: "?"
            })]
          })
        })
      })
    })
  }),
  filters: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    class: "ec-store-icon-color",
    xmlns: "http://www.w3.org/2000/svg",
    width: "24",
    height: "24",
    viewBox: "0 0 24 24",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      fill: "none",
      "fill-rule": "evenodd",
      "stroke-linecap": "round",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("line", {
        x1: "2",
        x2: "22",
        y1: "7",
        y2: "7",
        stroke: "currentColor",
        "stroke-width": "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("line", {
        x1: "6",
        x2: "18",
        y1: "13",
        y2: "13",
        stroke: "currentColor",
        "stroke-width": "2"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("line", {
        x1: "11",
        x2: "13",
        y1: "19",
        y2: "19",
        stroke: "currentColor",
        "stroke-width": "2"
      })]
    })
  }),
  cartPage: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    class: "ec-store-icon-color",
    width: "24px",
    height: "24px",
    viewBox: "0 0 24 24",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
      id: "Typography",
      stroke: "none",
      "stroke-width": "1",
      "fill-rule": "evenodd",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
        id: "gutenberg-widgets-icons",
        transform: "translate(-470.000000, -500.000000)",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
          id: "cart-icon",
          transform: "translate(470.000000, 500.000000)",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
            id: "Group-6",
            transform: "translate(2.000000, 3.000000)",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
              d: "M2.5269723,1 L0,1 C-0.55228475,1 -1,0.55228475 -1,-1.11022302e-16 C-1,-0.55228475 -0.55228475,-1 0,-1 L3.33333333,-1 C3.80393835,-1 4.21086155,-0.671872981 4.31061146,-0.211960997 L6.74215205,10.9990234 L16.2517453,10.9990234 L17.6754416,6 L17.0067139,6 C16.4544291,6 16.0067139,5.55228475 16.0067139,5 C16.0067139,4.44771525 16.4544291,4 17.0067139,4 L19,4 C19.6640252,4 20.143636,4.63527258 19.9617572,5.27390353 L17.968471,12.272927 C17.8460922,12.7026358 17.4535094,12.9990234 17.0067139,12.9990234 L5.93579102,12.9990234 C5.465186,12.9990234 5.0582628,12.6708964 4.95851289,12.2109844 L2.5269723,1 Z",
              id: "Path-3",
              "fill-rule": "nonzero"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
              d: "M13.6266547,1.30878828 C14.0084012,0.909679249 14.6414097,0.895604177 15.0405188,1.27735072 C15.4396278,1.65909727 15.4537029,2.29210579 15.0719563,2.69121482 L11.0068929,6.89568259 C10.6246557,7.29530459 9.99064332,7.30882561 9.59171662,6.92586281 L7.61584318,5.00113813 C7.21742856,4.61866691 7.20450388,3.98563386 7.5869751,3.58721924 C7.96944632,3.18880462 8.60247937,3.17587994 9.00089399,3.55835116 L10.2540618,4.78929076 L13.6266547,1.30878828 Z",
              id: "Line-6",
              "fill-rule": "nonzero"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("circle", {
              id: "Oval-2",
              cx: "7",
              cy: "17",
              r: "2"
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("circle", {
              id: "Oval-2",
              cx: "16",
              cy: "17",
              r: "2"
            })]
          })
        })
      })
    })
  }),
  latestProducts: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
    class: "ec-store-icon-color",
    width: "24px",
    height: "24px",
    viewBox: "0 0 24 24",
    version: "1.1",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
      id: "Typography",
      stroke: "none",
      "stroke-width": "1",
      "fill-rule": "evenodd",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
        id: "gutenberg-widgets-icons",
        transform: "translate(-470.000000, -416.000000)",
        "fill-rule": "nonzero",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
          transform: "translate(470.000000, 416.000000)",
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
            d: "M5,17 L5,20 L9,20 L9,17 L5,17 Z M3,15 L11,15 L11,22 L3,22 L3,15 Z",
            id: "Rectangle-2"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
            d: "M5,8 L5,11 L9,11 L9,8 L5,8 Z M3,6 L11,6 L11,13 L3,13 L3,6 Z",
            id: "Rectangle-2-Copy"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
            d: "M15,17 L15,20 L19,20 L19,17 L15,17 Z M13,15 L21,15 L21,22 L13,22 L13,15 Z",
            id: "Rectangle-2"
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
            d: "M15,8 L15,11 L19,11 L19,8 L15,8 Z M13,6 L21,6 L21,13 L13,13 L13,6 Z",
            id: "Rectangle-2-Copy-3"
          })]
        })
      })
    })
  })
};


/***/ }),

/***/ "./js/gutenberg/src/includes/utils.js":
/*!********************************************!*\
  !*** ./js/gutenberg/src/includes/utils.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   buildDangerousHTMLMessageWithTitle: () => (/* binding */ buildDangerousHTMLMessageWithTitle)
/* harmony export */ });
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__);


/**
 * Returns a message with HTML inside, safely wrapped in BaseControl
 * @param {string} title - Block title
 * @param {string} message - HTML message (will be inserted as innerHTML)
 */

function buildDangerousHTMLMessageWithTitle(title, message) {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.BaseControl, {
    label: title,
    __nextHasNoMarginBottom: true,
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)("div", {
      dangerouslySetInnerHTML: {
        __html: message
      }
    })
  });
}

/***/ }),

/***/ "./js/gutenberg/src/index.js":
/*!***********************************!*\
  !*** ./js/gutenberg/src/index.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _includes_icons_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./includes/icons.js */ "./js/gutenberg/src/includes/icons.js");
/* harmony import */ var _store_block_jsx__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./store/block.jsx */ "./js/gutenberg/src/store/block.jsx");
/* harmony import */ var _product_block_jsx__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./product/block.jsx */ "./js/gutenberg/src/product/block.jsx");
/* harmony import */ var _buynow_block_jsx__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./buynow/block.jsx */ "./js/gutenberg/src/buynow/block.jsx");
/* harmony import */ var _search_block_jsx__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./search/block.jsx */ "./js/gutenberg/src/search/block.jsx");
/* harmony import */ var _categories_block_jsx__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./categories/block.jsx */ "./js/gutenberg/src/categories/block.jsx");
/* harmony import */ var _minicart_block_jsx__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./minicart/block.jsx */ "./js/gutenberg/src/minicart/block.jsx");
/* harmony import */ var _category_page_block_jsx__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./category-page/block.jsx */ "./js/gutenberg/src/category-page/block.jsx");
/* harmony import */ var _product_page_block_jsx__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./product-page/block.jsx */ "./js/gutenberg/src/product-page/block.jsx");
/* harmony import */ var _filters_page_block_jsx__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./filters-page/block.jsx */ "./js/gutenberg/src/filters-page/block.jsx");
/* harmony import */ var _cart_page_block_jsx__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./cart-page/block.jsx */ "./js/gutenberg/src/cart-page/block.jsx");
/**
 * Gutenberg Blocks
 *
 * All blocks related JavaScript files should be imported here.
 * You can create a new block folder in this dir and include code
 * for that block here as well.
 *
 * All blocks should be included here since this is the file that
 * Webpack is compiling as the input file.
 */


wp.blocks.updateCategory('ec-store', {
  icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_0__.EcwidIcons.ecwid
});











/***/ }),

/***/ "./js/gutenberg/src/minicart/block.jsx":
/*!*********************************************!*\
  !*** ./js/gutenberg/src/minicart/block.jsx ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./style.scss */ "./js/gutenberg/src/minicart/style.scss");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./editor.scss */ "./js/gutenberg/src/minicart/editor.scss");
/* harmony import */ var _includes_icons_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../includes/icons.js */ "./js/gutenberg/src/includes/icons.js");
/* harmony import */ var _includes_controls_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../includes/controls.js */ "./js/gutenberg/src/includes/controls.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__);









/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */

(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)('ec-store/minicart', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Shopping Cart Icon', 'ecwid-shopping-cart'),
  icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_6__.EcwidIcons.cart,
  category: 'ec-store',
  // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Display shopping bag link and summary', 'ecwid-shopping-cart'),
  supports: {
    customClassName: false,
    className: false,
    html: false,
    inserter: EcwidGutenbergParams.isApiAvailable,
    isPrivate: !EcwidGutenbergParams.isApiAvailable,
    align: true,
    alignWide: false
  },
  attributes: EcwidGutenbergParams.minicartAttributes,
  example: {},
  /**
   * The edit function describes the structure of your block in the context of the editor.
   * This represents what the editor will render when the block is used.
   *
   * The "edit" property must be a valid function.
   *
   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
   */
  edit: function (props) {
    const {
      attributes
    } = props;
    const controls = (0,_includes_controls_js__WEBPACK_IMPORTED_MODULE_7__.EcwidControls)(EcwidGutenbergParams.minicartAttributes, props);
    function buildItem(props, name, type) {
      const item = EcwidGutenbergParams.minicartAttributes[name];
      if (typeof type === 'undefined') {
        type = item.type;
      }
      return controls.select(name);
    }
    const editor = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("div", {
      className: "ec-store-block ec-store-block-minicart",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("div", {
        className: "image"
      })
    });
    return [editor, /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, {
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Appearance', 'ecwid-shopping-cart'),
        initialOpen: true,
        children: [buildItem(props, 'layout', 'select'), buildItem(props, 'icon', 'select'), buildItem(props, 'fixed_shape', 'select')]
      })
    })];
  },
  save: function (props) {
    return false;
  }
});

/***/ }),

/***/ "./js/gutenberg/src/minicart/editor.scss":
/*!***********************************************!*\
  !*** ./js/gutenberg/src/minicart/editor.scss ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/minicart/style.scss":
/*!**********************************************!*\
  !*** ./js/gutenberg/src/minicart/style.scss ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/product-page/block.jsx":
/*!*************************************************!*\
  !*** ./js/gutenberg/src/product-page/block.jsx ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./style.scss */ "./js/gutenberg/src/product-page/style.scss");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./editor.scss */ "./js/gutenberg/src/product-page/editor.scss");
/* harmony import */ var _includes_icons_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../includes/icons.js */ "./js/gutenberg/src/includes/icons.js");
/* harmony import */ var _includes_controls_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../includes/controls.js */ "./js/gutenberg/src/includes/controls.js");
/* harmony import */ var _includes_utils_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../includes/utils.js */ "./js/gutenberg/src/includes/utils.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__);










const blockName = 'ec-store/product-page';
const blockParams = EcwidGutenbergParams.blockParams[blockName];
/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)('ec-store/product-page', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Product Card Large', 'ecwid-shopping-cart'),
  icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_6__.EcwidIcons.product,
  category: 'ec-store',
  // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  attributes: blockParams.attributes,
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Display product page with description and a buy button', 'ecwid-shopping-cart'),
  supports: {
    customClassName: false,
    className: false,
    html: false,
    multiple: false,
    inserter: EcwidGutenbergParams.isWidgetsScreen ? false : true
  },
  example: {},
  /**
   * The edit function describes the structure of your block in the context of the editor.
   * This represents what the editor will render when the block is used.
   *
   * The "edit" property must be a valid function.
   *
   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
   */
  edit: function (props) {
    const {
      attributes
    } = props;
    const saveCallback = function (params) {
      const attributes = {
        'default_product_id': params.newProps.product.id
      };
      EcwidGutenbergParams.products[params.newProps.product.id] = {
        name: params.newProps.product.name,
        imageUrl: params.newProps.product.thumb
      };
      params.originalProps.setAttributes(attributes);
    };
    function openEcwidProductPopup(props) {
      ecwid_open_product_popup({
        'saveCallback': saveCallback,
        'props': props
      });
    }
    const editor = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_includes_controls_js__WEBPACK_IMPORTED_MODULE_7__.EcwidProductBrowserBlock, {
      props: props,
      attributes: attributes,
      icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_6__.EcwidIcons.product,
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Product Card Large', 'ecwid-shopping-cart'),
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
        className: "ec-store-product-page-preview"
      }), !attributes.default_product_id && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
        className: "button-container",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("button", {
          className: "button ec-store-block-button",
          onClick: () => {
            var params = {
              'saveCallback': saveCallback,
              'props': props
            };
            ecwid_open_product_popup(params);
          },
          children: EcwidGutenbergParams.chooseProduct
        })
      })]
    });
    const productMigrationWarning = (0,_includes_utils_js__WEBPACK_IMPORTED_MODULE_8__.buildDangerousHTMLMessageWithTitle)('', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('To improve the look and feel of your store and manage your storefront appearance here, please enable the “Next-gen look and feel of the product list on the storefront” option in your store dashboard (“<a href="admin.php?page=ec-store&ec-store-page=whatsnew">Settings → What’s New</a>”).', 'ecwid-shopping-cart'));
    const productDetailsMigrationWarning = (0,_includes_utils_js__WEBPACK_IMPORTED_MODULE_8__.buildDangerousHTMLMessageWithTitle)('', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('To improve the look and feel of your product page and manage your its appearance here, please enable the “Next-gen look and feel of the product page on the storefront” option in your store dashboard (“<a href="admin.php?page=ec-store&ec-store-page=whatsnew">Settings → What’s New</a>”).', 'ecwid-shopping-cart'));
    const isNewDetailsPage = blockParams.isNewDetailsPage;
    const controls = (0,_includes_controls_js__WEBPACK_IMPORTED_MODULE_7__.EcwidControls)(blockParams.attributes, props);
    return [editor, /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, {
      children: [attributes.default_product_id > 0 && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("div", {
          className: "ec-store-inspector-row",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("label", {
            className: "ec-store-inspector-subheader",
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Linked product', 'ecwid-shopping-cart')
          })
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)("div", {
          className: "ec-store-inspector-row",
          children: [EcwidGutenbergParams.products && EcwidGutenbergParams.products[attributes.default_product_id] && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("label", {
            children: EcwidGutenbergParams.products[attributes.default_product_id].name
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("button", {
            className: "button",
            onClick: () => openEcwidProductPopup(props),
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Change', 'ecwid-shopping-cart')
          })]
        })]
      }), !attributes.default_product_id && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsx)("button", {
          className: "button",
          onClick: () => openEcwidProductPopup(props),
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Choose product', 'ecwid-shopping-cart')
        })
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Appearance', 'ecwid-shopping-cart'),
        initialOpen: false,
        children: [isNewDetailsPage && [controls.select('product_details_layout'), (attributes.product_details_layout === 'TWO_COLUMNS_SIDEBAR_ON_THE_RIGHT' || attributes.product_details_layout === 'TWO_COLUMNS_SIDEBAR_ON_THE_LEFT') && controls.toggle('show_description_under_image'), controls.toolbar('product_details_gallery_layout'), (0,_includes_controls_js__WEBPACK_IMPORTED_MODULE_7__.EcwidInspectorSubheader)((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Product sidebar content', 'ecwid-shopping-cart')), controls.toggle('product_details_show_product_name'), controls.toggle('product_details_show_breadcrumbs'), controls.toggle('product_details_show_product_sku'), controls.toggle('product_details_show_product_price'), controls.toggle('product_details_show_qty'), controls.toggle('product_details_show_weight'), controls.toggle('product_details_show_number_of_items_in_stock'), controls.toggle('product_details_show_in_stock_label'), controls.toggle('product_details_show_wholesale_prices'), controls.toggle('product_details_show_share_buttons'), controls.toggle('product_details_show_navigation_arrows'), controls.toggle('product_details_show_product_photo_zoom')], !isNewDetailsPage && productMigrationWarning]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_9__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Color settings', 'ecwid-shopping-cart'),
        initialOpen: false,
        children: [controls.color('chameleon_color_button'), controls.color('chameleon_color_foreground'), controls.color('chameleon_color_price'), controls.color('chameleon_color_link'), controls.color('chameleon_color_background')]
      })]
    })];
  },
  save: function (props) {
    return null;
  }
});

/***/ }),

/***/ "./js/gutenberg/src/product-page/editor.scss":
/*!***************************************************!*\
  !*** ./js/gutenberg/src/product-page/editor.scss ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/product-page/style.scss":
/*!**************************************************!*\
  !*** ./js/gutenberg/src/product-page/style.scss ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/product/block.jsx":
/*!********************************************!*\
  !*** ./js/gutenberg/src/product/block.jsx ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./style.scss */ "./js/gutenberg/src/product/style.scss");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./editor.scss */ "./js/gutenberg/src/product/editor.scss");
/* harmony import */ var _includes_icons_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../includes/icons.js */ "./js/gutenberg/src/includes/icons.js");
/* harmony import */ var _includes_controls_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../includes/controls.js */ "./js/gutenberg/src/includes/controls.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__);









const blockName = 'ecwid/product-block';
const blockParams = EcwidGutenbergParams.blockParams[blockName];

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)('ecwid/product-block', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Product Card Small', 'ecwid-shopping-cart'),
  // Block title.
  icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_6__.EcwidIcons.product,
  category: 'ec-store',
  // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  attributes: {
    id: {
      type: 'integer'
    },
    show_picture: {
      type: 'boolean',
      default: true
    },
    show_title: {
      type: 'boolean',
      default: true
    },
    show_price: {
      type: 'boolean',
      default: true
    },
    show_options: {
      type: 'boolean',
      default: true
    },
    show_qty: {
      type: 'boolean',
      default: false
    },
    show_addtobag: {
      type: 'boolean',
      default: true
    },
    show_price_on_button: {
      type: 'boolean',
      default: true
    },
    show_border: {
      type: 'boolean',
      default: true
    },
    center_align: {
      type: 'boolean',
      default: true
    }
  },
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Display product with a buy button', 'ecwid-shopping-cart'),
  alignWide: false,
  supports: {
    customClassName: false,
    className: false,
    html: false,
    align: true,
    inserter: EcwidGutenbergParams.isApiAvailable,
    isPrivate: !EcwidGutenbergParams.isApiAvailable
  },
  example: {},
  /**
   * The edit function describes the structure of your block in the context of the editor.
   * This represents what the editor will render when the block is used.
   *
   * The "edit" property must be a valid function.
   *
   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
   */
  edit: function (props) {
    const {
      attributes
    } = props;
    const saveCallback = function (params) {
      const attributes = {
        'id': params.newProps.product.id
      };
      EcwidGutenbergParams.products[params.newProps.product.id] = {
        name: params.newProps.product.name,
        imageUrl: params.newProps.product.thumb
      };
      params.originalProps.setAttributes(attributes);
    };
    const editor = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)(_includes_controls_js__WEBPACK_IMPORTED_MODULE_7__.EcwidProductBrowserBlock, {
      props: props,
      attributes: attributes,
      icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_6__.EcwidIcons.product,
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Product Card Small', 'ecwid-shopping-cart'),
      showDemoButton: blockParams.isDemoStore,
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("div", {
        className: "ec-store-products",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsxs)("div", {
          className: "ec-store-product-block ec-store-product-block-small",
          children: [EcwidGutenbergParams.products && attributes.id && EcwidGutenbergParams.products[attributes.id] && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("div", {
            className: "ec-store-block-image",
            children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("img", {
              src: EcwidGutenbergParams.products[attributes.id].imageUrl
            })
          }), !attributes.id && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("div", {
            className: "ec-store-product ec-store-product-y_sneaker"
          }), !attributes.id && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("div", {
            className: "ec-store-stub-sample"
          }), !attributes.id && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("div", {
            children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("button", {
              className: "button ec-store-block-button",
              onClick: () => {
                var params = {
                  'saveCallback': saveCallback,
                  'props': props
                };
                ecwid_open_product_popup(params);
              },
              children: EcwidGutenbergParams.chooseProduct
            })
          }), EcwidGutenbergParams.products && attributes.id && EcwidGutenbergParams.products[attributes.id] && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("div", {
            className: "ec-store-product-title",
            children: EcwidGutenbergParams.products[attributes.id].name
          })]
        })
      })
    });
    function buildToggle(props, name, label) {
      return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.ToggleControl, {
        label: label,
        checked: props.attributes[name],
        onChange: () => props.setAttributes({
          [name]: !props.attributes[name]
        }),
        __nextHasNoMarginBottom: true
      });
    }
    function openEcwidProductPopup(props) {
      ecwid_open_product_popup({
        'saveCallback': saveCallback,
        'props': props
      });
    }
    return [editor, /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsxs)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, {
      children: [attributes.id && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("div", {
          className: "ec-store-inspector-row",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("label", {
            className: "ec-store-inspector-subheader",
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Displayed product', 'ecwid-shopping-cart')
          })
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsxs)("div", {
          className: "ec-store-inspector-row",
          children: [EcwidGutenbergParams.products && EcwidGutenbergParams.products[attributes.id] && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("label", {
            children: EcwidGutenbergParams.products[attributes.id].name
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("button", {
            className: "button",
            onClick: () => openEcwidProductPopup(props),
            children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Change', 'ecwid-shopping-cart')
          })]
        })]
      }), !attributes.id && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsx)("button", {
          className: "button",
          onClick: () => openEcwidProductPopup(props),
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Choose product', 'ecwid-shopping-cart')
        })
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__._x)('Content', 'gutenberg-product-block', 'ecwid-shopping-cart'),
        initialOpen: false,
        children: [buildToggle(props, 'show_picture', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Picture', 'ecwid-shopping-cart')), buildToggle(props, 'show_title', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Title', 'ecwid-shopping-cart')), buildToggle(props, 'show_price', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Price', 'ecwid-shopping-cart')), buildToggle(props, 'show_options', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Options', 'ecwid-shopping-cart')), buildToggle(props, 'show_qty', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Quantity', 'ecwid-shopping-cart')), buildToggle(props, 'show_addtobag', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('«Buy now» button', 'ecwid-shopping-cart'))]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_8__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Appearance', 'ecwid-shopping-cart'),
        initialOpen: false,
        children: [buildToggle(props, 'show_price_on_button', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Show price inside the «Buy now» button', 'ecwid-shopping-cart')), buildToggle(props, 'show_border', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Add border', 'ecwid-shopping-cart')), buildToggle(props, 'center_align', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)('Center align on a page', 'ecwid-shopping-cart'))]
      })]
    })];
  },
  save: function (props) {
    return false;
  }
});

/***/ }),

/***/ "./js/gutenberg/src/product/editor.scss":
/*!**********************************************!*\
  !*** ./js/gutenberg/src/product/editor.scss ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/product/style.scss":
/*!*********************************************!*\
  !*** ./js/gutenberg/src/product/style.scss ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/search/block.jsx":
/*!*******************************************!*\
  !*** ./js/gutenberg/src/search/block.jsx ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./style.scss */ "./js/gutenberg/src/search/style.scss");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./editor.scss */ "./js/gutenberg/src/search/editor.scss");
/* harmony import */ var _includes_icons_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../includes/icons.js */ "./js/gutenberg/src/includes/icons.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__);






/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */

(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)('ec-store/search', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Product Search Box', 'ecwid-shopping-cart'),
  icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_4__.EcwidIcons.search,
  category: 'ec-store',
  // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Display search box', 'ecwid-shopping-cart'),
  supports: {
    customClassName: false,
    className: false,
    html: false,
    inserter: EcwidGutenbergParams.isApiAvailable,
    isPrivate: !EcwidGutenbergParams.isApiAvailable
  },
  example: {},
  /**
   * The edit function describes the structure of your block in the context of the editor.
   * This represents what the editor will render when the block is used.
   *
   * The "edit" property must be a valid function.
   *
   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
   */
  edit: function (props) {
    const {
      attributes
    } = props;
    const editor = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
      className: "ec-store-block ec-store-block-search",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)("div", {
        class: "image"
      })
    });
    return [editor];
  },
  save: function (props) {
    return false;
  }
});

/***/ }),

/***/ "./js/gutenberg/src/search/editor.scss":
/*!*********************************************!*\
  !*** ./js/gutenberg/src/search/editor.scss ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/search/style.scss":
/*!********************************************!*\
  !*** ./js/gutenberg/src/search/style.scss ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/store/block.jsx":
/*!******************************************!*\
  !*** ./js/gutenberg/src/store/block.jsx ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./style.scss */ "./js/gutenberg/src/store/style.scss");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./editor.scss */ "./js/gutenberg/src/store/editor.scss");
/* harmony import */ var _includes_icons_js__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../includes/icons.js */ "./js/gutenberg/src/includes/icons.js");
/* harmony import */ var _includes_controls_js__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../includes/controls.js */ "./js/gutenberg/src/includes/controls.js");
/* harmony import */ var _includes_utils_js__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../includes/utils.js */ "./js/gutenberg/src/includes/utils.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__);











const blockName = 'ecwid/store-block';
const blockParams = EcwidGutenbergParams.blockParams[blockName];
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)('ecwid/store-block', {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Store Home Page', 'ecwid-shopping-cart'),
  // Block title.
  icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_7__.EcwidIcons.store,
  category: 'ec-store',
  // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  attributes: blockParams.attributes,
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Add storefront (product listing)', 'ecwid-shopping-cart'),
  supports: {
    customClassName: false,
    className: false,
    html: false,
    multiple: false,
    inserter: EcwidGutenbergParams.isWidgetsScreen ? false : true
  },
  example: {},
  /**
   * The edit function describes the structure of your block in the context of the editor.
   * This represents what the editor will render when the block is used.
   *
   * The "edit" property must be a valid function.
   *
   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
   */
  edit: function (props) {
    if (Object.keys(props.attributes).length <= 1) {
      for (var key in blockParams.attributes) {
        if (blockParams.attributes.hasOwnProperty(key)) {
          props.attributes[key] = blockParams.attributes[key].default;
        }
      }
    }
    const {
      attributes
    } = props;

    // legacy reset 
    props.setAttributes({
      widgets: ''
    });
    const productMigrationWarning = (0,_includes_utils_js__WEBPACK_IMPORTED_MODULE_9__.buildDangerousHTMLMessageWithTitle)('', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('To improve the look and feel of your store and manage your storefront appearance here, please enable the “Next-gen look and feel of the product list on the storefront” option in your store dashboard (“<a href="admin.php?page=ec-store&ec-store-page=whatsnew">Settings → What’s New</a>”).', 'ecwid-shopping-cart'));
    const cartIconMessage = (0,_includes_utils_js__WEBPACK_IMPORTED_MODULE_9__.buildDangerousHTMLMessageWithTitle)((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Display cart icon', 'ecwid-shopping-cart'), blockParams.customizeMinicartText);
    const productDetailsMigrationWarning = (0,_includes_utils_js__WEBPACK_IMPORTED_MODULE_9__.buildDangerousHTMLMessageWithTitle)('', (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('To improve the look and feel of your product page and manage its appearance here, please enable the “Next-gen look and feel of the product page on the storefront” option in your store dashboard (“<a href="admin.php?page=ec-store&ec-store-page=whatsnew">Settings → What’s New</a>”).', 'ecwid-shopping-cart'));
    const isNewProductList = blockParams.isNewProductList;
    const isNewDetailsPage = blockParams.isNewDetailsPage;
    const isEnabledProductSubtitles = blockParams.isEnabledProductSubtitles;
    const isLivePreviewEnabled = blockParams.isLivePreviewEnabled;
    const hasCategories = blockParams.attributes.default_category_id && blockParams.attributes.default_category_id.values && blockParams.attributes.default_category_id.values.length > 0;
    const needShowCategories = hasCategories && attributes.storefront_view == 'DEFAULT_CATEGORY_ID';
    if (attributes.show_description_under_image) {
      if (attributes.product_details_layout == 'TWO_COLUMNS_SIDEBAR_ON_THE_LEFT') props.setAttributes({
        product_details_two_columns_with_left_sidebar_show_product_description_on_sidebar: !attributes.show_description_under_image
      });
      if (attributes.product_details_layout == 'TWO_COLUMNS_SIDEBAR_ON_THE_RIGHT') props.setAttributes({
        product_details_two_columns_with_right_sidebar_show_product_description_on_sidebar: !attributes.show_description_under_image
      });
    } else {
      props.setAttributes({
        product_details_two_columns_with_left_sidebar_show_product_description_on_sidebar: '',
        product_details_two_columns_with_right_sidebar_show_product_description_on_sidebar: ''
      });
    }
    if (!needShowCategories) {
      props.setAttributes({
        default_category_id: ''
      });
    }
    if (!hasCategories) {
      props.setAttributes({
        storefront_view: 'COLLAPSE_CATEGORIES'
      });
    }
    const controls = (0,_includes_controls_js__WEBPACK_IMPORTED_MODULE_8__.EcwidControls)(blockParams.attributes, props);
    const [isProductPage, setIsProductPage] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(false);
    const itemsPanelBodyRef = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useRef)([]);
    const panelBodyElement = el => {
      let i = itemsPanelBodyRef.current.length;
      if (el !== null) itemsPanelBodyRef.current[i] = el;
    };
    const isPreviewInFrame = () => {
      return document.querySelector('[name=editor-canvas]') != null ? true : false;
    };
    const getPreviewFrameContent = () => {
      return document.querySelector('[name=editor-canvas]').contentWindow;
    };
    let w = window;
    if (isPreviewInFrame()) w = getPreviewFrameContent();
    const handleOnToggle = isToggled => {
      if (!isLivePreviewEnabled) return;
      if (isToggled) {
        setIsProductPage(false);
        itemsPanelBodyRef.current.map(function (e) {
          if (e.classList.contains('is-opened')) {
            e.querySelector('button').click();
            if (e.classList.contains('ec-store-panelbody-product-details')) {
              if (attributes.storefront_view == 'FILTERS_PAGE') w.Ecwid.openPage('search');else w.Ecwid.openPage('category');
            }
          }
        });
      }
    };
    const handleOnToggleProduct = isToggled => {
      if (!isLivePreviewEnabled) return;
      if (isToggled) {
        setIsProductPage(true);
        handleOnToggle(isToggled);
        w.Ecwid.openPage('product', {
          'id': blockParams.randomProductId
        });
      }
    };
    const [isOpen, setOpen] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(false);
    const openModal = () => setOpen(true);
    const closeModal = () => setOpen(false);
    if (typeof w.Ecwid != 'undefined') {
      w.Ecwid.OnPageLoaded.add(function (page) {
        if (page.type == 'PRODUCT') {
          blockParams.randomProductId = page.productId;
        }
      });
      w.Ecwid.OnPageSwitch.add(function (page) {
        if (page.type != 'PRODUCT' && page.type != 'CATEGORY' && page.type != 'SEARCH') {
          openModal();
          return false;
        }
      });
    }
    let editor = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)("div", {
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_includes_controls_js__WEBPACK_IMPORTED_MODULE_8__.EcwidProductBrowserBlock, {
        props: props,
        attributes: attributes,
        icon: _includes_icons_js__WEBPACK_IMPORTED_MODULE_7__.EcwidIcons.store,
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Store Home Page', 'ecwid-shopping-cart'),
        showDemoButton: blockParams.isDemoStore,
        isLivePreviewEnabled: isLivePreviewEnabled,
        blockParams: blockParams,
        isProductPage: isProductPage,
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_includes_controls_js__WEBPACK_IMPORTED_MODULE_8__.EcwidStoreBlockInner, {
          state: attributes.storefront_view
        })
      }), isOpen && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Modal, {
        title: "Edit Mode",
        onRequestClose: closeModal,
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)("p", {
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('The transition to this page is disabled in the editor, on a real storefront it works as it should.', 'ecwid-shopping-cart')
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
          variant: "secondary",
          onClick: closeModal,
          children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Continue Editing Page', 'ecwid-shopping-cart')
        })]
      })]
    });
    return [editor, /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, {
      children: [hasCategories && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Category List Appearance', 'ecwid-shopping-cart'),
        initialOpen: false,
        ref: panelBodyElement,
        onToggle: handleOnToggle,
        children: [isNewProductList && [controls.select('product_list_category_title_behavior'), attributes.product_list_category_title_behavior !== 'SHOW_TEXT_ONLY' && [controls.buttonGroup('product_list_category_image_size'), controls.toolbar('product_list_category_image_aspect_ratio')]], !isNewProductList && productMigrationWarning]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Product List Appearance', 'ecwid-shopping-cart'),
        initialOpen: false,
        ref: panelBodyElement,
        onToggle: handleOnToggle,
        children: [isNewProductList && [controls.toggle('product_list_show_product_images'), attributes.product_list_show_product_images && [controls.buttonGroup('product_list_image_size'), controls.toolbar('product_list_image_aspect_ratio')], controls.toolbar('product_list_product_info_layout'), controls.select('product_list_title_behavior'), isEnabledProductSubtitles && controls.select('product_list_subtitles_behavior'), controls.select('product_list_price_behavior'), controls.select('product_list_sku_behavior'), controls.select('product_list_buybutton_behavior'), controls.toggle('product_list_show_additional_image_on_hover'), controls.toggle('product_list_show_frame')], !isNewProductList && productMigrationWarning]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Product Page Appearance', 'ecwid-shopping-cart'),
        initialOpen: false,
        ref: panelBodyElement,
        onToggle: handleOnToggleProduct,
        className: "ec-store-panelbody-product-details",
        children: [isNewDetailsPage && [controls.select('product_details_layout'), (attributes.product_details_layout === 'TWO_COLUMNS_SIDEBAR_ON_THE_RIGHT' || attributes.product_details_layout === 'TWO_COLUMNS_SIDEBAR_ON_THE_LEFT') && controls.toggle('show_description_under_image'), controls.toolbar('product_details_gallery_layout'), (0,_includes_controls_js__WEBPACK_IMPORTED_MODULE_8__.EcwidInspectorSubheader)((0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Product sidebar content', 'ecwid-shopping-cart')), controls.toggle('product_details_show_product_name'), isEnabledProductSubtitles && controls.toggle('product_details_show_subtitle'), controls.toggle('product_details_show_breadcrumbs'), controls.toggle('product_details_show_product_sku'), controls.toggle('product_details_show_product_price'), controls.toggle('product_details_show_qty'), controls.toggle('product_details_show_weight'), controls.toggle('product_details_show_number_of_items_in_stock'), controls.toggle('product_details_show_in_stock_label'), controls.toggle('product_details_show_wholesale_prices'), controls.toggle('product_details_show_share_buttons'), controls.toggle('product_details_show_navigation_arrows'), controls.toggle('product_details_show_product_photo_zoom')], !isNewDetailsPage && productMigrationWarning]
      }), hasCategories && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Store Front Page', 'ecwid-shopping-cart'),
        initialOpen: false,
        ref: panelBodyElement,
        onToggle: handleOnToggle,
        children: controls.radioButtonWithDescription('storefront_view', isLivePreviewEnabled)
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Store Navigation', 'ecwid-shopping-cart'),
        initialOpen: false,
        ref: panelBodyElement,
        onToggle: handleOnToggle,
        children: [hasCategories && controls.toggle('show_categories'), controls.toggle('show_search'), controls.toggle('show_breadcrumbs'), isNewProductList && controls.toggle('show_footer_menu'), controls.toggle('show_signin_link'), controls.toggle('product_list_show_sort_viewas_options'), cartIconMessage]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_10__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Color settings', 'ecwid-shopping-cart'),
        initialOpen: false,
        ref: panelBodyElement,
        onToggle: handleOnToggle,
        children: [controls.color('chameleon_color_button'), controls.color('chameleon_color_foreground'), controls.color('chameleon_color_price'), controls.color('chameleon_color_link'), controls.color('chameleon_color_background')]
      })]
    })];
  },
  save: function (props) {
    var widgets = ['productbrowser'];
    if (props.attributes.show_categories) {
      widgets[widgets.length] = 'categories';
    }
    if (props.attributes.show_search) {
      widgets[widgets.length] = 'search';
    }
    const shortcodeAttributes = {
      'widgets': widgets.join(' '),
      'default_category_id': typeof props.attributes.default_category_id !== 'undefined' ? props.attributes.default_category_id : ''
    };
    const shortcode = new wp.shortcode({
      'tag': blockParams.shortcodeName,
      'attrs': shortcodeAttributes,
      'type': 'single'
    });
    return shortcode.string();
  },
  deprecated: [{
    attributes: {
      widgets: {
        type: 'string'
      },
      categories_per_row: {
        type: 'integer'
      },
      grid: {
        type: 'string'
      },
      list: {
        type: 'integer'
      },
      table: {
        type: 'integer'
      },
      default_category_id: {
        type: 'integer'
      },
      default_product_id: {
        type: 'integer'
      },
      category_view: {
        type: 'string'
      },
      search_view: {
        type: 'string'
      },
      minicart_layout: {
        type: 'string'
      }
    },
    save: function (props) {
      return null;
    }
  }, {
    attributes: {
      widgets: {
        type: 'string',
        default: 'productbrowser'
      },
      default_category_id: {
        type: 'integer',
        default: 0
      }
    },
    migrate: function (attributes) {
      return {
        'widgets': attributes.widgets,
        'default_category_id': attributes.default_category_id
      };
    },
    save: function (props) {
      var shortcodeAttributes = {};
      const attrs = ['widgets', 'default_category_id'];
      for (var i = 0; i < attrs.length; i++) {
        shortcodeAttributes[attrs[i]] = props.attributes[attrs[i]];
      }
      shortcodeAttributes.default_product_id = 0;
      var shortcode = new wp.shortcode({
        'tag': blockParams.shortcodeName,
        'attrs': shortcodeAttributes,
        'type': 'single'
      });
      return shortcode.string();
    }
  }, {
    save: function (props) {
      return '[ecwid]';
    }
  }, {
    save: function (props) {
      return '[ecwid widgets="productbrowser" default_category_id="0" default_product_id="0"]';
    }
  }, {
    save: function (props) {
      return '[ecwid widgets="productbrowser" default_category_id="0"]';
    }
  }],
  transforms: {
    from: [{
      type: 'shortcode',
      tag: ['ecwid', 'ec_store'],
      attributes: {
        default_category_id: {
          type: 'integer',
          shortcode: function (named) {
            return named.default_category_id;
          }
        },
        show_categories: {
          type: 'boolean',
          shortcode: function (attributes) {
            return attributes.named.widgets.indexOf('categories') !== -1;
          }
        },
        show_search: {
          type: 'boolean',
          shortcode: function (attributes) {
            return attributes.named.widgets.indexOf('search') !== -1;
          }
        }
      },
      priority: 10
    }]
  }
});

/***/ }),

/***/ "./js/gutenberg/src/store/editor.scss":
/*!********************************************!*\
  !*** ./js/gutenberg/src/store/editor.scss ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./js/gutenberg/src/store/style.scss":
/*!*******************************************!*\
  !*** ./js/gutenberg/src/store/style.scss ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ ((module) => {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ ((module) => {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["ReactJSXRuntime"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"main": 0,
/******/ 			"./style-main": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = globalThis["webpackChunkecwid_blocks"] = globalThis["webpackChunkecwid_blocks"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["./style-main"], () => (__webpack_require__("./js/gutenberg/src/index.js")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
//# sourceMappingURL=index.js.map