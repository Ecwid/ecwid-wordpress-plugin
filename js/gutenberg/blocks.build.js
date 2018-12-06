!function(e){function t(r){if(n[r])return n[r].exports;var l=n[r]={i:r,l:!1,exports:{}};return e[r].call(l.exports,l,l.exports,t),l.l=!0,l.exports}var n={};t.m=e,t.c=n,t.d=function(e,n,r){t.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:r})},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="",t(t.s=0)}([function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});n(1),n(5)},function(e,t,n){"use strict";function r(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}var l=n(2),o=(n.n(l),n(3)),i=(n.n(o),n(4)),a=wp.i18n,c=a.__,s=(a._x,i.a,wp.blocks),p=(s.BlockControls,s.registerBlockType),m=wp.editor,u=m.InspectorControls,d=(m.AlignmentToolbar,m.withColors,wp.components),w=d.PanelBody,g=d.PanelRow,h=d.ToggleControl,_=d.ButtonGroup,f=d.Button,b=(d.IconButton,d.BaseControl),E=d.Toolbar,y=d.ColorPalette,v=d.ColorIndicator,x=wp.compose.withState;wp.element.Fragment;p("ecwid/store-block",{title:EcwidGutenbergParams.storeBlockTitle,icon:wp.element.createElement("svg",{className:"dashicon",viewBox:"0 0 20 20",width:"20",height:"20"},wp.element.createElement("path",{d:EcwidGutenbergParams.storeIcon})),category:"common",attributes:EcwidGutenbergStoreBlockParams.attributes,description:c("Add storefront (product listing)","ecwid-shopping-cart"),supports:{customClassName:!1,className:!1,html:!1,multiple:!1},edit:function(e){function t(e,t,n){return wp.element.createElement(h,{label:n,checked:e.attributes[t],onChange:function(){return e.setAttributes(r({},t,!e.attributes[t]))}})}function n(e,t,n,l){return wp.element.createElement(b,{label:n},wp.element.createElement(E,{controls:l.map(function(n){return{icon:i.a[n.icon],title:n.title,isActive:e.attributes[t]===n.value,className:"ecwid-toolbar-icon",onClick:function(){return e.setAttributes(r({},t,n.value))}}})}))}function l(e,t,n,l){return wp.element.createElement(b,{label:n},wp.element.createElement("select",{onChange:function(n){e.setAttributes(r({},t,n.target.value))}},l.map(function(n){return wp.element.createElement("option",{value:n.value,selected:e.attributes[t]==n.value},n.title)})))}function o(e,t,n){return wp.element.createElement(b,{label:n},wp.element.createElement("input",{type:"text",value:e.attributes[t],onChange:function(n){e.setAttributes(r({},t,n.target.value))}}))}function a(e,t,n,l){return wp.element.createElement(b,{label:n},wp.element.createElement(_,null,l.map(function(n){return wp.element.createElement(f,{isDefault:!0,isButton:!0,isPrimary:C[t]==n.value,onClick:function(){return e.setAttributes(r({},t,n.value))}},n.title)})))}function s(e,t){return wp.element.createElement(b,{label:e},wp.element.createElement("div",{dangerouslySetInnerHTML:{__html:t}}))}function p(e,r,i){var c=EcwidGutenbergStoreBlockParams.attributes[r];return"undefined"===typeof i&&(i=c.type),"default_category_id"===i&&(i=c.values&&c.values.length>1?"select":"textbox"),"buttonGroup"===i?a(e,c.name,c.title,c.values):"toolbar"===i?n(e,c.name,c.title,c.values):"select"===i?l(e,c.name,c.title,c.values):"colorPalette"===i?m(e,c.name,c.title):"text"===i?s(c.title,c.message):"textbox"===i?o(e,c.name,c.title):t(e,c.name,c.title)}function m(e,t,n){var l=wp.element.createElement("span",null,n,wp.element.createElement(v,{colorValue:C[t]}));return wp.element.createElement(b,{label:l,className:"ec-store-color-picker"},wp.element.createElement(y,{value:C[t],colors:B,onChange:function(n){return e.setAttributes(r({},t,n))}}))}function d(e){function t(e){o(function(t){return{manual:"manual",color:e}}),a.setAttributes(r({},i,e))}var n=e.manual,l=e.color,o=e.setState,i=arguments[0].name,a=arguments[0].props,s=EcwidGutenbergStoreBlockParams.attributes[i].title,p=null===n&&null!==a.attributes[i]&&""!==a.attributes[i]||"manual"===n;p?null!==l&&a.setAttributes(r({},i,l)):a.setAttributes(r({},i,null));var m=a.attributes[i],u=wp.element.createElement("span",null,s,null!==m&&wp.element.createElement(v,{colorValue:C[i]}));return wp.element.createElement(b,{label:u,className:"ec-store-color-picker"},wp.element.createElement("select",{onChange:function(e){return o(function(e){return{manual:event.target.value,color:e.color}})}},wp.element.createElement("option",{value:"auto",selected:!p},c("Detect automatically","ecwid-shopping-cart")),wp.element.createElement("option",{value:"manual",selected:p},c("Set manually","ecwid-shopping-cart"))),p&&wp.element.createElement(y,{value:m,colors:B,onChange:t}))}function k(e){var t=e.count,n=e.setState;return wp.element.createElement("div",null,wp.element.createElement("button",{onClick:function(){return n(function(e){return{count:e.count+1}})}},"text ",t," ",arguments[0].color))}var C=e.attributes;e.setAttributes({widgets:""});var P=wp.element.createElement("div",{className:"ec-store-block ec-store-block-product-browser"},wp.element.createElement("div",{className:"ec-store-block-header"},wp.element.createElement("svg",{className:"dashicon",viewBox:"0 0 20 20",width:"20",height:"20"},wp.element.createElement("path",{d:EcwidGutenbergParams.storeIcon})),EcwidGutenbergParams.isDemoStore&&c("Demo store","ecwid-shopping-cart"),!EcwidGutenbergParams.isDemoStore&&EcwidGutenbergStoreBlockParams.storeBlockTitle),EcwidGutenbergParams.isDemoStore&&wp.element.createElement("div",null,wp.element.createElement("a",{className:"button button-primary",href:"admin.php?page=ec-store"},c("Set up your store","ecwid-shopping-cart")))),B=[{name:c("Pale pink"),slug:"pale-pink",color:"#f78da7"},{name:c("Vivid red"),slug:"vivid-red",color:"#cf2e2e"},{name:c("Luminous vivid orange"),slug:"luminous-vivid-orange",color:"#ff6900"},{name:c("Luminous vivid amber"),slug:"luminous-vivid-amber",color:"#fcb900"},{name:c("Light green cyan"),slug:"light-green-cyan",color:"#7bdcb5"},{name:c("Vivid green cyan"),slug:"vivid-green-cyan",color:"#00d084"},{name:c("Pale cyan blue"),slug:"pale-cyan-blue",color:"#8ed1fc"},{name:c("Vivid cyan blue"),slug:"vivid-cyan-blue",color:"#0693e3"},{name:c("Very light gray"),slug:"very-light-gray",color:"#eeeeee"},{name:c("Cyan bluish gray"),slug:"cyan-bluish-gray",color:"#abb8c3"},{name:c("Very dark gray"),slug:"very-dark-gray",color:"#313131"}],G=x({manual:null,color:null})(d),N=(x({count:0})(k),s("",c('To improve the look and feel of your store and manage your storefront appearance here, please enable the \u201cNext-gen look and feel of the product list on the storefront\u201d option in your store dashboard (\u201c<a href="admin.php?page=ec-store&ec-store-page=whatsnew">Settings \u2192 What\u2019s New</a>\u201d).',"ecwid-shopping-cart"))),S=s(c("Display cart icon","ecwid-shopping-cart"),EcwidGutenbergParams.customizeMinicartText),O=(s("",c('To improve the look and feel of your product page and manage your its appearance here, please enable the \u201cNext-gen look and feel of the product page on the storefront\u201d option in your store dashboard (\u201c<a href="admin.php?page=ec-store&ec-store-page=whatsnew">Settings \u2192 What\u2019s New</a>\u201d).',"ecwid-shopping-cart")),EcwidGutenbergStoreBlockParams.is_new_product_list),A=EcwidGutenbergStoreBlockParams.is_new_details_page,T=EcwidGutenbergStoreBlockParams.attributes.default_category_id&&EcwidGutenbergStoreBlockParams.attributes.default_category_id.values&&EcwidGutenbergStoreBlockParams.attributes.default_category_id.values.length>0;return[P,wp.element.createElement(u,null,T&&wp.element.createElement(w,{title:c("Category List Appearance","ecwid-shopping-cart"),initialOpen:!1},O&&p(e,"product_list_category_title_behavior","select"),O&&"SHOW_TEXT_ONLY"!==C.product_list_category_title_behavior&&p(e,"product_list_category_image_size","buttonGroup"),O&&"SHOW_TEXT_ONLY"!==C.product_list_category_title_behavior&&p(e,"product_list_category_image_aspect_ratio","toolbar"),!O&&N),wp.element.createElement(w,{title:c("Product List Appearance","ecwid-shopping-cart"),initialOpen:!1},O&&p(e,"product_list_show_product_images","toggle"),O&&C.product_list_show_product_images&&p(e,"product_list_image_size","buttonGroup"),O&&C.product_list_show_product_images&&p(e,"product_list_image_aspect_ratio","toolbar"),O&&p(e,"product_list_product_info_layout","toolbar"),O&&p(e,"product_list_title_behavior","select"),O&&p(e,"product_list_price_behavior","select"),O&&p(e,"product_list_sku_behavior","select"),O&&p(e,"product_list_buybutton_behavior","select"),O&&p(e,"product_list_show_additional_image_on_hover","toggle"),O&&p(e,"product_list_show_frame","toggle"),!O&&N),wp.element.createElement(w,{title:c("Product Page Appearance","ecwid-shopping-cart"),initialOpen:!1},A&&p(e,"product_details_layout","select"),A&&("TWO_COLUMNS_SIDEBAR_ON_THE_RIGHT"==C.product_details_layout||"TWO_COLUMNS_SIDEBAR_ON_THE_LEFT"==C.product_details_layout)&&p(e,"show_description_under_image","toggle"),A&&p(e,"product_details_gallery_layout","toolbar"),A&&wp.element.createElement(g,null,wp.element.createElement("label",{className:"ec-store-inspector-subheader"},c("Product sidebar content","ecwid-shopping-cart"))),A&&p(e,"product_details_show_product_name","toggle"),A&&p(e,"product_details_show_breadcrumbs","toggle"),A&&p(e,"product_details_show_product_sku","toggle"),A&&p(e,"product_details_show_product_price","toggle"),A&&p(e,"product_details_show_qty","toggle"),A&&p(e,"product_details_show_number_of_items_in_stock","toggle"),A&&p(e,"product_details_show_in_stock_label","toggle"),A&&p(e,"product_details_show_wholesale_prices","toggle"),A&&p(e,"product_details_show_share_buttons","toggle"),!A&&N),T&&wp.element.createElement(w,{title:c("Store Front Page","ecwid-shopping-cart"),initialOpen:!1},p(e,"default_category_id","default_category_id")),wp.element.createElement(w,{title:c("Store Navigation","ecwid-shopping-cart"),initialOpen:!1},p(e,"show_categories","toggle"),p(e,"show_search","toggle"),p(e,"show_breadcrumbs","toggle"),O&&p(e,"show_footer_menu","toggle"),p(e,"show_signin_link","toggle"),p(e,"product_list_show_sort_viewas_options","toggle"),S),wp.element.createElement(w,{title:c("Color settings","ecwid-shopping-cart"),initialOpen:!1},wp.element.createElement(G,{props:e,name:"chameleon_color_button"}),wp.element.createElement(G,{props:e,name:"chameleon_color_foreground"}),wp.element.createElement(G,{props:e,name:"chameleon_color_price"}),wp.element.createElement(G,{props:e,name:"chameleon_color_link"}),wp.element.createElement(G,{props:e,name:"chameleon_color_background"})))]},save:function(e){var t=["productbrowser"];e.attributes.show_categories&&(t[t.length]="categories"),e.attributes.show_search&&(t[t.length]="search");var n={widgets:t.join(" "),default_category_id:"undefined"!=typeof e.attributes.default_category_id?e.attributes.default_category_id:0};return new wp.shortcode({tag:EcwidGutenbergParams.storeShortcodeName,attrs:n,type:"single"}).string()},deprecated:[{attributes:{widgets:{type:"string"},categories_per_row:{type:"integer"},grid:{type:"string"},list:{type:"integer"},table:{type:"integer"},default_category_id:{type:"integer"},default_product_id:{type:"integer"},category_view:{type:"string"},search_view:{type:"string"},minicart_layout:{type:"string"}},save:function(e){return null}},{attributes:{widgets:{type:"string",default:"productbrowser"},default_category_id:{type:"integer",default:0}},migrate:function(e){return{widgets:e.widgets,default_category_id:e.default_category_id}},save:function(e){for(var t={},n=["widgets","default_category_id"],r=0;r<n.length;r++)t[n[r]]=e.attributes[n[r]];return t.default_product_id=0,new wp.shortcode({tag:EcwidGutenbergParams.storeShortcodeName,attrs:t,type:"single"}).string()}},{save:function(e){return"[ecwid]"}},{save:function(e){return'[ecwid widgets="productbrowser" default_category_id="0" default_product_id="0"]'}},{save:function(e){return'[ecwid widgets="productbrowser" default_category_id="0"]'}}],transforms:{from:[{type:"shortcode",tag:["ecwid","ec_store"],attributes:{default_category_id:{type:"integer",shortcode:function(e){return e.default_category_id}},show_categories:{type:"boolean",shortcode:function(e){return-1!==e.named.widgets.indexOf("categories")}},show_search:{type:"boolean",shortcode:function(e){return-1!==e.named.widgets.indexOf("search")}}},priority:10}]}})},function(e,t){},function(e,t){},function(e,t,n){"use strict";n.d(t,"a",function(){return r});var r={store:wp.element.createElement("svg",{version:"1.1",x:"0px",y:"0px",viewBox:"0 0 20 20","enable-background":"new 0 0 20 20"},wp.element.createElement("path",{fill:"#555d66",d:"M15.32,15.58c-0.37,0-0.66,0.3-0.66,0.67c0,0.37,0.3,0.67,0.66,0.67c0.37,0,0.67-0.3,0.67-0.67 C15.98,15.88,15.69,15.58,15.32,15.58z M15.45,0H4.55C2.04,0,0,2.04,0,4.55v10.91C0,17.97,2.04,20,4.55,20h10.91c2.51,0,4.55-2.04,4.55-4.55V4.55 C20,2.04,17.96,0,15.45,0z M12.97,4.94C13.54,4.94,14,5.4,14,5.96s-0.46,1.03-1.03,1.03c-0.57,0-1.03-0.46-1.03-1.03 C11.95,5.4,12.41,4.94,12.97,4.94z M12.97,8.02c0.57,0,1.03,0.46,1.03,1.03c0,0.57-0.46,1.03-1.03,1.03c-0.57,0-1.03-0.46-1.03-1.03 C11.95,8.48,12.41,8.02,12.97,8.02z M9.98,4.94c0.57,0,1.03,0.46,1.03,1.03s-0.46,1.03-1.03,1.03c-0.57,0-1.03-0.46-1.03-1.03 C8.95,5.4,9.41,4.94,9.98,4.94z M9.98,8.02c0.57,0,1.03,0.46,1.03,1.03s-0.46,1.03-1.03,1.03c-0.57,0-1.03-0.46-1.03-1.03 C8.95,8.48,9.41,8.02,9.98,8.02z M7.03,4.94c0.57,0,1.03,0.46,1.03,1.03S7.6,6.99,7.03,6.99C6.46,6.99,6,6.53,6,5.96 C6,5.4,6.46,4.94,7.03,4.94z M7.03,8.02c0.57,0,1.03,0.46,1.03,1.03s-0.46,1.03-1.03,1.03C6.46,10.08,6,9.62,6,9.05 C6,8.48,6.46,8.02,7.03,8.02z M4.6,18.02c-1.02,0-1.86-0.83-1.86-1.86c0-1.03,0.83-1.86,1.86-1.86c1.03,0,1.86,0.83,1.86,1.86 C6.45,17.19,5.62,18.02,4.6,18.02z M15.32,18.1c-1.02,0-1.86-0.83-1.86-1.86c0-1.03,0.83-1.86,1.86-1.86c1.03,0,1.86,0.83,1.86,1.86 C17.17,17.27,16.34,18.1,15.32,18.1z M18.48,2.79l-1.92,7.14c-0.51,1.91-2.03,3.1-4,3.1H7.2c-1.91,0-3.26-1.09-3.84-2.91L1.73,5 C1.7,4.9,1.72,4.79,1.78,4.71c0.06-0.09,0.16-0.14,0.27-0.14l0.31,0c0.75,0,1.41,0.49,1.64,1.2l1.2,3.76 c0.32,1.02,1.26,1.7,2.33,1.7h4.81c1.1,0,2.08-0.74,2.36-1.81l1.55-5.78c0.2-0.75,0.89-1.28,1.67-1.28h0.24 c0.1,0,0.2,0.05,0.26,0.13C18.48,2.58,18.5,2.68,18.48,2.79z M4.6,15.5c-0.37,0-0.66,0.3-0.66,0.67c0,0.37,0.3,0.67,0.66,0.67c0.37,0,0.67-0.3,0.67-0.67 S4.96,15.5,4.6,15.5z"})),product:wp.element.createElement("svg",{version:"1.1",x:"0px",y:"0px",viewBox:"0 0 20 20","enable-background":"new 0 0 20 20"},wp.element.createElement("path",{fill:"#231F20",d:"M16.43,5.12c-0.13-1.19-0.15-1.19-1.35-1.33c-0.21-0.02-0.21-0.02-0.43-0.05c-0.01,0.06,0.06,0.78,0.14,1.13 c0.57,0.37,0.87,0.98,0.87,1.71c0,1.14-0.93,2.07-2.07,2.07s-2.07-0.93-2.07-2.07c0-0.54,0.09-0.97,0.55-1.4 c-0.06-0.61-0.19-1.54-0.18-1.64C10.14,3.46,8.72,3.46,8.58,3.6l-8.17,8.13c-0.56,0.55-0.56,1.43,0,1.97l5.54,5.93 c0.56,0.55,1.46,0.55,2.01,0l8.67-8.14C17.04,11.09,16.68,7.14,16.43,5.12z M16.06,0.04c-1.91,0-3.46,1.53-3.46,3.41c0,0.74,0.4,3.09,0.44,3.28c0.07,0.34,0.52,0.56,0.86,0.49 C14,7.19,14.07,7.15,14.12,7.1c0.24-0.11,0.32-0.39,0.25-0.68c-0.09-0.45-0.39-2.44-0.39-2.94c0-1.16,0.94-2.09,2.11-2.09 c1.24,0,2.11,0.96,2.11,2.34c0,2.43-0.31,4.23-0.32,4.26c-0.1,0.17-0.1,0.38-0.03,0.55c0.03,0.17,0.13,0.31,0.28,0.4 c0.1,0.06,0.22,0.09,0.33,0.09c0.21,0,0.42-0.1,0.54-0.3c0.06-0.09,0.52-2.17,0.52-5.03C19.52,1.61,18.04,0.04,16.06,0.04z"})),aspect169:wp.element.createElement("svg",{width:"40px",height:"40px",viewBox:"0 0 40 40",version:"1.1"},wp.element.createElement("g",{stroke:"none","stroke-width":"1",fill:"none","fill-rule":"evenodd"},wp.element.createElement("rect",{fill:"#000000",x:"9",y:"14",width:"22",height:"12",rx:"2"}))),aspect916:wp.element.createElement("svg",{width:"40px",height:"40px",viewBox:"0 0 40 40",version:"1.1"},"    ",wp.element.createElement("g",{stroke:"none","stroke-width":"1",fill:"none","fill-rule":"evenodd"},wp.element.createElement("rect",{fill:"#000000",x:"14",y:"9",width:"12",height:"22",rx:"2"}))),aspect11:wp.element.createElement("svg",{width:"40px",height:"40px",viewBox:"0 0 40 40",version:"1.1"},wp.element.createElement("g",{stroke:"none","stroke-width":"1",fill:"none","fill-rule":"evenodd"},wp.element.createElement("rect",{fill:"#000000",x:"12",y:"12",width:"16",height:"16",rx:"2"}))),aspect34:wp.element.createElement("svg",{width:"40px",height:"40px",viewBox:"0 0 40 40",version:"1.1"},wp.element.createElement("g",{stroke:"none","stroke-width":"1",fill:"none","fill-rule":"evenodd"},wp.element.createElement("rect",{fill:"#000000",x:"12",y:"10",width:"16",height:"20",rx:"2"}))),aspect43:wp.element.createElement("svg",{width:"40px",height:"40px",viewBox:"0 0 40 40",version:"1.1"},wp.element.createElement("g",{stroke:"none","stroke-width":"1",fill:"none","fill-rule":"evenodd"},wp.element.createElement("rect",{fill:"#000000",x:"10",y:"12",width:"20",height:"16",rx:"2"}))),textalignleft:wp.element.createElement("svg",{width:"40px",height:"40px",viewBox:"0 0 40 40",version:"1.1"},wp.element.createElement("g",{stroke:"none","stroke-width":"1",fill:"none","fill-rule":"evenodd"},wp.element.createElement("rect",{fill:"#000000",x:"13",y:"13",width:"14",height:"2"}),wp.element.createElement("rect",{fill:"#000000",x:"13",y:"16",width:"9",height:"2"}),wp.element.createElement("rect",{fill:"#000000",x:"13",y:"19",width:"13",height:"2"}),wp.element.createElement("rect",{fill:"#000000",x:"13",y:"22",width:"9",height:"2"}),wp.element.createElement("rect",{fill:"#000000",x:"13",y:"25",width:"14",height:"2"}))),textaligncenter:wp.element.createElement("svg",{width:"40px",height:"40px",viewBox:"0 0 40 40",version:"1.1"},wp.element.createElement("g",{stroke:"none","stroke-width":"1",fill:"none","fill-rule":"evenodd"},wp.element.createElement("rect",{fill:"#000000",x:"13",y:"13",width:"14",height:"2"}),wp.element.createElement("rect",{fill:"#000000",x:"16",y:"16",width:"8",height:"2"}),wp.element.createElement("rect",{fill:"#000000",x:"14",y:"19",width:"12",height:"2"}),wp.element.createElement("rect",{fill:"#000000",x:"16",y:"22",width:"8",height:"2"}),wp.element.createElement("rect",{fill:"#000000",x:"13",y:"25",width:"14",height:"2"}))),textalignright:wp.element.createElement("svg",{width:"40px",height:"40px",viewBox:"0 0 40 40",version:"1.1"},wp.element.createElement("g",{stroke:"none","stroke-width":"1",fill:"none","fill-rule":"evenodd"},wp.element.createElement("rect",{fill:"#000000",x:"13",y:"13",width:"14",height:"2"}),wp.element.createElement("rect",{fill:"#000000",x:"18",y:"16",width:"9",height:"2"}),wp.element.createElement("rect",{fill:"#000000",x:"14",y:"19",width:"13",height:"2"}),wp.element.createElement("rect",{fill:"#000000",x:"18",y:"22",width:"9",height:"2"}),wp.element.createElement("rect",{fill:"#000000",x:"13",y:"25",width:"14",height:"2"}))),textalignjustify:wp.element.createElement("svg",{width:"40px",height:"40px",viewBox:"0 0 40 40",zoomAndPan:"1.5",version:"1.1"},wp.element.createElement("g",{stroke:"none","stroke-width":"1",fill:"none","fill-rule":"evenodd"},wp.element.createElement("rect",{fill:"#000000",x:"13",y:"13",width:"14",height:"2"}),wp.element.createElement("rect",{fill:"#000000",x:"13",y:"16",width:"14",height:"2"}),wp.element.createElement("rect",{fill:"#000000",x:"13",y:"19",width:"14",height:"2"}),wp.element.createElement("rect",{fill:"#000000",x:"13",y:"22",width:"14",height:"2"}),wp.element.createElement("rect",{fill:"#000000",x:"13",y:"25",width:"14",height:"2"}))),productLayout3Columns:wp.element.createElement("svg",{width:"40px",height:"40px",viewBox:"0 0 40 40",version:"1.1"},wp.element.createElement("g",{stroke:"none","stroke-width":"1",fill:"none","fill-rule":"evenodd"},wp.element.createElement("rect",{fill:"#000000",transform:"translate(13.000000, 19.500000) rotate(-270.000000) translate(-13.000000, -19.500000) ",x:"3.5",y:"16.5",width:"19",height:"6",rx:"1"}),wp.element.createElement("rect",{fill:"#000000",x:"18",y:"10",width:"5",height:"19"}),wp.element.createElement("rect",{fill:"#000000",x:"25",y:"10",width:"5",height:"8"}),wp.element.createElement("rect",{fill:"#000000",x:"25",y:"19",width:"5",height:"10"}))),productLayout2ColumnsLeft:wp.element.createElement("svg",{width:"40px",height:"40px",viewBox:"0 0 40 40",version:"1.1"},wp.element.createElement("g",{stroke:"none","stroke-width":"1",fill:"none","fill-rule":"evenodd"},wp.element.createElement("rect",{fill:"#000000",x:"17",y:"10",width:"13",height:"19",rx:"1"}),wp.element.createElement("rect",{fill:"#000000",x:"10",y:"10",width:"5",height:"5"}),wp.element.createElement("rect",{fill:"#000000",x:"10",y:"17",width:"5",height:"12"}))),productLayout2ColumnsRight:wp.element.createElement("svg",{width:"40px",height:"40px",viewBox:"0 0 40 40",version:"1.1"},wp.element.createElement("g",{stroke:"none","stroke-width":"1",fill:"none","fill-rule":"evenodd"},wp.element.createElement("g",{transform:"translate(10.000000, 10.000000)",fill:"#000000"},wp.element.createElement("rect",{x:"0",y:"0",width:"13",height:"19",rx:"1"}),wp.element.createElement("rect",{x:"15",y:"0",width:"5",height:"5"}),wp.element.createElement("rect",{x:"15",y:"7",width:"5",height:"12"})))),productLayout2ColumnsBottom:wp.element.createElement("svg",{width:"40px",height:"40px",viewBox:"0 0 40 40",version:"1.1"},wp.element.createElement("g",{stroke:"none","stroke-width":"1",fill:"none","fill-rule":"evenodd"},wp.element.createElement("g",{transform:"translate(10.000000, 10.000000)",fill:"#000000"},wp.element.createElement("rect",{x:"0",y:"0",width:"13",height:"12",rx:"1"}),wp.element.createElement("rect",{x:"15",y:"0",width:"5",height:"12"}),wp.element.createElement("rect",{x:"0",y:"14",width:"20",height:"5"})))),galleryLayoutHorizontal:wp.element.createElement("svg",{width:"40px",height:"40px",viewBox:"0 0 40 40",version:"1.1"},wp.element.createElement("g",{stroke:"none","stroke-width":"1",fill:"none","fill-rule":"evenodd"},wp.element.createElement("g",{transform:"translate(20.000000, 20.500000) rotate(-180.000000) translate(-20.000000, -20.500000) translate(10.000000, 11.000000)",fill:"#000000","fill-rule":"nonzero"},wp.element.createElement("rect",{x:"0",y:"0",width:"13",height:"19",rx:"1"}),wp.element.createElement("rect",{x:"15",y:"0",width:"5",height:"6"}),wp.element.createElement("rect",{x:"15",y:"14",width:"5",height:"5"}),wp.element.createElement("rect",{x:"15",y:"7",width:"5",height:"6"})))),galleryLayoutVertical:wp.element.createElement("svg",{width:"40px",height:"40px",viewBox:"0 0 40 40",version:"1.1"},wp.element.createElement("g",{stroke:"none","stroke-width":"1",fill:"none","fill-rule":"evenodd"},wp.element.createElement("g",{transform:"translate(19.500000, 20.000000) rotate(-270.000000) translate(-19.500000, -20.000000) translate(9.500000, 10.500000)",fill:"#000000","fill-rule":"nonzero"},wp.element.createElement("rect",{x:"0",y:"-1.13686838e-13",width:"13",height:"19",rx:"1"}),wp.element.createElement("rect",{x:"15",y:"-1.13686838e-13",width:"5",height:"6"}),wp.element.createElement("rect",{x:"15",y:"7",width:"5",height:"5"}),wp.element.createElement("rect",{x:"15",y:"13",width:"5",height:"6"})))),galleryLayoutFeed:wp.element.createElement("svg",{width:"40px",height:"40px",viewBox:"0 0 40 40",version:"1.1"},wp.element.createElement("g",{stroke:"none","stroke-width":"1",fill:"none","fill-rule":"evenodd"},wp.element.createElement("g",{transform:"translate(20.500000, 12.500000) rotate(-270.000000) translate(-20.500000, -12.500000) translate(14.000000, 3.000000)",fill:"#000000","fill-rule":"nonzero"},wp.element.createElement("rect",{x:"0",y:"0",width:"13",height:"19",rx:"1"})),wp.element.createElement("g",{transform:"translate(20.500000, 27.500000) rotate(-270.000000) translate(-20.500000, -27.500000) translate(14.000000, 18.000000)",fill:"#000000","fill-rule":"nonzero"},wp.element.createElement("rect",{x:"0",y:"0",width:"13",height:"19",rx:"1"}))))}},function(e,t,n){"use strict";function r(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}var l=n(6),o=(n.n(l),n(7)),i=(n.n(o),wp.i18n),a=i.__,c=i._x,s=wp.blocks,p=(s.BlockControls,s.registerBlockType),m=wp.editor,u=m.InspectorControls,d=(m.AlignmentToolbar,m.withColors,wp.components),w=d.PanelBody,g=d.PanelRow,h=d.ToggleControl;d.ButtonGroup,d.Button,d.IconButton,d.BaseControl,d.Toolbar,d.ColorPalette,d.ColorIndicator,wp.compose.withState,wp.element.Fragment;p("ecwid/product-block",{title:EcwidGutenbergParams.productBlockTitle,icon:wp.element.createElement("svg",{className:"dashicon",viewBox:"0 0 20 20",width:"20",height:"20"},wp.element.createElement("path",{d:EcwidGutenbergParams.storeIcon})),category:"common",attributes:{id:{type:"integer"},show_picture:{type:"boolean"},show_title:{type:"boolean"},show_price:{type:"boolean"},show_options:{type:"boolean"},show_qty:{type:"boolean"},show_addtobag:{type:"boolean"},show_price_on_button:{type:"boolean"},show_border:{type:"boolean"},center_align:{type:"boolean"}},description:a("Display product with a buy button","ecwid-shopping-cart"),supports:{customClassName:!1,className:!1,html:!1,isPrivate:!EcwidGutenbergParams.isApiAvailable},edit:function(e){function t(e,t,n){return wp.element.createElement(h,{label:n,checked:e.attributes[t],onChange:function(){return e.setAttributes(r({},t,!e.attributes[t]))}})}function n(e){ecwid_open_product_popup({saveCallback:o,props:e})}var l=e.attributes,o=function(e){var t={id:e.newProps.id};EcwidGutenbergParams.products[e.newProps.id]={name:e.newProps.product.name,imageUrl:e.newProps.product.thumb},e.originalProps.setAttributes(t)};return[wp.element.createElement("div",{className:"ec-store-block"},wp.element.createElement("div",{className:"ec-store-block-header"},wp.element.createElement("svg",{className:"dashicon",viewBox:"0 0 20 20",width:"20",height:"20"},wp.element.createElement("path",{d:EcwidGutenbergParams.productIcon})),EcwidGutenbergParams.yourProductLabel),EcwidGutenbergParams.products&&l.id&&EcwidGutenbergParams.products[l.id]&&wp.element.createElement("div",{className:"ec-store-block-image"},wp.element.createElement("img",{src:EcwidGutenbergParams.products[l.id].imageUrl})),EcwidGutenbergParams.products&&l.id&&EcwidGutenbergParams.products[l.id]&&wp.element.createElement("div",{className:"ec-store-block-title"},EcwidGutenbergParams.products[l.id].name)),wp.element.createElement(u,null,wp.element.createElement(g,null,wp.element.createElement("label",{className:"ec-store-inspector-subheader"},a("Displayed product","ecwid-shopping-cart"))),l.id&&wp.element.createElement("div",{className:"ec-store-inspector-row"},EcwidGutenbergParams.products&&EcwidGutenbergParams.products[l.id]&&wp.element.createElement("label",null,EcwidGutenbergParams.products[l.id].name),wp.element.createElement("button",{onClick:function(){return n(e)}},a("Change","ecwid-shopping-cart"))),!l.id&&wp.element.createElement("div",{className:"ec-store-inspector-row"},wp.element.createElement("button",{onClick:function(){return n(e)}},a("Change","ecwid-shopping-cart"))),wp.element.createElement("br",null),wp.element.createElement(w,{title:c("Content","gutenberg-product-block","ecwid-shopping-cart"),initialOpen:!1},t(e,"show_picture",a("Picture","ecwid-shopping-cart")),t(e,"show_title",a("Title","ecwid-shopping-cart")),t(e,"show_price",a("Price","ecwid-shopping-cart")),t(e,"show_options",a("Options","ecwid-shopping-cart")),t(e,"show_qty",a("Quantity","ecwid-shopping-cart")),t(e,"show_addtobag",a("\xabBuy now\xbb button","ecwid-shopping-cart"))),wp.element.createElement(w,{title:a("Appearance","ecwid-shopping-cart"),initialOpen:!1},t(e,"show_price_on_button",a("Show price inside the \xabBuy now\xbb button","ecwid-shopping-cart")),t(e,"show_border",a("Add border","ecwid-shopping-cart")),t(e,"center_align",a("Center align on a page","ecwid-shopping-cart"))))]},save:function(e){return!1}})},function(e,t){},function(e,t){}]);