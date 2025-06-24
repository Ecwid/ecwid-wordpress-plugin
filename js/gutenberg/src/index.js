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

import { EcwidIcons } from './includes/icons.js';
wp.blocks.updateCategory('ec-store', { icon: EcwidIcons.ecwid });

import './store/block.jsx';
import './product/block.jsx';
import './buynow/block.jsx';
import './search/block.jsx';
import './categories/block.jsx';
import './minicart/block.jsx';
import './category-page/block.jsx';
import './product-page/block.jsx';
import './filters-page/block.jsx';
import './cart-page/block.jsx';